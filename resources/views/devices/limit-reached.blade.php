<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-neu-heading leading-tight">🚫 ডিভাইস লিমিট শেষ</h2>
    </x-slot>

    <div class="py-10 bg-neu-base">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="neu-inset-sm rounded-md-md text-neu-text text-sm p-4">
                নিরাপত্তার জন্য আপনি সর্বোচ্চ <strong class="text-neu-heading">১টি মোবাইল + ১টি কম্পিউটার</strong> থেকে একাউন্ট ব্যবহার করতে পারবেন।
                এই ডিভাইসটি থেকে এক্সেস দেওয়ার জন্য নিচে থেকে একটি পুরনো ডিভাইস রিমুভ করুন।
            </div>

            @foreach($devices as $device)
                <div class="neu-card p-5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl neu-inset-sm text-neu-heading flex items-center justify-center text-2xl">
                            {{ $device->device_type === 'mobile' ? '📱' : '💻' }}
                        </div>
                        <div>
                            <div class="font-bold text-neu-heading">{{ $device->device_name }}</div>
                            <div class="text-xs text-neu-muted">
                                {{ ucfirst($device->device_type) }} · সর্বশেষ একটিভ: {{ $device->last_active_at?->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('devices.destroy', $device) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="neu-btn md-ripple text-sm text-neu-heading px-4 py-2 rounded-md-md font-semibold neu-inset-sm">রিমুভ করুন</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
