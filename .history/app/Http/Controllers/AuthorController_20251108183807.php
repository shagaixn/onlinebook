<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthorController extends Controller
{
    public function index()
    {
        // Голлогдох болзол: role == 'author' эсвэл is_author == 1
        $authors = User::where('role', 'author')
                        ->orWhere('is_author', 1)
                        ->get();

        return view('authors.index', compact('authors'));
    }
}
