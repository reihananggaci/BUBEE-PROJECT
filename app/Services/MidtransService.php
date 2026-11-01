<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public static function configured(): bool
    {
        return (string) env('MIDTRANS_SERVER_KEY', '') !== '';
    }

    public static function isProduction(): bool
    {
        return (bool) env('MIDTRANS_IS_PRODUCTION', false);
    }

    public static function clientKey(): string
    {
        return (string) env('MIDTRANS_CLIENT_KEY', '');
    }

    protected static function boot(): void
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = self::isProduction();
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Buat Snap token asli. Return null jika gagal / belum dikonfigurasi.
     */
    public static function createSnapToken(Order $order): ?string
    {
        if (! self::configured()) {
            return null;
        }
        self::boot();
        $order->loadMissing('items.product', 'user');
        $itemDetails = $order->items->map(fn ($it) => [
            'id' => (string) $it->product_id,
            'price' => (int) $it->harga_satuan,
            'quantity' => (int) $it->qty,
            'name' => substr(($it->product->nama ?? 'Produk').' ('.($it->variant?->label() ?? '').')', 0, 50),
        ])->values()->all();
        $itemDetails[] = ['id' => 'ONGKIR', 'price' => (int) $order->ongkir, 'quantity' => 1, 'name' => 'Ongkir '.$order->kurir];
        if ($order->diskon > 0) {
            $itemDetails[] = ['id' => 'VOUCHER', 'price' => -(int) $order->diskon, 'quantity' => 1, 'name' => 'Voucher'];
        }
        $params = [
            'transaction_details' => ['order_id' => $order->kode, 'gross_amount' => (int) $order->total_bayar],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $order->nama_penerima,
                'email' => $order->user->email ?? 'guest@bubee.id',
                'phone' => $order->hp_penerima,
            ],
        ];
        try {
            return Snap::getSnapToken($params);
        } catch (\Throwable $e) {
            Log::error('[Midtrans] gagal buat snap: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Verifikasi signature webhook Midtrans. Return payload array atau null.
     */
    public static function verifyNotification(array $data): ?array
    {
        $serverKey = (string) env('MIDTRANS_SERVER_KEY', '');
        if ($serverKey === '') {
            return null;
        }
        $need = ['order_id', 'status_code', 'gross_amount', 'signature_key'];
        foreach ($need as $k) {
            if (! isset($data[$k])) {
                return null;
            }
        }
        $calc = hash('sha512', $data['order_id'].$data['status_code'].$data['gross_amount'].$serverKey);
        if (! hash_equals($calc, (string) $data['signature_key'])) {
            Log::warning('[Midtrans] signature tidak valid untuk '.($data['order_id'] ?? '?'));

            return null;
        }

        return $data;
    }

    public static function snapJsUrl(): string
    {
        return self::isProduction()
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    }
}
