<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductTrackCourse extends Model
{
    use HasFactory;

    protected $table = 'product_track_course';

    protected $fillable = [
        'product_id',
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

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
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
