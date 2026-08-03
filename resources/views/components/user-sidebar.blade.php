@php
    $authUser = Auth::user();
    $avatarUrl = null;
    if ($authUser && $authUser->profile_photo_url) {
        $avatarUrl = \Illuminate\Support\Str::startsWith($authUser->profile_photo_url, ['http://', 'https://'])
            ? $authUser->profile_photo_url
            : asset('storage/' . $authUser->profile_photo_url);
    }
    $firstLetter = mb_strtoupper(mb_substr($authUser->name ?? 'U', 0, 1));

    $sidebarLinks = [
        [
            'name' => 'ওভারভিউ',
            'route' => route('dashboard'),
            'active' => request()->routeIs('dashboard'),
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>',
        ],
        [
            'name' => 'আমার কোর্স',
            'route' => route('dashboard'),
            'active' => false,
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>',
        ],
        [
            'name' => 'সার্টিফিকেট',
            'route' => route('profile.certificates'),
            'active' => request()->routeIs('profile.certificates'),
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>',
        ],
        [
            'name' => 'পেমেন্ট হিস্টোরি',
            'route' => route('profile.payment-history'),
            'active' => request()->routeIs('profile.payment-history'),
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
        ],
        [
            'name' => 'সাবস্ক্রিপশন প্ল্যান',
            'route' => route('subscriptions.index'),
            'active' => request()->routeIs('subscriptions.index'),
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>',
        ],
        [
            'name' => 'আমার ডিভাইস',
            'route' => route('devices.index'),
            'active' => request()->routeIs('devices.index'),
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>',
        ],
        [
            'name' => 'সাপোর্ট',
            'route' => route('support.index'),
            'active' => request()->routeIs('support.*'),
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>',
        ],
        [
            'name' => 'সেটিংস',
            'route' => route('profile.edit'),
            'active' => request()->routeIs('profile.edit'),
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
        ],
    ];
@endphp

<div class="w-full lg:w-64 shrink-0 space-y-4">
    <!-- User Profile Header Card (Matching design reference) -->
    <div class="bg-surface-default border border-outline rounded-2xl p-4 flex items-center gap-3.5 shadow-elevation-1">
        @if($avatarUrl)
            <img src="{{ $avatarUrl }}" alt="{{ $authUser->name }}" class="w-11 h-11 rounded-full object-cover shrink-0 border-2 border-brand-teal/30" />
        @else
            <div class="w-11 h-11 rounded-full bg-brand-teal text-white flex items-center justify-center text-base font-extrabold shrink-0 uppercase shadow-xs">{{ $firstLetter }}</div>
        @endif
        <div class="min-w-0 flex-1">
            <h4 class="font-bold text-brand-navy text-sm truncate">{{ $authUser->name }}</h4>
            <p class="text-xs text-on-surface-muted">শিক্ষার্থী</p>
        </div>
    </div>

    <!-- Navigation Menu Links (Matching design reference) -->
    <div class="bg-surface-default border border-outline rounded-2xl p-2.5 shadow-elevation-1 space-y-1">
        <nav class="space-y-1">
            @foreach($sidebarLinks as $link)
                <a href="{{ $link['route'] }}"
                   class="flex items-center gap-3.5 px-4 py-3 rounded-xl font-semibold text-sm transition-all duration-150 {{ $link['active'] ? 'bg-brand-blue-light text-brand-teal font-bold' : 'text-on-surface hover:bg-surface-hover hover:text-brand-navy' }}">
                    {!! $link['icon'] !!}
                    <span>{{ $link['name'] }}</span>
                </a>
            @endforeach
        </nav>

        <div class="pt-2 mt-2 border-t border-outline">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex items-center gap-3.5 px-4 py-3 rounded-xl font-semibold text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>{{ __('nav.log_out') }}</span>
                </button>
            </form>
        </div>
    </div>
</div>
