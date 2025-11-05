<?php

namespace App\Http\Controllers;

use App\Models\dashboard;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        public function index()
    {
    $categories = \App\Models\BookCategory::withCount('books')->get();

    $categoryLabels = $categories->pluck('name');
    $categoryCounts = $categories->pluck('books_count');

    return view('layouts.admin', [
        'categoryLabels' => $categoryLabels,
        'categoryCounts' => $categoryCounts,
    ]);
    }
    }

    /**
     * Show the form for creating a new resource.
     */

    public function bookChart()
    {
        return view('layouts.admin.bookchart');
    }

    public function create()
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
    public function show(dashboard $dashboard)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(dashboard $dashboard)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, dashboard $dashboard)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(dashboard $dashboard)
    {
        //
    }
}
