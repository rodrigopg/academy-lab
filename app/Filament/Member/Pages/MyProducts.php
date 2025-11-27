<?php

namespace App\Filament\Member\Pages;

use App\Models\Product;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class MyProducts extends Page
{
    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-academic-cap';

    protected static ?string $navigationLabel = 'Meus Produtos';

    protected static ?int $navigationSort = 1;

    protected string $view = 'filament.member.pages.my-products';

    protected static ?string $title = 'Meus Produtos';

    // Propriedades públicas para Livewire
    public string $search = '';

    public string $statusFilter = 'all';

    public string $sortBy = 'name';

    // Computed properties
    public function getProductsProperty(): Collection
    {
        $userId = auth()->id();

        return Product::query()
            ->whereHas('users', function (Builder $query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->with([
                'users' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                },
                'productCourses.course.modules.lessons.lessonStatuses' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                },
                'productTracks.track.trackCourses.course.modules.lessons.lessonStatuses' => function ($query) use ($userId) {
                    $query->where('user_id', $userId);
                },
            ])
            ->when($this->search, function (Builder $query) {
                $query->where(function (Builder $q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('description', 'like', "%{$this->search}%");
                });
            })
            ->when($this->statusFilter !== 'all', function (Builder $query) use ($userId) {
                $query->whereHas('users', function (Builder $q) use ($userId) {
                    $q->where('user_id', $userId)
                        ->where('status', $this->statusFilter);
                });
            })
            ->when($this->sortBy === 'name', fn(Builder $q) => $q->orderBy('name'))
            ->when($this->sortBy === 'recent', fn(Builder $q) => $q->latest())
            ->when($this->sortBy === 'progress', function (Builder $q) use ($userId) {
                // Ordenar por progresso (isso requer uma subquery mais complexa)
                $q->withCount([
                    'productCourses as total_lessons' => function ($query) {
                        $query->join('courses', 'product_course.course_id', '=', 'courses.id')
                            ->join('modules', 'courses.id', '=', 'modules.course_id')
                            ->join('lessons', 'modules.id', '=', 'lessons.module_id')
                            ->selectRaw('count(distinct lessons.id)');
                    }
                ])->orderByDesc('total_lessons');
            })
            ->get()
            ->map(function ($product) use ($userId) {
                // Adicionar dados calculados ao produto
                $pivot = $product->users->first()?->pivot;

                $product->user_status = $pivot?->status ?? 'unknown';
                $product->starts_at = $pivot?->starts_at;
                $product->expires_at = $pivot?->expires_at;

                // Calcular estatísticas
                $stats = $this->calculateProductStats($product, $userId);
                $product->stats = $stats;

                return $product;
            });
    }

    protected function calculateProductStats(Product $product, int $userId): array
    {
        $totalLessons = 0;
        $completedLessons = 0;
        $totalDuration = 0;
        $watchedDuration = 0;

        // Contar aulas e duração dos cursos diretos (product_course)
        foreach ($product->productCourses as $productCourse) {
            $course = $productCourse->course;

            foreach ($course->modules as $module) {
                foreach ($module->lessons as $lesson) {
                    $totalLessons++;
                    $totalDuration += $lesson->duration ?? 0;

                    // Verificar se o usuário completou a aula
                    $status = $lesson->lessonStatuses
                    ->where('product_course_id', $productCourse->id)
                    ->first();
                    
                    if ($status && $status->completed_at) {
                        $completedLessons++;
                    $watchedDuration += $lesson->duration ?? 0;
                    }
                }
            }
        }

        // Contar aulas e duração das trilhas (product_track → track_course)
        foreach ($product->productTracks as $productTrack) {
            $track = $productTrack->track;

            foreach ($track->trackCourses as $trackCourse) {
                $course = $trackCourse->course;

                foreach ($course->modules as $module) {
                    foreach ($module->lessons as $lesson) {
                        $totalLessons++;
                        $totalDuration += $lesson->duration ?? 0;

                        // Para trilhas, verificamos se há product_course_id relacionado
                        $productCourseId = $product->productCourses()
                            ->where('course_id', $course->id)
                            ->value('id');

                        $status = $lesson->lessonStatuses
                        ->where('product_course_id', $productCourseId)
                        ->first();
                        
                        if ($status && $status->completed_at) {
                            $completedLessons++;
                        $watchedDuration += $lesson->duration ?? 0;
                        }
                    }
                }
            }
        }

        $progressPercentage = $totalLessons > 0
            ? round(($completedLessons / $totalLessons) * 100)
            : 0;

        return [
            'total_lessons' => $totalLessons,
            'completed_lessons' => $completedLessons,
            'progress_percentage' => $progressPercentage,
            'total_duration' => $totalDuration,
            'watched_duration' => $watchedDuration,
            'total_courses' => $product->productCourses->count() +
                               $product->productTracks->sum(fn($pt) => $pt->track->trackCourses->count()),
        ];
    }

    public function updatedSearch(): void
    {
        // Livewire irá automaticamente recarregar getProductsProperty()
    }

    public function updatedStatusFilter(): void
    {
        // Livewire irá automaticamente recarregar getProductsProperty()
    }

    public function updatedSortBy(): void
    {
        // Livewire irá automaticamente recarregar getProductsProperty()
    }

    protected function formatDuration(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        if ($hours > 0) {
            return "{$hours}h " . ($minutes > 0 ? "{$minutes}min" : "");
        }

        return "{$minutes}min";
    }

    protected function getDaysRemaining($expiresAt): ?int
    {
        if (!$expiresAt) {
            return null;
        }

        $expires = \Carbon\Carbon::parse($expiresAt);
        $now = now();

        if ($expires->isPast()) {
            return 0;
        }

        return $now->diffInDays($expires);
    }

    protected function getStatusBadgeColor(string $status): string
    {
        return match($status) {
            'active' => 'success',
            'suspended' => 'warning',
            'canceled' => 'danger',
            default => 'gray',
        };
    }

    protected function getStatusLabel(string $status): string
    {
        return match($status) {
            'active' => 'Ativo',
            'suspended' => 'Suspenso',
            'canceled' => 'Cancelado',
            default => 'Desconhecido',
        };
    }
}
