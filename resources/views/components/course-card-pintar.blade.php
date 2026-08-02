@props([
    'course',
    'badge' => null,
    'effectivePrice' => null,
    'hasDiscount' => false,
    'lessonCount' => null,
    'index' => 0,
])

@php
    $price = $effectivePrice ?? ($course->effective_price ?? $course->price ?? 0);
    $lessons = $lessonCount ?? 0;
    $coverClass = 'cover-'.(($index % 4) + 1);
@endphp

<article {{ $attributes->merge(['class' => 'pintar-home-course-card pintar-home-reveal']) }}>
    <div class="pintar-home-course-cover {{ $coverClass }}">
        @if($course->category)
            <span class="pintar-home-course-tag">{{ $course->category->name }}</span>
        @endif
    </div>

    <div class="pintar-home-course-body">
        <span class="by">{{ $lessons > 0 ? $lessons.' Lesson'.($lessons !== 1 ? 's' : '') : 'Live course' }}</span>
        <h3>
            <a href="{{ route('courses.show', $course->slug) }}" class="hover:text-brand-teal transition-colors focus-visible:underline">
                {{ $course->title }}
            </a>
        </h3>
        <span class="by">by {{ $course->instructor?->name ?? 'SecondShiftBD' }}</span>

        <div class="pintar-home-course-foot">
            <div class="pintar-home-price-tag">
                <small>Price</small>
                <strong>৳{{ number_format($price, 0) }}</strong>
                @if($hasDiscount && isset($course->price))
                    <span class="text-xs text-gray-400 line-through ml-1">৳{{ number_format($course->price, 0) }}</span>
                @endif
            </div>
            <a href="{{ route('courses.show', $course->slug) }}" class="pintar-home-start-link">
                Start
                <svg width="14" height="14" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4" stroke="#14615F" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </a>
        </div>
    </div>
</article>
