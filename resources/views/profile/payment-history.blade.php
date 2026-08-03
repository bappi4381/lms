<x-app-layout>
    <div class="min-h-screen bg-[var(--surface-canvas)] py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6 items-start">
                <x-user-sidebar />

                <div class="flex-1 w-full space-y-4">
                    <div class="flex items-center justify-between mb-2">
                        <h1 class="text-lg font-bold text-[var(--brand-navy)]">💳 পেমেন্ট হিস্টোরি</h1>
                    </div>

                    @if (session('status'))
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-xl px-4 py-3">{{ session('status') }}</div>
                    @endif

                    <div class="bg-[var(--surface-default)] border border-[var(--outline)] rounded-2xl overflow-hidden shadow-elevation-1">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-[var(--outline)] bg-[var(--surface-muted)]">
                                    <th class="px-5 py-3.5 font-semibold text-[var(--brand-navy)] text-left">আইটেম</th>
                                    <th class="px-5 py-3.5 font-semibold text-[var(--brand-navy)] text-left">পরিমাণ</th>
                                    <th class="px-5 py-3.5 font-semibold text-[var(--brand-navy)] text-left">স্ট্যাটাস</th>
                                    <th class="px-5 py-3.5 font-semibold text-[var(--brand-navy)] text-left">তারিখ</th>
                                    <th class="px-5 py-3.5 font-semibold text-[var(--brand-navy)]"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[var(--outline)]">
                                @forelse($orders as $order)
                                    <tr class="hover:bg-[var(--surface-hover)] transition-colors">
                                        <td class="px-5 py-4 font-medium text-[var(--brand-navy)]">
                                            {{ $order->type === 'subscription' ? $order->subscriptionPlan?->name : $order->course?->title }}
                                            <div class="text-xs text-[var(--on-surface-muted)] font-mono">{{ $order->transaction_id }}</div>
                                        </td>
                                        <td class="px-5 py-4 text-[var(--on-surface)]">৳{{ number_format($order->totalPayable(), 0) }}</td>
                                        <td class="px-5 py-4">
                                            @php
                                                $badgeClass = match($order->payment_status) {
                                                    'paid' => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                                    'pending' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                                    default => 'bg-[var(--surface-muted)] text-[var(--on-surface-muted)] border border-[var(--outline)]',
                                                };
                                                $label = match($order->payment_status) {
                                                    'paid' => 'পেইড',
                                                    'pending' => 'পেন্ডিং',
                                                    'refund_requested' => 'রিফান্ড রিকোয়েস্ট',
                                                    'refunded' => 'রিফান্ড হয়েছে',
                                                    'failed' => 'ব্যর্থ',
                                                    'canceled' => 'বাতিল',
                                                    default => $order->payment_status,
                                                };
                                            @endphp
                                            <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">{{ $label }}</span>
                                        </td>
                                        <td class="px-5 py-4 text-[var(--on-surface-muted)]">{{ $order->created_at->format('d M, Y') }}</td>
                                        <td class="px-5 py-4 text-right">
                                            @if($order->payment_status === 'paid')
                                                <form method="POST" action="{{ route('orders.refund-request', $order) }}" onsubmit="return confirm('রিফান্ড রিকোয়েস্ট করতে চান?')">
                                                    @csrf
                                                    <button type="submit" class="text-xs text-[var(--on-surface-muted)] hover:text-[var(--brand-navy)] font-semibold underline-offset-2 hover:underline">রিফান্ড রিকোয়েস্ট</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-5 py-10 text-center text-[var(--on-surface-muted)]">কোনো পেমেন্ট রেকর্ড নেই।</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
