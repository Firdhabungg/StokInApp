@extends('layouts.dashboard')

@section('title', 'Profil')
@section('page-title', 'Profil')
@section('page-description', 'Lihat dan perbarui informasi akun serta data toko Anda')

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
                        {{ auth()->user()->isOwner() ? 'Owner' : 'Kasir' }}
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

        {{-- Informasi Toko --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Informasi Toko</h3>

                @if (auth()->user()->isOwner())
                    <button onclick="editToko()"
                        class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-edit mr-2"></i>Edit Toko
                    </button>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Toko</label>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-900">
                            {{ auth()->user()->toko->name ?? '-' }}
                        </p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Toko</label>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-900">
                            {{ auth()->user()->toko->email ?? '-' }}
                        </p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-900">
                            {{ auth()->user()->toko->phone ?? '-' }}
                        </p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Toko</label>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-gray-900">
                            {{ auth()->user()->toko->address ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>

            @if (!auth()->user()->isOwner())
                <p class="mt-4 text-sm text-gray-500 italic">
                    * Informasi toko hanya dapat diubah oleh owner.
                </p>
            @endif
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
                confirmButtonColor: '#F59E0B',
                preConfirm: () => {
                    return {
                        name: document.getElementById('name').value,
                        email: document.getElementById('email').value
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('profil.update') }}", {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(result.value)
                    })
                    .then(res => {
                        if (!res.ok) throw res;
                        return res.json();
                    })
                    .then(() => {
                        Swal.fire('Berhasil', 'Profil berhasil diperbarui', 'success')
                            .then(() => location.reload());
                    })
                    .catch((err) => {
                        err.json().then(data => {
                            Swal.fire('Gagal', data.message || 'Tidak dapat menyimpan data', 'error');
                        });
                    });
                }
            });
        }

        function editToko() {
            Swal.fire({
                title: 'Edit Informasi Toko',
                html: `
                    <div class="text-left space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-1">Nama Toko</label>
                            <input id="toko_name" class="w-full p-2 border rounded"
                                value="{{ auth()->user()->toko->name ?? '' }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Email Toko</label>
                            <input id="toko_email" class="w-full p-2 border rounded"
                                value="{{ auth()->user()->toko->email ?? '' }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">No. Telepon</label>
                            <input id="toko_phone" class="w-full p-2 border rounded"
                                value="{{ auth()->user()->toko->phone ?? '' }}">
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">Alamat</label>
                            <textarea id="toko_address" class="w-full p-2 border rounded" rows="3">{{ auth()->user()->toko->address ?? '' }}</textarea>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#F59E0B',
                preConfirm: () => {
                    return {
                        name: document.getElementById('toko_name').value,
                        email: document.getElementById('toko_email').value,
                        phone: document.getElementById('toko_phone').value,
                        address: document.getElementById('toko_address').value,
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('profil.toko.update') }}", {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify(result.value)
                        })
                        .then(res => {
                            if (!res.ok) throw res;
                            return res.json();
                        })
                        .then(() => {
                            Swal.fire('Berhasil', 'Informasi toko diperbarui', 'success')
                                .then(() => location.reload());
                        })
                        .catch(() => {
                            Swal.fire('Gagal', 'Tidak dapat menyimpan data', 'error');
                        });
                }
            });
        }

        function changePassword() {
            Swal.fire({
                title: 'Ubah Password',
                html: `
            <div class="text-left space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Password Lama</label>
                    <input type="password" id="current_password" class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Password Baru</label>
                    <input type="password" id="password" class="w-full p-2 border rounded">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" class="w-full p-2 border rounded">
                </div>
            </div>
        `,
                showCancelButton: true,
                confirmButtonText: 'Ubah Password',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#F59E0B',
                preConfirm: () => {
                    const password = document.getElementById('password').value;
                    const confirmation = document.getElementById('password_confirmation').value;
                    
                    if (password !== confirmation) {
                        Swal.showValidationMessage('Konfirmasi password tidak cocok');
                        return false;
                    }
                    
                    return {
                        current_password: document.getElementById('current_password').value,
                        password: password,
                        password_confirmation: confirmation
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('profil.password.update') }}", {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(result.value)
                    })
                    .then(res => {
                        if (!res.ok) throw res;
                        return res.json();
                    })
                    .then(() => {
                        Swal.fire('Berhasil', 'Password berhasil diubah', 'success');
                    })
                    .catch((err) => {
                        err.json().then(data => {
                            Swal.fire('Gagal', data.message || 'Tidak dapat mengubah password', 'error');
                        });
                    });
                }
            });
        }
    </script>
@endpush
