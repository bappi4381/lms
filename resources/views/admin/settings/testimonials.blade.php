@extends('layouts.admin')

@section('title', 'Testimonials Section Settings')
@section('eyebrow', 'Site Settings')
@section('page_heading', 'Testimonials Section')

@section('content')
<div class="mx-auto max-w-4xl">
    @include('admin.settings._nav')

    <form method="POST" action="{{ route('admin.settings.testimonials.update') }}" class="space-y-5">
        @csrf @method('PUT')

        <div class="admin-card">
            <div class="admin-card-head">
                <h3 class="admin-card-title">Testimonials Section Headings</h3>
                <p class="text-[12.5px]" style="color:var(--a-ink-faint)">Set eyebrow label and title for the Testimonials section.</p>
            </div>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label class="admin-label">Eyebrow Text (বাংলা) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="testi_eyebrow_bn" value="{{ old('testi_eyebrow_bn', $settings->testi_eyebrow_bn) }}" required class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Eyebrow Text (English) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="testi_eyebrow_en" value="{{ old('testi_eyebrow_en', $settings->testi_eyebrow_en) }}" required class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Section Title (বাংলা) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="testi_title_bn" value="{{ old('testi_title_bn', $settings->testi_title_bn) }}" required class="admin-input">
                </div>
                <div>
                    <label class="admin-label">Section Title (English) <span style="color:var(--a-brick)">*</span></label>
                    <input type="text" name="testi_title_en" value="{{ old('testi_title_en', $settings->testi_title_en) }}" required class="admin-input">
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="admin-btn admin-btn-primary">Save Testimonials Section</button>
        </div>
    </form>
</div>
@endsection
