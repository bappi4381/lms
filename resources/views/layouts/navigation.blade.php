@php
    $navMenus = [
        [
            'label' => 'একাডেমিক',
            'items' => [
                ['label' => 'SSC', 'href' => route('courses.list', ['q' => 'SSC'])],
                ['label' => 'HSC', 'href' => route('courses.list', ['q' => 'HSC'])],
                ['label' => 'University Admission', 'href' => route('courses.list', ['q' => 'Admission'])],
                ['label' => 'BCS/Govt Job', 'href' => route('courses.list', ['q' => 'BCS'])],
            ],
        ],
        [
            'label' => 'স্কিলস',
            'items' => [
                ['label' => 'Web Development', 'href' => route('courses.list', ['q' => 'Web'])],
                ['label' => 'Graphic Design', 'href' => route('courses.list', ['q' => 'Design'])],
                ['label' => 'Digital Marketing', 'href' => route('courses.list', ['q' => 'Marketing'])],
                ['label' => 'Excel & Data', 'href' => route('courses.list', ['q' => 'Excel'])],
            ],
        ],
        [
            'label' => 'টেস্ট প্রস্তুতি',
            'items' => [
                ['label' => 'BCS', 'href' => route('courses.list', ['q' => 'BCS'])],
                ['label' => 'IELTS', 'href' => route('courses.list', ['q' => 'IELTS'])],
                ['label' => 'Bank Job', 'href' => route('courses.list', ['q' => 'Bank'])],
            ],
        ],
        [
            'label' => 'প্রফেশনাল',
            'items' => [
                ['label' => 'CA / Maritime', 'href' => route('courses.list', ['q' => 'CA'])],
                ['label' => 'Freelancing', 'href' => route('courses.list', ['q' => 'Freelancing'])],
            ],
        ],
    ];

    $navLinks = [
        ['label' => 'রিসোর্স', 'href' => route('courses.list', ['resources' => 1]), 'active' => request()->boolean('resources')],
        ['label' => 'স্টোর', 'href' => route('courses.list', ['store' => 1]), 'active' => request()->boolean('store')],
        ['label' => 'ব্লগ', 'href' => route('courses.list', ['blog' => 1]), 'active' => request()->boolean('blog')],
    ];
@endphp

<nav
    x-data="{
        open: false,
        searchOpen: false,
        cartCount: 0,
        dark: localStorage.getItem('theme') === 'dark',
        toggleDark() {
            this.dark = !this.dark;
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', this.dark);
        }
    }"
    x-init="
        document.documentElement.classList.toggle('dark', dark);
        try { cartCount = JSON.parse(localStorage.getItem('secondshiftbd_cart') || '[]').reduce((s, i) => s + i.quantity, 0); } catch(e) {}
    "
    @cart-updated.window="cartCount = $event.detail.count"
    class="glass-nav"
>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16 lg:h-[4.25rem] gap-4">
            {{-- Logo --}}
            <div class="shrink-0 flex items-center">
                <a href="{{ route('courses.index') }}" class="focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue rounded-lg">
                    <x-application-logo compact class="h-9" />
                </a>
            </div>

            {{-- Desktop center nav --}}
            <div class="hidden xl:flex items-center gap-0.5 flex-1 justify-center">
                @foreach($navMenus as $menu)
                    <div x-data="{ open: false }" class="relative" @mouseenter="open = true" @mouseleave="open = false">
                        <button
                            type="button"
                            class="inline-flex items-center gap-1 px-3 py-2 text-sm font-semibold text-gray-700 hover:text-brand-navy rounded-lg glass-nav-hover focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue"
                            :aria-expanded="open"
                        >
                            {{ $menu['label'] }}
                            <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div
                            x-show="open"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 translate-y-1"
                            class="absolute left-0 top-full pt-1 w-48 z-[60]"
                            style="display: none;"
                        >
                            <div class="glass-dropdown py-1.5">
                                @foreach($menu['items'] as $item)
                                    <a href="{{ $item['href'] }}" class="glass-dropdown-link">
                                        {{ $item['label'] }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                @foreach($navLinks as $link)
                    <a href="{{ $link['href'] }}"
                        class="px-3 py-2 text-sm font-semibold rounded-lg transition-colors
                            {{ ($link['active'] ?? false)
                                ? 'glass-pill-active rounded-full'
                                : 'text-gray-700 hover:text-brand-navy glass-nav-hover' }}">
                        {{ $link['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- Desktop actions --}}
            <div class="hidden sm:flex items-center gap-2 shrink-0">
                <button
                    type="button"
                    @click="searchOpen = true"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-xl glass-icon-btn text-brand-navy transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue"
                    aria-label="খুঁজুন"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                </button>

                <button
                    type="button"
                    class="inline-flex items-center justify-center min-w-[2.5rem] h-10 px-2 rounded-xl glass-icon-btn text-brand-navy text-xs font-bold"
                    aria-label="Switch language"
                >
                    EN
                </button>

                <button
                    type="button"
                    @click="toggleDark()"
                    class="inline-flex items-center justify-center w-10 h-10 rounded-xl glass-icon-btn text-brand-navy transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue"
                    :aria-label="dark ? 'Light mode' : 'Dark mode'"
                >
                    <svg x-show="!dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg x-show="dark" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>

                <button
                    type="button"
                    @click="$dispatch('open-cart-drawer')"
                    class="relative inline-flex items-center justify-center w-10 h-10 rounded-xl glass-icon-btn text-brand-navy transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue"
                    aria-label="কার্ট"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    <span
                        x-show="cartCount > 0"
                        x-text="cartCount"
                        class="absolute -top-0.5 -right-0.5 min-w-[1rem] h-4 px-1 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center"
                        style="display: none;"
                    ></span>
                </button>

                @auth
                    @php $unreadNotifications = auth()->user()->unreadNotifications()->latest()->take(8)->get(); @endphp
                    <x-dropdown align="right" width="w-80">
                        <x-slot name="trigger">
                            <button class="relative inline-flex items-center justify-center w-10 h-10 rounded-xl glass-icon-btn text-brand-navy transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                @if($unreadNotifications->count())
                                    <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ $unreadNotifications->count() }}</span>
                                @endif
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <div class="max-h-96 overflow-y-auto">
                                @forelse($unreadNotifications as $notification)
                                    <div class="px-4 py-3 text-sm text-gray-700 border-b glass-divider last:border-0">
                                        {{ $notification->data['message'] ?? 'নতুন নোটিফিকেশন' }}
                                        <div class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                                    </div>
                                @empty
                                    <div class="px-4 py-6 text-sm text-gray-400 text-center">কোনো নতুন নোটিফিকেশন নেই।</div>
                                @endforelse
                            </div>
                        </x-slot>
                    </x-dropdown>

                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center gap-2 px-3 py-2 text-sm font-semibold text-gray-700 hover:text-brand-navy rounded-xl glass-nav-hover">
                                <span class="max-w-[8rem] truncate">{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('dashboard')">{{ __('আমার ড্যাশবোর্ড') }}</x-dropdown-link>
                            <x-dropdown-link :href="route('profile.edit')">{{ __('Profile') }}</x-dropdown-link>
                            <x-dropdown-link :href="route('subscriptions.index')">{{ __('সাবস্ক্রিপশন প্ল্যান') }}</x-dropdown-link>
                            <x-dropdown-link :href="route('profile.certificates')">{{ __('সার্টিফিকেট') }}</x-dropdown-link>
                            <x-dropdown-link :href="route('profile.payment-history')">{{ __('পেমেন্ট হিস্টোরি') }}</x-dropdown-link>
                            <x-dropdown-link :href="route('devices.index')">{{ __('আমার ডিভাইস') }}</x-dropdown-link>
                            <x-dropdown-link :href="route('support.index')">{{ __('সাপোর্ট') }}</x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <button
                        type="button"
                        @click="$dispatch('open-auth-drawer')"
                        class="md-ripple inline-flex items-center justify-center min-h-[44px] px-5 py-2.5 rounded-xl glass-btn-soft text-sm"
                    >
                        লগইন
                    </button>
                @endauth
            </div>

            {{-- Mobile menu button --}}
            <button
                @click="open = !open"
                class="sm:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-700 glass-icon-btn focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue"
                aria-label="Menu"
            >
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden glass-mobile-menu">
        <div class="px-4 py-3 space-y-1 max-h-[70vh] overflow-y-auto">
            @foreach($navMenus as $menu)
                <div x-data="{ expanded: false }" class="border-b glass-divider last:border-0">
                    <button type="button" @click="expanded = !expanded" class="w-full flex items-center justify-between py-3 text-sm font-semibold text-gray-800">
                        {{ $menu['label'] }}
                        <svg class="w-4 h-4 text-gray-400 transition-transform" :class="expanded && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="expanded" class="pb-2 pl-3 space-y-1" style="display:none;">
                        @foreach($menu['items'] as $item)
                            <a href="{{ $item['href'] }}" class="block py-2 text-sm text-gray-600 hover:text-brand-navy">{{ $item['label'] }}</a>
                        @endforeach
                    </div>
                </div>
            @endforeach
            @foreach($navLinks as $link)
                <a href="{{ $link['href'] }}" class="block py-3 text-sm font-semibold text-gray-800 border-b glass-divider">{{ $link['label'] }}</a>
            @endforeach
            @auth
                <a href="{{ route('dashboard') }}" class="block py-3 text-sm font-semibold text-gray-800">ড্যাশবোর্ড</a>
            @else
                <button type="button" @click="$dispatch('open-auth-drawer'); open = false" class="mt-3 w-full min-h-[44px] rounded-xl glass-btn-soft text-sm">লগইন</button>
            @endauth
        </div>
    </div>

    {{-- Search overlay --}}
    <div
        x-show="searchOpen"
        x-transition.opacity
        class="fixed inset-0 z-[60] glass-backdrop-dark flex items-start justify-center pt-24 px-4"
        style="display:none;"
        @keydown.escape.window="searchOpen = false"
    >
        <div @click.outside="searchOpen = false" class="w-full max-w-xl glass-search-overlay p-2 flex items-center gap-2">
            <svg class="w-5 h-5 text-brand-blue ml-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
            <form action="{{ route('courses.list') }}" method="GET" class="flex flex-1 items-center gap-2 min-w-0">
                <input type="text" name="q" autofocus placeholder="কোর্স, বই বা স্কিল খুঁজুন..." class="flex-1 border-0 focus:ring-0 text-sm text-neu-text placeholder:text-neu-muted bg-transparent min-w-0">
                <button type="submit" class="md-ripple shrink-0 glass-btn px-5 py-2.5 rounded-xl font-bold text-sm">খুঁজুন</button>
            </form>
        </div>
    </div>
</nav>
