<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductGalleryTest extends TestCase
{
    use RefreshDatabase;
    public function test_produk_dengan_galeri_menampilkan_slider_dan_thumbnail(): void
    {
        $user = User::create([
            'name' => 'Regular User',
            'email' => 'regular@test.id',
            'password' => bcrypt('password'),
            'role' => 'enduser',
            'status_reseller' => 'none',
            'hp' => '081299990001',
        ]);
        $product = Product::create([
            'nama' => 'Test Tumbler Galeri',
            'foto' => 'produk/test_main.jpg',
            'stok' => 50,
            'berat_gram' => 500,
            'harga_enduser' => 120000,
            'harga_reseller' => 95000,
            'is_active' => true,
        ]);

        ProductImage::create([
            'product_id' => $product->id,
            'foto' => 'produk/gallery/test_extra_1.jpg',
            'judul' => 'Foto Tambahan 1',
            'urutan' => 1,
        ]);

        ProductImage::create([
            'product_id' => $product->id,
            'foto' => 'produk/gallery/test_extra_2.jpg',
            'judul' => 'Foto Tambahan 2',
            'urutan' => 2,
        ]);

        $semua = $product->semuaFoto();
        $this->assertCount(3, $semua);
        $this->assertEquals('produk/test_main.jpg', $semua[0]);
        $this->assertEquals('produk/gallery/test_extra_1.jpg', $semua[1]);
        $this->assertEquals('produk/gallery/test_extra_2.jpg', $semua[2]);

        // Test tampilan mobile
        $respMobile = $this->actingAs($user)
            ->withServerVariables(['HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X)'])
            ->get('/produk/' . $product->id);

        $respMobile->assertStatus(200);
        $respMobile->assertSee('carousel-track');
        $respMobile->assertSee('carousel-counter');
        $respMobile->assertSee('1 / 3');
        $respMobile->assertSee('test_extra_1.jpg');
        $respMobile->assertSee('test_extra_2.jpg');

        // Test tampilan desktop
        $respWeb = $this->actingAs($user)
            ->withSession(['tampilan' => 'web'])
            ->get('/produk/' . $product->id);

        $respWeb->assertStatus(200);
        $respWeb->assertSee('carousel-track-web');
        $respWeb->assertSee('carousel-counter-web');
        $respWeb->assertSee('1 / 3');
        $respWeb->assertSee('test_extra_1.jpg');
        $respWeb->assertSee('test_extra_2.jpg');
    }
}
