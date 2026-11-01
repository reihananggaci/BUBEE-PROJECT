<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\StockMutation;
use App\Models\Voucher;
use App\Services\MidtransService;
use App\Services\WaNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function notify(Request $request)
    {
        $data = MidtransService::verifyNotification($request->all());
        if (! $data) {
            return response()->json(['message' => 'invalid'], 403);
        }

        $order = Order::where('kode', $data['order_id'])->first();
        if (! $order) {
            return response()->json(['message' => 'order not found'], 404);
        }

        $trx = $data['transaction_status'] ?? '';
        $fraud = $data['fraud_status'] ?? '';
        Log::info('[Midtrans] notify '.$order->kode.' = '.$trx.'/'.$fraud);

        if (in_array($trx, ['settlement']) || ($trx === 'capture' && $fraud === 'accept')) {
            if ($order->status === 'menunggu_bayar') {
                $order->update(['status' => 'diproses']);
                WaNotifier::bayarMasuk($order, 'midtrans:'.($data['payment_type'] ?? ''));
                ActivityLog::catat('bayar_masuk', $order->kode, 'Rp '.number_format($order->total_bayar).' via '.($data['payment_type'] ?? '?'), 'order', null, '/admin/order/'.$order->id);
            }
        } elseif (in_array($trx, ['expire', 'cancel', 'deny'])) {
            if ($order->status === 'menunggu_bayar') {
                DB::transaction(function () use ($order) {
                    $order->load('items');
                    foreach ($order->items as $it) {
                        $p = Product::find($it->product_id);
                        if ($p) {
                            StockMutation::create(['product_id' => $p->id, 'variant_id' => $it->variant_id, 'tipe' => 'masuk', 'qty' => $it->qty, 'stok_sebelum' => $p->stok, 'stok_sesudah' => $p->stok + $it->qty, 'keterangan' => 'Midtrans '.$order->kode.' '.'batal']);
                            $p->increment('stok', $it->qty);
                            if ($it->variant_id) {
                                ProductVariant::where('id', $it->variant_id)->increment('stok', $it->qty);
                            }
                        }
                    }
                    if ($order->voucher_id) {
                        Voucher::where('id', $order->voucher_id)->where('terpakai', '>', 0)->decrement('terpakai');
                    }
                    $order->update(['status' => 'batal']);
                });
                WaNotifier::statusOrder($order->fresh());
                ActivityLog::catat('order_batal', $order->kode, 'Midtrans '.$trx, 'order', null, '/admin/order/'.$order->id);
            }
        }

        return response()->json(['message' => 'ok']);
    }
}
