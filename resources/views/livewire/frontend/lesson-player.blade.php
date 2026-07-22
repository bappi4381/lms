<?php

use Livewire\Volt\Component;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Services\BunnyStreamService;
use App\Services\CertificateService;

new class extends Component {
    public Course $course;
    public Lesson $lesson;

    public function mount($slug, $lesson_id)
    {
        $this->course = Course::where('slug', $slug)
            ->where('is_published', true)
            ->with(['modules' => function($query) {
                $query->orderBy('order');
            }, 'modules.lessons' => function($query) {
                $query->orderBy('order');
            }])
            ->firstOrFail();

        $this->lesson = Lesson::whereIn('module_id', $this->course->modules->pluck('id'))
            ->findOrFail($lesson_id);

        // Mark lesson as viewed/completed for logged-in enrolled users (drives certificate eligibility)
        if (auth()->check() && (auth()->user()->isEnrolledIn($this->course->id) || $this->lesson->is_preview)) {
            LessonProgress::updateOrCreate(
                ['user_id' => auth()->id(), 'lesson_id' => $this->lesson->id],
                ['is_completed' => true, 'completed_at' => now()]
            );

            if (auth()->user()->isEnrolledIn($this->course->id)) {
                app(CertificateService::class)->issueIfEligible(auth()->user(), $this->course);
            }
        }
    }

    public function getVideoUrl()
    {
        if (! $this->lesson->video_id) {
            return null;
        }

        return app(BunnyStreamService::class)->signedEmbedUrl($this->lesson->video_id);
    }

    public function getWatermarkText(): string
    {
        $user = auth()->user();

        if (! $user) {
            return '';
        }

        return $user->email ?: ($user->phone ?: $user->name);
    }
};
?>

@php
    $canWatch = $lesson->is_preview || (auth()->check() && auth()->user()->isEnrolledIn($course->id));

    // Calculate previous & next lessons
    $allLessons = $course->modules->flatMap->lessons;
    $currentIndex = $allLessons->search(fn($l) => $l->id === $lesson->id);
    $prevLesson = $currentIndex > 0 ? $allLessons->get($currentIndex - 1) : null;
    $nextLesson = $currentIndex !== false && $currentIndex < $allLessons->count() - 1 ? $allLessons->get($currentIndex + 1) : null;
@endphp

<div class="bg-gray-100 min-h-screen py-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-4">

        {{-- ── Top Navigation Bar ── --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('courses.show', $course->slug) }}" class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-semibold px-4 py-2 rounded-xl transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                পেছনে যাই
            </a>

            <h1 class="text-lg font-extrabold text-gray-900 hidden sm:block truncate max-w-md">
                {{ $course->title }}
            </h1>
        </div>

        {{-- ── Main Player & Playlist Grid ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start" x-data="{ search: '' }">

            {{-- ── Left Column: Video Player Box (8 cols) ── --}}
            <div class="lg:col-span-8 space-y-4">
                
                {{-- Video Container --}}
                <div class="bg-white rounded-2xl border-2 border-amber-400/80 shadow-md overflow-hidden relative aspect-video flex items-center justify-center">
                    
                    @if($canWatch)
                        @if($lesson->video_id)
                            <iframe 
                                src="{{ $this->getVideoUrl() }}" 
                                loading="lazy" 
                                class="w-full h-full border-0 absolute inset-0"
                                allow="accelerometer;gyroscope;autoplay;encrypted-media;picture-in-picture;" 
                                allowfullscreen="true"
                                oncontextmenu="return false;">
                            </iframe>

                            {{-- Anti-piracy watermark: overlays the logged-in user's email/phone
                                 over the video so leaked recordings can be traced back to the
                                 sharer. Position drifts periodically so it can't be easily cropped. --}}
                            @auth
                                <div
                                    x-data="{
                                        text: @js($this->getWatermarkText()),
                                        pos: { top: '10%', left: '8%' },
                                        move() {
                                            const tops = ['8%', '20%', '40%', '60%', '78%'];
                                            const lefts = ['6%', '55%', '30%', '70%', '15%'];
                                            this.pos = {
                                                top: tops[Math.floor(Math.random() * tops.length)],
                                                left: lefts[Math.floor(Math.random() * lefts.length)],
                                            };
                                        }
                                    }"
                                    x-init="setInterval(() => move(), 15000)"
                                    class="absolute inset-0 pointer-events-none select-none z-20"
                                >
                                    <span
                                        x-text="text"
                                        :style="`top:${pos.top}; left:${pos.left};`"
                                        class="absolute text-white/40 text-xs sm:text-sm font-semibold tracking-wide transition-all duration-1000 ease-in-out drop-shadow"
                                    ></span>
                                </div>
                            @endauth
                        @else
                            {{-- Placeholder if video ID missing --}}
                            <div class="flex flex-col items-center justify-center text-center p-8">
                                <div class="w-16 h-16 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center mb-3">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-1">ভিডিও লিংক উপলব্ধ নয়</h3>
                                <p class="text-sm text-gray-500">এই লেসনের ভিডিও খুব শীঘ্রই আপলোড করা হবে।</p>
                            </div>
                        @endif
                    @else
                        {{-- Locked / Guest Overlay --}}
                        <div class="flex flex-col items-center justify-center text-center p-8 space-y-4">
                            <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900 max-w-lg leading-snug">
                                সবগুলো ভিডিও একসাথে দেখতে লগইন / রেজিস্টার করুন।
                            </h2>
                            @auth
                                <form method="POST" action="{{ route('payment.checkout', $course) }}">
                                    @csrf
                                    <button type="submit" class="bg-amber-400 hover:bg-amber-500 text-gray-950 font-extrabold px-8 py-3 rounded-xl text-base shadow-md transition-all">
                                        কোর্সে এনরোল করুন
                                    </button>
                                </form>
                            @else
                                <button @click="$dispatch('open-auth-drawer')" class="bg-amber-400 hover:bg-amber-500 text-gray-950 font-extrabold px-8 py-3 rounded-xl text-base shadow-md transition-all">
                                    লগইন/সাইনআপ
                                </button>
                            @endauth
                        </div>
                    @endif

                </div>

                {{-- Lesson Info & Prev/Next Bar --}}
                <div class="bg-white rounded-2xl border border-gray-200 p-5 shadow-xs flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-extrabold text-gray-900">{{ $lesson->title }}</h2>
                        @if($lesson->duration_seconds)
                            <p class="text-xs text-gray-500 font-medium mt-1">
                                ⏱ {{ gmdate("i:s", $lesson->duration_seconds) }} মিনিট
                            </p>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 shrink-0">
                        @if($prevLesson)
                            <a href="{{ route('courses.lesson', ['slug' => $course->slug, 'lesson_id' => $prevLesson->id]) }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-bold rounded-xl transition-colors">
                                ← আগের লেসন
                            </a>
                        @endif

                        @if($nextLesson)
                            <a href="{{ route('courses.lesson', ['slug' => $course->slug, 'lesson_id' => $nextLesson->id]) }}" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold rounded-xl transition-colors">
                                পরের লেসন →
                            </a>
                        @endif
                    </div>
                </div>

            </div>

            {{-- ── Right Column: Playlist Sidebar (4 cols) ── --}}
            <div class="lg:col-span-4 bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col h-[680px]">
                
                {{-- Playlist Header & Search --}}
                <div class="p-4 border-b border-gray-100 space-y-3 bg-white">
                    <h3 class="text-lg font-bold text-gray-900">প্লেলিস্ট</h3>
                    
                    {{-- Search Input --}}
                    <div class="relative">
                        <input 
                            type="text" 
                            x-model="search" 
                            placeholder="Search" 
                            class="w-full pl-4 pr-10 py-2 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-purple-500 transition-colors"
                        >
                        <svg class="w-4 h-4 text-gray-400 absolute right-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>

                {{-- Playlist Scrollable Items --}}
                <div class="flex-1 overflow-y-auto divide-y divide-gray-100 custom-scrollbar">
                    @php $lessonCounter = 1; @endphp
                    @foreach($course->modules as $module)
                        @foreach($module->lessons as $curriculumLesson)
                            @php
                                $isActive = $curriculumLesson->id === $lesson->id;
                                $isUnlocked = $curriculumLesson->is_preview || (auth()->check() && auth()->user()->isEnrolledIn($course->id));
                                $lessonNum = $lessonCounter++;
                            @endphp

                            <a 
                                href="{{ route('courses.lesson', ['slug' => $course->slug, 'lesson_id' => $curriculumLesson->id]) }}" 
                                x-show="search === '' || '{{ strtolower(addslashes($curriculumLesson->title)) }}'.includes(search.toLowerCase())"
                                class="flex items-start gap-3 p-3.5 transition-colors group {{ $isActive ? 'bg-purple-50/80 border-l-4 border-purple-600' : 'hover:bg-gray-50' }}"
                            >
                                {{-- Icon --}}
                                <div class="mt-0.5 w-7 h-7 rounded-full flex items-center justify-center shrink-0 {{ $isActive ? 'bg-purple-600 text-white' : ($isUnlocked ? 'bg-purple-100 text-purple-600 group-hover:bg-purple-200' : 'bg-gray-100 text-gray-400') }}">
                                    @if($isUnlocked)
                                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.841z"/>
                                        </svg>
                                    @else
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                        </svg>
                                    @endif
                                </div>

                                {{-- Text Info --}}
                                <div class="min-w-0 flex-1">
                                    <p class="text-xs sm:text-sm font-semibold leading-snug {{ $isActive ? 'text-purple-900 font-bold' : 'text-gray-800' }}">
                                        {{ $lessonNum }}) {{ $curriculumLesson->title }}
                                    </p>
                                    @if($curriculumLesson->duration_seconds)
                                        <p class="text-[11px] text-gray-400 mt-1">
                                            {{ gmdate("i", $curriculumLesson->duration_seconds) }} মিনিট
                                        </p>
                                    @endif
                                </div>
                            </a>
                        @endforeach
                    @endforeach
                </div>

            </div>

        </div>

    </div>
</div>