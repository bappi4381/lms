<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Course $course): RedirectResponse
    {
        abort_unless(auth()->user()->isEnrolledIn($course->id), 403, 'রিভিউ দেওয়ার জন্য কোর্সে এনরোল থাকতে হবে।');

        $data = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:2000'],
        ]);

        Review::updateOrCreate(
            ['user_id' => auth()->id(), 'course_id' => $course->id],
            ['rating' => $data['rating'], 'comment' => $data['comment'] ?? null, 'is_approved' => true]
        );

        return back()->with('status', 'আপনার রিভিউ জমা হয়েছে, ধন্যবাদ!');
    }
}
