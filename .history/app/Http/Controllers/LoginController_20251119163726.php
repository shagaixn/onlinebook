<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Нэвтрэх form харуулах
     */
    public function showLoginForm()
    {
        return view('include.login');
    }

    /**
     * Хэрэглэгч нэвтрүүлэх
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Хэрвээ remember checkbox байгаа бол request-д include хийгээрэй: $request->has('remember')
        $remember = $request->boolean('remember', false);

        if (Auth::attempt($credentials, $remember)) {
            // session-ийг regenerate хийж session fixation-оос хамгаална
            $request->session()->regenerate();

            // Хэрвээ админ бол admin dashboard руу, бусад нь intended('/home') руу
            $user = Auth::user();
            if (method_exists($user, 'isAdmin') ? $user->isAdmin() : (($user->role ?? null) === 'admin')) {
                return redirect()->intended(route('home'))
                    ->with('success', 'Админ эрхээр амжилттай нэвтэрлээ!');
            }

            return redirect()->intended('/home')->with('success', 'Амжилттай нэвтэрлээ!');
        }

        return back()->withErrors([
            'email' => 'Имэйл эсвэл нууц үг буруу байна.',
        ])->onlyInput('email');
    }

    /**
     * Хэрэглэгч гаргах
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // Админаас гармагц веб сайтын нүүр рүү буцаана
        return redirect()->route('home')->with('success', 'Амжилттай гарлаа!');
    }
}