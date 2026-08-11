{{-- Shared enrollment form partial --}}
{{-- Variables: $enrollment (null = create), $users, $courses, $paymentStatuses --}}

<div class="admin-card">

    <!-- User & Course -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
            <label class="admin-label">Student <span style="color:var(--a-brick)">*</span></label>
            <x-searchable-select name="user_id"
                                 :options="$users"
                                 :value="old('user_id', $enrollment?->user_id)"
                                 placeholder="— Select Student —"
                                 searchPlaceholder="Search student..."
                                 required="true" />
            @error('user_id')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="admin-label">Course <span style="color:var(--a-brick)">*</span></label>
            <x-searchable-select name="course_id"
                                 :options="$courses"
                                 :value="old('course_id', $enrollment?->course_id)"
                                 placeholder="— Select Course —"
                                 searchPlaceholder="Search course..."
                                 required="true" />
            @error('course_id')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
        </div>
    </div>

    <!-- Payment Status & Amount -->
    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
            <label class="admin-label">Payment Status <span style="color:var(--a-brick)">*</span></label>
            <x-searchable-select name="payment_status"
                                 :options="$paymentStatuses"
                                 :value="old('payment_status', $enrollment?->payment_status ?? 'pending')"
                                 placeholder="Select Status"
                                 searchPlaceholder="Search status..."
                                 required="true" />
            @error('payment_status')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="admin-label">Amount Paid (BDT)</label>
            <div class="flex items-center">
                <span class="flex h-[38px] items-center rounded-l-ledger border px-3 text-[13px] font-bold" style="background:var(--a-panel); border-color:var(--a-line); color:var(--a-ink-soft)">৳</span>
                <input type="number" name="amount_paid" step="0.01" min="0"
                       value="{{ old('amount_paid', $enrollment?->amount_paid ?? 0) }}"
                       class="admin-input flex-1 !rounded-l-none">
            </div>
            @error('amount_paid')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
        </div>
    </div>

    <!-- Transaction ID -->
    <div class="mt-4">
        <label class="admin-label">Transaction ID</label>
        <input type="text" name="transaction_id"
               value="{{ old('transaction_id', $enrollment?->transaction_id) }}"
               placeholder="e.g. TXN-SSLCOMMERZ-12345"
               class="admin-input font-mono">
        @error('transaction_id')<p class="mt-1 text-[12px] font-semibold" style="color:var(--a-brick)">{{ $message }}</p>@enderror
    </div>

    <!-- Enrolled At & Expires At -->
    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
        <div>
            <label class="admin-label">Enrolled At</label>
            <input type="datetime-local" name="enrolled_at"
                   value="{{ old('enrolled_at', $enrollment?->enrolled_at?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i')) }}"
                   class="admin-input">
        </div>
        <div>
            <label class="admin-label">Expires At <span class="text-[11px] font-normal" style="color:var(--a-ink-faint)">(optional)</span></label>
            <input type="datetime-local" name="expires_at"
                   value="{{ old('expires_at', $enrollment?->expires_at?->format('Y-m-d\TH:i')) }}"
                   placeholder="Leave empty for lifetime access"
                   class="admin-input">
            <p class="mt-1 text-[11px]" style="color:var(--a-ink-faint)">Leave empty for lifetime access</p>
        </div>
    </div>

</div>
