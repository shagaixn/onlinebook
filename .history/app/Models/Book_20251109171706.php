<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'books';

    protected $fillable = [
        'title',
        'author_id',
        'author',
        'category',
        'category_id',
        'price',
        'pages',
        'description',
        'published_date',
        'cover_image',
    ];

    // relations
    public function authorModel()
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    public function categoryModel()
    {
        return $this->belongsTo(BookCategory::class, 'category_id');
    }
}