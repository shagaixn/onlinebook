<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Book;
use App\Models\Author;
use Illuminate\Support\Str;

class LinkBooksToAuthors extends Command
{
    protected $signature = 'books:link-authors';
    protected $description = 'Link existing books to authors based on author name matching';

    public function handle()
    {
        $this->info('Starting to link books to authors...');
        
        $booksWithoutAuthorId = Book::whereNull('author_id')
            ->whereNotNull('author')
            ->where('author', '!=', '')
            ->get();

        $this->info('Found ' . $booksWithoutAuthorId->count() . ' books without author_id but with author names');

        $linkedCount = 0;
        $createdAuthorsCount = 0;

        foreach ($booksWithoutAuthorId as $book) {
            $authorName = trim($book->author);
            
            if (empty($authorName)) {
                continue;
            }

            // Try to find existing author by name or slug
            $author = Author::where('name', $authorName)
                ->orWhere('slug', Str::slug($authorName))
                ->first();

            if (!$author) {
                // Create new author if doesn't exist
                $author = Author::create([
                    'name' => $authorName,
                    'slug' => $this->generateUniqueSlug($authorName),
                ]);
                $createdAuthorsCount++;
                $this->line("Created new author: {$authorName}");
            }

            // Link book to author
            $book->author_id = $author->id;
            $book->save();
            $linkedCount++;
            
            $this->line("Linked book '{$book->title}' to author '{$author->name}'");
        }

        $this->info("Process completed!");
        $this->info("Created {$createdAuthorsCount} new authors");
        $this->info("Linked {$linkedCount} books to authors");
    }

    private function generateUniqueSlug($name)
    {
        $base = Str::slug($name ?: Str::random(8));
        $slug = $base;
        $i = 1;

        while (Author::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}