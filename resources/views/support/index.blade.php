<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-neu-heading leading-tight">🎧 সাপোর্ট টিকেট</h2>
    </x-slot>

    <div class="py-10 bg-neu-base">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('status'))
                <div class="neu-inset-sm rounded-md-md p-3 text-neu-text text-sm font-medium">{{ session('status') }}</div>
            @endif

            <div class="neu-panel">
                <h3 class="text-lg font-bold text-neu-heading mb-4">নতুন টিকেট তৈরি করুন</h3>
                <form method="POST" action="{{ route('support.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-neu-heading mb-1">সাবজেক্ট</label>
                            <input type="text" name="subject" required class="neu-input px-4 py-2.5 min-h-[44px] text-sm" placeholder="সংক্ষেপে সমস্যা লিখুন">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-neu-heading mb-1">ক্যাটাগরি</label>
                            <select name="category" class="neu-input px-4 py-2.5 min-h-[44px] text-sm">
                                <option value="technical">টেকনিকাল</option>
                                <option value="payment">পেমেন্ট</option>
                                <option value="course">কোর্স</option>
                                <option value="other">অন্যান্য</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-neu-heading mb-1">বিস্তারিত</label>
                        <textarea name="message" rows="4" required class="neu-input px-4 py-2.5 text-sm" placeholder="আপনার সমস্যাটি বিস্তারিত লিখুন..."></textarea>
                    </div>
                    <button type="submit" class="neu-btn-primary md-ripple px-6 py-2.5 text-sm font-bold">
                        টিকেট জমা দিন
                    </button>
                </form>
            </div>

            <div class="space-y-3">
                <h3 class="text-lg font-bold text-neu-heading">আমার টিকেটসমূহ</h3>
                @forelse($tickets as $ticket)
                    <a href="{{ route('support.show', $ticket) }}" class="block neu-card p-4 hover:shadow-neu-raised transition-shadow duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="font-bold text-neu-heading">{{ $ticket->subject }}</div>
                                <div class="text-xs text-neu-muted mt-1">{{ $ticket->created_at->diffForHumans() }}</div>
                            </div>
                            <span class="neu-pill text-xs font-bold text-neu-text">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="text-center text-neu-muted py-8">এখনো কোনো টিকেট তৈরি করা হয়নি।</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
