<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // লগইন আছে এবং utype 'admin' হলে অনুমতি
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // অন্যথায় 403 Forbidden
        abort(404);
    }
}
