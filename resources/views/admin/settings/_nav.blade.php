@php
    $settingsTabs = [
        ['route' => 'admin.settings.hero.edit', 'pattern' => 'admin.settings.hero.*', 'label' => 'Hero'],
        ['route' => 'admin.settings.about.edit', 'pattern' => 'admin.settings.about.*', 'label' => 'About'],
        ['route' => 'admin.settings.why-us.edit', 'pattern' => 'admin.settings.why-us.*', 'label' => 'Why Choose Us'],
        ['route' => 'admin.settings.pricing.edit', 'pattern' => 'admin.settings.pricing.*', 'label' => 'Pricing'],
        ['route' => 'admin.settings.testimonials.edit', 'pattern' => 'admin.settings.testimonials.*', 'label' => 'Testimonials'],
        ['route' => 'admin.settings.header-footer.edit', 'pattern' => 'admin.settings.header-footer.*', 'label' => 'Header & Footer'],
    ];
@endphp
<div class="mb-5 flex flex-wrap gap-1.5 border-b pb-3" style="border-color:var(--a-line-soft)">
    @foreach($settingsTabs as $tab)
        <a href="{{ route($tab['route']) }}"
           class="rounded-ledger px-3 py-1.5 text-[12.5px] font-semibold transition-colors"
           style="{{ request()->routeIs($tab['pattern']) ? 'background:var(--a-accent);color:#fff' : 'color:var(--a-ink-soft)' }}">
            {{ $tab['label'] }}
        </a>
    @endforeach
</div>
