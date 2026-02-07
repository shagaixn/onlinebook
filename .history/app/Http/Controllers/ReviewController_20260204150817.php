<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user already reviewed this book
        $existingReview = Review::where('user_id', Auth::id())
            ->where('book_id', $validated['book_id'])
            ->first();

        if ($existingReview) {
            // Update existing review
            $existingReview->update([
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]);
            
            return redirect()->back()->with('success', 'Үнэлгээ амжилттай шинэчлэгдлээ!');
        } else {
            // Create new review
            Review::create([
                'user_id' => Auth::id(),
                'book_id' => $validated['book_id'],
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
            ]);
            
            return redirect()->back()->with('success', 'Үнэлгээ амжилттай нэмэгдлээ!');
        }
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        
        // Only allow user to delete their own review
        if ($review->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Та зөвхөн өөрийн үнэлгээг устгах боломжтой.');
        }

        $review->delete();
        
        return redirect()->back()->with('success', 'Үнэлгээ амжилттай устгагдлаа!');
    }
}
