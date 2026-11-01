<?php

namespace Tests\Feature;

use App\Mail\OtpMail;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OtpTest extends TestCase
{
    use RefreshDatabase;

    private function userBelumVerif(): User
    {
        return User::create([
            'name' => 'Baru', 'email' => 'baru@t.id', 'password' => 'rahasia123',
            'role' => 'enduser', 'hp' => '081299988877',
        ]);
    }

    private function otpAktif(string $email, string $kode = '123456'): Otp
    {
        return Otp::create([
            'email' => $email, 'kode_hash' => Hash::make($kode),
            'kedaluwarsa_at' => now()->addMinutes(10),
        ]);
    }

    public function test_register_mengirim_otp_dan_belum_login(): void
    {
        Mail::fake();
        $this->post('/register', [
            'name' => 'Baru', 'email' => 'baru@t.id', 'password' => 'rahasia123',
            'role' => 'enduser', 'hp' => '081299988877',
        ])->assertRedirect('/verifikasi-otp');
        $this->assertGuest();
        $this->assertDatabaseHas('users', ['email' => 'baru@t.id', 'hp' => '6281299988877']);
        $this->assertNull(User::where('email', 'baru@t.id')->first()->email_verified_at);
        Mail::assertSent(OtpMail::class);
    }

    public function test_register_hp_wajib_dan_unik(): void
    {
        User::create(['name' => 'Lama', 'email' => 'lama@t.id', 'password' => 'x', 'role' => 'enduser', 'hp' => '081211122233', 'email_verified_at' => now()]);
        $this->post('/register', ['name' => 'B', 'email' => 'b@t.id', 'password' => 'rahasia123', 'role' => 'enduser'])
            ->assertSessionHasErrors('hp');
        $this->post('/register', ['name' => 'B', 'email' => 'b@t.id', 'password' => 'rahasia123', 'role' => 'enduser', 'hp' => '081211122233'])
            ->assertSessionHasErrors('hp');
        $this->assertDatabaseMissing('users', ['email' => 'b@t.id']);
    }

    public function test_login_ditolak_sebelum_verifikasi(): void
    {
        Mail::fake();
        $this->userBelumVerif();
        $this->post('/login', ['email' => 'baru@t.id', 'password' => 'rahasia123'])
            ->assertRedirect('/verifikasi-otp');
        $this->assertGuest();
    }

    public function test_kode_benar_memverifikasi_dan_login(): void
    {
        $u = $this->userBelumVerif();
        $this->otpAktif($u->email);
        $this->withSession(['otp_email' => $u->email])
            ->post('/verifikasi-otp', ['kode' => '123456'])
            ->assertRedirect('/');
        $this->assertAuthenticatedAs($u->fresh());
        $this->assertNotNull($u->fresh()->email_verified_at);
    }

    public function test_lima_kali_salah_kode_hangus(): void
    {
        $u = $this->userBelumVerif();
        $this->otpAktif($u->email);
        for ($i = 0; $i < 5; $i++) {
            $this->withSession(['otp_email' => $u->email])
                ->post('/verifikasi-otp', ['kode' => '000000'])
                ->assertSessionHasErrors('kode');
        }
        $this->assertGuest();
        // Kode benar pun sudah hangus
        $this->withSession(['otp_email' => $u->email])
            ->post('/verifikasi-otp', ['kode' => '123456'])
            ->assertSessionHasErrors('kode');
        $this->assertNull($u->fresh()->email_verified_at);
    }

    public function test_kode_kedaluwarsa_ditolak(): void
    {
        $u = $this->userBelumVerif();
        Otp::create(['email' => $u->email, 'kode_hash' => Hash::make('123456'), 'kedaluwarsa_at' => now()->subMinute()]);
        $this->withSession(['otp_email' => $u->email])
            ->post('/verifikasi-otp', ['kode' => '123456'])
            ->assertSessionHasErrors('kode');
        $this->assertGuest();
    }

    public function test_kirim_ulang_ada_cooldown(): void
    {
        Mail::fake();
        $u = $this->userBelumVerif();
        $this->otpAktif($u->email);
        $this->withSession(['otp_email' => $u->email])
            ->post('/verifikasi-otp/kirim-ulang')
            ->assertSessionHasNoErrors();
        // Langsung minta lagi -> ditolak cooldown 60 detik
        $this->withSession(['otp_email' => $u->email])
            ->post('/verifikasi-otp/kirim-ulang')
            ->assertSessionHasErrors('kode');
    }

    public function test_register_email_belum_verif_jadi_percobaan_ulang(): void
    {
        Mail::fake();
        $this->userBelumVerif();
        $this->post('/register', [
            'name' => 'Baru Lagi', 'email' => 'baru@t.id', 'password' => 'rahasia123',
            'role' => 'enduser', 'hp' => '081200011122',
        ])->assertRedirect('/verifikasi-otp');
        $this->assertGuest();
        $this->assertEquals(1, User::where('email', 'baru@t.id')->count());
        $this->assertEquals(2, User::where('email', 'baru@t.id')->first()->daftar_coba);
        // Data pending ditimpa yang terbaru
        $this->assertEquals('Baru Lagi', User::where('email', 'baru@t.id')->first()->name);
    }

    public function test_batas_tiga_kali_daftar_ulang(): void
    {
        Mail::fake();
        $data = ['name' => 'B', 'email' => 'b3@t.id', 'password' => 'rahasia123', 'role' => 'enduser'];
        $this->post('/register', $data + ['hp' => '081200011101'])->assertRedirect('/verifikasi-otp');
        $this->post('/register', $data + ['hp' => '081200011102'])->assertRedirect('/verifikasi-otp');
        $this->post('/register', $data + ['hp' => '081200011103'])->assertRedirect('/verifikasi-otp');
        $this->assertEquals(3, User::where('email', 'b3@t.id')->first()->daftar_coba);
        // Ke-4 ditolak -> disuruh login/WA admin
        $this->post('/register', $data + ['hp' => '081200011104'])
            ->assertRedirect('/login')->assertSessionHasErrors('email');
        $this->assertEquals(3, User::where('email', 'b3@t.id')->first()->fresh()->daftar_coba);
    }

    public function test_login_tidak_menambah_hitungan_daftar(): void
    {
        Mail::fake();
        $u = $this->userBelumVerif();
        $this->assertEquals(1, $u->fresh()->daftar_coba);
        $this->post('/login', ['email' => 'baru@t.id', 'password' => 'rahasia123'])
            ->assertRedirect('/verifikasi-otp');
        $this->assertEquals(1, $u->fresh()->daftar_coba);
    }

    public function test_hp_setara_dianggap_sama(): void
    {
        Mail::fake();
        $this->post('/register', [
            'name' => 'A', 'email' => 'a@t.id', 'password' => 'rahasia123',
            'role' => 'enduser', 'hp' => '081200099900',
        ])->assertRedirect('/verifikasi-otp');
        // 62812... = nomor yang sama -> ditolak
        $this->post('/register', [
            'name' => 'B', 'email' => 'b@t.id', 'password' => 'rahasia123',
            'role' => 'enduser', 'hp' => '6281200099900',
        ])->assertSessionHasErrors('hp');
        $this->assertDatabaseMissing('users', ['email' => 'b@t.id']);
        $this->assertEquals('6281200099900', User::where('email', 'a@t.id')->first()->hp);
    }

    public function test_register_reseller_tersimpan_dan_radio_bisa_diklik(): void
    {
        Mail::fake();
        $this->post('/register', [
            'name' => 'Toko', 'email' => 'toko@t.id', 'password' => 'rahasia123',
            'role' => 'reseller', 'hp' => '081233344455',
            'toko_nama' => 'Toko Berkah', 'nik' => '3271010101010001',
        ])->assertRedirect('/verifikasi-otp');
        $this->assertDatabaseHas('users', ['email' => 'toko@t.id', 'role' => 'reseller', 'status_reseller' => 'pending', 'toko_nama' => 'Toko Berkah', 'nik' => '3271010101010001']);
        // Radio role tidak boleh display:none (label tak bisa diklik di sebagian browser) -> pakai sr-only
        foreach (['/register', '/register?tampilan=web'] as $u) {
            $this->get($u)->assertOk()
                ->assertSee('name="role" value="reseller" class="sr-only"', false)
                ->assertDontSee('name="role" value="reseller" class="hidden"', false);
        }
        // Penanda visual terpilih wajib ada di kedua mode (tanpa ini user mengira tak bisa diklik)
        $this->get('/register')->assertOk()
            ->assertSee('has-[:checked]:border-cyan-500', false);
        $this->get('/register?tampilan=web')->assertOk()
            ->assertSee('has-[:checked]:border-cyan-500', false)
            ->assertSee('has-[:checked]:border-blue-600', false);
    }

    public function test_register_reseller_wajib_data_pribadi(): void
    {
        // Tanpa toko + NIK -> ditolak
        $this->post('/register', [
            'name' => 'Toko', 'email' => 't2@t.id', 'password' => 'rahasia123',
            'role' => 'reseller', 'hp' => '081233344456',
        ])->assertSessionHasErrors(['toko_nama', 'nik']);
        $this->assertDatabaseMissing('users', ['email' => 't2@t.id']);
        // Reguler tanpa data toko tetap lolos
        $this->post('/register', [
            'name' => 'Rgl', 'email' => 'rgl@t.id', 'password' => 'rahasia123',
            'role' => 'enduser', 'hp' => '081233344457',
        ])->assertRedirect('/verifikasi-otp');
    }

    public function test_admin_lihat_data_pribadi_reseller_dan_label_reguler(): void
    {
        $admin = User::create(['name' => 'Admin', 'email' => 'adm@t.id', 'password' => 'x', 'role' => 'admin', 'email_verified_at' => now()]);
        User::create(['name' => 'Rsl', 'email' => 'rsl@t.id', 'password' => 'x', 'role' => 'reseller', 'status_reseller' => 'pending', 'hp' => '628120000001', 'toko_nama' => 'Toko Maju', 'nik' => '3271010101010002', 'email_verified_at' => now()]);
        $this->actingAs($admin)->get('/admin/reseller')->assertOk()
            ->assertSee('Toko Maju', false)->assertSee('3271010101010002', false);
        $this->actingAs($admin)->get('/admin/reseller?tampilan=web')->assertOk()
            ->assertSee('Toko Maju', false)->assertSee('3271010101010002', false);
        // Label peran tampil Reguler, bukan enduser
        $this->actingAs($admin)->get('/admin/users')->assertOk()->assertDontSee('>ENDUSER<', false);
        $this->get('/register')->assertOk()->assertSee('>Reguler<', false)->assertDontSee('>End User<', false);
    }

    public function test_halaman_verifikasi_terbuka_dua_mode(): void
    {
        $u = $this->userBelumVerif();
        $this->withSession(['otp_email' => $u->email, 'tampilan' => 'hp'])
            ->get('/verifikasi-otp')->assertOk()->assertDontSee('lg:grid-cols-2', false);
        $this->withSession(['otp_email' => $u->email])
            ->get('/verifikasi-otp?tampilan=web')->assertOk()->assertSee('lg:grid-cols-2', false);
    }
}
