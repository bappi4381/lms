@extends('layouts.admin')

@section('title', 'Hero Section Settings')
@section('eyebrow', 'Site Settings')
@section('page_heading', 'Hero Section')

@section('content')
<div class="mx-auto max-w-4xl">
    @include('admin.settings._nav')

    <form method="POST" action="{{ route('admin.settings.hero.update') }}" class="space-y-5">
        @csrf @method('PUT')

        <div class="admin-card">
            <div class="admin-card-head">
                <h3 class="admin-card-title">Hero Headings &amp; Eyebrow</h3>
                <p class="text-[12.5px]" style="color:var(--a-ink-faint)">Configure the primary headline and eyebrow tags shown at the top of the homepage.</p>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label">Eyebrow Text (বাংলা) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="hero_eyebrow_bn" value="{{ old('hero_eyebrow_bn', $settings->hero_eyebrow_bn) }}" required class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Eyebrow Text (English) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="hero_eyebrow_en" value="{{ old('hero_eyebrow_en', $settings->hero_eyebrow_en) }}" required class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Main Title (বাংলা) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="hero_title_bn" value="{{ old('hero_title_bn', $settings->hero_title_bn) }}" required class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Main Title (English) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="hero_title_en" value="{{ old('hero_title_en', $settings->hero_title_en) }}" required class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Highlighted Word (বাংলা) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="hero_highlight_bn" value="{{ old('hero_highlight_bn', $settings->hero_highlight_bn) }}" required class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Highlighted Word (English) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="hero_highlight_en" value="{{ old('hero_highlight_en', $settings->hero_highlight_en) }}" required class="admin-input">
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-head">
                <h3 class="admin-card-title">Hero Description</h3>
                <p class="text-[12.5px]" style="color:var(--a-ink-faint)">Subtitle and introduction paragraph.</p>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label">Description (বাংলা) <span style="color:var(--a-brick)">*</span></label>
                    <textarea name="hero_description_bn" rows="3" required class="admin-textarea">{{ old('hero_description_bn', $settings->hero_description_bn) }}</textarea>
                </div>
                <div>
                    <label class="admin-label">Description (English) <span style="color:var(--a-brick)">*</span></label>
                    <textarea name="hero_description_en" rows="3" required class="admin-textarea">{{ old('hero_description_en', $settings->hero_description_en) }}</textarea>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-head">
                <h3 class="admin-card-title">Call to Action Buttons</h3>
                <p class="text-[12.5px]" style="color:var(--a-ink-faint)">Button labels displayed on the Hero CTA area.</p>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label">Primary Button Text (বাংলা) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="hero_btn_primary_bn" value="{{ old('hero_btn_primary_bn', $settings->hero_btn_primary_bn) }}" required class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Primary Button Text (English) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="hero_btn_primary_en" value="{{ old('hero_btn_primary_en', $settings->hero_btn_primary_en) }}" required class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Secondary Button Text (বাংলা) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="hero_btn_secondary_bn" value="{{ old('hero_btn_secondary_bn', $settings->hero_btn_secondary_bn) }}" required class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Secondary Button Text (English) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="hero_btn_secondary_en" value="{{ old('hero_btn_secondary_en', $settings->hero_btn_secondary_en) }}" required class="admin-input">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="admin-btn admin-btn-primary">Save Hero Section</button>
        </div>
    </form>
</div>
@endsection
