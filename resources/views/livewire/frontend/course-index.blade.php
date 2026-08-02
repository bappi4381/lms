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
            ->orderBy('order')
            ->withCount('courses')
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
    $featureCards = [
        ['bg' => 'rgba(255,122,46,.12)', 'stroke' => '#FF7A2E', 'title' => 'Interactive Learning', 'desc' => 'Video, quizzes, and assignments with hands-on practice.'],
        ['bg' => 'rgba(28,114,111,.12)', 'stroke' => '#1C726F', 'title' => 'Expert Instructors', 'desc' => 'Industry-experienced mentors guide you every step.'],
        ['bg' => 'rgba(255,209,119,.3)', 'stroke' => '#E06524', 'title' => 'Flexible Schedules', 'desc' => 'Learn at your own pace — anytime, on any device.'],
        ['bg' => 'rgba(14,97,95,.12)', 'stroke' => '#14615F', 'title' => 'Affordable Pricing', 'desc' => 'Flexible pricing and subscription options for everyone.'],
    ];
    $categoryIcons = [
        '<path d="M9 17l-5-5 5-5M15 7l5 5-5 5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>',
        '<path d="M4 7h16v11H4z" stroke="currentColor" stroke-width="1.6"/><path d="M9 7V5.5A1.5 1.5 0 0110.5 4h3A1.5 1.5 0 0115 5.5V7" stroke="currentColor" stroke-width="1.6"/>',
        '<circle cx="12" cy="8" r="3.2" stroke="currentColor" stroke-width="1.6"/><path d="M5 20c1-3.5 4-5.5 7-5.5s6 2 7 5.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>',
        '<path d="M4 19V6a2 2 0 012-2h9l5 5v10a2 2 0 01-2 2H6a2 2 0 01-2-2z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M14 4v5h5" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>',
    ];
    $categoryIconBgs = [
        'rgba(255,255,255,.14)',
        'rgba(255,122,46,.12)',
        'rgba(28,114,111,.12)',
        'rgba(255,209,119,.25)',
    ];
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
                <div class="pintar-home-eyebrow">See how our teachers learn</div>
                <h1>We provide<br><span>fun e-course</span></h1>
                <p class="pintar-home-hero-copy">Learn new skills the way that actually sticks — bite-sized lessons, live mentors, and a community that keeps you moving forward.</p>
                <div class="pintar-home-hero-ctas">
                    <a href="#courses" class="pintar-home-btn pintar-home-btn-primary">
                        View Courses
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4" stroke="#fff" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                    <div class="pintar-home-play-inline">
                        <span class="pintar-home-play-btn-sm" aria-hidden="true"></span>
                        Watch intro
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
                <div class="pintar-home-eyebrow">Abouts us</div>
                <h2>Founded in 2015</h2>
                <p>E-Learning Adventures is committed to transforming the traditional learning landscape. With a blend of engaging content, interactive exercises, and cutting-edge technology, we ensure every learner finds their path to success.</p>
                <a href="#courses" class="pintar-home-btn-ghost">
                    Learn more
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4" stroke="#E06524" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Popular Courses --}}
    <section id="courses" class="pintar-home-section" x-data="{ activeCategory: 'all' }">
        <div class="pintar-home-container">
            <div class="pintar-home-section-head pintar-home-reveal">
                <div class="pintar-home-eyebrow">Popular Courses</div>
                <h2>Course that change your life!</h2>
            </div>

            <div class="pintar-home-filter-row pintar-home-reveal">
                <button type="button" class="pintar-home-filter-pill" :class="activeCategory === 'all' ? 'active' : ''" @click="activeCategory = 'all'">All</button>
                @foreach($categories->take(5) as $category)
                    <button type="button" class="pintar-home-filter-pill" :class="activeCategory === '{{ $category->id }}' ? 'active' : ''" @click="activeCategory = '{{ $category->id }}'">{{ $category->name }}</button>
                @endforeach
            </div>

            <div class="pintar-home-course-grid">
                @forelse($featuredCourses->take(4) as $index => $course)
                    <div x-show="activeCategory === 'all' || activeCategory === '{{ $course->category_id }}'" x-transition.opacity.duration.200ms wire:key="home-course-{{ $course->id }}">
                        <x-course-card-pintar
                            :course="$course"
                            :index="$index"
                            :effective-price="$this->effectivePrice($course)"
                            :has-discount="$this->hasDiscount($course)"
                            :lesson-count="$this->lessonCount($course)"
                        />
                    </div>
                @empty
                    <div class="col-span-full py-16 text-center pintar-home-reveal">
                        <p class="text-lg font-bold text-brand-navy">No courses available at the moment.</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-11 pintar-home-reveal">
                <a href="{{ route('courses.list') }}" class="pintar-home-btn pintar-home-btn-outline">
                    View Courses
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4" stroke="#0E1E1D" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Categories --}}
    <section class="pintar-home-section">
        <div class="pintar-home-container">
            <div class="pintar-home-cat-top">
                <div class="pintar-home-reveal">
                    <div class="pintar-home-eyebrow">Course categories</div>
                    <h2>Course that change your life!</h2>
                </div>
                <div class="pintar-home-reveal">
                    <p style="color:var(--on-surface-muted);line-height:1.7;">Explore academic, skill, test prep, and professional tracks — start from the category that matches your goals.</p>
                    <a href="{{ route('courses.list') }}" class="pintar-home-btn-ghost" style="margin-top:18px;">
                        Learn more
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4" stroke="#E06524" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>
            </div>

            <div class="pintar-home-cat-grid">
                @forelse($categories->take(4) as $index => $category)
                    <a href="{{ route('courses.list', ['category' => $category->id]) }}" @class(['pintar-home-cat-card', 'big' => $index === 0, 'pintar-home-reveal'])>
                        <span class="pintar-home-cat-icon" style="background:{{ $categoryIconBgs[$index] ?? $categoryIconBgs[0] }};color:{{ $categoryIconColors[$index] ?? $categoryIconColors[0] }}">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">{!! $categoryIcons[$index] ?? $categoryIcons[0] !!}</svg>
                        </span>
                        <div>
                            <h3>{{ $category->name }}</h3>
                            <p>{{ $this->bn(max($category->courses_count, 1)) }}+ courses available.</p>
                            <span class="pintar-home-btn-ghost" style="margin-top:14px;color:{{ $index === 0 ? '#FFD177' : 'var(--brand-orange-soft)' }};">
                                Learn more
                                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </span>
                        </div>
                    </a>
                @empty
                    @foreach(['Tech and Web Development', 'Business & Leadership', 'Languages', 'Science'] as $index => $name)
                        <div @class(['pintar-home-cat-card', 'big' => $index === 0, 'pintar-home-reveal'])>
                            <span class="pintar-home-cat-icon" style="background:{{ $categoryIconBgs[$index] }};color:{{ $categoryIconColors[$index] }}">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" aria-hidden="true">{!! $categoryIcons[$index] !!}</svg>
                            </span>
                            <div>
                                <h3>{{ $name }}</h3>
                                <p>Explore courses in this category.</p>
                            </div>
                        </div>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    {{-- Why Choose Us --}}
    <section class="pintar-home-section">
        <div class="pintar-home-container">
            <div class="pintar-home-why-top">
                <div class="pintar-home-section-head pintar-home-reveal" style="margin-bottom:0;">
                    <div class="pintar-home-eyebrow">Why choose us</div>
                    <h2>Our courses are designed to be immersive and interactive!</h2>
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
                <div class="pintar-home-eyebrow">Pricing</div>
                <h2>Pricing Plan</h2>
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
                    <div class="pintar-home-eyebrow">Testimonials</div>
                    <h2>"Here our customers say"</h2>
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
    </section>
</div>
