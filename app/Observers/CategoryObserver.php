<?php

namespace App\Observers;

use App\Models\Category;
use App\Services\CategoryNavService;

/**
 * Keeps the cached navbar category tree in sync whenever a category is
 * created, updated, or deleted from the admin panel (or anywhere else).
 */
class CategoryObserver
{
    public function saved(Category $category): void
    {
        CategoryNavService::forget();
    }

    public function deleted(Category $category): void
    {
        CategoryNavService::forget();
    }
}
