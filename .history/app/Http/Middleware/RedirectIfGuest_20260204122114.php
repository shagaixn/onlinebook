<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfGuest
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('message', 'Та энэ хуудсыг үзэхийн тулд заавал бүртгэл үүсгэсэн байх ёстой. Нэвтэрч орно уу эсвэл шинээр бүртгүүлнэ үү.');
        }

        return $next($request);
    }
}
