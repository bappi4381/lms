<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-neu-heading leading-tight">{{ $ticket->subject }}</h2>
    </x-slot>

    <div class="py-10 bg-neu-base">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if(session('status'))
                <div class="neu-inset-sm rounded-md-md p-3 text-neu-text text-sm font-medium">{{ session('status') }}</div>
            @endif

            <div class="flex items-center gap-2">
                <span class="neu-pill text-xs font-bold text-neu-muted">{{ ucfirst($ticket->category) }}</span>
                <span class="neu-inset-sm rounded-full px-3 py-1 text-xs font-bold text-neu-heading">
                    {{ ucfirst($ticket->status) }}
                </span>
            </div>

            <div class="space-y-3">
                @foreach($ticket->replies as $reply)
                    <div class="rounded-md-lg p-4 {{ $reply->is_staff_reply ? 'neu-inset-sm ml-6' : 'neu-card' }}">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-bold text-sm text-neu-heading">{{ $reply->is_staff_reply ? 'সাপোর্ট টিম' : $reply->user->name }}</span>
                            <span class="text-xs text-neu-muted">{{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-sm text-neu-text whitespace-pre-line">{{ $reply->message }}</p>
                    </div>
                @endforeach
            </div>

            <form method="POST" action="{{ route('support.reply', $ticket) }}" class="neu-panel space-y-3">
                @csrf
                <textarea name="message" rows="3" required class="neu-input px-4 py-2.5 text-sm" placeholder="রিপ্লাই লিখুন..."></textarea>
                <button type="submit" class="neu-btn-primary md-ripple px-6 py-2.5 text-sm font-bold">
                    রিপ্লাই পাঠান
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
