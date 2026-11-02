<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLaporanTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): User
    {
        return User::create([
            'name' => 'Admin Bubee',
            'email' => 'admin.laporan@bubee.test',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);
    }

    public function test_laporan_dropdown_periode_harian_mingguan_bulanan(): void
    {
        $admin = $this->createAdmin();
        $user = User::create([
            'name' => 'Budi Pembeli',
            'email' => 'budi@test.com',
            'password' => bcrypt('password'),
            'role' => 'enduser',
            'email_verified_at' => now(),
        ]);
        $prod = Product::create([
            'nama' => 'Tumbler Tyeso Test',
            'berat_gram' => 500,
            'stok' => 50,
            'harga_enduser' => 150000,
            'harga_reseller' => 120000,
            'min_reseller' => 2,
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'kode' => 'BB-TEST01',
            'total_produk' => 150000,
            'ongkir' => 10000,
            'diskon' => 0,
            'total_bayar' => 160000,
            'kurir' => 'JNE',
            'layanan' => 'REG',
            'status' => 'selesai',
            'alamat' => 'Jl Test No 1',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $prod->id,
            'qty' => 1,
            'harga_satuan' => 150000,
            'subtotal' => 150000,
            'tipe_harga' => 'enduser',
        ]);

        // 1. Cek default bulanan (Mobile & Web)
        $resDefault = $this->actingAs($admin)->get('/admin/laporan');
        $resDefault->assertOk()
            ->assertSee('Laporan Harian')
            ->assertSee('Laporan Mingguan')
            ->assertSee('Laporan Bulanan')
            ->assertSee('160,000');

        // 2. Cek filter Harian
        $resHarian = $this->actingAs($admin)->get('/admin/laporan?periode=harian');
        $resHarian->assertOk()
            ->assertSee('BB-TEST01')
            ->assertSee('160,000')
            ->assertSee('Per Status')
            ->assertSee('Per Kurir');

        // 3. Cek filter Mingguan
        $resMingguan = $this->actingAs($admin)->get('/admin/laporan?periode=mingguan');
        $resMingguan->assertOk()
            ->assertSee('Laporan Mingguan')
            ->assertSee('160,000')
            ->assertSee('Per Status');

        // 4. Cek filter Bulanan
        $resBulanan = $this->actingAs($admin)->get('/admin/laporan?periode=bulanan');
        $resBulanan->assertOk()
            ->assertSee('Laporan Bulanan')
            ->assertSee('160,000')
            ->assertSee('Top Pembeli');

        // 5. Cek Export CSV periode
        $this->actingAs($admin)->get('/admin/laporan/export?periode=harian')->assertOk()->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->actingAs($admin)->get('/admin/laporan/export?periode=mingguan')->assertOk()->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->actingAs($admin)->get('/admin/laporan/export?periode=bulanan')->assertOk()->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }
}

