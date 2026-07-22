<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">💳 পেমেন্ট হিস্টোরি</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-lg">{{ session('status') }}</div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-600 text-left">
                        <tr>
                            <th class="px-5 py-3 font-semibold">আইটেম</th>
                            <th class="px-5 py-3 font-semibold">পরিমাণ</th>
                            <th class="px-5 py-3 font-semibold">স্ট্যাটাস</th>
                            <th class="px-5 py-3 font-semibold">তারিখ</th>
                            <th class="px-5 py-3 font-semibold"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($orders as $order)
                            <tr>
                                <td class="px-5 py-4 font-medium text-gray-800">
                                    {{ $order->type === 'subscription' ? $order->subscriptionPlan?->name : $order->course?->title }}
                                    <div class="text-xs text-gray-400 font-mono">{{ $order->transaction_id }}</div>
                                </td>
                                <td class="px-5 py-4">৳{{ number_format($order->totalPayable(), 0) }}</td>
                                <td class="px-5 py-4">
                                    @php
                                        $badge = match($order->payment_status) {
                                            'paid' => 'bg-emerald-100 text-emerald-700',
                                            'pending' => 'bg-amber-100 text-amber-700',
                                            'refund_requested' => 'bg-amber-100 text-amber-700',
                                            'refunded' => 'bg-gray-100 text-gray-600',
                                            default => 'bg-red-100 text-red-700',
                                        };
                                        $label = match($order->payment_status) {
                                            'paid' => 'পেইড',
                                            'pending' => 'পেন্ডিং',
                                            'refund_requested' => 'রিফান্ড রিকোয়েস্ট করা হয়েছে',
                                            'refunded' => 'রিফান্ড হয়েছে',
                                            'failed' => 'ব্যর্থ',
                                            'canceled' => 'বাতিল',
                                            default => $order->payment_status,
                                        };
                                    @endphp
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $badge }}">{{ $label }}</span>
                                </td>
                                <td class="px-5 py-4 text-gray-500">{{ $order->created_at->format('d M, Y') }}</td>
                                <td class="px-5 py-4 text-right">
                                    @if($order->payment_status === 'paid')
                                        <form method="POST" action="{{ route('orders.refund-request', $order) }}" onsubmit="return confirm('আপনি কি নিশ্চিত রিফান্ড রিকোয়েস্ট করতে চান?')">
                                            @csrf
                                            <button type="submit" class="text-xs text-red-600 hover:underline font-semibold">রিফান্ড রিকোয়েস্ট</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-gray-400">কোনো পেমেন্ট রেকর্ড নেই।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
