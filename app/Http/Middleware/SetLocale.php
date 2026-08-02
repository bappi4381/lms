<?php

namespace App\Http\Middleware;

use App\Models\Category;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resolves the active locale for the request and applies it via App::setLocale().
 *
 * Priority: an explicit `{locale}` route segment (used by the locale-aware
 * category browse routes) > the session value set by the language switcher
 * > the app's configured default locale. The chosen locale is persisted to
 * the session so the rest of the site (which has no `{locale}` URL prefix)
 * keeps using it on subsequent requests.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale');

        if (is_string($locale) && in_array($locale, Category::LOCALES, true)) {
            session(['locale' => $locale]);
        } else {
            $locale = session('locale');
        }

        if (! is_string($locale) || ! in_array($locale, Category::LOCALES, true)) {
            $locale = config('app.locale');
        }

        App::setLocale($locale);

        return $next($request);
    }
}
