<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrackCourse extends Model
{
    use HasFactory;

    protected $table = 'track_course';

    protected $fillable = [
        'track_id',
        'course_id',
        'position',
        'visibility',
    ];

    protected function casts(): array
    {
        return [
            'position' => 'integer',
        ];
    }

    public function track(): BelongsTo
    {
        return $this->belongsTo(Track::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
