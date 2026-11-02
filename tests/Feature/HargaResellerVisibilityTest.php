<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class HargaResellerVisibilityTest extends TestCase
{
    use RefreshDatabase;

    private function createShop()
    {
        $regularUser = User::create([
            'name' => 'Regular User',
            'email' => 'regular@test.id',
            'password' => Hash::make('password'),
            'role' => 'enduser',
            'status_reseller' => 'none',
            'hp' => '081299990001',
        ]);

        $resellerUser = User::create([
            'name' => 'Reseller User',
            'email' => 'reseller@test.id',
            'password' => Hash::make('password'),
            'role' => 'reseller',
            'status_reseller' => 'approved',
            'hp' => '081299990002',
        ]);

        $product = Product::create([
            'nama' => 'Tumbler Uji Rahasia Reseller',
            'deskripsi' => 'Deskripsi botol minum',
            'kategori' => 'Tumbler',
            'foto' => 'produk/test.jpg',
            'berat_gram' => 500,
            'harga_enduser' => 150000,
            'harga_reseller' => 110000,
            'min_reseller' => 1,
            'is_active' => true,
        ]);

        ProductVariant::create([
            'product_id' => $product->id,
            'warna' => 'Hitam',
            'ukuran' => 'All Size',
            'stok' => 50,
            'sku' => 'TEST-01',
        ]);
        $product->syncStok();

        return [$regularUser, $resellerUser, $product];
    }

    public function test_user_reguler_tidak_melihat_harga_reseller_di_home(): void
    {
        [$regularUser, $resellerUser, $product] = $this->createShop();

        // Regular user di home: melihat harga retail, TIDAK melihat harga reseller
        $response = $this->actingAs($regularUser)->get('/');
        $response->assertOk();
        $response->assertSee(number_format(150000));
        $response->assertSee('RETAIL');
        $response->assertDontSee(number_format(110000));
        $response->assertDontSee('Harga Reseller');
        $response->assertDontSee('RSLR');
    }

    public function test_user_reguler_tidak_melihat_harga_reseller_di_detail_produk(): void
    {
        [$regularUser, $resellerUser, $product] = $this->createShop();

        // Regular user di detail produk: melihat harga retail, TIDAK melihat harga reseller
        $response = $this->actingAs($regularUser)->get('/produk/'.$product->id);
        $response->assertOk();
        $response->assertSee(number_format(150000));
        $response->assertSee('RETAIL');
        $response->assertDontSee(number_format(110000));
        $response->assertDontSee('Khusus Mitra Reseller');
    }

    public function test_reseller_melihat_harga_grosir(): void
    {
        [$regularUser, $resellerUser, $product] = $this->createShop();

        // Reseller approved melihat harga reseller dan label GROSIR
        $response = $this->actingAs($resellerUser)->get('/');
        $response->assertOk();
        $response->assertSee(number_format(110000));
        $response->assertSee('GROSIR');

        $responseDetail = $this->actingAs($resellerUser)->get('/produk/'.$product->id);
        $responseDetail->assertOk();
        $responseDetail->assertSee(number_format(110000));
        $responseDetail->assertSee('GROSIR');
    }

    public function test_desktop_web_user_reguler_tidak_melihat_harga_reseller(): void
    {
        [$regularUser, $resellerUser, $product] = $this->createShop();

        // Desktop web view
        $response = $this->actingAs($regularUser)->withSession(['tampilan' => 'web'])->get('/');
        $response->assertOk();
        $response->assertSee(number_format(150000));
        $response->assertSee('RETAIL');
        $response->assertDontSee(number_format(110000));
        $response->assertDontSee('Harga Reseller');
        $response->assertDontSee('RSLR');

        $responseDetail = $this->actingAs($regularUser)->withSession(['tampilan' => 'web'])->get('/produk/'.$product->id);
        $responseDetail->assertOk();
        $responseDetail->assertSee(number_format(150000));
        $responseDetail->assertSee('RETAIL');
        $responseDetail->assertDontSee(number_format(110000));
        $responseDetail->assertDontSee('Khusus Mitra Reseller');
    }
}
