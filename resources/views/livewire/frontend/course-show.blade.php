<?php

use Livewire\Volt\Component;
use App\Models\Course;

new class extends Component {
    public Course $course;

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

<div>
    {{-- ════════════════════════════════════════════════════════════════════════
         TOP HERO SECTION (Dark Theme)
    ════════════════════════════════════════════════════════════════════════ --}}
    <div class="bg-[#070e1b] text-white pt-6 pb-12 border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                
                {{-- ── Left Column: Course Main Details ── --}}
                <div class="lg:col-span-7 xl:col-span-8 space-y-4">
                    
                    {{-- Navigation / Back Button --}}
                    <div class="flex items-center gap-2 text-sm text-gray-400">
                        <a href="{{ route('courses.index') }}" class="hover:text-white flex items-center gap-1 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            ফিরে যান
                        </a>
                    </div>

                    {{-- Badges Row --}}
                    <div class="flex flex-wrap items-center gap-2 pt-1">
                        <span class="inline-flex items-center gap-1 bg-[#1e293b] text-amber-400 border border-amber-500/30 text-xs font-semibold px-2.5 py-1 rounded-md">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            ব্যাচ - {{ $course->batch_number ?? 1 }}
                        </span>

                        <span class="inline-flex items-center gap-1 bg-[#1e293b] text-emerald-400 border border-emerald-500/30 text-xs font-semibold px-2.5 py-1 rounded-md">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            লাইভ কোর্স
                        </span>

                        <span class="inline-flex items-center gap-1 bg-[#1e293b] text-yellow-400 text-xs font-semibold px-2.5 py-1 rounded-md">
                            ★ 4.8 (313 reviews)
                        </span>
                    </div>

                    {{-- Main Title --}}
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white leading-tight tracking-tight">
                        {{ $course->title }}
                    </h1>

                    {{-- Subtitle / Sub Description --}}
                    <div class="text-gray-300 text-sm sm:text-base leading-relaxed">
                        @if($course->sub_description)
                            <p class="font-medium text-gray-200">{{ $course->sub_description }}</p>
                        @else
                            <p class="line-clamp-3">{!! strip_tags($course->description) !!}</p>
                        @endif
                    </div>

                    {{-- Price and Quick Action Banner --}}
                    <div class="flex flex-wrap items-center gap-4 py-2">
                        @auth
                            <form method="POST" action="{{ route('payment.checkout', $course) }}">
                                @csrf
                                <button type="submit" class="bg-amber-400 hover:bg-amber-500 text-gray-950 font-bold px-6 py-3 rounded-lg text-sm transition-all shadow-lg flex items-center gap-2">
                                    ব্যাচে ভর্তি হন
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </form>
                        @else
                            <button @click="$dispatch('open-auth-drawer')" class="bg-amber-400 hover:bg-amber-500 text-gray-950 font-bold px-6 py-3 rounded-lg text-sm transition-all shadow-lg flex items-center gap-2">
                                ব্যাচে ভর্তি হন
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                            </button>
                        @endauth

                        <div class="flex items-baseline gap-2">
                            @if($course->discount_price && $course->discount_price < $course->price)
                                <span class="text-2xl font-extrabold text-white">৳{{ number_format($course->discount_price, 0) }}</span>
                                <span class="text-sm font-semibold text-gray-400 line-through">৳{{ number_format($course->price, 0) }}</span>
                                <span class="text-xs bg-emerald-950 text-emerald-400 border border-emerald-700/50 px-2 py-0.5 rounded font-semibold flex items-center gap-1">
                                    ✓ প্রোমো অ্যাপ্লাইড
                                </span>
                            @else
                                <span class="text-2xl font-extrabold text-white">৳{{ number_format($course->price, 0) }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Schedule Grid Box --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-[#0d1829] border border-slate-800 p-4 rounded-xl mt-4">
                        <div class="space-y-1">
                            <p class="text-xs text-gray-400 flex items-center gap-1">
                                📅 ব্যাচ শুরু
                            </p>
                            <p class="text-xs sm:text-sm font-bold text-slate-200">
                                {{ $course->starts_at ? $course->starts_at->format('d M, Y') : 'শীঘ্রই শুরু' }}
                            </p>
                        </div>
                        <div class="space-y-1 border-l border-slate-800/80 pl-3">
                            <p class="text-xs text-gray-400 flex items-center gap-1">
                                ⏰ লাইভ ক্লাস
                            </p>
                            <p class="text-xs sm:text-sm font-bold text-slate-200">
                                {{ $course->live_class_schedule ?? 'নির্ধারিত সময়ে' }}
                            </p>
                        </div>
                        <div class="space-y-1 border-l border-slate-800/80 pl-3">
                            <p class="text-xs text-gray-400 flex items-center gap-1">
                                🎧 সাপোর্ট ক্লাস
                            </p>
                            <p class="text-xs sm:text-sm font-bold text-slate-200">
                                {{ $course->support_class_schedule ?? 'সপ্তাহে ৭ দিন' }}
                            </p>
                        </div>
                        <div class="space-y-1 border-l border-slate-800/80 pl-3">
                            <p class="text-xs text-gray-400 flex items-center gap-1">
                                🔥 সিট বাকি
                            </p>
                            <p class="text-xs sm:text-sm font-bold text-amber-400">
                                {{ $course->seatsRemaining() }} টি
                            </p>
                        </div>
                    </div>

                </div>

                {{-- Right Column Placeholder in Header to align layout --}}
                <div class="hidden lg:block lg:col-span-5 xl:col-span-4"></div>

            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════════════════════════════════════
         MAIN CONTENT AREA WITH STICKY SIDEBAR
    ════════════════════════════════════════════════════════════════════════ --}}
    <div class="bg-[#f8fafc] min-h-screen py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 relative items-start">
                
                {{-- ── Left Main Content (65%) ── --}}
                <div class="lg:col-span-7 xl:col-span-8 space-y-8">
                    
                    {{-- 1. কোর্স সম্পর্কে Card --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8 space-y-6">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-4">
                            <h2 class="text-xl font-bold text-slate-900 bg-slate-100 px-3 py-1 rounded-md inline-block">
                                কোর্স সম্পর্কে:
                            </h2>
                        </div>
                        
                        <div class="prose prose-slate max-w-none text-slate-700 text-sm leading-relaxed space-y-4">
                            {!! $course->description !!}
                        </div>
                    </div>

                    {{-- 2. ইন্সট্রাক্টর Card --}}
                    @if($course->instructor)
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                            <h2 class="text-xl font-bold text-slate-900 mb-6">ইন্সট্রাক্টর</h2>
                            <div class="flex items-start gap-4">
                                <img
                                    src="{{ $course->instructor->profile_photo_url ? asset('storage/'.$course->instructor->profile_photo_url) : 'https://ui-avatars.com/api/?name='.urlencode($course->instructor->name).'&background=e55a4e&color=fff&bold=true&size=128' }}"
                                    alt="{{ $course->instructor->name }}"
                                    class="w-20 h-20 rounded-2xl object-cover border border-slate-200 shrink-0 shadow-sm"
                                >
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">{{ $course->instructor->name }}</h3>
                                    @if($course->instructor->designation || $course->instructor->company)
                                        <p class="text-indigo-600 font-semibold text-sm mt-0.5">
                                            {{ $course->instructor->designation }}
                                            @if($course->instructor->designation && $course->instructor->company) · @endif
                                            {{ $course->instructor->company }}
                                        </p>
                                    @endif
                                    @if($course->instructor->about)
                                        <p class="text-slate-600 text-sm mt-2 leading-relaxed">{{ $course->instructor->about }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- 3. স্টাডি প্ল্যান / কারিকুলাম Card --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                        <h2 class="text-xl font-bold text-slate-900 mb-6">স্টাডি প্ল্যান</h2>
                        <div class="space-y-3">
                            @forelse($course->modules as $i => $module)
                                <details class="group border border-slate-200 rounded-xl overflow-hidden {{ $i === 0 ? 'open' : '' }}">
                                    <summary class="flex items-center justify-between px-5 py-4 cursor-pointer list-none hover:bg-slate-50 transition-colors select-none">
                                        <div class="flex items-center gap-3">
                                            <span class="w-7 h-7 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold flex items-center justify-center shrink-0">{{ $i+1 }}</span>
                                            <span class="font-semibold text-slate-800 text-base">{{ $module->title }}</span>
                                        </div>
                                        <svg class="w-5 h-5 text-slate-400 transition-transform duration-200 group-open:rotate-180 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </summary>
                                    <div class="border-t border-slate-100 bg-slate-50/50 p-4">
                                        @if($module->hasLiveClass())
                                            <div class="flex flex-wrap items-center justify-between gap-2 bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-3 mb-3">
                                                <div class="text-sm text-emerald-800">
                                                    <span class="font-bold">🔴 লাইভ ক্লাস</span>
                                                    @if($module->live_class_at)
                                                        — {{ $module->live_class_at->format('d M, Y — h:i A') }}
                                                    @endif
                                                    <span class="text-emerald-600 font-medium">({{ $module->live_class_provider === 'zoom' ? 'Zoom' : ($module->live_class_provider === 'google_meet' ? 'Google Meet' : 'Live') }})</span>
                                                </div>
                                                @if(auth()->check() && auth()->user()->isEnrolledIn($course->id))
                                                    <a href="{{ $module->live_class_link }}" target="_blank" class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2 rounded-lg transition-colors">
                                                        ক্লাসে যোগ দিন →
                                                    </a>
                                                @else
                                                    <span class="text-xs text-emerald-700 font-semibold">এনরোল করে জয়েন করুন</span>
                                                @endif
                                            </div>
                                        @endif
                                        <ul class="space-y-2">
                                            @forelse($module->lessons as $lesson)
                                                @php $unlocked = $lesson->is_preview || (auth()->check() && auth()->user()->isEnrolledIn($course->id)); @endphp
                                                <li class="flex items-center justify-between p-3.5 rounded-lg bg-white border border-slate-200/60 hover:border-indigo-300 hover:shadow-sm transition-all text-sm">
                                                    <div class="flex items-center gap-3 min-w-0">
                                                        <div class="w-7 h-7 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center shrink-0">
                                                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/>
                                                            </svg>
                                                        </div>
                                                        <span class="font-medium text-slate-700 truncate">{{ $lesson->title }}</span>
                                                        @if($lesson->is_preview)
                                                            <span class="text-xs bg-emerald-50 text-emerald-600 border border-emerald-200 px-2 py-0.5 rounded-full font-semibold shrink-0">ফ্রি ప్రీভিউ</span>
                                                        @endif
                                                    </div>
                                                    <div class="flex items-center gap-3 shrink-0 ml-2">
                                                        @if($lesson->duration_seconds)
                                                            <span class="text-xs text-slate-400 font-medium">{{ gmdate("i:s", $lesson->duration_seconds) }}</span>
                                                        @endif
                                                        @if($unlocked)
                                                            <a href="{{ route('courses.lesson', ['slug' => $course->slug, 'lesson_id' => $lesson->id]) }}"
                                                               class="text-xs px-3 py-1.5 bg-indigo-50 text-indigo-600 rounded-full font-bold hover:bg-indigo-600 hover:text-white transition-colors">
                                                                দেখুন
                                                            </a>
                                                        @else
                                                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                                            </svg>
                                                        @endif
                                                    </div>
                                                </li>
                                            @empty
                                                <li class="text-sm text-slate-400 italic p-2">কোনো লেসন নেই।</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </details>
                            @empty
                                <div class="text-center py-8 text-slate-400 text-sm">কারিকুলাম শীঘ্রই আপডেট করা হবে।</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- 4. যেসব প্রজেক্ট করবেন Card --}}
                    @if($course->projects && count($course->projects))
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                            <h2 class="text-xl font-bold text-slate-900 mb-6 text-center">যেসব প্রজেক্ট করবেন</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($course->projects as $project)
                                    <div class="rounded-xl overflow-hidden border border-slate-200 group hover:shadow-md transition-all">
                                        @if(!empty($project['image']))
                                            <img src="{{ asset('storage/'.$project['image']) }}" alt="{{ $project['title'] ?? '' }}" class="w-full h-44 object-cover group-hover:scale-105 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-44 bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                                                <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                        @endif
                                        <div class="p-3.5 bg-white border-t border-slate-100">
                                            <p class="font-semibold text-slate-800 text-sm">{{ $project['title'] ?? '' }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 5. ক্যারিয়ার অপরচুনিটি Card --}}
                    @if($course->career_opportunities && count($course->career_opportunities))
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                            <h2 class="text-xl font-bold text-slate-900 mb-6 text-center">ক্যারিয়ার অপরচুনিটি</h2>
                            <div class="flex flex-wrap justify-center gap-3">
                                @foreach($course->career_opportunities as $career)
                                    <span class="inline-flex items-center gap-2 bg-slate-50 border border-slate-200 text-slate-800 text-sm font-semibold px-5 py-2.5 rounded-xl shadow-xs">
                                        <svg class="w-4 h-4 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                        {{ is_array($career) ? ($career['job_role'] ?? '') : $career }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 6. যেসব টুলস শিখবেন Card --}}
                    @if($course->tools && count($course->tools))
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                            <h2 class="text-xl font-bold text-slate-900 mb-6 text-center">যেসব টুলস শিখবেন</h2>
                            <div class="flex flex-wrap justify-center gap-3">
                                @foreach($course->tools as $tool)
                                    <span class="inline-block bg-white border border-slate-200 text-slate-700 font-semibold text-sm px-5 py-2.5 rounded-xl shadow-xs hover:border-indigo-300 hover:bg-indigo-50 hover:text-indigo-700 transition-all cursor-default">
                                        {{ is_array($tool) ? ($tool['tool'] ?? '') : $tool }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- 7ক. রিভিউ ও রেটিং Card --}}
                    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-bold text-slate-900">শিক্ষার্থীদের রিভিউ</h2>
                            @if($course->reviewsCount())
                                <span class="text-sm font-semibold text-amber-600">★ {{ $course->averageRating() }} ({{ $course->reviewsCount() }} রিভিউ)</span>
                            @endif
                        </div>

                        @if(auth()->check() && auth()->user()->isEnrolledIn($course->id))
                            <form method="POST" action="{{ route('reviews.store', $course) }}" class="mb-6 bg-slate-50 rounded-xl p-4 space-y-3" x-data="{ rating: 5 }">
                                @csrf
                                <div class="flex items-center gap-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <button type="button" @click="rating = {{ $i }}" class="text-2xl" :class="rating >= {{ $i }} ? 'text-amber-400' : 'text-slate-300'">★</button>
                                    @endfor
                                    <input type="hidden" name="rating" x-model="rating">
                                </div>
                                <textarea name="comment" rows="2" placeholder="আপনার অভিজ্ঞতা লিখুন (ঐচ্ছিক)" class="w-full rounded-lg border-slate-200 text-sm px-3 py-2"></textarea>
                                <button type="submit" class="text-sm font-bold bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg transition-colors">
                                    রিভিউ জমা দিন
                                </button>
                            </form>
                        @endif

                        <div class="space-y-4">
                            @forelse($course->reviews as $review)
                                <div class="border-b border-slate-100 pb-4 last:border-0 last:pb-0">
                                    <div class="flex items-center justify-between">
                                        <span class="font-semibold text-slate-800 text-sm">{{ $review->user->name }}</span>
                                        <span class="text-amber-400 text-sm">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                                    </div>
                                    @if($review->comment)
                                        <p class="text-sm text-slate-600 mt-1">{{ $review->comment }}</p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-sm text-slate-400 italic">এখনো কোনো রিভিউ নেই।</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- 7. সচরাচর জিজ্ঞাসা (FAQ) Card --}}
                    @if($course->faqs && count($course->faqs))
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 sm:p-8">
                            <h2 class="text-xl font-bold text-slate-900 mb-6 text-center">সচরাচর জিজ্ঞাসা (FAQ)</h2>
                            <div class="space-y-3">
                                @foreach($course->faqs as $faq)
                                    <details class="group border border-slate-200 rounded-xl overflow-hidden">
                                        <summary class="flex items-center justify-between px-5 py-4 cursor-pointer list-none hover:bg-slate-50 transition-colors select-none">
                                            <span class="font-semibold text-slate-800 text-sm pr-4">{{ is_array($faq) ? ($faq['question'] ?? '') : $faq }}</span>
                                            <svg class="w-4 h-4 text-slate-400 transition-transform duration-200 group-open:rotate-180 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </summary>
                                        <div class="border-t border-slate-100 px-5 py-4 bg-slate-50/50 text-slate-600 text-sm leading-relaxed">
                                            {{ is_array($faq) ? ($faq['answer'] ?? '') : '' }}
                                        </div>
                                    </details>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

                {{-- ── Right Column: Sticky Floating Purchase Card (35%) ── --}}
                <div class="lg:col-span-5 xl:col-span-4 lg:-mt-72 lg:z-30">
                    <div class="sticky top-6">
                        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden">

                            {{-- Video / Thumbnail Player Area --}}
                            <div class="w-full aspect-video bg-slate-950 relative overflow-hidden group">
                                @if($course->video_url)
                                    @php
                                        $vid = $course->video_url;
                                        if (str_contains($vid, 'watch?v='))       { $vid = str_replace('watch?v=', 'embed/', $vid); }
                                        elseif (str_contains($vid, 'youtu.be/'))  { $vid = str_replace('youtu.be/', 'youtube.com/embed/', $vid); }
                                    @endphp
                                    <iframe class="absolute inset-0 w-full h-full" src="{{ $vid }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                @elseif($course->thumbnail)
                                    <img src="{{ asset('storage/'.$course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/30 flex items-center justify-center">
                                        <div class="w-14 h-14 rounded-full bg-amber-400 text-slate-950 flex items-center justify-center pl-1 shadow-lg transform group-hover:scale-110 transition-transform">
                                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.841z"/></svg>
                                        </div>
                                    </div>
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-slate-900 text-slate-600">
                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    </div>
                                @endif
                                
                                {{-- Overlay hint --}}
                                <div class="absolute bottom-2 left-2 bg-black/60 backdrop-blur-sm text-white text-[11px] px-2.5 py-1 rounded-md font-medium flex items-center gap-1 pointer-events-none">
                                    ▶ ক্লিক করে দেখে নিন কোর্সের ডেমো ক্লাস
                                </div>
                            </div>

                            <div class="p-6 space-y-5">
                                
                                {{-- Tabs: Personal / Group --}}
                                <div class="grid grid-cols-2 bg-slate-100 p-1 rounded-lg text-center text-xs font-bold text-slate-700">
                                    <div class="bg-white py-1.5 rounded-md shadow-xs text-slate-900 cursor-pointer">Personal</div>
                                    <div class="py-1.5 cursor-pointer hover:text-slate-900">একসাথে (Group)</div>
                                </div>

                                {{-- Price display --}}
                                <div class="flex items-baseline gap-2">
                                    @if($course->discount_price && $course->discount_price < $course->price)
                                        <span class="text-3xl font-extrabold text-slate-900">৳{{ number_format($course->discount_price, 0) }}</span>
                                        <span class="text-sm font-semibold text-slate-400 line-through">৳{{ number_format($course->price, 0) }}</span>
                                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded">
                                            ✓ প্রোমো অ্যাপ্লাইড
                                        </span>
                                    @else
                                        <span class="text-3xl font-extrabold text-slate-900">৳{{ number_format($course->price, 0) }}</span>
                                    @endif
                                </div>

                                {{-- Alerts --}}
                                @if(session('status'))
                                    <div class="p-3 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-medium rounded-lg">{{ session('status') }}</div>
                                @endif
                                @if(session('error'))
                                    <div class="p-3 bg-rose-50 border border-rose-200 text-rose-600 text-xs font-medium rounded-lg">{{ session('error') }}</div>
                                @endif

                                {{-- CTA Button --}}
                                @if(auth()->check() && auth()->user()->isEnrolledIn($course->id))
                                    <div class="w-full py-3 px-4 bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold rounded-xl text-center text-sm">
                                        ✓ আপনি ইতিমধ্যে এনরোল করেছেন
                                    </div>
                                    @if($course->modules->count() && $course->modules->first()->lessons->count())
                                        <a href="{{ route('courses.lesson', ['slug' => $course->slug, 'lesson_id' => $course->modules->first()->lessons->first()->id]) }}"
                                           class="block w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-center text-sm transition-colors shadow-md">
                                            কোর্সটি দেখা শুরু করুন →
                                        </a>
                                    @endif
                                @elseauth
                                    <form method="POST" action="{{ route('payment.checkout', $course) }}">
                                        @csrf
                                        <button type="submit"
                                            class="w-full py-3.5 bg-amber-400 hover:bg-amber-500 text-slate-950 font-extrabold rounded-xl text-base transition-colors flex items-center justify-center gap-2 shadow-md">
                                            ব্যাচে ভর্তি হন
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                        </button>
                                    </form>
                                @else
                                    <div x-data>
                                        <button @click="$dispatch('open-auth-drawer')"
                                            class="w-full py-3.5 bg-amber-400 hover:bg-amber-500 text-slate-950 font-extrabold rounded-xl text-base transition-colors flex items-center justify-center gap-2 shadow-md">
                                            ব্যাচে ভর্তি হন
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                        </button>
                                    </div>
                                @endauth

                                {{-- Timer badge --}}
                                <div class="text-center text-xs font-semibold text-rose-600 bg-rose-50 border border-rose-100 py-2 rounded-lg">
                                    ⏰ ব্যাচ শুরু হতে সময় বাকি - 7d 3h 42m 14s
                                </div>

                                <hr class="border-slate-100">

                                {{-- "এই কোর্সে আপনি পাচ্ছেন:" Checklist (2 columns like Ostad UI) --}}
                                <div>
                                    <p class="text-xs font-extrabold text-slate-900 mb-3 uppercase tracking-wide">
                                        এই কোর্সে আপনি পাচ্ছেন:
                                    </p>

                                    <ul class="grid grid-cols-1 gap-2.5">
                                        @if($course->course_includes && count($course->course_includes))
                                            @foreach($course->course_includes as $inc)
                                                <li class="flex items-start gap-2 text-xs text-slate-700 font-medium">
                                                    <span class="text-emerald-500 font-bold shrink-0 mt-0.5">✓</span>
                                                    <span>{{ is_array($inc) ? ($inc['item'] ?? '') : $inc }}</span>
                                                </li>
                                            @endforeach
                                        @else
                                            <li class="flex items-start gap-2 text-xs text-slate-700 font-medium">
                                                <span class="text-emerald-500 font-bold shrink-0 mt-0.5">✓</span>
                                                <span>{{ $course->modules->count() }} টি মডিউল ও সর্বমোট {{ $course->modules->sum(fn($m) => $m->lessons->count()) }} টি লেসন</span>
                                            </li>
                                            <li class="flex items-start gap-2 text-xs text-slate-700 font-medium">
                                                <span class="text-emerald-500 font-bold shrink-0 mt-0.5">✓</span>
                                                <span>লাইভ ক্লাস ও সাপোর্ট সেসন</span>
                                            </li>
                                            <li class="flex items-start gap-2 text-xs text-slate-700 font-medium">
                                                <span class="text-emerald-500 font-bold shrink-0 mt-0.5">✓</span>
                                                <span>লাইফটাইম এক্সেস ও সার্টিফিকেট</span>
                                            </li>
                                        @endif
                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>