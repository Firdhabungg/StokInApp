<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'StokIn') }} - Kelola Stok Toko Lebih Mudah</title>
    <meta name="description"
        content="Solusi manajemen stok barang untuk grosir dan gudang. Otomatis, akurat, dan meningkatkan efisiensi bisnis Anda.">


    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50">

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
