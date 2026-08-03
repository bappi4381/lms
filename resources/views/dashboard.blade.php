<x-app-layout>
    <div class="min-h-screen bg-[var(--surface-canvas)] py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row gap-6 items-start">

                {{-- LEFT SIDEBAR --}}
                <x-user-sidebar />

                {{-- MAIN CONTENT --}}
                <div class="flex-1 w-full space-y-6">

                    {{-- Welcome Banner --}}
                    <div class="relative overflow-hidden rounded-2xl p-6 md:p-7"
                         style="background: var(--hero-gradient);">
                        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div>
                                <h1 class="text-2xl md:text-3xl font-extrabold text-white mb-1">
                                    স্বাগতম, {{ Auth::user()->name }}! 👋
                                </h1>
                                <p class="text-white/75 text-sm md:text-base">
                                    আজ শেখার জন্য দারুণ একটি দিন
                                </p>
                            </div>
                            <a href="{{ route('courses.index') }}"
                               class="inline-flex items-center gap-2 bg-white/20 hover:bg-white/30 text-white font-bold px-5 py-2.5 rounded-xl transition-colors text-sm backdrop-blur-sm border border-white/25 shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                নতুন কোর্স
                            </a>
                        </div>
                        {{-- decorative blobs --}}
                        <div class="absolute -right-8 -top-8 w-40 h-40 rounded-full opacity-10 bg-white"></div>
                        <div class="absolute right-16 -bottom-10 w-28 h-28 rounded-full opacity-10 bg-white"></div>
                    </div>

                    {{-- Stats Row --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div class="bg-[var(--surface-default)] border border-[var(--outline)] rounded-2xl p-5 flex flex-col items-start gap-2 shadow-elevation-1">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: var(--pastel-mint);">
                                <svg class="w-5 h-5 text-[var(--brand-teal)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <div class="text-2xl font-extrabold text-[var(--brand-navy)]">{{ $paidEnrollmentsCount }}</div>
                            <div class="text-xs font-medium text-[var(--on-surface-muted)]">চলমান কোর্স</div>
                        </div>
                        <div class="bg-[var(--surface-default)] border border-[var(--outline)] rounded-2xl p-5 flex flex-col items-start gap-2 shadow-elevation-1">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: var(--pastel-sky);">
                                <svg class="w-5 h-5 text-[var(--brand-teal)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                            </div>
                            <div class="text-2xl font-extrabold text-[var(--brand-navy)]">{{ $certificatesCount }}</div>
                            <div class="text-xs font-medium text-[var(--on-surface-muted)]">অর্জিত সার্টিফিকেট</div>
                        </div>
                        <div class="bg-[var(--surface-default)] border border-[var(--outline)] rounded-2xl p-5 flex flex-col items-start gap-2 shadow-elevation-1">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center" style="background: var(--pastel-peach);">
                                <svg class="w-5 h-5 text-[var(--brand-orange)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            @php
                                $totalMinutes = 0;
                                foreach($enrollments->where('payment_status','paid') as $e) {
                                    foreach($e->course->modules ?? [] as $m) {
                                        foreach($m->lessons ?? [] as $l) {
                                            $totalMinutes += ($l->duration_minutes ?? 0);
                                        }
                                    }
                                }
                            @endphp
                            <div class="text-2xl font-extrabold text-[var(--brand-navy)]">{{ $totalMinutes }}</div>
                            <div class="text-xs font-medium text-[var(--on-surface-muted)]">শেখার মিনিট</div>
                        </div>
                    </div>

                    {{-- Active Subscription Badge --}}
                    @if($activeSubscription)
                    <div class="flex items-center gap-3 bg-[var(--surface-default)] border border-[var(--outline)] rounded-2xl px-5 py-3.5 shadow-elevation-1">
                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-500 shrink-0 animate-pulse"></div>
                        <p class="text-sm text-[var(--on-surface)]">
                            আপনার <span class="font-bold text-[var(--brand-navy)]">{{ $activeSubscription->plan->name }}</span> সাবস্ক্রিপশন সক্রিয় আছে।
                            মেয়াদ শেষ: <span class="font-semibold">{{ $activeSubscription->ends_at->format('d M, Y') }}</span>
                        </p>
                    </div>
                    @endif

                    {{-- Enrolled Courses --}}
                    <div>
                        <h2 class="text-lg font-bold text-[var(--brand-navy)] mb-4">চলমান কোর্স</h2>

                        @if($enrollments->count() > 0)
                            <div class="space-y-3">
                                @foreach($enrollments as $enrollment)
                                    @php
                                        $course = $enrollment->course;
                                        $firstLessonId = $course->modules->first()?->lessons->first()?->id ?? 0;
                                        $totalLessons = $course->modules->sum(fn($m) => $m->lessons->count());
                                        $thumb = $course->thumbnail
                                            ? asset('storage/'.$course->thumbnail)
                                            : 'https://ui-avatars.com/api/?name='.urlencode($course->title).'&background=1d7270&color=fff&size=80';
                                    @endphp

                                    <div class="bg-[var(--surface-default)] border border-[var(--outline)] rounded-2xl p-4 flex items-center gap-4 shadow-elevation-1 hover:shadow-elevation-2 transition-shadow">
                                        {{-- Thumbnail --}}
                                        <div class="w-14 h-14 rounded-xl overflow-hidden shrink-0 bg-[var(--surface-muted)]">
                                            <img src="{{ $thumb }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                                        </div>

                                        {{-- Info --}}
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-bold text-sm text-[var(--brand-navy)] truncate">{{ $course->title }}</h3>
                                            @if($enrollment->payment_status === 'paid')
                                                <div class="mt-2">
                                                    <div class="h-1.5 w-full bg-[var(--surface-muted)] rounded-full overflow-hidden">
                                                        <div class="h-full bg-[var(--brand-teal)] rounded-full" style="width: 35%"></div>
                                                    </div>
                                                    <p class="text-xs text-[var(--on-surface-muted)] mt-1">35% সম্পন্ন</p>
                                                </div>
                                            @else
                                                <span class="inline-block mt-1.5 text-xs font-semibold px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-600 border border-amber-200">
                                                    অনুমোদনের অপেক্ষায়
                                                </span>
                                            @endif
                                        </div>

                                        {{-- CTA --}}
                                        <div class="shrink-0">
                                            @if($enrollment->payment_status === 'paid')
                                                <a href="{{ route('courses.lesson', ['slug' => $course->slug, 'lesson_id' => $firstLessonId]) }}"
                                                   class="inline-flex items-center gap-1.5 font-bold text-sm text-white px-4 py-2 rounded-xl transition-colors"
                                                   style="background: var(--brand-teal);">
                                                    চালিয়ে যান
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                                </a>
                                            @else
                                                <a href="{{ route('courses.show', $course->slug) }}"
                                                   class="inline-flex items-center gap-1.5 font-semibold text-sm px-4 py-2 rounded-xl border border-[var(--outline)] text-[var(--on-surface)] hover:bg-[var(--surface-hover)] transition-colors">
                                                    বিস্তারিত
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-[var(--surface-default)] border border-[var(--outline)] rounded-2xl p-12 text-center shadow-elevation-1">
                                <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center" style="background: var(--pastel-mint);">
                                    <svg class="w-8 h-8 text-[var(--brand-teal)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                </div>
                                <h4 class="text-lg font-bold text-[var(--brand-navy)] mb-2">এখনো কোনো কোর্সে এনরোল করেননি</h4>
                                <p class="text-sm text-[var(--on-surface-muted)] mb-6">আমাদের জনপ্রিয় কোর্সগুলো থেকে আপনার পছন্দের বিষয় বেছে নিন।</p>
                                <a href="{{ route('courses.index') }}"
                                   class="inline-flex items-center gap-2 font-bold text-sm text-white px-6 py-2.5 rounded-xl"
                                   style="background: var(--brand-teal);">
                                    কোর্সসমূহ দেখুন →
                                </a>
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
