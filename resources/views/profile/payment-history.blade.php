<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-neu-heading leading-tight">💳 পেমেন্ট হিস্টোরি</h2>
    </x-slot>

    <div class="py-10 bg-neu-base">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 neu-inset-sm rounded-md-md p-3 text-neu-text text-sm font-medium">{{ session('status') }}</div>
            @endif

            <div class="neu-card overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="neu-inset-sm text-neu-muted text-left">
                        <tr>
                            <th class="px-5 py-3 font-semibold text-neu-heading">আইটেম</th>
                            <th class="px-5 py-3 font-semibold text-neu-heading">পরিমাণ</th>
                            <th class="px-5 py-3 font-semibold text-neu-heading">স্ট্যাটাস</th>
                            <th class="px-5 py-3 font-semibold text-neu-heading">তারিখ</th>
                            <th class="px-5 py-3 font-semibold text-neu-heading"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-neu-dark/10">
                        @forelse($orders as $order)
                            <tr>
                                <td class="px-5 py-4 font-medium text-neu-heading">
                                    {{ $order->type === 'subscription' ? $order->subscriptionPlan?->name : $order->course?->title }}
                                    <div class="text-xs text-neu-muted font-mono">{{ $order->transaction_id }}</div>
                                </td>
                                <td class="px-5 py-4 text-neu-text">৳{{ number_format($order->totalPayable(), 0) }}</td>
                                <td class="px-5 py-4">
                                    @php
                                        $badge = match($order->payment_status) {
                                            'paid' => 'neu-inset-sm text-neu-heading',
                                            default => 'neu-pill text-neu-text',
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
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ $badge }}">{{ $label }}</span>
                                </td>
                                <td class="px-5 py-4 text-neu-muted">{{ $order->created_at->format('d M, Y') }}</td>
                                <td class="px-5 py-4 text-right">
                                    @if($order->payment_status === 'paid')
                                        <form method="POST" action="{{ route('orders.refund-request', $order) }}" onsubmit="return confirm('আপনি কি নিশ্চিত রিফান্ড রিকোয়েস্ট করতে চান?')">
                                            @csrf
                                            <button type="submit" class="text-xs text-neu-muted hover:text-neu-heading font-semibold underline-offset-2 hover:underline">রিফান্ড রিকোয়েস্ট</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-neu-muted">কোনো পেমেন্ট রেকর্ড নেই।</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
