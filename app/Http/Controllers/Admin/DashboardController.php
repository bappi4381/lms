<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'       => User::count(),
            'total_courses'     => Course::count(),
            'published_courses' => Course::where('is_published', true)->count(),
            'total_enrollments' => Enrollment::count(),
            'paid_enrollments'  => Enrollment::where('payment_status', 'paid')->count(),
            'total_revenue'     => Enrollment::where('payment_status', 'paid')->sum('amount_paid'),
            'total_categories'  => Category::count(),
        ];

        // Recent enrollments
        $recentEnrollments = Enrollment::with(['user', 'course'])
            ->latest()
            ->limit(6)
            ->get();

        // Recent users
        $recentUsers = User::latest()->limit(5)->get();

        // Top courses by enrollment count
        $topCourses = Course::withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->limit(5)
            ->get();

        // Monthly enrollments for last 6 months
        $monthlyEnrollments = Enrollment::select(
                DB::raw("DATE_FORMAT(created_at, '%b %Y') as month"),
                DB::raw("COUNT(*) as count"),
                DB::raw("SUM(amount_paid) as revenue")
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month', DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->orderBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentEnrollments',
            'recentUsers',
            'topCourses',
            'monthlyEnrollments'
        ));
    }
}
