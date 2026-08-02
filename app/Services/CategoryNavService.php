<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Builds the category tree used by the static navbar's dynamic dropdowns.
 * Cached and fallback-safe: a DB error or empty table never breaks the nav,
 * it just renders without a dropdown for the affected main_type.
 *
 * The tree is cached as plain nested arrays (never Eloquent models or
 * Collection objects) because this app's cache config disallows
 * unserializing objects from cache (`config('cache.serializable_classes')`
 * is `false`, a deliberate anti object-injection hardening) — so the cached
 * payload must be pure arrays. It's converted back into Collections after
 * being read, and the correct name/slug for the current request's locale
 * is picked at render time via pick().
 */
class CategoryNavService
{
    public const CACHE_KEY = 'nav.category-tree';

    public const CACHE_TTL_HOURS = 6;

    /**
     * Max nesting depth (top-level category + sub-category + sub-sub-category).
     */
    private const MAX_DEPTH = 3;

    /**
     * Active category tree grouped by main_type (academic/skills/test_prep/professional).
     * Each top-level category is eager-loaded with its active children and
     * grandchildren (levels 2 and 3) in two extra queries total — no N+1.
     *
     * @return Collection<string, Collection<int, array>>
     */
    public function tree(): Collection
    {
        try {
            $grouped = Cache::remember(self::CACHE_KEY, now()->addHours(self::CACHE_TTL_HOURS), function () {
                $activeOrdered = fn ($query) => $query->where('is_active', true)->orderBy('order');

                return Category::query()
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->whereNotNull('main_type')
                    ->with([
                        'children' => $activeOrdered,
                        'children.children' => $activeOrdered,
                    ])
                    ->orderBy('order')
                    ->get()
                    ->map(fn (Category $category) => $this->toArray($category))
                    ->groupBy('main_type')
                    ->map(fn (Collection $items) => $items->values()->all())
                    ->all();
            });

            return collect($grouped)->map(fn (array $items) => collect($items));
        } catch (Throwable $e) {
            Log::warning('CategoryNavService: falling back to empty nav tree — '.$e->getMessage());

            return collect();
        }
    }

    /**
     * Top-level categories (with children) for a single navbar main_type,
     * or an empty collection when none exist yet or the query failed.
     */
    public function forMainType(string $mainType): Collection
    {
        return $this->tree()->get($mainType) ?? collect();
    }

    /**
     * Clear the cached tree. Called by CategoryObserver whenever a category
     * is created, updated, or deleted.
     */
    public static function forget(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * Pick the locale-aware value of a bilingual field (e.g. 'name', 'slug')
     * out of a cached nav array, falling back to English then Bangla.
     */
    public static function pick(array $item, string $field, string $locale): ?string
    {
        $locale = in_array($locale, Category::LOCALES, true) ? $locale : 'en';

        return $item["{$field}_{$locale}"]
            ?: ($item["{$field}_en"] ?? null)
            ?: ($item["{$field}_bn"] ?? null);
    }

    /**
     * Recursively converts a category (and, up to MAX_DEPTH, its
     * eager-loaded children) into a plain array safe for caching.
     */
    private function toArray(Category $category, int $level = 1): array
    {
        $item = [
            'id' => $category->id,
            'name_en' => $category->name_en,
            'name_bn' => $category->name_bn,
            'slug_en' => $category->slug_en,
            'slug_bn' => $category->slug_bn,
            'icon' => $category->icon,
        ];

        if ($level === 1) {
            $item['main_type'] = $category->main_type;
        }

        $item['children'] = ($level < self::MAX_DEPTH && $category->relationLoaded('children'))
            ? $category->children->map(fn (Category $child) => $this->toArray($child, $level + 1))->values()->all()
            : [];

        return $item;
    }
}
