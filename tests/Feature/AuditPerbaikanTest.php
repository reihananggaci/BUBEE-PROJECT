<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Cart;
use App\Models\Faq;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Penerimaan;
use App\Models\Product;
use App\Models\Retur;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditPerbaikanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }

    private function seedAudit(): array
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'admin@audit.id', 'password' => 'x', 'role' => 'admin', 'email_verified_at' => now()]);
        $user = User::create(['name' => 'User', 'email' => 'user@audit.id', 'password' => 'x', 'role' => 'enduser', 'hp' => '0811', 'email_verified_at' => now()]);
        $product = Product::create([
            'nama' => 'Kaos Audit', 'berat_gram' => 200, 'stok' => 0,
            'harga_enduser' => 100000, 'harga_reseller' => 80000, 'min_reseller' => 2,
        ]);
        $variant = $product->variants()->create(['ukuran' => 'M', 'warna' => 'Hitam', 'stok' => 5, 'sku' => 'AUD-M']);
        $product->syncStok();

        return compact('admin', 'user', 'product', 'variant');
    }

    private function order(User $user, Product $product, $variant, array $attributes = []): Order
    {
        $order = Order::create($attributes + [
            'user_id' => $user->id, 'kode' => 'BB-AUD-'.rand(1000, 9999),
            'total_produk' => 100000, 'ongkir' => 10000, 'diskon' => 0, 'total_bayar' => 110000,
            'kurir' => 'JNE', 'layanan' => 'REG', 'status' => 'diproses', 'alamat' => 'Jl Audit',
        ]);
        $order->items()->create([
            'product_id' => $product->id, 'variant_id' => $variant->id,
            'qty' => 1, 'harga_satuan' => 100000, 'subtotal' => 100000, 'tipe_harga' => 'enduser',
        ]);

        return $order;
    }

    public function test_penerimaan_barang_baru_membuat_varian_default_yang_bisa_dibeli(): void
    {
        ['admin' => $admin, 'user' => $user] = $this->seedAudit();

        $this->actingAs($admin)->post('/admin/penerimaan', [
            'no_surat_jalan' => 'SJ-AUD-1',
            'items' => [[
                'is_baru' => 1, 'nama_baru' => 'Sandal Baru', 'qty_klaim' => 4,
                'harga_enduser' => 50000, 'harga_reseller' => 40000,
            ]],
        ])->assertRedirect();

        $produk = Product::where('nama', 'Sandal Baru')->firstOrFail();
        $variant = $produk->variants()->first();
        $this->assertNotNull($variant);
        $this->assertEquals('All Size', $variant->label());
        $this->assertEquals('BB-'.$produk->id.'-1', $variant->sku);

        $p = Penerimaan::latest()->firstOrFail();
        $item = $p->items()->firstOrFail();
        $this->assertEquals($variant->id, $item->variant_id);

        $this->actingAs($admin)->post('/admin/penerimaan/'.$p->id.'/verifikasi', [
            'hasil' => [$item->id => ['lolos' => 3, 'rusak' => 1]],
        ])->assertRedirect('/admin/penerimaan');

        $this->assertEquals(3, $variant->fresh()->stok);
        $this->assertEquals(3, $produk->fresh()->stok);

        $this->actingAs($user)->post('/keranjang/'.$produk->id, [
            'variant_id' => $variant->id, 'qty' => 2,
        ])->assertRedirect('/keranjang');
        $this->assertDatabaseHas('carts', ['product_id' => $produk->id, 'variant_id' => $variant->id, 'qty' => 2]);
    }

    public function test_verifikasi_setuju_wajib_resi_dan_hanya_status_diproses(): void
    {
        ['admin' => $admin, 'user' => $user, 'product' => $product, 'variant' => $variant] = $this->seedAudit();

        $order = $this->order($user, $product, $variant, ['bukti_bayar' => 'bukti/a.jpg']);

        $this->actingAs($admin)->post('/admin/verifikasi/'.$order->id.'/setuju')
            ->assertSessionHasErrors('resi');
        $this->assertEquals('diproses', $order->fresh()->status);

        $this->actingAs($admin)->post('/admin/verifikasi/'.$order->id.'/setuju', ['resi' => 'JNE-123'])
            ->assertRedirect();
        $order->refresh();
        $this->assertEquals('dikirim', $order->status);
        $this->assertEquals('JNE-123', $order->resi);

        $this->actingAs($admin)->post('/admin/verifikasi/'.$order->id.'/setuju', ['resi' => 'JNE-456'])
            ->assertStatus(400);

        $menunggu = $this->order($user, $product, $variant, ['status' => 'menunggu_bayar', 'bukti_bayar' => 'bukti/b.jpg']);
        $this->actingAs($admin)->post('/admin/verifikasi/'.$menunggu->id.'/setuju', ['resi' => 'X'])
            ->assertStatus(400);
    }

    public function test_verifikasi_tolak_hanya_untuk_bukti_yang_diproses(): void
    {
        ['admin' => $admin, 'user' => $user, 'product' => $product, 'variant' => $variant] = $this->seedAudit();

        $dikirim = $this->order($user, $product, $variant, ['status' => 'dikirim', 'bukti_bayar' => 'bukti/c.jpg']);
        $this->actingAs($admin)->post('/admin/verifikasi/'.$dikirim->id.'/tolak')->assertStatus(400);
        $this->assertEquals('dikirim', $dikirim->fresh()->status);
        $this->assertEquals(5, $variant->fresh()->stok);

        $diproses = $this->order($user, $product, $variant, ['bukti_bayar' => 'bukti/d.jpg']);
        $this->actingAs($admin)->post('/admin/verifikasi/'.$diproses->id.'/tolak')->assertRedirect();
        $this->assertEquals('batal', $diproses->fresh()->status);
        $this->assertEquals(6, $variant->fresh()->stok);
    }

    public function test_order_update_tidak_bisa_batalkan_yang_sudah_dikirim_dan_resi_tidak_hilang(): void
    {
        ['admin' => $admin, 'user' => $user, 'product' => $product, 'variant' => $variant] = $this->seedAudit();

        $dikirim = $this->order($user, $product, $variant, ['status' => 'dikirim', 'resi' => 'JNE-999']);
        $this->actingAs($admin)->put('/admin/order/'.$dikirim->id, ['status' => 'batal'])
            ->assertSessionHasErrors('status');
        $this->assertEquals('dikirim', $dikirim->fresh()->status);
        $this->assertEquals(5, $variant->fresh()->stok);

        $this->actingAs($admin)->put('/admin/order/'.$dikirim->id, ['status' => 'selesai'])
            ->assertRedirect();
        $dikirim->refresh();
        $this->assertEquals('selesai', $dikirim->status);
        $this->assertEquals('JNE-999', $dikirim->resi);

        $diproses = $this->order($user, $product, $variant);
        $this->actingAs($admin)->put('/admin/order/'.$diproses->id, ['status' => 'batal'])
            ->assertRedirect();
        $this->assertEquals('batal', $diproses->fresh()->status);
        $this->assertEquals(6, $variant->fresh()->stok);
    }

    public function test_retur_selesai_tidak_bisa_diubah_dan_stok_tidak_dobel(): void
    {
        ['admin' => $admin, 'user' => $user, 'product' => $product, 'variant' => $variant] = $this->seedAudit();

        $order = $this->order($user, $product, $variant, ['status' => 'selesai']);
        $orderItem = $order->items()->firstOrFail();

        $retur = Retur::create(['order_id' => $order->id, 'user_id' => $user->id, 'alasan' => 'cacat', 'status' => 'disetujui']);
        $retur->items()->create(['order_item_id' => $orderItem->id, 'qty' => 2]);

        $this->actingAs($admin)->put('/admin/retur/'.$retur->id, ['status' => 'selesai'])->assertRedirect();
        $this->assertEquals(7, $variant->fresh()->stok);

        $this->actingAs($admin)->put('/admin/retur/'.$retur->id, ['status' => 'selesai'])->assertRedirect();
        $this->assertEquals(7, $variant->fresh()->stok);

        $this->actingAs($admin)->put('/admin/retur/'.$retur->id, ['status' => 'disetujui'])
            ->assertSessionHasErrors('status');
        $this->assertEquals('selesai', $retur->fresh()->status);

        $retur2 = Retur::create(['order_id' => $order->id, 'user_id' => $user->id, 'alasan' => 'cacat', 'status' => 'diajukan']);
        $retur2->items()->create(['order_item_id' => $orderItem->id, 'qty' => 1]);
        $this->actingAs($admin)->put('/admin/retur/'.$retur2->id, ['status' => 'selesai'])
            ->assertSessionHasErrors('status');
        $this->assertEquals('diajukan', $retur2->fresh()->status);
        $this->assertEquals(7, $variant->fresh()->stok);
    }

    public function test_keranjang_produk_terhapus_dibersihkan_tanpa_error(): void
    {
        ['user' => $user, 'product' => $product, 'variant' => $variant] = $this->seedAudit();

        Cart::create(['user_id' => $user->id, 'product_id' => $product->id, 'variant_id' => $variant->id, 'qty' => 1]);
        $product->delete();

        $this->actingAs($user)->get('/keranjang')->assertOk();
        $this->assertDatabaseCount('carts', 0);

        $this->actingAs($user)->get('/checkout')->assertRedirect('/');
        $this->assertDatabaseCount('carts', 0);
    }

    public function test_tambah_keranjang_menolak_produk_nonaktif(): void
    {
        ['user' => $user, 'product' => $product, 'variant' => $variant] = $this->seedAudit();
        $product->update(['is_active' => false]);

        $this->actingAs($user)->post('/keranjang/'.$product->id, [
            'variant_id' => $variant->id, 'qty' => 1,
        ])->assertStatus(404);
        $this->assertDatabaseCount('carts', 0);
    }

    public function test_produk_nonaktif_tidak_bisa_dibuka_publik(): void
    {
        ['admin' => $admin, 'product' => $product] = $this->seedAudit();
        $product->update(['is_active' => false]);

        $this->get('/produk/'.$product->id)->assertStatus(404);
        $this->actingAs($admin)->get('/produk/'.$product->id)->assertOk();
    }

    public function test_akun_update_menormalisasi_dan_menolak_hp_duplikat(): void
    {
        ['user' => $user] = $this->seedAudit();
        $lain = User::create(['name' => 'Lain', 'email' => 'lain@audit.id', 'password' => 'x', 'role' => 'enduser', 'hp' => '6281234567891']);

        $this->actingAs($user)->put('/akun', ['name' => 'User', 'hp' => '0812-3456-7890'])
            ->assertRedirect();
        $this->assertEquals('6281234567890', $user->fresh()->hp);

        $this->actingAs($user)->put('/akun', ['name' => 'User', 'hp' => '0812-3456-7891'])
            ->assertSessionHasErrors('hp');
        $this->assertEquals('6281234567890', $user->fresh()->hp);
        $this->assertEquals('6281234567891', $lain->fresh()->hp);
    }

    public function test_filter_bantuan_tidak_bocor_antar_topik(): void
    {
        Faq::create(['topik' => 'Order', 'pertanyaan' => 'Refund pesanan bagaimana?', 'jawaban' => 'Ajukan retur.', 'urutan' => 1, 'is_active' => true]);
        Faq::create(['topik' => 'Bayar', 'pertanyaan' => 'Metode bayar?', 'jawaban' => 'Refund otomatis.', 'urutan' => 2, 'is_active' => true]);

        $this->get('/bantuan?q=Refund&topik=Order')
            ->assertOk()
            ->assertSee('Refund pesanan bagaimana?')
            ->assertDontSee('Refund otomatis.');
    }

    public function test_filter_aktivitas_dengan_kategori_tidak_bocor(): void
    {
        ['admin' => $admin] = $this->seedAudit();
        ActivityLog::create(['aksi' => 'login_uji', 'kategori' => 'auth', 'level' => 'info', 'subjek' => 'SUBJEK-AUTH', 'keterangan' => 'kata rahasia']);
        ActivityLog::create(['aksi' => 'order_uji', 'kategori' => 'order', 'level' => 'info', 'subjek' => 'SUBJEK-ORDER', 'keterangan' => 'kata rahasia']);

        $this->actingAs($admin)->get('/admin/aktivitas?q=rahasia&kategori=auth')
            ->assertOk()
            ->assertSee('SUBJEK-AUTH')
            ->assertDontSee('SUBJEK-ORDER');
    }

    public function test_rekonsiliasi_tidak_menolkan_stok_produk_tanpa_varian(): void
    {
        ['admin' => $admin] = $this->seedAudit();
        $tanpaVarian = Product::create([
            'nama' => 'Produk Tanpa Varian', 'berat_gram' => 100, 'stok' => 25,
            'harga_enduser' => 10000, 'harga_reseller' => 9000, 'min_reseller' => 2,
        ]);

        $this->actingAs($admin)->post('/admin/rekonsiliasi')->assertRedirect();

        $this->assertEquals(25, $tanpaVarian->fresh()->stok);
        $this->assertDatabaseMissing('stock_mutations', [
            'product_id' => $tanpaVarian->id, 'keterangan' => 'Rekonsiliasi otomatis',
        ]);
    }

    public function test_checkout_dengan_varian_hilang_tidak_500(): void
    {
        ['user' => $user, 'product' => $product, 'variant' => $variant] = $this->seedAudit();

        Cart::create(['user_id' => $user->id, 'product_id' => $product->id, 'variant_id' => $variant->id, 'qty' => 1]);
        $variant->delete();

        $this->actingAs($user)->post('/checkout', [
            'nama_penerima' => 'U', 'hp_penerima' => '0812', 'alamat' => 'Jl',
            'kurir' => 'JNE', 'layanan' => 'REG',
        ])->assertSessionHasErrors('cart');

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('carts', 0);
    }
}
