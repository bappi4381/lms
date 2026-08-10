{{-- Shared enrollment form partial --}}
{{-- Variables: $enrollment (null = create), $users, $courses, $paymentStatuses --}}

<div class="bg-white rounded-2xl border border-slate-200/80 p-6 space-y-5">

    <!-- User & Course -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Student <span class="text-rose-500">*</span></label>
            <x-searchable-select name="user_id"
                                 :options="$users"
                                 :value="old('user_id', $enrollment?->user_id)"
                                 placeholder="— Select Student —"
                                 searchPlaceholder="Search student..."
                                 required="true" />
            @error('user_id')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Course <span class="text-rose-500">*</span></label>
            <x-searchable-select name="course_id"
                                 :options="$courses"
                                 :value="old('course_id', $enrollment?->course_id)"
                                 placeholder="— Select Course —"
                                 searchPlaceholder="Search course..."
                                 required="true" />
            @error('course_id')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <!-- Payment Status & Amount -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Payment Status <span class="text-rose-500">*</span></label>
            <x-searchable-select name="payment_status"
                                 :options="$paymentStatuses"
                                 :value="old('payment_status', $enrollment?->payment_status ?? 'pending')"
                                 placeholder="Select Status"
                                 searchPlaceholder="Search status..."
                                 required="true" />
            @error('payment_status')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Amount Paid (BDT)</label>
            <div class="flex items-center">
                <span class="px-3 py-2.5 bg-slate-100 border border-r-0 border-slate-300 rounded-l-xl text-sm font-bold text-slate-600">৳</span>
                <input type="number" name="amount_paid" step="0.01" min="0"
                       value="{{ old('amount_paid', $enrollment?->amount_paid ?? 0) }}"
                       class="flex-1 px-4 py-2.5 rounded-r-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>
            @error('amount_paid')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>

    <!-- Transaction ID -->
    <div>
        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Transaction ID</label>
        <input type="text" name="transaction_id"
               value="{{ old('transaction_id', $enrollment?->transaction_id) }}"
               placeholder="e.g. TXN-SSLCOMMERZ-12345"
               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 font-mono">
        @error('transaction_id')<p class="text-rose-600 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    <!-- Enrolled At & Expires At -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Enrolled At</label>
            <input type="datetime-local" name="enrolled_at"
                   value="{{ old('enrolled_at', $enrollment?->enrolled_at?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}"
                   class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
        </div>
        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-1.5">Expires At <span class="text-slate-400 text-xs font-normal">(optional)</span></label>
            <input type="datetime-local" name="expires_at"
                   value="{{ old('expires_at', $enrollment?->expires_at?->format('Y-m-d\TH:i')) }}"
                   placeholder="Leave empty for lifetime access"
                   class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
            <p class="text-xs text-slate-400 mt-1">Leave empty for lifetime access</p>
        </div>
    </div>

</div>
