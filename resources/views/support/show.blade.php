<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">{{ $ticket->subject }}</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if(session('status'))
                <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-lg">{{ session('status') }}</div>
            @endif

            <div class="flex items-center gap-2">
                <span class="text-xs font-bold px-3 py-1 rounded-full bg-gray-100 text-gray-600">{{ ucfirst($ticket->category) }}</span>
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

            <div class="space-y-3">
                @foreach($ticket->replies as $reply)
                    <div class="rounded-2xl p-4 {{ $reply->is_staff_reply ? 'bg-indigo-50 border border-indigo-200 ml-6' : 'bg-white border border-gray-200' }}">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-bold text-sm text-gray-800">{{ $reply->is_staff_reply ? 'সাপোর্ট টিম' : $reply->user->name }}</span>
                            <span class="text-xs text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $reply->message }}</p>
                    </div>
                @endforeach
            </div>

            <form method="POST" action="{{ route('support.reply', $ticket) }}" class="bg-white rounded-2xl border border-gray-200 p-4 space-y-3">
                @csrf
                <textarea name="message" rows="3" required class="w-full rounded-lg border-gray-300 px-4 py-2.5 text-sm" placeholder="রিপ্লাই লিখুন..."></textarea>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-colors">
                    রিপ্লাই পাঠান
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
