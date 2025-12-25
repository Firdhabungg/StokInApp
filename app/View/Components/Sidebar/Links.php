<?php

namespace App\View\Components\Sidebar;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Links extends Component
{
    public string $title, $route, $icon, $active;

    public function __construct($title, $route, $icon)
    {
        $this->title = $title;
        $this->route = $route;
        $this->icon = $icon;

        if ($route === '#') {
            $this->active = 'text-gray-600';
        } else {
            $basePath = $this->generatePath($route);
            $this->active = request()->routeIs($basePath) ? 'bg-amber-500 text-white font-medium' : 'text-gray-600 hover:bg-amber-50 hover:text-amber-600';
        }
    }

    public function generatePath($route)
    {
        if (str_contains($route, '.')) {
            $path = explode('.', $route);
            
            // For admin.dashboard - use exact match
            if ($path[0] === 'admin' && isset($path[1]) && $path[1] === 'dashboard') {
                return 'admin.dashboard';
            }
            
            // For admin routes like admin.toko.index, admin.pelanggan.index
            // Use admin.toko.*, admin.pelanggan.*
            if ($path[0] === 'admin' && count($path) >= 3) {
                return $path[0] . '.' . $path[1] . '.*';
            }
            
            // For admin routes with 2 parts like admin.kelola-paket.index
            if ($path[0] === 'admin' && count($path) >= 2) {
                return $path[0] . '.' . $path[1] . '.*';
            }
            
            // For nested routes like stock.in.index, stock.out.index
            if (count($path) >= 3) {
                return $path[0] . '.' . $path[1] . '.*';
            }
            
            // For routes like barang.index, penjualan.index
            return $path[0] . '.*';
        } else {
            return $route;
        }
    }

    public function render(): View|Closure|string
    {
        return view('components.sidebar.links');
    }
}
