<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Module;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ModuleController extends Controller
{
    public function index(Request $request): View
    {
        $query = Module::query()->with(['course', 'lessons']);

        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhereHas('course', fn ($q) => $q->where('title_en', 'like', "%{$search}%"));
        }

        if ($courseId = $request->input('course_id')) {
            $query->where('course_id', $courseId);
        }

        $modules = $query->orderBy('course_id')->orderBy('order')
            ->paginate(20)->withQueryString();

        $courses = Course::orderBy('title_en')->pluck('title_en', 'id');

        return view('admin.modules.index', compact('modules', 'courses'));
    }

    public function create(): View
    {
        $courses = Course::orderBy('title_en')->pluck('title_en', 'id');
        return view('admin.modules.create', compact('courses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'course_id'           => 'required|exists:courses,id',
            'title'               => 'required|string|max:255',
            'order'               => 'required|integer|min:0',
            'live_class_provider' => 'nullable|in:zoom,google_meet,other',
            'live_class_link'     => 'nullable|url|max:500',
            'live_class_at'       => 'nullable|date',
        ]);

        Module::create($data);

        return redirect()->route('admin.modules.index')
            ->with('success', 'Module created successfully!');
    }

    public function edit(Module $module): View
    {
        $courses = Course::orderBy('title_en')->pluck('title_en', 'id');
        $lessons = $module->lessons()->orderBy('order')->get();
        return view('admin.modules.edit', compact('module', 'courses', 'lessons'));
    }

    public function update(Request $request, Module $module): RedirectResponse
    {
        $data = $request->validate([
            'course_id'           => 'required|exists:courses,id',
            'title'               => 'required|string|max:255',
            'order'               => 'required|integer|min:0',
            'live_class_provider' => 'nullable|in:zoom,google_meet,other',
            'live_class_link'     => 'nullable|url|max:500',
            'live_class_at'       => 'nullable|date',
        ]);

        $module->update($data);

        return redirect()->route('admin.modules.index')
            ->with('success', 'Module updated successfully!');
    }

    public function destroy(Module $module): RedirectResponse
    {
        if ($module->lessons()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete a module that has lessons. Remove lessons first.');
        }

        $module->delete();

        return redirect()->route('admin.modules.index')
            ->with('success', 'Module deleted successfully!');
    }
}
