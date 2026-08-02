<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    /**
     * Locales supported for category names/slugs.
     */
    public const LOCALES = ['en', 'bn'];

    protected $fillable = [
        'parent_id',
        'main_type',
        'name_en',
        'name_bn',
        'slug_en',
        'slug_bn',
        'icon',
        'is_active',
        'order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Auto-generate locale-aware slugs from their matching name field.
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $category) {
            if (empty($category->slug_en) && ! empty($category->name_en)) {
                $category->slug_en = Str::slug($category->name_en);
            }

            if (empty($category->slug_bn) && ! empty($category->name_bn)) {
                $category->slug_bn = Str::slug($category->name_bn);
            }
        });
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('order');
    }

    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    public function isSubCategory(): bool
    {
        return $this->parent_id !== null;
    }

    /**
     * Whether this category currently has any child categories.
     * Used to prevent re-parenting a category that already has children
     * (would break the 2-level DB / 3-level conceptual depth cap).
     */
    public function hasChildren(): bool
    {
        if ($this->relationLoaded('children')) {
            return $this->children->isNotEmpty();
        }

        return $this->children()->exists();
    }

    /**
     * The main navbar type this category belongs to. Sub-categories (levels
     * 2 and 3) don't store their own main_type — they inherit it by walking
     * up to their top-level ancestor.
     */
    public function resolvedMainType(): ?string
    {
        if ($this->main_type) {
            return $this->main_type;
        }

        return $this->parent?->resolvedMainType();
    }

    /**
     * Nesting depth: 1 = top-level, 2 = sub-category, 3 = sub-sub-category.
     * The app caps categories at 3 levels deep.
     */
    public function depth(): int
    {
        $parent = $this->relationLoaded('parent') ? $this->parent : $this->parent()->first();

        return $parent ? $parent->depth() + 1 : 1;
    }

    /**
     * How many additional levels of descendants this category currently
     * has (0 = leaf, 1 = has children, 2 = has grandchildren). Used to make
     * sure re-parenting a category never pushes its existing descendants
     * past the 3-level cap.
     */
    public function subtreeHeight(): int
    {
        $children = $this->relationLoaded('children') ? $this->children : $this->children()->get();

        if ($children->isEmpty()) {
            return 0;
        }

        return 1 + $children->max(fn (self $child) => $child->subtreeHeight());
    }

    /**
     * Whether $ancestor is anywhere above this category in the parent
     * chain — used to block re-parenting a category under its own
     * descendant (which would create a cycle).
     */
    public function isDescendantOf(self $ancestor): bool
    {
        $current = $this;

        while ($current->parent_id) {
            if ($current->parent_id === $ancestor->id) {
                return true;
            }

            $current = $current->relationLoaded('parent') ? $current->parent : $current->parent()->first();

            if (! $current) {
                return false;
            }
        }

        return false;
    }

    /**
     * Locale-aware name, falling back to English then Bangla when the
     * requested locale's value hasn't been filled in yet.
     */
    public function nameFor(string $locale): ?string
    {
        $locale = in_array($locale, self::LOCALES, true) ? $locale : 'en';

        return $this->{"name_{$locale}"} ?: ($this->name_en ?: $this->name_bn);
    }

    /**
     * Locale-aware slug, with the same fallback behavior as nameFor().
     */
    public function slugFor(string $locale): ?string
    {
        $locale = in_array($locale, self::LOCALES, true) ? $locale : 'en';

        return $this->{"slug_{$locale}"} ?: ($this->slug_en ?: $this->slug_bn);
    }

    /**
     * Virtual `name` attribute resolved for the current app locale, so
     * existing `$category->name` usages keep working unchanged.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->nameFor(app()->getLocale()),
        );
    }

    /**
     * Virtual `slug` attribute resolved for the current app locale, so
     * existing `$category->slug` usages keep working unchanged.
     */
    protected function slug(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->slugFor(app()->getLocale()),
        );
    }
}
