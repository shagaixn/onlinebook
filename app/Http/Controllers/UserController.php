<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Хэрэглэгчдийн жагсаалт харуулах
     */
    public function index()
    {
        $users = User::all(); // бүх хэрэглэгчийг авах
        return view('layouts.admin.users.index', compact('users'));
    }

    /**
     * Шинэ хэрэглэгч үүсгэх form
     */
    public function create()
    {
        return view('layouts.admin.users.create');
    }

    /**
     * Хэрэглэгч шинээр хадгалах
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        User::create($validated);

        return redirect()->route('layouts.admin.users.store')->with('success', 'Шинэ хэрэглэгч нэмэгдлээ!');
    }

    /**
     * Хэрэглэгч засах form
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('layouts.admin.users.edit', compact('user'));
    }

    /**
     * Хэрэглэгчийн мэдээлэл шинэчлэх
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$id}",
            'password' => 'nullable|min:6|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('layouts.admin.users.update')->with('success', 'Хэрэглэгчийн мэдээлэл шинэчлэгдлээ!');
    }

    /**
     * Хэрэглэгч устгах
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('layouts.admin.users.destroy')->with('success', 'Хэрэглэгч устгагдлаа!');
    }
}
