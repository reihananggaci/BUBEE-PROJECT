<?php

namespace Tests\Feature;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LupaPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_lupa_password_bisa_dibuka(): void
    {
        $response = $this->get('/lupa-password');
        $response->assertStatus(200);
        $response->assertSee('Lupa Password');
    }

    public function test_kirim_otp_gagal_jika_email_tidak_terdaftar(): void
    {
        $response = $this->post('/lupa-password', [
            'email' => 'tidakada@bubee.id',
        ]);

        $response->assertSessionHasErrors('email');
    }

    public function test_kirim_otp_berhasil_untuk_email_terdaftar(): void
    {
        $user = User::create([
            'name' => 'Budi Test',
            'email' => 'user@bubee.id',
            'password' => Hash::make('password'),
            'hp' => '081234567890',
            'role' => 'enduser',
        ]);

        $response = $this->post('/lupa-password', [
            'email' => $user->email,
        ]);

        $response->assertRedirect('/lupa-password/verifikasi');
        $this->assertEquals($user->email, session('reset_password_email'));

        $this->assertDatabaseHas('otps', [
            'email' => $user->email,
            'dipakai_at' => null,
        ]);
    }

    public function test_reset_password_berhasil_dengan_otp_valid(): void
    {
        $user = User::create([
            'name' => 'Budi Test',
            'email' => 'user@bubee.id',
            'password' => Hash::make('passwordLama'),
            'hp' => '081234567890',
            'role' => 'enduser',
        ]);
        
        // Simulasikan session reset
        $this->withSession(['reset_password_email' => $user->email]);
        
        // Buat OTP
        Otp::create([
            'email' => $user->email,
            'kode_hash' => Hash::make('123456'),
            'kedaluwarsa_at' => now()->addMinutes(10),
        ]);

        // Submit form reset
        $response = $this->post('/lupa-password/verifikasi', [
            'kode' => '123456',
            'password' => 'passwordBaru123',
            'password_confirmation' => 'passwordBaru123',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHas('ok');

        $user->refresh();
        $this->assertTrue(Hash::check('passwordBaru123', $user->password));
    }
}
