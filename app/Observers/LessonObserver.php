<?php

namespace App\Observers;

use App\Models\Lesson;
use App\Notifications\NewLessonAddedNotification;

class LessonObserver
{
    /**
     * Notify all paid-enrolled students when a new lesson is added to
     * a course they're taking.
     */
    public function created(Lesson $lesson): void
    {
        $course = $lesson->module?->course;

        if (! $course) {
            return;
        }

        $students = $course->enrollments()
            ->where('payment_status', 'paid')
            ->with('user')
            ->get()
            ->pluck('user')
            ->filter();

        foreach ($students as $student) {
            $student->notify(new NewLessonAddedNotification($lesson));
        }
    }
}
