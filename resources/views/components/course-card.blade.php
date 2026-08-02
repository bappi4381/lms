@props([
    'course',
    'badge' => null,
    'effectivePrice' => null,
    'hasDiscount' => false,
    'showPrice' => true,
    'showRating' => true,
    'rating' => null,
    'reviewCount' => null,
    'showCategory' => true,
    'showInstructor' => true,
])

@php
    $price = $effectivePrice ?? ($course->effective_price ?? $course->price ?? 0);
    $displayRating = $rating ?? ($course->averageRating() > 0 ? $course->averageRating() : '4.8');
    $displayReviews = $reviewCount ?? ($course->reviewsCount() ?? 320);
@endphp

<article {{ $attributes->merge(['class' => 'group relative flex flex-col glass-card-hover overflow-hidden h-full']) }}>
    @if(isset($overlay))
        {{ $overlay }}
    @endif
    <a href="{{ route('courses.show', $course->slug) }}" class="block focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-blue focus-visible:ring-inset">
        <div class="relative aspect-video overflow-hidden bg-gray-100 dark:bg-gray-800">
            @if($course->thumbnail)
                <img
                    src="{{ asset('storage/'.$course->thumbnail) }}"
                    alt="{{ $course->title }}"
                    loading="lazy"
                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 ease-md-standard"
                >
            @else
                <div class="course-thumb-pattern absolute inset-0"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-12 h-12 rounded-full glass-icon-accent flex items-center justify-center">
                        <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M6.3 2.841A1.5 1.5 0 004 4.11V15.89a1.5 1.5 0 002.3 1.269l9.344-5.89a1.5 1.5 0 000-2.538L6.3 2.84z"/></svg>
                    </div>
                </div>
            @endif

            @if($badge)
                <span class="absolute top-3 left-3 {{ $badge['class'] ?? 'bg-brand-gold' }} text-white text-[11px] font-bold px-2.5 py-1 rounded-md-sm shadow-elevation-1">
                    {{ $badge['label'] }}
                </span>
            @endif
        </div>
    </a>

    <div class="p-4 sm:p-5 flex flex-col flex-1 gap-2">
        @if($showCategory && $course->category)
            <p class="text-[11px] sm:text-xs text-brand-blue font-bold uppercase tracking-wide">
                {{ $course->category->name }}
            </p>
        @endif

        <h3 class="text-sm sm:text-[15px] font-bold text-brand-navy line-clamp-2 leading-snug min-h-[2.5rem]">
            <a href="{{ route('courses.show', $course->slug) }}" class="hover:text-brand-blue transition-colors focus-visible:underline">
                {{ $course->title }}
            </a>
        </h3>

        @if($showInstructor)
            <p class="text-xs text-gray-500 truncate">
                {{ $course->instructor?->name ?? 'SecondShiftBD' }}
            </p>
        @endif

        @if($showRating)
            <div class="flex items-center gap-1.5 text-xs">
                <svg class="w-3.5 h-3.5 text-brand-gold shrink-0" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <span class="font-bold text-brand-navy">{{ $displayRating }}</span>
                <span class="text-gray-400">({{ is_numeric($displayReviews) ? number_format($displayReviews) : $displayReviews }})</span>
            </div>
        @endif

        @if(isset($footer))
            <div class="mt-auto pt-2 border-t border-gray-200 dark:border-gray-800">
                {{ $footer }}
            </div>
        @elseif($showPrice)
            <div class="mt-auto flex items-baseline gap-2 pt-2">
                <span class="text-lg sm:text-xl font-extrabold text-brand-orange">৳{{ number_format($price, 0) }}</span>
                @if($hasDiscount && isset($course->price))
                    <span class="text-sm text-gray-400 line-through">৳{{ number_format($course->price, 0) }}</span>
                @endif
            </div>
        @endif
    </div>
</article>
