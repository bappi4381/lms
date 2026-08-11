@extends('layouts.admin')

@section('title', 'Modules: ' . $course->title_en)
@section('eyebrow', 'Course Management')
@section('page_heading', 'Manage Modules')

@section('content')
<div class="mx-auto max-w-4xl space-y-5">

    <div class="mb-2 flex items-center justify-between">
        <a href="{{ route('admin.courses.edit', $course) }}" class="inline-flex items-center gap-1 text-[13px] font-semibold" style="color:var(--a-ink-soft)">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Course Edit
        </a>
        <h2 class="admin-heading max-w-xs truncate text-[15px]">{{ $course->title_en }}</h2>
    </div>

    <!-- Add Module Form -->
    <div class="admin-card p-6">
        <h3 class="admin-card-title mb-4">Add New Module</h3>

        <form method="POST" action="{{ route('admin.courses.modules.store', $course) }}" class="grid grid-cols-1 gap-4 md:grid-cols-2">
            @csrf

            <div class="md:col-span-2">
                <label class="admin-label">Module Title <span style="color:var(--a-brick)">*</span></label>
                <input type="text" name="title" required value="{{ old('title') }}"
                       placeholder="e.g. Week 1: Python Fundamentals"
                       class="admin-input">
            </div>

            <div>
                <label class="admin-label">Order</label>
                <input type="number" name="order" min="0" value="{{ old('order', $modules->count() + 1) }}" class="admin-input">
            </div>

            <div>
                <label class="admin-label">Live Class Provider</label>
                <select name="live_class_provider" class="admin-select">
                    <option value="">— No Live Class —</option>
                    <option value="zoom" {{ old('live_class_provider') === 'zoom' ? 'selected' : '' }}>Zoom</option>
                    <option value="google_meet" {{ old('live_class_provider') === 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                    <option value="other" {{ old('live_class_provider') === 'other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <div>
                <label class="admin-label">Live Class Link</label>
                <input type="url" name="live_class_link" value="{{ old('live_class_link') }}"
                       placeholder="https://zoom.us/j/..."
                       class="admin-input">
            </div>

            <div>
                <label class="admin-label">Live Class Date &amp; Time</label>
                <input type="datetime-local" name="live_class_at" value="{{ old('live_class_at') }}" class="admin-input">
            </div>

            <div class="flex justify-end md:col-span-2">
                <button type="submit" class="admin-btn admin-btn-primary">+ Add Module</button>
            </div>
        </form>
    </div>

    <!-- Modules List -->
    <div class="admin-table-wrap">
        <div class="admin-card-head">
            <span class="admin-card-title">Modules ({{ $modules->count() }})</span>
        </div>

        @if($modules->isEmpty())
            <div class="admin-empty">No modules yet. Add one above.</div>
        @else
            <div class="divide-y" style="border-color:var(--a-line-soft)">
                @foreach($modules as $module)
                    <div class="p-4" x-data="{ editing: false }">
                        <!-- View Mode -->
                        <div x-show="!editing" class="flex items-start justify-between gap-4">
                            <div class="flex min-w-0 flex-1 items-start gap-3">
                                <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-ledger text-[13px] font-semibold" style="background:var(--a-page); color:var(--a-ink-soft)">
                                    {{ $module->order }}
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-[13px] font-semibold" style="color:var(--a-ink)">{{ $module->title }}</p>
                                    @if($module->live_class_at)
                                        <p class="mt-0.5 text-[12px]" style="color:var(--a-ink-soft)">
                                            <span class="font-semibold capitalize">{{ $module->live_class_provider }}</span>
                                            · {{ $module->live_class_at->format('d M Y, h:i A') }}
                                        </p>
                                    @endif
                                    @if($module->live_class_link)
                                        <a href="{{ $module->live_class_link }}" target="_blank"
                                           class="block max-w-xs truncate text-[12px] hover:underline" style="color:var(--a-accent)">
                                            {{ $module->live_class_link }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="flex shrink-0 items-center gap-2">
                                <button type="button" @click="editing = true" class="admin-btn admin-btn-secondary !min-h-[30px] !px-3 text-[12px]">Edit</button>
                                <form method="POST" action="{{ route('admin.courses.modules.destroy', [$course, $module]) }}"
                                      onsubmit="return confirm('Delete this module?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn admin-btn-danger !min-h-[30px] !px-3 text-[12px]">Delete</button>
                                </form>
                            </div>
                        </div>

                        <!-- Edit Mode (inline) -->
                        <div x-show="editing">
                            <form method="POST" action="{{ route('admin.courses.modules.update', [$course, $module]) }}"
                                  class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                @csrf
                                @method('PUT')

                                <div class="md:col-span-2">
                                    <input type="text" name="title" value="{{ $module->title }}" required class="admin-input font-semibold">
                                </div>
                                <div>
                                    <label class="admin-label !mb-1">Order</label>
                                    <input type="number" name="order" value="{{ $module->order }}" min="0" class="admin-input">
                                </div>
                                <div>
                                    <label class="admin-label !mb-1">Provider</label>
                                    <select name="live_class_provider" class="admin-select">
                                        <option value="">— None —</option>
                                        <option value="zoom" {{ $module->live_class_provider === 'zoom' ? 'selected' : '' }}>Zoom</option>
                                        <option value="google_meet" {{ $module->live_class_provider === 'google_meet' ? 'selected' : '' }}>Google Meet</option>
                                        <option value="other" {{ $module->live_class_provider === 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="admin-label !mb-1">Live Link</label>
                                    <input type="url" name="live_class_link" value="{{ $module->live_class_link }}" class="admin-input">
                                </div>
                                <div>
                                    <label class="admin-label !mb-1">Live Date/Time</label>
                                    <input type="datetime-local" name="live_class_at"
                                           value="{{ $module->live_class_at?->format('Y-m-d\TH:i') }}"
                                           class="admin-input">
                                </div>

                                <div class="flex justify-end gap-2 md:col-span-2">
                                    <button type="button" @click="editing = false" class="admin-btn admin-btn-ghost !min-h-[32px] !text-[12px]">Cancel</button>
                                    <button type="submit" class="admin-btn admin-btn-primary !min-h-[32px] !text-[12px]">Save Changes</button>
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
