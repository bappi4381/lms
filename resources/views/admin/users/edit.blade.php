@extends('layouts.admin')

@section('title', 'Edit User: ' . $user->name)
@section('page_heading', 'Edit User')

@section('content')
<div class="max-w-3xl mx-auto space-y-5">
    <a href="{{ route('admin.users.index') }}" class="text-sm text-slate-500 hover:text-sky-600 flex items-center gap-1 w-fit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Users
    </a>

    <!-- User Summary Card -->
    <div class="bg-gradient-to-r from-sky-50 to-indigo-50 border border-sky-200 rounded-2xl p-5 flex items-center gap-4">
        <div class="w-12 h-12 rounded-full font-bold text-lg flex items-center justify-center shrink-0 uppercase
            {{ $user->hasRole('admin') ? 'bg-rose-200 text-rose-800' : ($user->hasRole('instructor') ? 'bg-violet-200 text-violet-800' : 'bg-sky-200 text-sky-800') }}">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
            <div class="font-bold text-slate-900">{{ $user->name }}</div>
            <div class="text-sm text-slate-500">{{ $user->email }}</div>
            <div class="flex gap-1 mt-1.5">
                @foreach($user->roles as $role)
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold capitalize
                        {{ match($role->name) { 'admin' => 'bg-rose-100 text-rose-700', 'instructor' => 'bg-violet-100 text-violet-700', 'student' => 'bg-emerald-100 text-emerald-700', default => 'bg-slate-100 text-slate-600' } }}">
                        {{ $role->name }}
                    </span>
                @endforeach
            </div>
        </div>
        <div class="text-right shrink-0">
            <div class="text-xs text-slate-400">Joined</div>
            <div class="text-sm font-bold text-slate-700">{{ $user->created_at->format('d M Y') }}</div>
            <div class="text-xs text-slate-400 mt-1">{{ $enrollments->count() }} enrollment(s)</div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-5">
        @csrf @method('PUT')
        @include('admin.users._form', ['user' => $user, 'userRoles' => $userRoles])
        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.users.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-sm hover:bg-slate-50 transition-all">Cancel</a>
            <button type="submit" class="px-8 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-md transition-all">Update User</button>
        </div>
    </form>

    <!-- Recent Enrollments mini table -->
    @if($enrollments->count() > 0)
        <div class="bg-white rounded-2xl border border-slate-200/80 p-5">
            <h3 class="font-bold text-slate-800 text-sm mb-3">Recent Enrollments</h3>
            <div class="space-y-2">
                @foreach($enrollments as $enrollment)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-200">
                        <div class="text-sm font-semibold text-slate-700 truncate max-w-xs">
                            {{ $enrollment->course?->title_en ?? '—' }}
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <span class="text-xs font-bold text-slate-500">৳{{ number_format($enrollment->amount_paid, 0) }}</span>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold capitalize
                                {{ $enrollment->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-800' : ($enrollment->payment_status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-600') }}">
                                {{ $enrollment->payment_status }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
