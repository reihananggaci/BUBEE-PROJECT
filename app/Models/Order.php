<?php

namespace App\Models;

use App\Services\WaNotifier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    protected $fillable = ['user_id', 'kode', 'total_produk', 'ongkir', 'ongkir_sumber', 'berat_tagih_gram', 'voucher_id', 'diskon', 'total_bayar', 'kurir', 'layanan', 'resi', 'alasan_batal', 'status', 'alamat', 'kota_tujuan', 'city_id', 'nama_penerima', 'hp_penerima', 'bukti_bayar', 'snap_token'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function returs()
    {
        return $this->hasMany(Retur::class);
    }

    public static function batalkanKedaluwarsa(int $jam = 24): int
    {
        $batas = now()->subHours($jam);
        $orders = static::with('items')->where('status', 'menunggu_bayar')
            ->where('created_at', '<=', $batas)
            ->get();

        $count = 0;
        foreach ($orders as $order) {
            $diproses = DB::transaction(function () use ($order) {
                $fresh = static::with('items')->whereKey($order->id)
                    ->where('status', 'menunggu_bayar')->lockForUpdate()->first();
                if (! $fresh) {
                    return false;
                }
                foreach ($fresh->items as $it) {
                    $p = Product::find($it->product_id);
                    if ($p) {
                        StockMutation::create([
                            'product_id' => $p->id,
                            'variant_id' => $it->variant_id,
                            'tipe' => 'masuk',
                            'qty' => $it->qty,
                            'stok_sebelum' => $p->stok,
                            'stok_sesudah' => $p->stok + $it->qty,
                            'keterangan' => 'Auto-batal kedaluwarsa '.$fresh->kode,
                        ]);
                        $p->increment('stok', $it->qty);
                        if ($it->variant_id) {
                            ProductVariant::where('id', $it->variant_id)->increment('stok', $it->qty);
                        }
                    }
                }
                if ($fresh->voucher_id) {
                    Voucher::where('id', $fresh->voucher_id)->where('terpakai', '>', 0)->decrement('terpakai');
                }
                $fresh->update([
                    'status' => 'batal',
                    'alasan_batal' => 'Kedaluwarsa (batas waktu pembayaran habis)',
                ]);

                return true;
            });
            if (! $diproses) {
                continue;
            }

            ActivityLog::catat('order_auto_batal', $order->kode, 'Kedaluwarsa > '.$jam.' jam, stok & voucher dikembalikan', 'order', null, '/admin/order/'.$order->id);
            WaNotifier::statusOrder($order->fresh());
            $count++;
        }

        return $count;
    }
}
