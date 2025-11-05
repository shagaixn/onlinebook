<?php

namespace App\Http\Controllers;

use Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SocialController extends Controller
{
    // Facebook login
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }
    public function handleFacebookCallback()
    {
        $socialUser = Socialite::driver('facebook')->stateless()->user();
        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getName(),
                'password' => bcrypt(uniqid()),
                'role' => 'user'
            ]
        );
        Auth::login($user);
        return redirect('/home');
    }

    // Instagram login
    public function redirectToInstagram()
    {
        return Socialite::driver('instagram')->redirect();
    }
    public function handleInstagramCallback()
    {
        $socialUser = Socialite::driver('instagram')->stateless()->user();
        $user = User::firstOrCreate(
            ['email' => $socialUser->getEmail()],
            [
                'name' => $socialUser->getNickname() ?: $socialUser->getName(),
                'password' => bcrypt(uniqid()),
                'role' => 'user'
            ]
        );
        Auth::login($user);
        return redirect('/home');
    }
}
