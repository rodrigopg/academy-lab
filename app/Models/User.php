<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'role_id',
        'name',
        'email',
        'password',
        'avatar',
        'document',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    /**
     * Get the role that owns the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the products that the user has access to.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot('starts_at', 'expires_at', 'status')
            ->withTimestamps();
    }

    /**
     * Get the lesson statuses for the user.
     */
    public function lessonStatuses(): HasMany
    {
        return $this->hasMany(LessonStatus::class);
    }

    /**
     * Get the ratings given by the user.
     */
    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get the comments made by the user.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
