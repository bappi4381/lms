<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LessonController extends Controller
{
    private const TYPES = [
        Lesson::TYPE_VIDEO      => 'Video',
        Lesson::TYPE_PDF        => 'PDF',
        Lesson::TYPE_QUIZ       => 'Quiz',
        Lesson::TYPE_ASSIGNMENT => 'Assignment',
    ];

    public function index(Request $request): View
    {
        $query = Lesson::query()->with(['module.course']);

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhereHas('module', fn ($q) => $q->where('title', 'like', "%{$search}%"));
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($moduleId = $request->input('module_id')) {
            $query->where('module_id', $moduleId);
        }

        $lessons = $query->orderBy('module_id')->orderBy('order')
            ->paginate(20)->withQueryString();

        $modules = Module::with('course')->orderBy('title')->get()
            ->mapWithKeys(fn ($m) => [$m->id => ($m->course ? $m->course->title_en . ' — ' : '') . $m->title]);

        return view('admin.lessons.index', [
            'lessons' => $lessons,
            'modules' => $modules,
            'types'   => self::TYPES,
        ]);
    }

    public function create(Request $request): View
    {
        $modules = Module::with('course')->orderBy('title')->get()
            ->mapWithKeys(fn ($m) => [$m->id => ($m->course ? $m->course->title_en . ' — ' : '') . $m->title]);

        $selectedModuleId = $request->input('module_id');

        return view('admin.lessons.create', [
            'modules'          => $modules,
            'types'            => self::TYPES,
            'selectedModuleId' => $selectedModuleId,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'module_id'        => 'required|exists:modules,id',
            'title'            => 'required|string|max:255',
            'type'             => 'required|in:video,pdf,quiz,assignment',
            'video_id'         => 'nullable|string|max:255',
            'pdf_url'          => 'nullable|url|max:500',
            'content'          => 'nullable|string',
            'duration_seconds' => 'nullable|integer|min:0',
            'is_preview'       => 'boolean',
            'order'            => 'required|integer|min:0',
        ]);

        $data['is_preview'] = $request->boolean('is_preview');

        Lesson::create($data);

        return redirect()->route('admin.lessons.index')
            ->with('success', 'Lesson created successfully!');
    }

    public function edit(Lesson $lesson): View
    {
        $modules = Module::with('course')->orderBy('title')->get()
            ->mapWithKeys(fn ($m) => [$m->id => ($m->course ? $m->course->title_en . ' — ' : '') . $m->title]);

        return view('admin.lessons.edit', [
            'lesson'  => $lesson->load(['module.course', 'quiz', 'assignment']),
            'modules' => $modules,
            'types'   => self::TYPES,
        ]);
    }

    public function update(Request $request, Lesson $lesson): RedirectResponse
    {
        $data = $request->validate([
            'module_id'        => 'required|exists:modules,id',
            'title'            => 'required|string|max:255',
            'type'             => 'required|in:video,pdf,quiz,assignment',
            'video_id'         => 'nullable|string|max:255',
            'pdf_url'          => 'nullable|url|max:500',
            'content'          => 'nullable|string',
            'duration_seconds' => 'nullable|integer|min:0',
            'is_preview'       => 'boolean',
            'order'            => 'required|integer|min:0',
        ]);

        $data['is_preview'] = $request->boolean('is_preview');

        $lesson->update($data);

        return redirect()->route('admin.lessons.index')
            ->with('success', 'Lesson updated successfully!');
    }

    public function destroy(Lesson $lesson): RedirectResponse
    {
        if ($lesson->quiz || $lesson->assignment) {
            return redirect()->back()
                ->with('error', 'Cannot delete a lesson associated with a quiz or assignment!');
        }

        $lesson->delete();

        return redirect()->route('admin.lessons.index')
            ->with('success', 'Lesson deleted successfully!');
    }
}
