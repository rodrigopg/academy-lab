<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Course extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'cover',
        'duration',
    ];

    protected function casts(): array
    {
        return [
            'position' => 'integer',
        ];
    }

    public function productTracks(): BelongsToMany
    {
        return $this->belongsToMany(Track::class, 'product_track_course', 'course_id', 'track_id')
            ->withPivot('product_id', 'position', 'visibility')
            ->withTimestamps()
            ->orderByPivot('position');
    }

    public function productTrackCourses(): HasMany
    {
        return $this->hasMany(ProductTrackCourse::class);
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class)->orderBy('position');
    }

    public function getProductTrackCourseId(int $product_id, int $track_id)
    {
        $cacheKey = sprintf('product-%d-track-%d-course-%d', $product_id, $track_id, $this->id);

        return Cache::rememberForever($cacheKey, fn () => DB::table('product_track_course as ptc')
            ->where('ptc.product_id', $product_id)
            ->where('ptc.track_id', $track_id)
            ->where('ptc.course_id', $this->id)
            ->where('ptc.visibility', 'visible')
            ->orderBy('ptc.position')
            ->value('ptc.id'));
    }
}
