<x-mail::message>
# ⚠️ Stok Barang Habis!

Halo, Owner **{{ $toko->name }}**,

Kami mendeteksi bahwa salah satu barang di toko Anda telah **kehabisan stok** dan memerlukan restok segera.

---

## Detail Barang

| | |
|---|---|
| **Nama Barang** | {{ $barang->nama_barang }} |
| **Kode Barang** | {{ $barang->kode_barang }} |
| **Stok Saat Ini** | **0 unit** ❌ |
| **Stok Minimum** | {{ $barang->stok_minimum }} unit |

---

Segera tambahkan stok agar transaksi tidak terganggu.

<x-mail::button :url="$url" color="red">
Tambah Stok Sekarang
</x-mail::button>

Jika Anda sudah menangani hal ini, abaikan email ini.

---

_Notifikasi ini dikirim otomatis oleh **StokInApp** karena stok barang mencapai nol._  
_Anda menerima email ini karena notifikasi aktif untuk toko **{{ $toko->name }}**._
</x-mail::message>
