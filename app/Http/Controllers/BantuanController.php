<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Setting;
use Illuminate\Http\Request;

class BantuanController extends Controller
{
    public function index(Request $r)
    {
        $r->validate(['q' => 'nullable|string|max:50', 'topik' => 'nullable|string|max:30']);
        $q = $r->get('q');
        $topik = $r->get('topik', 'semua');
        $faqs = Faq::where('is_active', true)
            ->when($q, fn ($x) => $x->where(function ($y) use ($q) {
                $y->where('pertanyaan', 'like', "%$q%")->orWhere('jawaban', 'like', "%$q%");
            }))
            ->when($topik !== 'semua', fn ($x) => $x->where('topik', $topik))
            ->orderBy('urutan')->orderBy('id')->get()->groupBy('topik');
        $wa = Setting::get('wa_admin', '6281234567890');

        return web_view('bantuan', compact('faqs', 'q', 'topik', 'wa'));
    }

    public function json()
    {
        return response()->json(
            Faq::where('is_active', true)->orderBy('urutan')->orderBy('id')->take(5)
                ->get(['topik', 'pertanyaan', 'jawaban'])
        );
    }
}
