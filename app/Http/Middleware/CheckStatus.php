<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class CheckStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$status): Response
    {
        if (in_array(auth()->user()->status, $status)) {
            return $next($request);
        }
        session()->invalidate();
        return redirect()->route('verify', ['username' => Crypt::encrypt(auth()->user()->username)]);
    }
}
