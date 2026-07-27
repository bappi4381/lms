<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('Welcome') }} - {{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-neu-base text-neu-text antialiased min-h-screen flex flex-col items-center justify-center p-6 lg:p-10">
        <header class="w-full max-w-4xl mb-8">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="neu-btn px-5 py-2 text-sm font-semibold text-neu-heading">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="neu-btn px-5 py-2 text-sm font-semibold text-neu-text">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="neu-btn-primary md-ripple px-5 py-2 text-sm font-semibold">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <main class="w-full max-w-4xl">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-stretch">
                <div class="neu-panel flex flex-col justify-center">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-neu-heading mb-2">Let's get started</h1>
                    <p class="text-neu-muted mb-6 leading-relaxed">
                        Laravel has an incredibly rich ecosystem. We suggest starting with the following.
                    </p>

                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start gap-3">
                            <span class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full neu-inset-sm text-xs font-bold text-neu-heading">1</span>
                            <span class="text-sm text-neu-text">
                                Read the
                                <a href="https://laravel.com/docs" target="_blank" class="font-semibold text-neu-heading underline underline-offset-2 hover:text-neu-muted">
                                    Documentation
                                </a>
                            </span>
                        </li>
                        <li class="flex items-start gap-3">
                            <span class="mt-1 flex h-6 w-6 shrink-0 items-center justify-center rounded-full neu-inset-sm text-xs font-bold text-neu-heading">2</span>
                            <span class="text-sm text-neu-text">
                                Watch video tutorials at
                                <a href="https://laracasts.com" target="_blank" class="font-semibold text-neu-heading underline underline-offset-2 hover:text-neu-muted">
                                    Laracasts
                                </a>
                            </span>
                        </li>
                    </ul>

                    <div>
                        <a href="https://cloud.laravel.com" target="_blank" class="neu-btn-primary md-ripple inline-flex items-center px-6 py-2.5 text-sm font-bold">
                            Deploy now
                        </a>
                    </div>
                </div>

                <div class="neu-raised-lg rounded-md-lg p-10 flex items-center justify-center min-h-[280px]">
                    <div class="text-center">
                        <x-application-logo class="justify-center mb-4" />
                        <p class="text-sm text-neu-muted max-w-xs mx-auto">
                            {{ config('app.name', 'Laravel') }} — monochrome neumorphic welcome surface
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
