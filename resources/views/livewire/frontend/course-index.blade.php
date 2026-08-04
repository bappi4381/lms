<?php

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Review;
use App\Models\SubscriptionPlan;
use Livewire\Volt\Component;

use function Livewire\Volt\layout;

layout('layouts::pintar');

new class extends Component
{
    public $categories = [];
    public $featuredCourses = [];
    public $testimonials = [];
    public $subscriptionPlans = [];
    public $stats = [];
    public $satisfactionRate = 98;
    public $totalReviews = 0;

    public function mount(): void
    {
        $this->categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->with(['children.children'])
            ->orderBy('order')
            ->get();

        $this->featuredCourses = Course::where('is_published', true)
            ->with(['category', 'instructor', 'modules.lessons'])
            ->withCount('enrollments')
            ->latest()
            ->take(12)
            ->get();

        $this->testimonials = Review::where('is_approved', true)
            ->where('rating', '>=', 4)
            ->with(['user', 'course'])
            ->latest()
            ->take(6)
            ->get();

        $avgRating = Review::where('is_approved', true)->avg('rating');
        $this->totalReviews = Review::where('is_approved', true)->count();
        $this->satisfactionRate = $avgRating
            ? (int) round(($avgRating / 5) * 100)
            : 98;

        $this->stats = [
            'courses' => Course::where('is_published', true)->count(),
            'students' => Enrollment::distinct('user_id')->count('user_id'),
            'instructors' => Course::where('is_published', true)->whereNotNull('instructor_id')->distinct('instructor_id')->count('instructor_id'),
        ];

        $this->subscriptionPlans = SubscriptionPlan::where('is_active', true)
            ->orderBy('price')
            ->take(3)
            ->get();
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
            return rtrim(rtrim(number_format($number / 1_000, 1), '0'), '.').'K';
        }

        return (string) $number;
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

    public function lessonCount($course): int
    {
        if (! $course->relationLoaded('modules')) {
            return 0;
        }

        return (int) $course->modules->sum(fn ($module) => $module->lessons->count());
    }

    public function initials(?string $name): string
    {
        if (! $name) {
            return 'SS';
        }

        $parts = preg_split('/\s+/', trim($name));

        return strtoupper(substr($parts[0], 0, 1).substr($parts[1] ?? '', 0, 1));
    }
};
?>

@php
    $cms = \App\Models\SiteSetting::getSettings();
    $isBn = app()->getLocale() === 'bn';
    $avgRatingDisplay = number_format($satisfactionRate / 20, 1);
    $courseCount = max($stats['courses'], 150);
    $studentCount = max($stats['students'], 200000);
    $pricingFeatures = [
        'Limited access to all courses',
        '24/7 priority support via chat, email & phone',
        'Dedicated mentor for personalized guidance',
        'Basic mentor session',
        'Unlimited access to all courses, incl. exclusive ones',
        'Premium resources, toolkits & downloadable materials',
        'Unlimited access for the full subscription duration',
    ];
    // Feature cards: from CMS if available, fallback to hardcoded
    $defaultStrokeColors = ['#FF7A2E', '#1C726F', '#E06524', '#14615F'];
    $defaultBgColors = ['rgba(255,122,46,.12)', 'rgba(28,114,111,.12)', 'rgba(255,209,119,.3)', 'rgba(14,97,95,.12)'];
    $rawWhyusCards = $cms->whyus_cards ?? [];
    $featureCards = !empty($rawWhyusCards) ? array_map(fn($c, $i) => [
        'bg'     => $defaultBgColors[$i % 4],
        'stroke' => $defaultStrokeColors[$i % 4],
        'title'  => $isBn ? ($c['title_bn'] ?? $c['title_en'] ?? '') : ($c['title_en'] ?? $c['title_bn'] ?? ''),
        'desc'   => $isBn ? ($c['desc_bn']  ?? $c['desc_en']  ?? '') : ($c['desc_en']  ?? $c['desc_bn']  ?? ''),
    ], $rawWhyusCards, array_keys($rawWhyusCards)) : [
        ['bg' => 'rgba(255,122,46,.12)', 'stroke' => '#FF7A2E', 'title' => $isBn ? 'ইন্টারেক্টিভ শিক্ষা'   : 'Interactive Learning', 'desc' => $isBn ? 'ভিডিও, কুইজ ও অ্যাসাইনমেন্টে হাতে-কলমে অনুশীলন।' : 'Video, quizzes, and assignments with hands-on practice.'],
        ['bg' => 'rgba(28,114,111,.12)',  'stroke' => '#1C726F', 'title' => $isBn ? 'বিশেষজ্ঞ শিক্ষক'       : 'Expert Instructors',   'desc' => $isBn ? 'শিল্পে অভিজ্ঞ মেন্টররা প্রতিটি ধাপে গাইড করেন।' : 'Industry-experienced mentors guide you every step.'],
        ['bg' => 'rgba(255,209,119,.3)',  'stroke' => '#E06524', 'title' => $isBn ? 'নমনীয় সময়সূচি'        : 'Flexible Schedules',   'desc' => $isBn ? 'নিজের গতিতে শিখুন — যেকোনো সময়, যেকোনো ডিভাইসে।' : 'Learn at your own pace — anytime, on any device.'],
        ['bg' => 'rgba(14,97,95,.12)',    'stroke' => '#14615F', 'title' => $isBn ? 'সাশ্রয়ী মূল্য'          : 'Affordable Pricing',   'desc' => $isBn ? 'সকলের জন্য নমনীয় মূল্য ও সাবস্ক্রিপশন অপশন।' : 'Flexible pricing and subscription options for everyone.'],
    ];
    $categoryIcons = [
        '<path d="M9 17l-5-5 5-5M15 7l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        '<path d="M4 7h16v11H4z" stroke="currentColor" stroke-width="1.6"/><path d="M9 7V5.5A1.5 1.5 0 0110.5 4h3A1.5 1.5 0 0115 5.5V7" stroke="currentColor" stroke-width="1.6"/>',
        '<circle cx="12" cy="8" r="3.2" stroke="currentColor" stroke-width="1.6"/><path d="M5 20c1-3.5 4-5.5 7-5.5s6 2 7 5.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>',
        '<path d="M4 19V6a2 2 0 012-2h9l5 5v10a2 2 0 01-2 2H6a2 2 0 01-2-2z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M14 4v5h5" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>',
    ];
    $categoryIconBgs    = ['rgba(255,255,255,.14)', 'rgba(255,122,46,.12)', 'rgba(28,114,111,.12)', 'rgba(255,209,119,.25)'];
    $categoryIconColors = ['#FFD177', '#FF7A2E', '#1C726F', '#E06524'];
    $fallbackTestimonials = [
        ['quote' => 'Every module was a journey of discovery. The engaging content empowered me to approach challenges at work with newfound confidence.', 'name' => "Michael O'Brien", 'role' => 'Business Analyst', 'initials' => 'MO', 'bg' => '#1C726F'],
        ['quote' => 'A genuine game changer for professionals — the lessons are practical, the pacing is smart, and mentor feedback made all the difference.', 'name' => 'James Border', 'role' => 'Senior Marketing', 'initials' => 'JB', 'bg' => '#FF7A2E'],
        ['quote' => 'Engaging and empowering from lesson one. The interactive exercises made even harder topics click almost immediately.', 'name' => 'Indra Scopee', 'role' => 'Graphic Designer', 'initials' => 'IS', 'bg' => '#E06524'],
        ['quote' => 'A game changer for professionals — every module built on the last, and mentor sessions kept me accountable.', 'name' => 'Sukay Negara', 'role' => 'Business Analyst', 'initials' => 'SN', 'bg' => '#14615F'],
        ['quote' => 'Every module was a journey of discovery. Actionable insights gave me real confidence to lead projects at work.', 'name' => "Michael O'Brien", 'role' => 'Senior Marketing', 'initials' => 'MO', 'bg' => '#FFD177', 'text' => '#0E1E1D'],
        ['quote' => 'Engaging content, clear structure, and an instructor team that actually answers your questions.', 'name' => 'Rico Saputra', 'role' => 'Graphic Designer', 'initials' => 'RS', 'bg' => '#1D7270'],
    ];
    $fallbackPlans = [
        ['name' => 'Basic Package', 'price' => 50, 'interval' => 'month', 'features' => [0, 1, 2, 3]],
        ['name' => 'Premium Package', 'price' => 150, 'interval' => 'month', 'popular' => true, 'features' => [4, 1, 2, 5]],
        ['name' => 'Complete Package', 'price' => 300, 'interval' => 'month', 'features' => [4, 2, 5, 6]],
    ];
@endphp

<div
    x-data="{
        initReveal() {
            const els = this.$root.querySelectorAll('.pintar-home-reveal');
            const io = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in');
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.15 });
            els.forEach(el => io.observe(el));
        }
    }"
    x-init="initReveal()"
>
    {{-- Hero --}}
    <section class="pintar-home-hero">
        <div class="pintar-home-container pintar-home-hero-grid">
            <div class="pintar-home-reveal">
                <div class="pintar-home-eyebrow">{{ $cms->hero_eyebrow }}</div>
                <h1>{{ $cms->hero_title }}<br><span>{{ $cms->hero_highlight }}</span></h1>
                <p class="pintar-home-hero-copy">{{ $cms->hero_description }}</p>
                <div class="pintar-home-hero-ctas">
                    <a href="#courses" class="pintar-home-btn pintar-home-btn-primary">
                        {{ $cms->hero_btn_primary }}
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <div class="pintar-home-play-inline">
                        <span class="pintar-home-play-btn-sm" aria-hidden="true"></span>
                        {{ $cms->hero_btn_secondary }}
                    </div>
                </div>
                <div class="pintar-home-trust-row">
                    <div class="pintar-home-trust-item">
                        <strong>{{ $this->bn($courseCount) }}+</strong>
                        <span>Video courses</span>
                    </div>
                    <div class="pintar-home-trust-item">
                        <strong>{{ $this->bn($avgRatingDisplay) }}/5</strong>
                        <span>Average rating</span>
                    </div>
                    <div class="pintar-home-trust-item">
                        <strong>{{ $this->bn($this->formatCompact($studentCount)) }}+</strong>
                        <span>Active students</span>
                    </div>
                </div>
            </div>

            <div class="pintar-home-hero-visual pintar-home-reveal">
                <div class="pintar-home-hero-card">
                    <span class="pintar-home-hero-blob b1"></span>
                    <span class="pintar-home-hero-blob b2"></span>
                    <div class="pintar-home-hero-figure"></div>
                    <button type="button" class="pintar-home-hero-play-main" aria-label="Watch intro video">
                        <span class="pintar-home-hero-play-ring"></span>
                    </button>
                </div>
                <div class="pintar-home-badge-chip c1">
                    <span class="pintar-home-badge-icon" style="background:rgba(255,122,46,.12)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 3l9 5-9 5-9-5 9-5z" stroke="#FF7A2E" stroke-width="1.6" stroke-linejoin="round"/><path d="M6 11v5c0 1.5 2.7 3 6 3s6-1.5 6-3v-5" stroke="#FF7A2E" stroke-width="1.6"/></svg>
                    </span>
                    <span><strong>{{ $this->bn(max($courseCount, 320)) }}+</strong><small>Certified courses</small></span>
                </div>
                <div class="pintar-home-badge-chip c2">
                    <span class="pintar-home-badge-icon" style="background:rgba(28,114,111,.12)">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 4h16v12H8l-4 4V4z" stroke="#1C726F" stroke-width="1.6" stroke-linejoin="round"/></svg>
                    </span>
                    <span><strong>24/7</strong><small>Mentor support</small></span>
                </div>
            </div>
        </div>
    </section>

    {{-- About --}}
    <section id="about" class="pintar-home-section">
        <div class="pintar-home-container pintar-home-about-grid">
            <div class="pintar-home-about-visual pintar-home-reveal">
                <div class="pintar-home-collage"><div class="pintar-home-collage-inner"></div></div>
                <div class="pintar-home-stat-card">
                    <div class="pintar-home-avatar-stack">
                        <span class="avatar" style="background:#1C726F">MO</span>
                        <span class="avatar" style="background:#FF7A2E">JB</span>
                        <span class="avatar" style="background:#FFD177;color:#0E1E1D">IS</span>
                    </div>
                    <div class="txt">
                        <strong>Students Happy!</strong>
                        <span class="stars">★★★★★ {{ $avgRatingDisplay }} average</span>
                    </div>
                </div>
            </div>
            <div class="pintar-home-about-copy pintar-home-reveal">
                <div class="pintar-home-eyebrow">{{ $cms->about_eyebrow }}</div>
                <h2>{{ $cms->about_title }}</h2>
                <p>{{ $cms->about_description }}</p>
                <a href="#courses" class="pintar-home-btn-ghost">
                    {{ $cms->about_btn }}
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4" stroke="#E06524" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Popular Courses Section (Theme Integrated) --}}
    <section id="courses" class="pintar-home-section">
        <div class="pintar-home-container">
            
            {{-- Header Row (Testimonials-style) --}}
            <div class="pintar-home-testi-head">
                <div class="pintar-home-section-head pintar-home-reveal" style="margin-bottom:0;">
                    <div class="pintar-home-eyebrow">{{ __('course.featured_courses') }}</div>
                    <h2>{{ __('course.popular_courses') }}</h2>
                </div>
                <div class="pintar-home-testi-controls pintar-home-reveal">
                    <button type="button" class="pintar-home-testi-nav-btn"
                            @click="$refs.slider.scrollBy({ left: -300, behavior: 'smooth' })"
                            aria-label="Previous">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M10 3L5 8l5 5" stroke="#0E1E1D" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <button type="button" class="pintar-home-testi-nav-btn"
                            @click="$refs.slider.scrollBy({ left: 300, behavior: 'smooth' })"
                            aria-label="Next">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M6 3l5 5-5 5" stroke="#0E1E1D" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </div>
            </div>

            {{-- 8 Courses Cards Grid / Slider --}}
            <div x-ref="slider" 
                 class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"
                 style="overflow-x: auto; scroll-behavior: smooth; scrollbar-width: none; -ms-overflow-style: none;">
                @forelse($featuredCourses->take(8) as $index => $course)
                    @php
                        $effectivePrice = $this->effectivePrice($course);
                        $hasDiscount = $this->hasDiscount($course);
                        $badgeText = ($index % 2 === 0) ? __('course.bestseller') : __('course.new_badge');
                        $badgeBg = ($index % 2 === 0) ? 'bg-rose-500' : 'bg-amber-500';
                    @endphp
                    <div wire:key="home-course-{{ $course->id }}" class="w-full flex flex-col">
                        
                        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs hover:shadow-md hover:border-slate-300 transition-all duration-300 flex flex-col h-full overflow-hidden group">
                            
                            {{-- Fixed Height Thumbnail Container --}}
                            <div class="relative w-full overflow-hidden bg-slate-900" style="height: 175px;">
                                @if($course->thumbnail)
                                    <img src="{{ asset('storage/'.$course->thumbnail) }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-slate-700 via-slate-800 to-slate-900 flex items-center justify-center relative">
                                        <div class="absolute inset-0 bg-[radial-gradient(#e2e8f0_1px,transparent_1px)] [background-size:16px_16px] opacity-20"></div>
                                    </div>
                                @endif

                                {{-- Top Left Badge --}}
                                <div class="absolute top-3 left-3 z-10">
                                    <span class="{{ $badgeBg }} text-white text-[11px] font-extrabold px-2.5 py-0.5 rounded-full shadow-sm">
                                        {{ $badgeText }}
                                    </span>
                                </div>

                                {{-- Center Play Icon Overlay --}}
                                <a href="{{ route('courses.show', $course->slug) }}" class="absolute inset-0 bg-slate-900/20 group-hover:bg-slate-900/30 transition-colors flex items-center justify-center">
                                    <div class="w-10 h-10 rounded-full bg-white/90 group-hover:bg-white text-slate-800 flex items-center justify-center shadow-md group-hover:scale-110 transition-all duration-300 pl-0.5">
                                        <svg class="w-4 h-4 fill-current text-slate-800" viewBox="0 0 24 24">
                                            <path d="M8 5v14l11-7z"/>
                                        </svg>
                                    </div>
                                </a>
                            </div>

                            {{-- Card Body --}}
                            <div class="p-4 flex flex-col justify-between flex-1 space-y-3">
                                <div>
                                    {{-- Category Name --}}
                                    <span class="text-xs font-extrabold text-sky-600 tracking-wide uppercase">
                                        {{ $course->category->name ?? __('nav.main_types.academic') }}
                                    </span>

                                    {{-- Course Title --}}
                                    <h3 class="text-sm font-extrabold text-slate-900 line-clamp-2 leading-snug mt-1 group-hover:text-sky-700 transition-colors min-h-[2.5rem]">
                                        <a href="{{ route('courses.show', $course->slug) }}">
                                            {{ $course->title }}
                                        </a>
                                    </h3>

                                    {{-- Instructor Name --}}
                                    <p class="text-xs font-semibold text-slate-500 mt-1 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <span>{{ $course->instructor?->name ?? 'Rahim Ahmed' }}</span>
                                    </p>
                                </div>

                                <div>
                                    {{-- Rating & Student Count --}}
                                    <div class="flex items-center gap-1.5 text-xs text-slate-500 font-semibold mb-2">
                                        <span class="text-amber-500 font-bold flex items-center gap-0.5">
                                            ★ {{ $course->averageRating() > 0 ? number_format($course->averageRating(), 1) : '4.8' }}
                                        </span>
                                        <span class="text-slate-400">({{ $course->reviewsCount() > 0 ? $course->reviewsCount() : '320' }})</span>
                                        <span class="text-slate-300">•</span>
                                        <span>{{ $course->enrollments_count > 0 ? number_format($course->enrollments_count) : '12.5k' }}</span>
                                    </div>

                                    {{-- Price Display --}}
                                    <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                                        <div class="flex items-baseline gap-2">
                                            <span class="text-base font-black text-slate-900">৳{{ number_format($effectivePrice, 0) }}</span>
                                            @if($hasDiscount && isset($course->price))
                                                <span class="text-xs text-slate-400 line-through font-semibold">৳{{ number_format($course->price, 0) }}</span>
                                            @endif
                                        </div>

                                        <a href="{{ route('courses.show', $course->slug) }}" 
                                           class="text-xs font-bold text-sky-600 hover:text-sky-700 flex items-center gap-1 transition-colors">
                                            <span>{{ __('course.watch_btn') }}</span>
                                            <svg class="w-3.5 h-3.5 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-slate-400 text-sm">
                        {{ __('course.no_lessons') }}
                    </div>
                @endforelse
            </div>

            {{-- Bottom CTA --}}
            <div class="pintar-home-dots" style="margin-top:2.5rem;">
                <a href="{{ route('courses.list') }}" class="pintar-home-btn-ghost">
                    {{ __('course.view_all_courses') }}
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4" stroke="#E06524" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>

        </div>
    </section>

    {{-- Categories --}}
    @php
        $isBn = app()->getLocale() === 'bn';

        $sections = [
            [
                'key' => 'academic',
                'name_bn' => 'একাডেমিক',
                'name_en' => 'Academic',
                'bg' => 'var(--pastel-sky, #e8f4f8)',
                'color' => 'var(--brand-teal, #1d7270)',
                'svg' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>',
                'fallback_bn' => '১২০+ কোর্স',
                'fallback_en' => '120+ Courses',
            ],
            [
                'key' => 'skills',
                'name_bn' => 'স্কিলস',
                'name_en' => 'Skills',
                'bg' => 'var(--pastel-peach, #fdf0e9)',
                'color' => 'var(--brand-orange-soft, #e06524)',
                'svg' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/><polyline points="7 8 10 11 7 14"/><line x1="13" y1="14" x2="17" y2="14"/></svg>',
                'fallback_bn' => '৮০+ কোর্স',
                'fallback_en' => '80+ Courses',
            ],
            [
                'key' => 'test_prep',
                'name_bn' => 'টেস্ট প্রস্তুতি',
                'name_en' => 'Test Prep',
                'bg' => 'var(--pastel-mint, #e0f0ed)',
                'color' => 'var(--brand-teal, #1d7270)',
                'svg' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10M7 12h10M7 16h6"/></svg>',
                'fallback_bn' => '৩৫+ কোর্স',
                'fallback_en' => '35+ Courses',
            ],
            [
                'key' => 'professional',
                'name_bn' => 'CA/মেরিটাইম',
                'name_en' => 'CA / Professional',
                'bg' => 'var(--pastel-rose, #fce8ec)',
                'color' => 'var(--brand-orange-soft, #e06524)',
                'svg' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>',
                'fallback_bn' => '৪৫+ কোর্স',
                'fallback_en' => '45+ Courses',
            ],
            [
                'key' => 'ebooks',
                'name_bn' => 'ই-বুকস',
                'name_en' => 'E-Books',
                'bg' => 'var(--pastel-sky, #e8f4f8)',
                'color' => 'var(--brand-teal, #1d7270)',
                'svg' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>',
                'fallback_bn' => '৩০০+ বই',
                'fallback_en' => '300+ Books',
            ],
            [
                'key' => 'free',
                'name_bn' => 'ফ্রি রিসোর্স',
                'name_en' => 'Free Resources',
                'bg' => 'var(--pastel-peach, #fdf0e9)',
                'color' => 'var(--brand-orange-soft, #e06524)',
                'svg' => '<svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 12 20 22 4 22 4 12"/><rect x="2" y="7" width="20" height="5"/><line x1="12" y1="22" x2="12" y2="7"/><path d="M12 7H7.5a2.5 2.5 0 0 1 0-5C11 2 12 7 12 7z"/><path d="M12 7h4.5a2.5 2.5 0 0 0 0-5C13 2 12 7 12 7z"/></svg>',
                'fallback_bn' => '৫০+ রিসোর্স',
                'fallback_en' => '500+ Resources',
            ],
        ];

        $displayCards = [];

        foreach ($sections as $sec) {
            $sumCount = App\Models\Category::totalCoursesCountForMainType($sec['key']);
            
            if ($sumCount > 0) {
                $subText = $isBn ? ($this->bn($sumCount) . '+ কোর্স') : ($sumCount . '+ Courses');
            } else {
                $subText = $isBn ? $sec['fallback_bn'] : $sec['fallback_en'];
            }

            $firstCat = App\Models\Category::where('main_type', $sec['key'])->whereNull('parent_id')->first();
            $url = $firstCat 
                ? route('courses.list', ['category' => $firstCat->id])
                : route('courses.list');

            $displayCards[] = [
                'title' => $isBn ? $sec['name_bn'] : $sec['name_en'],
                'sub' => $subText,
                'url' => $url,
                'bg' => $sec['bg'],
                'color' => $sec['color'],
                'icon' => $sec['svg'],
            ];
        }
    @endphp

    <section class="pintar-home-section">
        <div class="pintar-home-container">
            <div class="pintar-home-why-top">
                <div class="pintar-home-section-head pintar-home-reveal" style="margin-bottom:0;">
                    <div class="pintar-home-eyebrow">{{ $isBn ? 'ক্যাটাগরি' : 'Category' }}</div>
                    <h2>
                        {{ $isBn ? 'আপনার যা প্রয়োজন, সবই এখানে' : 'Everything You Need Is Right Here' }}
                    </h2>
                </div>
                <a href="{{ route('courses.list') }}" class="pintar-home-btn-ghost pintar-home-reveal">
                    {{ $isBn ? 'সব কোর্স দেখুন' : 'See All Courses' }}
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4" stroke="#E06524" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 sm:gap-5">
                @foreach($displayCards as $card)
                    <a href="{{ $card['url'] }}" 
                       class="group bg-white rounded-2xl p-5 border border-black/5 shadow-[0_4px_20px_-4px_rgba(0,0,0,0.04)] hover:shadow-lg transition-all duration-300 flex flex-col items-center text-center cursor-pointer hover:-translate-y-1">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl flex items-center justify-center mb-4 transition-all duration-300 shadow-sm"
                             style="background-color: {{ $card['bg'] }}; color: {{ $card['color'] }};">
                            @if(str_starts_with(trim($card['icon']), '<svg'))
                                {!! $card['icon'] !!}
                            @else
                                <span class="text-2xl">{{ $card['icon'] }}</span>
                            @endif
                        </div>
                        <h3 class="font-bold text-sm sm:text-base transition-colors mb-1 group-hover:opacity-80" style="color: var(--brand-navy, #0e1e1d);">
                            {{ $card['title'] }}
                        </h3>
                        <p class="text-xs sm:text-sm font-medium" style="color: var(--on-surface-muted, #6e767d);">
                            {{ $card['sub'] }}
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Why Choose Us --}}
    <section class="pintar-home-section">
        <div class="pintar-home-container">
            <div class="pintar-home-why-top">
                <div class="pintar-home-section-head pintar-home-reveal" style="margin-bottom:0;">
                    <div class="pintar-home-eyebrow">{{ $cms->whyus_eyebrow }}</div>
                    <h2>{{ $cms->whyus_title }}</h2>
                </div>
                <a href="{{ route('courses.list') }}" class="pintar-home-btn-ghost pintar-home-reveal">
                    Learn more
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4" stroke="#E06524" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>

            <div class="pintar-home-feature-grid">
                @foreach($featureCards as $feature)
                    <article class="pintar-home-feature-card pintar-home-reveal">
                        <span class="pintar-home-icon-circle" style="background:{{ $feature['bg'] }}">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M4 5h16v11H8l-4 4V5z" stroke="{{ $feature['stroke'] }}" stroke-width="1.7" stroke-linejoin="round"/></svg>
                        </span>
                        <h3>{{ $feature['title'] }}</h3>
                        <p>{{ $feature['desc'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Pricing --}}
    <section id="pricing" class="pintar-home-section">
        <div class="pintar-home-container">
            <div class="pintar-home-section-head center pintar-home-reveal">
                <div class="pintar-home-eyebrow">{{ $cms->pricing_eyebrow }}</div>
                <h2>{{ $cms->pricing_title }}</h2>
            </div>

            <div class="pintar-home-pricing-grid">
                @forelse($subscriptionPlans as $index => $plan)
                    @if($index === 1)
                        <div class="pintar-home-price-card popular pintar-home-reveal">
                            <span class="pintar-home-popular-flag">Popular</span>
                            <h3>{{ $plan->name }}</h3>
                            <p class="pintar-home-tier-desc">{{ $plan->description ?? 'Serious learners, industry professionals, and those seeking comprehensive career development.' }}</p>
                            <div class="pintar-home-price-value">
                                <strong>৳{{ number_format($plan->price, 0) }}</strong>
                                <span>/ {{ $plan->interval === 'yearly' ? 'year' : 'month' }}</span>
                            </div>
                            @auth
                                <form method="POST" action="{{ route('payment.checkout-subscription', $plan) }}">
                                    @csrf
                                    <button type="submit" class="pintar-home-btn pintar-home-btn-primary">Start Learn</button>
                                </form>
                            @else
                                <button type="button" @click="$dispatch('open-auth-drawer')" class="pintar-home-btn pintar-home-btn-primary">Start Learn</button>
                            @endauth
                            <ul class="pintar-home-tier-list">
                                @foreach(array_slice($pricingFeatures, 4, 4) as $feat)
                                    <li><svg width="15" height="15" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8.5l3 3 7-7" stroke="#FFD177" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>{{ $feat }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="pintar-home-price-card pintar-home-reveal">
                            <h3>{{ $plan->name }}</h3>
                            <p class="pintar-home-tier-desc">{{ $plan->description ?? 'Serious learners, industry professionals, and those seeking comprehensive career development.' }}</p>
                            <div class="pintar-home-price-value">
                                <strong>৳{{ number_format($plan->price, 0) }}</strong>
                                <span>/ {{ $plan->interval === 'yearly' ? 'year' : 'month' }}</span>
                            </div>
                            @auth
                                <form method="POST" action="{{ route('payment.checkout-subscription', $plan) }}">
                                    @csrf
                                    <button type="submit" class="pintar-home-btn pintar-home-btn-outline">Start Learn</button>
                                </form>
                            @else
                                <button type="button" @click="$dispatch('open-auth-drawer')" class="pintar-home-btn pintar-home-btn-outline">Start Learn</button>
                            @endauth
                            <ul class="pintar-home-tier-list">
                                @foreach(array_map(fn ($i) => $pricingFeatures[$i], $index === 0 ? [0, 1, 2, 3] : [4, 2, 5, 6]) as $feat)
                                    <li><svg width="15" height="15" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8.5l3 3 7-7" stroke="#1C726F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>{{ $feat }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                @empty
                    @foreach($fallbackPlans as $tier)
                        <div @class(['pintar-home-price-card', 'popular' => ! empty($tier['popular']), 'pintar-home-reveal'])>
                            @if(! empty($tier['popular']))
                                <span class="pintar-home-popular-flag">Popular</span>
                            @endif
                            <h3>{{ $tier['name'] }}</h3>
                            <p class="pintar-home-tier-desc">Serious learners, industry professionals, and those seeking comprehensive career development.</p>
                            <div class="pintar-home-price-value">
                                <strong>${{ $tier['price'] }}</strong>
                                <span>/ {{ $tier['interval'] }}</span>
                            </div>
                            <a href="{{ route('subscriptions.index') }}" class="pintar-home-btn {{ ! empty($tier['popular']) ? 'pintar-home-btn-primary' : 'pintar-home-btn-outline' }}">Start Learn</a>
                            <ul class="pintar-home-tier-list">
                                @foreach($tier['features'] as $featIndex)
                                    <li><svg width="15" height="15" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8.5l3 3 7-7" stroke="{{ ! empty($tier['popular']) ? '#FFD177' : '#1C726F' }}" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>{{ $pricingFeatures[$featIndex] }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    {{-- Mobile App --}}
    {{-- 
    <section class="pintar-home-section">
        <div class="pintar-home-container">
            <div class="pintar-home-app-section">
                <div class="pintar-home-container pintar-home-app-grid">
                    <div class="pintar-home-reveal">
                        <div class="pintar-home-eyebrow">Our mobile app</div>
                        <h2>Take learning on the go!</h2>
                        <p>Download our app today and access your courses anytime, anywhere. Available on iOS and Android.</p>
                        <div class="pintar-home-app-points">
                            <div class="pintar-home-app-point">
                                <span class="dot"><svg width="13" height="13" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8.5l3 3 7-7" stroke="#FFD177" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                                <span>FREE access to all courses — let's install our app!</span>
                            </div>
                            <div class="pintar-home-app-point">
                                <span class="dot"><svg width="13" height="13" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8.5l3 3 7-7" stroke="#FFD177" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                                <span>Discount up to 70% for the first year</span>
                            </div>
                            <div class="pintar-home-app-point">
                                <span class="dot"><svg width="13" height="13" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8.5l3 3 7-7" stroke="#FFD177" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                                <span>Courses stay up to date — always the newest content</span>
                            </div>
                        </div>
                        <a href="{{ route('courses.list') }}" class="pintar-home-btn pintar-home-btn-primary">
                            More category
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </a>
                        <div class="pintar-home-app-stat">
                            <strong>{{ $this->bn($this->formatCompact($studentCount)) }}+</strong>
                            <small>Active Students</small>
                        </div>
                    </div>

                    <div class="pintar-home-reveal">
                        <div class="pintar-home-phone">
                            <div class="pintar-home-phone-notch"></div>
                            <div class="pintar-home-phone-screen">
                                <div class="pintar-home-phone-app-bar">
                                    <strong>My Courses</strong>
                                    <span class="pintar-home-ring-progress"></span>
                                </div>
                                <div class="pintar-home-phone-card">
                                    <span class="sw" style="background:#1C726F"></span>
                                    <div class="meta"><strong>UI/UX Design</strong><small>Lesson 6 of 12</small><div class="pintar-home-phone-bar"><i style="width:55%"></i></div></div>
                                </div>
                                <div class="pintar-home-phone-card">
                                    <span class="sw" style="background:#FF7A2E"></span>
                                    <div class="meta"><strong>Digital Marketing</strong><small>Lesson 3 of 9</small><div class="pintar-home-phone-bar"><i style="width:30%"></i></div></div>
                                </div>
                                <div class="pintar-home-phone-card">
                                    <span class="sw" style="background:#FFD177"></span>
                                    <div class="meta"><strong>Spanish A1</strong><small>Lesson 8 of 10</small><div class="pintar-home-phone-bar"><i style="width:80%"></i></div></div>
                                </div>
                                <div class="pintar-home-store-pills">
                                    <span class="pintar-home-store-pill">App Store</span>
                                    <span class="pintar-home-store-pill">Google Play</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    --}}

    {{-- Testimonials --}}
    <section id="testimonials" class="pintar-home-section"
        x-data="{
            index: 0,
            perView: window.innerWidth <= 980 ? 1 : 3,
            cards: [],
            init() {
                this.cards = Array.from(this.$refs.track.children);
                this.buildDots();
                window.addEventListener('resize', () => {
                    this.perView = window.innerWidth <= 980 ? 1 : 3;
                    this.index = 0;
                    this.buildDots();
                    this.update();
                });
            },
            buildDots() {
                const pages = Math.max(1, this.cards.length - this.perView + 1);
                this.$refs.dots.innerHTML = '';
                for (let i = 0; i < pages; i++) {
                    const d = document.createElement('button');
                    d.type = 'button';
                    d.className = 'pintar-home-dot-btn' + (i === 0 ? ' active' : '');
                    d.setAttribute('aria-label', 'Go to slide ' + (i + 1));
                    d.addEventListener('click', () => { this.index = i; this.update(); });
                    this.$refs.dots.appendChild(d);
                }
            },
            update() {
                if (! this.cards.length) return;
                const cardWidth = this.cards[0].getBoundingClientRect().width + 26;
                this.$refs.track.style.transform = `translateX(-${this.index * cardWidth}px)`;
                Array.from(this.$refs.dots.children).forEach((d, i) => d.classList.toggle('active', i === this.index));
            },
            next() {
                const maxIndex = this.cards.length - this.perView;
                this.index = Math.min(this.index + 1, maxIndex);
                this.update();
            },
            prev() {
                this.index = Math.max(this.index - 1, 0);
                this.update();
            }
        }"
        x-init="init()"
    >
        <div class="pintar-home-container">
            <div class="pintar-home-testi-head">
                <div class="pintar-home-section-head pintar-home-reveal" style="margin-bottom:0;">
                    <div class="pintar-home-eyebrow">{{ $cms->testi_eyebrow }}</div>
                    <h2>{{ $cms->testi_title }}</h2>
                </div>
                <div class="pintar-home-testi-controls pintar-home-reveal">
                    <button type="button" class="pintar-home-testi-nav-btn" @click="prev()" aria-label="Previous">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M10 3L5 8l5 5" stroke="#0E1E1D" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                    <button type="button" class="pintar-home-testi-nav-btn" @click="next()" aria-label="Next">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M6 3l5 5-5 5" stroke="#0E1E1D" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>
                </div>
            </div>

            <div class="pintar-home-testi-viewport pintar-home-reveal">
                <div class="pintar-home-testi-track" x-ref="track">
                    @forelse($testimonials as $tmIndex => $tm)
                        @php
                            $avatarColors = ['#1C726F', '#FF7A2E', '#E06524', '#14615F', '#FFD177', '#1D7270'];
                            $bg = $avatarColors[$tmIndex % count($avatarColors)];
                            $textColor = $bg === '#FFD177' ? '#0E1E1D' : '#fff';
                        @endphp
                        <div class="pintar-home-testi-card">
                            <div class="pintar-home-testi-stars">★★★★★</div>
                            <p>"{{ $tm->comment }}"</p>
                            <div class="pintar-home-testi-person">
                                <span class="pintar-home-testi-avatar" style="background:{{ $bg }};color:{{ $textColor }}">{{ $this->initials($tm->user?->name) }}</span>
                                <div>
                                    <strong>{{ $tm->user?->name ?? 'Student' }}</strong>
                                    <small>{{ $tm->user?->designation ?? 'SecondShiftBD Learner' }}</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        @foreach($fallbackTestimonials as $tm)
                            <div class="pintar-home-testi-card">
                                <div class="pintar-home-testi-stars">★★★★★</div>
                                <p>"{{ $tm['quote'] }}"</p>
                                <div class="pintar-home-testi-person">
                                    <span class="pintar-home-testi-avatar" style="background:{{ $tm['bg'] }};color:{{ $tm['text'] ?? '#fff' }}">{{ $tm['initials'] }}</span>
                                    <div>
                                        <strong>{{ $tm['name'] }}</strong>
                                        <small>{{ $tm['role'] }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforelse
                </div>
            </div>
            <div class="pintar-home-dots" x-ref="dots"></div>
        </div>
    {{-- Call To Action Banner --}}
    <section class="pintar-home-section pt-4 pb-16">
        <div class="pintar-home-container">
            <div class="relative overflow-hidden py-14 px-6 sm:px-12 md:py-16 text-center text-white shadow-xl"
                 style="background: linear-gradient(155deg, var(--brand-navy, #0e1e1d) 0%, var(--brand-teal, #1d7270) 50%, var(--brand-teal-deep, #14615f) 100%); border-radius: 36px;">
                
                {{-- Decorative background glow effect --}}
                <div class="absolute -top-24 -left-24 w-72 h-72 rounded-full blur-3xl pointer-events-none" style="background: rgba(255, 255, 255, 0.08);"></div>
                <div class="absolute -bottom-24 -right-24 w-72 h-72 rounded-full blur-3xl pointer-events-none" style="background: rgba(29, 114, 112, 0.25);"></div>

                <div class="relative z-10 max-w-3xl mx-auto">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black tracking-tight text-white mb-3">
                        {{ __('course.cta_title') }}
                    </h2>
                    
                    <p class="text-slate-100/90 text-sm sm:text-base font-medium max-w-xl mx-auto mb-8 leading-relaxed">
                        {{ __('course.cta_subtitle') }}
                    </p>

                    <div>
                        @auth
                            <a href="{{ route('courses.list') }}" 
                               class="inline-flex items-center justify-center px-8 py-3.5 rounded-xl text-white font-bold text-base shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 cursor-pointer"
                               style="background-color: var(--brand-orange-soft, #e06524); shadow-color: rgba(224, 101, 36, 0.3);">
                                <span>{{ __('course.cta_btn_auth') }}</span>
                            </a>
                        @else
                            <button type="button" 
                                    @click="$dispatch('open-auth-drawer')"
                                    class="inline-flex items-center justify-center px-8 py-3.5 rounded-xl text-white font-bold text-base shadow-lg transition-all duration-300 transform hover:-translate-y-0.5 cursor-pointer"
                                    style="background-color: var(--brand-orange-soft, #e06524);">
                                <span>{{ __('course.cta_btn_guest') }}</span>
                            </button>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
