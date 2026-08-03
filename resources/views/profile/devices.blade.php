<x-app-layout>
    <div class="min-h-screen bg-[var(--surface-canvas)] py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6 items-start">
                <x-user-sidebar />

                <div class="flex-1 w-full space-y-4">
                    <div class="mb-2">
                        <h1 class="text-lg font-bold text-[var(--brand-navy)]">📱 আমার ডিভাইস</h1>
                    </div>

                    <div class="bg-[var(--surface-default)] border border-[var(--outline)] rounded-2xl px-5 py-4 shadow-elevation-1 text-sm text-[var(--on-surface)]">
                        নিরাপত্তার জন্য আপনি সর্বোচ্চ <strong class="text-[var(--brand-navy)]">১টি মোবাইল + ১টি কম্পিউটার/ল্যাপটপ</strong> থেকে একাউন্ট ব্যবহার করতে পারবেন।
                        নতুন ডিভাইস থেকে লগইন করতে চাইলে নিচে থেকে পুরনো ডিভাইস রিমুভ করুন।
                    </div>

                    @if (session('status'))
                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-xl px-4 py-3">{{ session('status') }}</div>
                    @endif

                    @forelse($devices as $device)
                        <div class="bg-[var(--surface-default)] border border-[var(--outline)] rounded-2xl p-5 flex items-center justify-between gap-4 shadow-elevation-1">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl" style="background: var(--pastel-mint);">
                                    {{ $device->device_type === 'mobile' ? '📱' : '💻' }}
                                </div>
                                <div>
                                    <div class="font-bold text-[var(--brand-navy)] text-sm">
                                        {{ $device->device_name }}
                                        @if($device->device_uuid === $currentDeviceUuid)
                                            <span class="ml-1.5 text-xs font-bold px-2 py-0.5 rounded-full bg-[var(--brand-blue-light)] text-[var(--brand-teal)]">এই ডিভাইস</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-[var(--on-surface-muted)] mt-0.5">
                                        {{ ucfirst($device->device_type) }} · IP: {{ $device->ip_address }} ·
                                        সর্বশেষ একটিভ: {{ $device->last_active_at?->diffForHumans() }}
                                    </div>
                                </div>
                            </div>
                            <form method="POST" action="{{ route('devices.destroy', $device) }}" onsubmit="return confirm('এই ডিভাইসটি রিমুভ করতে চান?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm text-red-500 hover:text-red-700 font-semibold hover:underline underline-offset-2 transition-colors">রিমুভ</button>
                            </form>
                        </div>
                    @empty
                        <div class="bg-[var(--surface-default)] border border-[var(--outline)] rounded-2xl p-10 text-center text-[var(--on-surface-muted)] shadow-elevation-1">
                            এখনো কোনো ডিভাইস রেজিস্টার করা হয়নি।
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
