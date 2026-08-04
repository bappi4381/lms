<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="pintar-home-page">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SecondShiftBD') }} — Fun E-Course</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Rubik:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        document.documentElement.classList.remove('dark');
    </script>
</head>

<body class="pintar-home-body antialiased">
    @php
        $siteSetting = \App\Models\SiteSetting::getSettings();
        $isBn = app()->getLocale() === 'bn';
    @endphp

    <header class="pintar-home-header" x-data="{ menuOpen: false }">
        <div class="pintar-home-container pintar-home-nav-wrap">
            <a href="{{ route('courses.index') }}" class="pintar-home-logo">
                <span class="pintar-home-logo-mark" aria-hidden="true"></span>
                {{ config('app.name', 'SecondShiftBD') }}
            </a>

            <nav class="pintar-home-primary-nav" :class="menuOpen ? 'pintar-home-primary-nav--open' : ''">
                @if(!empty($siteSetting->header_links) && count($siteSetting->header_links) > 0)
                    @foreach($siteSetting->header_links as $hLink)
                        @if($hLink['is_active'] ?? true)
                            @php
                                $hLabel = $isBn ? ($hLink['label_bn'] ?? $hLink['label_en'] ?? '') : ($hLink['label_en'] ?? $hLink['label_bn'] ?? '');
                                $target = !empty($hLink['open_in_new_tab']) ? '_blank' : '_self';
                            @endphp
                            <a href="{{ $hLink['url'] ?? '#' }}" target="{{ $target }}" @click="menuOpen = false">{{ $hLabel }}</a>
                        @endif
                    @endforeach
                @else
                    <a href="{{ route('courses.index') }}" class="active">{{ $isBn ? 'হোম' : 'Home' }}</a>
                    <a href="#about" @click="menuOpen = false">{{ $isBn ? 'আমাদের সম্পর্কে' : 'About' }}</a>
                    <a href="#courses" @click="menuOpen = false">{{ $isBn ? 'কোর্সসমূহ' : 'Courses' }}</a>
                    <a href="#pricing" @click="menuOpen = false">{{ $isBn ? 'প্রাইসিং' : 'Pricing' }}</a>
                    <a href="#testimonials" @click="menuOpen = false">{{ $isBn ? 'ইনস্ট্রাক্টর' : 'Teacher' }}</a>
                    <a href="#contact" @click="menuOpen = false">{{ $isBn ? 'যোগাযোগ' : 'Contact' }}</a>
                @endif
            </nav>

            <div class="pintar-home-nav-right">
                @auth
                    @php
                        $authUser = Auth::user();
                        $avatarUrl = null;
                        if ($authUser->profile_photo_url) {
                            $avatarUrl = \Illuminate\Support\Str::startsWith($authUser->profile_photo_url, ['http://', 'https://'])
                                ? $authUser->profile_photo_url
                                : asset('storage/' . $authUser->profile_photo_url);
                        }
                        $firstLetter = mb_strtoupper(mb_substr($authUser->name ?? 'U', 0, 1));
                    @endphp
                    <a href="{{ route('dashboard') }}"
                       class="inline-flex items-center p-1 rounded-full glass-icon-btn focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue"
                       aria-label="{{ $authUser->name }}">
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ $authUser->name }}" class="w-8 h-8 rounded-full object-cover shrink-0 border border-gray-200" />
                        @else
                            <div class="w-8 h-8 rounded-full bg-brand-teal text-white flex items-center justify-center text-sm font-bold shrink-0 uppercase shadow-xs">{{ $firstLetter }}</div>
                        @endif
                    </a>
                @else
                    <button type="button" @click="$dispatch('open-auth-drawer')"
                        class="pintar-home-btn pintar-home-btn-primary">{{ $isBn ? 'শুরু করুন' : 'Get Started' }}</button>
                @endauth

                <button type="button" class="pintar-home-menu-toggle" aria-label="Open menu" :aria-expanded="menuOpen"
                    @click="menuOpen = !menuOpen">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </header>

    <main class="pintar-home-main">
        {{ $slot }}
    </main>

    <footer id="contact" class="pintar-home-footer">
        <div class="pintar-home-container">
            <div class="pintar-home-foot-grid">
                {{-- Column 1: Brand Info & Description --}}
                <div class="pintar-home-foot-brand">
                    <div class="pintar-home-foot-logo">
                        <span class="pintar-home-logo-mark" aria-hidden="true"></span>
                        {{ config('app.name', 'SecondShiftBD') }}
                    </div>
                    <p>{{ $siteSetting->brand_description }}</p>
                    
                    {{-- Social Links --}}
                    @if(!empty($siteSetting->social_links))
                        <div class="pintar-home-social-row">
                            @foreach($siteSetting->social_links as $sLink)
                                @if($sLink['is_active'] ?? true)
                                    @php
                                        $platform = strtolower($sLink['platform'] ?? '');
                                        $url = $sLink['url'] ?? '#';
                                    @endphp
                                    <a class="pintar-home-social-icon" href="{{ $url }}" target="_blank" rel="noopener noreferrer" aria-label="{{ ucfirst($platform) }}">
                                        @if($platform === 'facebook')
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M14 9h3V6h-3c-1.7 0-3 1.3-3 3v2H9v3h2v7h3v-7h2.5l.5-3H14V9z" fill="#fff" />
                                            </svg>
                                        @elseif($platform === 'youtube')
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <rect x="3" y="6" width="18" height="12" rx="3" stroke="#fff" stroke-width="1.6" />
                                                <path d="M11 9.5l4 2.5-4 2.5v-5z" fill="#fff" />
                                            </svg>
                                        @elseif($platform === 'instagram')
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <rect x="3.5" y="3.5" width="17" height="17" rx="5" stroke="#fff" stroke-width="1.6" />
                                                <circle cx="12" cy="12" r="3.6" stroke="#fff" stroke-width="1.6" />
                                                <circle cx="17.2" cy="6.8" r="1" fill="#fff" />
                                            </svg>
                                        @elseif($platform === 'linkedin')
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z" fill="#fff" />
                                                <circle cx="4" cy="4" r="2" fill="#fff" />
                                            </svg>
                                        @else
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                                <path d="M21 5.5c-.7.3-1.4.5-2.2.6.8-.5 1.4-1.2 1.7-2.1-.7.5-1.6.8-2.4 1a3.8 3.8 0 00-6.5 3.5A10.8 10.8 0 014 4.9a3.8 3.8 0 001.2 5.1c-.6 0-1.2-.2-1.7-.5v.1c0 1.8 1.3 3.4 3 3.7-.5.2-1.1.2-1.7.1.5 1.5 1.9 2.6 3.6 2.6A7.7 7.7 0 013 17.5a10.9 10.9 0 005.9 1.7c7 0 10.9-5.9 10.9-11v-.5c.7-.5 1.4-1.2 1.9-2z" fill="#fff" />
                                            </svg>
                                        @endif
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Dynamic Footer Columns --}}
                @if(!empty($siteSetting->footer_columns))
                    @foreach($siteSetting->footer_columns as $col)
                        @php
                            $colTitle = $isBn ? ($col['column_title_bn'] ?? $col['column_title_en'] ?? '') : ($col['column_title_en'] ?? $col['column_title_bn'] ?? '');
                        @endphp
                        <div class="pintar-home-foot-col">
                            <h4>{{ $colTitle }}</h4>
                            @if(!empty($col['links']))
                                <ul>
                                    @foreach($col['links'] as $fLink)
                                        @if($fLink['is_active'] ?? true)
                                            @php
                                                $fLabel = $isBn ? ($fLink['label_bn'] ?? $fLink['label_en'] ?? '') : ($fLink['label_en'] ?? $fLink['label_bn'] ?? '');
                                            @endphp
                                            <li><a href="{{ $fLink['url'] ?? '#' }}">{{ $fLabel }}</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    @endforeach
                @endif

                {{-- Column 4: Contact Info --}}
                <div class="pintar-home-foot-col pintar-home-foot-contact">
                    <h4>{{ $isBn ? 'যোগাযোগ' : 'Contact' }}</h4>
                    <ul>
                        @if($siteSetting->contact_email)
                            <li>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M4 5h16v14H4z" stroke="#fff" stroke-width="1.5" opacity=".7" />
                                    <path d="M4 6l8 6 8-6" stroke="#fff" stroke-width="1.5" opacity=".7" />
                                </svg>
                                <a href="mailto:{{ $siteSetting->contact_email }}" class="hover:underline">{{ $siteSetting->contact_email }}</a>
                            </li>
                        @endif
                        @if($siteSetting->phone)
                            <li>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M6 3l3 5-2 2c1 2 3 4 5 5l2-2 5 3v3c0 1-1 2-2 2C9 21 3 15 3 7c0-1 1-2 2-2h1z"
                                        stroke="#fff" stroke-width="1.5" opacity=".7" />
                                </svg>
                                {{ $siteSetting->phone }}
                            </li>
                        @endif
                        @if($siteSetting->address)
                            <li>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M12 21s7-6.5 7-11.5A7 7 0 105 9.5C5 14.5 12 21 12 21z" stroke="#fff"
                                        stroke-width="1.5" opacity=".7" />
                                    <circle cx="12" cy="9.5" r="2.4" stroke="#fff" stroke-width="1.5" opacity=".7" />
                                </svg>
                                {{ $siteSetting->address }}
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="pintar-home-foot-bottom">
                <span>{{ $siteSetting->copyright_text }}</span>
                <span>Made with Laravel, Livewire &amp; Tailwind.</span>
            </div>
        </div>
    </footer>

    @guest
        <x-auth-drawer />
    @endguest

    <x-cart-drawer />
</body>

</html>