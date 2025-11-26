<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'user_id',
        'parent_id',
        'content',
        'status',
        'read_at',
        'product_track_course_id',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    /**
     * Get the lesson that owns this comment.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get the user that owns this comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Get the child comments (replies).
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function repliesRecursive(): HasMany
    {
        return $this->replies()->with(['user', 'repliesRecursive']);
    }

}
