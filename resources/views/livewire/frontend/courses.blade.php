<?php

use App\Models\Category;
use App\Models\Course;
use Livewire\Volt\Component;

new class extends Component
{
    public $categories = [];
    public $courses = [];
    public $selectedCategory = null;
    public $search = '';
    public $priceFilter = 'all';

    public function mount(): void
    {
        $this->categories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        $this->selectedCategory = request()->integer('category') ?: null;
        $this->search = trim((string) request()->query('q', ''));

        $this->loadCourses();
    }

    public function filterByCategory(?int $categoryId): void
    {
        $this->selectedCategory = $categoryId;
        $this->loadCourses();
    }

    public function updatedSearch(): void
    {
        $this->loadCourses();
    }

    public function updatedPriceFilter(): void
    {
        // View-layer filter only — loadCourses() unchanged.
    }

    public function clearFilters(): void
    {
        $this->selectedCategory = null;
        $this->search = '';
        $this->priceFilter = 'all';
        $this->loadCourses();
    }

    private function loadCourses(): void
    {
        $query = Course::where('is_published', true);

        if ($this->selectedCategory) {
            $category = Category::with('children')->find($this->selectedCategory);
            if ($category) {
                $categoryIds = array_merge([$category->id], $category->children->pluck('id')->toArray());
                $query->whereIn('category_id', $categoryIds);
            }
        }

        if ($this->search !== '') {
            $query->where('title', 'like', '%'.$this->search.'%');
        }

        $this->courses = $query->with('category')->latest()->get();
    }

    public function bn(int|string $number): string
    {
        return str_replace(
            ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
            ['০', '১', '২', '৩', '৪', '৫', '৬', '৭', '৮', '৯'],
            (string) $number
        );
    }
};
?>

@php
    $visibleCourses = collect($courses)->filter(function ($course) use ($priceFilter) {
        $price = (float) $course->price;

        return match ($priceFilter) {
            'free' => $price <= 0,
            'paid' => $price > 0,
            'under1500' => $price > 0 && $price < 1500,
            default => true,
        };
    });

    $activeCategory = $categories->firstWhere('id', $selectedCategory);
    $academicTerms = ['ssc', 'hsc', 'admission', 'university', 'academic', 'একাডেমিক'];
    $isAcademicContext = $activeCategory
        || collect($academicTerms)->contains(fn ($term) => str_contains(strtolower($search), $term));

    $pageTitle = $search !== ''
        ? $search.' কোর্স'
        : ($activeCategory?->name ?? ($isAcademicContext ? 'একাডেমিক' : 'সকল কোর্স'));

    $showFreeResources = request()->boolean('resources');
    $showStore = request()->boolean('store');
    $showBlog = request()->boolean('blog');
@endphp

@if($showFreeResources)
    @include('livewire.frontend.partials.free-resources')
@elseif($showStore)
    @include('livewire.frontend.partials.store')
@elseif($showBlog)
    @include('livewire.frontend.partials.blog')
@else
<div x-data="{ mobileFilters: false }" class="min-h-screen">
    {{-- Page header --}}
    <section class="glass-section">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="glass-page-header">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    @if($isAcademicContext && $search === '')
                        <p class="text-xs sm:text-sm font-bold text-brand-blue mb-2 uppercase tracking-wide">একাডেমিক</p>
                    @endif
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-brand-navy tracking-tight">{{ $pageTitle }}</h1>
                    <p class="mt-2 text-sm text-gray-500 max-w-2xl">
                        @if($isAcademicContext)
                            SSC, HSC, ভর্তি প্রস্তুতি — সেরা ইন্সট্রাক্টরদের সাথে একাডেমিক কোর্স ব্রাউজ করুন।
                        @else
                            পছন্দের কোর্স খুঁজে নিন এবং সেরা ইন্সট্রাক্টরদের সাথে স্কিল ডেভেলপ করুন।
                        @endif
                    </p>
                </div>

                {{-- Mobile search + filter toggle --}}
                <div class="flex items-center gap-2 w-full sm:w-auto sm:min-w-[20rem]">
                    <div class="flex-1 glass-input-pill flex items-center gap-2 px-3 py-2">
                        <svg class="w-4 h-4 text-brand-blue shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
                        <input
                            type="text"
                            wire:model.live.debounce.400ms="search"
                            placeholder="কোর্স খুঁজুন..."
                            class="flex-1 border-0 focus:ring-0 text-sm bg-transparent min-w-0"
                        >
                    </div>
                    <button
                        type="button"
                        @click="mobileFilters = true"
                        class="lg:hidden inline-flex items-center justify-center min-h-[44px] min-w-[44px] rounded-xl glass-icon-btn text-brand-navy"
                        aria-label="ফিল্টার"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    </button>
                </div>
            </div>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 sm:pb-10">
        <div class="glass-content-area">
        <div class="flex gap-8 lg:gap-10">
            {{-- Desktop sidebar --}}
            @include('livewire.frontend.partials.course-filters', ['mobile' => false])

            {{-- Main content --}}
            <div class="flex-1 min-w-0">
                <div wire:loading.class="opacity-60" class="transition-opacity duration-200">
                    <p class="text-sm font-semibold text-gray-600 mb-6">
                        <span class="text-brand-navy font-bold">{{ $this->bn($visibleCourses->count()) }}টি</span> কোর্স পাওয়া গেছে
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 sm:gap-6">
                        @forelse($visibleCourses as $index => $course)
                            @php
                                $effectivePrice = ($course->discount_price && $course->discount_price < $course->price)
                                    ? (float) $course->discount_price
                                    : (float) $course->price;
                                $hasDiscount = $course->discount_price && $course->discount_price < $course->price;
                                $badge = $index < 2
                                    ? ['label' => 'New', 'class' => 'bg-orange-500']
                                    : (($course->reviewsCount() >= 5 || $index % 3 === 0)
                                        ? ['label' => 'Bestseller', 'class' => 'bg-red-500']
                                        : null);
                            @endphp

                            <article class="group flex flex-col glass-card-hover overflow-hidden h-full">
                                <a href="{{ route('courses.show', $course->slug) }}" class="block focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue focus-visible:ring-inset">
                                    <div class="relative aspect-video overflow-hidden glass-thumb">
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
                                    <p class="text-[11px] sm:text-xs text-brand-blue font-bold mb-1.5 uppercase tracking-wide">
                                        {{ $course->category?->name ?? 'একাডেমিক' }}
                                    </p>

                                    <h2 class="text-sm sm:text-[15px] font-bold text-brand-navy mb-2 line-clamp-2 leading-snug min-h-[2.5rem]">
                                        <a href="{{ route('courses.show', $course->slug) }}" class="hover:text-brand-blue transition-colors focus-visible:underline">
                                            {{ $course->title }}
                                        </a>
                                    </h2>

                                    <p class="text-xs text-gray-500 mb-2.5 truncate">
                                        {{ $course->instructor?->name ?? 'SecondShiftBD' }} · Beginner
                                    </p>

                                    <div class="flex items-center gap-1.5 text-xs mb-4">
                                        <svg class="w-3.5 h-3.5 text-brand-gold shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <span class="font-bold text-brand-navy">{{ $course->averageRating() > 0 ? $course->averageRating() : '4.8' }}</span>
                                        <span class="text-gray-400">({{ $this->bn($course->reviewsCount() ?: 320) }})</span>
                                    </div>

                                    <div class="mt-auto flex items-baseline gap-2">
                                        <span class="text-lg sm:text-xl font-extrabold text-brand-navy">৳{{ number_format($effectivePrice, 0) }}</span>
                                        @if($hasDiscount)
                                            <span class="text-sm text-gray-400 line-through">৳{{ number_format($course->price, 0) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="col-span-full flex flex-col items-center justify-center py-20 sm:py-24 glass-empty">
                                <svg class="w-14 h-14 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                <p class="text-lg font-bold text-brand-navy">বর্তমানে কোনো কোর্স পাওয়া যায়নি।</p>
                                <p class="text-gray-500 mt-1 text-sm">ফিল্টার পরিবর্তন করুন অথবা অন্য ক্যাটাগরি দেখুন।</p>
                                <button
                                    type="button"
                                    wire:click="clearFilters"
                                    class="mt-5 md-ripple inline-flex items-center min-h-[44px] px-5 py-2.5 rounded-xl glass-btn text-sm"
                                >
                                    সব ফিল্টার মুছুন
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>

    {{-- Mobile filter drawer --}}
    <div
        x-show="mobileFilters"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 lg:hidden"
        style="display: none;"
        @keydown.escape.window="mobileFilters = false"
    >
        <div class="absolute inset-0 glass-backdrop-dark" @click="mobileFilters = false"></div>
        <div
            x-show="mobileFilters"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="absolute right-0 top-0 h-full w-full max-w-sm glass-drawer overflow-y-auto"
            style="display: none;"
        >
            <div class="flex items-center justify-between p-4 border-b glass-divider">
                <h2 class="text-lg font-bold text-brand-navy">ফিল্টার</h2>
                <button type="button" @click="mobileFilters = false" class="p-2 rounded-lg glass-icon-btn" aria-label="Close">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-4">
                @include('livewire.frontend.partials.course-filters', ['mobile' => true])
            </div>
        </div>
    </div>
</div>
@endif
