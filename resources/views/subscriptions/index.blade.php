<x-app-layout>
    <div class="min-h-screen bg-[var(--surface-canvas)] py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6 items-start">
                <x-user-sidebar />

                <div class="flex-1 w-full space-y-5">
                    <div class="mb-2">
                        <h1 class="text-lg font-bold text-[var(--brand-navy)]">📦 সাবস্ক্রিপশন প্ল্যান</h1>
                        <p class="text-sm text-[var(--on-surface-muted)] mt-1">একটি প্ল্যান কিনে <strong class="text-[var(--brand-navy)]">সবগুলো কোর্সে</strong> সীমাহীন এক্সেস পান।</p>
                    </div>

                    @if(session('status'))
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-xl px-4 py-3">{{ session('status') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="bg-red-50 border border-red-200 text-red-700 text-sm font-medium rounded-xl px-4 py-3">{{ session('error') }}</div>
                    @endif

                    @if($activeSubscription)
                        <div class="flex items-center gap-3 bg-[var(--surface-default)] border border-[var(--outline)] rounded-2xl px-5 py-3.5 shadow-elevation-1">
                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0 animate-pulse"></div>
                            <p class="text-sm text-[var(--on-surface)]">
                                আপনার <span class="font-bold text-[var(--brand-navy)]">{{ $activeSubscription->plan->name }}</span> সাবস্ক্রিপশন সক্রিয় আছে।
                                মেয়াদ শেষ: <span class="font-semibold">{{ $activeSubscription->ends_at->format('d M, Y') }}</span>
                            </p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @foreach($plans as $plan)
                            <div class="bg-[var(--surface-default)] border border-[var(--outline)] rounded-2xl p-7 flex flex-col shadow-elevation-1 hover:shadow-elevation-2 transition-shadow">
                                <h3 class="text-lg font-bold text-[var(--brand-navy)]">{{ $plan->name }}</h3>
                                <p class="text-sm text-[var(--on-surface-muted)] mt-2 flex-grow">{{ $plan->description }}</p>
                                <div class="my-5">
                                    <span class="text-3xl font-extrabold text-[var(--brand-navy)]">৳{{ number_format($plan->price, 0) }}</span>
                                    <span class="text-[var(--on-surface-muted)] text-sm"> / {{ $plan->interval === 'yearly' ? 'বছর' : 'মাস' }}</span>
                                </div>
                                @auth
                                    <form method="POST" action="{{ route('payment.checkout-subscription', $plan) }}">
                                        @csrf
                                        <button type="submit"
                                                class="w-full py-3 font-bold text-sm text-white rounded-xl transition-colors"
                                                style="background: var(--brand-teal);">
                                            সাবস্ক্রাইব করুন
                                        </button>
                                    </form>
                                @else
                                    <button @click="$dispatch('open-auth-drawer')"
                                            class="w-full py-3 font-bold text-sm text-white rounded-xl transition-colors"
                                            style="background: var(--brand-teal);">
                                        সাবস্ক্রাইব করুন
                                    </button>
                                @endauth
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
