<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check() || Auth::user()->role !== 'admin') {
            return redirect('/')->withErrors(['akses' => 'Halaman khusus admin. Login sebagai admin@bubee.id / password']);
        }

        return $next($request);
    }
}
