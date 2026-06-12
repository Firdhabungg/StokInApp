<?php
// Script debug: Generate URL verifikasi valid untuk user ID 9
// Jalankan di root project: php debug_verify.php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\URL;

$user = App\Models\User::find(9);

echo "=== DEBUG VERIFIKASI EMAIL ===" . PHP_EOL;
echo "User ID    : " . $user->id . PHP_EOL;
echo "Email      : " . $user->email . PHP_EOL;
echo "Verified   : " . ($user->email_verified_at ?? 'NULL') . PHP_EOL;
echo PHP_EOL;

// Generate URL verifikasi yang valid (60 menit)
$url = URL::temporarySignedRoute(
    'verification.verify',
    now()->addMinutes(60),
    ['id' => $user->getKey(), 'hash' => sha1($user->getEmailForVerification())]
);

echo "URL Verifikasi:" . PHP_EOL;
echo $url . PHP_EOL;
echo PHP_EOL;
echo "Salin URL di atas ke browser untuk test verifikasi manual." . PHP_EOL;
