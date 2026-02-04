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
                ->with('warning', 'Уучлаарай, та бүртгэлээ баталгаажуулна уу.')
                ->with('intended', $request->url());
        }

        return $next($request);
    }
}
