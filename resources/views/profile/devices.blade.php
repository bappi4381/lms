<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-neu-heading leading-tight">📱 আমার ডিভাইস</h2>
    </x-slot>

    <div class="py-10 bg-neu-base">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="neu-inset-sm rounded-md-md text-neu-text text-sm p-4">
                নিরাপত্তার জন্য আপনি সর্বোচ্চ <strong class="text-neu-heading">১টি মোবাইল + ১টি কম্পিউটার/ল্যাপটপ</strong> থেকে একাউন্ট ব্যবহার করতে পারবেন।
                নতুন ডিভাইস থেকে লগইন করতে চাইলে নিচে থেকে পুরনো ডিভাইস রিমুভ করুন।
            </div>

            @if (session('status'))
                <div class="neu-inset-sm rounded-md-md p-3 text-neu-text text-sm font-medium">{{ session('status') }}</div>
            @endif

            @forelse($devices as $device)
                <div class="neu-card p-5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl neu-inset-sm text-neu-heading flex items-center justify-center text-2xl">
                            {{ $device->device_type === 'mobile' ? '📱' : '💻' }}
                        </div>
                        <div>
                            <div class="font-bold text-neu-heading">
                                {{ $device->device_name }}
                                @if($device->device_uuid === $currentDeviceUuid)
                                    <span class="text-xs neu-inset-sm rounded-full px-2 py-0.5 ml-1 text-neu-text">এই ডিভাইস</span>
                                @endif
                            </div>
                            <div class="text-xs text-neu-muted">
                                {{ ucfirst($device->device_type) }} · IP: {{ $device->ip_address }} ·
                                সর্বশেষ একটিভ: {{ $device->last_active_at?->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('devices.destroy', $device) }}" onsubmit="return confirm('এই ডিভাইসটি রিমুভ করতে চান?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-neu-muted hover:text-neu-heading font-semibold underline-offset-2 hover:underline">রিমুভ করুন</button>
                    </form>
                </div>
            @empty
                <div class="neu-inset rounded-md-lg p-10 text-center text-neu-muted">
                    এখনো কোনো ডিভাইস রেজিস্টার করা হয়নি।
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
