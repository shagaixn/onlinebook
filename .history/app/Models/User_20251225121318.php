<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'profile_image',
        'bio',
        'phone',
        'address',
        'age',
        'gender',
        'interests',
        'provider',
        'provider_id',
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
            'email_verified_at' => 'datetime',
            'is_author' => 'boolean',
            'age' => 'integer',
        ];
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isAuthor(): bool
    {
        return $this->is_author || $this->role === 'author';
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class);
    }

    public function wishlistBooks()
    {
        return $this->belongsToMany(Book::class, 'wishlists', 'user_id', 'book_id')->withTimestamps();
    }
}
