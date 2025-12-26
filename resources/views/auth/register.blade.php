@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl w-full">
            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="/" class="text-3xl font-bold text-gray-800">Stok<span class="text-amber-600">In</span></a>
                <h2 class="mt-4 text-2xl font-bold text-gray-900">Daftar Sebagai Pemilik Toko</h2>
                <p class="mt-2 text-gray-600">Mulai kelola stok toko Anda dengan StokIn</p>
            </div>

            <!-- Registration Form -->
            <div class="bg-white rounded-xl shadow-lg p-8">
                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-8">
                    @csrf
                    <!-- Section 1: User Data -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="bg-amber-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-sm mr-2">1</span>
                            Data Pengguna
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                    placeholder="Nama lengkap Anda">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                    placeholder="nama@email.com">
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                <input type="password" name="password" id="password" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                    placeholder="Minimal 8 karakter">
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                    placeholder="Ulangi password">
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-200">

                    <!-- Section 2: Toko Data -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span class="bg-amber-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-sm mr-2">2</span>
                            Data Toko
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Toko Name -->
                            <div>
                                <label for="toko_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Toko</label>
                                <input type="text" name="toko_name" id="toko_name" value="{{ old('toko_name') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                    placeholder="Nama toko Anda">
                            </div>

                            <!-- Toko Email -->
                            <div>
                                <label for="toko_email" class="block text-sm font-medium text-gray-700 mb-2">Email Toko</label>
                                <input type="email" name="toko_email" id="toko_email" value="{{ old('toko_email') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                    placeholder="tokoanda@stokinapp.com">
                            </div>

                            <!-- Toko Phone -->
                            <div>
                                <label for="toko_phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon Toko</label>
                                <input type="tel" name="toko_phone" id="toko_phone" value="{{ old('toko_phone') }}" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                    placeholder="08xxxxxxxxxx" autocomplete="off">
                            </div>

                            <!-- Toko Address -->
                            <div class="md:col-span-2">
                                <label for="toko_address" class="block text-sm font-medium text-gray-700 mb-2">Alamat Toko</label>
                                <textarea name="toko_address" id="toko_address" rows="3" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors resize-none"
                                    placeholder="Alamat lengkap toko Anda">{{ old('toko_address') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                        class="w-full bg-amber-600 hover:bg-amber-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                        <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                    </button>
                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-gray-600">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-amber-600 hover:text-amber-700 font-semibold">Masuk di sini</a>
                    </p>
                </div>
            </div>

            <!-- Back to Home -->
            <div class="mt-6 text-center">
                <a href="/" class="text-gray-500 hover:text-gray-700 text-sm">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
@endsection
