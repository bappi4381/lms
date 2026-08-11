@extends('layouts.admin')

@section('title', 'About Section Settings')
@section('eyebrow', 'Site Settings')
@section('page_heading', 'About Section')

@section('content')
<div class="mx-auto max-w-4xl">
    @include('admin.settings._nav')

    <form method="POST" action="{{ route('admin.settings.about.update') }}" class="space-y-5">
        @csrf @method('PUT')

        <div class="admin-card">
            <div class="admin-card-head">
                <h3 class="admin-card-title">About Section Titles</h3>
                <p class="text-[12.5px]" style="color:var(--a-ink-faint)">Set the eyebrow badge text and main heading for the homepage About block.</p>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label">Eyebrow Text (বাংলা) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="about_eyebrow_bn" value="{{ old('about_eyebrow_bn', $settings->about_eyebrow_bn) }}" required class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Eyebrow Text (English) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="about_eyebrow_en" value="{{ old('about_eyebrow_en', $settings->about_eyebrow_en) }}" required class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Title (বাংলা) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="about_title_bn" value="{{ old('about_title_bn', $settings->about_title_bn) }}" required class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Title (English) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="about_title_en" value="{{ old('about_title_en', $settings->about_title_en) }}" required class="admin-input">
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-head">
                <h3 class="admin-card-title">Description &amp; Call-to-Action</h3>
                <p class="text-[12.5px]" style="color:var(--a-ink-faint)">Detailed overview paragraph and action button text.</p>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label">Description (বাংলা) <span style="color:var(--a-brick)">*</span></label>
                    <textarea name="about_description_bn" rows="4" required class="admin-textarea">{{ old('about_description_bn', $settings->about_description_bn) }}</textarea>
                </div>
                <div>
                    <label class="admin-label">Description (English) <span style="color:var(--a-brick)">*</span></label>
                    <textarea name="about_description_en" rows="4" required class="admin-textarea">{{ old('about_description_en', $settings->about_description_en) }}</textarea>
                </div>
                <div>
                    <label class="admin-label">Button Text (বাংলা) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="about_btn_bn" value="{{ old('about_btn_bn', $settings->about_btn_bn) }}" required class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Button Text (English) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="about_btn_en" value="{{ old('about_btn_en', $settings->about_btn_en) }}" required class="admin-input">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="admin-btn admin-btn-primary">Save About Section</button>
        </div>
    </form>
</div>
@endsection
