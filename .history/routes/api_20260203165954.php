<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// Health check endpoint
Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'message' => 'API is working!']);
});