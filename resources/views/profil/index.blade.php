@extends('layouts.dashboard')
@section('title', 'Profil')
@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 bg-amber-500 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-white text-2xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ auth()->user()->name }}</h2>
                    <p class="text-gray-500">{{ auth()->user()->email }}</p>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                        {{ auth()->user()->isOwner() ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ auth()->user()->isOwner() ? 'Administrator' : 'Staff' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Informasi Profil</h3>
                <button onclick="editProfile()"
                    class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-edit mr-2"></i>Edit Profil
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-900">{{ auth()->user()->name }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-900">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Keamanan Akun</h3>

            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <h4 class="font-medium text-gray-900">Ubah Password</h4>
                    <p class="text-sm text-gray-500">Perbarui password untuk keamanan akun</p>
                </div>
                <button onclick="changePassword()" class="text-amber-600 hover:text-amber-700 font-medium">
                    <i class="fas fa-key mr-2"></i>Ubah Password
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function editProfile() {
            Swal.fire({
                title: 'Edit Profil',
                html: `
            <div class="text-left space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nama Lengkap</label>
                    <input id="name" class="w-full p-2 border rounded" value="{{ auth()->user()->name }}">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Email</label>
                    <input id="email" class="w-full p-2 border rounded" value="{{ auth()->user()->email }}">
                </div>
            </div>
        `,
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#F59E0B'
            });
        }

        function changePassword() {
            Swal.fire({
                title: 'Ubah Password',
                html: `
            <div class="text-left space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Password Lama</label>
                    <input type="password" id="old_password" class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Password Baru</label>
                    <input type="password" id="new_password" class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Konfirmasi Password</label>
                    <input type="password" id="confirm_password" class="w-full p-2 border rounded">
                </div>
            </div>
        `,
                showCancelButton: true,
                confirmButtonText: 'Ubah Password',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#F59E0B'
            });
        }
    </script>
@endpush
