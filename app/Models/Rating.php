<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'user_id',
        'stars',
        'comment',
        'product_course_id'
    ];

    protected function casts(): array
    {
        return [
            'stars' => 'integer',
        ];
    }

    /**
     * Get the lesson that owns this rating.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get the user that owns this rating.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
