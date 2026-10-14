<x-mail::message>
# 🔔 Uji Coba Notifikasi Berhasil!

Halo, Owner **{{ $toko->name }}**,

Ini adalah email uji coba dari sistem notifikasi stok **StokInApp**.

Jika Anda menerima email ini, berarti pengaturan integrasi email untuk toko Anda telah **berfungsi dengan baik**. Anda akan menerima notifikasi otomatis ketika:
- 🔴 Stok barang habis total (0 unit) — dikirim secara langsung.
- 🟡 Stok barang mendekati batas minimum — dirangkum dalam laporan harian setiap pukul **{{ substr($toko->notif_digest_time ?? '08:00', 0, 5) }} WIB**.

---

<x-mail::button :url="$url">
Buka Dashboard StokInApp
</x-mail::button>

Waktu pengujian: **{{ $time }}**

Terima kasih,<br>
Tim {{ config('app.name') }}
</x-mail::message>
