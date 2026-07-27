@php
    $allResources = [
        ['type' => 'pdf', 'title' => 'SSC Math Suggestion 2024', 'meta' => 'PDF • 2.0 MB', 'url' => '#'],
        ['type' => 'pdf', 'title' => 'HSC Physics Formula Sheet', 'meta' => 'PDF • 1.4 MB', 'url' => '#'],
        ['type' => 'pdf', 'title' => 'BCS Preliminary Guide', 'meta' => 'PDF • 3.2 MB', 'url' => '#'],
        ['type' => 'pdf', 'title' => 'English Grammar Basics', 'meta' => 'PDF • 1.8 MB', 'url' => '#'],
        ['type' => 'pdf', 'title' => 'ICT Short Suggestion HSC', 'meta' => 'PDF • 2.5 MB', 'url' => '#'],
        ['type' => 'pdf', 'title' => 'Admission Math Archive', 'meta' => 'PDF • 4.1 MB', 'url' => '#'],
        ['type' => 'video', 'title' => 'SSC Math Chapter 1 — Basics', 'meta' => 'Video • 45 min', 'url' => '#'],
        ['type' => 'video', 'title' => 'HSC Chemistry Intro Lecture', 'meta' => 'Video • 38 min', 'url' => '#'],
        ['type' => 'video', 'title' => 'Excel Beginner Tutorial', 'meta' => 'Video • 52 min', 'url' => '#'],
        ['type' => 'video', 'title' => 'IELTS Speaking Tips', 'meta' => 'Video • 28 min', 'url' => '#'],
        ['type' => 'test', 'title' => 'SSC Math Model Test 01', 'meta' => 'Test • 30 questions', 'url' => '#'],
        ['type' => 'test', 'title' => 'HSC Physics MCQ Practice', 'meta' => 'Test • 25 questions', 'url' => '#'],
        ['type' => 'test', 'title' => 'BCS Bangla Mock Exam', 'meta' => 'Test • 50 questions', 'url' => '#'],
        ['type' => 'test', 'title' => 'General Knowledge Quiz', 'meta' => 'Test • 20 questions', 'url' => '#'],
    ];

    $initialTab = in_array(request()->query('tab'), ['pdf', 'video', 'test'], true)
        ? request()->query('tab')
        : 'pdf';

    $tabs = [
        ['id' => 'pdf', 'label' => 'পিডিএফ'],
        ['id' => 'video', 'label' => 'ভিডিও'],
        ['id' => 'test', 'label' => 'টেস্ট'],
    ];
@endphp

<div
    x-data="{
        tab: '{{ $initialTab }}',
        resources: @js($allResources),
        get filtered() {
            return this.resources.filter(r => r.type === this.tab);
        },
        icon(type) {
            if (type === 'video') return 'video';
            if (type === 'test') return 'test';
            return 'pdf';
        }
    }"
    class="min-h-screen"
>
    {{-- Header --}}
    <section class="glass-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-page-header">
            <p class="text-xs sm:text-sm font-bold text-brand-blue mb-2 uppercase tracking-wide">রিসোর্স</p>
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-brand-navy tracking-tight">ফ্রি রিসোর্স</h1>
            <p class="mt-3 text-sm sm:text-base text-gray-500 max-w-2xl leading-relaxed">
                বিনামূল্যে পিডিএফ, ভিডিও লেকচার ও মডেল টেস্ট — যেকোনো সময় ডাউনলোড করে প্রস্তুতি নিন।
            </p>

            {{-- Filter tabs --}}
            <div class="flex flex-wrap gap-2.5 mt-8">
                @foreach($tabs as $tab)
                    <button
                        type="button"
                        @click="tab = '{{ $tab['id'] }}'"
                        :class="tab === '{{ $tab['id'] }}'
                            ? 'glass-pill-active'
                            : 'glass-pill'"
                        class="md-ripple inline-flex items-center min-h-[44px] px-5 sm:px-6 py-2.5 rounded-full text-sm font-bold border transition-colors duration-200 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue focus-visible:ring-offset-2"
                    >
                        {{ $tab['label'] }}
                    </button>
                @endforeach
            </div>
            </div>
        </div>
    </section>

    {{-- Resource grid --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 sm:pb-10">
        <div class="glass-content-area">
        <p class="text-sm font-semibold text-gray-600 mb-6">
            <span class="text-brand-navy font-bold" x-text="filtered.length"></span>টি রিসোর্স পাওয়া গেছে
        </p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 sm:gap-6">
            <template x-for="(item, index) in filtered" :key="tab + '-' + index">
                <article class="flex flex-col glass-card-hover overflow-hidden h-full">
                    <div class="p-5 flex gap-4 flex-1">
                        {{-- Icon --}}
                        <div class="shrink-0 w-12 h-12 glass-icon-box flex items-center justify-center relative">
                            <svg x-show="item.type === 'pdf'" class="w-6 h-6 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M14 3v4a2 2 0 002 2h4"/></svg>
                            <svg x-show="item.type === 'video'" class="w-6 h-6 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <svg x-show="item.type === 'test'" class="w-6 h-6 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>

                        {{-- Info --}}
                        <div class="min-w-0 flex-1">
                            <h2 class="text-sm sm:text-base font-bold text-brand-navy leading-snug line-clamp-2" x-text="item.title"></h2>
                            <p class="text-xs text-gray-400 mt-1.5" x-text="item.meta"></p>
                        </div>
                    </div>

                    {{-- Download / action --}}
                    <div class="px-5 pb-5 pt-0">
                        <a
                            :href="item.url"
                            class="md-ripple flex items-center justify-center gap-2 w-full min-h-[44px] rounded-xl glass-btn-ghost text-sm font-bold focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue focus-visible:ring-offset-2"
                            :aria-label="'ডাউনলোড ' + item.title"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span x-text="item.type === 'test' ? 'টেস্ট শুরু করুন' : (item.type === 'video' ? 'দেখুন' : 'ডাউনলোড')"></span>
                        </a>
                    </div>
                </article>
            </template>
        </div>

        {{-- Empty state --}}
        <div x-show="filtered.length === 0" class="flex flex-col items-center justify-center py-20 glass-empty" style="display: none;">
            <svg class="w-14 h-14 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <p class="text-lg font-bold text-brand-navy">এই ক্যাটাগরিতে কোনো রিসোর্স নেই</p>
            <p class="text-gray-500 mt-1 text-sm">অন্য ট্যাব দেখুন।</p>
        </div>
        </div>
    </section>
</div>
