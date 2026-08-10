@extends('layouts.admin')

@section('title', 'Modules: ' . $course->title_en)
@section('page_heading', 'Manage Modules')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between mb-2">
        <a href="{{ route('admin.courses.edit', $course) }}" class="text-sm text-slate-500 hover:text-sky-600 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Course Edit
        </a>
        <h2 class="text-base font-bold text-slate-700 max-w-xs truncate">{{ $course->title_en }}</h2>
    </div>

    @if(session('success'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm font-semibold">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm font-semibold">
            {{ session('error') }}
        </div>
    @endif

    <!-- Add Module Form -->
    <div class="bg-white rounded-2xl border border-slate-200/80 p-6">
        <h3 class="font-bold text-slate-800 mb-4 text-base">Add New Module</h3>

        @if($errors->any())
            <div class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs mb-4 space-y-1">
                @foreach($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.courses.modules.store', $course) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf

            <div class="md:col-span-2">
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Module Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required value="{{ old('title') }}"
                       placeholder="e.g. Week 1: Python Fundamentals"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Order</label>
                <input type="number" name="order" min="0" value="{{ old('order', $modules->count() + 1) }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Live Class Provider</label>
                <select name="live_class_provider"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 bg-white">
                    <option value="">— No Live Class —</option>
                    <option value="zoom" {{ old('live_class_provider') === 'zoom' ? 'selected' : '' }}>Zoom</option>
                    <option value="google_meet" {{ old('live_class_provider') === 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                    <option value="other" {{ old('live_class_provider') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Live Class Link</label>
                <input type="url" name="live_class_link" value="{{ old('live_class_link') }}"
                       placeholder="https://zoom.us/j/..."
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Live Class Date & Time</label>
                <input type="datetime-local" name="live_class_at" value="{{ old('live_class_at') }}"
                       class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div class="md:col-span-2 flex justify-end">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-md transition-all">
                    + Add Module
                </button>
            </div>
        </form>
    </div>

    <!-- Modules List -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h3 class="font-bold text-slate-800 text-base">Modules ({{ $modules->count() }})</h3>
        </div>

        @if($modules->isEmpty())
            <div class="py-12 text-center text-slate-400 text-sm">
                No modules yet. Add one above.
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($modules as $module)
                    <div class="p-4" x-data="{ editing: false }">
                        <!-- View Mode -->
                        <div x-show="!editing" class="flex items-start justify-between gap-4">
                            <div class="flex items-start gap-3 flex-1 min-w-0">
                                <span class="flex-shrink-0 w-8 h-8 rounded-lg bg-slate-100 text-slate-600 font-bold text-sm flex items-center justify-center">
                                    {{ $module->order }}
                                </span>
                                <div class="flex-1 min-w-0">
                                    <p class="font-bold text-slate-800 text-sm truncate">{{ $module->title }}</p>
                                    @if($module->live_class_at)
                                        <p class="text-xs text-slate-500 mt-0.5">
                                            <span class="font-semibold capitalize">{{ $module->live_class_provider }}</span>
                                            · {{ $module->live_class_at->format('d M Y, h:i A') }}
                                        </p>
                                    @endif
                                    @if($module->live_class_link)
                                        <a href="{{ $module->live_class_link }}" target="_blank"
                                           class="text-xs text-sky-600 hover:underline truncate block max-w-xs">
                                            {{ $module->live_class_link }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <button type="button" @click="editing = true"
                                        class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-sky-100 text-slate-700 hover:text-sky-800 text-xs font-bold transition-all">
                                    Edit
                                </button>
                                <form method="POST" action="{{ route('admin.courses.modules.destroy', [$course, $module]) }}"
                                      onsubmit="return confirm('Delete this module?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition-all">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Edit Mode (inline) -->
                        <div x-show="editing">
                            <form method="POST" action="{{ route('admin.courses.modules.update', [$course, $module]) }}"
                                  class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @csrf
                                @method('PUT')

                                <div class="md:col-span-2">
                                    <input type="text" name="title" value="{{ $module->title }}" required
                                           class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 font-semibold">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-600 block mb-1">Order</label>
                                    <input type="number" name="order" value="{{ $module->order }}" min="0"
                                           class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-600 block mb-1">Provider</label>
                                    <select name="live_class_provider"
                                            class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-sky-500">
                                        <option value="">— None —</option>
                                        <option value="zoom" {{ $module->live_class_provider === 'zoom' ? 'selected' : '' }}>Zoom</option>
                                        <option value="google_meet" {{ $module->live_class_provider === 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                                        <option value="other" {{ $module->live_class_provider === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-600 block mb-1">Live Link</label>
                                    <input type="url" name="live_class_link" value="{{ $module->live_class_link }}"
                                           class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                                </div>
                                <div>
                                    <label class="text-xs font-semibold text-slate-600 block mb-1">Live Date/Time</label>
                                    <input type="datetime-local" name="live_class_at"
                                           value="{{ $module->live_class_at?->format('Y-m-d\TH:i') }}"
                                           class="w-full px-3 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                                </div>

                                <div class="md:col-span-2 flex gap-2 justify-end">
                                    <button type="button" @click="editing = false"
                                            class="px-4 py-2 rounded-xl border border-slate-300 text-slate-700 text-xs font-semibold hover:bg-slate-50 transition-all">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                            class="px-4 py-2 rounded-xl bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold transition-all">
                                        Save Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection
