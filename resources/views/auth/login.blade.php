@extends('layouts.app')
@section('title', 'Masuk - StokIn')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="text-center mb-6">
                <a href="/" class="text-3xl font-bold text-gray-800">Stok<span class="text-amber-600">In</span></a>
                <h2 class="mt-4 text-2xl font-bold text-gray-900">Masuk ke Akun Anda</h2>
                <p class="mt-2 text-gray-600">Kelola stok toko Anda dengan mudah</p>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-8">
                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-600 rounded-lg text-xs">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" autofocus
                            class="w-full px-4 py-2 text-sm border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-amber-500 focus:border-amber-500 transition-colors"
                            placeholder="Masukkan email Anda" autocomplete="off">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password
                            </label>

                            <a href="{{ route('password.request') }}" wire:navigate
                                class="text-xs text-amber-600 hover:text-red-500 underline">Lupa
                                Password?
                            </a>
                        </div>

                        <div class="relative">
                            <input type="password" name="password" id="password"
                                class="w-full px-4 py-2 pr-12 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} rounded-xl focus:outline-none focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                placeholder="••••••••">

                            <button type="button" onclick="togglePassword()"
                                class="absolute inset-y-0 right-0 flex items-center px-4 text-gray-400 hover:text-amber-600 focus:outline-none">
                                <i id="eyeIcon" class="fas fa-eye text-sm sm:text-sm"></i>
                            </button>
                        </div>

                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 disabled:opacity-70 disabled:cursor-not-allowed">
                        Masuk
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-gray-600">
                        Belum punya akun?
                        <a href="{{ route('register') }}" wire:navigate
                            class="text-amber-600 hover:text-amber-700 font-semibold">Daftar
                            Sekarang</a>
                    </p>
                </div>

            </div>

            <div class="mt-6 text-center">
                <a href="/" class="text-gray-500 hover:text-gray-700 text-sm">
                    <i class="fa-regular fa-circle-left text-lg"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>

@endsection
