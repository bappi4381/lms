@extends('layouts.admin')

@section('title', 'Add Quiz')
@section('page_heading', 'Add New Quiz')

@section('content')
<div class="max-w-4xl mx-auto space-y-5" x-data="quizBuilder()">
    <a href="{{ route('admin.quizzes.index') }}" class="text-sm text-slate-500 hover:text-sky-600 flex items-center gap-1 w-fit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Back to Quizzes
    </a>

    <form method="POST" action="{{ route('admin.quizzes.store') }}" @submit="syncJSON()" class="space-y-6">
        @csrf
        <input type="hidden" name="questions_json" id="questions_json" :value="JSON.stringify(questions)">

        <!-- Basic Quiz Info -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 space-y-4">
            <h3 class="font-bold text-slate-800 text-base border-b border-slate-100 pb-3">Quiz Details</h3>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Lesson (type = quiz) <span class="text-rose-500">*</span></label>
                <select name="lesson_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 bg-white">
                    <option value="">— Select Quiz Lesson —</option>
                    @foreach($lessons as $id => $title)
                        <option value="{{ $id }}" {{ old('lesson_id') == $id ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Quiz Title <span class="text-rose-500">*</span></label>
                <input type="text" name="title" required value="{{ old('title') }}" placeholder="e.g. Midterm Evaluation Quiz" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Pass Percentage (%) <span class="text-rose-500">*</span></label>
                    <input type="number" name="pass_percentage" min="1" max="100" value="{{ old('pass_percentage', 60) }}" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">Time Limit (Minutes)</label>
                    <input type="number" name="time_limit_minutes" min="1" value="{{ old('time_limit_minutes') }}" placeholder="Leave blank for unlimited" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Instructions</label>
                <textarea name="instructions" rows="2" placeholder="Instructions for students..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-sky-500 resize-none">{{ old('instructions') }}</textarea>
            </div>
        </div>

        <!-- Questions Repeater -->
        <div class="bg-white rounded-2xl border border-slate-200/80 p-6 space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="font-bold text-slate-800 text-base">Questions & Options</h3>
                <span class="text-xs text-slate-400 font-semibold" x-text="questions.length + ' Question(s)'"></span>
            </div>

            <div class="space-y-6">
                <template x-for="(q, qIdx) in questions" :key="qIdx">
                    <div class="bg-slate-50 rounded-2xl border border-slate-200 p-5 space-y-4 relative">
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-sky-700 text-sm" x-text="'Question #' + (qIdx + 1)"></span>
                            <button type="button" @click="removeQuestion(qIdx)" class="text-rose-500 hover:text-rose-700 text-xs font-bold">Remove Question</button>
                        </div>

                        <div>
                            <input type="text" x-model="q.question" placeholder="Enter question text..." class="w-full px-4 py-2 rounded-xl border border-slate-300 text-sm font-semibold bg-white">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1">Question Type</label>
                                <select x-model="q.type" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 text-xs bg-white">
                                    <option value="single">Single Choice (Radio)</option>
                                    <option value="multiple">Multiple Choice (Checkbox)</option>
                                    <option value="true_false">True / False</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-600 mb-1">Points</label>
                                <input type="number" x-model="q.points" min="1" class="w-full px-3 py-1.5 rounded-lg border border-slate-300 text-xs bg-white">
                            </div>
                        </div>

                        <!-- Options / Choices -->
                        <div class="space-y-2 pt-2">
                            <label class="block text-xs font-bold text-slate-500">Choices / Options</label>
                            <template x-for="(c, cIdx) in q.choices" :key="cIdx">
                                <div class="flex items-center gap-3">
                                    <input type="checkbox" x-model="c.is_correct" class="w-4 h-4 text-sky-600 rounded">
                                    <input type="text" x-model="c.choice_text" placeholder="Option text..." class="flex-1 px-3 py-1.5 rounded-lg border border-slate-300 text-xs bg-white">
                                    <button type="button" @click="removeChoice(qIdx, cIdx)" class="text-slate-400 hover:text-rose-500 text-xs">✕</button>
                                </div>
                            </template>
                            <button type="button" @click="addChoice(qIdx)" class="text-xs text-sky-600 hover:underline font-bold mt-1">+ Add Option</button>
                        </div>
                    </div>
                </template>
            </div>

            <button type="button" @click="addQuestion()" class="w-full py-3 rounded-xl border-2 border-dashed border-slate-300 text-slate-600 hover:border-sky-500 hover:text-sky-600 font-bold text-sm transition-all">
                + Add Question
            </button>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('admin.quizzes.index') }}" class="px-6 py-2.5 rounded-xl border border-slate-300 text-slate-700 font-semibold text-sm">Cancel</a>
            <button type="submit" class="px-8 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm shadow-md">Create Quiz</button>
        </div>
    </form>
</div>

<script>
function quizBuilder() {
    return {
        questions: [],
        init() {
            if (this.questions.length === 0) {
                this.addQuestion();
            }
        },
        addQuestion() {
            this.questions.push({
                question: '',
                type: 'single',
                points: 1,
                choices: [
                    { choice_text: '', is_correct: false },
                    { choice_text: '', is_correct: false }
                ]
            });
        },
        removeQuestion(qIdx) {
            this.questions.splice(qIdx, 1);
        },
        addChoice(qIdx) {
            this.questions[qIdx].choices.push({ choice_text: '', is_correct: false });
        },
        removeChoice(qIdx, cIdx) {
            this.questions[qIdx].choices.splice(cIdx, 1);
        },
        syncJSON() {
            document.getElementById('questions_json').value = JSON.stringify(this.questions);
        }
    }
}
</script>
@endsection
