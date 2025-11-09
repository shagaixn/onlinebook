<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


class AuthorController extends Controller
{
    public function index()
    {
        // Голлох болзол: role == 'author' эсвэл is_author == 1
        $authors = User::where(function ($q) {
                        $q->where('role', 'author')
                          ->orWhere('is_author', 1);
                    })
                    ->orderBy('name')
                    ->paginate(12);

        return view('authors.index', compact('authors'));
    }
}
