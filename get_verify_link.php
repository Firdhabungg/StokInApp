#!/usr/bin/env php
<?php
// Script untuk mengambil link verifikasi email dari log Laravel
// Jalankan: php get_verify_link.php

$logFile = __DIR__ . '/storage/logs/laravel.log';

if (!file_exists($logFile)) {
    echo "❌ File log tidak ditemukan.\n";
    exit(1);
}

$content = file_get_contents($logFile);

// Cari semua URL verifikasi email
preg_match_all('/(http[s]?:\/\/[^\s]+email\/verify\/[^\s"\'<>]+)/', $content, $matches);

if (empty($matches[1])) {
    echo "❌ Belum ada link verifikasi di log.\n";
    echo "   Coba register akun baru terlebih dahulu.\n";
    exit(1);
}

// Ambil link terbaru (terakhir)
$latestLink = end($matches[1]);

// Bersihkan karakter yang tidak perlu di akhir
$latestLink = rtrim($latestLink, '.,;"\'>');

echo "\n✅ Link verifikasi terbaru ditemukan!\n\n";
echo "🔗 " . $latestLink . "\n\n";
echo "Salin link di atas dan buka di browser.\n\n";
