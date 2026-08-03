<x-app-layout>
    <div class="min-h-screen bg-[var(--surface-canvas)] py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6 items-start">
                <x-user-sidebar />

                <div class="flex-1 w-full space-y-4">
                    <div class="flex items-center justify-between mb-2">
                        <h1 class="text-lg font-bold text-[var(--brand-navy)]">🏆 আমার সার্টিফিকেট</h1>
                    </div>

                    @forelse($certificates as $certificate)
                        <div class="bg-[var(--surface-default)] border border-[var(--outline)] rounded-2xl p-5 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-elevation-1">
                            <div>
                                <h3 class="text-base font-bold text-[var(--brand-navy)]">{{ $certificate->course->title }}</h3>
                                <p class="text-sm text-[var(--on-surface-muted)] mt-1">
                                    সার্টিফিকেট নং: <span class="font-mono text-[var(--on-surface)]">{{ $certificate->certificate_number }}</span>
                                    · ইস্যু: {{ $certificate->issued_at->format('d M, Y') }}
                                </p>
                            </div>
                            <div class="flex gap-2 shrink-0">
                                <a href="{{ route('certificates.download', $certificate) }}"
                                   class="inline-flex items-center gap-1.5 font-bold text-sm text-white px-4 py-2 rounded-xl"
                                   style="background: var(--brand-teal);">
                                    ডাউনলোড করুন
                                </a>
                                <a href="{{ route('certificates.verify', $certificate->certificate_number) }}" target="_blank"
                                   class="inline-flex items-center gap-1.5 font-semibold text-sm px-4 py-2 rounded-xl border border-[var(--outline)] text-[var(--on-surface)] hover:bg-[var(--surface-hover)] transition-colors">
                                    ভেরিফাই
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="bg-[var(--surface-default)] border border-[var(--outline)] rounded-2xl p-12 text-center shadow-elevation-1">
                            <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center text-4xl" style="background: var(--pastel-sky);">🏆</div>
                            <h4 class="text-base font-bold text-[var(--brand-navy)] mb-2">এখনো কোনো সার্টিফিকেট নেই।</h4>
                            <p class="text-sm text-[var(--on-surface-muted)]">কোর্সের সবগুলো লেসন সম্পন্ন করলে এখানে সার্টিফিকেট পাবেন।</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
