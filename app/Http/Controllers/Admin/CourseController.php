<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourseRequest;
use App\Models\Category;
use App\Models\Course;
use App\Models\Module;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $query = Course::query()
            ->with(['category', 'instructor'])
            ->withCount('enrollments');

        // Search Filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title_en', 'like', "%{$search}%")
                  ->orWhere('title_bn', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Category Filter
        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        // Published Filter
        if ($request->has('is_published') && $request->input('is_published') !== '') {
            $query->where('is_published', (bool) $request->input('is_published'));
        }

        $courses = $query->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        $categories = Category::where('is_active', true)->orderBy('order')->get();

        return view('admin.courses.index', compact('courses', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('order')->get()
            ->mapWithKeys(fn ($cat) => [$cat->id => "{$cat->name_en} / {$cat->name_bn}"]);

        $instructors = User::role('instructor')->pluck('name', 'id');

        return view('admin.courses.create', compact('categories', 'instructors'));
    }

    public function store(CourseRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Auto-generate slug if missing
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title_en'] ?? $data['title_bn'] ?? 'course');
        }

        // Sync title field
        $data['title'] = $data['title_en'] ?? $data['title_bn'] ?? '';

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail_upload')) {
            $data['thumbnail'] = $request->file('thumbnail_upload')
                ->store('course-thumbnails', 'public');
        }

        // Decode JSON array fields from form + attach uploaded project images
        $data = $this->processArrayFields($data, $request);

        if ($error = $this->missingProjectImageError($data)) {
            return redirect()->back()->withErrors(['projects' => $error])->withInput();
        }

        Course::create($data);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course created successfully!');
    }

    public function edit(Course $course): View
    {
        $categories = Category::where('is_active', true)->orderBy('order')->get()
            ->mapWithKeys(fn ($cat) => [$cat->id => "{$cat->name_en} / {$cat->name_bn}"]);

        $instructors = User::role('instructor')->pluck('name', 'id');

        $modules = $course->modules()->orderBy('order')->get();

        return view('admin.courses.edit', compact('course', 'categories', 'instructors', 'modules'));
    }

    public function update(CourseRequest $request, Course $course): RedirectResponse
    {
        $data = $request->validated();

        // Sync title field
        $data['title'] = $data['title_en'] ?? $data['title_bn'] ?? $course->title;

        // Handle thumbnail upload (replace old file)
        if ($request->hasFile('thumbnail_upload')) {
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail_upload')
                ->store('course-thumbnails', 'public');
        }

        // Decode JSON array fields from form + attach uploaded project images
        $data = $this->processArrayFields($data, $request);

        if ($error = $this->missingProjectImageError($data)) {
            return redirect()->back()->withErrors(['projects' => $error])->withInput();
        }

        $course->update($data);

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course updated successfully!');
    }

    public function destroy(Course $course): RedirectResponse
    {
        if ($course->enrollments()->whereIn('payment_status', ['paid'])->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete a course with paid enrollments!');
        }

        // Delete thumbnail from storage
        if ($course->thumbnail) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($course->thumbnail);
        }

        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Course deleted successfully!');
    }

    // ── Module (sub-resource) management ────────────────────────────

    public function modules(Course $course): View
    {
        $modules = $course->modules()->orderBy('order')->get();
        return view('admin.courses.modules', compact('course', 'modules'));
    }

    public function storeModule(Request $request, Course $course): RedirectResponse
    {
        $data = $request->validate([
            'title'               => 'required|string|max:255',
            'order'               => 'required|integer|min:0',
            'live_class_provider' => 'nullable|in:zoom,google_meet,other',
            'live_class_link'     => 'nullable|url|max:500',
            'live_class_at'       => 'nullable|date',
        ]);

        $course->modules()->create($data);

        return redirect()->route('admin.courses.modules', $course)
            ->with('success', 'Module added successfully!');
    }

    public function updateModule(Request $request, Course $course, Module $module): RedirectResponse
    {
        abort_if($module->course_id !== $course->id, 403);

        $data = $request->validate([
            'title'               => 'required|string|max:255',
            'order'               => 'required|integer|min:0',
            'live_class_provider' => 'nullable|in:zoom,google_meet,other',
            'live_class_link'     => 'nullable|url|max:500',
            'live_class_at'       => 'nullable|date',
        ]);

        $module->update($data);

        return redirect()->route('admin.courses.modules', $course)
            ->with('success', 'Module updated successfully!');
    }

    public function destroyModule(Course $course, Module $module): RedirectResponse
    {
        abort_if($module->course_id !== $course->id, 403);
        $module->delete();

        return redirect()->route('admin.courses.modules', $course)
            ->with('success', 'Module deleted successfully!');
    }

    // ── Private helpers ─────────────────────────────────────────────

    /**
     * Process array fields that come as JSON strings from the form.
     * Fields that are cast as arrays in the model should be decoded.
     */
    private function processArrayFields(array $data, Request $request): array
    {
        $arrayFields = [
            'key_features_en', 'key_features_bn',
            'tools_en', 'tools_bn',
            'course_includes',
            'projects_en', 'projects_bn',
            'faqs_en', 'faqs_bn',
        ];

        foreach ($arrayFields as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $decoded = json_decode($data[$field], true);
                $data[$field] = is_array($decoded) ? $decoded : [];
            } elseif (! isset($data[$field])) {
                $data[$field] = [];
            }
        }

        // Attach uploaded project preview images (parity with Filament's
        // FileUpload::make('image') on the projects_en/projects_bn repeaters).
        $data['projects_en'] = $this->attachProjectImages($request, 'projects_en_images', $data['projects_en']);
        $data['projects_bn'] = $this->attachProjectImages($request, 'projects_bn_images', $data['projects_bn']);

        unset($data['projects_en_images'], $data['projects_bn_images']);

        return $data;
    }

    /**
     * Merge uploaded files into project repeater items by submission order.
     * Existing rows keep their previously stored image path unless a new
     * file is uploaded for that row.
     */
    private function attachProjectImages(Request $request, string $filesField, array $items): array
    {
        $files = $request->file($filesField, []);

        foreach ($items as $index => &$item) {
            $uploaded = $files[$index] ?? null;

            if ($uploaded instanceof \Illuminate\Http\UploadedFile && $uploaded->isValid()) {
                $item['image'] = $uploaded->store('course-projects', 'public');
            } else {
                $item['image'] = $item['image'] ?? '';
            }
        }

        return $items;
    }

    /**
     * Every project row must have a preview image (mirrors the Filament
     * FileUpload::make('image')->required() rule). Returns an error message
     * if any project row is missing an image, or null when all rows are valid.
     */
    private function missingProjectImageError(array $data): ?string
    {
        $allProjects = array_merge($data['projects_en'] ?? [], $data['projects_bn'] ?? []);

        foreach ($allProjects as $project) {
            if (empty($project['image'])) {
                return 'Each project must have a preview image uploaded.';
            }
        }

        return null;
    }
}
