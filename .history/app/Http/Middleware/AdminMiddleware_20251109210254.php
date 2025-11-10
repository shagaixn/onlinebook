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

        // Таны User модель дээрх админ шалгалтыг тохируулна уу
        // Жишээ: is_admin boolean талбар ашигласан бол:
        if (!$user || !$user->is_admin) {
            // Redirect эсвэл 403 хийх
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}