@extends('layouts.app')

@section('content')
    <nav class="bg-white shadow-amber-500 border-gray-200 ">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                {{-- <img src="{{ asset('images/logo.png') }}" alt="StokIn Logo" class="h-8 md:h-10 w-auto"> --}}
                <p class="text-xl font-bold text-gray-800">Stok<span class="text-amber-500">In</span></p>
            </a>
            <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                <a href="/login"
                    class="hidden md:block text-white bg-amber-600 hover:bg-amber-700 focus:ring-4 focus:outline-none focus:ring-amber-300 font-medium rounded-lg text-md px-6 py-2.5 text-center transition-colors shadow-lg">Masuk</a>
                <button data-collapse-toggle="navbar-default" type="button"
                    class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
                    aria-controls="navbar-default" aria-expanded="false">
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
                    class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:flex-row md:space-x-8 rtl:space-x-reverse md:mt-0 md:border-0 md:bg-white">
                    <li>
                        <a href="#features"
                            class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-amber-600 md:p-0 transition-colors">Fitur</a>
                    </li>
                    <li>
                        <a href="#pricing"
                            class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-amber-600 md:p-0 transition-colors">Paket</a>
                    </li>
                    <li>
                        <a href="#contact"
                            class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:border-0 md:hover:text-amber-600 md:p-0 transition-colors">Kontak</a>
                    </li>
                    <li class="md:hidden">
                        <a href="/login"
                            class="block py-2 px-3 text-white bg-amber-600 hover:bg-amber-700 rounded-lg text-center font-medium transition-colors mt-2">Masuk</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="bg-amber-gradient">
        <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-8 xl:gap-0 lg:py-24 lg:grid-cols-12">
            <div data-aos="fade-up" class="mr-auto place-self-center lg:col-span-6">
                <h1
                    class="max-w-2xl mb-4 text-4xl font-extrabold tracking-tight leading-none md:text-5xl xl:text-6xl dark:text-white">
                    Kelola Stok Toko dengan <span class="text-amber-600">Lebih Mudah</span>
                </h1>
                <p class="max-w-2xl mb-6 font-light text-white lg:mb-8 md:text-lg lg:text-xl dark:text-black">
                    Solusi manajemen stok barang untuk grosir dan gudang. Otomatis, akurat, dan meningkatkan efisensi
                    bisnis anda.
                </p>
                <a href="/register"
                    class="inline-flex items-center justify-center px-6 py-3 text-base font-medium text-center text-white rounded-lg bg-amber-600 hover:bg-amber-700 focus:ring-4 focus:ring-amber-300 transition-colors shadow-lg shadow-amber-500/50">
                    Mulai Trial Gratis
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="#features"
                    class="inline-flex items-center justify-center px-6 py-3 ml-4 text-base font-medium text-center text-gray-900 border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 dark:text-white dark:border-gray-700 dark:hover:bg-gray-700 dark:focus:ring-gray-800 transition-colors">
                    Lihat Fitur
                </a>
            </div>
            <div data-aos="fade-left" data-aos-delay="200" class="hidden lg:mt-0 lg:col-span-6 lg:flex">
                <img src="{{ asset('images/hero-desktop.png') }}" alt="Aplikasi StokIn di Laptop"
                    class="rounded-xl shadow-2xl">
            </div>
        </div>
    </section>

    {{-- Stats Section --}}
    <section class="bg-gray-50 py-12 md:py-16">
        <div class="max-w-screen-xl mx-auto px-4 lg:px-6">
            <div data-aos="fade-up" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                    class="bg-white rounded-xl border border-gray-200 p-8 text-center shadow-lg hover:shadow-amber-500 transition-shadow">
                    <h3 class="text-3xl md:text-4xl font-extrabold text-amber-600 mb-2">10.000+</h3>
                    <p class="text-gray-600 font-medium">Pengguna Aktif</p>
                </div>
                <div
                    class="bg-white rounded-xl border border-gray-200 p-8 text-center shadow-lg hover:shadow-amber-500 transition-shadow">
                    <h3 class="text-3xl md:text-4xl font-extrabold text-amber-600 mb-2">5K+</h3>
                    <p class="text-gray-600 font-medium">Transaksi per Hari</p>
                </div>
                <div
                    class="bg-white rounded-xl border border-gray-200 p-8 text-center shadow-lg hover:shadow-amber-500 transition-shadow">
                    <h3 class="text-3xl md:text-4xl font-extrabold text-amber-600 mb-2">24/7</h3>
                    <p class="text-gray-600 font-medium">Dukungan & Akses</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Why Choose StokIn Section --}}
    <section class="bg-amber-gradient py-16 md:py-24">
        <div class="max-w-screen-xl mx-auto px-4 lg:px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                {{-- Image --}}
                <div data-aos="fade-right" class="relative">
                    <img src="{{ asset('images/hero-desktop.png') }}" alt="StokIn Dashboard di Tablet"
                        class="rounded-xl shadow-2xl w-full max-w-lg mx-auto">
                </div>

                {{-- Content --}}
                <div data-aos="fade-left" data-aos-delay="100">
                    <h2 class="text-3xl md:text-4xl font-extrabold text-white mb-4">
                        Mengapa Memilih <span class="text-amber-500">StokIn</span>?
                    </h2>
                    <p class="text-gray-300 mb-8 text-base md:text-lg">
                        StokIn dirancang khusus untuk membantu bisnis Anda mengelola inventaris dengan lebih efisien dan
                        akurat.
                    </p>

                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-white flex items-center justify-center">
                                <i class="fas fa-hand-pointer text-amber-500 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-white mb-1">Mudah Digunakan</h4>
                                <p class="text-gray-400">Interface intuitif yang dapat dipelajari dalam hitungan menit</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-white flex items-center justify-center">
                                <i class="fas fa-clock text-amber-500 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-white mb-1">Hemat Waktu</h4>
                                <p class="text-gray-400">Otomasi proses pencatatan dan pelaporan stok</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-white flex items-center justify-center">
                                <i class="fas fa-check-double text-amber-500 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-white mb-1">Data Akurat</h4>
                                <p class="text-gray-400">Minimalisir kesalahan manual dengan sistem terintegrasi</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-12 h-12 rounded-full bg-white flex items-center justify-center">
                                <i class="fas fa-chart-line text-amber-500 text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-white mb-1">Skalabilitas</h4>
                                <p class="text-gray-400">Berkembang bersama bisnis Anda dari kecil hingga besar</p>
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
                    <div
                        class="p-6 bg-white rounded-lg shadow-lg hover:shadow-xl hover:shadow-amber-200 transition-shadow duration-300">
                        <div
                            class="flex justify-center items-center mb-4 w-12 h-12 rounded-full bg-amber-100 text-amber-600">
                            <i class="fas fa-boxes text-xl"></i>
                        </div>
                        <h3 class="mb-2 text-xl font-bold dark:text-dark-200">Manajemen Stok Cerdas</h3>
                        <p class="text-gray-500 dark:text-gray-400">
                            Lacak stok masuk, keluar, dan sisa secara real-time. Atur SKU dan kategori dengan mudah.
                        </p>
                    </div>
                    <div
                        class="p-6 bg-white rounded-lg shadow-lg hover:shadow-xl hover:shadow-amber-200 transition-shadow duration-300">
                        <div
                            class="flex justify-center items-center mb-4 w-12 h-12 rounded-full bg-amber-100 text-amber-600">
                            <i class="fa-solid fa-cart-shopping text-xl"></i>
                        </div>
                        <h3 class="mb-2 text-xl font-bold dark:text-dark-200">Transaksi Pembelian & Penjualan</h3>
                        <p class="text-gray-500 dark:text-gray-400">
                            Catat semua transaksi dengan cepat, update stok otomatis, dan lacak riwayat lengkap.
                        </p>
                    </div>
                    <div
                        class="p-6 bg-white rounded-lg shadow-lg hover:shadow-xl hover:shadow-amber-200 transition-shadow duration-300">
                        <div
                            class="flex justify-center items-center mb-4 w-12 h-12 rounded-full bg-amber-100 text-amber-600">
                            <i class="fa-solid fa-users text-xl"></i>
                        </div>
                        <h3 class="mb-2 text-xl font-bold dark:text-dark-200">Multi User & Role Access</h3>
                        <p class="text-gray-500 dark:text-gray-400">
                            Tambahkan tim dengan peran yang berbeda: Admin, Kasir, Staff Gudang, dan Pemilik Toko.
                        </p>
                    </div>
                    <div
                        class="p-6 bg-white rounded-lg shadow-lg hover:shadow-xl hover:shadow-amber-200 transition-shadow duration-300">
                        <div
                            class="flex justify-center items-center mb-4 w-12 h-12 rounded-full bg-amber-100 text-amber-600">
                            <i class="fa-solid fa-chart-simple text-xl"></i>
                        </div>
                        <h3 class="mb-2 text-xl font-bold dark:text-dark-200">Dashboard Real-time</h3>
                        <p class="text-gray-500 dark:text-gray-400">
                            Visualiasi data dengan grafik interaktif, monitoring performa bisnis secara langsung.
                        </p>
                    </div>
                    <div
                        class="p-6 bg-white rounded-lg shadow-lg hover:shadow-xl hover:shadow-amber-200 transition-shadow duration-300">
                        <div
                            class="flex justify-center items-center mb-4 w-12 h-12 rounded-full bg-amber-100 text-amber-600">
                            <i class="fas fa-bell text-xl"></i>
                        </div>
                        <h3 class="mb-2 text-xl font-bold dark:text-dark-200">Notifikasi Pintar</h3>
                        <p class="text-gray-500 dark:text-gray-400">
                            Peringatan stok menipis, kadaluwarsa, dan rekomendasi restock.
                        </p>
                    </div>
                    <div
                        class="p-6 bg-white rounded-lg shadow-lg hover:shadow-xl hover:shadow-amber-200 transition-shadow duration-300">
                        <div
                            class="flex justify-center items-center mb-4 w-12 h-12 rounded-full bg-amber-100 text-amber-600">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                        <h3 class="mb-2 text-xl font-bold dark:text-dark-200">Laporan & Analisis</h3>
                        <p class="text-gray-500 dark:text-gray-400">
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
                    bisnis Anda
                </p>
            </div>
            <div class="space-y-6 md:space-y-8 lg:grid lg:grid-cols-3 sm:gap-6 xl:gap-10 lg:space-y-0">
                <div data-aos="fade-up" data-aos-delay="100"
                    class="flex flex-col p-4 md:p-6 mx-auto max-w-lg text-center text-gray-900 bg-white rounded-lg border hover:border-amber-600 shadow-md hover:shadow-amber-400 hover:border-none hover:bg-amber-50">
                    <h3 class="mb-3 md:mb-4 text-xl md:text-2xl font-semibold">Starter</h3>
                    <div class="flex justify-center items-baseline my-4 md:my-8">
                        <span class="mr-2 text-3xl md:text-5xl font-extrabold">Rp0K</span>
                        <span class="text-gray-500 text-sm md:text-base">14 hari</span>
                    </div>
                    <ul class="mb-6 md:mb-8 space-y-3 md:space-y-4 text-left text-sm md:text-base">
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-check text-green-500 text-sm"></i>
                            <span>Hingga 100 produk</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-check text-green-500 text-sm"></i>
                            <span>1 pengguna</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-check text-green-500 text-sm"></i>
                            <span>Export Laporan</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-check text-green-500 text-sm"></i>
                            <span>Dashboard Analytics</span>
                        </li>
                    </ul>
                    <a href="/register"
                        class="text-white bg-amber-600 hover:bg-amber-700 font-medium rounded-lg text-sm px-4 md:px-5 py-2 md:py-2.5 text-center">Pilih
                        Paket</a>
                </div>
                <div data-aos="fade-up" data-aos-delay="200"
                    class="flex flex-col p-4 md:p-6 mx-auto max-w-lg text-center text-gray-900 bg-white rounded-lg border hover:border-amber-600 shadow-md hover:shadow-amber-400 hover:border-none hover:bg-amber-50">
                    <h3 class="mb-3 md:mb-4 text-xl md:text-2xl font-semibold">Pro</h3>
                    <div class="flex justify-center items-baseline my-4 md:my-8">
                        <span class="mr-2 text-3xl md:text-5xl font-extrabold">Rp99K</span>
                        <span class="text-gray-500 text-sm md:text-base">/bulan</span>
                    </div>
                    <ul class="mb-6 md:mb-8 space-y-3 md:space-y-4 text-left text-sm md:text-base">
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-check text-green-500 text-sm"></i>
                            <span>Hingga 1000 produk</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-check text-green-500 text-sm"></i>
                            <span>10 pengguna</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-check text-green-500 text-sm"></i>
                            <span>Semua fitur Starter</span>
                        </li>
                    </ul>
                    <a href="/register"
                        class="text-white bg-amber-600 hover:bg-amber-700 font-medium rounded-lg text-sm px-4 md:px-5 py-2 md:py-2.5 text-center">Pilih
                        Paket</a>
                </div>
                <div data-aos="fade-up" data-aos-delay="300"
                    class="flex flex-col p-4 md:p-6 mx-auto max-w-lg text-center text-gray-900 bg-white rounded-lg border hover:border-amber-600 shadow-md hover:shadow-amber-400 hover:border-none hover:bg-amber-50">
                    <h3 class="mb-3 md:mb-4 text-xl md:text-2xl font-semibold">Enterprise</h3>
                    <div class="flex justify-center items-baseline my-4 md:my-8">
                        <span class="mr-2 text-3xl md:text-5xl font-extrabold">Rp299K</span>
                        <span class="text-gray-500 text-sm md:text-base">/bulan</span>
                    </div>
                    <ul class="mb-6 md:mb-8 space-y-3 md:space-y-4 text-left text-sm md:text-base">
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-check text-green-500 text-sm"></i>
                            <span>Produk unlimited</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-check text-green-500 text-sm"></i>
                            <span>Pengguna unlimited</span>
                        </li>
                        <li class="flex items-center space-x-3">
                            <i class="fas fa-check text-green-500 text-sm"></i>
                            <span>Semua fitur Pro</span>
                        </li>
                    </ul>
                    <a href="/register"
                        class="text-white bg-amber-600 hover:bg-amber-700 font-medium rounded-lg text-sm px-4 md:px-5 py-2 md:py-2.5 text-center">Pilih
                        Paket</a>
                </div>
            </div>
        </div>
    </section>


    {{-- CTA Section --}}
    <section class="bg-amber-gradient py-12 md:py-16">
        <div data-aos="fade-up" class="py-8 px-4 mx-auto max-w-screen-xl text-center lg:px-6">
            <h2 class="mb-4 text-2xl md:text-4xl tracking-tight font-extrabold text-white">
                Siap Mengoptimalkan Bisnis Anda?
            </h2>
            <p class="mb-8 font-light text-amber-100 text-base md:text-xl max-w-2xl mx-auto">
                Bergabung dengan ribuan pemilik bisnis yang sudah menggunakan StokIn untuk mengelola inventaris mereka
                dengan lebih efisien.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="/register"
                    class="inline-flex items-center justify-center px-6 py-3 text-base font-medium text-amber-600 bg-white rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-amber-300 transition-colors shadow-lg">
                    Mulai Trial Gratis
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="#contact"
                    class="inline-flex items-center justify-center px-6 py-3 text-base font-medium text-white border-2 border-white rounded-lg hover:bg-amber-700 focus:ring-4 focus:ring-amber-300 transition-colors">
                    Hubungi Kami
                </a>
            </div>
        </div>
    </section>

    <footer class="bg-white dark:bg-gray-900 border-t border-gray-100">
        <div class="mx-auto w-full max-w-screen-xl p-4 py-6 lg:py-8">
            <div class="md:flex md:justify-between">
                <div class="mb-6 md:mb-0">
                    <a href="/" class="flex items-center">
                        <img src="{{ asset('images/logo.png') }}" alt="StokIn Logo" class="h-8 md:h-10 me-3">
                    </a>
                </div>
                <div class="grid grid-cols-2 gap-8 sm:gap-6 sm:grid-cols-4">
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Fitur</h2>
                        <ul class="text-gray-500 dark:text-gray-400 font-medium">
                            <li class="mb-4"><a href="#features" class="hover:underline">Inventaris</a></li>
                            <li class="mb-4"><a href="#features" class="hover:underline">Laporan</a></li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Harga</h2>
                        <ul class="text-gray-500 dark:text-gray-400 font-medium">
                            <li class="mb-4"><a href="#pricing" class="hover:underline">Paket Starter</a></li>
                            <li class="mb-4"><a href="#pricing" class="hover:underline">Paket Pro</a></li>
                        </ul>
                    </div>
                    <div>
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Legal</h2>
                        <ul class="text-gray-500 dark:text-gray-400 font-medium">
                            <li class="mb-4"><a href="#" class="hover:underline">Privacy Policy</a></li>
                            <li class="mb-4"><a href="#" class="hover:underline">Terms &amp; Conditions</a></li>
                        </ul>
                    </div>
                    <div id="contact">
                        <h2 class="mb-6 text-sm font-semibold text-gray-900 uppercase dark:text-white">Kontak</h2>
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
