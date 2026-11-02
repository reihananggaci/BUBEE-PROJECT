<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Database\Seeder;

class TyesoProductSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'kode' => 'TYESO-TS-8827',
                'drive_id' => '1aID3UsBAJyeM4bKtSly8BpkecfELt8vK',
                'nama' => 'TYESO TS-8827 Tumbler Handle 750mL',
                'deskripsi' => 'Tumbler Tyeso TS-8827 kapasitas 750mL dengan handle kokoh, bahan stainless steel sus 304 tahan panas dan dingin hingga 12 jam.',
                'berat_gram' => 600,
                'harga_enduser' => 139000,
                'harga_reseller' => 109000,
                'foto' => 'produk/TYESO-TS-8827.jpg',
                'varian' => ['Putih (A)', 'Orange (B)', 'Biru Toska (C)', 'Hitam (D)', 'Ungu Lilac (E)', 'Hijau Sage (F)', 'Kuning (G)', 'Pink (H)'],
            ],
            [
                'kode' => 'TYESO-TS-8828',
                'drive_id' => '1xQqC5rgmys7FRjfJxUcOC_74GwgJa9sj',
                'nama' => 'TYESO TS-8828 Tumbler Handle 900mL',
                'deskripsi' => 'Tumbler Tyeso TS-8828 kapasitas 900mL dengan handle kokoh, stainless steel sus 304 insulasi ganda tahan suhu panas & dingin seharian.',
                'berat_gram' => 700,
                'harga_enduser' => 149000,
                'harga_reseller' => 119000,
                'foto' => 'produk/TYESO-TS-8828.jpg',
                'varian' => ['Putih (A)', 'Orange (B)', 'Biru Toska (C)', 'Hitam (D)', 'Ungu Lilac (E)', 'Hijau Sage (F)', 'Kuning (G)', 'Pink (H)'],
            ],
            [
                'kode' => 'TYESO-TS-8830',
                'drive_id' => '1gOtqNKnS_pqA7CKD9P0HisqYiGccNckQ',
                'nama' => 'TYESO TS-8830 Jumbo Handle Tumbler 1200mL',
                'deskripsi' => 'Tumbler Tyeso TS-8830 kapasitas ekstra besar 1200mL (1.2L) dengan handle pegangan nyaman dan tutup sedotan anti bocor.',
                'berat_gram' => 800,
                'harga_enduser' => 169000,
                'harga_reseller' => 135000,
                'foto' => 'produk/TYESO-TS-8830.jpg',
                'varian' => ['Putih (A)', 'Orange (B)', 'Biru Toska (C)', 'Hitam (D)', 'Ungu Lilac (E)', 'Hijau Sage (F)', 'Kuning (G)', 'Pink (H)'],
            ],
            [
                'kode' => 'TYESO-TS-8840',
                'drive_id' => '1TokCGitKYTrhWSuQ5fx_AlMXKqdyjSaJ',
                'nama' => 'TYESO TS-8840 Minimalist Slim Tumbler 360mL',
                'deskripsi' => 'Tumbler Tyeso TS-8840 desain slim minimalis modern, anti tumpah, pas di cup holder mobil dan kantong tas ransel.',
                'berat_gram' => 400,
                'harga_enduser' => 109000,
                'harga_reseller' => 85000,
                'foto' => 'produk/TYESO-TS-8840.jpg',
                'varian' => ['Putih (A)', 'Pink (B)', 'Toska Muda (C)', 'Navy (D)'],
            ],
            [
                'kode' => 'TYESO-01-01024',
                'drive_id' => '1GTpQW5-e3HXlLHKQJv0WZdGCJEk7FQ36',
                'nama' => 'TYESO 01-01024 Large Handle Tumbler 900mL',
                'deskripsi' => 'Tumbler Tyeso 01-01024 kapasitas 900mL dengan pegangan samping ergonomis dan tutup multifungsi sedotan/teguk langsung.',
                'berat_gram' => 700,
                'harga_enduser' => 149000,
                'harga_reseller' => 119000,
                'foto' => 'produk/TYESO-01-01024.jpg',
                'varian' => ['Putih (A)', 'Pink (B)', 'Ungu (C)', 'Mint (D)', 'Khaki (E)', 'Coral (F)', 'Biru (G)', 'Abu-abu (H)', 'Hitam (I)'],
            ],
            [
                'kode' => 'TYESO-01-01007',
                'drive_id' => '1qTEIxcoeTOE8QZy14T68lP5uHH226cwm',
                'nama' => 'TYESO 01-01007 Wonderful Life Gradasi 600mL',
                'deskripsi' => 'Tumbler Tyeso 01-01007 kapasitas 600mL warna gradasi Make Wonderful Life dengan tutup sedotan anti bocor.',
                'berat_gram' => 500,
                'harga_enduser' => 129000,
                'harga_reseller' => 99000,
                'foto' => 'produk/TYESO-01-01007.jpg',
                'varian' => ['Mint (A)', 'Pink (B)', 'Sunset Peach (C)', 'Lime Green (D)', 'Biru (E)', 'Abu-abu (F)'],
            ],
            [
                'kode' => 'TYESO-01-01030',
                'drive_id' => '1aaWaRPmRWY4V70nETA-MI87MfSoIksg4',
                'nama' => 'TYESO 01-01030 Insulated Straight Tumbler 750mL',
                'deskripsi' => 'Tumbler lurus elegan Tyeso 01-01030 750mL matte finish dengan tutup kedap udara dan bahan stainless steel food grade.',
                'berat_gram' => 550,
                'harga_enduser' => 139000,
                'harga_reseller' => 109000,
                'foto' => 'produk/TYESO-01-01030.jpg',
                'varian' => ['Putih (A)', 'Abu-abu (B)', 'Cokelat Bronze (C)', 'Emerald (D)', 'Biru Navy (E)'],
            ],
            [
                'kode' => 'TYESO-01-01008',
                'drive_id' => '1YMVB19Qk1Y9kqpPpqb82ujCoeGmIUO8f',
                'nama' => 'TYESO 01-01008 Sports Bottle Loop 600mL',
                'deskripsi' => 'Botol minum sport Tyeso 01-01008 kapasitas 600mL dengan tutup loop carabiner praktis dibawa berolahraga dan traveling.',
                'berat_gram' => 450,
                'harga_enduser' => 119000,
                'harga_reseller' => 89000,
                'foto' => 'produk/TYESO-01-01008.jpg',
                'varian' => ['Putih (A)', 'Khaki (B)', 'Biru Lilac (C)', 'Pink (D)', 'Lime Green (E)', 'Hitam (F)'],
            ],
            [
                'kode' => 'TYESO-01-01010',
                'drive_id' => '1ap9TnGlidaV8Hkc91lD5MNCibatjZ9QV',
                'nama' => 'TYESO 01-01010 Sports Bottle Loop 900mL',
                'deskripsi' => 'Botol minum sport Tyeso 01-01010 kapasitas 900mL insulasi stainless steel tahan dingin 24 jam dan panas 12 jam.',
                'berat_gram' => 600,
                'harga_enduser' => 139000,
                'harga_reseller' => 109000,
                'foto' => 'produk/TYESO-01-01010.jpg',
                'varian' => ['Putih (A)', 'Khaki (B)', 'Biru Lilac (C)', 'Pink (D)', 'Lime Green (E)', 'Hitam (F)'],
            ],
            [
                'kode' => 'TYESO-01-01012',
                'drive_id' => '129mw6UGiQ0XhbfN2ATpqj_-ryxrIyQIU',
                'nama' => 'TYESO 01-01012 Sports Bottle Loop 1500mL',
                'deskripsi' => 'Botol sport jumbo Tyeso 01-01012 kapasitas 1.5 Liter untuk kebutuhan hidrasi harian, gym, dan aktivitas outdoor berat.',
                'berat_gram' => 800,
                'harga_enduser' => 179000,
                'harga_reseller' => 145000,
                'foto' => 'produk/TYESO-01-01012.jpg',
                'varian' => ['Putih (A)', 'Khaki (B)', 'Biru Lilac (C)', 'Pink (D)', 'Lime Green (E)', 'Hitam (F)'],
            ],
            [
                'kode' => 'TYESO-01-01002',
                'drive_id' => '1AtNKCHkt6r2UzIrjkQRZvY7mbK8u_ac2',
                'nama' => 'TYESO 01-01002 Slim Cone Neck Ring 750mL',
                'deskripsi' => 'Botol minum leher kerucut Tyeso 01-01002 750mL dengan ring handle metal elegan dan insulasi ganda tahan suhu.',
                'berat_gram' => 550,
                'harga_enduser' => 135000,
                'harga_reseller' => 105000,
                'foto' => 'produk/TYESO-01-01002.jpg',
                'varian' => ['Putih (A)', 'Hijau Muda (B)', 'Pink (C)', 'Abu-abu (D)', 'Hijau (E)', 'Biru (F)', 'Hitam (G)'],
            ],
            [
                'kode' => 'TYESO-01-01014',
                'drive_id' => '1dmA91YrWU9jEO1x0xpBiRSIi9AXcwABt',
                'nama' => 'TYESO 01-01014 Jumbo Dual Tone 1500mL',
                'deskripsi' => 'Botol minum jumbo Tyeso 01-01014 1.5 Liter dengan pelindung bumper bawah anti selip dan tutup spout higienis.',
                'berat_gram' => 850,
                'harga_enduser' => 189000,
                'harga_reseller' => 149000,
                'foto' => 'produk/TYESO-01-01014.jpg',
                'varian' => ['Abu Gelap (A)', 'Putih Abu (B)', 'Pink Rose (C)', 'Ungu (D)', 'Biru (E)'],
            ],
            [
                'kode' => 'TYESO-TS-8848A',
                'drive_id' => '10mkNcoRdXTK1RWNZKyp9IGYKM8fjFGvh',
                'nama' => 'TYESO TS-8848A Diamond Tumbler 600mL',
                'deskripsi' => 'Tumbler Tyeso TS-8848A kapasitas 600mL tekstur diamond cut mewah Make Wonderful Life dengan sedotan anti tumpah.',
                'berat_gram' => 500,
                'harga_enduser' => 139000,
                'harga_reseller' => 109000,
                'foto' => 'produk/TYESO-TS-8848A.jpg',
                'varian' => ['Putih Diamond', 'Abu Diamond', 'Pink Diamond', 'Hijau Diamond', 'Hitam Diamond'],
            ],
            [
                'kode' => 'TYESO-TS-8848B',
                'drive_id' => '10T_NlP1xBtcm7NMQkoI6BVKJiqaaMfKj',
                'nama' => 'TYESO TS-8848B Diamond Gradasi Sedotan 600mL',
                'deskripsi' => 'Tumbler Tyeso TS-8848B kapasitas 600mL tekstur diamond prisma warna gradasi estetik lengkap sedotan stainless steel.',
                'berat_gram' => 500,
                'harga_enduser' => 139000,
                'harga_reseller' => 109000,
                'foto' => 'produk/TYESO-TS-8848B.jpg',
                'varian' => ['Abu Gradasi (A)', 'Hijau Gradasi (B)', 'Sunset Peach (C)', 'Putih Krem (D)', 'Hitam Gradasi (E)'],
            ],
            [
                'kode' => 'TYESO-TS-8706C',
                'drive_id' => '1pbr21v-eCYBvqxQsLcPA9115ssEYXnE4',
                'nama' => 'TYESO TS-8706C Slim Top Flip 600mL',
                'deskripsi' => 'Botol minum ramping Tyeso TS-8706C kapasitas 600mL tutup flip top push button satu tangan, anti bocor.',
                'berat_gram' => 450,
                'harga_enduser' => 119000,
                'harga_reseller' => 89000,
                'foto' => 'produk/TYESO-TS-8706C.jpg',
                'varian' => ['Putih (A)', 'Pink (B)', 'Ungu (C)', 'Khaki (D)', 'Orange (E)', 'Lime Green (F)', 'Toska (G)', 'Biru (H)'],
            ],
            [
                'kode' => 'TYESO-TS-8709B',
                'drive_id' => '1oZhMKLJnWxaHSEkT0t40-bsEq8EgkHez',
                'nama' => 'TYESO TS-8709B Textured Grip 750mL',
                'deskripsi' => 'Tumbler Tyeso TS-8709B kapasitas 750mL dengan grip silikon anti slip dan handle gantung kuat untuk mobilitas harian.',
                'berat_gram' => 550,
                'harga_enduser' => 139000,
                'harga_reseller' => 109000,
                'foto' => 'produk/TYESO-TS-8709B.jpg',
                'varian' => ['Putih (A)', 'Peach (B)', 'Navy (C)', 'Hitam (D)', 'Lilac (E)', 'Toska (F)'],
            ],
            [
                'kode' => 'TYESO-TS-8815',
                'drive_id' => '1AZyDKPaMp7WGg2d19sNh57ZPfwr-pItL',
                'nama' => 'TYESO TS-8815 Ribbed Vacuum 750mL',
                'deskripsi' => 'Botol minum bergelombang vintage Tyeso TS-8815 kapasitas 750mL dengan gagang lengkung metal kokoh.',
                'berat_gram' => 600,
                'harga_enduser' => 139000,
                'harga_reseller' => 109000,
                'foto' => 'produk/TYESO-TS-8815.jpg',
                'varian' => ['Putih (A)', 'Hitam (B)', 'Kuning (C)', 'Pink (D)', 'Hijau Army (E)', 'Ungu Lilac (F)'],
            ],
            [
                'kode' => 'TYESO-TS-8816',
                'drive_id' => '1lOqJuIh0hJ7v_Ub05nwMMd7tn6tEiQdH',
                'nama' => 'TYESO TS-8816 Ribbed Vacuum 1000mL',
                'deskripsi' => 'Botol minum bergelombang Tyeso TS-8816 kapasitas 1000mL (1L) insulasi ganda kokoh untuk travelling dan camping.',
                'berat_gram' => 700,
                'harga_enduser' => 159000,
                'harga_reseller' => 125000,
                'foto' => 'produk/TYESO-TS-8816.jpg',
                'varian' => ['Putih (A)', 'Hitam (B)', 'Kuning (C)', 'Pink (D)', 'Hijau Army (E)', 'Ungu Lilac (F)'],
            ],
            [
                'kode' => 'TYESO-TS-8832',
                'drive_id' => '1tryE2l7LJlQVtCm7sSbCp7ykRonubD4b',
                'nama' => 'TYESO TS-8832 Mini Ribbed Bottle 270mL',
                'deskripsi' => 'Botol minum mini saku Tyeso TS-8832 kapasitas 270mL praktis masuk tas kecil dan menjaga suhu minuman tetap segar.',
                'berat_gram' => 250,
                'harga_enduser' => 89000,
                'harga_reseller' => 69000,
                'foto' => 'produk/TYESO-TS-8832.jpg',
                'varian' => ['Putih (A)', 'Hitam (B)', 'Kuning (C)', 'Pink (D)', 'Hijau Army (E)', 'Ungu Lilac (F)'],
            ],
            [
                'kode' => 'TYESO-TS-8833',
                'drive_id' => '1hYlxNEE9q4TnTsNJ1YPe33YOnHjXGgZB',
                'nama' => 'TYESO TS-8833 Mini Ribbed Bottle 360mL',
                'deskripsi' => 'Botol minum compact Tyeso TS-8833 kapasitas 360mL desain bergelombang unik dan tutup stainless steel mewah.',
                'berat_gram' => 300,
                'harga_enduser' => 99000,
                'harga_reseller' => 79000,
                'foto' => 'produk/TYESO-TS-8833.jpg',
                'varian' => ['Putih (A)', 'Hitam (B)', 'Kuning (C)', 'Pink (D)', 'Hijau Army (E)', 'Ungu Lilac (F)'],
            ],
            [
                'kode' => 'TYESO-TS-8765A',
                'drive_id' => '1TtMgtnRCdx-iO0850HNZQbXR5gQfN53g',
                'nama' => 'TYESO TS-8765A Mini Coffee Jar 280mL',
                'deskripsi' => 'Jar mug kopi mini Tyeso TS-8765A kapasitas 280mL dengan tutup ulir rapat, cocok untuk espresso & americano panas.',
                'berat_gram' => 300,
                'harga_enduser' => 89000,
                'harga_reseller' => 69000,
                'foto' => 'produk/TYESO-TS-8765A.jpg',
                'varian' => ['Putih (A)', 'Pink (B)', 'Toska (C)', 'Biru (D)'],
            ],
            [
                'kode' => 'TYESO-TS-8804',
                'drive_id' => '1dgZ5CMU9CJOWmzOFq8YWXqjf3EBgTKog',
                'nama' => 'TYESO TS-8804 Fluted Handle Tumbler 500mL',
                'deskripsi' => 'Tumbler Tyeso TS-8804 kapasitas 500mL tekstur alur vertikal modern dengan handle lipat atas ergonomis.',
                'berat_gram' => 450,
                'harga_enduser' => 129000,
                'harga_reseller' => 99000,
                'foto' => 'produk/TYESO-TS-8804.jpg',
                'varian' => ['Navy (A)', 'Abu-abu (B)', 'Emerald (C)', 'Rose Gold (D)'],
            ],
            [
                'kode' => 'TYESO-TS-8818',
                'drive_id' => '1F5rASuzA53joej5diMfwft-TXviE7BNJ',
                'nama' => 'TYESO TS-8818 Jumbo Jug Loop Handle 1500mL',
                'deskripsi' => 'Jug termos olahraga Tyeso TS-8818 kapasitas 1500mL (1.5L) dengan tutup stainless steel dan handle kuat anti patah.',
                'berat_gram' => 900,
                'harga_enduser' => 189000,
                'harga_reseller' => 149000,
                'foto' => 'produk/TYESO-TS-8818.jpg',
                'varian' => ['Putih (A)', 'Hitam (B)', 'Orange (C)', 'Biru (D)'],
            ],
            [
                'kode' => 'TYESO-TS-8766B',
                'drive_id' => '1RpR_WsBj7LEl9UPbBi-t_mLoTSfg5hfo',
                'nama' => 'TYESO TS-8766B Take Away Cup Sedotan 550mL',
                'deskripsi' => 'Tumbler Tyeso TS-8766B kapasitas 550mL tutup model take away cup lengkap sedotan dan handle jinjing silikon.',
                'berat_gram' => 450,
                'harga_enduser' => 129000,
                'harga_reseller' => 99000,
                'foto' => 'produk/TYESO-TS-8766B.jpg',
                'varian' => ['Putih (A)', 'Hijau (B)', 'Abu Gelap (C)', 'Pink (D)', 'Ungu (E)'],
            ],
            [
                'kode' => 'TYESO-TS-8789B',
                'drive_id' => '1JYU-VlMYlM2_CKJl7bz3j0GvU5rEeXti',
                'nama' => 'TYESO TS-8789B Stainless Coffee Mug 400mL',
                'deskripsi' => 'Cangkir mug kopi kantor Tyeso S-8789B kapasitas 400mL dengan gagang kokoh dan tutup slider transparan anti tumpah.',
                'berat_gram' => 350,
                'harga_enduser' => 109000,
                'harga_reseller' => 85000,
                'foto' => 'produk/TYESO-TS-8789B.jpg',
                'varian' => ['Abu-abu (A)', 'Biru (B)', 'Pink (C)', 'Hijau (D)', 'Putih (E)'],
            ],
            [
                'kode' => 'TYESO-TS-8850',
                'drive_id' => '1JdIda0yDpMKB0wyccGCZnGde_h8gIQ_9',
                'nama' => 'TYESO TS-8850 Outdoor Flask Cangkir 750mL',
                'deskripsi' => 'Termos outdoor Tyeso S-8850 kapasitas 750mL dengan tutup ganda yang dapat dilepas menjadi cangkir minum.',
                'berat_gram' => 600,
                'harga_enduser' => 149000,
                'harga_reseller' => 119000,
                'foto' => 'produk/TYESO-TS-8850.jpg',
                'varian' => ['Putih/Mint (A)', 'Abu Gelap (B)', 'Ungu (C)', 'Peach (D)', 'Biru (E)'],
            ],
            [
                'kode' => 'TYESO-TS-8853',
                'drive_id' => '1DW76AHhE47p6F9awWBxp96iAHQoMV5h8',
                'nama' => 'TYESO TS-8853 Gradient Slim Ring 750mL',
                'deskripsi' => 'Botol minum gradasi metalik mewah Tyeso TS-8853 kapasitas 750mL dengan tutup ring handle stainless steel.',
                'berat_gram' => 550,
                'harga_enduser' => 145000,
                'harga_reseller' => 115000,
                'foto' => 'produk/TYESO-TS-8853.jpg',
                'varian' => ['Silver Putih (A)', 'Silver Hitam (B)', 'Silver Biru (C)', 'Silver Pink (D)', 'Silver Hijau (E)'],
            ],
            [
                'kode' => 'TYESO-TS-8831',
                'drive_id' => '1qIQwbZyCJ9n0O7G-jFHvWaYBCDUCHDey',
                'nama' => 'TYESO TS-8831 Straight Minimalist 800mL',
                'deskripsi' => 'Botol minum lurus minimalis Tyeso S-8831 kapasitas 800mL dengan tutup ulir rapat kedap udara dan bahan food grade SUS 304.',
                'berat_gram' => 550,
                'harga_enduser' => 139000,
                'harga_reseller' => 109000,
                'foto' => 'produk/TYESO-TS-8831.jpg',
                'varian' => ['Putih (A)', 'Abu-abu (B)', 'Biru (C)', 'Merah Bata (D)'],
            ],
            [
                'kode' => 'TYESO-TS-8730B',
                'drive_id' => '15MkaDpJ1JihIthFVCUl6n540e9gv4iVI',
                'nama' => 'TYESO TS-8730B Classic Vacuum Flask 500mL',
                'deskripsi' => 'Termos klasik Tyeso TS-8730B kapasitas 500mL warna pastel estetik tahan panas 12 jam dan dingin 24 jam.',
                'berat_gram' => 400,
                'harga_enduser' => 119000,
                'harga_reseller' => 89000,
                'foto' => 'produk/TYESO-TS-8730B.jpg',
                'varian' => ['Khaki (A)', 'Mint (B)', 'Pink (C)', 'Putih Krem (D)', 'Lilac (E)', 'Orange (F)'],
            ],
            [
                'kode' => 'TYESO-TS-8836',
                'drive_id' => '1OKLmmcuedUfTXAmWmXkLWmDU-PFO1vMI',
                'nama' => 'TYESO TS-8836 Coffee Mug Cork Base 530mL',
                'deskripsi' => 'Mug kopi Tyeso TS-8836 kapasitas 530mL dengan alas gabus alami (cork bottom) anti selip dan peredam benturan meja.',
                'berat_gram' => 450,
                'harga_enduser' => 129000,
                'harga_reseller' => 99000,
                'foto' => 'produk/TYESO-TS-8836.jpg',
                'varian' => ['Putih (A)', 'Pink (B)', 'Hijau (C)', 'Biru Navy (D)', 'Terracotta (E)', 'Maroon (F)', 'Lilac (G)', 'Hitam (H)'],
            ],
            [
                'kode' => 'TYESO-TS-8875',
                'drive_id' => '1HXcpzn_oKn-mcJTlyZ9OglVL2o5f2V83',
                'nama' => 'TYESO TS-8875 Coffee Kettle Termos Teko 600mL',
                'deskripsi' => 'Teko termos kopi dan teh Tyeso TS-8875 kapasitas 600mL dengan tuas penuang satu tangan (one-click lever) praktis di kantor & rumah.',
                'berat_gram' => 600,
                'harga_enduser' => 159000,
                'harga_reseller' => 125000,
                'foto' => 'produk/TYESO-TS-8875.jpg',
                'varian' => ['Merah (A)', 'Abu-abu (B)', 'Biru Toska (C)', 'Hitam (D)', 'Putih Krem (E)'],
            ],
            [
                'kode' => 'TYESO-TS-8843',
                'drive_id' => '1YxoDY_fWKB_x2_lymMCO-R7pGO6ZFK1C',
                'nama' => 'TYESO TS-8843 Sport Curved Neck 1000mL',
                'deskripsi' => 'Botol minum sport Tyeso TS-8843 kapasitas 1000mL (1 Liter) dengan leher botol melengkung dan gagang ergonomis.',
                'berat_gram' => 650,
                'harga_enduser' => 149000,
                'harga_reseller' => 119000,
                'foto' => 'produk/TYESO-TS-8843.jpg',
                'varian' => ['Hitam (A)', 'Biru (B)', 'Putih (C)', 'Mint (D)', 'Pink (E)'],
            ],
            [
                'kode' => 'TYESO-TS-8817B',
                'drive_id' => '10p8Okwr8qAMNjL3Wu7XoSt7QTuQYyxPX',
                'nama' => 'TYESO TS-8817B Prism Diamond Sport 600mL',
                'deskripsi' => 'Botol minum bentuk segi prisma Tyeso TS-8817B kapasitas 600mL dengan tutup flip lock push-button dan insulasi vakum.',
                'berat_gram' => 450,
                'harga_enduser' => 129000,
                'harga_reseller' => 99000,
                'foto' => 'produk/TYESO-TS-8817B.jpg',
                'varian' => ['Putih (A)', 'Abu/Orange (B)', 'Hijau (C)', 'Biru (D)', 'Pink (E)'],
            ],
            [
                'kode' => 'TYESO-TS-8866',
                'drive_id' => '1ulwbydhYbtieeJEDolf3peE-Ztcyb2Qu',
                'nama' => 'TYESO TS-8866 Quencher Handle Mug 900mL',
                'deskripsi' => 'Tumbler Quencher viral Tyeso TS-8866 kapasitas 900mL gagang besar kokoh, tutup multifungsi sedotan & teguk langsung.',
                'berat_gram' => 750,
                'harga_enduser' => 159000,
                'harga_reseller' => 125000,
                'foto' => 'produk/TYESO-TS-8866.jpg',
                'varian' => ['Biru Pastel (A)', 'Biru Royal (B)', 'Sage (C)', 'Butter (D)', 'Hijau Mint (E)', 'Merah (F)', 'Biru Laut (G)', 'Kuning Mustard (H)', 'Pink (I)'],
            ],
            [
                'kode' => 'TYESO-TS-8868',
                'drive_id' => '1urSVV6OF5Qr6hi02UOTPPnKDMSP83-NR',
                'nama' => 'TYESO TS-8868 Quencher Jumbo Handle Mug 1200mL',
                'deskripsi' => 'Tumbler Quencher viral Tyeso TS-8868 kapasitas jumbo 1200mL (1.2L) dengan sedotan reusable dan insulasi es tahan hingga 36 jam.',
                'berat_gram' => 850,
                'harga_enduser' => 179000,
                'harga_reseller' => 139000,
                'foto' => 'produk/TYESO-TS-8868.jpg',
                'varian' => ['Dark Grey (J)', 'Metal Grey (K)', 'Dusty Pink (L)', 'Orange (M)', 'Charcoal (N)', 'Lilac (O)', 'White (P)'],
            ],
            [
                'kode' => 'TYESO-TS-8878',
                'drive_id' => '1_7TGpB_wzrN3W9Ze0gPZKZoupibIvX8y',
                'nama' => 'TYESO TS-8878 Curved Coffee Mug Handle 400mL',
                'deskripsi' => 'Cangkir kopi termos melengkung Tyeso TS-8878 kapasitas 400mL dengan gagang nyaman & tutup slider kedap.',
                'berat_gram' => 350,
                'harga_enduser' => 109000,
                'harga_reseller' => 85000,
                'foto' => 'produk/TYESO-TS-8878.jpg',
                'varian' => ['Hitam (A)', 'Krem (B)', 'Merah (C)', 'Toska (D)', 'Charcoal (E)'],
            ],
            [
                'kode' => 'TYESO-TS-8871',
                'drive_id' => '1_JLjrvwKXmAuCyFyOI-jnhibI8Ux3QW_',
                'nama' => 'TYESO TS-8871 SUS 316 Medical Grade 450mL',
                'deskripsi' => 'Botol minum premium Tyeso TS-8871 kapasitas 450mL menggunakan baja medis SUS 316 tahan korosi tinggi, aman untuk susu, kopi, dan jus asam.',
                'berat_gram' => 400,
                'harga_enduser' => 139000,
                'harga_reseller' => 109000,
                'foto' => 'produk/TYESO-TS-8871.jpg',
                'varian' => ['Putih (A)', 'Pink (B)', 'Cyan (C)', 'Navy (D)', 'Hijau (E)', 'Merah (F)'],
            ],
        ];

        foreach ($items as $it) {
            $targetPath = storage_path('app/public/'.$it['foto']);
            if (!file_exists($targetPath) && !empty($it['drive_id'])) {
                @mkdir(dirname($targetPath), 0777, true);
                $url = 'https://drive.google.com/uc?export=download&id='.$it['drive_id'];
                $img = @file_get_contents($url);
                if ($img) {
                    file_put_contents($targetPath, $img);
                }
            }

            $p = Product::updateOrCreate(['nama' => $it['nama']], [
                'deskripsi' => $it['deskripsi'],
                'kategori' => 'Tumbler',
                'foto' => $it['foto'],
                'berat_gram' => $it['berat_gram'],
                'harga_enduser' => $it['harga_enduser'],
                'harga_reseller' => $it['harga_reseller'],
                'min_reseller' => 1,
                'is_active' => true,
            ]);

            // Variants
            foreach ($it['varian'] as $i => $warna) {
                $p->variants()->updateOrCreate(
                    ['warna' => $warna, 'ukuran' => 'All Size'],
                    ['stok' => 20, 'sku' => $it['kode'].'-'.($i + 1)]
                );
            }
            $p->syncStok();

            // Tier prices (Stage 2 Penyesuaian Harga Kuantitas)
            // User regular quantity tiers
            $enduser = $it['harga_enduser'];
            $reseller = $it['harga_reseller'];

            ProductPrice::updateOrCreate(
                ['product_id' => $p->id, 'level' => 'user', 'min_qty' => 1],
                ['price' => $enduser]
            );
            ProductPrice::updateOrCreate(
                ['product_id' => $p->id, 'level' => 'user', 'min_qty' => 3],
                ['price' => round($enduser * 0.96 / 1000) * 1000] // Diskon ~4%
            );
            ProductPrice::updateOrCreate(
                ['product_id' => $p->id, 'level' => 'user', 'min_qty' => 6],
                ['price' => round($enduser * 0.92 / 1000) * 1000] // Diskon ~8%
            );
            ProductPrice::updateOrCreate(
                ['product_id' => $p->id, 'level' => 'user', 'min_qty' => 12],
                ['price' => round($enduser * 0.88 / 1000) * 1000] // Diskon ~12% (grosir)
            );

            // Reseller quantity tiers
            ProductPrice::updateOrCreate(
                ['product_id' => $p->id, 'level' => 'reseller', 'min_qty' => 1],
                ['price' => $reseller]
            );
            ProductPrice::updateOrCreate(
                ['product_id' => $p->id, 'level' => 'reseller', 'min_qty' => 6],
                ['price' => round($reseller * 0.96 / 1000) * 1000] // Diskon reseller qty 6+
            );
            ProductPrice::updateOrCreate(
                ['product_id' => $p->id, 'level' => 'reseller', 'min_qty' => 12],
                ['price' => round($reseller * 0.92 / 1000) * 1000] // Diskon reseller qty 12+
            );
            ProductPrice::updateOrCreate(
                ['product_id' => $p->id, 'level' => 'reseller', 'min_qty' => 24],
                ['price' => round($reseller * 0.88 / 1000) * 1000] // Diskon reseller grosir partai 24+
            );
        }
    }
}
