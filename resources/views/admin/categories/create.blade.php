@extends('layouts.admin')

@section('title', 'Add New Category')
@section('page_heading', 'Add New Category')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header Action Navigation -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.categories.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-slate-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Back to Categories</span>
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white p-6 sm:p-8 rounded-2xl border border-slate-200/80 shadow-xs">
        <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-6">
            @csrf

            <!-- Section 1: Basic Info -->
            <div>
                <h3 class="text-base font-bold text-slate-900 mb-1">Basic Details</h3>
                <p class="text-xs text-slate-500 mb-4">Provide category names in both English and Bangla.</p>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Category Name (English) <span class="text-rose-500">*</span></label>
                        <input type="text" 
                               name="name_en" 
                               value="{{ old('name_en') }}" 
                               placeholder="e.g. Web Development" 
                               required 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Category Name (বাংলা) <span class="text-rose-500">*</span></label>
                        <input type="text" 
                               name="name_bn" 
                               value="{{ old('name_bn') }}" 
                               placeholder="যেমন: ওয়েব ডেভেলপমেন্ট" 
                               required 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                    </div>
                </div>
            </div>

            <!-- Section 2: Hierarchy -->
            <div class="pt-6 border-t border-slate-100">
                <h3 class="text-base font-bold text-slate-900 mb-1">Hierarchy & Placement</h3>
                <p class="text-xs text-slate-500 mb-4">Categories can be nested up to 3 levels deep (Category → Sub-category → Sub-sub-category).</p>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Parent Category</label>
                        <select name="parent_id" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                            <option value="">— No Parent (Top-level Category) —</option>
                            @foreach($parentOptions as $id => $label)
                                <option value="{{ $id }}" {{ old('parent_id') == $id ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <span class="text-[11px] text-slate-500 mt-1 block">Leave empty to create a Top-level main category.</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Main Navbar Section</label>
                        <select name="main_type" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                            <option value="">— Select Section —</option>
                            @foreach($mainTypes as $key => $label)
                                <option value="{{ $key }}" {{ old('main_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <span class="text-[11px] text-slate-500 mt-1 block">Required for Top-level categories. Sub-categories inherit this from their parent.</span>
                    </div>
                </div>
            </div>

            <!-- Section 3: Additional -->
            <div class="pt-6 border-t border-slate-100">
                <h3 class="text-base font-bold text-slate-900 mb-1">Additional Options</h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Icon / Emoji</label>
                        <input type="text" 
                               name="icon" 
                               value="{{ old('icon') }}" 
                               placeholder="e.g. 💻 or 🎓" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Display Order</label>
                        <input type="number" 
                               name="order" 
                               value="{{ old('order', 0) }}" 
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                    </div>

                    <div class="flex items-center pt-6">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} class="sr-only peer">
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-sky-600"></div>
                            <span class="ml-3 text-xs font-bold text-slate-700 uppercase tracking-wider">Active</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Submit Button Bar -->
            <div class="pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('admin.categories.index') }}" class="px-5 py-2.5 rounded-xl text-slate-600 hover:text-slate-900 font-bold text-sm">Cancel</a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-md transition-all">
                    Save Category
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
