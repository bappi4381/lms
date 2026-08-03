<x-app-layout>
    <div class="min-h-screen bg-[var(--surface-canvas)] py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6 items-start">
                <x-user-sidebar />

                <div class="flex-1 w-full space-y-5">
                    <div class="mb-2">
                        <h1 class="text-lg font-bold text-[var(--brand-navy)]">🎧 সাপোর্ট টিকেট</h1>
                    </div>

                    @if(session('status'))
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-xl px-4 py-3">{{ session('status') }}</div>
                    @endif

                    {{-- New Ticket Form --}}
                    <div class="bg-[var(--surface-default)] border border-[var(--outline)] rounded-2xl p-6 shadow-elevation-1">
                        <h3 class="text-base font-bold text-[var(--brand-navy)] mb-4">নতুন টিকেট তৈরি করুন</h3>
                        <form method="POST" action="{{ route('support.store') }}" class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-bold text-[var(--brand-navy)] mb-1">সাবজেক্ট</label>
                                    <input type="text" name="subject" required
                                           class="w-full px-4 py-2.5 rounded-xl border border-[var(--outline)] bg-[var(--surface-default)] text-[var(--on-surface)] text-sm focus:outline-none focus:border-[var(--brand-teal)] transition-colors"
                                           placeholder="সংক্ষেপে সমস্যা লিখুন">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-[var(--brand-navy)] mb-1">ক্যাটাগরি</label>
                                    <select name="category"
                                            class="w-full px-4 py-2.5 rounded-xl border border-[var(--outline)] bg-[var(--surface-default)] text-[var(--on-surface)] text-sm focus:outline-none focus:border-[var(--brand-teal)] transition-colors">
                                        <option value="technical">টেকনিকাল</option>
                                        <option value="payment">পেমেন্ট</option>
                                        <option value="course">কোর্স</option>
                                        <option value="other">অন্যান্য</option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-[var(--brand-navy)] mb-1">বিস্তারিত</label>
                                <textarea name="message" rows="4" required
                                          class="w-full px-4 py-2.5 rounded-xl border border-[var(--outline)] bg-[var(--surface-default)] text-[var(--on-surface)] text-sm focus:outline-none focus:border-[var(--brand-teal)] transition-colors resize-none"
                                          placeholder="আপনার সমস্যাটি বিস্তারিত লিখুন..."></textarea>
                            </div>
                            <button type="submit"
                                    class="inline-flex items-center gap-2 font-bold text-sm text-white px-6 py-2.5 rounded-xl transition-colors"
                                    style="background: var(--brand-teal);">
                                টিকেট জমা দিন
                            </button>
                        </form>
                    </div>

                    {{-- Tickets List --}}
                    <div class="space-y-3">
                        <h3 class="text-base font-bold text-[var(--brand-navy)]">আমার টিকেটসমূহ</h3>
                        @forelse($tickets as $ticket)
                            <a href="{{ route('support.show', $ticket) }}"
                               class="block bg-[var(--surface-default)] border border-[var(--outline)] rounded-2xl p-4 hover:shadow-elevation-2 transition-all shadow-elevation-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-bold text-sm text-[var(--brand-navy)]">{{ $ticket->subject }}</div>
                                        <div class="text-xs text-[var(--on-surface-muted)] mt-1">{{ $ticket->created_at->diffForHumans() }}</div>
                                    </div>
                                    <span class="text-xs font-bold px-3 py-1 rounded-full bg-[var(--surface-muted)] text-[var(--on-surface-muted)] border border-[var(--outline)]">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </div>
                            </a>
                        @empty
                            <div class="bg-[var(--surface-default)] border border-[var(--outline)] rounded-2xl text-center py-8 text-sm text-[var(--on-surface-muted)] shadow-elevation-1">
                                এখনো কোনো টিকেট তৈরি করা হয়নি।
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
