<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">🚫 ডিভাইস লিমিট শেষ</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl p-4">
                নিরাপত্তার জন্য আপনি সর্বোচ্চ <strong>১টি মোবাইল + ১টি কম্পিউটার</strong> থেকে একাউন্ট ব্যবহার করতে পারবেন।
                এই ডিভাইসটি থেকে এক্সেস দেওয়ার জন্য নিচে থেকে একটি পুরনো ডিভাইস রিমুভ করুন।
            </div>

            @foreach($devices as $device)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-2xl">
                            {{ $device->device_type === 'mobile' ? '📱' : '💻' }}
                        </div>
                        <div>
                            <div class="font-bold text-gray-900">{{ $device->device_name }}</div>
                            <div class="text-xs text-gray-400">
                                {{ ucfirst($device->device_type) }} · সর্বশেষ একটিভ: {{ $device->last_active_at?->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('devices.destroy', $device) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-semibold">রিমুভ করুন</button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
