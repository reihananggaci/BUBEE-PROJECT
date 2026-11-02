<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiCapacityProductTest extends TestCase
{
    use RefreshDatabase;

    public function test_merged_product_has_multiple_capacities_and_price_ranges(): void
    {
        $product = Product::forceCreate([
            'id' => 61,
            'nama' => 'TYESO TS-8866 / TS-8868 Quencher Handle Mug (900mL & 1200mL)',
            'berat_gram' => 650,
            'stok' => 40,
            'harga_enduser' => 159000,
            'harga_reseller' => 125000,
            'is_active' => true,
        ]);

        $v1 = $product->variants()->create([
            'ukuran' => '900mL',
            'warna' => 'Sage',
            'stok' => 20,
            'harga_enduser' => 159000,
            'harga_reseller' => 125000,
            'berat_gram' => 650,
        ]);

        $v2 = $product->variants()->create([
            'ukuran' => '1200mL',
            'warna' => 'Charcoal',
            'stok' => 20,
            'harga_enduser' => 179000,
            'harga_reseller' => 139000,
            'berat_gram' => 850,
        ]);

        $this->assertEquals(['900mL', '1200mL'], $product->daftarUkuran());
        $this->assertEquals('Rp 159,000 - Rp 179,000', $product->hargaRentang(null));

        $reseller = User::create([
            'name' => 'Reseller Mitra',
            'email' => 'reseller@test.id',
            'password' => 'secret',
            'role' => 'reseller',
            'status_reseller' => 'approved',
        ]);
        $this->assertEquals('Rp 125,000 - Rp 139,000', $product->hargaRentang($reseller));
    }

    public function test_merged_old_url_redirects_to_primary_product(): void
    {
        $product = Product::forceCreate([
            'id' => 61,
            'nama' => 'Primary Product',
            'berat_gram' => 500,
            'stok' => 10,
            'is_active' => true,
        ]);

        // Merged product 62 (soft deleted or active 0)
        $oldProduct = Product::forceCreate([
            'id' => 62,
            'nama' => 'Old Merged Product',
            'berat_gram' => 500,
            'stok' => 0,
            'is_active' => false,
        ]);

        $resp = $this->get('/produk/62');
        $resp->assertStatus(301);
        $resp->assertRedirect('/produk/61');

        $p44 = Product::forceCreate([
            'id' => 44,
            'nama' => 'Ribbed Vacuum',
            'berat_gram' => 280,
            'stok' => 20,
            'is_active' => true,
        ]);
        $old46 = Product::forceCreate([
            'id' => 46,
            'nama' => 'Mini Ribbed Bottle',
            'berat_gram' => 280,
            'stok' => 0,
            'is_active' => false,
        ]);
        $resp46 = $this->get('/produk/46');
        $resp46->assertStatus(301);
        $resp46->assertRedirect('/produk/44');
    }

    public function test_product_detail_renders_capacity_buttons_and_switching(): void
    {
        $user = User::create([
            'name' => 'Customer',
            'email' => 'c@test.id',
            'password' => 'secret',
            'role' => 'customer',
        ]);

        $product = Product::forceCreate([
            'id' => 61,
            'nama' => 'TYESO TS-8866 / TS-8868',
            'berat_gram' => 650,
            'stok' => 40,
            'harga_enduser' => 159000,
            'harga_reseller' => 125000,
            'is_active' => true,
        ]);

        $product->variants()->create([
            'ukuran' => '900mL',
            'warna' => 'Sage',
            'stok' => 20,
            'harga_enduser' => 159000,
            'harga_reseller' => 125000,
            'berat_gram' => 650,
        ]);

        $product->variants()->create([
            'ukuran' => '1200mL',
            'warna' => 'Charcoal',
            'stok' => 20,
            'harga_enduser' => 179000,
            'harga_reseller' => 139000,
            'berat_gram' => 850,
        ]);

        $resp = $this->actingAs($user)->get('/produk/61');
        $resp->assertStatus(200);
        $resp->assertSee('Pilih Kapasitas / Ukuran');
        $resp->assertSee('900mL');
        $resp->assertSee('1200mL');
        $resp->assertSee('btn-ukuran');
    }

    public function test_home_catalog_renders_price_range(): void
    {
        $user = User::create([
            'name' => 'Customer',
            'email' => 'c2@test.id',
            'password' => 'secret',
            'role' => 'customer',
        ]);

        $product = Product::create([
            'nama' => 'Multi Size Tumbler',
            'berat_gram' => 650,
            'stok' => 40,
            'harga_enduser' => 159000,
            'harga_reseller' => 125000,
            'is_active' => true,
        ]);

        $product->variants()->create([
            'ukuran' => '900mL',
            'warna' => 'Sage',
            'stok' => 20,
            'harga_enduser' => 159000,
            'harga_reseller' => 125000,
        ]);

        $product->variants()->create([
            'ukuran' => '1200mL',
            'warna' => 'Charcoal',
            'stok' => 20,
            'harga_enduser' => 179000,
            'harga_reseller' => 139000,
        ]);

        $resp = $this->actingAs($user)->get('/');
        $resp->assertStatus(200);
        $resp->assertSee('Rp 159,000 - Rp 179,000');
    }
}
