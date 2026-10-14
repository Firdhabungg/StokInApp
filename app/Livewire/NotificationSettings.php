<?php

namespace App\Livewire;

use App\Mail\TestNotificationMail;
use App\Models\NotificationLogs;
use App\Models\Toko;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Pengaturan Notifikasi')]
#[Layout('layouts.dashboard')]
class NotificationSettings extends Component
{
    public bool $notif_email_enabled = true;
    public string $notif_digest_time = '08:00';
    public ?string $recipient_email = null;
    public string $toko_name = '';

    public function mount(): void
    {
        $user = Auth::user();
        $toko = $user->toko;

        if (! $toko) {
            abort(404, 'Toko tidak ditemukan.');
        }

        $this->toko_name = $toko->name;
        $this->notif_email_enabled = (bool) $toko->notif_email_enabled;
        $this->notif_digest_time = $toko->notif_digest_time ? substr($toko->notif_digest_time, 0, 5) : '08:00';
        $this->recipient_email = $toko->getNotifEmail();
    }

    protected function rules(): array
    {
        return [
            'notif_email_enabled' => 'required|boolean',
            'notif_digest_time'   => ['required', 'regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/'],
        ];
    }

    protected function messages(): array
    {
        return [
            'notif_digest_time.required' => 'Waktu laporan harian wajib diisi.',
            'notif_digest_time.regex'    => 'Format jam tidak valid (contoh: 08:00).',
        ];
    }

    public function save(): void
    {
        $this->validate();

        $toko = Auth::user()->toko;

        if ($toko) {
            $toko->update([
                'notif_email_enabled' => $this->notif_email_enabled,
                'notif_digest_time'   => $this->notif_digest_time . ':00',
            ]);

            session()->flash('success', 'Pengaturan notifikasi berhasil diperbarui.');
        }
    }

    public function sendTestEmail(): void
    {
        $toko = Auth::user()->toko;

        if (! $toko) {
            session()->flash('error', 'Toko tidak ditemukan.');
            return;
        }

        $recipient = $toko->getNotifEmail();

        if (! $recipient) {
            session()->flash('error', 'Email penerima tidak ditemukan. Pastikan profil akun owner atau email toko sudah terisi.');
            return;
        }

        try {
            Mail::to($recipient)->send(new TestNotificationMail($toko));

            NotificationLogs::create([
                'toko_id'   => $toko->id,
                'barang_id' => null,
                'type'      => 'test_email',
                'channel'   => 'email',
                'status'    => 'sent',
                'recipient' => $recipient,
                'payload'   => ['test' => true],
                'sent_at'   => now(),
            ]);

            session()->flash('test_success', "Email uji coba berhasil dikirim ke {$recipient}. Silakan periksa kotak masuk atau spam.");
        } catch (\Throwable $e) {
            Log::error("[TestEmail] Gagal mengirim test email: {$e->getMessage()}");

            NotificationLogs::create([
                'toko_id'   => $toko->id,
                'barang_id' => null,
                'type'      => 'test_email',
                'channel'   => 'email',
                'status'    => 'failed',
                'recipient' => $recipient,
                'error'     => $e->getMessage(),
                'sent_at'   => now(),
            ]);

            session()->flash('test_error', 'Gagal mengirim email: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $tokoId = Auth::user()->effective_toko_id;

        $recentLogs = NotificationLogs::where('toko_id', $tokoId)
            ->with('barang')
            ->latest()
            ->take(6)
            ->get();

        return view('livewire.notification-settings', [
            'recentLogs' => $recentLogs,
        ]);
    }
}
