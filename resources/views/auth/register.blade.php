@extends('layouts.app')
@section('title', 'Registrasi')

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
                @if (session('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm">
                        <p>{{ session('error') }}</p>
                    </div>
                @endif

                @if ($errors->has('error'))
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm">
                        <p>{{ $errors->first('error') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-8">
                    @csrf
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span
                                class="bg-amber-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-sm mr-2">1</span>
                            Data Pengguna
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama
                                    Lengkap</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" autofocus
                                    class="w-full px-4 py-3 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                    placeholder="Nama lengkap Anda">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="w-full px-4 py-3 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                    placeholder="nama@email.com">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                <input type="password" name="password" id="password"
                                    class="w-full px-4 py-3 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                    placeholder="Minimal 8 karakter">
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation"
                                    class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="w-full px-4 py-3 border {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                    placeholder="Ulangi password">
                                @error('password_confirmation')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="border-gray-200">

                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <span
                                class="bg-amber-600 text-white w-6 h-6 rounded-full flex items-center justify-center text-sm mr-2">2</span>
                            Data Toko
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="toko_name" class="block text-sm font-medium text-gray-700 mb-2">Nama
                                    Toko</label>
                                <input type="text" name="toko_name" id="toko_name" value="{{ old('toko_name') }}"
                                    class="w-full px-4 py-3 border {{ $errors->has('toko_name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                    placeholder="Nama toko Anda">
                                @error('toko_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="toko_email" class="block text-sm font-medium text-gray-700 mb-2">Email
                                    Toko</label>
                                <input type="email" name="toko_email" id="toko_email" value="{{ old('toko_email') }}"
                                    class="w-full px-4 py-3 border {{ $errors->has('toko_email') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                    placeholder="tokoanda@stokinapp.com">
                                @error('toko_email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="toko_phone" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon
                                    Toko</label>
                                <input type="tel" name="toko_phone" id="toko_phone" value="{{ old('toko_phone') }}"
                                    class="w-full px-4 py-3 border {{ $errors->has('toko_phone') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                    placeholder="08xxxxxxxxxx" autocomplete="off">
                                @error('toko_phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="toko_address" class="block text-sm font-medium text-gray-700 mb-2">Alamat
                                    Toko</label>
                                <textarea name="toko_address" id="toko_address" rows="3"
                                    class="w-full px-4 py-3 border {{ $errors->has('toko_address') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors resize-none"
                                    placeholder="Alamat lengkap toko Anda">{{ old('toko_address') }}</textarea>
                                @error('toko_address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 disabled:opacity-70 disabled:cursor-not-allowed">
                        {{-- <svg aria-hidden="true" class="w-4 h-4 text-neutral-quaternary animate-spin fill-brand"
                            viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                fill="currentColor" />
                            <path
                                d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                fill="currentFill" />
                        </svg>
                        <span class="sr-only">Loading...</span> --}}
                        Daftar Sekarang
                    </button>
                </form>

                <!-- Login Link -->
                <div class="mt-6 text-center">
                    <p class="text-gray-600">
                        Sudah punya akun?
                        <a href="{{ route('login') }}" class="text-amber-600 hover:text-amber-700 font-semibold">Masuk di
                            sini</a>
                    </p>
                </div>
            </div>

            <!-- Back to Home -->
            <div class="mt-6 text-center">
                <a href="/" class="text-gray-500 hover:text-gray-700 text-sm">
                    <i class="fa-regular fa-circle-left text-lg"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
@endsection
