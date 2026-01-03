@extends('admin.layouts.app')

@section('title', 'Profil Saya')
@section('header_title', 'Profil Saya')
@section('header_description', 'Kelola informasi akun Anda')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 bg-gradient-to-br from-amber-400 to-amber-600 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-200">
                    <i class="fas fa-user text-white text-3xl font-bold"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h2>
                    <p class="text-gray-500">{{ $user->email }}</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 mt-2">
                        <i class="fas fa-shield-halved mr-1.5"></i>
                        Super Admin
                    </span>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Informasi Profil</h3>
                    <p class="text-sm text-gray-500">Perbarui nama dan email akun Anda</p>
                </div>
                <button onclick="editProfile()"
                    class="bg-amber-500 hover:bg-amber-600 text-white px-4 py-2.5 rounded-xl font-medium transition-all shadow-sm hover:shadow-md active:scale-95">
                    <i class="fas fa-edit mr-2"></i>Edit Profil
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Nama Lengkap</label>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-gray-900 font-medium" id="display-name">{{ $user->name }}</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Email</label>
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-gray-900 font-medium" id="display-email">{{ $user->email }}</p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Keamanan Akun</h3>
                <p class="text-sm text-gray-500">Kelola password dan keamanan akun Anda</p>
            </div>

            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-slate-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-key text-slate-500"></i>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900">Password</h4>
                        <p class="text-sm text-gray-500">Terakhir diubah: Tidak diketahui</p>
                    </div>
                </div>
                <button onclick="changePassword()" 
                    class="text-amber-600 hover:text-amber-700 font-medium hover:bg-amber-50 px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-pen mr-2"></i>Ubah Password
                </button>
            </div>
        </div>

        {{-- Info Box --}}
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 flex items-start gap-3">
            <i class="fas fa-info-circle text-blue-500 mt-0.5"></i>
            <div>
                <p class="text-sm text-blue-800">
                    <strong>Tips Keamanan:</strong> Gunakan password yang kuat dengan kombinasi huruf, angka, dan simbol. 
                    Ubah password secara berkala untuk menjaga keamanan akun Anda.
                </p>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input id="edit_name" type="text" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500" 
                            value="{{ $user->name }}">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input id="edit_email" type="email" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500" 
                            value="{{ $user->email }}">
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save mr-2"></i>Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#F59E0B',
            cancelButtonColor: '#6B7280',
            focusConfirm: false,
            preConfirm: () => {
                const name = document.getElementById('edit_name').value;
                const email = document.getElementById('edit_email').value;
                
                if (!name || !email) {
                    Swal.showValidationMessage('Nama dan email wajib diisi');
                    return false;
                }
                
                if (!email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
                    Swal.showValidationMessage('Format email tidak valid');
                    return false;
                }
                
                return { name, email };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menyimpan...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch("{{ route('admin.profil.update') }}", {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(result.value)
                })
                .then(res => {
                    if (!res.ok) throw res;
                    return res.json();
                })
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonColor: '#F59E0B'
                    }).then(() => location.reload());
                })
                .catch(async (err) => {
                    let message = 'Gagal menyimpan perubahan';
                    if (err.json) {
                        const data = await err.json();
                        message = data.message || Object.values(data.errors || {}).flat().join(', ');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: message,
                        confirmButtonColor: '#F59E0B'
                    });
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Lama</label>
                        <input id="current_password" type="password" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500" 
                            placeholder="Masukkan password lama">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input id="new_password" type="password" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500" 
                            placeholder="Minimal 8 karakter">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                        <input id="confirm_password" type="password" class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500" 
                            placeholder="Ulangi password baru">
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-lock mr-2"></i>Ubah Password',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#F59E0B',
            cancelButtonColor: '#6B7280',
            focusConfirm: false,
            preConfirm: () => {
                const current_password = document.getElementById('current_password').value;
                const password = document.getElementById('new_password').value;
                const password_confirmation = document.getElementById('confirm_password').value;
                
                if (!current_password || !password || !password_confirmation) {
                    Swal.showValidationMessage('Semua field wajib diisi');
                    return false;
                }
                
                if (password.length < 8) {
                    Swal.showValidationMessage('Password baru minimal 8 karakter');
                    return false;
                }
                
                if (password !== password_confirmation) {
                    Swal.showValidationMessage('Konfirmasi password tidak cocok');
                    return false;
                }
                
                return { current_password, password, password_confirmation };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menyimpan...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch("{{ route('admin.profil.password') }}", {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(result.value)
                })
                .then(res => {
                    if (!res.ok) throw res;
                    return res.json();
                })
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        confirmButtonColor: '#F59E0B'
                    });
                })
                .catch(async (err) => {
                    let message = 'Gagal mengubah password';
                    if (err.json) {
                        const data = await err.json();
                        message = data.message || Object.values(data.errors || {}).flat().join(', ');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: message,
                        confirmButtonColor: '#F59E0B'
                    });
                });
            }
        });
    }
</script>
@endpush
