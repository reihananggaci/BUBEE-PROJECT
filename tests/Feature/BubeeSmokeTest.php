<?php

namespace Tests\Feature;

use App\Models\Faq;
use App\Models\OpnameItem;
use App\Models\OpnameSesi;
use App\Models\Order;
use App\Models\Penerimaan;
use App\Models\Product;
use App\Models\ProductPrice;
use App\Models\Setting;
use App\Models\User;
use App\Models\Voucher;
use App\Services\OngkirDummy;
use App\Services\PriceService;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BubeeSmokeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    private function seedShop(): array
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@t.id', 'password' => 'x', 'role' => 'admin', 'email_verified_at' => now()]);
        $user = User::create(['name' => 'User', 'email' => 'user@t.id', 'password' => 'x', 'role' => 'enduser', 'hp' => '0812', 'email_verified_at' => now()]);
        $product = Product::create([
            'nama' => 'Kaos', 'berat_gram' => 200, 'stok' => 0,
            'harga_enduser' => 100000, 'harga_reseller' => 80000, 'min_reseller' => 2,
        ]);
        $m = $product->variants()->create(['ukuran' => 'M', 'warna' => 'Hitam', 'stok' => 10, 'sku' => 'T-M']);
        $l = $product->variants()->create(['ukuran' => 'L', 'warna' => 'Hitam', 'stok' => 5, 'sku' => 'T-L']);
        $product->syncStok();

        return compact('admin', 'user', 'product', 'm', 'l');
    }

    public function test_home_splash_login_terbuka(): void
    {
        $this->get('/')->assertOk()->assertDontSee('title="Kembali"', false)->assertSee('helpFab', false);
        $this->get('/')->assertSee('grid-cols-2', false)->assertDontSee('dnav', false);
        $this->get('/splash')->assertOk();
        $this->get('/login')->assertOk()->assertSee('title="Kembali"', false)->assertSee('auth-split', false);
        $this->get('/register')->assertOk()->assertSee('title="Kembali"', false)->assertSee('auth-split', false);
        $this->get('/')->assertDontSee('@media (min-width:1024px)', false);
    }

    public function test_tamu_dialihkan_dari_area_auth(): void
    {
        $this->get('/keranjang')->assertRedirect('/login');
        $this->get('/akun')->assertRedirect('/login');
    }

    public function test_harga_disembunyikan_dari_tamu(): void
    {
        ['user' => $user, 'product' => $product] = $this->seedShop();
        // tamu bisa browsing, tapi tanpa angka harga
        $this->get('/')->assertOk()->assertDontSee('Rp ')->assertSee('Login untuk lihat harga');
        $this->get('/produk/'.$product->id)->assertOk()->assertDontSee('Rp ')->assertSee('Login untuk lihat harga');
        // yang login tetap lihat harga
        $this->actingAs($user)->get('/')->assertSee('Rp ');
        $this->actingAs($user)->get('/produk/'.$product->id)->assertSee('Rp ');
    }

    public function test_admin_terproteksi(): void
    {
        ['user' => $user, 'admin' => $admin] = $this->seedShop();
        $this->actingAs($user)->get('/admin')->assertRedirect('/');
        $this->actingAs($admin)->get('/admin')->assertOk()->assertSee('COMMAND CENTER');
        $this->actingAs($admin)->get('/admin')->assertDontSee('class="aside"', false)->assertDontSee('MENU ADMIN');
    }

    public function test_order_varian_mengurangi_stok_yang_benar(): void
    {
        ['user' => $user, 'product' => $product, 'm' => $m, 'l' => $l] = $this->seedShop();
        $this->actingAs($user)
            ->post('/keranjang/'.$product->id, ['variant_id' => $m->id, 'qty' => 2])
            ->assertRedirect('/keranjang');
        $this->actingAs($user)->post('/checkout', [
            'nama_penerima' => 'User', 'hp_penerima' => '0812', 'alamat' => 'Jl',
            'kurir' => 'JNE', 'layanan' => 'REG',
        ])->assertRedirect();
        $order = Order::latest()->first();
        $this->assertEquals(2 * 100000 + $order->ongkir, $order->total_bayar);
        $this->assertEquals(8, $m->fresh()->stok);
        $this->assertEquals(5, $l->fresh()->stok); // varian lain utuh
        $this->assertEquals(13, $product->fresh()->stok); // total tersinkron
    }

    public function test_voucher_valid_dan_invalid(): void
    {
        ['user' => $user, 'product' => $product, 'm' => $m] = $this->seedShop();
        Voucher::create(['kode' => 'HEMAT10', 'tipe' => 'persen', 'nilai' => 10, 'kuota' => 5]);
        $this->actingAs($user)->post('/keranjang/'.$product->id, ['variant_id' => $m->id, 'qty' => 1]);
        $this->actingAs($user)->post('/checkout', [
            'nama_penerima' => 'User', 'hp_penerima' => '0812', 'alamat' => 'Jl',
            'kurir' => 'JNE', 'layanan' => 'REG', 'voucher_kode' => 'HEMAT10',
        ])->assertRedirect();
        $order = Order::latest()->first();
        $this->assertEquals(10000, $order->diskon);
        $this->assertEquals(1, Voucher::where('kode', 'HEMAT10')->first()->terpakai);
        // kode salah ditolak
        $this->actingAs($user)->post('/keranjang/'.$product->id, ['variant_id' => $m->id, 'qty' => 1]);
        $this->actingAs($user)->post('/checkout', [
            'nama_penerima' => 'User', 'hp_penerima' => '0812', 'alamat' => 'Jl',
            'kurir' => 'JNE', 'layanan' => 'REG', 'voucher_kode' => 'SALAH',
        ])->assertSessionHasErrors('order');
    }

    public function test_batal_mengembalikan_stok_varian(): void
    {
        ['user' => $user, 'product' => $product, 'm' => $m] = $this->seedShop();
        $this->actingAs($user)->post('/keranjang/'.$product->id, ['variant_id' => $m->id, 'qty' => 3]);
        $this->actingAs($user)->post('/checkout', [
            'nama_penerima' => 'User', 'hp_penerima' => '0812', 'alamat' => 'Jl',
            'kurir' => 'JNE', 'layanan' => 'REG',
        ]);
        $order = Order::latest()->first();
        $this->actingAs($user)->post('/pesanan/'.$order->id.'/batal')->assertSessionHasErrors('alasan');
        $this->actingAs($user)->post('/pesanan/'.$order->id.'/batal', ['alasan' => 'Berubah pikiran'])->assertRedirect('/pesanan');
        $this->assertEquals(10, $m->fresh()->stok);
        $this->assertEquals('batal', $order->fresh()->status);
        $this->assertEquals('Berubah pikiran', $order->fresh()->alasan_batal);
        $this->assertDatabaseHas('notification_logs', ['order_id' => $order->id, 'tipe' => 'batal']);
        $this->assertDatabaseHas('notification_logs', ['order_id' => $order->id, 'tipe' => 'status']);
    }

    public function test_webhook_menolak_signature_palsu(): void
    {
        $this->postJson('/midtrans/notify', ['order_id' => 'X'])->assertStatus(403);
    }

    public function test_wilayah_dummy_tersedia(): void
    {
        $this->get('/wilayah/provinsi')->assertOk()->assertSee('Jawa Barat');
        $this->get('/wilayah/kota?province=9')->assertOk()->assertSee('Bandung');
    }

    public function test_kategori_dan_sort(): void
    {
        ['product' => $product] = $this->seedShop();
        $product->update(['kategori' => 'Kaos']);
        $this->get('/?kategori=Kaos')->assertOk()->assertSee('Kaos');
        $this->get('/?kategori=Jaket')->assertDontSee('>Kaos<');
        $this->get('/?sort=murah')->assertOk();
    }

    public function test_ulasan_hanya_pembeli_selesai(): void
    {
        ['user' => $user, 'admin' => $admin, 'product' => $product, 'm' => $m] = $this->seedShop();
        // tanpa beli -> ditolak
        $this->actingAs($user)->post('/produk/'.$product->id.'/ulasan', ['rating' => 5, 'ulasan' => 'x'])
            ->assertSessionHasErrors('ulasan');
        // beli sampai selesai
        $this->actingAs($user)->post('/keranjang/'.$product->id, ['variant_id' => $m->id, 'qty' => 1]);
        $this->actingAs($user)->post('/checkout', [
            'nama_penerima' => 'U', 'hp_penerima' => '0812', 'alamat' => 'Jl',
            'kurir' => 'JNE', 'layanan' => 'REG',
        ]);
        $order = Order::latest()->first();
        $this->actingAs($admin)->put('/admin/order/'.$order->id, ['status' => 'dikirim', 'resi' => 'R1']);
        $this->actingAs($user)->post('/pesanan/'.$order->id.'/selesai');
        $this->actingAs($user)->post('/produk/'.$product->id.'/ulasan', ['rating' => 5, 'ulasan' => 'Bagus!'])
            ->assertSessionHasNoErrors();
        $this->assertDatabaseHas('reviews', ['product_id' => $product->id, 'rating' => 5]);
        // duplikat ditolak
        $this->actingAs($user)->post('/produk/'.$product->id.'/ulasan', ['rating' => 4])
            ->assertSessionHasErrors('ulasan');
        $this->actingAs($user)->get('/produk/'.$product->id)->assertSee('Bagus!');
    }

    public function test_tamu_bisa_buka_detail_produk(): void
    {
        ['product' => $product] = $this->seedShop();
        $this->get('/produk/'.$product->id)->assertOk()->assertSee($product->nama);
    }

    public function test_tracking_link_muncul_saat_ada_resi(): void
    {
        ['user' => $user, 'admin' => $admin, 'product' => $product, 'm' => $m] = $this->seedShop();
        $this->actingAs($user)->post('/keranjang/'.$product->id, ['variant_id' => $m->id, 'qty' => 1]);
        $this->actingAs($user)->post('/checkout', [
            'nama_penerima' => 'U', 'hp_penerima' => '0812', 'alamat' => 'Jl',
            'kurir' => 'JNE', 'layanan' => 'REG',
        ]);
        $order = Order::latest()->first();
        $this->actingAs($admin)->put('/admin/order/'.$order->id, ['status' => 'dikirim', 'resi' => 'JNE999']);
        $this->actingAs($user)->get('/pesanan/'.$order->id)
            ->assertOk()->assertSee('Lacak Paket')->assertSee('JNE999')->assertSee('jne.co.id');
    }

    public function test_tombol_back_ada_di_halaman_dalam(): void
    {
        ['user' => $user, 'admin' => $admin, 'product' => $product, 'm' => $m] = $this->seedShop();
        // user: halaman dalam punya back, hub tidak
        $this->actingAs($user)->get('/produk/'.$product->id)->assertSee('title="Kembali"', false);
        $this->actingAs($user)->post('/keranjang/'.$product->id, ['variant_id' => $m->id, 'qty' => 1]);
        $this->actingAs($user)->get('/keranjang')->assertSee('title="Kembali"', false)->assertDontSee('lg:col-span-2', false);
        $this->actingAs($user)->get('/akun')->assertOk()->assertDontSee('title="Kembali"', false);
        $this->actingAs($user)->get('/akun/pengaturan')->assertOk()->assertSee('title="Kembali"', false);
        // admin: sub-halaman kembali ke dashboard
        $this->actingAs($admin)->get('/admin')->assertOk()->assertDontSee('title="Kembali"', false);
        $this->actingAs($admin)->get('/admin/produk')->assertSee('title="Kembali"', false);
    }

    public function test_reseller_center_terproteksi(): void
    {
        ['user' => $user, 'admin' => $admin] = $this->seedShop();
        $pending = User::create(['name' => 'R', 'email' => 'r@t.id', 'password' => 'x', 'role' => 'reseller', 'status_reseller' => 'pending']);
        $this->actingAs($user)->get('/reseller')->assertRedirect('/akun');
        $this->actingAs($admin)->get('/reseller')->assertRedirect('/akun');
        $this->actingAs($pending)->get('/reseller')->assertRedirect('/akun');
        $this->get('/reseller')->assertRedirect('/akun');
    }

    public function test_reseller_dashboard_angka_benar(): void
    {
        ['product' => $product, 'm' => $m] = $this->seedShop();
        $r = User::create(['name' => 'R', 'email' => 'r@t.id', 'password' => 'x', 'role' => 'reseller', 'status_reseller' => 'approved']);
        $order = Order::create([
            'user_id' => $r->id, 'kode' => 'BB-T1', 'total_produk' => 160000, 'ongkir' => 10000,
            'total_bayar' => 170000, 'kurir' => 'JNE', 'layanan' => 'REG', 'status' => 'selesai', 'alamat' => 'Jl',
        ]);
        $order->items()->create(['product_id' => $product->id, 'variant_id' => $m->id, 'qty' => 2, 'harga_satuan' => 80000, 'subtotal' => 160000, 'tipe_harga' => 'reseller']);
        $this->actingAs($r)->get('/reseller')->assertOk()->assertSee('40,000')->assertSee('RESELLER CENTER');
        $this->actingAs($r)->get('/reseller/etalase')->assertOk()->assertSee('Etalase Harga Modal')->assertSee('20,000');
        $this->actingAs($r)->get('/reseller/hemat')->assertOk()->assertSee('40,000');
    }

    public function test_setelan_akun_per_peran(): void
    {
        ['user' => $user, 'admin' => $admin] = $this->seedShop();
        $r = User::create(['name' => 'R', 'email' => 'r@t.id', 'password' => 'x', 'role' => 'reseller', 'status_reseller' => 'approved']);
        // password lama salah ditolak
        $this->actingAs($user)->post('/akun/password', ['password_lama' => 'salah', 'password' => 'baru1234', 'password_confirmation' => 'baru1234'])
            ->assertSessionHasErrors('password_lama');
        // password benar berhasil diganti
        $this->actingAs($user)->post('/akun/password', ['password_lama' => 'x', 'password' => 'baru1234', 'password_confirmation' => 'baru1234'])
            ->assertSessionHasNoErrors();
        $this->assertTrue(Hash::check('baru1234', $user->fresh()->password));
        // enduser tidak bisa set nama toko, tapi bisa matikan notif (checkbox tidak dikirim)
        $this->actingAs($user)->put('/akun', ['name' => 'User', 'toko_nama' => 'Nakal']);
        $this->assertNull($user->fresh()->toko_nama);
        $this->assertFalse((bool) $user->fresh()->notif_wa);
        // reseller bisa set nama toko
        $this->actingAs($r)->put('/akun', ['name' => 'R', 'toko_nama' => 'Berkah Fashion', 'notif_wa' => '1']);
        $this->assertEquals('Berkah Fashion', $r->fresh()->toko_nama);
        $this->actingAs($r)->get('/akun')->assertSee('Berkah Fashion')->assertSee('Reseller Center');
        $this->actingAs($r)->get('/akun/pengaturan')->assertOk()->assertSee('Ganti Password')->assertSee('toko_nama', false);
        // admin lihat jalan pintas pengaturan
        $this->actingAs($admin)->get('/akun')->assertSee('/admin/pengaturan');
    }

    public function test_bantuan_terbuka_dan_faq_terkelola(): void
    {
        ['user' => $user, 'admin' => $admin] = $this->seedShop();
        Faq::create(['topik' => 'Order', 'pertanyaan' => 'Cara order?', 'jawaban' => 'Klik beli.', 'urutan' => 1]);
        Faq::create(['topik' => 'Bayar', 'pertanyaan' => 'Rahasia?', 'jawaban' => 'xx', 'urutan' => 2, 'is_active' => false]);
        // tamu bisa buka, yg nonaktif tidak tampil
        $this->get('/bantuan')->assertOk()->assertSee('Cara order?')->assertDontSee('Rahasia?');
        $this->get('/bantuan?topik=Order')->assertOk()->assertSee('Cara order?');
        $this->get('/faq')->assertOk()->assertSee('Cara order?');
        // user biasa tidak bisa kelola
        $this->actingAs($user)->get('/admin/faq')->assertRedirect('/');
        // admin bisa tambah + tampil + hapus
        $this->actingAs($admin)->post('/admin/faq', ['topik' => 'Akun', 'pertanyaan' => 'Lupa sandi?', 'jawaban' => 'Reset.', 'is_active' => '1'])
            ->assertRedirect();
        $this->assertDatabaseHas('faqs', ['pertanyaan' => 'Lupa sandi?']);
        $this->get('/bantuan')->assertSee('Lupa sandi?');
        $f = Faq::where('pertanyaan', 'Lupa sandi?')->first();
        $this->actingAs($admin)->delete('/admin/faq/'.$f->id)->assertRedirect();
        $this->assertDatabaseMissing('faqs', ['pertanyaan' => 'Lupa sandi?']);
    }

    public function test_hapus_akun_dijaga(): void
    {
        ['user' => $user, 'admin' => $admin, 'product' => $product, 'm' => $m] = $this->seedShop();
        // ada order aktif -> ditolak
        Order::create(['user_id' => $user->id, 'kode' => 'BB-X', 'total_produk' => 1, 'ongkir' => 0, 'total_bayar' => 1, 'kurir' => 'JNE', 'layanan' => 'REG', 'status' => 'diproses', 'alamat' => 'Jl']);
        $this->actingAs($user)->delete('/akun', ['konfirmasi' => 'HAPUS', 'password' => 'x'])
            ->assertSessionHasErrors('hapus');
        // tanpa order aktif -> anonim + logout
        $user2 = User::create(['name' => 'Z', 'email' => 'z@t.id', 'password' => 'x', 'role' => 'enduser']);
        $this->actingAs($user2)->delete('/akun', ['konfirmasi' => 'HAPUS', 'password' => 'x'])->assertRedirect('/');
        $this->assertEquals('Pengguna Dihapus', $user2->fresh()->name);
        $this->assertGuest();
    }

    public function test_opname_menyesuaikan_stok(): void
    {
        ['admin' => $admin, 'product' => $product, 'm' => $m, 'l' => $l] = $this->seedShop();
        $this->actingAs($admin)->get('/admin/opname')->assertOk()->assertSee('Stok Opname');
        $this->actingAs($admin)->post('/admin/opname', ['fisik' => [$m->id => 7, $l->id => 5]])
            ->assertRedirect();
        $this->assertEquals(7, $m->fresh()->stok);
        $this->assertEquals(5, $l->fresh()->stok);
        $this->assertEquals(12, $product->fresh()->stok);
        $this->assertDatabaseHas('stock_mutations', ['variant_id' => $m->id, 'keterangan' => 'Opname '.now()->format('d M Y')]);
    }

    public function test_penerimaan_dua_tahap(): void
    {
        ['admin' => $admin, 'product' => $product, 'm' => $m, 'l' => $l] = $this->seedShop();
        $stokM = $m->stok;
        // Tahap 1: catat -> stok BELUM berubah
        $this->actingAs($admin)->post('/admin/penerimaan', [
            'no_surat_jalan' => 'SJ-001', 'supplier' => 'Guangzhou',
            'items' => [
                ['product_id' => $product->id, 'variant_id' => $m->id, 'qty_klaim' => 10],
                ['product_id' => $product->id, 'variant_id' => $l->id, 'qty_klaim' => 5],
            ],
        ])->assertRedirect();
        $p = Penerimaan::latest()->first();
        $this->assertEquals('dicatat', $p->status);
        $this->assertEquals($stokM, $m->fresh()->stok);
        $this->actingAs($admin)->get('/admin/penerimaan')->assertOk()->assertSee('SJ-001', false);
        // Varian tidak cocok -> ditolak
        $this->actingAs($admin)->post('/admin/penerimaan', [
            'no_surat_jalan' => 'SJ-X', 'items' => [['product_id' => $product->id, 'variant_id' => 999999, 'qty_klaim' => 1]],
        ])->assertSessionHasErrors();
        // Tahap 2: lolos+rusak harus pas klaim
        $item = $p->items()->first();
        $this->actingAs($admin)->post('/admin/penerimaan/'.$p->id.'/verifikasi', [
            'hasil' => [$item->id => ['lolos' => 8, 'rusak' => 1]],
        ])->assertSessionHasErrors('hasil');
        // QC sah: stok bertambah sejumlah lolos saja
        $hasil = [];
        foreach ($p->items as $it) {
            $hasil[$it->id] = ['lolos' => $it->qty_klaim - 1, 'rusak' => 1];
        }
        $this->actingAs($admin)->post('/admin/penerimaan/'.$p->id.'/verifikasi', ['hasil' => $hasil])->assertRedirect('/admin/penerimaan');
        $this->assertEquals($stokM + 9, $m->fresh()->stok);
        $this->assertEquals('diverifikasi', $p->fresh()->status);
        $this->assertDatabaseHas('stock_mutations', ['keterangan' => 'Terima SJ-001']);
        $this->assertDatabaseHas('activity_logs', ['aksi' => 'terima_verifikasi']);
        // Verifikasi kedua ditolak
        $this->actingAs($admin)->post('/admin/penerimaan/'.$p->id.'/verifikasi', ['hasil' => $hasil])->assertStatus(400);
    }

    public function test_stok_keluar_manual(): void
    {
        ['admin' => $admin, 'product' => $product, 'm' => $m] = $this->seedShop();
        $this->actingAs($admin)->get('/admin/stok-keluar')->assertOk();
        // Melebihi stok ditolak
        $this->actingAs($admin)->post('/admin/stok-keluar', ['variant_id' => $m->id, 'qty' => 999, 'alasan' => 'rusak'])->assertSessionHasErrors('qty');
        $this->assertEquals(10, $m->fresh()->stok);
        // Sah: stok berkurang + mutasi + log
        $this->actingAs($admin)->post('/admin/stok-keluar', ['variant_id' => $m->id, 'qty' => 3, 'alasan' => 'rusak', 'catatan' => 'pecah'])->assertRedirect('/admin/mutasi');
        $this->assertEquals(7, $m->fresh()->stok);
        $this->assertEquals(12, $product->fresh()->stok);
        $this->assertDatabaseHas('stock_mutations', ['variant_id' => $m->id, 'tipe' => 'keluar', 'qty' => 3]);
        $this->assertDatabaseHas('activity_logs', ['aksi' => 'stok_keluar']);
    }

    public function test_opname_sesi_terpandu(): void
    {
        ['admin' => $admin, 'product' => $product, 'm' => $m, 'l' => $l] = $this->seedShop();
        // Mulai sesi -> snapshot dibuat
        $this->actingAs($admin)->post('/admin/opname/mulai', ['cakupan' => 'semua'])->assertRedirect();
        $sesi = OpnameSesi::latest()->first();
        $this->assertEquals('jalan', $sesi->status);
        $this->assertEquals(2, $sesi->items()->count());
        // Sesi kedua ditolak selama ada yang jalan
        $this->actingAs($admin)->post('/admin/opname/mulai', ['cakupan' => 'semua'])
            ->assertRedirect('/admin/opname/'.$sesi->id);
        $this->assertEquals(1, OpnameSesi::where('status', 'jalan')->count());
        // Hitung: m cocok (10->10), l selisih kecil (5->7, diff 2, 40%? >20% -> BESAR!)
        $this->actingAs($admin)->post('/admin/opname/'.$sesi->id.'/hitung', ['fisik' => [$m->id => 10, $l->id => 6], 'next' => 1])->assertRedirect();
        // Review tampil, sahkan tanpa foto untuk selisih kecil (diff 1 = 20%, tidak >20%)
        $this->actingAs($admin)->get('/admin/opname/'.$sesi->id.'/review')->assertOk()->assertSee('+1', false);
        $this->actingAs($admin)->post('/admin/opname/'.$sesi->id.'/sahkan')->assertRedirect('/admin/opname');
        $this->assertEquals(6, $l->fresh()->stok);
        $this->assertEquals(16, $product->fresh()->stok);
        $this->assertEquals('selesai', $sesi->fresh()->status);
        $this->assertDatabaseHas('stock_mutations', ['variant_id' => $l->id]);
        $this->assertDatabaseHas('activity_logs', ['aksi' => 'opname_sahkan']);
    }

    public function test_opname_selisih_besar_wajib_foto(): void
    {
        ['admin' => $admin, 'm' => $m] = $this->seedShop();
        $this->actingAs($admin)->post('/admin/opname/mulai', ['cakupan' => 'semua'])->assertRedirect();
        $sesi = OpnameSesi::latest()->first();
        // Selisih besar: snapshot 10 -> fisik 20 (diff +10)
        $this->actingAs($admin)->post('/admin/opname/'.$sesi->id.'/hitung', ['fisik' => [$m->id => 20], 'next' => 1]);
        OpnameItem::where('sesi_id', $sesi->id)->whereNull('fisik')->update(['fisik' => 5]);
        $this->actingAs($admin)->post('/admin/opname/'.$sesi->id.'/sahkan')->assertSessionHasErrors('foto');
        $this->assertEquals('jalan', $sesi->fresh()->status);
        // Dengan foto -> lolos
        $foto = UploadedFile::fake()->image('bukti.jpg');
        $this->actingAs($admin)->post('/admin/opname/'.$sesi->id.'/sahkan', ['foto' => [$m->id => $foto]])->assertRedirect('/admin/opname');
        $this->assertEquals(20, $m->fresh()->stok);
        $this->assertEquals('selesai', $sesi->fresh()->status);
    }

    public function test_export_csv_dan_label(): void
    {
        ['admin' => $admin, 'user' => $user, 'product' => $product, 'm' => $m] = $this->seedShop();
        $order = Order::create(['user_id' => $user->id, 'kode' => 'BB-E1', 'total_produk' => 5, 'ongkir' => 1, 'total_bayar' => 6, 'kurir' => 'JNE', 'layanan' => 'REG', 'status' => 'diproses', 'alamat' => 'Jl']);
        $r = $this->actingAs($admin)->get('/admin/order/export');
        $r->assertOk()->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('BB-E1', $r->getContent());
        $this->actingAs($admin)->get('/admin/laporan/export')->assertOk();
        $this->actingAs($admin)->get('/admin/order/'.$order->id.'/label')->assertOk()->assertSee('BB-E1');
        // user biasa ditolak
        $this->actingAs($user)->get('/admin/order/export')->assertRedirect('/');
    }

    public function test_upload_bukti_hanya_saat_menunggu_bayar(): void
    {
        ['user' => $user, 'product' => $product, 'm' => $m] = $this->seedShop();
        $file = UploadedFile::fake()->image('bukti.jpg');
        $selesai = Order::create(['user_id' => $user->id, 'kode' => 'BB-S1', 'total_produk' => 1, 'ongkir' => 0, 'total_bayar' => 1, 'kurir' => 'JNE', 'layanan' => 'REG', 'status' => 'selesai', 'alamat' => 'Jl']);
        $this->actingAs($user)->post('/bayar/'.$selesai->id.'/bukti', ['bukti' => $file])->assertStatus(400);
        $this->assertEquals('selesai', $selesai->fresh()->status);
        $tunggu = Order::create(['user_id' => $user->id, 'kode' => 'BB-S2', 'total_produk' => 1, 'ongkir' => 0, 'total_bayar' => 1, 'kurir' => 'JNE', 'layanan' => 'REG', 'status' => 'menunggu_bayar', 'alamat' => 'Jl']);
        $this->actingAs($user)->post('/bayar/'.$tunggu->id.'/bukti', ['bukti' => UploadedFile::fake()->image('b2.jpg')])->assertRedirect();
        $this->assertEquals('diproses', $tunggu->fresh()->status);
    }

    public function test_price_service_paritas_tier(): void
    {
        ['product' => $product] = $this->seedShop(); // min_reseller=2, enduser 100rb, reseller 80rb
        $end = User::create(['name' => 'E', 'email' => 'e@t.id', 'password' => 'x', 'role' => 'enduser']);
        $res = User::create(['name' => 'R', 'email' => 'r@t.id', 'password' => 'x', 'role' => 'reseller', 'status_reseller' => 'approved']);
        $pend = User::create(['name' => 'P', 'email' => 'p@t.id', 'password' => 'x', 'role' => 'reseller', 'status_reseller' => 'pending']);
        $this->assertEquals(['harga' => 100000, 'tipe' => 'enduser'], PriceService::untuk($product, $end, 10));
        $this->assertEquals(['harga' => 100000, 'tipe' => 'enduser'], PriceService::untuk($product, $res, 1));
        $this->assertEquals(['harga' => 80000, 'tipe' => 'reseller'], PriceService::untuk($product, $res, 2));
        $this->assertEquals(['harga' => 100000, 'tipe' => 'enduser'], PriceService::untuk($product, $pend, 10));
        $this->assertEquals(['harga' => 100000, 'tipe' => 'enduser'], PriceService::untuk($product, null, 10));
    }

    public function test_aktivitas_admin_tercatat(): void
    {
        ['user' => $user, 'admin' => $admin] = $this->seedShop();
        $pend = User::create(['name' => 'P', 'email' => 'p@t.id', 'password' => 'x', 'role' => 'reseller', 'status_reseller' => 'pending', 'email_verified_at' => now(), 'toko_nama' => 'Toko P', 'nik' => '3271010101010003', 'hp' => '628120000003', 'alamat' => 'Jl P']);
        $this->actingAs($admin)->post('/admin/reseller/'.$pend->id.'/approve')->assertRedirect();
        $this->assertDatabaseHas('activity_logs', ['aksi' => 'reseller_approve', 'actor_id' => $admin->id]);
        $this->actingAs($admin)->get('/admin/aktivitas')->assertOk()->assertSee('reseller_approve');
        // user biasa ditolak masuk halaman aktivitas
        $this->actingAs($user)->get('/admin/aktivitas')->assertRedirect('/');
    }

    public function test_approve_reseller_ketat(): void
    {
        ['admin' => $admin] = $this->seedShop();
        $buat = fn (string $email, array $extra) => User::create(array_merge(
            ['name' => 'R', 'email' => $email, 'password' => 'x', 'role' => 'reseller', 'status_reseller' => 'pending'], $extra
        ));
        // Data kurang -> ditolak + disebut kekurangannya, status tetap pending
        $kurang = $buat('k@t.id', []);
        $this->actingAs($admin)->post('/admin/reseller/'.$kurang->id.'/approve')->assertSessionHasErrors('reseller');
        $this->assertEquals('pending', $kurang->fresh()->status_reseller);
        $this->assertDatabaseHas('activity_logs', ['aksi' => 'reseller_approve_gagal']);
        // Tolak tanpa alasan / alasan pendek -> ditolak
        $this->actingAs($admin)->post('/admin/reseller/'.$kurang->id.'/reject', [])->assertSessionHasErrors('alasan');
        $this->actingAs($admin)->post('/admin/reseller/'.$kurang->id.'/reject', ['alasan' => 'kurang'])->assertSessionHasErrors('alasan');
        // Tolak beralasan -> rejected + tercatat
        $this->actingAs($admin)->post('/admin/reseller/'.$kurang->id.'/reject', ['alasan' => 'Foto KTP tidak jelas sekali'])->assertRedirect();
        $this->assertEquals('rejected', $kurang->fresh()->status_reseller);
        // Approve langsung dari rejected -> ditolak, harus via review-ulang
        $this->actingAs($admin)->post('/admin/reseller/'.$kurang->id.'/approve')->assertSessionHasErrors('reseller');
        $this->actingAs($admin)->post('/admin/reseller/'.$kurang->id.'/review-ulang')->assertRedirect();
        $this->assertEquals('pending', $kurang->fresh()->status_reseller);
        // Lengkap -> approve lolos
        $lengkap = $buat('l@t.id', ['email_verified_at' => now(), 'toko_nama' => 'Toko L', 'nik' => '3271010101010004', 'hp' => '628120000004', 'alamat' => 'Jl L']);
        $this->actingAs($admin)->post('/admin/reseller/'.$lengkap->id.'/approve')->assertRedirect()->assertSessionHasNoErrors();
        $this->assertEquals('approved', $lengkap->fresh()->status_reseller);
    }

    public function test_tier_harga_per_qty(): void
    {
        ['product' => $product, 'admin' => $admin] = $this->seedShop();
        $res = User::create(['name' => 'R', 'email' => 'r@t.id', 'password' => 'x', 'role' => 'reseller', 'status_reseller' => 'approved']);
        ProductPrice::create(['product_id' => $product->id, 'level' => 'reseller', 'min_qty' => 1, 'price' => 100000]);
        ProductPrice::create(['product_id' => $product->id, 'level' => 'reseller', 'min_qty' => 2, 'price' => 80000]);
        ProductPrice::create(['product_id' => $product->id, 'level' => 'reseller', 'min_qty' => 10, 'price' => 70000]);
        $this->assertEquals(80000, PriceService::untuk($product, $res, 5)['harga']);
        $this->assertEquals(70000, PriceService::untuk($product, $res, 10)['harga']);
        // admin tambah tier via HTTP
        $this->actingAs($admin)->post('/admin/produk/'.$product->id.'/tier', ['level' => 'user', 'min_qty' => 5, 'price' => 90000])->assertRedirect();
        $this->assertDatabaseHas('product_prices', ['product_id' => $product->id, 'level' => 'user', 'min_qty' => 5]);
        // tier terakhir tiap level tidak boleh dihapus
        $last = ProductPrice::where('product_id', $product->id)->where('level', 'user')->first();
        ProductPrice::where('product_id', $product->id)->where('level', 'user')->where('id', '!=', $last->id)->delete();
        $this->actingAs($admin)->delete('/admin/tier/'.$last->id)->assertSessionHasErrors('tier');
        // fallback kolom lama saat tier dihapus semua manual
        ProductPrice::where('product_id', $product->id)->delete();
        $this->assertEquals(['harga' => 80000, 'tipe' => 'reseller'], PriceService::untuk($product->fresh(), $res, 2));
        $this->assertEquals(80000, PriceService::resellerTermurah($product->fresh()));
        $this->assertEquals(2, PriceService::minReseller($product->fresh()));
    }

    public function test_berat_tagih_volumetrik(): void
    {
        $O = OngkirDummy::class;
        // Tumbler: berat tapi kecil -> berat asli menang (JNE ÷6000)
        $t = $O::beratTagih([['berat_gram' => 800, 'qty' => 1, 'p' => 10, 'l' => 10, 't' => 20]], 'JNE');
        $this->assertEquals(800, $t['tagih']);
        // Rak plastik 60x40x10 via Sentral (÷4000): 24000/4000=6kg -> volumetrik menang
        $r = $O::beratTagih([['berat_gram' => 1500, 'qty' => 1, 'p' => 60, 'l' => 40, 't' => 10]], 'SENTRAL');
        $this->assertEquals(6000, $r['tagih']);
        // J&T ÷5000: 24000/5000=4,8kg -> 4800 gram
        $j = $O::beratTagih([['berat_gram' => 1500, 'qty' => 1, 'p' => 60, 'l' => 40, 't' => 10]], 'JNT');
        $this->assertEquals(4800, $j['tagih']);
        // Tanpa dimensi -> pakai berat asli
        $n = $O::beratTagih([['berat_gram' => 500, 'qty' => 2, 'p' => 0, 'l' => 0, 't' => 0]], 'LION');
        $this->assertEquals(1000, $n['tagih']);
    }

    public function test_checkout_kurir_baru(): void
    {
        ['user' => $user, 'product' => $product, 'm' => $m] = $this->seedShop();
        $product->update(['panjang_cm' => 60, 'lebar_cm' => 40, 'tinggi_cm' => 10, 'berat_gram' => 1500]);
        $this->actingAs($user)->post('/keranjang/'.$product->id, ['variant_id' => $m->id, 'qty' => 1]);
        // kurir tidak dikenal ditolak
        $this->actingAs($user)->post('/checkout', [
            'nama_penerima' => 'U', 'hp_penerima' => '0812', 'alamat' => 'Jl',
            'kurir' => 'TIKI', 'layanan' => 'REG',
        ])->assertSessionHasErrors('kurir');
        // Sentral DARAT: volumetrik 6kg x 8000 = 48000
        $this->actingAs($user)->post('/checkout', [
            'nama_penerima' => 'U', 'hp_penerima' => '0812', 'alamat' => 'Jl',
            'kurir' => 'SENTRAL', 'layanan' => 'DARAT',
        ])->assertRedirect();
        $order = Order::latest()->first();
        $this->assertEquals(6000, $order->berat_tagih_gram);
        $this->assertEquals(48000, $order->ongkir);
    }

    public function test_checkout_ditolak_stok_berubah(): void
    {
        ['user' => $user, 'product' => $product, 'm' => $m] = $this->seedShop();
        $this->actingAs($user)->post('/keranjang/'.$product->id, ['variant_id' => $m->id, 'qty' => 2]);
        $m->update(['stok' => 1]); // stok menyusut setelah masuk keranjang
        $this->actingAs($user)->post('/checkout', [
            'nama_penerima' => 'U', 'hp_penerima' => '0812', 'alamat' => 'Jl',
            'kurir' => 'JNE', 'layanan' => 'REG',
        ])->assertSessionHasErrors('order');
        $this->assertEquals(0, Order::count());
        $this->assertEquals(1, $m->fresh()->stok); // tidak negatif / tidak berubah
    }

    public function test_rekonsiliasi_menyelaraskan_total(): void
    {
        ['admin' => $admin, 'user' => $user, 'product' => $product, 'm' => $m, 'l' => $l] = $this->seedShop();
        $product->update(['stok' => 999]); // rusakkan total manual
        $this->actingAs($user)->post('/admin/rekonsiliasi')->assertRedirect('/');
        $this->actingAs($admin)->get('/admin')->assertSee('selisih');
        $this->actingAs($admin)->post('/admin/rekonsiliasi')->assertRedirect();
        $this->assertEquals(15, $product->fresh()->stok);
        $this->assertDatabaseHas('stock_mutations', ['product_id' => $product->id, 'keterangan' => 'Rekonsiliasi otomatis']);
        $this->actingAs($admin)->get('/admin')->assertDontSee('selisih dari jumlah varian');
    }

    public function test_rekap_dan_struk(): void
    {
        ['admin' => $admin, 'user' => $user, 'product' => $product, 'm' => $m] = $this->seedShop();
        $order = Order::create(['user_id' => $user->id, 'kode' => 'BB-R1', 'total_produk' => 100000, 'ongkir' => 11000, 'total_bayar' => 111000, 'kurir' => 'JNE', 'layanan' => 'REG', 'status' => 'diproses', 'alamat' => 'Jl']);
        $order->items()->create(['product_id' => $product->id, 'variant_id' => $m->id, 'qty' => 1, 'harga_satuan' => 100000, 'subtotal' => 100000, 'tipe_harga' => 'enduser']);
        $this->actingAs($admin)->get('/admin/laporan?tab=harian')->assertOk()->assertSee('BB-R1')->assertSee('111,000');
        $this->actingAs($admin)->get('/admin/laporan?tab=harian&tanggal=2000-01-01')->assertOk()->assertSee('Tidak ada order');
        $this->actingAs($admin)->get('/admin/rekap')->assertRedirect('/admin/laporan?tab=harian');
        $this->actingAs($admin)->get('/admin/rekap?tanggal=2000-01-01')->assertRedirect('/admin/laporan?tab=harian&tanggal=2000-01-01');
        $this->actingAs($admin)->get('/admin/order/'.$order->id.'/struk')->assertOk()->assertSee('BB-R1')->assertSee('111,000');
        $this->actingAs($user)->get('/admin/rekap')->assertRedirect('/');
    }

    public function test_duplikat_dan_arsip_massal(): void
    {
        ['admin' => $admin, 'user' => $user, 'product' => $product, 'm' => $m] = $this->seedShop();
        ProductPrice::create(['product_id' => $product->id, 'level' => 'reseller', 'min_qty' => 2, 'price' => 80000]);
        $this->actingAs($user)->post('/admin/produk/'.$product->id.'/duplikat')->assertRedirect('/');
        $this->actingAs($admin)->post('/admin/produk/'.$product->id.'/duplikat')->assertRedirect();
        $copy = Product::where('nama', 'Kaos (Copy)')->first();
        $this->assertNotNull($copy);
        $this->assertEquals(0, $copy->stok);
        $this->assertFalse((bool) $copy->is_active);
        $this->assertEquals(2, $copy->variants()->count());
        $this->assertEquals(0, $copy->variants()->sum('stok'));
        $this->assertEquals(1, $copy->prices()->where('level', 'reseller')->count());
        $this->actingAs($admin)->get('/admin/produk')->assertSee('Kaos (Copy)');
        // arsip massal: hanya yang dicentang
        $this->actingAs($admin)->post('/admin/produk/arsip-massal', ['ids' => [$copy->id]])->assertRedirect();
        $this->assertFalse((bool) $copy->fresh()->is_active);
        $this->assertTrue((bool) $product->fresh()->is_active);
        // validasi: tanpa ids ditolak
        $this->actingAs($admin)->post('/admin/produk/arsip-massal', [])->assertSessionHasErrors('ids');
    }

    public function test_login_dikunci_setelah_gagal_berulang(): void
    {
        ['user' => $user] = $this->seedShop();
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => $user->email, 'password' => 'salah']);
        }
        $this->post('/login', ['email' => $user->email, 'password' => 'salah'])
            ->assertSessionHasErrors('email');
        $this->assertStringContainsString('Terlalu banyak', session('errors')->first('email'));
        $this->assertDatabaseHas('activity_logs', ['aksi' => 'login_gagal']);
        // password benar pun ditolak selama dikunci
        $this->post('/login', ['email' => $user->email, 'password' => 'x'])
            ->assertSessionHasErrors('email');
    }

    public function test_honeypot_menolak_robot(): void
    {
        ['user' => $user] = $this->seedShop();
        $this->post('/login', ['email' => $user->email, 'password' => 'x', 'website' => 'http://spam.bot'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
        $this->post('/register', ['name' => 'B', 'email' => 'b@t.id', 'password' => 'rahasia123', 'role' => 'enduser', 'website' => 'bot'])
            ->assertSessionHasErrors('email');
        $this->assertDatabaseMissing('users', ['email' => 'b@t.id']);
    }

    public function test_password_minimal_8(): void
    {
        $this->post('/register', ['name' => 'C', 'email' => 'c@t.id', 'password' => 'pendek7', 'role' => 'enduser'])
            ->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('users', ['email' => 'c@t.id']);
    }

    public function test_captcha_mati_login_bebas(): void
    {
        ['user' => $user] = $this->seedShop();
        // default: saklar mati -> tanpa token tetap lolos
        $this->post('/login', ['email' => $user->email, 'password' => 'x'])
            ->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    public function test_captcha_nyala_menolak_tanpa_token(): void
    {
        ['user' => $user] = $this->seedShop();
        Setting::set('turnstile_wajib', '1');
        Setting::set('turnstile_site_key', 'site-palsu');
        Setting::set('turnstile_secret', 'kunci-palsu');
        Http::fake(['challenges.cloudflare.com/*' => Http::response(['success' => false], 200)]);
        $this->post('/login', ['email' => $user->email, 'password' => 'x'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_captcha_otomatis_mati_di_link_tunnel(): void
    {
        ['user' => $user] = $this->seedShop();
        Setting::set('turnstile_wajib', '1');
        Setting::set('turnstile_secret', 'kunci-palsu');
        // host tunnel: bebas tanpa token, widget pun disembunyikan
        $this->call('POST', '/login', ['email' => $user->email, 'password' => 'x'], [], [], ['HTTP_HOST' => 'demo.trycloudflare.com'])
            ->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
        $this->call('GET', '/login', [], [], [], ['HTTP_HOST' => 'demo.trycloudflare.com'])
            ->assertOk()->assertDontSee('cf-turnstile', false);
    }

    public function test_toggle_jelaskan_disembunyikan(): void
    {
        ['admin' => $admin] = $this->seedShop();
        $this->get('/login')->assertOk()->assertDontSee('jelaskanToggle', false)->assertDontSee('/js/jelaskan.js', false);
        $this->actingAs($admin)->get('/admin')->assertOk()->assertDontSee('jelaskanToggle', false);
        // rute demo/panduan/tur sudah dihapus total
        $this->get('/demo')->assertNotFound();
        $this->get('/panduan')->assertNotFound();
        $this->actingAs($admin)->get('/admin/panduan')->assertNotFound();
    }

    public function test_walkthrough_semua_halaman(): void
    {
        ['user' => $user, 'admin' => $admin, 'product' => $product, 'm' => $m] = $this->seedShop();
        $res = User::create(['name' => 'R', 'email' => 'r@t.id', 'password' => 'x', 'role' => 'reseller', 'status_reseller' => 'approved']);
        $order = Order::create(['user_id' => $user->id, 'kode' => 'BB-W', 'total_produk' => 1, 'ongkir' => 0, 'total_bayar' => 1, 'kurir' => 'JNE', 'layanan' => 'REG', 'status' => 'menunggu_bayar', 'alamat' => 'Jl']);
        $this->actingAs($user)->post('/keranjang/'.$product->id, ['variant_id' => $m->id, 'qty' => 1]);
        $pembeli = ['/', '/produk/'.$product->id, '/keranjang', '/checkout', '/bayar/'.$order->id, '/pesanan', '/pesanan/'.$order->id, '/akun', '/akun/pengaturan', '/bantuan'];
        foreach ($pembeli as $u) {
            $this->actingAs($user)->get($u)->assertOk("gagal di $u");
        }
        foreach (['/reseller', '/reseller/etalase', '/reseller/hemat'] as $u) {
            $this->actingAs($res)->get($u)->assertOk("gagal di $u");
        }
        foreach (['/admin', '/admin/order', '/admin/order/'.$order->id, '/admin/produk', '/admin/verifikasi', '/admin/reseller', '/admin/users', '/admin/laporan', '/admin/laporan?tab=harian', '/admin/mutasi', '/admin/opname', '/admin/voucher', '/admin/retur', '/admin/ulasan', '/admin/faq', '/admin/notifikasi', '/admin/pengaturan', '/admin/integrasi', '/admin/aktivitas'] as $u) {
            $this->actingAs($admin)->get($u)->assertOk("gagal di $u");
        }
    }

    public function test_layout_dua_kolom_desktop(): void
    {
        ['user' => $user, 'admin' => $admin, 'product' => $product, 'm' => $m] = $this->seedShop();
        $order = Order::create(['user_id' => $user->id, 'kode' => 'BB-D', 'total_produk' => 1, 'ongkir' => 0, 'total_bayar' => 1, 'kurir' => 'JNE', 'layanan' => 'REG', 'status' => 'menunggu_bayar', 'alamat' => 'Jl']);
        $this->actingAs($user)->post('/keranjang/'.$product->id, ['variant_id' => $m->id, 'qty' => 1]);
        $this->actingAs($user)->get('/keranjang')->assertDontSee('lg:col-span-2', false);
        $this->actingAs($user)->get('/checkout')->assertDontSee('lg:order-2', false);
        $this->actingAs($user)->get('/bayar/'.$order->id)->assertDontSee('lg:sticky', false);
        $this->actingAs($user)->get('/pesanan/'.$order->id)->assertDontSee('lg:sticky', false);
    }

    public function test_tidak_ada_sisa_tampilan_web(): void
    {
        ['admin' => $admin] = $this->seedShop();
        foreach (['/admin/order', '/admin/users', '/admin/reseller', '/admin/verifikasi', '/admin/mutasi', '/admin/notifikasi', '/admin/aktivitas', '/admin/retur', '/admin/produk'] as $u) {
            $r = $this->actingAs($admin)->get($u)->assertOk();
            $r->assertDontSee('lg:table', false)->assertDontSee('lg:grid', false)->assertDontSee('lg:hidden', false);
        }
        $this->actingAs($admin)->get('/admin')->assertDontSee('class="aside"', false);
    }
}
