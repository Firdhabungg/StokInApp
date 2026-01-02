<li>
    <a href="{{ $route === '#' ? '#' : route($route) }}"
        title="{{ $title }}"
        class="nav-link flex items-center gap-3 px-4 py-3 rounded-lg {{ $active }} transition-colors {{ $route === '#' ? 'cursor-not-allowed opacity-60' : '' }}">
        <i class="{{ $icon }} w-5 flex-shrink-0"></i>
        <span class="nav-text">{{ $title }}</span>
        @if ($route === '#')
            <span class="nav-text ml-auto text-xs text-slate-400">(Soon)</span>
        @endif
    </a>
</li>

