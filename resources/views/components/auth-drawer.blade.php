<div 
    x-data="{ 
        open: {{ $errors->any() ? 'true' : 'false' }}, 
        tab: '{{ old('name') || $errors->has('name') ? 'register' : 'login' }}',
        showConfirmClose: false,
        close() {
            if (this.showConfirmClose) return;
            this.showConfirmClose = true;
        },
        forceClose() {
            this.open = false;
            this.showConfirmClose = false;
            setTimeout(() => { this.tab = 'login'; }, 300);
        },
        cancelClose() {
            this.showConfirmClose = false;
        }
    }"
    x-on:open-auth-drawer.window="open = true"
    x-show="open"
    class="relative z-[100]" 
    aria-labelledby="slide-over-title" 
    role="dialog" 
    aria-modal="true"
    style="display: none;"
>
    <!-- Background backdrop -->
    <div 
        x-show="open"
        x-transition:enter="ease-in-out duration-500"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in-out duration-500"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900 bg-opacity-50 transition-opacity"
        @click="close()"
    ></div>

    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
                
                <!-- Slide-over panel -->
                <div 
                    x-show="open"
                    x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full"
                    class="pointer-events-auto relative w-screen max-w-md"
                >
                    
                    <!-- Close button -->
                    <div class="absolute top-0 right-0 flex pt-4 pr-4 sm:pr-6 z-10">
                        <button type="button" @click="close()" class="rounded-full bg-gray-100 p-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-ostad-yellow focus:ring-offset-2 transition-colors">
                            <span class="sr-only">Close panel</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex h-full flex-col overflow-y-scroll bg-white py-6 shadow-xl relative">
                        <div class="px-4 sm:px-6 pt-10 text-center">
                            <!-- Icon / Animation Placeholder -->
                            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-yellow-50 mb-4">
                                <svg class="h-10 w-10 text-ostad-yellow" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.182 15.182a4.5 4.5 0 01-6.364 0M21 12a9 9 0 11-18 0 9 9 0 0118 0zM9.75 9.75c0 .414-.168.75-.375.75S9 10.164 9 9.75 9.168 9 9.375 9s.375.336.375.75zm3.625 0c0 .414-.168.75-.375.75s-.375-.336-.375-.75.168-.75.375-.75.375.336.375.75z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-extrabold text-ostad-black" id="slide-over-title">ওস্তাদ এর সাথে শেখা শুরু করি</h2>
                        </div>
                        
                        <div class="relative mt-6 flex-1 px-4 sm:px-6">
                            
                            <!-- Tabs -->
                            <div class="flex p-1 bg-gray-100 rounded-lg mb-8">
                                <button @click="tab = 'login'" :class="{ 'bg-white shadow': tab === 'login', 'text-gray-500 hover:text-gray-700': tab !== 'login' }" class="flex-1 py-2 text-sm font-bold text-center rounded-md transition-all">
                                    লগিন
                                </button>
                                <button @click="tab = 'register'" :class="{ 'bg-white shadow': tab === 'register', 'text-gray-500 hover:text-gray-700': tab !== 'register' }" class="flex-1 py-2 text-sm font-bold text-center rounded-md transition-all">
                                    নতুন একাউন্ট
                                </button>
                            </div>

                            <!-- Login Form -->
                            <div x-show="tab === 'login'" x-transition.opacity.duration.300ms>
                                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                                    @csrf
                                    <div>
                                        <label for="login-email" class="block text-sm font-bold text-ostad-black mb-1">ফোন নাম্বার বা ইমেইল দিন</label>
                                        <input type="email" name="email" id="login-email" value="{{ old('email') }}" required autofocus autocomplete="username" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-ostad-yellow focus:ring-ostad-yellow sm:text-sm px-4 py-3 bg-gray-50 placeholder-gray-400" placeholder="ফোন নাম্বার বা ইমেইল দিন">
                                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="login-password" class="block text-sm font-bold text-ostad-black mb-1">পাসওয়ার্ড</label>
                                        <input type="password" name="password" id="login-password" required autocomplete="current-password" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-ostad-yellow focus:ring-ostad-yellow sm:text-sm px-4 py-3 bg-gray-50 placeholder-gray-400" placeholder="পাসওয়ার্ড দিন">
                                        @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="flex items-center justify-between mt-2 mb-6">
                                        <div class="flex items-center">
                                            <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-ostad-yellow focus:ring-ostad-yellow">
                                            <label for="remember_me" class="ml-2 block text-sm text-gray-600">মনে রাখুন</label>
                                        </div>
                                        @if (Route::has('password.request'))
                                            <div class="text-sm">
                                                <a href="{{ route('password.request') }}" class="font-medium text-gray-500 hover:text-ostad-black transition-colors">পাসওয়ার্ড ভুলে গেছেন?</a>
                                            </div>
                                        @endif
                                    </div>

                                    <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-ostad-black bg-ostad-yellow hover:bg-ostad-yellow-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-ostad-yellow transition-colors">
                                        এগিয়ে যাই 
                                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </button>
                                </form>

                                @include('components.partials.social-and-otp-login')
                            </div>

                            <!-- Register Form -->
                            <div x-show="tab === 'register'" x-transition.opacity.duration.300ms style="display: none;">
                                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                                    @csrf
                                    <div>
                                        <label for="name" class="block text-sm font-bold text-ostad-black mb-1">আপনার নাম</label>
                                        <input type="text" name="name" id="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-ostad-yellow focus:ring-ostad-yellow sm:text-sm px-4 py-3 bg-gray-50 placeholder-gray-400" placeholder="আপনার নাম লিখুন">
                                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="register-email" class="block text-sm font-bold text-ostad-black mb-1">ইমেইল</label>
                                        <input type="email" name="email" id="register-email" value="{{ old('email') }}" required autocomplete="username" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-ostad-yellow focus:ring-ostad-yellow sm:text-sm px-4 py-3 bg-gray-50 placeholder-gray-400" placeholder="ইমেইল দিন">
                                        @error('email') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label for="register-password" class="block text-sm font-bold text-ostad-black mb-1">পাসওয়ার্ড</label>
                                        <input type="password" name="password" id="register-password" required autocomplete="new-password" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-ostad-yellow focus:ring-ostad-yellow sm:text-sm px-4 py-3 bg-gray-50 placeholder-gray-400" placeholder="পাসওয়ার্ড দিন">
                                        @error('password') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-bold text-ostad-black mb-1">পাসওয়ার্ড কনফার্ম করুন</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-ostad-yellow focus:ring-ostad-yellow sm:text-sm px-4 py-3 bg-gray-50 placeholder-gray-400" placeholder="পুনরায় পাসওয়ার্ড দিন">
                                    </div>

                                    <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-ostad-black bg-ostad-yellow hover:bg-ostad-yellow-hover focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-ostad-yellow transition-colors mt-6">
                                        এগিয়ে যাই 
                                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>

                    <!-- Confirm Close Overlay within the panel -->
                    <div 
                        x-show="showConfirmClose" 
                        x-transition.opacity 
                        class="absolute inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 backdrop-blur-sm"
                        style="display: none;"
                    >
                        <div class="bg-white rounded-xl shadow-2xl p-6 m-4 max-w-sm w-full text-center transform transition-all">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100 mb-4">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-ostad-black mb-2">আপনি কি লগইন/রেজিস্ট্রার প্রসেস থেকে বের হতে চাচ্ছেন?</h3>
                            
                            <div class="mt-6 flex gap-3">
                                <button type="button" @click="forceClose()" class="flex-1 inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-bold text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-ostad-yellow focus:ring-offset-2 sm:text-sm">
                                    হ্যাঁ, বের হবো
                                </button>
                                <button type="button" @click="cancelClose()" class="flex-1 inline-flex justify-center rounded-md border border-transparent bg-ostad-yellow px-4 py-2 text-base font-bold text-ostad-black shadow-sm hover:bg-ostad-yellow-hover focus:outline-none focus:ring-2 focus:ring-ostad-yellow focus:ring-offset-2 sm:text-sm">
                                    বের হবো না
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
