<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'author', // шинэ нэмсэн author нэр (string)
        'title',
        'category',
        'category_id',
        'cover_image',
        'published_date',
        'price',
        'pages',
        'description',
    ];

    protected $casts = [
        'published_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        
        // Automatically create category if it doesn't exist
        static::saving(function ($book) {
            if (!empty($book->category) && empty($book->category_id)) {
                $category = \App\Models\BookCategory::firstOrCreate([
                    'name' => $book->category
                ], [
                    'slug' => \Illuminate\Support\Str::slug($book->category)
                ]);
                $book->category_id = $category->id;
            }
        });
    }

    // Эх сурвалж authors хүснэгттэй харилцаа (хэрвээ та өмнөх author_id-ыг хадгалсан бол ашиглагдана)
    public function authorModel()
    {
        return $this->belongsTo(\App\Models\Author::class, 'author_id');
    }

    // Book дээрх харуулах зориулалттай нэр: эхлээд books.author (string) байвал түүнийг харуулна,
    // үгүй бол authorModel->name (хэрвээ заагдсан бол) - ийг харуулна.
    public function getAuthorDisplayAttribute()
    {
        // books.author (string) гэж хадгалсан нэрийг тэргүүнд харуулна
        if (!empty($this->author)) {
            return $this->author;
        }

        // эсвэл existing author relation-аас нэр авна
        return $this->authorModel?->name;
    }

    // Хэрвээ та category_id ашиглаж байгаа бол:
    public function categoryModel()
    {
        return $this->belongsTo(\App\Models\BookCategory::class, 'category_id');
    }
    
    // Many-to-many relationship with categories
    public function categories()
    {
        return $this->belongsToMany(\App\Models\BookCategory::class, 'book_book_category', 'book_id', 'book_category_id')
                    ->withTimestamps();
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class);
    }
}