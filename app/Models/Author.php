<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'nationality',
        'country',
        'birth_place',
        'position',
        'email',
        'social_links',
        'birth_date',
        'death_date',
        'profile_image',
        'biography',
        'awards',
        'notable_works',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'death_date' => 'date',
        'social_links' => 'array',
    ];
}