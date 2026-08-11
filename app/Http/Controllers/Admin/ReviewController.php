<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Review::query()->with(['user', 'course']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('course', fn ($c) => $c->where('title_en', 'like', "%{$search}%"));
            });
        }

        if ($request->has('is_approved') && $request->input('is_approved') !== '') {
            $query->where('is_approved', (bool) $request->input('is_approved'));
        }

        $reviews = $query->latest()->paginate(20)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function edit(Review $review): View
    {
        return view('admin.reviews.edit', ['review' => $review->load(['user', 'course'])]);
    }

    public function update(Request $request, Review $review): RedirectResponse
    {
        $data = $request->validate([
            'rating'      => 'required|integer|min:1|max:5',
            'comment'     => 'nullable|string',
            'is_approved' => 'boolean',
        ]);

        $data['is_approved'] = $request->boolean('is_approved');

        $review->update($data);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review updated successfully!');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully!');
    }
}
