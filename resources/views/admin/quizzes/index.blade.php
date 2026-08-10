@extends('layouts.admin')

@section('title', 'Quiz Management')
@section('page_heading', 'Quiz Management')

@section('content')
<div class="space-y-6">

    <!-- Filters & Actions -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form method="GET" action="{{ route('admin.quizzes.index') }}" class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search quiz title..."
                       class="w-full pl-10 pr-4 py-2 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            @if(request('search'))
                <a href="{{ route('admin.quizzes.index') }}" class="py-2 px-3 text-xs font-semibold text-slate-500 hover:text-slate-800 underline">Clear</a>
            @endif
        </form>

        <a href="{{ route('admin.quizzes.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-md transition-all shrink-0">
            <svg class="w-4 h-4 stroke-[2.5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Add Quiz
        </a>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase tracking-wider">
                        <th class="py-3.5 px-4">Title</th>
                        <th class="py-3.5 px-4">Lesson</th>
                        <th class="py-3.5 px-4">Course</th>
                        <th class="py-3.5 px-4 text-center">Questions</th>
                        <th class="py-3.5 px-4 text-center">Pass %</th>
                        <th class="py-3.5 px-4 text-center">Time Limit</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-slate-700">
                    @forelse($quizzes as $quiz)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-3.5 px-4 font-bold text-slate-900">{{ $quiz->title }}</td>
                            <td class="py-3.5 px-4 text-xs font-semibold text-slate-700">{{ $quiz->lesson?->title ?? '—' }}</td>
                            <td class="py-3.5 px-4 text-xs text-slate-500">{{ $quiz->lesson?->module?->course?->title_en ?? '—' }}</td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    {{ $quiz->questions_count }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-bold text-emerald-600">{{ $quiz->pass_percentage }}%</td>
                            <td class="py-3.5 px-4 text-center text-xs text-slate-500">
                                {{ $quiz->time_limit_minutes ? $quiz->time_limit_minutes . ' mins' : 'Unlimited' }}
                            </td>
                            <td class="py-3.5 px-4 text-right space-x-2">
                                <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-sky-100 text-slate-700 hover:text-sky-800 text-xs font-bold transition-all">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.quizzes.destroy', $quiz) }}" class="inline-block" onsubmit="return confirm('Delete this quiz?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold transition-all">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400 text-sm">No quizzes found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($quizzes->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50/50">{{ $quizzes->links() }}</div>
        @endif
    </div>

</div>
@endsection
