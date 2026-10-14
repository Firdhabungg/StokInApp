<x-mail::message>
# 📦 Laporan Stok Harian

Halo, Owner **{{ $toko->name }}**,

Berikut adalah ringkasan kondisi stok toko Anda per **{{ $tanggal }}**.

---

@if($barangsHabis->isNotEmpty())
## ❌ Barang Stok Habis ({{ $barangsHabis->count() }} item)

Barang-barang berikut stoknya sudah **habis total** dan perlu direstok segera:

<x-mail::table>
| Nama Barang | Kode | Stok | Min |
|:---|:---|:---:|:---:|
@foreach($barangsHabis as $barang)
| {{ $barang->nama_barang }} | {{ $barang->kode_barang }} | **0** | {{ $barang->stok_minimum }} |
@endforeach
</x-mail::table>

@endif

@if($barangsMenipis->isNotEmpty())
## ⚠️ Barang Stok Menipis ({{ $barangsMenipis->count() }} item)

Barang-barang berikut stoknya **mendekati batas minimum** dan perlu diperhatikan:

<x-mail::table>
| Nama Barang | Kode | Stok Saat Ini | Min |
|:---|:---|:---:|:---:|
@foreach($barangsMenipis as $barang)
| {{ $barang->nama_barang }} | {{ $barang->kode_barang }} | **{{ $barang->stok }}** | {{ $barang->stok_minimum }} |
@endforeach
</x-mail::table>

@endif

---

<x-mail::button :url="$url">
Kelola Stok Sekarang
</x-mail::button>

---

_Laporan ini dikirim otomatis setiap pagi oleh **StokInApp**._  
_Untuk menonaktifkan notifikasi ini, ubah pengaturan di dashboard toko Anda._
</x-mail::message>
