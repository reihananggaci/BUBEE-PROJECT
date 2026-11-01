<?php

use Illuminate\Support\Facades\View;

if (! function_exists('web_view')) {
    /**
     * Render view web jika mode web aktif DAN file web-nya ada,
     * selain itu jatuh kembali ke view mobile.
     */
    function web_view(string $name, array $data = [])
    {
        if (session('tampilan') === 'web' && View::exists('web.'.$name)) {
            return view('web.'.$name, $data);
        }

        return view($name, $data);
    }
}

if (! function_exists('tampilan_aktif')) {
    function tampilan_aktif(): string
    {
        return session('tampilan', 'hp');
    }
}

if (! function_exists('nama_role')) {
    /**
     * Nama tampil peran akun. Nilai DB tidak diubah (enduser tetap enduser).
     */
    function nama_role(?string $role): string
    {
        return match ($role) {
            'admin' => 'Admin',
            'reseller' => 'Reseller',
            default => 'Reguler',
        };
    }
}
