<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureReseller
{
    public function handle(Request $request, Closure $next)
    {
        $u = Auth::user();
        if (! $u || $u->role !== 'reseller') {
            return redirect('/akun')->withErrors(['akses' => 'Halaman khusus reseller.']);
        }
        if (($u->status_reseller ?? '') !== 'approved') {
            return redirect('/akun')->withErrors(['akses' => 'Akun resellermu masih '.($u->status_reseller ?? 'pending').', tunggu approval admin.']);
        }

        return $next($request);
    }
}
