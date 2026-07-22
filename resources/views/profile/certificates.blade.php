<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">🏆 আমার সার্টিফিকেট</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @forelse($certificates as $certificate)
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $certificate->course->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">
                            সার্টিফিকেট নং: <span class="font-mono">{{ $certificate->certificate_number }}</span>
                            · ইস্যু: {{ $certificate->issued_at->format('d M, Y') }}
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('certificates.download', $certificate) }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-colors">
                            ডাউনলোড করুন
                        </a>
                        <a href="{{ route('certificates.verify', $certificate->certificate_number) }}" target="_blank" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-bold rounded-xl transition-colors">
                            ভেরিফাই
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white border border-dashed border-gray-300 rounded-2xl p-12 text-center">
                    <div class="text-5xl mb-4">🏆</div>
                    <h4 class="text-lg font-bold text-gray-800 mb-2">এখনো কোনো সার্টিফিকেট নেই।</h4>
                    <p class="text-gray-500">কোর্সের সবগুলো লেসন সম্পন্ন করলে এখানে সার্টিফিকেট পাবেন।</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
