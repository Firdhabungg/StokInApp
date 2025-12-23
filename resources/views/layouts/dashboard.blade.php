<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'StokIn') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50 text-slate-900 overflow-x-hidden">
    <div id="sidebarOverlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-40 hidden"></div>

    <div class="flex min-h-screen">
        <x-sidebar />

        <div id="mainWrapper" class="flex-1 flex flex-col min-w-0 md:ml-64 transition-all duration-300">
            <x-header />

            <main class="p-4 md:p-6">
                <div class="max-w-7xl mx-auto">
                    {{-- Alert Success & Error Tetap Disini --}}
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')

    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: 'Apakah Anda yakin ingin keluar dari aplikasi?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#F59E0B',
                cancelButtonColor: '#6B7280',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
</body>

</html>