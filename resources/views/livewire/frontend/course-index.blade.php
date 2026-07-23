<?php

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Review;
use Livewire\Volt\Component;

new class extends Component
{
    public $categories = [];
    public $featuredCourses = [];
    public $testimonials = [];
    public $stats = [];

    public function mount(): void
    {
        $this->categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->withCount('courses')
            ->get();

        $this->featuredCourses = Course::where('is_published', true)
            ->with(['category', 'instructor'])
            ->latest()
            ->take(8)
            ->get();

        $this->testimonials = Review::where('is_approved', true)
            ->where('rating', '>=', 4)
            ->with('user')
            ->latest()
            ->take(4)
            ->get();

        $this->stats = [
            'courses' => Course::where('is_published', true)->count(),
            'students' => Enrollment::distinct('user_id')->count('user_id'),
            'instructors' => Course::where('is_published', true)->whereNotNull('instructor_id')->distinct('instructor_id')->count('instructor_id'),
            'categories' => Category::where('is_active', true)->count(),
        ];
    }

    // Bengali digits keep the marketing copy visually consistent with the rest of the UI.
    public function bn(int|string $number): string
    {
        return str_replace(
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'],
            (string) $number
        );
    }
}
?>



<div>
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-brand-navy via-[#16406e] to-brand-blue text-white overflow-hidden">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-32 md:pt-20 md:pb-36 text-center relative z-10">
            <span class="inline-flex items-center gap-1.5 bg-white/15 text-xs sm:text-sm font-bold px-4 py-1.5 rounded-full mb-6">
                🎓 {{ $this->bn(number_format(max($stats['students'], 500))) }}+ শিক্ষার্থীর আস্থা
            </span>

            <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold leading-tight mb-4">
                বাংলাদেশের সবচেয়ে বড়<br>শিক্ষা প্ল্যাটফর্ম
            </h1>

            <p class="text-sm sm:text-base md:text-lg text-blue-100/90 mb-8 max-w-2xl mx-auto">
                Bangladesh's Biggest Education Platform — একাডেমিক থেকে ক্যারিয়ার, সব একসাথে
            </p>

            <form action="{{ route('courses.list') }}" method="GET" class="bg-white rounded-2xl p-2 flex items-center gap-2 max-w-xl mx-auto shadow-2xl shadow-black/20">
                <svg class="w-5 h-5 text-brand-blue ml-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"></path></svg>
                <input type="text" name="q" placeholder="কোর্স খুঁজুন — যেমন &quot;SSC&quot;, &quot;Excel&quot;, &quot;IELTS&quot;" class="flex-1 border-0 focus:ring-0 text-sm text-ostad-black placeholder-gray-400 bg-transparent min-w-0">
                <button type="submit" class="md-ripple shrink-0 bg-brand-navy hover:bg-brand-navy-light text-white px-5 sm:px-7 py-3 rounded-xl font-bold text-sm transition-colors">
                    খুঁজুন
                </button>
            </form>

            <div class="flex flex-wrap justify-center gap-2.5 mt-6">
                @foreach($categories->take(6) as $tag)
                    <a href="{{ route('courses.list', ['category' => $tag->id]) }}" class="md-ripple bg-white/10 hover:bg-white/20 text-xs sm:text-sm font-semibold px-4 py-2 rounded-full transition-colors">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Stats Bar (overlaps hero bottom) -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20 sm:-mt-24 relative z-10">
        <div class="bg-white rounded-2xl shadow-2xl shadow-brand-navy/10 p-6 sm:p-8 grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach([
                ['value' => $this->bn(number_format($stats['courses'])) . '+', 'label' => 'কোর্স'],
                ['value' => $this->bn(number_format(max($stats['students'], 500))) . '+', 'label' => 'শিক্ষার্থী'],
                ['value' => $this->bn(number_format(max($stats['instructors'], 20))) . '+', 'label' => 'ইন্সট্রাক্টর'],
                ['value' => $this->bn(number_format($stats['categories'])) . '+', 'label' => 'ক্যাটাগরি'],
            ] as $stat)
                <div class="text-center px-2">
                    <div class="text-2xl sm:text-3xl font-extrabold text-brand-navy">{{ $stat['value'] }}</div>
                    <div class="text-xs sm:text-sm text-gray-500 font-semibold mt-1">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Categories -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-4">
        <div class="text-center mb-10">
            <div class="text-xs sm:text-sm font-bold text-brand-blue mb-2 tracking-wide">ক্যাটাগরি</div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-brand-navy">আপনার যা প্রয়োজন, সবই এখানে</h2>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
            @forelse($categories as $category)
                <a href="{{ route('courses.list', ['category' => $category->id]) }}" class="md-ripple group bg-white border border-gray-200 rounded-2xl p-5 text-center shadow-elevation-1 hover:shadow-elevation-3 transition-shadow duration-200 ease-md-standard">
                    <div class="w-12 h-12 rounded-xl bg-brand-blue-light flex items-center justify-center mx-auto mb-3">
                        @if($category->icon)
                            @if(str_contains($category->icon, '<'))
                                <span class="inline-flex items-center justify-center text-brand-navy">{!! $category->icon !!}</span>
                            @elseif(str_contains($category->icon, 'fa-') || str_starts_with($category->icon, 'fa'))
                                <i class="{{ (str_contains($category->icon, 'fa-') && !str_contains($category->icon, 'fa-solid') && !str_contains($category->icon, 'fa-regular') && !str_contains($category->icon, 'fas ') && !str_contains($category->icon, 'far ') && !str_contains($category->icon, 'fab ')) ? 'fa-solid '.$category->icon : $category->icon }} text-lg text-brand-navy"></i>
                            @else
                                <span class="text-lg">{{ $category->icon }}</span>
                            @endif
                        @else
                            <svg class="w-6 h-6 text-brand-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0112 20.055a11.952 11.952 0 01-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                        @endif
                    </div>
                    <div class="text-sm font-bold text-ostad-black">{{ $category->name }}</div>
                    <div class="text-xs text-gray-400 mt-1">{{ $this->bn($category->courses_count) }} কোর্স</div>
                </a>
            @empty
                <div class="col-span-full text-center text-gray-400 italic py-10">কোনো ক্যাটাগরি নেই</div>
            @endforelse
        </div>
    </section>

    <!-- Featured Courses -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-4">
        <div class="flex items-end justify-between gap-4 mb-8">
            <div>
                <div class="text-xs sm:text-sm font-bold text-brand-blue mb-2 tracking-wide">ফিচারড কোর্স</div>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-brand-navy">জনপ্রিয় কোর্সসমূহ</h2>
            </div>
            <a href="{{ route('courses.list') }}" class="md-ripple shrink-0 inline-flex items-center gap-1.5 text-sm font-bold text-brand-navy border border-gray-200 hover:border-brand-blue rounded-full px-4 py-2 transition-colors">
                সব দেখুন
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($featuredCourses as $course)
                <a href="{{ route('courses.show', $course->slug) }}" class="md-ripple group flex flex-col bg-white rounded-2xl border border-gray-200 shadow-elevation-1 hover:shadow-elevation-3 transition-shadow duration-200 ease-md-standard overflow-hidden">
                    <div class="relative w-full aspect-video overflow-hidden bg-gray-100">
                        <img src="{{ $course->thumbnail ? asset('storage/'.$course->thumbnail) : 'https://ui-avatars.com/api/?name='.urlencode($course->title).'&background=0F3460&color=fff&size=600' }}" alt="{{ $course->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <div class="absolute top-2 left-2 bg-brand-navy/85 backdrop-blur-sm text-white text-xs font-bold px-2 py-1 rounded flex items-center gap-1 border border-white/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                            লাইভ
                        </div>
                    </div>
                    <div class="p-4 flex flex-col flex-grow">
                        @if($course->category)
                            <div class="text-[11px] text-brand-blue font-bold mb-1.5">{{ $course->category->name }}</div>
                        @endif
                        <h3 class="text-[15px] font-bold text-ostad-black mb-2 line-clamp-2 leading-snug min-h-[42px]">{{ $course->title }}</h3>
                        <div class="text-xs text-gray-400 mb-2.5 flex items-center gap-1.5">
                            <i class="fa-regular fa-circle-user"></i>
                            {{ $course->instructor?->name ?? 'SecondShiftBD' }}
                        </div>
                        <div class="flex items-center gap-1.5 text-xs mb-3">
                            <i class="fa-solid fa-star text-brand-gold"></i>
                            <span class="font-bold text-ostad-black">{{ $course->averageRating() > 0 ? $course->averageRating() : '৫.০' }}</span>
                            <span class="text-gray-400">({{ $this->bn($course->reviewsCount()) }})</span>
                        </div>
                        <div class="mt-auto flex items-baseline gap-2">
                            <span class="text-lg font-extrabold text-brand-navy">৳{{ number_format($course->price, 0) }}</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-300">
                    <svg class="w-14 h-14 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    <p class="text-lg text-ostad-black font-bold">বর্তমানে কোনো কোর্স পাওয়া যায়নি।</p>
                    <p class="text-gray-500 mt-1">শীঘ্রই নতুন কোর্স যুক্ত করা হবে।</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Testimonials -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-4">
        <div class="text-center mb-10">
            <div class="text-xs sm:text-sm font-bold text-brand-blue mb-2 tracking-wide">শিক্ষার্থীদের মতামত</div>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-brand-navy">তারা কী বলছেন</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @forelse($testimonials as $tm)
                <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-elevation-1">
                    <div class="text-brand-gold text-xs mb-3">
                        @for($i = 0; $i < 5; $i++)
                            <i class="fa-solid fa-star"></i>
                        @endfor
                    </div>
                    <p class="text-sm leading-relaxed text-gray-600 mb-5 line-clamp-4">"{{ $tm->comment }}"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-navy to-brand-blue flex items-center justify-center text-white font-extrabold text-sm shrink-0">
                            {{ mb_substr($tm->user?->name ?? 'S', 0, 1) }}
                        </div>
                        <div>
                            <div class="text-sm font-bold text-ostad-black">{{ $tm->user?->name ?? 'শিক্ষার্থী' }}</div>
                            <div class="text-xs text-gray-400">SecondShiftBD শিক্ষার্থী</div>
                        </div>
                    </div>
                </div>
            @empty
                @foreach([
                    ['quote' => 'SecondShiftBD-তে ভর্তি হয়ে আমার ক্যারিয়ারে বড় পরিবর্তন এসেছে। ইন্সট্রাক্টররা অসাধারণ সাপোর্ট দিয়েছেন।', 'name' => 'রাফসান আহমেদ'],
                    ['quote' => 'লাইভ ক্লাসের ইন্টারঅ্যাকটিভ পরিবেশ আমাকে দ্রুত স্কিল ডেভেলপ করতে সাহায্য করেছে।', 'name' => 'নুসরাত জাহান'],
                    ['quote' => 'যেকোনো জায়গা থেকে শেখার সুবিধা আর প্র্যাক্টিকাল প্রজেক্টগুলো সত্যিই কাজে দিয়েছে।', 'name' => 'তানভীর হাসান'],
                    ['quote' => 'সাশ্রয়ী মূল্যে মানসম্পন্ন শিক্ষা — SecondShiftBD সত্যিই আলাদা।', 'name' => 'সাদিয়া ইসলাম'],
                ] as $tm)
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-elevation-1">
                        <div class="text-brand-gold text-xs mb-3">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fa-solid fa-star"></i>
                            @endfor
                        </div>
                        <p class="text-sm leading-relaxed text-gray-600 mb-5">"{{ $tm['quote'] }}"</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-navy to-brand-blue flex items-center justify-center text-white font-extrabold text-sm shrink-0">
                                {{ mb_substr($tm['name'], 0, 1) }}
                            </div>
                            <div>
                                <div class="text-sm font-bold text-ostad-black">{{ $tm['name'] }}</div>
                                <div class="text-xs text-gray-400">SecondShiftBD শিক্ষার্থী</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endforelse
        </div>
    </section>

    <!-- Final CTA -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="bg-gradient-to-r from-brand-navy to-brand-blue rounded-3xl px-6 sm:px-10 py-14 text-center text-white">
            <h2 class="text-2xl sm:text-3xl font-extrabold mb-3">আজই আপনার শেখা শুরু করুন</h2>
            <p class="text-sm sm:text-base text-blue-100/90 mb-7">হাজারো শিক্ষার্থীর সাথে যুক্ত হয়ে গড়ুন উজ্জ্বল ভবিষ্যৎ</p>
            <button type="button" @click="$dispatch('open-auth-drawer')" class="md-ripple inline-flex items-center gap-2 bg-white text-brand-navy px-8 py-3.5 rounded-xl font-extrabold text-sm shadow-lg hover:-translate-y-0.5 transition-transform duration-200 ease-md-standard">
                ফ্রি একাউন্ট খুলুন
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </button>
        </div>
    </section>
</div>
