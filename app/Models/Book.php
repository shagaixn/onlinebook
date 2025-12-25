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

    // Relationship with authors table
    public function author()
    {
        return $this->belongsTo(\App\Models\Author::class, 'author_id');
    }

    // Alias for backward compatibility
    public function authorModel()
    {
        return $this->author();
    }

    // Book дээрх харуулах зориулалттай нэр: эхлээд books.author (string) байвал түүнийг харуулна,
    // үгүй бол author->name (хэрвээ заагдсан бол) - ийг харуулна.
    public function getAuthorDisplayAttribute()
    {
        // books.author (string) гэж хадгалсан нэрийг тэргүүнд харуулна
        if (!empty($this->attributes['author'])) {
            return $this->attributes['author'];
        }

        // эсвэл existing author relation-аас нэр авна
        return $this->author?->name;
    }

    // Хэрвээ та category_id ашиглаж байгаа бол:
    public function categoryModel()
    {
        return $this->belongsTo(\App\Models\BookCategory::class, 'category_id');
    }
}