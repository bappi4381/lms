@extends('layouts.admin')

@section('title', 'Add New Category')
@section('eyebrow', 'Course Management')
@section('page_heading', 'Add New Category')

@section('content')
<div class="mx-auto max-w-3xl space-y-5">

    <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center gap-2 text-[13px] font-semibold" style="color:var(--a-ink-soft)">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <span>Back to Categories</span>
    </a>

    <div class="admin-card p-6 sm:p-8">
        <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-7">
            @csrf

            <div>
                <h3 class="admin-card-title">Basic Details</h3>
                <p class="mt-1 text-[12.5px]" style="color:var(--a-ink-soft)">Provide category names in both English and Bangla.</p>

                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="admin-label">Category Name (English) <span style="color:var(--a-brick)">*</span></label>
                        <input type="text" name="name_en" value="{{ old('name_en') }}" placeholder="e.g. Web Development" required class="admin-input">
                    </div>
                    <div>
                        <label class="admin-label">Category Name (বাংলা) <span style="color:var(--a-brick)">*</span></label>
                        <input type="text" name="name_bn" value="{{ old('name_bn') }}" placeholder="যেমন: ওয়েব ডেভেলপমেন্ট" required class="admin-input">
                    </div>
                </div>
            </div>

            <div class="border-t pt-6" style="border-color:var(--a-line-soft)">
                <h3 class="admin-card-title">Hierarchy &amp; Placement</h3>
                <p class="mt-1 text-[12.5px]" style="color:var(--a-ink-soft)">Categories can be nested up to 3 levels deep (Category → Sub-category → Sub-sub-category).</p>

                <div class="mt-4 space-y-4">
                    @php
                        $parentSelectOptions = ['' => '— No Parent (Top-level Category) —'] + $parentOptions;
                        $mainTypeSelectOptions = ['' => '— Select Section —'] + $mainTypes;
                    @endphp

                    <div>
                        <label class="admin-label">Parent Category</label>
                        <x-searchable-select name="parent_id"
                                             :options="$parentSelectOptions"
                                             :value="old('parent_id')"
                                             placeholder="— No Parent (Top-level Category) —"
                                             searchPlaceholder="Search parent category..." />
                        <span class="mt-1 block text-[11px]" style="color:var(--a-ink-faint)">Leave empty to create a Top-level main category.</span>
                    </div>

                    <div>
                        <label class="admin-label">Main Navbar Section</label>
                        <x-searchable-select name="main_type"
                                             :options="$mainTypeSelectOptions"
                                             :value="old('main_type')"
                                             placeholder="— Select Section —"
                                             searchPlaceholder="Search section..." />
                        <span class="mt-1 block text-[11px]" style="color:var(--a-ink-faint)">Required for Top-level categories. Sub-categories inherit this from their parent.</span>
                    </div>
                </div>
            </div>

            <div class="border-t pt-6" style="border-color:var(--a-line-soft)">
                <h3 class="admin-card-title">Additional Options</h3>

                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="admin-label">Display Order</label>
                        <input type="number" name="order" value="{{ old('order', 0) }}" class="admin-input">
                    </div>

                    <div class="flex items-center pt-6">
                        <label class="relative inline-flex cursor-pointer items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="peer sr-only">
                            <div class="h-6 w-11 rounded-full bg-[var(--a-line)] transition-colors after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:bg-[var(--a-accent)] peer-checked:after:translate-x-full"></div>
                            <span class="ml-3 text-[12.5px] font-semibold uppercase tracking-wide" style="color:var(--a-ink-soft)">Active</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 border-t pt-6" style="border-color:var(--a-line-soft)">
                <a href="{{ route('admin.categories.index') }}" class="admin-btn admin-btn-ghost">Cancel</a>
                <button type="submit" class="admin-btn admin-btn-primary">Save Category</button>
            </div>
        </form>
    </div>

</div>
@endsection
