<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Lesson;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Assignment::query()->with(['lesson.module.course'])->withCount('submissions');

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhereHas('lesson', fn ($q) => $q->where('title', 'like', "%{$search}%"));
        }

        $assignments = $query->latest()->paginate(15)->withQueryString();

        return view('admin.assignments.index', compact('assignments'));
    }

    public function create(): View
    {
        $lessons = Lesson::where('type', Lesson::TYPE_ASSIGNMENT)
            ->with('module.course')
            ->get()
            ->mapWithKeys(fn ($l) => [$l->id => ($l->module?->course ? $l->module->course->title_en . ' — ' : '') . ($l->module ? $l->module->title . ' — ' : '') . $l->title]);

        return view('admin.assignments.create', compact('lessons'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'lesson_id'    => 'required|exists:lessons,id|unique:assignments,lesson_id',
            'title'        => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'max_points'   => 'required|integer|min:1',
            'due_at'       => 'nullable|date',
        ]);

        Assignment::create($data);

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Assignment created successfully!');
    }

    public function edit(Assignment $assignment): View
    {
        $lessons = Lesson::where('type', Lesson::TYPE_ASSIGNMENT)
            ->orWhere('id', $assignment->lesson_id)
            ->with('module.course')
            ->get()
            ->mapWithKeys(fn ($l) => [$l->id => ($l->module?->course ? $l->module->course->title_en . ' — ' : '') . ($l->module ? $l->module->title . ' — ' : '') . $l->title]);

        return view('admin.assignments.edit', compact('assignment', 'lessons'));
    }

    public function update(Request $request, Assignment $assignment): RedirectResponse
    {
        $data = $request->validate([
            'lesson_id'    => "required|exists:lessons,id|unique:assignments,lesson_id,{$assignment->id}",
            'title'        => 'required|string|max:255',
            'instructions' => 'nullable|string',
            'max_points'   => 'required|integer|min:1',
            'due_at'       => 'nullable|date',
        ]);

        $assignment->update($data);

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Assignment updated successfully!');
    }

    public function destroy(Assignment $assignment): RedirectResponse
    {
        if ($assignment->submissions()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete assignment with existing student submissions!');
        }

        $assignment->delete();

        return redirect()->route('admin.assignments.index')
            ->with('success', 'Assignment deleted successfully!');
    }
}
