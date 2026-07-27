<section x-data="{ step: '{{ session('otp_sent_to') ? 'verify' : 'request' }}' }">
    <header>
        <h2 class="text-lg font-medium text-neu-heading">
            {{ __('ফোন নাম্বার ভেরিফিকেশন') }}
        </h2>
        <p class="mt-1 text-sm text-neu-muted">
            {{ __('OTP দিয়ে আপনার ফোন নাম্বার ভেরিফাই করুন।') }}
        </p>
    </header>

    @if ($user->phone && $user->phone_verified_at)
        <div class="mt-4 flex items-center gap-2 text-sm text-neu-heading neu-inset-sm rounded-md-md px-4 py-3">
            <span>✓</span>
            <span>{{ $user->phone }} — ভেরিফাইড</span>
        </div>
    @else
        <div class="mt-6 space-y-4">
            <div x-show="step === 'request'">
                <form method="POST" action="{{ route('otp.send') }}" class="flex gap-3">
                    @csrf
                    <x-text-input name="phone" type="text" class="block w-full" placeholder="01XXXXXXXXX" value="{{ $user->phone }}" />
                    <x-primary-button type="submit" onclick="setTimeout(() => { document.dispatchEvent(new CustomEvent('otp-requested')) }, 100)">
                        {{ __('OTP পাঠান') }}
                    </x-primary-button>
                </form>
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            <div x-show="step === 'verify'" x-cloak>
                <form method="POST" action="{{ route('otp.verify') }}" class="flex gap-3">
                    @csrf
                    <input type="hidden" name="phone" value="{{ session('otp_sent_to') }}">
                    <x-text-input name="code" type="text" class="block w-full" placeholder="৬ ডিজিট OTP কোড" />
                    <x-primary-button type="submit">{{ __('ভেরিফাই করুন') }}</x-primary-button>
                </form>
                <x-input-error class="mt-2" :messages="$errors->get('code')" />
            </div>

            @if (session('status'))
                <p class="text-sm text-neu-text">{{ session('status') }}</p>
            @endif
            @if (session('error'))
                <p class="text-sm text-neu-heading">{{ session('error') }}</p>
            @endif
        </div>
    @endif
</section>
