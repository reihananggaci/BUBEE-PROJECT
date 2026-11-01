<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Faq;
use App\Models\NotificationLog;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Retur;
use App\Models\Review;
use App\Models\StockMutation;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class WebModeCheckTest extends TestCase
{
    use RefreshDatabase;

    public function test_web_mode_memakai_view_web(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'a@t.id', 'password' => 'x', 'role' => 'admin']);
        $user = User::create(['name' => 'User', 'email' => 'u@t.id', 'password' => 'x', 'role' => 'enduser', 'hp' => '0812']);
        $product = Product::create([
            'nama' => 'Kaos', 'berat_gram' => 200, 'stok' => 5,
            'harga_enduser' => 100000, 'harga_reseller' => 80000, 'min_reseller' => 2,
        ]);
        $variant = $product->variants()->create(['ukuran' => 'M', 'warna' => 'Hitam', 'stok' => 10, 'sku' => 'T-M']);
        $product->syncStok();
        $order = Order::create(['user_id' => $user->id, 'kode' => 'BB-WEB', 'total_produk' => 1, 'ongkir' => 0, 'total_bayar' => 1, 'kurir' => 'JNE', 'layanan' => 'REG', 'status' => 'menunggu_bayar', 'alamat' => 'Jl']);

        // Mode HP (default testing) → mobile, tanpa marker desktop
        $this->get('/')->assertOk()->assertDontSee('lg:grid-cols-2', false);
        $this->actingAs($admin)->get('/admin')->assertOk()->assertDontSee('lg:table', false);

        // Mode web via query override → view web dengan marker desktop
        $this->get('/?tampilan=web')->assertOk()->assertSee('lg:grid-cols-2', false);
        $this->get('/produk/'.$product->id.'?tampilan=web')->assertOk()->assertSee('lg:sticky', false);
        $this->actingAs($admin)->get('/admin?tampilan=web')->assertOk()->assertSee('lg:table', false);
    }

    public function test_web_mode_checkout_bayar_tracking(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'a2@t.id', 'password' => 'x', 'role' => 'admin']);
        $user = User::create(['name' => 'User', 'email' => 'u2@t.id', 'password' => 'x', 'role' => 'enduser', 'hp' => '0812']);
        $product = Product::create([
            'nama' => 'Kaos', 'berat_gram' => 200, 'stok' => 5,
            'harga_enduser' => 100000, 'harga_reseller' => 80000, 'min_reseller' => 2,
        ]);
        $variant = $product->variants()->create(['ukuran' => 'M', 'warna' => 'Hitam', 'stok' => 10, 'sku' => 'T-M']);
        $product->syncStok();
        $this->actingAs($user)->post('/keranjang/'.$product->id, ['variant_id' => $variant->id, 'qty' => 1]);
        $order = Order::create(['user_id' => $user->id, 'kode' => 'BB-W1', 'total_produk' => 1, 'ongkir' => 0, 'total_bayar' => 1, 'kurir' => 'JNE', 'layanan' => 'REG', 'status' => 'menunggu_bayar', 'alamat' => 'Jl']);

        // Mobile tetap bersih
        $this->actingAs($user)->get('/checkout')->assertOk()->assertDontSee('lg:order-2', false);
        $this->actingAs($user)->get('/bayar/'.$order->id)->assertOk()->assertDontSee('lg:sticky', false);
        $this->actingAs($user)->get('/pesanan/'.$order->id)->assertOk()->assertDontSee('lg:sticky', false);

        // Web memakai layout 2 kolom
        $this->actingAs($user)->get('/checkout?tampilan=web')->assertOk()->assertSee('lg:order-2', false);
        $this->actingAs($user)->get('/bayar/'.$order->id.'?tampilan=web')->assertOk()->assertSee('lg:sticky', false);
        $this->actingAs($user)->get('/pesanan/'.$order->id.'?tampilan=web')->assertOk()->assertSee('lg:sticky', false);
    }

    public function test_laporan_filter_dan_ringkas(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'lap@t.id', 'password' => 'x', 'role' => 'admin', 'email_verified_at' => now()]);
        $user = User::create(['name' => 'User', 'email' => 'lapu@t.id', 'password' => 'x', 'role' => 'enduser', 'hp' => '081299900011', 'email_verified_at' => now()]);
        $product = Product::create(['nama' => 'Kaos Lap', 'berat_gram' => 200, 'stok' => 10, 'harga_enduser' => 100000, 'harga_reseller' => 80000, 'min_reseller' => 2]);
        $order = Order::create(['user_id' => $user->id, 'kode' => 'BB-L1', 'total_produk' => 100000, 'ongkir' => 10000, 'diskon' => 5000, 'total_bayar' => 105000, 'kurir' => 'JNE', 'layanan' => 'REG', 'status' => 'diproses', 'alamat' => 'Jl']);
        OrderItem::create(['order_id' => $order->id, 'product_id' => $product->id, 'qty' => 2, 'harga_satuan' => 50000, 'subtotal' => 100000, 'tipe_harga' => 'enduser']);
        Order::create(['user_id' => $user->id, 'kode' => 'BB-L2', 'total_produk' => 50000, 'ongkir' => 0, 'total_bayar' => 50000, 'kurir' => 'JNE', 'layanan' => 'REG', 'status' => 'batal', 'alamat' => 'Jl']);

        $this->actingAs($admin)->get('/admin/laporan')->assertOk()
            ->assertSee('105,000', false)
            ->assertSee('Per Status', false)
            ->assertSee('Top Pembeli', false)
            ->assertSee('Voucher Terpakai', false);
        // Filter periode kosong -> omzet nol
        $this->actingAs($admin)->get('/admin/laporan?dari=2020-01-01&sampai=2020-01-02')->assertOk()
            ->assertSee('Rp 0', false);
        // Tanggal invalid ditolak
        $this->actingAs($admin)->get('/admin/laporan?dari=not-a-date')->assertSessionHasErrors('dari');
        // Export CSV mengikuti filter
        $this->actingAs($admin)->get('/admin/laporan/export?dari=2020-01-01&sampai=2020-01-02')->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }

    public function test_produk_ringkas_sinyal_dan_filter(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'stk@t.id', 'password' => 'x', 'role' => 'admin', 'email_verified_at' => now()]);
        $user = User::create(['name' => 'User', 'email' => 'stku@t.id', 'password' => 'x', 'role' => 'enduser', 'hp' => '081299900022', 'email_verified_at' => now()]);
        $laris = Product::create(['nama' => 'Laris Manis', 'berat_gram' => 200, 'stok' => 6, 'harga_enduser' => 100000, 'harga_reseller' => 80000, 'min_reseller' => 2]);
        $sepi = Product::create(['nama' => 'Sepi Sekali', 'berat_gram' => 200, 'stok' => 50, 'harga_enduser' => 100000, 'harga_reseller' => 80000, 'min_reseller' => 2]);
        $order = Order::create(['user_id' => $user->id, 'kode' => 'BB-S1', 'total_produk' => 600000, 'ongkir' => 0, 'total_bayar' => 600000, 'kurir' => 'JNE', 'layanan' => 'REG', 'status' => 'selesai', 'alamat' => 'Jl']);
        OrderItem::create(['order_id' => $order->id, 'product_id' => $laris->id, 'qty' => 60, 'harga_satuan' => 10000, 'subtotal' => 600000, 'tipe_harga' => 'enduser']);

        // Laku 60/30 hari = 2/hari, stok 6 -> ±3 hari -> restock segera
        $this->actingAs($admin)->get('/admin/produk')->assertOk()
            ->assertSee('NILAI STOK', false)
            ->assertSee('restock segera', false)
            ->assertSee('Tidak laku 30 hari', false);
        $this->actingAs($admin)->get('/admin/produk?tampilan=web')->assertOk()
            ->assertSee('NILAI STOK', false)
            ->assertSee('Sinyal', false);
        // Filter kritis hanya tampilkan yang kritis
        $this->actingAs($admin)->get('/admin/produk?filter=kritis')->assertOk()
            ->assertSee('Laris Manis', false)
            ->assertDontSee('Sepi Sekali', false);
        // Cari nama
        $this->actingAs($admin)->get('/admin/produk?q=sepi')->assertOk()
            ->assertSee('Sepi Sekali', false)
            ->assertDontSee('Laris Manis', false);
        // Opname ada progres + selisih live
        $this->actingAs($admin)->get('/admin/opname')->assertOk()->assertSee('opname-progres', false);
    }

    public function test_aktivitas_kompleks(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'ak@t.id', 'password' => 'x', 'role' => 'admin', 'email_verified_at' => now()]);
        $user = User::create(['name' => 'User', 'email' => 'aku@t.id', 'password' => 'x', 'role' => 'enduser', 'hp' => '081299900033', 'email_verified_at' => now()]);
        // Login sukses tercatat + berlevel info + berkategori auth
        $this->post('/login', ['email' => 'aku@t.id', 'password' => 'x'])->assertRedirect('/');
        $this->assertDatabaseHas('activity_logs', ['aksi' => 'login_sukses', 'kategori' => 'auth', 'level' => 'info']);
        // Filter kategori + level
        $this->actingAs($admin)->get('/admin/aktivitas?kategori=auth&level=info')->assertOk()
            ->assertSee('login_sukses', false)->assertSee('GAGAL LOGIN', false);
        $this->actingAs($admin)->get('/admin/aktivitas?kategori=order')->assertOk()
            ->assertDontSee('login_sukses', false);
        // Preset cepat + filter lanjutan tersedia
        $this->actingAs($admin)->get('/admin/aktivitas?preset=hari_ini')->assertOk();
        $this->actingAs($admin)->get('/admin/aktivitas?preset=ngawur')->assertSessionHasErrors('preset');
        $this->actingAs($admin)->get('/admin/aktivitas')->assertOk()->assertSee('Filter lanjutan', false);
        // Export CSV + prune
        $this->actingAs($admin)->get('/admin/aktivitas/export')->assertOk()
            ->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $lama = ActivityLog::create(['aksi' => 'lama', 'kategori' => 'sistem', 'level' => 'info']);
        DB::table('activity_logs')->where('id', $lama->id)->update(['created_at' => now()->subDays(100), 'updated_at' => now()->subDays(100)]);
        $this->actingAs($admin)->post('/admin/aktivitas/prune')->assertRedirect();
        $this->assertDatabaseMissing('activity_logs', ['aksi' => 'lama']);
        $this->assertDatabaseHas('activity_logs', ['aksi' => 'login_sukses']);
    }

    public function test_filter_barang_baru(): void
    {
        $baru = Product::create(['nama' => 'Baru Datang', 'berat_gram' => 200, 'stok' => 5, 'harga_enduser' => 100000, 'harga_reseller' => 80000, 'min_reseller' => 2]);
        $lama = Product::create(['nama' => 'Stok Lama', 'berat_gram' => 200, 'stok' => 5, 'harga_enduser' => 100000, 'harga_reseller' => 80000, 'min_reseller' => 2]);
        DB::table('products')->where('id', $lama->id)->update(['created_at' => now()->subDays(30), 'updated_at' => now()->subDays(30)]);
        $this->get('/?filter=baru')->assertOk()->assertSee('Baru Datang', false)->assertDontSee('Stok Lama', false);
        $this->get('/')->assertOk()->assertSee('BARU', false);
    }

    public function test_web_mode_tabel_admin(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'ab@t.id', 'password' => 'x', 'role' => 'admin']);
        $user = User::create(['name' => 'User', 'email' => 'ub@t.id', 'password' => 'x', 'role' => 'enduser', 'hp' => '0812']);
        User::create(['name' => 'Rsl', 'email' => 'rs@t.id', 'password' => 'x', 'role' => 'reseller', 'hp' => '0813', 'status_reseller' => 'pending']);
        $product = Product::create(['nama' => 'Kaos', 'berat_gram' => 200, 'stok' => 5, 'harga_enduser' => 100000, 'harga_reseller' => 80000, 'min_reseller' => 2]);
        $order = Order::create(['user_id' => $user->id, 'kode' => 'BB-T1', 'total_produk' => 1, 'ongkir' => 0, 'total_bayar' => 1, 'kurir' => 'JNE', 'layanan' => 'REG', 'status' => 'menunggu_bayar', 'alamat' => 'Jl']);
        Order::create(['user_id' => $user->id, 'kode' => 'BB-T2', 'total_produk' => 1, 'ongkir' => 0, 'total_bayar' => 1, 'kurir' => 'JNE', 'layanan' => 'REG', 'status' => 'diproses', 'alamat' => 'Jl', 'bukti_bayar' => 'bukti/x.jpg']);
        StockMutation::create(['product_id' => $product->id, 'tipe' => 'masuk', 'qty' => 5, 'stok_sebelum' => 0, 'stok_sesudah' => 5, 'keterangan' => 'stok awal']);
        ActivityLog::create(['actor_id' => $admin->id, 'aksi' => 'tes', 'subjek' => 'x', 'keterangan' => 'y']);
        NotificationLog::create(['tipe' => 'tes', 'tujuan' => '0812', 'pesan' => 'halo', 'status' => 'terkirim']);
        Retur::create(['order_id' => $order->id, 'user_id' => $user->id, 'alasan' => 'cacat', 'status' => 'diajukan']);
        Review::create(['product_id' => $product->id, 'user_id' => $user->id, 'order_id' => $order->id, 'rating' => 5, 'ulasan' => 'bagus']);
        Voucher::create(['kode' => 'HEMAT10', 'tipe' => 'persen', 'nilai' => 10, 'kuota' => 100, 'terpakai' => 0, 'khusus' => 'semua', 'is_active' => true]);

        $urls = ['/admin/order', '/admin/order/'.$order->id, '/admin/produk', '/admin/users', '/admin/reseller', '/admin/verifikasi', '/admin/mutasi', '/admin/notifikasi', '/admin/aktivitas', '/admin/retur', '/admin/ulasan', '/admin/voucher'];
        foreach ($urls as $u) {
            $this->actingAs($admin)->get($u)->assertOk()->assertDontSee('lg:table', false);
        }
        foreach ($urls as $u) {
            $this->actingAs($admin)->get($u.'?tampilan=web')->assertOk()->assertSee('lg:table', false);
        }
    }

    public function test_web_mode_halaman_user_dan_sisa_admin(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'ac@t.id', 'password' => 'x', 'role' => 'admin']);
        $user = User::create(['name' => 'User', 'email' => 'uc@t.id', 'password' => 'x', 'role' => 'enduser', 'hp' => '0812']);
        $reseller = User::create(['name' => 'Rsl', 'email' => 'rc@t.id', 'password' => 'x', 'role' => 'reseller', 'hp' => '0813', 'status_reseller' => 'approved']);
        $product = Product::create(['nama' => 'Kaos', 'berat_gram' => 200, 'stok' => 5, 'harga_enduser' => 100000, 'harga_reseller' => 80000, 'min_reseller' => 2]);
        $variant = $product->variants()->create(['ukuran' => 'M', 'warna' => 'Hitam', 'stok' => 10, 'sku' => 'T-M']);
        $product->syncStok();
        $orderSelesai = Order::create(['user_id' => $user->id, 'kode' => 'BB-C1', 'total_produk' => 1, 'ongkir' => 0, 'total_bayar' => 1, 'kurir' => 'JNE', 'layanan' => 'REG', 'status' => 'selesai', 'alamat' => 'Jl']);
        OrderItem::create(['order_id' => $orderSelesai->id, 'product_id' => $product->id, 'variant_id' => $variant->id, 'qty' => 1, 'harga_satuan' => 100000, 'subtotal' => 100000, 'tipe_harga' => 'enduser']);
        $retur = Retur::create(['order_id' => $orderSelesai->id, 'user_id' => $user->id, 'alasan' => 'cacat', 'status' => 'diajukan']);
        Faq::create(['topik' => 'Order', 'pertanyaan' => 'Q?', 'jawaban' => 'A.', 'urutan' => 0, 'is_active' => true]);

        // Keranjang butuh isi
        $this->actingAs($user)->post('/keranjang/'.$product->id, ['variant_id' => $variant->id, 'qty' => 1]);

        $userPages = [
            ['/keranjang', 'lg:col-span-2', $user], ['/pesanan', 'lg:table', $user],
            ['/akun', 'lg:sticky', $user], ['/akun/pengaturan', 'lg:grid-cols-2', $user],
            ['/reseller', 'lg:sticky', $reseller], ['/reseller/etalase', 'lg:table', $reseller],
            ['/reseller/hemat', 'lg:table', $reseller], ['/pesanan/'.$orderSelesai->id.'/retur', 'lg:sticky', $user],
            ['/retur', 'lg:table', $user], ['/retur/'.$retur->id, 'lg:table', $user],
            ['/bantuan', 'lg:sticky', $user],
        ];
        foreach ($userPages as [$u, $mark, $who]) {
            $this->actingAs($who)->withSession(['tampilan' => 'hp'])->get($u)->assertOk()->assertDontSee($mark, false);
        }
        foreach ($userPages as [$u, $mark, $who]) {
            $this->actingAs($who)->get($u.'?tampilan=web')->assertOk()->assertSee($mark, false);
        }
        // Guest: login/register/bantuan
        foreach ([['/login', 'lg:grid-cols-2'], ['/register', 'lg:grid-cols-2'], ['/bantuan', 'lg:sticky']] as [$u, $mark]) {
            $this->withSession(['tampilan' => 'hp'])->get($u)->assertOk()->assertDontSee($mark, false);
            $this->get($u.'?tampilan=web')->assertOk()->assertSee($mark, false);
        }

        $adminPages = [
            ['/admin/laporan', 'lg:table'], ['/admin/laporan?tab=harian', 'lg:table'], ['/admin/opname', 'lg:table'],
            ['/admin/integrasi', 'lg:grid-cols-2'], ['/admin/pengaturan', 'lg:grid-cols-2'],
            ['/admin/faq', 'lg:table'],
        ];
        foreach ($adminPages as [$u, $mark]) {
            $this->actingAs($admin)->withSession(['tampilan' => 'hp'])->get($u)->assertOk()->assertDontSee($mark, false);
        }
        foreach ($adminPages as [$u, $mark]) {
            $sep = str_contains($u, '?') ? '&' : '?';
            $this->actingAs($admin)->get($u.$sep.'tampilan=web')->assertOk()->assertSee($mark, false);
        }
    }

    public function test_emoji_hanya_fallback_tanpa_foto(): void
    {
        Product::create([
            'nama' => 'Tumbler Tes', 'berat_gram' => 500, 'stok' => 10,
            'harga_enduser' => 100000, 'harga_reseller' => 80000, 'min_reseller' => 2,
            'foto' => 'produk/tes.jpg', 'is_active' => true,
        ]);
        // Produk berfoto: tidak boleh ada emoji menutupi di kedua mode
        $this->withSession(['tampilan' => 'hp'])->get('/')->assertOk()->assertDontSee('>👕</span>', false);
        $this->get('/?tampilan=web')->assertOk()->assertDontSee('>👕</span>', false);
        // Produk tanpa foto: fallback emoji tetap muncul
        Product::query()->update(['foto' => null]);
        $this->withSession(['tampilan' => 'hp'])->get('/')->assertOk()->assertSee('>👕</span>', false);
    }

    public function test_kartu_katalog_rapi_tombol_sejajar(): void
    {
        Product::create([
            'nama' => 'Tumbler Tes Panjang Namanya Dua Baris', 'berat_gram' => 500, 'stok' => 10,
            'harga_enduser' => 100000, 'harga_reseller' => 80000, 'min_reseller' => 2,
            'foto' => 'produk/tes.jpg', 'is_active' => true,
        ]);
        // Area foto terkunci rasio 4:3 + grup tombol menempel dasar kartu, kedua mode
        $this->withSession(['tampilan' => 'hp'])->get('/')->assertOk()
            ->assertSee('aspect-ratio:4/3', false)
            ->assertSee('<div style="margin-top:auto">', false)
            ->assertSee('lihat-btn', false);
        $this->get('/?tampilan=web')->assertOk()
            ->assertSee('aspect-ratio:4/3', false)
            ->assertSee('<div style="margin-top:auto">', false)
            ->assertSee('lihat-btn', false);
    }
}
