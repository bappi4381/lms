{{--
    Sidebar nav item that gracefully degrades when its route hasn't been
    built yet (module still pending conversion from Filament), instead of
    throwing a RouteNotFoundException.
    Props: $route (route name), $pattern (routeIs pattern), $label, $icon (svg path data)
--}}
@if(Route::has($route))
    <a href="{{ route($route) }}" class="admin-nav-item {{ request()->routeIs($pattern) ? 'is-active' : '' }}">
        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="{{ $icon }}"/></svg>
        <span>{{ $label }}</span>
    </a>
@else
    <div class="admin-nav-item cursor-not-allowed opacity-50" title="Coming soon">
        <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="{{ $icon }}"/></svg>
        <span>{{ $label }}</span>
        <span class="admin-badge admin-badge-neutral ml-auto !text-[9px]">Soon</span>
    </div>
@endif
