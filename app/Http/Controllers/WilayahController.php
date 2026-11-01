<?php

namespace App\Http\Controllers;

use App\Services\RajaOngkirService;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function provinsi()
    {
        return response()->json(RajaOngkirService::provinces());
    }

    public function kota(Request $r)
    {
        $r->validate(['province' => 'nullable|string|max:10']);

        return response()->json(RajaOngkirService::cities((string) $r->get('province', '')));
    }
}
