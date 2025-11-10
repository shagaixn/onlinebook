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

        // Role талбараар шалгах (users.role === 'admin'). User::isAdmin() туслах функцийг ашиглав.
        if (!$user || method_exists($user, 'isAdmin') ? !$user->isAdmin() : ($user->role ?? null) !== 'admin') {
            // Админ биш тохиолдолд 403 буцаах нь илүү зөв; хүсвэл home руу чиглүүлэхээр өөрчилж болно.
            return abort(403, 'Access denied. Admins only.');
        }

        return $next($request);
    }
}