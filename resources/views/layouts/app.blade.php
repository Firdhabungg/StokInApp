<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'StokIn') }} - Kelola Stok Toko Lebih Mudah</title>
    <meta name="description" content="Solusi manajemen stok barang untuk grosir dan gudang. Otomatis, akurat, dan meningkatkan efisiensi bisnis Anda.">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-50">

    @yield('content')

    <script>
        // Mobile menu toggle
        const menuButton = document.querySelector('[data-collapse-toggle="navbar-default"]');
        const mobileMenu = document.getElementById('navbar-default');

        if (menuButton && mobileMenu) {
            menuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }
    </script>
</body>

</html>
