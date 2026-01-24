@extends('layouts.app')
@section('title', 'Beranda')

@section('content')
    <nav class="bg-amber-500 shadow-lg fixed top-0 w-full z-50">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <p class="text-xl font-bold text-gray-800">Stok<span class="text-white">In</span></p>
            </a>

            <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                <a href="{{ route('login') }}"
                    class="hidden md:block text-amber-600 bg-white hover:bg-gray-900 hover:text-white
                           focus:outline-none font-medium
                           rounded-lg text-md px-6 py-2.5 text-center transition-all duration-300 ease-in-out shadow-lg active:scale-95">
                    Masuk
                </a>

                <button data-collapse-toggle="navbar-default" type="button"
                    class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm
                           text-gray-500 rounded-lg md:hidden hover:bg-gray-100
                           focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                </button>
            </div>

            <div class="hidden w-full md:block md:w-auto" id="navbar-default">
                <ul
                    class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100
                           rounded-lg md:flex-row md:space-x-10 md:mt-0 md:border-0">
                    <li><a href="#features" class="nav-link">Fitur</a></li>
                    <li><a href="#pricing" class="nav-link">Paket</a></li>
                    <li><a href="#contact" class="nav-link">Kontak</a></li>
                    <li class="md:hidden">
                        <a href="{{ route('login') }}"
                            class="block py-2 px-3 text-white bg-amber-600 rounded-lg text-center font-medium mt-2">
                            Masuk
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="bg-white pt-28 md:pt-32 overflow-hidden">
        <div class="grid max-w-screen-xl px-4 py-12 mx-auto lg:gap-8 xl:gap-0 lg:py-20 lg:grid-cols-12">
            <div data-aos="fade-right" data-aos-duration="900" data-aos-easing="ease-out-cubic"
                class="mr-auto place-self-center lg:col-span-6">

                <h1 class="max-w-2xl mb-4 text-4xl font-extrabold tracking-tight leading-tight md:text-5xl xl:text-6xl">
                    Kelola Stok Toko dengan
                    <span class="text-amber-600">Lebih Mudah</span>
                </h1>

                <p class="max-w-2xl mb-6 font-light text-gray-600 md:text-lg lg:text-xl">
                    Solusi manajemen stok barang untuk grosir dan gudang.
                    Otomatis, akurat, dan meningkatkan efisensi bisnis anda.
                </p>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center justify-center px-6 py-3 text-base font-medium
                          text-white rounded-lg bg-amber-600 hover:bg-amber-700
                          focus:ring-4 focus:ring-amber-300
                          transition-all duration-300
                          shadow-lg shadow-amber-500/40 hover:-translate-y-0.5">
                        Mulai Trial Gratis
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>

                    <a href="#features"
                        class="inline-flex items-center justify-center px-6 py-3 text-base font-medium
                          text-gray-900 border border-gray-300 rounded-lg
                          hover:bg-gray-100 transition-colors">
                        Lihat Fitur
                    </a>
                </div>
            </div>

            <div data-aos="fade-left" data-aos-delay="150" data-aos-duration="900" data-aos-easing="ease-out-cubic"
                class="hidden lg:flex lg:col-span-6 justify-center">
                <img src="{{ asset('images/box1.png') }}" alt="Aplikasi StokIn di Laptop"
                    class="w-full max-w-md xl:max-w-lg mx-auto">
            </div>
        </div>
    </section>

    <section class="bg-gray-50 pt-20 pb-12 md:pt-24 md:pb-16">
        <div class="max-w-screen-xl mx-auto px-4 lg:px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div data-aos="fade-up" data-aos-delay="0"
                    class="bg-white rounded-xl border border-gray-200 p-8 text-center shadow-md card-hover card-hover-amber">
                    <h3 class="text-3xl md:text-4xl font-extrabold text-amber-600 mb-2">100+</h3>
                    <p class="text-gray-600 font-medium">Pengguna Aktif</p>
                </div>

                <div data-aos="fade-up" data-aos-delay="150"
                    class="bg-white rounded-xl border border-gray-200 p-8 text-center shadow-md card-hover card-hover-amber">
                    <h3 class="text-3xl md:text-4xl font-extrabold text-amber-600 mb-2">500+</h3>
                    <p class="text-gray-600 font-medium">Transaksi per Bulan</p>
                </div>

                <div data-aos="fade-up" data-aos-delay="300"
                    class="bg-white rounded-xl border border-gray-200 p-8 text-center shadow-md card-hover card-hover-amber">
                    <h3 class="text-3xl md:text-4xl font-extrabold text-amber-600 mb-2">24/7</h3>
                    <p class="text-gray-600 font-medium">Dukungan & Akses</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-16 md:py-24">
        <div class="max-w-screen-xl mx-auto px-4 lg:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div data-aos="fade-right" class="relative">
                    <img src="{{ asset('images/tablet.png') }}" alt="StokIn Dashboard di Tablet"
                        class="w-full max-w-lg mx-auto">
                </div>

                <div data-aos="fade-left" data-aos-delay="100">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-black mb-4">
                        Mengapa Memilih <span class="text-amber-500">StokIn</span>?
                    </h2>
                    <p class="text-gray-600 mb-8 text-base md:text-lg">
                        StokIn dirancang khusus untuk membantu bisnis Anda mengelola inventaris dengan lebih efisien dan
                        akurat.
                    </p>

                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">
                                <i class="fa-solid fa-hand-pointer text-amber-500 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-1">Mudah Digunakan</h4>
                                <p class="text-gray-600">Interface intuitif yang dapat dipelajari dalam hitungan menit</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">
                                <i class="fas fa-clock text-amber-500 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-1">Hemat Waktu</h4>
                                <p class="text-gray-600">Otomasi proses pencatatan dan pelaporan stok</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div
                                class="flex-shrink-0 w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">
                                <i class="fas fa-check-double text-amber-500 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-1">Data Akurat</h4>
                                <p class="text-gray-600">Minimalisir kesalahan manual dengan sistem terintegrasi</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div
                                class="flex-shrink-0 w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center">
                                <i class="fas fa-chart-line text-amber-500 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900 mb-1">Skalabilitas</h4>
                                <p class="text-gray-600">Berkembang bersama bisnis Anda dari kecil hingga besar</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="features" class="bg-gray-50 py-12 md:py-20">
        <div class="py-8 px-4 mx-auto max-w-screen-xl lg:px-6">
            <div data-aos="fade-up" class="mx-auto max-w-screen-md text-center mb-8 lg:mb-12">
                <h2 class="mb-4 text-3xl md:text-4xl tracking-tight font-extrabold text-gray-900">
                    Fitur Unggulan
                </h2>
                <p class="font-light text-gray-500 text-base md:text-xl">
                    Semua yang Anda butuhkan untuk mengelola inventaris bisnis dengan efisien
                </p>
            </div>
            <div data-aos="fade-up" data-aos-delay="100">
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="p-6 bg-white rounded-lg shadow-lg card-hover card-hover-amber">
                        <div
                            class="flex justify-center items-center mb-4 w-12 h-12 rounded-full bg-amber-100 text-amber-600">
                            <i class="fas fa-boxes text-xl"></i>
                        </div>
                        <h3 class="mb-2 text-xl font-bold">Manajemen Stok Cerdas</h3>
                        <p class="text-gray-500">
                            Lacak stok masuk, keluar, dan sisa secara real-time. Atur SKU dan kategori dengan mudah.
                        </p>
                    </div>
                    <div class="p-6 bg-white rounded-lg shadow-lg card-hover card-hover-amber">
                        <div
                            class="flex justify-center items-center mb-4 w-12 h-12 rounded-full bg-amber-100 text-amber-600">
                            <i class="fa-solid fa-cart-shopping text-xl"></i>
                        </div>
                        <h3 class="mb-2 text-xl font-bold">Transaksi Pembelian & Penjualan</h3>
                        <p class="text-gray-500">
                            Catat semua transaksi dengan cepat, update stok otomatis, dan lacak riwayat lengkap.
                        </p>
                    </div>
                    <div class="p-6 bg-white rounded-lg shadow-lg card-hover card-hover-amber">
                        <div
                            class="flex justify-center items-center mb-4 w-12 h-12 rounded-full bg-amber-100 text-amber-600">
                            <i class="fa-solid fa-users text-xl"></i>
                        </div>
                        <h3 class="mb-2 text-xl font-bold">Multi User & Role Access</h3>
                        <p class="text-gray-500">
                            Tambahkan tim dengan peran yang berbeda: Admin, Kasir, Staff Gudang, dan Pemilik Toko.
                        </p>
                    </div>
                    <div class="p-6 bg-white rounded-lg shadow-lg card-hover card-hover-amber">
                        <div
                            class="flex justify-center items-center mb-4 w-12 h-12 rounded-full bg-amber-100 text-amber-600">
                            <i class="fa-solid fa-chart-simple text-xl"></i>
                        </div>
                        <h3 class="mb-2 text-xl font-bold">Dashboard Real-time</h3>
                        <p class="text-gray-500">
                            Visualiasi data dengan grafik interaktif, monitoring performa bisnis secara langsung.
                        </p>
                    </div>
                    <div class="p-6 bg-white rounded-lg shadow-lg card-hover card-hover-amber">
                        <div
                            class="flex justify-center items-center mb-4 w-12 h-12 rounded-full bg-amber-100 text-amber-600">
                            <i class="fas fa-bell text-xl"></i>
                        </div>
                        <h3 class="mb-2 text-xl font-bold">Notifikasi Pintar</h3>
                        <p class="text-gray-500">
                            Peringatan stok menipis, kadaluwarsa, dan rekomendasi restock.
                        </p>
                    </div>
                    <div class="p-6 bg-white rounded-lg shadow-lg card-hover card-hover-amber">
                        <div
                            class="flex justify-center items-center mb-4 w-12 h-12 rounded-full bg-amber-100 text-amber-600">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                        <h3 class="mb-2 text-xl font-bold">Laporan & Analisis</h3>
                        <p class="text-gray-500">
                            Buat laporan cepat tentang pergerakan stok, nilai inventaris, dan tren penjualan.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="pricing" class="bg-white py-8 md:py-16">
        <div class="py-4 md:py-8 px-4 mx-auto max-w-screen-xl lg:px-6">
            <div data-aos="fade-up" class="mx-auto max-w-screen-md text-center mb-6 md:mb-8 lg:mb-12">
                <h2 class="mb-4 text-2xl md:text-4xl tracking-tight font-extrabold text-gray-900">Paket Harga</h2>
                <p class="mb-5 font-light text-gray-500 text-base md:text-xl">Pilih paket yang sesuai dengan kebutuhan
                    bisnis Anda</p>
            </div>

            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <div data-aos="fade-up" data-aos-delay="100"
                    class="flex flex-col p-6 md:p-8 text-center text-gray-900 bg-white rounded-2xl border-2 border-gray-200 shadow-lg hover:border-amber-500 hover:shadow-amber-200 transition-all duration-300">
                    <h3 class="mb-2 text-xl md:text-2xl font-bold text-gray-800">Free Trial</h3>
                    <p class="text-sm text-gray-500 mb-4">Coba gratis selama 14 hari</p>

                    <div class="flex justify-center items-baseline my-6">
                        <span class="text-4xl md:text-5xl font-extrabold text-gray-900">Rp 0</span>
                        <span class="ml-2 text-gray-500">/14 hari</span>
                    </div>

                    <ul class="mb-8 space-y-4 text-left">
                        <li class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span>Produk & Transaksi <strong>Unlimited</strong></span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span>1 Owner + 1 Kasir</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span>Manajemen Stok Barang</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span>Transaksi Penjualan</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-green-100 flex items-center justify-center">
                                <i class="fas fa-check text-green-600 text-xs"></i>
                            </div>
                            <span>Laporan & Dashboard</span>
                        </li>
                        <li class="flex items-center gap-3 text-gray-400">
                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-gray-100 flex items-center justify-center">
                                <i class="fas fa-times text-gray-400 text-xs"></i>
                            </div>
                            <span>Export Laporan</span>
                        </li>
                    </ul>

                    <a href="{{ route('register') }}?plan=free"
                        class="mt-auto w-full py-3 px-6 text-amber-600 bg-amber-50 border-2 border-amber-500 rounded-xl font-bold hover:bg-amber-500 hover:text-white transition-all">
                        Mulai Free Trial
                    </a>
                </div>

                <div data-aos="fade-up" data-aos-delay="200"
                    class="flex flex-col p-6 md:p-8 text-center text-white bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl hover:shadow-xl shadow-amber-200 relative overflow-hidden">

                    <div class="absolute top-4 right-4 bg-white text-amber-600 text-xs font-bold px-3 py-1 rounded-full">
                        POPULER
                    </div>

                    <h3 class="mb-2 text-xl md:text-2xl font-bold">Pro</h3>
                    <p class="text-sm text-amber-100 mb-4">Untuk bisnis yang berkembang</p>

                    <div class="flex justify-center items-baseline my-6">
                        <span class="text-4xl md:text-5xl font-extrabold">Rp 149K</span>
                        <span class="ml-2 text-amber-100">/bulan</span>
                    </div>

                    <ul class="mb-8 space-y-4 text-left">
                        <li class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-white/20 flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                            <span>Produk & Transaksi <strong>Unlimited</strong></span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-white/20 flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                            <span>Pengguna <strong>Unlimited</strong></span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-white/20 flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                            <span>Manajemen Stok Barang</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-white/20 flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                            <span>Transaksi Penjualan</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-white/20 flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                            <span>Laporan & Dashboard</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <div class="flex-shrink-0 w-5 h-5 rounded-full bg-white/20 flex items-center justify-center">
                                <i class="fas fa-check text-white text-xs"></i>
                            </div>
                            <span>Export Laporan</span>
                        </li>
                    </ul>

                    <a href="{{ route('register') }}?plan=pro"
                        class="mt-auto w-full py-3 px-6 text-amber-600 bg-white rounded-xl font-bold hover:bg-gray-100 transition-all shadow-lg">
                        Pilih Pro
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white py-12 md:py-16">
        <div data-aos="fade-up" class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:px-6">
            <h2 class="mb-4 text-xl md:text-4xl tracking-tight font-bold text-black">
                Siap Mengoptimalkan Bisnis Anda?
            </h2>
            <p class="mb-8 font-light text-black text-base md:text-xl max-w-2xl mx-auto">
                Bergabung dengan ribuan pemilik bisnis yang sudah menggunakan StokIn untuk mengelola inventaris mereka
                dengan lebih efisien.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-6">
                <a href="{{ route('register') }}"
                    class="inline-flex items-center justify-center px-6 py-3 text-base font-medium text-amber-600 bg-white border border-amber-500 rounded-lg focus:ring-4 focus:ring-amber-300 transition-colors hover:scale-110 hover:shadow-lg">
                    Mulai Trial Gratis
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="#contact"
                    class="bg-amber-600 inline-flex items-center justify-center px-6 py-3 text-base font-medium text-white border-2 border-white rounded-lg hover:bg-amber-600 focus:ring-4 focus:ring-amber-300 transition-colors hover:scale-110">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </section>

    <footer class="bg-gray-50 border-t border-gray-100">
        <div class="mx-auto w-full max-w-screen-xl p-4 py-6 lg:py-8">
            <div class="md:flex md:justify-between">
                <div class="mb-6 md:mb-0">
                    <a href="/" class="flex items-center">
                        <img src="{{ asset('images/logo.png') }}" alt="StokIn Logo" class="h-8 md:h-36 me-3">
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-4">
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase text-dark">Fitur</h2>
                        <ul class="text-gray-500 dark:text-gray-400 font-medium">
                            <li class="mb-4"><a href="#features" class="hover:underline">Inventaris</a></li>
                            <li class="mb-4"><a href="#features" class="hover:underline">Laporan</a></li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase text-dark">Harga</h2>
                        <ul class="text-gray-500 dark:text-gray-400 font-medium">
                            <li class="mb-4"><a href="#pricing" class="hover:underline">Free Trial</a></li>
                            <li class="mb-4"><a href="#pricing" class="hover:underline">Paket Pro</a></li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase text-dark">Legal</h2>
                        <ul class="text-gray-500 dark:text-gray-400 font-medium">
                            <li class="mb-4"><a href="#" class="hover:underline">Privacy Policy</a></li>
                            <li class="mb-4"><a href="#" class="hover:underline">Terms & Conditions</a></li>
                        </ul>
                    </div>
                    <div id="contact">
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase text-dark">Kontak</h2>
                        <ul class="text-gray-500 dark:text-gray-400 font-medium">
                            <li class="mb-4 flex items-center">
                                <i class="fab fa-whatsapp text-green-500 mr-2"></i>
                                <a href="https://wa.me/62895380187668" class="hover:underline">0895-3801-8766</a>
                            </li>
                            <li class="mb-4 flex items-center">
                                <i class="fas fa-envelope text-amber-600 mr-2"></i>
                                <a href="mailto:info@stokin.com" class="hover:underline">info@stokin.com</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8" />
            <div class="sm:flex sm:items-center sm:justify-between">
                <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">
                    © 2025 <a href="/" class="hover:underline">StokIn™</a>. All Rights Reserved.
                </span>
            </div>
        </div>
    </footer>
@endsection
