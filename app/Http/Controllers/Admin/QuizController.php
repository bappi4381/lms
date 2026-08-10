<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\QuizChoice;
use App\Models\QuizQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class QuizController extends Controller
{
    public function index(Request $request): View
    {
        $query = Quiz::query()->with(['lesson.module.course'])->withCount('questions');

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhereHas('lesson', fn ($q) => $q->where('title', 'like', "%{$search}%"));
        }

        $quizzes = $query->latest()->paginate(15)->withQueryString();

        return view('admin.quizzes.index', compact('quizzes'));
    }

    public function create(): View
    {
        $lessons = Lesson::where('type', Lesson::TYPE_QUIZ)
            ->with('module.course')
            ->get()
            ->mapWithKeys(fn ($l) => [$l->id => ($l->module?->course ? $l->module->course->title_en . ' — ' : '') . ($l->module ? $l->module->title . ' — ' : '') . $l->title]);

        return view('admin.quizzes.create', compact('lessons'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'lesson_id'          => 'required|exists:lessons,id|unique:quizzes,lesson_id',
            'title'              => 'required|string|max:255',
            'instructions'       => 'nullable|string',
            'pass_percentage'    => 'required|integer|between:1,100',
            'time_limit_minutes' => 'nullable|integer|min:1',
            'questions_json'     => 'nullable|string',
        ]);

        DB::transaction(function () use ($data) {
            $quiz = Quiz::create([
                'lesson_id'          => $data['lesson_id'],
                'title'              => $data['title'],
                'instructions'       => $data['instructions'],
                'pass_percentage'    => $data['pass_percentage'],
                'time_limit_minutes' => $data['time_limit_minutes'] ?? null,
            ]);

            $this->syncQuestions($quiz, $data['questions_json'] ?? '[]');
        });

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Quiz created successfully with questions!');
    }

    public function edit(Quiz $quiz): View
    {
        $quiz->load(['questions.choices', 'lesson.module.course']);

        $lessons = Lesson::where('type', Lesson::TYPE_QUIZ)
            ->orWhere('id', $quiz->lesson_id)
            ->with('module.course')
            ->get()
            ->mapWithKeys(fn ($l) => [$l->id => ($l->module?->course ? $l->module->course->title_en . ' — ' : '') . ($l->module ? $l->module->title . ' — ' : '') . $l->title]);

        return view('admin.quizzes.edit', compact('quiz', 'lessons'));
    }

    public function update(Request $request, Quiz $quiz): RedirectResponse
    {
        $data = $request->validate([
            'lesson_id'          => "required|exists:lessons,id|unique:quizzes,lesson_id,{$quiz->id}",
            'title'              => 'required|string|max:255',
            'instructions'       => 'nullable|string',
            'pass_percentage'    => 'required|integer|between:1,100',
            'time_limit_minutes' => 'nullable|integer|min:1',
            'questions_json'     => 'nullable|string',
        ]);

        DB::transaction(function () use ($quiz, $data) {
            $quiz->update([
                'lesson_id'          => $data['lesson_id'],
                'title'              => $data['title'],
                'instructions'       => $data['instructions'],
                'pass_percentage'    => $data['pass_percentage'],
                'time_limit_minutes' => $data['time_limit_minutes'] ?? null,
            ]);

            $this->syncQuestions($quiz, $data['questions_json'] ?? '[]');
        });

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Quiz updated successfully!');
    }

    public function destroy(Quiz $quiz): RedirectResponse
    {
        if ($quiz->attempts()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete quiz with existing student attempts!');
        }

        DB::transaction(function () use ($quiz) {
            foreach ($quiz->questions as $q) {
                $q->choices()->delete();
            }
            $quiz->questions()->delete();
            $quiz->delete();
        });

        return redirect()->route('admin.quizzes.index')
            ->with('success', 'Quiz deleted successfully!');
    }

    private function syncQuestions(Quiz $quiz, string $json): void
    {
        $questionsData = json_decode($json, true) ?? [];

        // Delete existing questions & choices
        foreach ($quiz->questions as $q) {
            $q->choices()->delete();
        }
        $quiz->questions()->delete();

        foreach ($questionsData as $qIdx => $qItem) {
            if (empty($qItem['question'])) continue;

            $question = QuizQuestion::create([
                'quiz_id'  => $quiz->id,
                'question' => $qItem['question'],
                'type'     => $qItem['type'] ?? 'single',
                'points'   => $qItem['points'] ?? 1,
                'order'    => $qIdx + 1,
            ]);

            if (!empty($qItem['choices']) && is_array($qItem['choices'])) {
                foreach ($qItem['choices'] as $cIdx => $cItem) {
                    if (empty($cItem['choice_text'])) continue;

                    QuizChoice::create([
                        'quiz_question_id' => $question->id,
                        'choice_text'      => $cItem['choice_text'],
                        'is_correct'       => !empty($cItem['is_correct']),
                        'order'            => $cIdx + 1,
                    ]);
                }
            }
        }
    }
}
