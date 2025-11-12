<?php

namespace App\Http\Controllers;

use App\Models\Home;
use App\Models\Book;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // resources/views/pages/HomePage.blade.php-ийг дуудаж байна
        return view('pages.HomePage');
    }
    public function book()
    {
        $books = Book::all(); // эсвэл Book::all() гэх мэт
        return view('pages.Book', compact('books'));
    }
    public function home()
    {
        return view('HomePage');
    }

    public function service()
    {
    return view('pages.service');
    }

    public function subscription()
    {
        return view('pages.subscription');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function artex()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Home $home)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Home $home)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Home $home)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Home $home)
    {
        //
    }
}
