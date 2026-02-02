<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user already reviewed this book
        $existingReview = Review::where('user_id', Auth::id())
            ->where('book_id', $request->book_id)
            ->first();

        if ($existingReview) {
            // Update existing review
            $existingReview->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            $message = 'Үнэлгээ амжилттай шинэчлэгдлээ.';
        } else {
            // Create new review
            Review::create([
                'user_id' => Auth::id(),
                'book_id' => $request->book_id,
                'rating' => $request->rating,
                'comment' => $request->comment,
            ]);
            $message = 'Үнэлгээ амжилттай нэмэгдлээ.';
        }

        return back()->with('success', $message);
    }
}
