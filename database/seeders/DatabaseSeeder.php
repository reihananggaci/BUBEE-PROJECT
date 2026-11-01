<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@bubee.id'], [
            'name' => 'Admin Bubee', 'password' => Hash::make('password'),
            'role' => 'admin', 'status_reseller' => 'none', 'hp' => '081234567890',
        ]);
        User::updateOrCreate(['email' => 'reseller@bubee.id'], [
            'name' => 'Reseller Demo', 'password' => Hash::make('password'),
            'role' => 'reseller', 'status_reseller' => 'approved', 'hp' => '081234567891',
            'alamat' => 'Jl. Reseller No. 1',
        ]);
        User::updateOrCreate(['email' => 'user@bubee.id'], [
            'name' => 'User Demo', 'password' => Hash::make('password'),
            'role' => 'enduser', 'status_reseller' => 'none', 'hp' => '081234567892',
        ]);

        // Arsipkan katalog lama (riwayat order tetap utuh, tidak dihapus fisik).
        Product::where('is_active', true)
            ->whereNotLike('nama', 'HQ-%')
            ->update(['is_active' => false]);

        // Katalog Drive (Chicutom & co) — foto: storage/app/public/produk/HQ-xxx.jpg
        $items = [
            ['kode' => 'HQ-001', 'nama' => 'HQ-001 Botol Chicutom Sedotan', 'deskripsi' => 'Botol minum sedotan, 4 pilihan warna.', 'berat_gram' => 500, 'harga_enduser' => 129000, 'harga_reseller' => 99000, 'varian' => ['Pink', 'Kuning', 'Toska', 'Hitam']],
            ['kode' => 'HQ-002', 'nama' => 'HQ-002 Mug Anak Karakter', 'deskripsi' => 'Mug anak motif Oh!e, Super Girl, Flower, Nice.', 'berat_gram' => 400, 'harga_enduser' => 99000, 'harga_reseller' => 79000, 'varian' => ['Merah Apel', 'Pink Super Girl', 'Kuning Flower', 'Ungu Nice']],
            ['kode' => 'HQ-004', 'nama' => 'HQ-004 Tumbler OZBOBO Gantungan', 'deskripsi' => 'Tumbler tali gantungan, 3 warna.', 'berat_gram' => 500, 'harga_enduser' => 149000, 'harga_reseller' => 119000, 'varian' => ['Hitam', 'Silver', 'Biru Muda']],
            ['kode' => 'HQ-006', 'nama' => 'HQ-006 Botol Sport ChiaTom Handle', 'deskripsi' => 'Botol sport gagang pegangan.', 'berat_gram' => 500, 'harga_enduser' => 139000, 'harga_reseller' => 109000, 'varian' => ['Pink', 'Hitam', 'Bronze', 'Putih']],
            ['kode' => 'HQ-012', 'nama' => 'HQ-012 Tumbler Cheer You Buah', 'deskripsi' => 'Tumbler motif buah Mango, Yummy, Peach, Apple.', 'berat_gram' => 500, 'harga_enduser' => 119000, 'harga_reseller' => 89000, 'varian' => ['Mangga', 'Yummy', 'Peach', 'Apel']],
            ['kode' => 'HQ-014', 'nama' => 'HQ-014 Botol Mini Chicutom Tutup Cangkir', 'deskripsi' => 'Botol mini tutup model cangkir.', 'berat_gram' => 300, 'harga_enduser' => 89000, 'harga_reseller' => 69000, 'varian' => ['Biru', 'Pink', 'Kuning', 'Biru Tua']],
            ['kode' => 'HQ-021', 'nama' => 'HQ-021 Tumbler Es Fantasi 1000mL', 'deskripsi' => 'Kapasitas 1000mL. Lapisan keramik, baja 316, PP, silikon.', 'berat_gram' => 800, 'harga_enduser' => 159000, 'harga_reseller' => 129000, 'varian' => ['Skateboard', 'Kelinci', 'Pisang', 'Lucky']],
            ['kode' => 'HQ-031', 'nama' => 'HQ-031 Mug Sweet Karakter', 'deskripsi' => 'Mug motif Kelinci, Kucing, Kapibara, Anjing.', 'berat_gram' => 400, 'harga_enduser' => 99000, 'harga_reseller' => 79000, 'varian' => ['Kelinci', 'Kucing', 'Kapibara', 'Anjing']],
            ['kode' => 'HQ-034', 'nama' => 'HQ-034 Tumbler Isolasi Sedotan Gradasi 780mL', 'deskripsi' => 'Kapasitas 780mL. Stainless 304/316 + lapisan keramik.', 'berat_gram' => 600, 'harga_enduser' => 149000, 'harga_reseller' => 119000, 'varian' => ['Kuning Jeruk', 'Sakura', 'Hitam Abu', 'Biru Muda']],
            ['kode' => 'HQ-035', 'nama' => 'HQ-035 Botol Mini Chicu Tom', 'deskripsi' => 'Botol mini 4 warna.', 'berat_gram' => 300, 'harga_enduser' => 89000, 'harga_reseller' => 69000, 'varian' => ['Hijau', 'Putih', 'Kuning', 'Ungu']],
            ['kode' => 'HQ-039', 'nama' => 'HQ-039 Mug Chicu Tom Polos', 'deskripsi' => 'Mug polos 4 warna.', 'berat_gram' => 400, 'harga_enduser' => 99000, 'harga_reseller' => 79000, 'varian' => ['Kuning', 'Abu', 'Hijau', 'Ungu']],
            ['kode' => 'HQ-039B', 'nama' => 'HQ-039B Mug Sayur Chicu Tom', 'deskripsi' => 'Mug motif sayur Vegetable, Tomato, Strawberry.', 'berat_gram' => 400, 'harga_enduser' => 99000, 'harga_reseller' => 79000, 'varian' => ['Vegetable', 'Tomato', 'Strawberry']],
            ['kode' => 'HQ-051', 'nama' => 'HQ-051 Botol Happy Kids Manik', 'deskripsi' => 'Botol anak tali manik Happy Kids.', 'berat_gram' => 400, 'harga_enduser' => 109000, 'harga_reseller' => 85000, 'varian' => ['Puppy', 'Kucing', 'Merah', 'Pink']],
            ['kode' => 'HQ-056', 'nama' => 'HQ-056 Botol Boneka Salju Chicu Tom', 'deskripsi' => 'Botol boneka salju 4 warna.', 'berat_gram' => 400, 'harga_enduser' => 109000, 'harga_reseller' => 85000, 'varian' => ['Kuning', 'Pink', 'Putih', 'Hitam']],
            ['kode' => 'HQ-057', 'nama' => 'HQ-057 Tumbler Chicu Tom Gagang', 'deskripsi' => 'Tumbler gagang untuk belajar & bekerja.', 'berat_gram' => 600, 'harga_enduser' => 139000, 'harga_reseller' => 109000, 'varian' => ['Steel', 'Putih']],
            ['kode' => 'HQ-085', 'nama' => 'HQ-085 Mug Puff Hijau 500mL Keramik', 'deskripsi' => 'Kapasitas 500mL. Lapisan dalam keramik.', 'berat_gram' => 500, 'harga_enduser' => 119000, 'harga_reseller' => 95000, 'varian' => ['Hijau Puff']],
            ['kode' => 'HQ-091', 'nama' => 'HQ-091 Botol Apel Happy Puppy', 'deskripsi' => 'Botol tutup apel Happy Puppy.', 'berat_gram' => 400, 'harga_enduser' => 109000, 'harga_reseller' => 85000, 'varian' => ['Sweet', 'Puppy', 'Beruang', 'Silver']],
            ['kode' => 'HQ-093', 'nama' => 'HQ-093 Tumbler Kantor Chicutom', 'deskripsi' => 'Tumbler steel untuk belajar & bekerja.', 'berat_gram' => 600, 'harga_enduser' => 149000, 'harga_reseller' => 119000, 'varian' => ['Steel 09']],
            ['kode' => 'HQ-096', 'nama' => 'HQ-096 Botol Mini Tali Kepang', 'deskripsi' => 'Botol mini tali kepang.', 'berat_gram' => 300, 'harga_enduser' => 89000, 'harga_reseller' => 69000, 'varian' => ['Pink', 'Krem', 'Merah', 'Kuning']],
            ['kode' => 'HQ-097', 'nama' => 'HQ-097 Botol Minum IP Hewan 480mL', 'deskripsi' => 'Kapasitas 480mL. Bagian dalam 304 + dasar 316, PP.', 'berat_gram' => 400, 'harga_enduser' => 119000, 'harga_reseller' => 95000, 'varian' => ['Macan', 'Sapi', 'Kucing', 'Panda']],
            ['kode' => 'HQ-098', 'nama' => 'HQ-098 Tumbler Hewan Gantungan', 'deskripsi' => 'Tumbler gantungan motif Panda, Kelinci, Kucing, Anjing.', 'berat_gram' => 500, 'harga_enduser' => 109000, 'harga_reseller' => 85000, 'varian' => ['Panda', 'Kelinci', 'Kucing', 'Anjing']],
        ];
        foreach ($items as $it) {
            $foto = 'produk/'.$it['kode'].'.jpg';
            $p = Product::updateOrCreate(['nama' => $it['nama']], [
                'deskripsi' => $it['deskripsi'], 'kategori' => 'Tumbler', 'foto' => $foto,
                'berat_gram' => $it['berat_gram'], 'harga_enduser' => $it['harga_enduser'],
                'harga_reseller' => $it['harga_reseller'], 'min_reseller' => 6, 'is_active' => true,
            ]);
            foreach ($it['varian'] as $i => $warna) {
                $p->variants()->updateOrCreate(
                    ['warna' => $warna, 'ukuran' => 'All Size'],
                    ['stok' => 20, 'sku' => $it['kode'].'-'.($i + 1)]
                );
            }
            $p->syncStok();
        }
    }
}
