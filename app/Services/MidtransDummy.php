<?php

namespace App\Services;

use App\Models\Order;

class MidtransDummy
{
    // Simulasi snap token. Nanti ganti dengan API Midtrans asli di file ini saja.
    public static function createSnapToken(Order $order): string
    {
        return 'DUMMY-'.$order->kode;
    }
}
