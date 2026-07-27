<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-neu-heading leading-tight">📦 সাবস্ক্রিপশন প্ল্যান</h2>
    </x-slot>

    <div class="py-10 bg-neu-base">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <p class="text-neu-text">একটি সাবস্ক্রিপশন প্ল্যান কিনে আমাদের <strong class="text-neu-heading">সবগুলো কোর্সে</strong> সীমাহীন এক্সেস পান।</p>

            @if(session('status'))
                <div class="neu-inset-sm rounded-md-md p-3 text-neu-text text-sm font-medium">{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="neu-inset rounded-md-md p-3 text-neu-heading text-sm font-medium">{{ session('error') }}</div>
            @endif

            @if($activeSubscription)
                <div class="neu-inset-sm rounded-md-lg p-5 text-neu-text">
                    আপনার <strong class="text-neu-heading">{{ $activeSubscription->plan->name }}</strong> সাবস্ক্রিপশন সক্রিয় আছে,
                    মেয়াদ শেষ হবে {{ $activeSubscription->ends_at->format('d M, Y') }} তারিখে।
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($plans as $plan)
                    <div class="neu-card p-8 flex flex-col">
                        <h3 class="text-xl font-bold text-neu-heading">{{ $plan->name }}</h3>
                        <p class="text-neu-muted text-sm mt-2 flex-grow">{{ $plan->description }}</p>
                        <div class="my-6">
                            <span class="text-3xl font-extrabold text-neu-heading">৳{{ number_format($plan->price, 0) }}</span>
                            <span class="text-neu-muted text-sm">/ {{ $plan->interval === 'yearly' ? 'বছর' : 'মাস' }}</span>
                        </div>
                        @auth
                            <form method="POST" action="{{ route('payment.checkout-subscription', $plan) }}">
                                @csrf
                                <button type="submit" class="neu-btn-primary md-ripple w-full py-3 font-bold">
                                    সাবস্ক্রাইব করুন
                                </button>
                            </form>
                        @else
                            <button @click="$dispatch('open-auth-drawer')" class="neu-btn-primary md-ripple w-full py-3 font-bold">
                                সাবস্ক্রাইব করুন
                            </button>
                        @endauth
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
