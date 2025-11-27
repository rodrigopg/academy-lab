<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar o usuário member
        $member = User::where('email', 'member@teste.com')->first();

        if (!$member) {
            $this->command->warn('⚠️  Usuário member@teste.com não encontrado. Pulando ProductUserSeeder.');
            return;
        }

        // Buscar produtos
        $products = Product::all();

        if ($products->isEmpty()) {
            $this->command->warn('⚠️  Nenhum produto encontrado. Pulando ProductUserSeeder.');
            return;
        }

        // Vincular produtos ao usuário member
        $enrollments = [
            [
                'product_id' => $products->where('slug', 'formacao-advpl-completa')->first()?->id,
                'user_id' => $member->id,
                'starts_at' => now(),
                'expires_at' => now()->addDays(180),
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $products->where('slug', 'consultor-protheus-essencial')->first()?->id,
                'user_id' => $member->id,
                'starts_at' => now()->subDays(15),
                'expires_at' => now()->addDays(210),
                'status' => 'active',
                'created_at' => now()->subDays(15),
                'updated_at' => now(),
            ],
            [
                'product_id' => $products->where('slug', 'sql-para-protheus')->first()?->id,
                'user_id' => $member->id,
                'starts_at' => now()->subDays(200),
                'expires_at' => now()->subDays(15),
                'status' => 'active', // Expirado mas ainda ativo (pode ser mostrado como expirado na UI)
                'created_at' => now()->subDays(200),
                'updated_at' => now(),
            ],
        ];

        // Filtrar apenas os que têm product_id válido
        $validEnrollments = collect($enrollments)->filter(fn($e) => $e['product_id'] !== null)->toArray();

        if (!empty($validEnrollments)) {
            DB::table('product_user')->insert($validEnrollments);
            $this->command->info('✅ ' . count($validEnrollments) . ' vínculos de produtos criados para o usuário member');
        }

        // Criar alguns lesson_statuses para simular progresso
        $this->createLessonProgress($member);
    }

    /**
     * Criar progresso de aulas para o usuário
     */
    protected function createLessonProgress(User $user): void
    {
        // Buscar produtos do usuário
        $userProducts = $user->products()->with('productCourses.course.modules.lessons')->get();

        $progressCount = 0;

        foreach ($userProducts as $product) {
            // Para o primeiro produto, marcar algumas aulas como concluídas (38% de progresso)
            if ($product->slug === 'formacao-advpl-completa') {
                $allLessons = $product->productCourses
                    ->flatMap(fn($pc) => $pc->course->modules->flatMap(fn($m) => $m->lessons))
                    ->take(30); // Pegar as primeiras 30 aulas

                foreach ($allLessons->take(11) as $index => $lesson) { // Completar ~38% (11 de 30)
                    DB::table('lesson_statuses')->insert([
                        'lesson_id' => $lesson->id,
                        'user_id' => $user->id,
                        'product_course_id' => $product->productCourses->first()->id,
                        'started_at' => now()->subDays(30 - $index),
                        'completed_at' => now()->subDays(29 - $index),
                        'created_at' => now()->subDays(30 - $index),
                        'updated_at' => now()->subDays(29 - $index),
                    ]);
                    $progressCount++;
                }
            }

            // Para o segundo produto, marcar algumas aulas (12% de progresso)
            if ($product->slug === 'consultor-protheus-essencial') {
                $allLessons = $product->productCourses
                    ->flatMap(fn($pc) => $pc->course->modules->flatMap(fn($m) => $m->lessons))
                    ->take(20);

                foreach ($allLessons->take(2) as $index => $lesson) { // ~12%
                    DB::table('lesson_statuses')->insert([
                        'lesson_id' => $lesson->id,
                        'user_id' => $user->id,
                        'product_course_id' => $product->productCourses->first()->id,
                        'started_at' => now()->subDays(10 - $index),
                        'completed_at' => now()->subDays(9 - $index),
                        'created_at' => now()->subDays(10 - $index),
                        'updated_at' => now()->subDays(9 - $index),
                    ]);
                    $progressCount++;
                }
            }

            // Para o terceiro produto, marcar quase todas as aulas (95% de progresso)
            if ($product->slug === 'sql-para-protheus') {
                $allLessons = $product->productCourses
                    ->flatMap(fn($pc) => $pc->course->modules->flatMap(fn($m) => $m->lessons))
                    ->take(20);

                foreach ($allLessons->take(19) as $index => $lesson) { // 95%
                    DB::table('lesson_statuses')->insert([
                        'lesson_id' => $lesson->id,
                        'user_id' => $user->id,
                        'product_course_id' => $product->productCourses->first()->id,
                        'started_at' => now()->subDays(190 - ($index * 5)),
                        'completed_at' => now()->subDays(189 - ($index * 5)),
                        'created_at' => now()->subDays(190 - ($index * 5)),
                        'updated_at' => now()->subDays(189 - ($index * 5)),
                    ]);
                    $progressCount++;
                }
            }
        }

        if ($progressCount > 0) {
            $this->command->info('✅ ' . $progressCount . ' registros de progresso de aulas criados');
        }
    }
}

