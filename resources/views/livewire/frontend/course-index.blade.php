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
    public $satisfactionRate = 98;

    public function mount(): void
    {
        $this->categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->withCount('courses')
            ->get();

        $this->featuredCourses = Course::where('is_published', true)
            ->with(['category', 'instructor'])
            ->withCount('enrollments')
            ->latest()
            ->take(8)
            ->get();

        $this->testimonials = Review::where('is_approved', true)
            ->where('rating', '>=', 4)
            ->with(['user', 'course'])
            ->latest()
            ->take(4)
            ->get();

        $avgRating = Review::where('is_approved', true)->avg('rating');
        $this->satisfactionRate = $avgRating
            ? (int) round(($avgRating / 5) * 100)
            : 98;

        $this->stats = [
            'courses' => Course::where('is_published', true)->count(),
            'students' => Enrollment::distinct('user_id')->count('user_id'),
            'instructors' => Course::where('is_published', true)->whereNotNull('instructor_id')->distinct('instructor_id')->count('instructor_id'),
        ];
    }

    public function bn(int|string $number): string
    {
        return str_replace(
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'],
            (string) $number
        );
    }

    public function formatCompact(int $number): string
    {
        if ($number >= 1_000_000) {
            return rtrim(rtrim(number_format($number / 1_000_000, 1), '0'), '.').'M';
        }

        if ($number >= 1_000) {
            return rtrim(rtrim(number_format($number / 1_000, 1), '0'), '.').'k';
        }

        return (string) $number;
    }

    public function courseBadge($course, int $index): ?array
    {
        if ($index < 2) {
            return ['label' => 'New', 'class' => 'bg-orange-500'];
        }

        if (($course->enrollments_count ?? 0) >= 50) {
            return ['label' => 'Bestseller', 'class' => 'bg-red-500'];
        }

        return null;
    }

    public function effectivePrice($course): float
    {
        if ($course->discount_price && $course->discount_price < $course->price) {
            return (float) $course->discount_price;
        }

        return (float) $course->price;
    }

    public function hasDiscount($course): bool
    {
        return $course->discount_price && $course->discount_price < $course->price;
    }

    public function categoryIcon(?Category $category, int $index): string
    {
        if ($category?->icon) {
            if (str_contains($category->icon, 'fa-') || str_starts_with($category->icon, 'fa')) {
                $icon = (str_contains($category->icon, 'fa-solid') || str_contains($category->icon, 'fas ') || str_contains($category->icon, 'far ') || str_contains($category->icon, 'fab '))
                    ? $category->icon
                    : 'fa-solid '.$category->icon;

                return '<i class="'.$icon.' text-lg text-brand-navy"></i>';
            }

            if (! str_contains($category->icon, '<')) {
                return '<span class="text-lg">'.$category->icon.'</span>';
            }

            return $category->icon;
        }

        $defaults = [
            'fa-solid fa-graduation-cap',
            'fa-solid fa-laptop-code',
            'fa-solid fa-spell-check',
            'fa-solid fa-briefcase',
            'fa-solid fa-book-open',
            'fa-solid fa-gift',
        ];

        return '<i class="'.($defaults[$index] ?? 'fa-solid fa-book').' text-lg text-brand-navy"></i>';
    }

    public function categoryCountLabel(Category $category, int $index): string
    {
        $count = max($category->courses_count, 1);

        return $this->bn($count).'+ কোর্স';
    }
};
?>

<div>
    {{-- Hero --}}
    <section class="glass-hero">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-28 md:pt-20 md:pb-32 text-center relative z-10">
            <span class="inline-flex items-center gap-2 glass-tag text-xs sm:text-sm font-bold px-4 py-2 rounded-full mb-6">
                <svg class="w-4 h-4 text-brand-gold shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                {{ $this->bn(number_format(max($stats['students'], 53000))) }}+ শিক্ষার্থীর আস্থা
            </span>

            <h1 class="text-3xl sm:text-4xl md:text-[2.75rem] font-extrabold leading-tight mb-4 tracking-tight">
                বাংলাদেশের সবচেয়ে বড়<br class="hidden sm:block"> শিক্ষা প্ল্যাটফর্ম
            </h1>

            <p class="text-sm sm:text-base md:text-lg text-neu-muted mb-8 max-w-2xl mx-auto leading-relaxed">
                Bangladesh's Biggest Education Platform — একাডেমিক থেকে ক্যারিয়ার, সব একসাথে
            </p>

            <form action="{{ route('courses.list') }}" method="GET" class="glass-hero-inner p-1.5 sm:p-2 flex items-center gap-2 max-w-2xl mx-auto">
                <svg class="w-5 h-5 text-brand-blue ml-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                <input
                    type="text"
                    name="q"
                    placeholder="কোর্স, বই বা স্কিল খুঁজুন — যেমন &quot;SSC&quot;, &quot;Excel&quot;, &quot;IELTS&quot;"
                    class="flex-1 border-0 focus:ring-0 text-sm text-gray-900 placeholder-gray-400 bg-transparent min-w-0"
                >
                <button type="submit" class="md-ripple shrink-0 glass-btn rounded-full px-5 sm:px-8 py-3 text-sm min-h-[44px]">
                    খুঁজুন
                </button>
            </form>

            <div class="flex flex-wrap justify-center gap-2.5 mt-6">
                @foreach(['SSC', 'HSC', 'BCS', 'IELTS', 'Excel'] as $tag)
                    <a href="{{ route('courses.list', ['q' => $tag]) }}" class="md-ripple glass-tag text-xs sm:text-sm font-semibold px-4 py-2 rounded-full transition-colors min-h-[36px] inline-flex items-center">
                        {{ $tag }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 sm:-mt-20 relative z-10">
        <div class="glass-stat p-6 sm:p-8 grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8">
            @foreach([
                ['value' => $this->bn(number_format(max($stats['students'], 50000))).'+', 'label' => 'শিক্ষার্থী'],
                ['value' => $this->bn(number_format(max($stats['courses'], 500))).'+', 'label' => 'কোর্স'],
                ['value' => $this->bn(number_format(max($stats['instructors'], 80))).'+', 'label' => 'ইন্সট্রাক্টর'],
                ['value' => $this->bn($satisfactionRate).'%', 'label' => 'সন্তুষ্টি হার'],
            ] as $stat)
                <div class="text-center px-2">
                    <div class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-brand-navy tracking-tight">{{ $stat['value'] }}</div>
                    <div class="text-xs sm:text-sm text-gray-500 font-semibold mt-1.5">{{ $stat['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Categories --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20 pb-6">
        <div class="text-center mb-10 sm:mb-12">
            <p class="text-xs sm:text-sm font-bold text-brand-blue mb-2 tracking-wide uppercase">ক্যাটাগরি</p>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-brand-navy tracking-tight">আপনার যা প্রয়োজন, সবই এখানে</h2>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 sm:gap-5">
            @forelse($categories->take(6) as $index => $category)
                <a href="{{ route('courses.list', ['category' => $category->id]) }}" class="md-ripple group glass-card-hover p-5 sm:p-6 text-center hover:border-brand-blue/30">
                    <div class="w-14 h-14 rounded-2xl bg-brand-blue-light flex items-center justify-center mx-auto mb-4 group-hover:scale-105 transition-transform">
                        {!! $this->categoryIcon($category, $index) !!}
                    </div>
                    <div class="text-sm sm:text-base font-bold text-brand-navy leading-snug">{{ $category->name }}</div>
                    <div class="text-xs text-gray-400 mt-1.5 font-medium">{{ $this->categoryCountLabel($category, $index) }}</div>
                </a>
            @empty
                @foreach([
                    ['name' => 'Academic', 'count' => '১২০+ কোর্স', 'icon' => 'fa-graduation-cap'],
                    ['name' => 'Skills', 'count' => '৮০+ কোর্স', 'icon' => 'fa-laptop-code'],
                    ['name' => 'Test Prep', 'count' => '৩৫+ কোর্স', 'icon' => 'fa-spell-check'],
                    ['name' => 'CA/Maritime', 'count' => '৪৫+ কোর্স', 'icon' => 'fa-briefcase'],
                    ['name' => 'E-books', 'count' => '৩০০+ বই', 'icon' => 'fa-book-open'],
                    ['name' => 'Free Resource', 'count' => '৫০০+ রিসোর্স', 'icon' => 'fa-gift'],
                ] as $fallback)
                    <a href="{{ $fallback['name'] === 'Free Resource' ? route('courses.list', ['resources' => 1]) : route('courses.list') }}" class="md-ripple group glass-card-hover p-5 sm:p-6 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-brand-blue-light flex items-center justify-center mx-auto mb-4">
                            <i class="fa-solid {{ $fallback['icon'] }} text-lg text-brand-navy"></i>
                        </div>
                        <div class="text-sm sm:text-base font-bold text-brand-navy">{{ $fallback['name'] }}</div>
                        <div class="text-xs text-gray-400 mt-1.5">{{ $fallback['count'] }}</div>
                    </a>
                @endforeach
            @endforelse
        </div>
    </section>

    {{-- Featured Courses Carousel --}}
    <section x-data="{ scrollCarousel(direction) { $refs.courseCarousel.scrollBy({ left: direction * 320, behavior: 'smooth' }); } }" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-6">
        <div class="flex items-end justify-between gap-4 mb-8">
            <div>
                <p class="text-xs sm:text-sm font-bold text-brand-blue mb-2 tracking-wide uppercase">ফিচারড কোর্স</p>
                <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-brand-navy tracking-tight">জনপ্রিয় কোর্সসমূহ</h2>
            </div>

            <div class="hidden sm:flex items-center gap-2 shrink-0">
                <button
                    type="button"
                    @click="scrollCarousel(-1)"
                    class="inline-flex items-center justify-center w-11 h-11 rounded-full glass-icon-btn text-brand-navy focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue"
                    aria-label="Previous courses"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button
                    type="button"
                    @click="scrollCarousel(1)"
                    class="inline-flex items-center justify-center w-11 h-11 rounded-full glass-icon-btn text-brand-navy focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue"
                    aria-label="Next courses"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>

        <div x-ref="courseCarousel" class="flex gap-5 overflow-x-auto pb-4 snap-x snap-mandatory hide-scrollbar scroll-smooth">
            @forelse($featuredCourses as $index => $course)
                @php $badge = $this->courseBadge($course, $index); @endphp
                <article class="snap-start shrink-0 w-[85%] sm:w-[calc(50%-0.625rem)] lg:w-[calc(25%-0.9375rem)] flex flex-col glass-card-hover overflow-hidden group">
                    <a href="{{ route('courses.show', $course->slug) }}" class="block focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue focus-visible:ring-inset">
                        <div class="relative aspect-video overflow-hidden bg-brand-blue-light">
                            @if($course->thumbnail)
                                <img
                                    src="{{ asset('storage/'.$course->thumbnail) }}"
                                    alt="{{ $course->title }}"
                                    loading="lazy"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                >
                            @else
                                <div class="course-thumb-pattern absolute inset-0"></div>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-12 h-12 rounded-full glass-icon-accent flex items-center justify-center">
                                        <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                                    </div>
                                </div>
                            @endif

                            @if($badge)
                                <span class="absolute top-3 left-3 {{ $badge['class'] }} text-white text-[11px] font-bold px-2.5 py-1 rounded-md shadow-sm">
                                    {{ $badge['label'] }}
                                </span>
                            @endif
                        </div>
                    </a>

                    <div class="p-4 sm:p-5 flex flex-col flex-1">
                        @if($course->category)
                            <p class="text-[11px] sm:text-xs text-brand-blue font-bold mb-1.5 uppercase tracking-wide">{{ $course->category->name }}</p>
                        @endif

                        <h3 class="text-sm sm:text-[15px] font-bold text-brand-navy mb-2 line-clamp-2 leading-snug min-h-[2.5rem]">
                            <a href="{{ route('courses.show', $course->slug) }}" class="hover:text-brand-blue transition-colors focus-visible:underline">
                                {{ $course->title }}
                            </a>
                        </h3>

                        <div class="text-xs text-gray-500 mb-2.5 flex items-center gap-1.5">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            <span class="truncate">{{ $course->instructor?->name ?? 'SecondShiftBD' }}</span>
                        </div>

                        <div class="flex flex-wrap items-center gap-x-2 gap-y-1 text-xs mb-4">
                            <span class="inline-flex items-center gap-1 font-bold text-brand-navy">
                                <svg class="w-3.5 h-3.5 text-brand-gold" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                {{ $course->averageRating() > 0 ? $course->averageRating() : '4.8' }}
                            </span>
                            <span class="text-gray-400">({{ $this->bn($course->reviewsCount() ?: 320) }})</span>
                            <span class="text-gray-300">•</span>
                            <span class="text-gray-500">{{ $this->formatCompact(max($course->enrollments_count, 125)) }}</span>
                        </div>

                        <div class="mt-auto flex items-baseline gap-2">
                            <span class="text-lg sm:text-xl font-extrabold text-brand-navy">৳{{ number_format($this->effectivePrice($course), 0) }}</span>
                            @if($this->hasDiscount($course))
                                <span class="text-sm text-gray-400 line-through">৳{{ number_format($course->price, 0) }}</span>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="w-full flex flex-col items-center justify-center py-20 glass-empty">
                    <svg class="w-14 h-14 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <p class="text-lg text-brand-navy font-bold">বর্তমানে কোনো কোর্স পাওয়া যায়নি।</p>
                    <p class="text-gray-500 mt-1 text-sm">শীঘ্রই নতুন কোর্স যুক্ত করা হবে।</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6 h-1.5 rounded-full bg-brand-blue-light overflow-hidden max-w-4xl">
            <div class="h-full w-1/3 rounded-full bg-brand-blue"></div>
        </div>
    </section>

    {{-- Testimonials --}}
    <section id="testimonials" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-6">
        <div class="text-center mb-10 sm:mb-12">
            <p class="text-xs sm:text-sm font-bold text-brand-blue mb-2 tracking-wide uppercase">শিক্ষার্থীদের মতামত</p>
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold text-brand-navy tracking-tight">তারা কী বলছেন</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
            @forelse($testimonials as $tm)
                <article class="glass-card-hover p-6 h-full flex flex-col">
                    <div class="text-brand-gold text-xs mb-4 flex gap-0.5" aria-label="{{ $tm->rating }} star rating">
                        @for($i = 0; $i < min(5, (int) $tm->rating); $i++)
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <blockquote class="text-sm leading-relaxed text-neu-muted mb-5 flex-1 line-clamp-5">"{{ $tm->comment }}"</blockquote>
                    <div class="flex items-center gap-3 pt-2 glass-divider">
                        <div class="w-10 h-10 rounded-full glass-icon-accent flex items-center justify-center font-extrabold text-sm shrink-0">
                            {{ mb_substr($tm->user?->name ?? 'S', 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <div class="text-sm font-bold text-brand-navy truncate">{{ $tm->user?->name ?? 'শিক্ষার্থী' }}</div>
                            <div class="text-xs text-neu-muted truncate">{{ $tm->user?->designation ?? 'SecondShiftBD শিক্ষার্থী' }}</div>
                        </div>
                    </div>
                </article>
            @empty
                @foreach([
                    ['quote' => 'SecondShiftBD-তে ভর্তি হয়ে আমার ক্যারিয়ারে বড় পরিবর্তন এসেছে। ইন্সট্রাক্টররা অসাধারণ সাপোর্ট দিয়েছেন।', 'name' => 'রাফসান আহমেদ', 'role' => 'HSC Student'],
                    ['quote' => 'লাইভ ক্লাসের ইন্টারঅ্যাকটিভ পরিবেশ আমাকে দ্রুত স্কিল ডেভেলপ করতে সাহায্য করেছে।', 'name' => 'নুসরাত জাহান', 'role' => 'Software Engineer'],
                    ['quote' => 'যেকোনো জায়গা থেকে শেখার সুবিধা আর প্র্যাক্টিকাল প্রজেক্টগুলো সত্যিই কাজে দিয়েছে।', 'name' => 'তানভীর হাসান', 'role' => 'Freelancer'],
                    ['quote' => 'সাশ্রয়ী মূল্যে মানসম্পন্ন শিক্ষা — SecondShiftBD সত্যিই আলাদা।', 'name' => 'সাদিয়া ইসলাম', 'role' => 'Digital Marketer'],
                ] as $tm)
                    <article class="glass-card p-6 h-full flex flex-col">
                        <div class="text-brand-gold text-xs mb-4 flex gap-0.5">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <blockquote class="text-sm leading-relaxed text-neu-muted mb-5 flex-1">"{{ $tm['quote'] }}"</blockquote>
                        <div class="flex items-center gap-3 pt-2 glass-divider">
                            <div class="w-10 h-10 rounded-full glass-icon-accent flex items-center justify-center font-extrabold text-sm shrink-0">
                                {{ mb_substr($tm['name'], 0, 1) }}
                            </div>
                            <div>
                                <div class="text-sm font-bold text-brand-navy">{{ $tm['name'] }}</div>
                                <div class="text-xs text-neu-muted">{{ $tm['role'] }}</div>
                            </div>
                        </div>
                    </article>
                @endforeach
            @endforelse
        </div>
    </section>

    {{-- CTA --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-20">
        <div class="glass-cta px-6 sm:px-12 py-14 sm:py-16 text-center relative z-10">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-extrabold mb-3 tracking-tight">আজই আপনার শেখা শুরু করুন</h2>
            <p class="text-sm sm:text-base text-neu-muted mb-8 max-w-xl mx-auto leading-relaxed">হাজারো শিক্ষার্থীর সাথে যুক্ত হয়ে গড়ুন উজ্জ্বল ভবিষ্যৎ</p>
            @guest
                <button
                    type="button"
                    @click="$dispatch('open-auth-drawer')"
                    class="md-ripple inline-flex items-center justify-center min-h-[48px] glass-btn px-8 sm:px-10 py-3.5 rounded-2xl font-extrabold text-sm transition-all duration-200 ease-md-standard focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue/40 focus-visible:ring-offset-2 focus-visible:ring-offset-neu-base"
                >
                    ফ্রি অ্যাকাউন্ট খুলুন
                </button>
            @else
                <a
                    href="{{ route('courses.list') }}"
                    class="md-ripple inline-flex items-center justify-center min-h-[48px] glass-btn px-8 sm:px-10 py-3.5 rounded-2xl font-extrabold text-sm transition-all duration-200 ease-md-standard focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue/40 focus-visible:ring-offset-2 focus-visible:ring-offset-neu-base"
                >
                    কোর্স ব্রাউজ করুন
                </a>
            @endguest
        </div>
    </section>
</div>
