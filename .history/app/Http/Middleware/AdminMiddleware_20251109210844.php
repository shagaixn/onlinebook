<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Тухайн төслийн логикт тохируулна: is_admin, role, isStaff гэх мэт талбар ашиглаж болно
        if (!$user || !($user->is_admin ?? false)) {
            // Redirect to home or show 403
            return redirect()->route('home');
        }

        return $next($request);
    }
}