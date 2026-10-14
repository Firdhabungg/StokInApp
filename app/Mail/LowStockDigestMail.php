<?php

namespace App\Mail;

use App\Models\Toko;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class LowStockDigestMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly Toko $toko,
        public readonly Collection $barangs,
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $tanggal = now()->translatedFormat('d F Y');

        return new Envelope(
            subject: "📦 Laporan Stok Harian — {$this->toko->name} ({$tanggal})",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $barangsHabis   = $this->barangs->where('status', 'habis')->values();
        $barangsMenipis = $this->barangs->where('status', 'menipis')->values();

        return new Content(
            markdown: 'mail.low-stock-digest',
            with: [
                'toko'            => $this->toko,
                'barangsHabis'    => $barangsHabis,
                'barangsMenipis'  => $barangsMenipis,
                'url'             => route('barang.index'),
                'tanggal'         => now()->translatedFormat('l, d F Y'),
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
