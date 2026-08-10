<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    private const PAYMENT_STATUSES = [
        'pending'  => 'Pending',
        'paid'     => 'Paid',
        'failed'   => 'Failed',
        'refunded' => 'Refunded',
    ];

    public function index(Request $request): View
    {
        $query = Enrollment::query()
            ->with(['user', 'course']);

        // Search by user name or email
        if ($search = $request->input('search')) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhereHas('course', function ($q) use ($search) {
                $q->where('title_en', 'like', "%{$search}%");
            })->orWhere('transaction_id', 'like', "%{$search}%");
        }

        // Payment status filter
        if ($status = $request->input('payment_status')) {
            $query->where('payment_status', $status);
        }

        // Course filter
        if ($courseId = $request->input('course_id')) {
            $query->where('course_id', $courseId);
        }

        $enrollments = $query->orderBy('enrolled_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $courses = Course::orderBy('title_en')->pluck('title_en', 'id');

        return view('admin.enrollments.index', [
            'enrollments'     => $enrollments,
            'courses'         => $courses,
            'paymentStatuses' => self::PAYMENT_STATUSES,
        ]);
    }

    public function create(): View
    {
        $users   = User::orderBy('name')->pluck('name', 'id');
        $courses = Course::orderBy('title_en')->pluck('title_en', 'id');

        return view('admin.enrollments.create', [
            'users'           => $users,
            'courses'         => $courses,
            'paymentStatuses' => self::PAYMENT_STATUSES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id'        => 'required|exists:users,id',
            'course_id'      => 'required|exists:courses,id',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'amount_paid'    => 'nullable|numeric|min:0',
            'transaction_id' => 'nullable|string|max:255',
            'enrolled_at'    => 'nullable|date',
            'expires_at'     => 'nullable|date|after_or_equal:enrolled_at',
        ]);

        $data['enrolled_at'] = $data['enrolled_at'] ?? now();
        $data['amount_paid'] = $data['amount_paid'] ?? 0;

        // Prevent duplicate enrollment
        $exists = Enrollment::where('user_id', $data['user_id'])
            ->where('course_id', $data['course_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'This user is already enrolled in this course!');
        }

        Enrollment::create($data);

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Enrollment created successfully!');
    }

    public function edit(Enrollment $enrollment): View
    {
        $users   = User::orderBy('name')->pluck('name', 'id');
        $courses = Course::orderBy('title_en')->pluck('title_en', 'id');

        return view('admin.enrollments.edit', [
            'enrollment'      => $enrollment->load(['user', 'course']),
            'users'           => $users,
            'courses'         => $courses,
            'paymentStatuses' => self::PAYMENT_STATUSES,
        ]);
    }

    public function update(Request $request, Enrollment $enrollment): RedirectResponse
    {
        $data = $request->validate([
            'user_id'        => 'required|exists:users,id',
            'course_id'      => 'required|exists:courses,id',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'amount_paid'    => 'nullable|numeric|min:0',
            'transaction_id' => 'nullable|string|max:255',
            'enrolled_at'    => 'nullable|date',
            'expires_at'     => 'nullable|date',
        ]);

        $enrollment->update($data);

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Enrollment updated successfully!');
    }

    public function destroy(Enrollment $enrollment): RedirectResponse
    {
        $enrollment->delete();

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Enrollment deleted successfully!');
    }
}
