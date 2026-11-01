<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function user(Order $order)
    {
        abort_unless($order->user_id === Auth::id(), 403);
        $order->load('items.product', 'items.variant', 'user', 'voucher');
        $pdf = Pdf::loadView('invoice', compact('order'));

        return $pdf->download('invoice-'.$order->kode.'.pdf');
    }

    public function admin(Order $order)
    {
        $order->load('items.product', 'items.variant', 'user', 'voucher');
        $pdf = Pdf::loadView('invoice', compact('order'));

        return $pdf->download('invoice-'.$order->kode.'.pdf');
    }
}
