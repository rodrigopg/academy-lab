<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Track extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];


    /**
     * Get the products that include this track.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_track')
            ->withPivot('position', 'visibility')
            ->withTimestamps()
            ->orderByPivot('position');
    }

    public function productTracks(): HasMany
    {
        return $this->hasMany(ProductTrack::class);
    }

    public function trackCourses(): HasMany
    {
        return $this->hasMany(TrackCourse::class);
    }
}
