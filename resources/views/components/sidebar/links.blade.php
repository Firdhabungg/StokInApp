<li>
    <a href="{{ $route === '#' ? '#' : route($route) }}"
        class="flex items-center gap-3 px-4 py-3 rounded-lg {{ $active }} transition-colors {{ $route === '#' ? 'cursor-not-allowed opacity-60' : '' }}">
        <i class="{{ $icon }} w-5"></i>
        <span>{{ $title }}</span>
        @if ($route === '#')
            <span class="ml-auto text-xs text-slate-400">(Soon)</span>
        @endif
    </a>
</li>
