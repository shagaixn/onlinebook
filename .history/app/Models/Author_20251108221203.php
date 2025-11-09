<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'avatar',
        'birth_date',
        'birth_place',
        'bio',
        'notable_works',
        'managed_by',
        'position',




        'country',
        'social_links',
        'meta',
    ];

    protected $casts = [
        'notable_works' => 'array',
        'social_links' => 'array',
        'meta' => 'array',
        'is_active' => 'boolean',
        'birth_date' => 'date',
    ];

    public function manager()
    {
        return $this->belongsTo(\App\Models\User::class, 'managed_by');
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
    public function books()
    {
    return $this->hasMany(Book::class);
    }
}

