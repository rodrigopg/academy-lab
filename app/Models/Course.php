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

    public function productCourses(): HasMany
    {
        return $this->hasMany(ProductCourse::class);
    }

    /**
     * Get products that have this course.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_course')
            ->withPivot('position', 'visibility')
            ->withTimestamps()
            ->orderByPivot('position');
    }

    /**
     * Get tracks that have this course.
     */
    public function tracks(): BelongsToMany
    {
        return $this->belongsToMany(Track::class, 'track_course')
            ->withPivot('position', 'visibility')
            ->withTimestamps()
            ->orderByPivot('position');
    }

    public function modules(): HasMany
    {
        return $this->hasMany(Module::class)->orderBy('position');
    }

    public function getProductCourseId(int $product_id)
    {
        $cacheKey = sprintf('product-%d-course-%d', $product_id, $this->id);

        return Cache::rememberForever($cacheKey, fn () => DB::table('product_course as pc')
            ->where('pc.product_id', $product_id)
            ->where('pc.course_id', $this->id)
            ->where('pc.visibility', 'visible')
            ->orderBy('pc.position')
            ->value('pc.id'));
    }
}
