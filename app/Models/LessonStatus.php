<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'user_id',
        'completed_at',
        'started_at',
        'product_course_id'
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
        ];
    }

    /**
     * Get the lesson that owns this status.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get the user that owns this status.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
