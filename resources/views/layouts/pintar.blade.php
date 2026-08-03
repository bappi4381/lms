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
    <header class="pintar-home-header" x-data="{ menuOpen: false }">
        <div class="pintar-home-container pintar-home-nav-wrap">
            <a href="{{ route('courses.index') }}" class="pintar-home-logo">
                <span class="pintar-home-logo-mark" aria-hidden="true"></span>
                {{ config('app.name', 'SecondShiftBD') }}
            </a>

            <nav class="pintar-home-primary-nav" :class="menuOpen ? 'pintar-home-primary-nav--open' : ''">
                <a href="{{ route('courses.index') }}" class="active">Home</a>
                <a href="#about" @click="menuOpen = false">About</a>
                <a href="#courses" @click="menuOpen = false">Course</a>
                <a href="#pricing" @click="menuOpen = false">Pricing</a>
                <a href="#testimonials" @click="menuOpen = false">Teacher</a>
                <a href="#contact" @click="menuOpen = false">Contact</a>
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
                        class="pintar-home-btn pintar-home-btn-primary">Get Started</button>
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
                <div class="pintar-home-foot-brand">
                    <div class="pintar-home-foot-logo">
                        <span class="pintar-home-logo-mark" aria-hidden="true"></span>
                        {{ config('app.name', 'SecondShiftBD') }}
                    </div>
                    <p>বাংলাদেশের শিক্ষার্থীদের জন্য মানসম্মত অনলাইন শিক্ষা — একাডেমিক থেকে প্রফেশনাল, সব এক
                        প্ল্যাটফর্মে।</p>
                    <div class="pintar-home-social-row">
                        <a class="pintar-home-social-icon" href="#" aria-label="Facebook">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M14 9h3V6h-3c-1.7 0-3 1.3-3 3v2H9v3h2v7h3v-7h2.5l.5-3H14V9z" fill="#fff" />
                            </svg>
                        </a>
                        <a class="pintar-home-social-icon" href="#" aria-label="Twitter">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path
                                    d="M21 5.5c-.7.3-1.4.5-2.2.6.8-.5 1.4-1.2 1.7-2.1-.7.5-1.6.8-2.4 1a3.8 3.8 0 00-6.5 3.5A10.8 10.8 0 014 4.9a3.8 3.8 0 001.2 5.1c-.6 0-1.2-.2-1.7-.5v.1c0 1.8 1.3 3.4 3 3.7-.5.2-1.1.2-1.7.1.5 1.5 1.9 2.6 3.6 2.6A7.7 7.7 0 013 17.5a10.9 10.9 0 005.9 1.7c7 0 10.9-5.9 10.9-11v-.5c.7-.5 1.4-1.2 1.9-2z"
                                    fill="#fff" />
                            </svg>
                        </a>
                        <a class="pintar-home-social-icon" href="#" aria-label="Youtube">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <rect x="3" y="6" width="18" height="12" rx="3" stroke="#fff" stroke-width="1.6" />
                                <path d="M11 9.5l4 2.5-4 2.5v-5z" fill="#fff" />
                            </svg>
                        </a>
                        <a class="pintar-home-social-icon" href="#" aria-label="Instagram">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <rect x="3.5" y="3.5" width="17" height="17" rx="5" stroke="#fff" stroke-width="1.6" />
                                <circle cx="12" cy="12" r="3.6" stroke="#fff" stroke-width="1.6" />
                                <circle cx="17.2" cy="6.8" r="1" fill="#fff" />
                            </svg>
                        </a>
                    </div>
                </div>

                <div class="pintar-home-foot-col">
                    <h4>Quick Link</h4>
                    <ul>
                        <li><a href="#about">About</a></li>
                        <li><a href="{{ route('courses.list') }}">Service</a></li>
                        <li><a href="#pricing">Pricing</a></li>
                        <li><a href="{{ route('courses.list', ['blog' => 1]) }}">Blog</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>

                <div class="pintar-home-foot-col">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Term &amp; Condition</a></li>
                        <li><a href="#">Disclaimer</a></li>
                        <li><a href="{{ route('support.index') }}">FAQ</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>

                <div class="pintar-home-foot-col pintar-home-foot-contact">
                    <h4>Contact</h4>
                    <ul>
                        <li>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M4 5h16v14H4z" stroke="#fff" stroke-width="1.5" opacity=".7" />
                                <path d="M4 6l8 6 8-6" stroke="#fff" stroke-width="1.5" opacity=".7" />
                            </svg>
                            support@secondshiftbd.com
                        </li>
                        <li>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M6 3l3 5-2 2c1 2 3 4 5 5l2-2 5 3v3c0 1-1 2-2 2C9 21 3 15 3 7c0-1 1-2 2-2h1z"
                                    stroke="#fff" stroke-width="1.5" opacity=".7" />
                            </svg>
                            +(880) 1234 567890
                        </li>
                        <li>
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 21s7-6.5 7-11.5A7 7 0 105 9.5C5 14.5 12 21 12 21z" stroke="#fff"
                                    stroke-width="1.5" opacity=".7" />
                                <circle cx="12" cy="9.5" r="2.4" stroke="#fff" stroke-width="1.5" opacity=".7" />
                            </svg>
                            Dhaka, Bangladesh
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pintar-home-foot-bottom">
                <span>&copy; {{ date('Y') }} {{ config('app.name', 'SecondShiftBD') }}. All rights reserved.</span>
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