<?php

use App\Http\Controllers\Auth\PhoneOtpController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SubscriptionPlanController;
use App\Http\Controllers\SupportTicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Volt::route('/', 'frontend.course-index')->name('courses.index');
Volt::route('/courses', 'frontend.courses')->name('courses.list');
Volt::route('/courses/{slug}', 'frontend.course-show')->name('courses.show');
Volt::route('/courses/{slug}/lessons/{lesson_id}', 'frontend.lesson-player')
    ->middleware('device.limit')
    ->name('courses.lesson');

// ── Language switcher — persists the choice in session (via SetLocale
//    middleware) and returns the visitor to whichever page they were on ──
Route::get('/locale/{locale}', function (Request $request, string $locale) {
    return redirect()->to(url()->previous(route('courses.index')));
})->where('locale', 'en|bn')->name('locale.switch');

// ── Locale-aware category browsing (additive — does not replace the
//    existing /courses?category={id} filtering used elsewhere) ──
Volt::route('/{locale}/{mainType}/{category}/{subcategory?}', 'frontend.courses')
    ->where([
        'locale' => 'en|bn',
        'mainType' => 'academic|skills|test_prep|professional',
    ])
    ->name('categories.browse');

Route::get('/dashboard', function () {
    $user = auth()->user();
    $enrollments = $user->enrollments()
        ->with(['course.modules.lessons'])
        ->latest()
        ->get();
    $certificatesCount = $user->certificates()->count();
    $activeSubscription = $user->activeSubscription();
    $paidEnrollmentsCount = $enrollments->where('payment_status', 'paid')->count();
    return view('dashboard', compact('enrollments', 'certificatesCount', 'activeSubscription', 'paidEnrollmentsCount'));
})->middleware(['auth', 'verified', 'device.limit'])->name('dashboard');

// ── OAuth (Google / Facebook) — must work for guests ────────────────────
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'callback'])->name('social.callback');

// ── Phone OTP — works for both guests (login/registration) and
//    authenticated users (verifying their phone from the profile page) ──
Route::post('/otp/send', [PhoneOtpController::class, 'send'])->name('otp.send');
Route::post('/otp/verify', [PhoneOtpController::class, 'verify'])->name('otp.verify');

// ── Public certificate verification (no auth required) ─────────────────
Route::get('/certificates/verify/{certificateNumber}', [CertificateController::class, 'verify'])->name('certificates.verify');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/certificates', [CertificateController::class, 'index'])->name('profile.certificates');
    Route::get('/certificates/{certificate}/download', [CertificateController::class, 'download'])->name('certificates.download');

    Route::get('/payment-history', [PaymentController::class, 'history'])->name('profile.payment-history');
    Route::post('/orders/{order}/refund-request', [PaymentController::class, 'refundRequest'])->name('orders.refund-request');

    Route::get('/my-devices', [DeviceController::class, 'index'])->name('devices.index');
    Route::delete('/my-devices/{device}', [DeviceController::class, 'destroy'])->name('devices.destroy');
    Route::get('/device-limit-reached', [DeviceController::class, 'limitReached'])->name('devices.limit-reached');

    Route::get('/subscriptions', [SubscriptionPlanController::class, 'index'])->name('subscriptions.index');

    Route::get('/support', [SupportTicketController::class, 'index'])->name('support.index');
    Route::post('/support', [SupportTicketController::class, 'store'])->name('support.store');
    Route::get('/support/{ticket}', [SupportTicketController::class, 'show'])->name('support.show');
    Route::post('/support/{ticket}/reply', [SupportTicketController::class, 'reply'])->name('support.reply');

    Route::post('/courses/{course}/reviews', [ReviewController::class, 'store'])->name('reviews.store');
});

// Payment Routes
Route::middleware(['auth', 'device.limit'])->group(function () {
    Route::post('/payment/checkout/{course}', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/checkout-subscription/{plan}', [PaymentController::class, 'checkoutSubscription'])->name('payment.checkout-subscription');
});

// SSLCommerz Callbacks (Exempt from CSRF)
Route::post('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::post('/payment/failure', [PaymentController::class, 'failure'])->name('payment.failure');
Route::post('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
Route::post('/payment/ipn', [PaymentController::class, 'ipn'])->name('payment.ipn');

// ── Admin Routes (Converted from Filament to Standard Controllers & Blade Views) ──
// Filament has been fully removed from this project, so the Blade admin now owns
// the /admin path outright — no more collision risk.
// Access mirrors the original Filament panel gates exactly:
//   - Panel access: hasRole(['admin', 'instructor', 'support'])
//   - Roles/Permissions/Users resources: hasRole('admin') only
Route::middleware(['auth', 'role:admin|instructor|support'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

    // Course resource
    Route::resource('courses', \App\Http\Controllers\Admin\CourseController::class);

    // Course → Module sub-resource
    Route::get('courses/{course}/modules', [\App\Http\Controllers\Admin\CourseController::class, 'modules'])
        ->name('courses.modules');
    Route::post('courses/{course}/modules', [\App\Http\Controllers\Admin\CourseController::class, 'storeModule'])
        ->name('courses.modules.store');
    Route::put('courses/{course}/modules/{module}', [\App\Http\Controllers\Admin\CourseController::class, 'updateModule'])
        ->name('courses.modules.update');
    Route::delete('courses/{course}/modules/{module}', [\App\Http\Controllers\Admin\CourseController::class, 'destroyModule'])
        ->name('courses.modules.destroy');
    // Modules resource
    Route::resource('modules', \App\Http\Controllers\Admin\ModuleController::class);

    // Lessons resource
    Route::resource('lessons', \App\Http\Controllers\Admin\LessonController::class);

    // Assignments resource
    Route::resource('assignments', \App\Http\Controllers\Admin\AssignmentController::class);

    // Assignment → Submissions (grading), mirrors Filament's SubmissionsRelationManager
    Route::get('assignments/{assignment}/submissions', [\App\Http\Controllers\Admin\AssignmentSubmissionController::class, 'index'])
        ->name('assignments.submissions.index');
    Route::get('assignments/{assignment}/submissions/{submission}/edit', [\App\Http\Controllers\Admin\AssignmentSubmissionController::class, 'edit'])
        ->name('assignments.submissions.edit');
    Route::put('assignments/{assignment}/submissions/{submission}', [\App\Http\Controllers\Admin\AssignmentSubmissionController::class, 'update'])
        ->name('assignments.submissions.update');

    // Quizzes resource
    Route::resource('quizzes', \App\Http\Controllers\Admin\QuizController::class);

    // Enrollment resource
    Route::resource('enrollments', \App\Http\Controllers\Admin\EnrollmentController::class);

    // User resource — admin only, mirrors UserResource::canAccess()
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)
        ->middleware('role:admin');

    // Role & Permission resources — admin only, mirror RoleResource/PermissionResource::canAccess()
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class)
        ->middleware('role:admin');
    Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class)
        ->middleware('role:admin');

    // Device resource — no create page (devices are created by the app itself)
    Route::resource('devices', \App\Http\Controllers\Admin\DeviceController::class)
        ->only(['index', 'edit', 'update', 'destroy']);

    // Coupon resource
    Route::resource('coupons', \App\Http\Controllers\Admin\CouponController::class);

    // Review resource
    Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class)
        ->only(['index', 'edit', 'update', 'destroy']);

    // Subscription Plan → Subscription → Order trio
    Route::resource('subscription-plans', \App\Http\Controllers\Admin\SubscriptionPlanController::class);
    Route::resource('subscriptions', \App\Http\Controllers\Admin\SubscriptionController::class)
        ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class)
        ->only(['index', 'edit', 'update']);
    Route::post('orders/{order}/mark-refunded', [\App\Http\Controllers\Admin\OrderController::class, 'markRefunded'])
        ->name('orders.mark-refunded');

    // Support Ticket resource — no create page (tickets are created by end users);
    // "edit" doubles as the ticket detail page (status/priority form + reply thread,
    // mirroring Filament's EditSupportTicket page + RepliesRelationManager).
    Route::resource('support-tickets', \App\Http\Controllers\Admin\SupportTicketController::class)
        ->only(['index', 'edit', 'update', 'destroy']);
    Route::post('support-tickets/{support_ticket}/reply', [\App\Http\Controllers\Admin\SupportTicketController::class, 'reply'])
        ->name('support-tickets.reply');

    // Site Settings — converted from the 6 standalone Filament "Manage *Section"
    // pages, all editing subsets of the same singleton SiteSetting row.
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('hero', [\App\Http\Controllers\Admin\SiteSettingController::class, 'heroEdit'])->name('hero.edit');
        Route::put('hero', [\App\Http\Controllers\Admin\SiteSettingController::class, 'heroUpdate'])->name('hero.update');

        Route::get('about', [\App\Http\Controllers\Admin\SiteSettingController::class, 'aboutEdit'])->name('about.edit');
        Route::put('about', [\App\Http\Controllers\Admin\SiteSettingController::class, 'aboutUpdate'])->name('about.update');

        Route::get('why-us', [\App\Http\Controllers\Admin\SiteSettingController::class, 'whyUsEdit'])->name('why-us.edit');
        Route::put('why-us', [\App\Http\Controllers\Admin\SiteSettingController::class, 'whyUsUpdate'])->name('why-us.update');

        Route::get('pricing', [\App\Http\Controllers\Admin\SiteSettingController::class, 'pricingEdit'])->name('pricing.edit');
        Route::put('pricing', [\App\Http\Controllers\Admin\SiteSettingController::class, 'pricingUpdate'])->name('pricing.update');

        Route::get('testimonials', [\App\Http\Controllers\Admin\SiteSettingController::class, 'testimonialsEdit'])->name('testimonials.edit');
        Route::put('testimonials', [\App\Http\Controllers\Admin\SiteSettingController::class, 'testimonialsUpdate'])->name('testimonials.update');

        Route::get('header-footer', [\App\Http\Controllers\Admin\SiteSettingController::class, 'headerFooterEdit'])->name('header-footer.edit');
        Route::put('header-footer', [\App\Http\Controllers\Admin\SiteSettingController::class, 'headerFooterUpdate'])->name('header-footer.update');
    });
});

require __DIR__ . '/auth.php';

