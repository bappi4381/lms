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
    $enrollments = auth()->user()->enrollments()
        ->with(['course.modules.lessons'])
        ->latest()
        ->get();
    return view('dashboard', compact('enrollments'));
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

require __DIR__ . '/auth.php';
