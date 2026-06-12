<div align="center">

<<<<<<< HEAD
# 📦 StokIn App

**Aplikasi Manajemen Stok & Penjualan Berbasis Web untuk UMKM Indonesia**
=======
# StokIn App

**Aplikasi Manajemen Stok & Penjualan Berbasis Web untuk Toko Ritel dan Kelontong**
>>>>>>> 722d1970fb69f26565958ae94a395b9aa08b7f50

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![Livewire](https://img.shields.io/badge/Livewire-4.x-FB70A9?style=flat-square)](https://livewire.laravel.com)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-4.x-38BDF8?style=flat-square&logo=tailwindcss&logoColor=white)](https://tailwindcss.com)

</div>

---

## Tentang Proyek

**StokIn App** adalah sistem manajemen stok dan kasir berbasis web untuk UMKM. Setiap toko memiliki data yang terisolasi (*multi-tenant*), dengan sistem role bertingkat dan langganan berbayar untuk akses fitur premium.

**Mengapa StokIn App?** Banyak UMKM masih mencatat stok & penjualan secara manual. StokIn hadir sebagai solusi digital yang terjangkau dengan fitur lengkap:

| Masalah | Solusi |
|---|---|
| Stok tidak terpantau | Dashboard + notifikasi stok menipis |
| Pencatatan penjualan manual | Modul kasir digital terintegrasi |
| Laporan keuangan sulit | Ekspor otomatis ke PDF & Excel |
| Tidak ada pemisahan akses | Role: Super Admin, Owner, Kasir |
| Pembayaran hanya tunai | Integrasi Midtrans (tunai & transfer) |

---

## Fitur Utama

- 🏪 **Multi-Tenant** — Setiap toko terisolasi, Super Admin dapat memantau semua toko
- 👥 **Manajemen Pengguna** — Role *Super Admin*, *Owner*, dan *Kasir*
- 📦 **Manajemen Barang & Stok** — Stok masuk/keluar per batch, notifikasi stok minimum
- 🛒 **Point of Sale** — Kasir responsif, cetak struk PDF, pembayaran tunai & Midtrans
- 📊 **Laporan** — Grafik interaktif, ekspor PDF & Excel
- 💳 **Berlangganan** — Free Trial 14 hari → Pro Rp 149.000/bulan (kasir tak terbatas)

---

## Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | Laravel 12, PHP 8.2+ |
| Frontend | Blade, Livewire 4, Alpine.js, Tailwind CSS 4 |
| UI Library | Flowbite, Font Awesome, SweetAlert2, DataTables.net |
| Database | MySQL 8.0+ |
| Build Tool | Vite 7 |
| PDF / Excel | DomPDF, Maatwebsite Excel |
| Payment | Midtrans PHP SDK |
| Charts | Chart.js, AOS |

---

## Prasyarat

- **PHP** 8.2+ (ekstensi: `pdo_mysql`, `mbstring`, `openssl`, `gd`, dll.)
- **Composer** 2.x
- **Node.js** 18.x+ & **NPM** 9.x+
- **MySQL** 8.0+
- **Laragon** (direkomendasikan) atau XAMPP

---

## Instalasi

```bash
# 1. Clone repositori
git clone https://github.com/Firdhabungg/StokInApp.git
cd StokInApp

# 2. Instal dependensi
composer install
npm install

# 3. Salin & konfigurasi environment
cp .env.example .env        # Windows: copy .env.example .env
php artisan key:generate
```

**Edit `.env`** — sesuaikan konfigurasi database:
```env
DB_DATABASE=stokin
DB_USERNAME=root
DB_PASSWORD=          # kosong untuk default Laragon
```

Buat database di MySQL jika belum ada:
```sql
CREATE DATABASE stokin CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

```bash
# 4. Migrasi & isi data awal
php artisan migrate --seed

# 5. Kompilasi aset
npm run build         # production
# atau: npm run dev   # development (hot reload)
```

---

## Menjalankan Aplikasi

**Development** — jalankan semua layanan sekaligus:
```bash
composer run dev
```
> Menjalankan: `php artisan serve` + queue worker + log viewer + `npm run dev`

**Atau manual** di dua terminal terpisah:
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

Buka browser: **http://127.0.0.1:8000** atau **http://stokinapp.test** (Laragon)

---

## Akun Default

> Tersedia setelah `php artisan db:seed` — **ganti password sebelum production!**

| Role | Email | Password |
|---|---|---|
| Super Admin | `superadmin@stokinapp.com` | `super123` |
| Owner | `owner@stokinapp.com` | `owner123` |
| Kasir | `kasir@stokinapp.com` | `kasir123` |

---

## Konfigurasi Midtrans

Daftar di [midtrans.com](https://midtrans.com), lalu isi di `.env`:
```env
MIDTRANS_SERVER_KEY=SB-Mid-server-xxxx
MIDTRANS_CLIENT_KEY=SB-Mid-client-xxxx
MIDTRANS_IS_PRODUCTION=false   # ubah ke true untuk production
```

---

## Perintah Artisan Berguna

```bash
php artisan route:list                  # Lihat semua route
php artisan make:controller NamaCtrl    # Buat Controller
php artisan make:model NamaModel -msf   # Model + Migration + Seeder
php artisan make:livewire NamaKomponen  # Livewire Component
php artisan migrate:fresh --seed        # Reset DB + seed ulang
php artisan config:clear && php artisan cache:clear  # Bersihkan cache
./vendor/bin/pint                       # Format kode PHP
```

---

## Lisensi

MIT — lihat file [LICENSE](LICENSE).

<div align="center">

Dibuat dengan ❤️ menggunakan **Laravel 12** & **Livewire**

</div>
