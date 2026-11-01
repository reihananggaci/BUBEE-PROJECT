<?php

namespace App\Services;

use App\Models\NotificationLog;
use App\Models\Order;
use App\Models\Retur;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WaNotifier
{
    public static function token(): string
    {
        return (string) (Setting::get('fonnte_token', '') ?: env('FONNTE_TOKEN', ''));
    }

    public static function isLive(): bool
    {
        return self::token() !== '';
    }

    public static function normalize(?string $hp): string
    {
        $n = preg_replace('/[^0-9]/', '', (string) ($hp ?? ''));
        if (str_starts_with($n, '0')) {
            $n = '62'.substr($n, 1);
        }

        return $n;
    }

    public static function adminNumber(): string
    {
        return self::normalize((string) Setting::get('wa_admin', '6281234567890'));
    }

    /**
     * Kirim WA. Tanpa token -> mode dummy (dicatat, tidak ada request keluar).
     */
    public static function send(?string $tujuan, string $pesan, string $tipe = 'info', ?int $orderId = null): NotificationLog
    {
        $tujuan = self::normalize($tujuan);
        if ($tujuan === '') {
            Log::warning('[WA] nomor tujuan kosong, pesan dibatalkan: '.substr($pesan, 0, 80));

            return new NotificationLog(['tipe' => $tipe, 'tujuan' => '', 'pesan' => $pesan, 'status' => 'skip', 'order_id' => $orderId]);
        }
        $log = NotificationLog::create([
            'tipe' => $tipe, 'tujuan' => $tujuan, 'pesan' => $pesan,
            'status' => 'dummy', 'order_id' => $orderId,
        ]);
        if (! self::isLive()) {
            Log::info('[WA-DUMMY] ke '.$tujuan.': '.$pesan);

            return $log;
        }
        try {
            $res = Http::withHeaders(['Authorization' => self::token()])
                ->asForm()->timeout(15)
                ->post('https://api.fonnte.com/send', ['target' => $tujuan, 'message' => $pesan]);
            $log->update([
                'status' => $res->successful() ? 'terkirim' : 'gagal',
                'response' => substr((string) $res->body(), 0, 1000),
            ]);
        } catch (\Throwable $e) {
            $log->update(['status' => 'gagal', 'response' => substr($e->getMessage(), 0, 1000)]);
        }

        return $log;
    }

    public static function orderBaru(Order $order): void
    {
        $o = $order->loadMissing('items.product');
        $rincian = $o->items->map(fn ($it) => '- '.($it->product->nama ?? '').' ×'.$it->qty)->implode("\n");
        self::send(self::adminNumber(),
            "🐝 *Order baru Bubee!*\nKode: {$o->kode}\n".$rincian."\nTotal: Rp ".number_format($o->total_bayar)." ({$o->kurir} {$o->layanan})\nPenerima: {$o->nama_penerima} ({$o->hp_penerima})\nSegera verifikasi di admin ya!",
            'order_baru', $o->id);
    }

    public static function statusOrder(Order $order): void
    {
        $order->loadMissing('user');
        if ($order->user && ! ($order->user->notif_wa ?? true)) {
            return;
        } // user mematikan notif
        $tambahan = match ($order->status) {
            'diproses' => 'Pembayaran diterima, pesananmu sedang disiapkan 📦',
            'dikirim' => 'Pesanan dikirim! Resi: '.($order->resi ?: '-').' 🚚',
            'selesai' => 'Pesanan selesai. Makasih sudah belanja! ⭐',
            'batal' => 'Yah, order dibatalkan. Stok sudah dikembalikan.',
            default => 'Status order: '.$order->status,
        };
        self::send($order->hp_penerima,
            "🐝 *Bubee - {$order->kode}*\n{$tambahan}\nTotal: Rp ".number_format($order->total_bayar),
            'status', $order->id);
    }

    public static function bayarMasuk(Order $order, string $via = 'simulasi'): void
    {
        self::send(self::adminNumber(),
            "💳 *Pembayaran {$order->kode}* via {$via} (Rp ".number_format($order->total_bayar).'). Cek & verifikasi ya!',
            'bayar', $order->id);
    }

    public static function batalMasuk(Order $order): void
    {
        $order->loadMissing('user', 'items');
        $pcs = $order->items->sum('qty');
        $siapa = $order->user && $order->user->role === 'reseller'
            ? 'reseller '.$order->user->name.($order->user->toko_nama ? ' ('.$order->user->toko_nama.')' : '')
            : ($order->nama_penerima ?? 'pembeli');
        self::send(self::adminNumber(),
            "⚠️ *Order batal {$order->kode}*\nOleh: {$siapa}\nAlasan: {$order->alasan_batal}\n{$pcs} pcs kembali ke stok. Follow-up yuk!",
            'batal', $order->id);
    }

    public static function returMasuk(Retur $retur): void
    {
        $retur->loadMissing('order');
        self::send(self::adminNumber(),
            "🔄 *Retur baru #{$retur->id}* order {$retur->order->kode}: {$retur->alasan}. Segera ditindaklanjuti!",
            'retur', $retur->order_id);
    }

    public static function returUpdate(Retur $retur): void
    {
        $retur->loadMissing('order', 'user');
        if ($retur->user && ! ($retur->user->notif_wa ?? true)) {
            return;
        }
        $hp = $retur->user->hp ?? $retur->order->hp_penerima;
        self::send($hp,
            "🔄 *Retur #{$retur->id}* ({$retur->order->kode}) → {$retur->status}.".($retur->catatan_admin ? "\nCatatan: {$retur->catatan_admin}" : ''),
            'retur', $retur->order_id);
    }

    public static function reseller(User $user, bool $setuju, ?string $alasan = null): void
    {
        self::send($user->hp ?? '-',
            $setuju
                ? "🎉 *Selamat {$user->name}!* Akun reseller Bubee-mu *DISETUJUI*. Harga khusus otomatis aktif. Happy cuan!"
                : "Maaf {$user->name}, pengajuan reseller Bubee *DITOLAK*.".($alasan ? "\nAlasan: {$alasan}\nPerbaiki datanya lalu ajukan ulang." : ' Hubungi admin untuk info.'),
            'reseller');
    }
}
