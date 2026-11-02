<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ProductGallerySeeder extends Seeder
{
    public function run(): void
    {
        $items = json_decode(file_get_contents(__DIR__ . '/tyeso_gallery_data.json'), true);

        $dir = storage_path('app/public/produk/gallery');
        if (! File::exists($dir)) {
            File::makeDirectory($dir, 0755, true);
        }

        foreach ($items as $item) {
            $targetPath = storage_path('app/public/' . $item['foto']);
            // Jika file gambar belum ada atau ukuran 0, download dari drive
            if ((! File::exists($targetPath) || File::size($targetPath) < 2000) && ! empty($item['drive_id'])) {
                $url = 'https://drive.google.com/uc?export=download&id=' . $item['drive_id'];
                try {
                    @file_put_contents($targetPath, @file_get_contents($url));
                } catch (\Throwable $e) {
                    // ignore
                }
            }

            $product = Product::where('foto', 'produk/' . $item['sku'] . '.jpg')
                ->orWhere('nama', 'like', '%' . str_replace('-', ' ', $item['sku']) . '%')
                ->orWhere('id', $item['product_id'])
                ->first();

            if (! $product) {
                continue;
            }

            if ($item['urutan'] === 1 && $product->foto !== $item['foto']) {
                $product->foto = $item['foto'];
                $product->save();
            }

            ProductImage::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'foto' => $item['foto'],
                ],
                [
                    'judul' => $item['judul'],
                    'urutan' => $item['urutan'],
                ]
            );
        }
    }
}
