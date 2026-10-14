<?php

namespace App\Mail;

use App\Models\Toko;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly Toko $toko
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "🔔 Uji Coba Notifikasi Email — {$this->toko->name}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.test-notification',
            with: [
                'toko' => $this->toko,
                'time' => now()->translatedFormat('d F Y H:i:s'),
                'url'  => route('dashboard'),
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
