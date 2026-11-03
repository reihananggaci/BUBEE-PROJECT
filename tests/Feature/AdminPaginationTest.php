<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Penerimaan;
use App\Models\Product;
use App\Models\Retur;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPaginationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_produk_view_pemisahan_dan_panah_paginasi(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'adm@bubee.id', 'password' => 'password123', 'role' => 'admin', 'email_verified_at' => now()]);

        // Buat 25 produk
        for ($i = 1; $i <= 25; $i++) {
            Product::create([
                'nama' => 'Produk Tes ' . $i,
                'kategori' => 'Tumbler',
                'stok' => $i * 5,
                'harga_enduser' => 100000,
                'harga_reseller' => 80000,
                'berat_gram' => 300,
                'is_active' => true,
            ]);
        }

        // 1. Default view = tabel: hanya tabel yang muncul, ada tombol tab, dan ada paginasi panah
        $resTabel = $this->actingAs($admin)->get('/admin/produk?view=tabel');
        $resTabel->assertOk()
            ->assertSee('Tabel Ringkasan')
            ->assertSee('Kartu Kelola', false)
            ->assertSee('Berikutnya')
            ->assertSee('Sebelumnya');

        // Halaman 2 tabel
        $resTabel2 = $this->actingAs($admin)->get('/admin/produk?view=tabel&page=2');
        $resTabel2->assertOk()
            ->assertSee('Sebelumnya')
            ->assertSee('Berikutnya');

        // 2. View kartu: menampilkan kartu edit
        $resKartu = $this->actingAs($admin)->get('/admin/produk?view=kartu');
        $resKartu->assertOk()
            ->assertSee('Kartu Kelola', false)
            ->assertSee('VARIAN')
            ->assertSee('TIER HARGA')
            ->assertSee('Berikutnya')
            ->assertSee('Sebelumnya');
    }

    public function test_admin_dapat_on_off_produk_langsung_dari_status(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'adm_toggle@bubee.id', 'password' => 'password123', 'role' => 'admin', 'email_verified_at' => now()]);
        $user = User::create(['name' => 'User', 'email' => 'user_toggle@bubee.id', 'password' => 'password123', 'role' => 'enduser', 'email_verified_at' => now()]);

        $prod = Product::create([
            'nama' => 'Tumbler Tes Toggle',
            'kategori' => 'Tumbler',
            'stok' => 50,
            'harga_enduser' => 100000,
            'harga_reseller' => 80000,
            'berat_gram' => 300,
            'is_active' => true,
        ]);

        // Pastikan tampilan tabel memiliki tombol toggle ON
        $this->actingAs($admin)->get('/admin/produk?view=tabel')
            ->assertOk()
            ->assertSee('Tumbler Tes Toggle')
            ->assertSee('/admin/produk/' . $prod->id . '/toggle')
            ->assertSee('ON');

        // Toggle dari ON -> OFF
        $this->actingAs($admin)->post('/admin/produk/' . $prod->id . '/toggle')
            ->assertRedirect()
            ->assertSessionHas('ok');

        $this->assertFalse($prod->fresh()->is_active);

        // Setelah OFF, halaman menampilkan OFF
        $this->actingAs($admin)->get('/admin/produk?view=tabel')
            ->assertOk()
            ->assertSee('OFF');

        // Toggle dari OFF -> ON
        $this->actingAs($admin)->post('/admin/produk/' . $prod->id . '/toggle')
            ->assertRedirect()
            ->assertSessionHas('ok');

        $this->assertTrue($prod->fresh()->is_active);

        // User biasa tidak bisa toggle
        $this->actingAs($user)->post('/admin/produk/' . $prod->id . '/toggle')
            ->assertRedirect('/');
    }

    public function test_admin_order_reseller_retur_penerimaan_paginasi_panah(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'adm2@bubee.id', 'password' => 'password123', 'role' => 'admin', 'email_verified_at' => now()]);
        $user = User::create(['name' => 'User', 'email' => 'u2@bubee.id', 'password' => 'password123', 'role' => 'enduser', 'email_verified_at' => now()]);
        $prod = Product::create(['nama' => 'Tumbler A', 'stok' => 100, 'harga_enduser' => 100000, 'harga_reseller' => 80000, 'berat_gram' => 300]);

        // Buat 20 order
        for ($i = 1; $i <= 20; $i++) {
            $order = Order::create([
                'user_id' => $user->id,
                'kode' => 'BB-ORD-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'total_produk' => 100000,
                'ongkir' => 10000,
                'total_bayar' => 110000,
                'kurir' => 'JNE',
                'layanan' => 'REG',
                'status' => 'diproses',
                'bukti_bayar' => 'bukti.jpg',
                'alamat' => 'Jl. Bubee',
            ]);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $prod->id,
                'qty' => 1,
                'harga_satuan' => 100000,
                'subtotal' => 100000,
                'tipe_harga' => 'enduser',
            ]);
        }

        // Test Order Pagination
        $resOrder = $this->actingAs($admin)->get('/admin/order');
        $resOrder->assertOk()
            ->assertSee('Kelola Order')
            ->assertSee('Berikutnya')
            ->assertSee('Sebelumnya');

        // Test Verifikasi Pagination
        $resVerif = $this->actingAs($admin)->get('/admin/verifikasi');
        $resVerif->assertOk()
            ->assertSee('Verifikasi Pembayaran')
            ->assertSee('Berikutnya')
            ->assertSee('Sebelumnya');

        // Buat 20 reseller
        for ($i = 1; $i <= 20; $i++) {
            User::create([
                'name' => 'Reseller ' . $i,
                'email' => 'rsl' . $i . '@bubee.id',
                'password' => 'password123',
                'role' => 'reseller',
                'status_reseller' => 'pending',
                'hp' => '081299900' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'alamat' => 'Alamat Reseller',
                'email_verified_at' => now(),
            ]);
        }

        $resReseller = $this->actingAs($admin)->get('/admin/reseller');
        $resReseller->assertOk()
            ->assertSee('Approval Reseller')
            ->assertSee('Berikutnya')
            ->assertSee('Sebelumnya');

        // Buat 20 Penerimaan
        for ($i = 1; $i <= 20; $i++) {
            Penerimaan::create([
                'no_surat_jalan' => 'SJ-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'supplier' => 'Supplier X',
                'user_id' => $admin->id,
                'status' => 'dicatat',
            ]);
        }

        $resPenerimaan = $this->actingAs($admin)->get('/admin/penerimaan');
        $resPenerimaan->assertOk()
            ->assertSee('Barang Masuk')
            ->assertSee('Berikutnya')
            ->assertSee('Sebelumnya');
    }
}
