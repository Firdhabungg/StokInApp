@extends('layouts.app')
@section('content')
    <nav class="bg-amber-500 shadow-lg fixed top-0 w-full z-50" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <p class="text-xl font-bold text-gray-800">Stok<span class="text-white">In</span></p>
            </a>

            <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                <a href="{{ route('login') }}"
                    class="hidden md:block text-amber-600 bg-white hover:bg-gray-900 hover:text-white
                           focus:ring-4 focus:outline-none focus:ring-amber-300 font-medium
                           rounded-lg text-md px-6 py-2.5 text-center transition-colors shadow-lg">
                    Masuk
                </a>

                <button @click="mobileMenuOpen = !mobileMenuOpen" type="button"
                    class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm
                           text-gray-500 rounded-lg md:hidden hover:bg-gray-100
                           focus:outline-none focus:ring-2 focus:ring-gray-200">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" :class="{ 'hidden': mobileMenuOpen, 'block': !mobileMenuOpen }" fill="none"
                        viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M1 1h15M1 7h15M1 13h15" />
                    </svg>
                    <svg class="w-5 h-5" :class="{ 'block': mobileMenuOpen, 'hidden': !mobileMenuOpen }" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>

            <div x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95" @click.away="mobileMenuOpen = false"
                class="w-full md:block md:w-auto md:!block" :class="{ 'hidden': !mobileMenuOpen }">
                <ul
                    class="font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100
                           rounded-lg md:flex-row md:space-x-10 md:mt-0 md:border-0">
                    <li><a href="#features" class="nav-link" @click="mobileMenuOpen = false">Fitur</a></li>
                    <li><a href="#pricing" class="nav-link" @click="mobileMenuOpen = false">Paket</a></li>
                    <li><a href="#contact" class="nav-link" @click="mobileMenuOpen = false">Kontak</a></li>
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

    <!-- Hero Section dengan counter animasi -->
    <section class="bg-white pt-28 md:pt-32 overflow-hidden" x-data="heroSection()">
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

                    <button @click="scrollToFeatures()"
                        class="inline-flex items-center justify-center px-6 py-3 text-base font-medium
                          text-gray-900 border border-gray-300 rounded-lg
                          hover:bg-gray-100 transition-colors">
                        Lihat Fitur
                    </button>
                </div>
            </div>

            <div data-aos="fade-left" data-aos-delay="150" data-aos-duration="900" data-aos-easing="ease-out-cubic"
                class="hidden lg:flex lg:col-span-6 justify-center">
                <img src="{{ asset('images/box1.png') }}" alt="Aplikasi StokIn di Laptop"
                    class="w-full max-w-md xl:max-w-lg mx-auto">
            </div>
        </div>
    </section>

    <!-- Stats Section dengan counter animasi -->
    <section class="bg-gray-50 pt-20 pb-12 md:pt-24 md:pb-16" x-data="statsCounter()" x-intersect="startCounting()">
        <div class="max-w-screen-xl mx-auto px-4 lg:px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div data-aos="fade-up" data-aos-delay="0"
                    class="bg-white rounded-xl border border-gray-200 p-8 text-center shadow-md card-hover card-hover-amber">
                    <h3 class="text-3xl md:text-4xl font-extrabold text-amber-600 mb-2">
                        <span x-text="users.toLocaleString()">0</span>+
                    </h3>
                    <p class="text-gray-600 font-medium">Pengguna Aktif</p>
                </div>

                <!-- Card 2 -->
                <div data-aos="fade-up" data-aos-delay="150"
                    class="bg-white rounded-xl border border-gray-200 p-8 text-center shadow-md card-hover card-hover-amber">
                    <h3 class="text-3xl md:text-4xl font-extrabold text-amber-600 mb-2">
                        <span x-text="transactions.toLocaleString()">0</span>K+
                    </h3>
                    <p class="text-gray-600 font-medium">Transaksi per Hari</p>
                </div>

                <!-- Card 3 -->
                <div data-aos="fade-up" data-aos-delay="300"
                    class="bg-white rounded-xl border border-gray-200 p-8 text-center shadow-md card-hover card-hover-amber">
                    <h3 class="text-3xl md:text-4xl font-extrabold text-amber-600 mb-2">24/7</h3>
                    <p class="text-gray-600 font-medium">Dukungan & Akses</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section dengan Alpine.js -->
    <section class="bg-white py-16 md:py-24" x-data="faqSection()">
        <div class="max-w-screen-xl mx-auto px-4 lg:px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-4">
                    Pertanyaan yang Sering Diajukan
                </h2>
                <p class="text-gray-600 text-lg">Temukan jawaban untuk pertanyaan umum tentang StokIn</p>
            </div>

            <div class="max-w-3xl mx-auto space-y-4">
                <template x-for="(faq, index) in faqs" :key="index">
                    <div class="border border-gray-200 rounded-lg">
                        <button @click="toggle(index)"
                            class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50">
                            <span class="font-medium text-gray-900" x-text="faq.question"></span>
                            <i class="fas fa-chevron-down transition-transform duration-200"
                                :class="{ 'rotate-180': faq.open }"></i>
                        </button>
                        <div x-show="faq.open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-96"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 max-h-96" x-transition:leave-end="opacity-0 max-h-0"
                            class="px-6 pb-4 text-gray-600 overflow-hidden">
                            <p x-text="faq.answer"></p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <!-- Sisanya tetap sama seperti sebelumnya... -->
    <!-- [Bagian lainnya dari welcome.blade.php tetap sama] -->
@endsection

@push('scripts')
    <script>
        // Alpine.js Components
        function heroSection() {
            return {
                scrollToFeatures() {
                    document.getElementById('features').scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        }

        function statsCounter() {
            return {
                users: 0,
                transactions: 0,
                started: false,

                startCounting() {
                    if (this.started) return;
                    this.started = true;

                    this.animateCounter('users', 10000, 2000);
                    this.animateCounter('transactions', 5, 1500);
                },

                animateCounter(property, target, duration) {
                    const start = 0;
                    const increment = target / (duration / 16);
                    const timer = setInterval(() => {
                        this[property] += increment;
                        if (this[property] >= target) {
                            this[property] = target;
                            clearInterval(timer);
                        }
                    }, 16);
                }
            }
        }

        function faqSection() {
            return {
                faqs: [{
                        question: "Apakah StokIn cocok untuk bisnis kecil?",
                        answer: "Ya, StokIn dirancang untuk semua ukuran bisnis. Paket Starter kami sangat cocok untuk bisnis kecil dengan hingga 100 produk.",
                        open: false
                    },
                    {
                        question: "Bagaimana cara backup data?",
                        answer: "Data Anda otomatis di-backup setiap hari ke cloud server yang aman. Anda juga bisa export data kapan saja.",
                        open: false
                    },
                    {
                        question: "Apakah bisa digunakan offline?",
                        answer: "StokIn adalah aplikasi web yang membutuhkan koneksi internet. Namun, data tersimpan aman di cloud dan bisa diakses dari mana saja.",
                        open: false
                    },
                    {
                        question: "Bagaimana sistem pembayaran?",
                        answer: "Kami menerima pembayaran melalui transfer bank, e-wallet, dan kartu kredit. Pembayaran dilakukan bulanan atau tahunan.",
                        open: false
                    }
                ],

                toggle(index) {
                    this.faqs[index].open = !this.faqs[index].open;
                }
            }
        }
    </script>
@endpush
