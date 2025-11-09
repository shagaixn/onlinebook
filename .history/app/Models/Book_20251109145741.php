<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'author_id',
        'title',
        'category',    // хэрэв та category-г стрингээр ашиглавал
        'category_id', // эсвэл category_id ашиглаж байгаа бол
        'cover_image',
        'published_date',
        'price',
        'pages',
        'description',
    ];

    protected $casts = [
        'published_date' => 'date',
    ];

    // Зохиолч руу харилцаа
    public function author()
    {
        return $this->belongsTo(\App\Models\Author::class, 'author_id');
    }

    // Хэрэв та book_categories хүснэгтийг ашиглаж байгаа бол
    public function categoryModel()
    {
        return $this->belongsTo(\App\Models\BookCategory::class, 'category_id');
    }
}