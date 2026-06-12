@extends('layouts.app')
@section('title', 'Forgot Password')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="text-center mb-4">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="mt-4 text-xl font-bold text-gray-900">Forgot Password</h2>
                    @if (session('status'))
                        <div class="my-4 p-4 bg-green-50 border border-green-200 text-green-600 rounded-lg text-xs">
                            <p>{{ session('status') }}</p>
                        </div>
                    @endif
                    <form action="{{ route('password.email') }}" method="post" class="space-y-6">
                        @csrf
                        <div class="text-left">
                            <label for="email" class="text-sm font-medium text-gray-700 mb-2 pl-1">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" autofocus
                                class="w-full px-3 py-2 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors"
                                placeholder="youremail@gmail.com">
                            @error('email')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                            class="w-full rounded-lg p-2 text-sm bg-amber-400 hover:bg-red-500 hover:text-white">Send reset
                            password link</button>
                    </form>
                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="underline text-xs">Back to login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
