<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerbaikanBubeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_varian_stok_otomatis_sinkron_ke_produk_induk(): void
    {
        $product = Product::create([
            'nama' => 'Baju Anak', 'berat_gram' => 200, 'stok' => 0,
            'harga_enduser' => 50000, 'harga_reseller' => 40000, 'min_reseller' => 2,
        ]);

        $v1 = $product->variants()->create(['ukuran' => 'S', 'warna' => 'Merah', 'stok' => 5, 'sku' => 'BA-S']);
        $product->refresh();
        $this->assertEquals(5, $product->stok);

        $v2 = $product->variants()->create(['ukuran' => 'M', 'warna' => 'Merah', 'stok' => 10, 'sku' => 'BA-M']);
        $product->refresh();
        $this->assertEquals(15, $product->stok);

        $v1->update(['stok' => 7]);
        $product->refresh();
        $this->assertEquals(17, $product->stok);

        $v2->delete();
        $product->refresh();
        $this->assertEquals(7, $product->stok);
    }

    public function test_batal_order_me_rollback_voucher(): void
    {
        $user = User::create(['name' => 'Budi', 'email' => 'budi@test.com', 'password' => 'secret123', 'role' => 'enduser', 'hp' => '081234567890']);
        $product = Product::create([
            'nama' => 'Sepatu', 'berat_gram' => 500, 'stok' => 10,
            'harga_enduser' => 100000, 'harga_reseller' => 80000,
        ]);
        $variant = $product->variants()->create(['ukuran' => '40', 'warna' => 'Hitam', 'stok' => 10, 'sku' => 'SP-40']);
        $voucher = Voucher::create([
            'kode' => 'DISKON10', 'tipe' => 'nominal', 'nilai' => 10000,
            'kuota' => 5, 'terpakai' => 1, 'is_active' => true,
        ]);

        $order = Order::create([
            'user_id' => $user->id, 'kode' => 'BB-TEST01',
            'total_produk' => 100000, 'ongkir' => 10000, 'diskon' => 10000, 'total_bayar' => 100000,
            'kurir' => 'JNE', 'layanan' => 'REG', 'status' => 'menunggu_bayar',
            'voucher_id' => $voucher->id, 'alamat' => 'Jl Mawar',
        ]);
        OrderItem::create([
            'order_id' => $order->id, 'product_id' => $product->id, 'variant_id' => $variant->id,
            'qty' => 1, 'harga_satuan' => 100000, 'subtotal' => 100000, 'tipe_harga' => 'enduser',
        ]);

        $this->actingAs($user)->post('/pesanan/'.$order->id.'/batal', ['alasan' => 'Berubah pikiran'])->assertRedirect('/pesanan');

        $order->refresh();
        $voucher->refresh();
        $this->assertEquals('batal', $order->status);
        $this->assertEquals(0, $voucher->terpakai);
    }

    public function test_auto_cancel_pesanan_kedaluwarsa(): void
    {
        $user = User::create(['name' => 'Budi', 'email' => 'budi2@test.com', 'password' => 'secret123', 'role' => 'enduser', 'hp' => '081234567890']);
        $product = Product::create([
            'nama' => 'Tas', 'berat_gram' => 300, 'stok' => 5,
            'harga_enduser' => 150000, 'harga_reseller' => 120000,
        ]);
        $variant = $product->variants()->create(['ukuran' => 'All Size', 'warna' => 'Biru', 'stok' => 5, 'sku' => 'TAS-1']);
        $voucher = Voucher::create([
            'kode' => 'HEMAT20', 'tipe' => 'nominal', 'nilai' => 20000,
            'kuota' => 5, 'terpakai' => 1, 'is_active' => true,
        ]);

        $order = Order::create([
            'user_id' => $user->id, 'kode' => 'BB-EXPIRED',
            'total_produk' => 150000, 'ongkir' => 10000, 'diskon' => 20000, 'total_bayar' => 140000,
            'kurir' => 'JNE', 'layanan' => 'REG', 'status' => 'menunggu_bayar',
            'voucher_id' => $voucher->id, 'alamat' => 'Jl Melati',
        ]);
        OrderItem::create([
            'order_id' => $order->id, 'product_id' => $product->id, 'variant_id' => $variant->id,
            'qty' => 2, 'harga_satuan' => 150000, 'subtotal' => 300000, 'tipe_harga' => 'enduser',
        ]);
        // Simulasikan order dibuat 25 jam yang lalu
        $order->created_at = now()->subHours(25);
        $order->saveQuietly();

        $jumlahBatal = Order::batalkanKedaluwarsa(24);
        $this->assertEquals(1, $jumlahBatal);

        $order->refresh();
        $voucher->refresh();
        $variant->refresh();
        $this->assertEquals('batal', $order->status);
        $this->assertEquals(0, $voucher->terpakai);
        $this->assertEquals(7, $variant->stok);
    }

    public function test_reseller_ditolak_bisa_mengajukan_ulang(): void
    {
        $user = User::create([
            'name' => 'Agus', 'email' => 'agus@test.com', 'password' => 'secret123',
            'role' => 'reseller', 'status_reseller' => 'rejected',
            'hp' => '081234567891', 'alamat' => 'Bandung', 'nik' => '1234567890123456',
        ]);

        $this->actingAs($user)->post('/akun/upgrade-reseller', [
            'toko_nama' => 'Toko Baru Agus',
            'nik' => '1234567890123456',
            'alamat' => 'Jl Sukajadi No 1',
        ])->assertSessionHas('ok');

        $user->refresh();
        $this->assertEquals('reseller', $user->role);
        $this->assertEquals('pending', $user->status_reseller);
        $this->assertEquals('Toko Baru Agus', $user->toko_nama);
    }

    public function test_halaman_bayar_menampilkan_info_rekening_bank(): void
    {
        Setting::set('bank_nama', 'BCA');
        Setting::set('bank_rekening', '9876543210');
        Setting::set('bank_atas_nama', 'PT Bubee Sejahtera');

        $user = User::create(['name' => 'Siti', 'email' => 'siti@test.com', 'password' => 'secret123', 'role' => 'enduser', 'hp' => '081234567892']);
        $order = Order::create([
            'user_id' => $user->id, 'kode' => 'BB-BAYAR01',
            'total_produk' => 100000, 'ongkir' => 10000, 'diskon' => 0, 'total_bayar' => 110000,
            'kurir' => 'JNE', 'layanan' => 'REG', 'status' => 'menunggu_bayar', 'alamat' => 'Jl Melati',
        ]);

        $response = $this->actingAs($user)->get('/bayar/'.$order->id);
        $response->assertOk();
        $response->assertSee('9876543210');
        $response->assertSee('PT Bubee Sejahtera');
    }
}
