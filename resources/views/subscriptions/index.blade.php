<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">📦 সাবস্ক্রিপশন প্ল্যান</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <p class="text-gray-600">একটি সাবস্ক্রিপশন প্ল্যান কিনে আমাদের <strong>সবগুলো কোর্সে</strong> সীমাহীন এক্সেস পান।</p>

            @if(session('status'))
                <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-lg">{{ session('status') }}</div>
            @endif
            @if(session('error'))
                <div class="p-3 bg-red-50 border border-red-200 text-red-600 text-sm font-medium rounded-lg">{{ session('error') }}</div>
            @endif

            @if($activeSubscription)
                <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-5 text-emerald-800">
                    আপনার <strong>{{ $activeSubscription->plan->name }}</strong> সাবস্ক্রিপশন সক্রিয় আছে,
                    মেয়াদ শেষ হবে {{ $activeSubscription->ends_at->format('d M, Y') }} তারিখে।
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($plans as $plan)
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8 flex flex-col">
                        <h3 class="text-xl font-bold text-gray-900">{{ $plan->name }}</h3>
                        <p class="text-gray-500 text-sm mt-2 flex-grow">{{ $plan->description }}</p>
                        <div class="my-6">
                            <span class="text-3xl font-extrabold text-indigo-600">৳{{ number_format($plan->price, 0) }}</span>
                            <span class="text-gray-500 text-sm">/ {{ $plan->interval === 'yearly' ? 'বছর' : 'মাস' }}</span>
                        </div>
                        @auth
                            <form method="POST" action="{{ route('payment.checkout-subscription', $plan) }}">
                                @csrf
                                <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-colors">
                                    সাবস্ক্রাইব করুন
                                </button>
                            </form>
                        @else
                            <button @click="$dispatch('open-auth-drawer')" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-colors">
                                সাবস্ক্রাইব করুন
                            </button>
                        @endauth
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
