@extends('layouts.admin')

@section('title', 'Quiz Management')
@section('eyebrow', 'Course Management')
@section('page_heading', 'Quizzes')

@section('content')
<div class="space-y-5">

    <!-- Filter bar -->
    <div class="admin-filter-bar">
        <form method="GET" action="{{ route('admin.quizzes.index') }}" class="flex flex-1 flex-wrap items-center gap-3">
            <div class="relative min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search quiz title..."
                       class="admin-input pl-9">
                <svg class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2" style="color:var(--a-ink-faint)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            @if(request('search'))
                <a href="{{ route('admin.quizzes.index') }}" class="text-[12px] font-semibold underline" style="color:var(--a-ink-soft)">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.quizzes.create') }}" class="admin-btn admin-btn-primary shrink-0">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Quiz
        </a>
    </div>

    <!-- Data table -->
    <div class="admin-table-wrap">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Lesson</th>
                        <th>Course</th>
                        <th class="text-center">Questions</th>
                        <th class="text-center">Pass %</th>
                        <th class="text-center">Time Limit</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($quizzes as $quiz)
                        <tr>
                            <td class="font-semibold" style="color:var(--a-ink)">{{ $quiz->title }}</td>
                            <td class="text-[12px] font-semibold" style="color:var(--a-ink-soft)">{{ $quiz->lesson?->title ?? '—' }}</td>
                            <td class="text-[12px]" style="color:var(--a-ink-faint)">{{ $quiz->lesson?->module?->course?->title_en ?? '—' }}</td>
                            <td class="text-center"><span class="admin-badge admin-badge-gold">{{ $quiz->questions_count }}</span></td>
                            <td class="text-center font-semibold" style="color:var(--a-accent)">{{ $quiz->pass_percentage }}%</td>
                            <td class="text-center text-[12px]" style="color:var(--a-ink-faint)">
                                {{ $quiz->time_limit_minutes ? $quiz->time_limit_minutes . ' mins' : 'Unlimited' }}
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="admin-btn admin-btn-secondary !min-h-[30px] !px-3 text-[12px]">Edit</a>
                                    <form method="POST" action="{{ route('admin.quizzes.destroy', $quiz) }}" onsubmit="return confirm('Delete this quiz?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="admin-btn admin-btn-danger !min-h-[30px] !px-3 text-[12px]">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="admin-empty">No quizzes found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($quizzes->hasPages())
            <div class="border-t px-4 py-3" style="border-color:var(--a-line-soft)">{{ $quizzes->links() }}</div>
        @endif
    </div>

</div>
@endsection
