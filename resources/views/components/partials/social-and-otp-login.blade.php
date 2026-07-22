<div class="mt-6 space-y-4">
    <div class="relative">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
        <div class="relative flex justify-center"><span class="bg-white px-3 text-xs font-semibold text-gray-400">অথবা</span></div>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <a href="{{ route('social.redirect', 'google') }}" class="flex items-center justify-center gap-2 py-2.5 border border-gray-200 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
            <svg class="w-4 h-4" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
            Google
        </a>
        <a href="{{ route('social.redirect', 'facebook') }}" class="flex items-center justify-center gap-2 py-2.5 border border-gray-200 rounded-md text-sm font-semibold text-gray-700 hover:bg-gray-50 transition-colors">
            <svg class="w-4 h-4" fill="#1877F2" viewBox="0 0 24 24"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15h-2.4v-3h2.4V9.6c0-2.4 1.44-3.72 3.6-3.72 1.03 0 2.1.18 2.1.18v2.88h-1.5c-1.18 0-1.5.72-1.5 1.5V12h2.88l-.42 3h-2.46v6.8c4.56-.93 8-4.96 8-9.8z"/></svg>
            Facebook
        </a>
    </div>

    <div x-data="{ otpStep: 'phone' }" class="pt-2 border-t border-gray-100">
        <p class="text-xs font-bold text-gray-500 mb-2 mt-3">অথবা ফোন নাম্বার দিয়ে OTP-তে লগইন করুন</p>

        <div x-show="otpStep === 'phone'">
            <form method="POST" action="{{ route('otp.send') }}" class="flex gap-2">
                @csrf
                <input type="text" name="phone" required placeholder="01XXXXXXXXX" class="flex-1 rounded-md border-gray-300 text-sm px-3 py-2.5">
                <button type="submit" class="px-4 py-2.5 bg-gray-800 hover:bg-gray-900 text-white text-xs font-bold rounded-md transition-colors">OTP পাঠান</button>
            </form>
        </div>

        @if(session('otp_sent_to'))
            <form method="POST" action="{{ route('otp.verify') }}" class="flex gap-2 mt-3">
                @csrf
                <input type="hidden" name="phone" value="{{ session('otp_sent_to') }}">
                <input type="text" name="code" required placeholder="৬ ডিজিট OTP কোড" class="flex-1 rounded-md border-gray-300 text-sm px-3 py-2.5">
                <button type="submit" class="px-4 py-2.5 bg-ostad-yellow hover:bg-ostad-yellow-hover text-ostad-black text-xs font-bold rounded-md transition-colors">ভেরিফাই</button>
            </form>
        @endif
    </div>
</div>
