<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">📱 আমার ডিভাইস</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="bg-indigo-50 border border-indigo-200 text-indigo-800 text-sm rounded-xl p-4">
                নিরাপত্তার জন্য আপনি সর্বোচ্চ <strong>১টি মোবাইল + ১টি কম্পিউটার/ল্যাপটপ</strong> থেকে একাউন্ট ব্যবহার করতে পারবেন।
                নতুন ডিভাইস থেকে লগইন করতে চাইলে নিচে থেকে পুরনো ডিভাইস রিমুভ করুন।
            </div>

            @if (session('status'))
                <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-lg">{{ session('status') }}</div>
            @endif

            @forelse($devices as $device)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl">
                            {{ $device->device_type === 'mobile' ? '📱' : '💻' }}
                        </div>
                        <div>
                            <div class="font-bold text-gray-900">
                                {{ $device->device_name }}
                                @if($device->device_uuid === $currentDeviceUuid)
                                    <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full ml-1">এই ডিভাইস</span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ ucfirst($device->device_type) }} · IP: {{ $device->ip_address }} ·
                                সর্বশেষ একটিভ: {{ $device->last_active_at?->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('devices.destroy', $device) }}" onsubmit="return confirm('এই ডিভাইসটি রিমুভ করতে চান?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-red-600 hover:underline font-semibold">রিমুভ করুন</button>
                    </form>
                </div>
            @empty
                <div class="bg-white border border-dashed border-gray-300 rounded-2xl p-10 text-center text-gray-400">
                    এখনো কোনো ডিভাইস রেজিস্টার করা হয়নি।
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
