<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    private const MAX_DEPTH = 3;

    public function index(Request $request): View
    {
        $query = Category::query()
            ->with(['parent'])
            ->withCount('courses');

        // Search Filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name_en', 'like', "%{$search}%")
                  ->orWhere('name_bn', 'like', "%{$search}%")
                  ->orWhere('slug_en', 'like', "%{$search}%")
                  ->orWhere('slug_bn', 'like', "%{$search}%");
            });
        }

        // Main Type Filter
        if ($mainType = $request->input('main_type')) {
            $query->where(function ($q) use ($mainType) {
                $q->where('main_type', $mainType)
                  ->orWhereHas('parent', function ($pq) use ($mainType) {
                      $pq->where('main_type', $mainType);
                  });
            });
        }

        // Active Status Filter
        if ($request->has('is_active') && $request->input('is_active') !== '') {
            $query->where('is_active', (bool) $request->input('is_active'));
        }

        $categories = $query->orderBy('order')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $mainTypeOptions = [
            'academic' => 'Academic (একাডেমিক)',
            'skills' => 'Skills (স্কিলস)',
            'test_prep' => 'Test Preparation (টেস্ট প্রস্তুতি)',
            'professional' => 'Professional (প্রফেশনাল)',
        ];

        return view('admin.categories.index', compact('categories', 'mainTypeOptions'));
    }

    public function create(): View
    {
        $parentOptions = $this->getParentOptions();
        $mainTypes = [
            'academic' => 'Academic (একাডেমিক)',
            'skills' => 'Skills (স্কিলস)',
            'test_prep' => 'Test Preparation (টেস্ট প্রস্তুতি)',
            'professional' => 'Professional (প্রফেশনাল)',
        ];

        return view('admin.categories.create', compact('parentOptions', 'mainTypes'));
    }

    public function store(CategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $data['slug_en'] = Str::slug($data['name_en']);
        $data['slug_bn'] = Str::slug($data['name_bn']);

        if (! empty($data['parent_id'])) {
            $data['main_type'] = null;
        }

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully!');
    }

    public function edit(Category $category): View
    {
        $parentOptions = $this->getParentOptions($category);
        $mainTypes = [
            'academic' => 'Academic (একাডেমিক)',
            'skills' => 'Skills (স্কিলস)',
            'test_prep' => 'Test Preparation (টেস্ট প্রস্তুতি)',
            'professional' => 'Professional (প্রফেশনাল)',
        ];

        return view('admin.categories.edit', compact('category', 'parentOptions', 'mainTypes'));
    }

    public function update(CategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();

        $data['slug_en'] = Str::slug($data['name_en']);
        $data['slug_bn'] = Str::slug($data['name_bn']);

        if (! empty($data['parent_id'])) {
            $data['main_type'] = null;
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category updated successfully!');
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->hasChildren()) {
            return redirect()->back()
                ->with('error', 'Cannot delete a category that has sub-categories! Re-assign them first.');
        }

        if ($category->courses()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete a category linked with published courses!');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully!');
    }

    private function getParentOptions(?Category $record = null): array
    {
        return Category::query()
            ->with('parent')
            ->orderBy('order')
            ->get()
            ->filter(fn (Category $cat) => $cat->depth() < self::MAX_DEPTH)
            ->when($record, fn ($categories) => $categories->reject(
                fn (Category $cat) => $cat->id === $record->id || $cat->isDescendantOf($record)
            ))
            ->sortBy([['parent_id', 'asc'], ['order', 'asc']])
            ->mapWithKeys(fn (Category $cat) => [
                $cat->id => $cat->parent
                    ? "{$cat->parent->name_en} → {$cat->name_en} / {$cat->parent->name_bn} → {$cat->name_bn}"
                    : "{$cat->name_en} / {$cat->name_bn}",
            ])
            ->all();
    }
}
