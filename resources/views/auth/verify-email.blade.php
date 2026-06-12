@extends('layouts.app')
@section('title', 'Verifikasi Email - StokIn')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">

            <div class="text-center mb-8">
                <a href="/" class="text-3xl font-bold text-gray-800">Stok<span class="text-amber-600">In</span></a>
                <h2 class="mt-4 text-2xl font-bold text-gray-900">Verifikasi Email Anda</h2>
                <p class="mt-2 text-gray-600">Satu langkah lagi sebelum memulai</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8">

                <div class="flex justify-center mb-6">
                    <div
                        class="w-20 h-20 bg-amber-50 rounded-full flex items-center justify-center border-4 border-amber-100">
                        <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

                <p class="text-gray-600 text-center mb-6">
                    Kami telah mengirimkan link verifikasi ke
                    <span class="font-semibold text-gray-800">{{ Auth::user()->email }}</span>.
                    Silakan cek <strong>inbox</strong> atau folder <strong>spam</strong> Anda.
                </p>

                {{-- Alert: Link berhasil dikirim ulang --}}
                @if (session('status') === 'verification-link-sent')
                    <div class="mb-5 p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
                        <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm text-green-700">Link verifikasi baru telah dikirim ke email Anda.</p>
                    </div>
                @endif

                {{-- Alert: Pesan info setelah register --}}
                @if (session('info'))
                    <div class="mb-5 p-4 bg-blue-50 border border-blue-200 rounded-lg flex items-start gap-3">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm text-blue-700">{{ session('info') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-3 px-3 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <div class="mt-5 text-center">
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 underline">
                            Keluar dan gunakan akun lain
                        </button>
                    </form>
                </div>

            </div>

            <div class="mt-6 text-center">
                <a href="/" class="text-gray-500 hover:text-gray-700 text-sm">
                    <i class="fa-regular fa-circle-left text-lg"></i> Kembali ke Beranda
                </a>
            </div>

        </div>
    </div>
@endsection
