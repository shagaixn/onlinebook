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

    /**
     * Get the notable works as an array.
     */
    public function getNotableWorksArrayAttribute(): array
    {
        if (empty($this->notable_works)) {
            return [];
        }
        return array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $this->notable_works)));
    }

    /**
     * Get the awards as an array.
     */
    public function getAwardsArrayAttribute(): array
    {
        if (empty($this->awards)) {
            return [];
        }
        return array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $this->awards)));
    }

    /**
     * Get the count of notable works.
     */
    public function getNotableWorksCountAttribute(): int
    {
        return count($this->notable_works_array);
    }

    /**
     * Get the count of awards.
     */
    public function getAwardsCountAttribute(): int
    {
        return count($this->awards_array);
    }
}