<?php

use Livewire\Volt\Component;
use App\Models\Course;
use function Livewire\Volt\layout;

layout('layouts::pintar');

new class extends Component {
    public Course $course;
    public $relatedCourses = [];

    public function mount($slug)
    {
        $this->course = Course::where('slug', $slug)
            ->where('is_published', true)
            ->with(['instructor', 'category', 'reviews.user', 'modules' => function($q) {
                $q->orderBy('order');
            }, 'modules.lessons' => function($q) {
                $q->orderBy('order');
            }])
            ->firstOrFail();

        $this->relatedCourses = Course::where('is_published', true)
            ->where('id', '!=', $this->course->id)
            ->where('category_id', $this->course->category_id)
            ->with(['category', 'instructor'])
            ->take(3)
            ->get();

        if ($this->relatedCourses->isEmpty()) {
            $this->relatedCourses = Course::where('is_published', true)
                ->where('id', '!=', $this->course->id)
                ->with(['category', 'instructor'])
                ->take(3)
                ->get();
        }
    }

    public function enroll()
    {
        if (! auth()->check()) {
            $this->dispatch('open-auth-drawer');
            return;
        }
        \App\Models\Enrollment::firstOrCreate([
            'user_id'   => auth()->id(),
            'course_id' => $this->course->id,
        ], ['payment_status' => 'pending']);
        return redirect()->route('courses.show', $this->course->slug)
            ->with('status', 'আপনার এনরোলমেন্ট রিকোয়েস্ট অ্যাডমিনকে পাঠানো হয়েছে।');
    }
};
?>

<div class="bg-[#f8fafc] text-slate-800 min-h-screen py-6 sm:py-8" x-data="{ activeTab: 'overview', showVideoModal: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- ════════════════════════════════════════════════════════════════════════
             TOP HEADER SECTION (Breadcrumb, Title, Meta Stats)
        ════════════════════════════════════════════════════════════════════════ --}}
        <div class="mb-8">
            {{-- Category Tag / Breadcrumb --}}
            <div class="flex items-center gap-2 mb-4">
                <a href="{{ route('courses.list', ['category' => $course->category_id]) }}"
                   class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold tracking-widest uppercase transition-all hover:opacity-90 shadow-sm"
                   style="background-color:#f09e0f; color:#ffffff; letter-spacing:0.08em;">
                    {{ $course->category->name ?? __('nav.main_types.academic') }}
                </a>
            </div>

            {{-- Course Main Title --}}
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight leading-tight max-w-4xl mb-5">
                {{ $course->title }}
            </h1>

            {{-- Meta Stats Row — pill badges --}}
            <div class="flex flex-wrap items-center gap-3">

                {{-- Rating --}}
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-amber-50 border border-amber-200 text-sm font-semibold text-amber-700">
                    <span class="text-amber-500 text-base leading-none">★</span>
                    <span>{{ $course->averageRating() > 0 ? number_format($course->averageRating(), 1) : '4.8' }}</span>
                    <span class="text-amber-500/70 font-normal">({{ $course->reviewsCount() > 0 ? $course->reviewsCount() : '320' }} {{ __('course.reviews') }})</span>
                </div>

                <span class="w-px h-5 bg-slate-200 hidden sm:block"></span>

                {{-- Enrolled Students --}}
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 border border-slate-200 text-sm font-semibold text-slate-700">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-1a4 4 0 00-5-3.87M9 20H4v-1a4 4 0 015-3.87m6-5a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>{{ $course->enrollments()->count() > 0 ? number_format($course->enrollments()->count()) : '4' }} {{ __('course.students') }}</span>
                </div>

                <span class="w-px h-5 bg-slate-200 hidden sm:block"></span>

                {{-- Total Lessons --}}
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 border border-slate-200 text-sm font-semibold text-slate-700">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ $course->modules->sum(fn($m) => $m->lessons->count()) ?: '1' }} {{ __('course.lessons') }}</span>
                </div>

                <span class="w-px h-5 bg-slate-200 hidden sm:block"></span>

                {{-- Language --}}
                <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-slate-100 border border-slate-200 text-sm font-semibold text-slate-700">
                    <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                    </svg>
                    <span>{{ __('course.language_name') }}</span>
                </div>

            </div>
        </div>

        {{-- ════════════════════════════════════════════════════════════════════════
             MAIN CONTENT & SIDEBAR GRID (2 COLUMNS)
        ════════════════════════════════════════════════════════════════════════ --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start relative">

            {{-- ── LEFT COLUMN (7-8 cols) ── --}}
            <div class="lg:col-span-7 xl:col-span-8 space-y-6">

                {{-- 1. Video / Thumbnail Player Box --}}
                <div class="w-full aspect-video rounded-2xl overflow-hidden bg-slate-900 border border-slate-200/80 relative shadow-sm group">
                    @if($course->thumbnail)
                        <img src="{{ asset('storage/'.$course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-slate-100 via-slate-50 to-blue-50/30 flex items-center justify-center relative">
                            <div class="absolute inset-0 bg-[radial-gradient(#e2e8f0_1px,transparent_1px)] [background-size:16px_16px] opacity-40"></div>
                        </div>
                    @endif

                    {{-- Dark Overlay --}}
                    <div class="absolute inset-0 bg-slate-900/25 group-hover:bg-slate-900/35 transition-colors flex items-center justify-center">
                        {{-- Centered Play Button --}}
                        <button @click="showVideoModal = true" type="button" 
                            class="w-16 h-16 sm:w-20 sm:h-20 rounded-full flex items-center justify-center shadow-xl hover:scale-110 active:scale-95 transition-all duration-300 pl-1 group/btn cursor-pointer"
                            style="background-color: #f09e0f !important; color: #ffffff !important;">
                            <svg class="w-8 h-8 text-white fill-current transform group-hover/btn:scale-110 transition-transform" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Bottom-Left Floating Badge --}}
                    <div class="absolute bottom-4 left-4 bg-white/95 backdrop-blur-sm text-slate-800 text-xs font-semibold px-3 py-1.5 rounded-lg shadow-sm flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>{{ __('course.preview_video') }}</span>
                    </div>
                </div>

                {{-- Navigation Tabs Row --}}
                <div class="border-b border-slate-200/80 bg-white rounded-xl px-4 sm:px-6 shadow-sm">
                    <nav class="flex items-center gap-6 sm:gap-8 overflow-x-auto text-sm font-medium scrollbar-none" aria-label="Tabs">
                        <button 
                            @click="activeTab = 'overview'" 
                            :class="activeTab === 'overview' ? 'font-bold border-b-2 py-3.5' : 'text-slate-500 hover:text-slate-800 py-3.5 transition-colors'"
                            :style="activeTab === 'overview' ? 'color: #1e3a5f !important; border-color: #1e3a5f !important;' : ''"
                            class="whitespace-nowrap flex items-center gap-2 cursor-pointer"
                        >
                            <span>{{ __('course.tab_overview') }}</span>
                        </button>

                        <button 
                            @click="activeTab = 'curriculum'" 
                            :class="activeTab === 'curriculum' ? 'font-bold border-b-2 py-3.5' : 'text-slate-500 hover:text-slate-800 py-3.5 transition-colors'"
                            :style="activeTab === 'curriculum' ? 'color: #1e3a5f !important; border-color: #1e3a5f !important;' : ''"
                            class="whitespace-nowrap flex items-center gap-2 cursor-pointer"
                        >
                            <span>{{ __('course.tab_curriculum') }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-semibold">{{ $course->modules->count() }}</span>
                        </button>

                        <button 
                            @click="activeTab = 'review'" 
                            :class="activeTab === 'review' ? 'font-bold border-b-2 py-3.5' : 'text-slate-500 hover:text-slate-800 py-3.5 transition-colors'"
                            :style="activeTab === 'review' ? 'color: #1e3a5f !important; border-color: #1e3a5f !important;' : ''"
                            class="whitespace-nowrap flex items-center gap-2 cursor-pointer"
                        >
                            <span>{{ __('course.tab_review') }}</span>
                            <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 font-semibold">{{ $course->reviewsCount() }}</span>
                        </button>

                        @if($course->instructor)
                            <button 
                                @click="activeTab = 'instructor'" 
                                :class="activeTab === 'instructor' ? 'font-bold border-b-2 py-3.5' : 'text-slate-500 hover:text-slate-800 py-3.5 transition-colors'"
                                :style="activeTab === 'instructor' ? 'color: #1e3a5f !important; border-color: #1e3a5f !important;' : ''"
                                class="whitespace-nowrap flex items-center gap-2 cursor-pointer"
                            >
                                <span>{{ __('course.tab_instructor') }}</span>
                            </button>
                        @endif
                    </nav>
                </div>

                {{-- ── TAB 1: OVERVIEW ── --}}
                <div x-show="activeTab === 'overview'" x-cloak class="space-y-6">
                    
                    {{-- Course Description --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80 space-y-4">
                        <div class="prose max-w-none text-slate-600 text-sm leading-relaxed space-y-3">
                            @if($course->sub_description)
                                <p class="text-base font-semibold text-slate-800 leading-relaxed">{{ $course->sub_description }}</p>
                            @endif
                            {!! $course->description !!}
                        </div>
                    </div>

                    {{-- What You Will Learn --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80 space-y-4">
                        <h3 class="text-lg font-bold text-slate-900">{{ __('course.what_you_learn') }}</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-1">
                            @if($course->key_features && count($course->key_features))
                                @foreach($course->key_features as $feature)
                                    <div class="flex items-start gap-3 p-2 rounded-lg hover:bg-slate-50 transition-colors">
                                        <div class="w-5 h-5 rounded-full text-white flex items-center justify-center shrink-0 mt-0.5 shadow-sm" style="background-color: #10b981 !important; color: #ffffff !important;">
                                            <svg class="w-3.5 h-3.5 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                        <span class="text-sm font-medium text-slate-700 leading-snug">{{ is_array($feature) ? ($feature['feature'] ?? '') : $feature }}</span>
                                    </div>
                                @endforeach
                            @else
                                @foreach(['default_learn_1','default_learn_2','default_learn_3','default_learn_4'] as $key)
                                    <div class="flex items-start gap-3 p-2 rounded-lg hover:bg-slate-50 transition-colors">
                                        <div class="w-5 h-5 rounded-full text-white flex items-center justify-center shrink-0 mt-0.5 shadow-sm" style="background-color: #10b981 !important; color: #ffffff !important;">
                                            <svg class="w-3.5 h-3.5 stroke-[3]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                        </div>
                                        <span class="text-sm font-medium text-slate-700 leading-snug">{{ __('course.'.$key) }}</span>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    {{-- Projects Section --}}
                    @if($course->projects && count($course->projects))
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80 space-y-4">
                            <h3 class="text-lg font-bold text-slate-900">{{ __('course.projects_title') }}</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($course->projects as $project)
                                    <div class="rounded-xl overflow-hidden border border-slate-200/70 hover:shadow-md transition-shadow group bg-slate-50">
                                        @if(!empty($project['image']))
                                            <img src="{{ asset('storage/'.$project['image']) }}" alt="{{ $project['title'] ?? '' }}" class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-500">
                                        @endif
                                        <div class="p-4">
                                            <p class="font-bold text-slate-800 text-sm">{{ $project['title'] ?? '' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Tools Section --}}
                    @if($course->tools && count($course->tools))
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80 space-y-4">
                            <h3 class="text-lg font-bold text-slate-900">{{ __('course.tools_title') }}</h3>
                            <div class="flex flex-wrap gap-2.5">
                                @foreach($course->tools as $tool)
                                    <span class="inline-flex items-center px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-semibold text-xs border border-slate-200/60 hover:bg-slate-200/70 transition-colors">
                                        {{ is_array($tool) ? ($tool['tool'] ?? '') : $tool }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- FAQ Section --}}
                    @if($course->faqs && count($course->faqs))
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80 space-y-4">
                            <h3 class="text-lg font-bold text-slate-900">{{ __('course.faq_title') }}</h3>
                            <div class="space-y-3">
                                @foreach($course->faqs as $faq)
                                    <details class="group rounded-xl border border-slate-200/80 overflow-hidden bg-slate-50/50">
                                        <summary class="flex items-center justify-between px-5 py-4 cursor-pointer list-none hover:bg-slate-100/60 transition-colors select-none font-bold text-sm text-slate-800">
                                            <span>{{ is_array($faq) ? ($faq['question'] ?? '') : $faq }}</span>
                                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 group-open:rotate-180 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </summary>
                                        <div class="px-5 py-4 text-slate-600 text-sm leading-relaxed border-t border-slate-200/60 bg-white">
                                            {{ is_array($faq) ? ($faq['answer'] ?? '') : '' }}
                                        </div>
                                    </details>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                {{-- ── TAB 2: CURRICULUM ── --}}
                <div x-show="activeTab === 'curriculum'" x-cloak class="space-y-4">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80 space-y-4">
                        <div class="flex items-center justify-between pb-2 border-b border-slate-100">
                            <h3 class="text-lg font-bold text-slate-900">{{ __('course.curriculum_title') }}</h3>
                            <span class="text-xs font-semibold text-slate-500">
                                {{ __('course.modules_count', ['count' => $course->modules->count()]) }}
                                •
                                {{ __('course.lessons_count', ['count' => $course->modules->sum(fn($m) => $m->lessons->count())]) }}
                            </span>
                        </div>

                        <div class="space-y-3">
                            @forelse($course->modules as $i => $module)
                                <details class="group rounded-xl border border-slate-200/80 overflow-hidden bg-white shadow-2xs {{ $i === 0 ? 'open' : '' }}">
                                    <summary class="flex items-center justify-between px-5 py-4 cursor-pointer list-none hover:bg-slate-50 transition-colors select-none">
                                        <div class="flex items-center gap-3">
                                            <span class="w-7 h-7 rounded-full bg-slate-100 text-slate-700 text-xs font-bold flex items-center justify-center shrink-0">{{ $i+1 }}</span>
                                            <span class="font-bold text-slate-800 text-sm sm:text-base">{{ $module->title }}</span>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <span class="text-xs text-slate-400 font-medium hidden sm:inline-block">{{ $module->lessons->count() }} {{ __('course.lesson_label') }}</span>
                                            <svg class="w-5 h-5 text-slate-400 transition-transform duration-200 group-open:rotate-180 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                        </div>
                                    </summary>
                                    <div class="p-4 bg-slate-50/50 border-t border-slate-100">
                                        @if($module->hasLiveClass())
                                            <div class="flex flex-wrap items-center justify-between gap-2 bg-emerald-50 border border-emerald-200/80 rounded-xl p-3.5 mb-3">
                                                <div class="text-xs sm:text-sm text-emerald-900">
                                                    <span class="font-bold">{{ __('course.live_class') }}</span>
                                                    @if($module->live_class_at)
                                                        — {{ $module->live_class_at->format('d M, Y — h:i A') }}
                                                    @endif
                                                </div>
                                                @if(auth()->check() && auth()->user()->isEnrolledIn($course->id))
                                                    <a href="{{ $module->live_class_link }}" target="_blank" 
                                                       class="px-3.5 py-1.5 rounded-lg text-xs font-bold transition-colors"
                                                       style="background-color: #059669 !important; color: #ffffff !important;">
                                                        {{ __('course.join_class') }}
                                                    </a>
                                                @else
                                                    <span class="text-xs text-emerald-700 font-semibold">{{ __('course.enroll_to_join') }}</span>
                                                @endif
                                            </div>
                                        @endif
                                        <ul class="space-y-2">
                                            @forelse($module->lessons as $lesson)
                                                @php $unlocked = $lesson->is_preview || (auth()->check() && auth()->user()->isEnrolledIn($course->id)); @endphp
                                                <li class="flex items-center justify-between p-3 rounded-lg bg-white border border-slate-200/60 hover:border-slate-300 transition-all text-xs sm:text-sm">
                                                    <div class="flex items-center gap-3 min-w-0">
                                                        <div class="w-6 h-6 rounded-full bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
                                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
                                                        </div>
                                                        <span class="font-medium text-slate-700 truncate">{{ $lesson->title }}</span>
                                                        @if($lesson->is_preview)
                                                            <span class="text-[11px] bg-blue-50 text-blue-700 font-semibold px-2 py-0.5 rounded-full shrink-0">{{ __('course.free_preview') }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="flex items-center gap-3 shrink-0 ml-2">
                                                        @if($lesson->duration_seconds)
                                                            <span class="text-xs text-slate-400 font-medium">{{ gmdate("i:s", $lesson->duration_seconds) }}</span>
                                                        @endif
                                                        @if($unlocked)
                                                            <a href="{{ route('courses.lesson', ['slug' => $course->slug, 'lesson_id' => $lesson->id]) }}" 
                                                               class="text-xs px-3 py-1 rounded-lg font-bold transition-colors"
                                                               style="background-color: #1e3a5f !important; color: #ffffff !important;">
                                                                {{ __('course.watch_btn') }}
                                                            </a>
                                                        @else
                                                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                                        @endif
                                                    </div>
                                                </li>
                                            @empty
                                                <li class="text-xs text-slate-400 italic p-2">{{ __('course.no_lessons') }}</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </details>
                            @empty
                                <div class="text-center py-8 text-slate-400 text-sm">{{ __('course.curriculum_coming') }}</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- ── TAB 3: REVIEWS ── --}}
                <div x-show="activeTab === 'review'" x-cloak class="space-y-4">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80 space-y-6">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                            <h3 class="text-lg font-bold text-slate-900">{{ __('course.reviews_title') }}</h3>
                            @if($course->reviewsCount())
                                <span class="text-sm font-bold text-amber-500">★ {{ $course->averageRating() }} ({{ $course->reviewsCount() }} {{ __('course.reviews') }})</span>
                            @endif
                        </div>

                        @if(auth()->check() && auth()->user()->isEnrolledIn($course->id))
                            <form method="POST" action="{{ route('reviews.store', $course) }}" class="bg-slate-50 border border-slate-200/80 rounded-xl p-4 space-y-3" x-data="{ rating: 5 }">
                                @csrf
                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button" @click="rating = {{ $i }}" class="text-xl focus:outline-none" :class="rating >= {{ $i }} ? 'text-amber-400' : 'text-slate-300'">★</button>
                                    @endfor
                                    <input type="hidden" name="rating" x-model="rating">
                                </div>
                                <textarea name="comment" rows="2" placeholder="{{ __('course.review_placeholder') }}" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm focus:ring-2 focus:ring-[#1e3a5f] focus:outline-none bg-white"></textarea>
                                <button type="submit" 
                                    class="px-4 py-2 text-xs font-bold rounded-lg transition-colors"
                                    style="background-color: #f09e0f !important; color: #ffffff !important;">
                                    {{ __('course.submit_review') }}
                                </button>
                            </form>
                        @endif

                        <div class="space-y-4">
                            @forelse($course->reviews as $review)
                                <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-slate-800 text-sm">{{ $review->user->name }}</span>
                                        <span class="text-amber-400 text-xs font-semibold">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                                    </div>
                                    @if($review->comment)
                                        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-sm text-slate-400 italic text-center py-6">{{ __('course.no_reviews') }}</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- ── TAB 4: INSTRUCTOR ── --}}
                @if($course->instructor)
                    <div x-show="activeTab === 'instructor'" x-cloak class="space-y-4">
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80 space-y-4">
                            <h3 class="text-lg font-bold text-slate-900">{{ __('course.instructor_title') }}</h3>
                            <div class="flex items-center gap-4">
                                @if($course->instructor->profile_photo_url)
                                    <img
                                        src="{{ asset('storage/'.$course->instructor->profile_photo_url) }}"
                                        alt="{{ $course->instructor->name }}"
                                        class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl object-cover shrink-0 border border-slate-200 shadow-sm"
                                    >
                                @else
                                    @php
                                        $words = array_values(array_filter(explode(' ', trim($course->instructor->name))));
                                        $initials = count($words) >= 2 
                                            ? mb_substr($words[0], 0, 1) . mb_substr($words[count($words)-1], 0, 1)
                                            : mb_substr($course->instructor->name, 0, 2);
                                        $initials = mb_strtoupper($initials);
                                    @endphp
                                    <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl shrink-0 flex items-center justify-center font-bold text-xl text-white shadow-sm" style="background-color: #f09e0f !important;">
                                        {{ $initials }}
                                    </div>
                                @endif

                                <div>
                                    <h4 class="text-base sm:text-lg font-bold text-slate-900">{{ $course->instructor->name }}</h4>
                                    @if($course->instructor->designation || $course->instructor->company)
                                        <p class="text-xs sm:text-sm font-semibold text-slate-500 mt-0.5">
                                            {{ $course->instructor->designation }}
                                            @if($course->instructor->designation && $course->instructor->company) · @endif
                                            {{ $course->instructor->company }}
                                        </p>
                                    @endif
                                    @if($course->instructor->about)
                                        <p class="text-xs sm:text-sm text-slate-600 mt-1 leading-relaxed">{{ $course->instructor->about }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ════════════════════════════════════════════════════════════════════════
                     Related Courses
                ════════════════════════════════════════════════════════════════════════ --}}
                @if($relatedCourses && count($relatedCourses))
                    <div class="pt-6 border-t border-slate-200/80">
                        <h3 class="text-lg font-bold text-slate-900 mb-4">{{ __('course.related_courses') }}</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            @foreach($relatedCourses as $relCourse)
                                <a href="{{ route('courses.show', $relCourse->slug) }}" class="bg-white rounded-2xl p-3 border border-slate-200/80 shadow-2xs hover:shadow-md hover:border-slate-300 transition-all group flex flex-col justify-between">
                                    <div>
                                        <div class="w-full aspect-video rounded-xl bg-slate-100 overflow-hidden mb-3">
                                            @if($relCourse->thumbnail)
                                                <img src="{{ asset('storage/'.$relCourse->thumbnail) }}" alt="{{ $relCourse->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-blue-50 to-indigo-50 flex items-center justify-center text-slate-400">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <h4 class="font-bold text-slate-800 text-xs sm:text-sm line-clamp-2 group-hover:text-[#1e3a5f] transition-colors leading-snug mb-2">
                                            {{ $relCourse->title }}
                                        </h4>
                                    </div>
                                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                        <span class="text-xs sm:text-sm font-extrabold text-slate-900">৳{{ number_format($relCourse->discount_price ?: $relCourse->price, 0) }}</span>
                                        @if($relCourse->discount_price && $relCourse->discount_price < $relCourse->price)
                                            <span class="text-[11px] text-slate-400 line-through font-medium">৳{{ number_format($relCourse->price, 0) }}</span>
                                        @endif
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>

            {{-- ── RIGHT COLUMN: STICKY FLOATING SIDEBAR ── --}}
            <div class="lg:col-span-5 xl:col-span-4 sticky top-6 z-20">
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200/80 space-y-5">
                    
                    {{-- Price Display --}}
                    <div>
                        <div class="flex items-baseline gap-2.5">
                            @if($course->discount_price && $course->discount_price < $course->price)
                                <span class="text-3xl sm:text-4xl font-extrabold text-[#0f172a] tracking-tight">৳{{ number_format($course->discount_price, 0) }}</span>
                                <span class="text-base font-semibold text-slate-400 line-through">৳{{ number_format($course->price, 0) }}</span>
                            @else
                                <span class="text-3xl sm:text-4xl font-extrabold text-[#0f172a] tracking-tight">৳{{ number_format($course->price, 0) }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-slate-500 font-medium mt-1">{{ __('course.one_time_payment') }}</p>
                    </div>

                    {{-- Status / Error Alerts --}}
                    @if(session('status'))
                        <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-semibold rounded-xl">{{ session('status') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="p-3 bg-rose-50 border border-rose-200 text-rose-800 text-xs font-semibold rounded-xl">{{ session('error') }}</div>
                    @endif

                    {{-- CTA Enrollment Button --}}
                    @if(auth()->check() && auth()->user()->isEnrolledIn($course->id))
                        <div class="w-full py-3 px-4 bg-emerald-50 text-emerald-800 font-bold rounded-xl text-center text-xs sm:text-sm border border-emerald-200/70">
                            {{ __('course.already_enrolled') }}
                        </div>
                        @if($course->modules->count() && $course->modules->first()->lessons->count())
                            <a href="{{ route('courses.lesson', ['slug' => $course->slug, 'lesson_id' => $course->modules->first()->lessons->first()->id]) }}"
                               class="w-full py-3.5 text-white font-extrabold text-sm sm:text-base rounded-xl transition-all duration-200 text-center flex items-center justify-center gap-2 shadow-md cursor-pointer hover:opacity-95"
                               style="background-color: #f09e0f !important; color: #ffffff !important;">
                                <span>{{ __('course.start_course') }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endif
                    @elseauth
                        <form method="POST" action="{{ route('payment.checkout', $course) }}">
                            @csrf
                            <button type="submit"
                                class="w-full py-3.5 text-white font-extrabold text-base rounded-xl transition-all duration-200 text-center flex items-center justify-center gap-2 shadow-md cursor-pointer hover:opacity-95"
                                style="background-color: #f09e0f !important; color: #ffffff !important;">
                                <span>{{ __('course.enroll_now') }}</span>
                            </button>
                        </form>
                    @else
                        <div>
                            <button @click="$dispatch('open-auth-drawer')"
                                class="w-full py-3.5 text-white font-extrabold text-base rounded-xl transition-all duration-200 text-center flex items-center justify-center gap-2 shadow-md cursor-pointer hover:opacity-95"
                                style="background-color: #f09e0f !important; color: #ffffff !important;">
                                <span>{{ __('course.enroll_now') }}</span>
                            </button>
                        </div>
                    @endauth

                    <hr class="border-slate-100">

                    {{-- Included Features List --}}
                    <div class="space-y-3">
                        <ul class="space-y-3">
                            <li class="flex items-center gap-3 text-xs sm:text-sm text-slate-700 font-medium">
                                <svg class="w-4 h-4 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>{{ __('course.lifetime_access') }}</span>
                            </li>
                            <li class="flex items-center gap-3 text-xs sm:text-sm text-slate-700 font-medium">
                                <svg class="w-4 h-4 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 001.946.806 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                <span>{{ __('course.certificate') }}</span>
                            </li>
                            <li class="flex items-center gap-3 text-xs sm:text-sm text-slate-700 font-medium">
                                <svg class="w-4 h-4 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                <span>{{ __('course.mobile_desktop') }}</span>
                            </li>
                            <li class="flex items-center gap-3 text-xs sm:text-sm text-slate-700 font-medium">
                                <svg class="w-4 h-4 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                                <span>{{ __('course.community_forum') }}</span>
                            </li>

                            @if($course->course_includes && count($course->course_includes))
                                @foreach($course->course_includes as $inc)
                                    <li class="flex items-center gap-3 text-xs sm:text-sm text-slate-700 font-medium">
                                        <svg class="w-4 h-4 text-sky-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        <span>{{ is_array($inc) ? ($inc['item'] ?? '') : $inc }}</span>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- Video Modal Preview Player --}}
    <div x-show="showVideoModal" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/80 backdrop-blur-sm" @keydown.escape.window="showVideoModal = false">
        <div class="relative w-full max-w-4xl bg-black rounded-2xl overflow-hidden shadow-2xl aspect-video" @click.away="showVideoModal = false">
            <button @click="showVideoModal = false" class="absolute top-3 right-3 z-10 w-9 h-9 rounded-full bg-slate-800/80 hover:bg-slate-700 text-white flex items-center justify-center transition-colors">
                ✕
            </button>
            @if($course->video_url)
                @php
                    $vid = $course->video_url;
                    if (str_contains($vid, 'watch?v='))       { $vid = str_replace('watch?v=', 'embed/', $vid); }
                    elseif (str_contains($vid, 'youtu.be/'))  { $vid = str_replace('youtu.be/', 'youtube.com/embed/', $vid); }
                @endphp
                <iframe class="w-full h-full" src="{{ $vid }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
            @else
                <div class="w-full h-full flex flex-col items-center justify-center text-white space-y-2 p-6 text-center">
                    <svg class="w-16 h-16 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-sm font-semibold">{{ __('course.no_video_found') }}</p>
                </div>
            @endif
        </div>
    </div>

</div>