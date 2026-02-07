<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $wishlistBooks = $user->wishlistBooks()->get();
        
        // Get reading history - books user has started reading
        $readingHistory = \App\Models\ReadingProgress::where('user_id', $user->id)
            ->with(['book.reviews', 'book.categories'])
            ->orderBy('updated_at', 'desc')
            ->get();
        
        return view('include.profile', compact('wishlistBooks', 'readingHistory'));
    }

    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'profile_image' => 'nullable|image|max:2048',
            'gender' => 'nullable|string|max:10',
            'age' => 'nullable|integer|min:0|max:120',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:1000',
            'interests' => 'nullable|string|max:255',
        ]);
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');
            $data['profile_image'] = '/storage/' . $path;
        }
        $user->update($data);
        return redirect()->route('profile.show')->with('success', 'Профайл амжилттай шинэчлэгдлээ!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);
        $user = Auth::user();
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Одоогийн нууц үг буруу байна.']);
        }
        $user->password = Hash::make($request->new_password);
        $user->save();
        return redirect()->route('profile.show')->with('success', 'Нууц үг амжилттай солигдлоо!');
    }
}

