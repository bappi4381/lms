{{-- Shared quiz-details fields. Variables: $quiz (null for create), $lessons --}}
<div class="admin-card">
    <h3 class="admin-card-title">Quiz Details</h3>

    <div>
        <label class="admin-label">Lesson (type = quiz) <span style="color:var(--a-brick)">*</span></label>
        <x-searchable-select name="lesson_id"
                             :options="$lessons"
                             :value="old('lesson_id', $quiz?->lesson_id)"
                             placeholder="— Select Quiz Lesson —"
                             searchPlaceholder="Search quiz lesson..."
                             required="true" />
    </div>

    <div class="mt-4">
        <label class="admin-label">Quiz Title <span style="color:var(--a-brick)">*</span></label>
        <input type="text" name="title" required value="{{ old('title', $quiz?->title) }}" placeholder="e.g. Midterm Evaluation Quiz" class="admin-input">
    </div>

    <div class="mt-4 grid grid-cols-2 gap-4">
        <div>
            <label class="admin-label">Pass Percentage (%) <span style="color:var(--a-brick)">*</span></label>
            <input type="number" name="pass_percentage" min="1" max="100" value="{{ old('pass_percentage', $quiz?->pass_percentage ?? 60) }}" required class="admin-input">
        </div>
        <div>
            <label class="admin-label">Time Limit (Minutes)</label>
            <input type="number" name="time_limit_minutes" min="1" value="{{ old('time_limit_minutes', $quiz?->time_limit_minutes) }}" placeholder="Leave blank for unlimited" class="admin-input">
        </div>
    </div>

    <div class="mt-4">
        <label class="admin-label">Instructions</label>
        <textarea name="instructions" rows="2" placeholder="Instructions for students..." class="admin-textarea resize-none">{{ old('instructions', $quiz?->instructions) }}</textarea>
    </div>
</div>

<!-- Questions Repeater -->
<div class="admin-card mt-6">
    <div class="flex items-center justify-between border-b pb-3" style="border-color:var(--a-line-soft)">
        <h3 class="font-ledger text-[15px] font-semibold" style="color:var(--a-ink)">Questions &amp; Options</h3>
        <span class="text-[12px] font-semibold" style="color:var(--a-ink-faint)" x-text="questions.length + ' Question(s)'"></span>
    </div>

    <div class="mt-5 space-y-5">
        <template x-for="(q, qIdx) in questions" :key="qIdx">
            <div class="relative space-y-4 rounded-ledger border p-5" style="border-color:var(--a-line); background:var(--a-page)">
                <div class="flex items-center justify-between">
                    <span class="text-[13px] font-semibold" style="color:var(--a-accent)" x-text="'Question #' + (qIdx + 1)"></span>
                    <button type="button" @click="removeQuestion(qIdx)" class="text-[12px] font-semibold" style="color:var(--a-brick)">Remove Question</button>
                </div>

                <div>
                    <input type="text" x-model="q.question" placeholder="Enter question text..." class="admin-input font-semibold">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="admin-label !text-[11px]">Question Type</label>
                        <select x-model="q.type" class="admin-select">
                            <option value="single">Single Choice (Radio)</option>
                            <option value="multiple">Multiple Choice (Checkbox)</option>
                            <option value="true_false">True / False</option>
                        </select>
                    </div>
                    <div>
                        <label class="admin-label !text-[11px]">Points</label>
                        <input type="number" x-model="q.points" min="1" class="admin-input">
                    </div>
                </div>

                <!-- Options / Choices -->
                <div class="space-y-2 pt-2">
                    <label class="admin-label !text-[11px]">Choices / Options</label>
                    <template x-for="(c, cIdx) in q.choices" :key="cIdx">
                        <div class="flex items-center gap-3">
                            <input type="checkbox" x-model="c.is_correct" class="h-4 w-4 rounded" style="accent-color:var(--a-accent)">
                            <input type="text" x-model="c.choice_text" placeholder="Option text..." class="admin-input flex-1 !py-1.5 text-[13px]">
                            <button type="button" @click="removeChoice(qIdx, cIdx)" class="text-[13px]" style="color:var(--a-ink-faint)">✕</button>
                        </div>
                    </template>
                    <button type="button" @click="addChoice(qIdx)" class="mt-1 text-[12px] font-semibold underline" style="color:var(--a-accent)">+ Add Option</button>
                </div>
            </div>
        </template>
    </div>

    <button type="button" @click="addQuestion()" class="mt-5 w-full rounded-ledger border-2 border-dashed py-3 text-[13px] font-semibold transition-colors" style="border-color:var(--a-line); color:var(--a-ink-soft)">
        + Add Question
    </button>
</div>
