<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $kode, public string $nama) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Kode verifikasi Bubee: '.$this->kode);
    }

    public function content(): Content
    {
        return new Content(text: 'emails.otp-plain', with: ['kode' => $this->kode, 'nama' => $this->nama]);
    }
}
