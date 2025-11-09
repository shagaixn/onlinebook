<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Author;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        $query = Author::query();       
        if ($q = $request->input('q')) {
            $query->where('name', 'like', "%{$q}%")->orWhere('bio', 'like', "%{$q}%");
        }
        
        return view('layouts.authors.index');
    }
}