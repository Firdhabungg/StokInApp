@extends('layouts.app')
@section('title', 'Reset Password')
@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="text-center mb-8">
                <a href="/" class="text-3xl font-bold text-gray-800">Stok<span class="text-amber-600">In</span></a>
                <h2 class="mt-4 text-2xl font-bold text-gray-900">Reset Password</h2>
                <p class="mt-2 text-gray-600">Buat password baru untuk akun Anda</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-8">
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-600 rounded-lg text-xs">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    {{-- Token tersembunyi, wajib ada --}}
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                            class="w-full px-4 py-3 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                            placeholder="nama@email.com">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                        <input type="password" name="password" id="password" required
                            class="w-full px-4 py-3 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                            placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi
                            Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation" required
                            class="w-full px-4 py-3 border {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                            placeholder="Ulangi password baru">
                    </div>
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                        Reset Password
                    </button>
                </form>
                <div class="mt-6 text-center">
                    <a href="{{ route('login') }}" class="text-amber-600 hover:text-amber-700 font-semibold text-sm">
                        Kembali ke Login
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
