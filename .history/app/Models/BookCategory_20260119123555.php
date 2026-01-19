<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookCategory extends Model
{
    use HasFactory;

    protected $table = 'book_categories';

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_book_category', 'book_category_id', 'book_id')
                    ->withTimestamps();
    }
    // Keep the old single-category relationship for backward compatibility
    public function booksWithSingleCategory()
    {
        return $this->hasMany(Book::class, 'category_id');
    }
}