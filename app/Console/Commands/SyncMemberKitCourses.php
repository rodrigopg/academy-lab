<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonMaterial;
use App\Models\Module;
use App\Models\ProductTrack;
use App\Models\ProductTrackCourse;
use App\Models\TrackCourse;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SyncMemberKitCourses extends Command
{
    protected $signature = 'app:sync-member-kit-courses';
    protected $description = 'Sincroniza cursos da MemberKit com paginação, pacing e barras de progresso';

    /** Pacing por processo p/ MemberKit (evitar >120 req/min) */
    private float $lastMemberkitCallAt = 0.0;

    /** Retry config */
    private int $maxRetries = 10;
    private int $baseBackoffMs = 600;
    private int $minIntervalMs = 1000;

    private string $memberkitApiKey;
    private string $pandaApiKey;

    public function handle()
    {
        $this->memberkitApiKey = (string) config('memberkit.apikey');
        $this->pandaApiKey = (string) config('panda.apikey');

        $memberKitCategoryMap = [
            24510 => 1,
            45812 => 2,
            45813 => 3,
            24517 => 4,
            56980 => 5,
            15859 => 6,
        ];

        $productTracks = ProductTrack::all();

        // -----------------------------
        // 1) Paginação de /courses
        // -----------------------------
        $courses = $this->fetchAllCoursesPaginated();

        // Filtra categorias e ordena por position (sem requisição extra)
        $courses = $courses
            ->whereIn('category.id', array_keys($memberKitCategoryMap))
            ->sortBy('position')
            ->values();

        $courses = $courses->filter(function($course, $key) { return $key > 12; })->values();

        $this->info('---------------------------------------------');
        $this->info('Iniciando sincronização da MemberKit');
        $this->info('Cursos selecionados: ' . $courses->count());
        $this->info('---------------------------------------------');

        foreach ($courses as $idx => $course) {
            $this->newLine();
            $this->line(sprintf(
                '<info>[%d/%d]</info> Curso: <comment>%s</comment> (categoria #%s)',
                $idx + 1,
                $courses->count(),
                $course['name'],
                $course['category']['id'] ?? '—'
            ));

            // Baixa thumb (fora do rate da MemberKit)
            $thumb_path = '';
            if (!empty($course['image_url'])) {
                $thumb = Http::retry(3, 300)->get($course['image_url']);
                if ($thumb->successful()) {
                    $thumb_path = "courses/thumb_" . Str::uuid() . ".jpg";
                    Storage::put($thumb_path, $thumb->body());
                }
            }

            $path_duration = 0;

            $courseModel = Course::create([
                'name'        => $course['name'],
                'description' => $course['description'],
                'slug'        => Str::slug($course['name']),
                'cover'       => $thumb_path,
            ]);

            $trackIds = $productTracks
                ->where('track_id', $memberKitCategoryMap[$course['category']['id']] ?? null)
                ->pluck('id')
                ->toArray();

            if ($trackIds) {
                foreach ($productTracks->whereIn('id', $trackIds) as $productTrack) {
                    TrackCourse::firstOrCreate(
                        [
                            'track_id' => $productTrack->track_id,
                            'course_id' => $courseModel->id,
                        ],
                        [
                            'position' => $course['position'],
                            'visibility' => 'visible',
                        ]
                    );

                    ProductTrackCourse::updateOrCreate(
                        [
                            'product_id' => $productTrack->product_id,
                            'track_id' => $productTrack->track_id,
                            'course_id' => $courseModel->id,
                        ],
                        [
                            'position' => $course['position'],
                            'visibility' => 'visible',
                        ]
                    );
                }
            }

            // Detalhe do curso (seções/lessons)
            $course_member = $this->memberkitGet("/courses/{$course['id']}")->collect();

            // Calcula total de lessons do curso (sem requisição extra)
            $totalLessons = 0;
            foreach ($course_member['sections'] as $s) {
                $totalLessons += is_countable($s['lessons'] ?? null) ? count($s['lessons']) : 0;
            }

            $this->line('Seções: ' . count($course_member['sections']) . ' | Aulas: ' . $totalLessons);

            // Barra de progresso por curso (1 step por lesson)
            $bar = $this->output->createProgressBar(max(1, $totalLessons));
            $bar->setFormat(' %current%/%max% [%bar%] %percent:3s%% | %elapsed:6s% | %message%');
            $bar->setMessage($course['name']);
            $bar->start();

            foreach ($course_member['sections'] as $section) {
                $module = Module::create([
                    'course_id'     => $courseModel->id,
                    'name'        => $section['name'],
                    'description' => $section['description'],
                    'position'    => $section['position'],
                ]);

                $module_duration = 0;

                foreach ($section['lessons'] as $lesson) {
                    // Detalhe da lesson
                    $lesson_data = $this->memberkitGet("/courses/{$course['id']}/lessons/{$lesson['id']}")->collect();

                    // Panda (fora do rate da MemberKit)
                    $panda_video = collect();
                    if (!empty($lesson_data['video']['uid'])) {
                        $panda_video = Http::withHeaders([
                            'Authorization' => $this->pandaApiKey,
                            'accept'        => 'application/json',
                        ])
                            ->retry(10, 500)
                            ->get("https://api-v2.pandavideo.com.br/videos/{$lesson_data['video']['uid']}")
                            ->collect();
                    }

                    $lesson_created = Lesson::create([
                        'module_id'           => $module->id,
                        'panda_id'            => $lesson_data['video']['uid'] ?? null,
                        'panda_thumbnail_url' => $lesson_data['video']['image'] ?? null,
                        'panda_player_url'    => $panda_video['video_player'] ?? null,
                        'name'                => $lesson_data['title'],
                        'slug'                => Str::slug($lesson_data['title']),
                        'description'         => $lesson_data['content'],
                        'position'            => $lesson_data['position'],
                        'duration'            => $lesson_data['video']['duration'] ?? null,
                    ]);

                    $module_duration += $lesson_data['video']['duration'] ?? 0;

                    // Materiais da lesson — baixa a partir do próprio arquivo
                    if (!empty($lesson_data['files']) && is_array($lesson_data['files'])) {
                        foreach ($lesson_data['files'] as $file) {
                            $fileUrl = $file['download_url'] ?? $file['url'] ?? null;
                            if (!$fileUrl) {
                                continue;
                            }

                            $document = Http::retry(3, 500)->get($fileUrl);
                            if ($document->failed()) {
                                continue;
                            }

                            // Extensão pelo content-type (fallbacks)
                            $ext = '';
                            $ctype = $file['content_type'] ?? $document->header('Content-Type');
                            if ($ctype && str_contains($ctype, '/')) {
                                $ext = '.' . explode('/', $ctype)[1];
                            }
                            if (!$ext || strlen($ext) > 6) {
                                if (!empty($file['filename']) && str_contains($file['filename'], '.')) {
                                    $ext = '.' . pathinfo($file['filename'], PATHINFO_EXTENSION);
                                } else {
                                    $ext = '.bin';
                                }
                            }

                            $file_path = "lesson/file_" . Str::uuid() . $ext;
                            Storage::put($file_path, $document->body());

                            LessonMaterial::create([
                                'lesson_id'        => $lesson_created->id,
                                'material_type_id' => 1,
                                'title'            => $file['filename'] ?? basename($file_path),
                                'file'             => $file_path,
                                'position'         => 0,
                                'description'      => "",
                            ]);
                        }
                    }

                    // Avança 1 step por lesson
                    $bar->advance();
                }

                $module->duration = $module_duration;
                $module->save();
                $path_duration +=$module_duration;
            }

            $path->duration = $path_duration;
            $path->save();

            $bar->finish();
            $this->newLine(2);
            $this->line('<info>✓ Curso sincronizado:</info> ' . $course['name']);
        }

        $this->info('---------------------------------------------');
        $this->info('Sync finalizado com sucesso!');
        $this->info('---------------------------------------------');
    }

    /**
     * Busca todas as páginas de /courses.
     * - Usa ?page=1,2,3... até a API parar de retornar itens.
     * - Suporta resposta no formato array puro OU { data: [...] }.
     * - Inclui limite de segurança para evitar loop infinito caso a API ignore o parâmetro.
     */
    private function fetchAllCoursesPaginated()
    {
        $all = collect();
        $seenIds = [];
        $maxPages = 200; // guarda-chuva anti-loop
        $page = 1;

        while ($page <= $maxPages) {
            $resp = $this->memberkitGet("/courses?page={$page}");
            $payload = $resp->json();

            $items = [];
            if (is_array($payload)) {
                // Se vier com wrapper "data", usa data; senão assume que já é a lista
                if (array_key_exists('data', $payload) && is_array($payload['data'])) {
                    $items = $payload['data'];
                } else {
                    $items = $payload;
                }
            }

            // Nada retornado => fim
            if (empty($items)) {
                break;
            }

            // Evita duplicatas se a API ignorar paginação
            $newOnThisPage = 0;
            foreach ($items as $it) {
                $id = $it['id'] ?? null;
                if ($id === null) {
                    // sem id? concatena mesmo assim
                    $all->push($it);
                    $newOnThisPage++;
                    continue;
                }
                if (!isset($seenIds[$id])) {
                    $seenIds[$id] = true;
                    $all->push($it);
                    $newOnThisPage++;
                }
            }

            // Se não entrou nada novo, encerra para não ficar em loop
            if ($newOnThisPage === 0) {
                break;
            }

            $page++;
        }

        return $all;
    }

    /** GET com pacing (<=120 req/min) + retry/backoff para MemberKit */
    private function memberkitGet(string $path)
    {
        $base = 'https://memberkit.com.br/api/v1';
        $url = rtrim($base, '/') . '/' . ltrim($path, '/');
        $url = $url . (str_contains($url, '?') ? '&' : '?') . 'api_key=' . urlencode($this->memberkitApiKey);

        $attempt = 0;

        while (true) {
            $attempt++;

            // Pacing mínimo entre chamadas
            $now = microtime(true);
            $elapsedMs = ($now - $this->lastMemberkitCallAt) * 1000;
            if ($this->lastMemberkitCallAt > 0 && $elapsedMs < $this->minIntervalMs) {
                dump("VAI ESPERAR" . (($this->minIntervalMs - $elapsedMs) * 1000));
                usleep((int) (($this->minIntervalMs - $elapsedMs) * 1000));
            }
            $this->lastMemberkitCallAt = microtime(true);

            $resp = Http::timeout(20)->get($url);

            if ($resp->successful()) {
                return $resp;
            }

            if ($resp->status() === 429) {
                $retryAfter = (int) ($resp->header('Retry-After') ?? 0);
                $sleepMs = max($retryAfter * 1000, $this->backoffMs($attempt));
                usleep($sleepMs * 1000);
            } elseif ($resp->serverError() || $resp->status() === 0) {
                $sleepMs = $this->backoffMs($attempt);
                usleep($sleepMs * 1000);
            } else {
                $resp->throw();
            }

            if ($attempt >= $this->maxRetries) {
                $resp->throw();
            }
        }
    }

    /** Backoff exponencial com jitter (ms) */
    private function backoffMs(int $attempt): int
    {
        $ms = (int) ($this->baseBackoffMs * (2 ** max(0, $attempt - 1)));
        $jitter = (int) ($ms * (mt_rand(-20, 20) / 100));
        return max(300, $ms + $jitter);
    }
}
