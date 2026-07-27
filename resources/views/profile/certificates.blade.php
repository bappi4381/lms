<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-neu-heading leading-tight">🏆 আমার সার্টিফিকেট</h2>
    </x-slot>

    <div class="py-10 bg-neu-base">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @forelse($certificates as $certificate)
                <div class="neu-card p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-neu-heading">{{ $certificate->course->title }}</h3>
                        <p class="text-sm text-neu-muted mt-1">
                            সার্টিফিকেট নং: <span class="font-mono text-neu-text">{{ $certificate->certificate_number }}</span>
                            · ইস্যু: {{ $certificate->issued_at->format('d M, Y') }}
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('certificates.download', $certificate) }}" class="neu-btn-primary md-ripple px-5 py-2.5 text-sm font-bold">
                            ডাউনলোড করুন
                        </a>
                        <a href="{{ route('certificates.verify', $certificate->certificate_number) }}" target="_blank" class="neu-btn px-5 py-2.5 text-neu-text text-sm font-bold rounded-md-md">
                            ভেরিফাই
                        </a>
                    </div>
                </div>
            @empty
                <div class="neu-inset rounded-md-lg p-12 text-center">
                    <div class="text-5xl mb-4">🏆</div>
                    <h4 class="text-lg font-bold text-neu-heading mb-2">এখনো কোনো সার্টিফিকেট নেই।</h4>
                    <p class="text-neu-muted">কোর্সের সবগুলো লেসন সম্পন্ন করলে এখানে সার্টিফিকেট পাবেন।</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
