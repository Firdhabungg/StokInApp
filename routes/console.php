<?php

use App\Jobs\SendDailyDigestJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ─── Jadwal Notifikasi Stok ───────────────────────────────────────
// Kirim daily digest stok ke semua owner setiap hari pukul 08:00 WIB
Schedule::job(new SendDailyDigestJob)->dailyAt('08:00');
