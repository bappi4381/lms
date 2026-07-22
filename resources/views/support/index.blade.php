<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">🎧 সাপোর্ট টিকেট</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if(session('status'))
                <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-lg">{{ session('status') }}</div>
            @endif

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4">নতুন টিকেট তৈরি করুন</h3>
                <form method="POST" action="{{ route('support.store') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">সাবজেক্ট</label>
                            <input type="text" name="subject" required class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm" placeholder="সংক্ষেপে সমস্যা লিখুন">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1">ক্যাটাগরি</label>
                            <select name="category" class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm">
                                <option value="technical">টেকনিকাল</option>
                                <option value="payment">পেমেন্ট</option>
                                <option value="course">কোর্স</option>
                                <option value="other">অন্যান্য</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">বিস্তারিত</label>
                        <textarea name="message" rows="4" required class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm" placeholder="আপনার সমস্যাটি বিস্তারিত লিখুন..."></textarea>
                    </div>
                    <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-colors">
                        টিকেট জমা দিন
                    </button>
                </form>
            </div>

            <div class="space-y-3">
                <h3 class="text-lg font-bold text-gray-900">আমার টিকেটসমূহ</h3>
                @forelse($tickets as $ticket)
                    <a href="{{ route('support.show', $ticket) }}" class="block bg-white rounded-xl border border-gray-200 p-4 hover:border-indigo-300 transition-colors">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="font-bold text-gray-800">{{ $ticket->subject }}</div>
                                <div class="text-xs text-gray-400 mt-1">{{ $ticket->created_at->diffForHumans() }}</div>
                            </div>
                            <span class="text-xs font-bold px-3 py-1 rounded-full
                                {{ match($ticket->status) {
                                    'open' => 'bg-red-100 text-red-700',
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'resolved' => 'bg-emerald-100 text-emerald-700',
                                    default => 'bg-gray-100 text-gray-600',
                                } }}">
                                {{ ucfirst($ticket->status) }}
                            </span>
                        </div>
                    </a>
                @empty
                    <div class="text-center text-gray-400 py-8">এখনো কোনো টিকেট তৈরি করা হয়নি।</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
