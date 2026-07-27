@props([
    'compact' => false,
    'wordmark' => 'md',
])

@php
    $wordmarkSize = $wordmark === 'lg' ? 'text-2xl' : 'text-xl';
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center gap-2.5 font-extrabold tracking-tight select-none']) }}>
    <div class="neu-logo-mark" aria-hidden="true">
        <div class="neu-logo-mark__inner">
            <span class="neu-logo-mark__letter">S</span>
        </div>
    </div>
    <div class="flex flex-col leading-none">
        <span class="{{ $wordmarkSize }} font-black text-brand-navy tracking-tight whitespace-nowrap">
            SecondShift<span class="text-brand-blue">BD</span>
        </span>
        @unless($compact)
            <span class="text-[9px] font-bold tracking-widest text-neu-muted uppercase">Education Platform</span>
        @endunless
    </div>
</div>
