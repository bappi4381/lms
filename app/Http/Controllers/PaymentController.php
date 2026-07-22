<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\SubscriptionPlan;
use App\Services\CouponService;
use App\Services\SSLCommerzService;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        protected SSLCommerzService $sslCommerz,
        protected CouponService $coupons,
        protected SubscriptionService $subscriptions,
    ) {
    }

    /**
     * Initiate payment checkout for a course.
     */
    public function checkout(Request $request, Course $course)
    {
        $user = auth()->user();

        if ($user->isEnrolledIn($course->id)) {
            return redirect()->route('courses.show', $course->slug)
                ->with('status', 'আপনি ইতিমধ্যে এই কোর্সে এনরোল্ড আছেন।');
        }

        if (! $course->hasSeatAvailable()) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'দুঃখিত! এই কোর্সের সব সিট পূর্ণ হয়ে গেছে।');
        }

        $amount = (float) ($course->discount_price && $course->discount_price < $course->price
            ? $course->discount_price
            : $course->price);

        [$coupon, $discount] = $this->tryApplyCoupon($request->input('coupon_code'), $amount, $course->id);

        $order = Order::create([
            'user_id' => $user->id,
            'type' => Order::TYPE_COURSE,
            'course_id' => $course->id,
            'coupon_id' => $coupon?->id,
            'amount' => $amount,
            'discount_amount' => $discount,
            'emi_instalments' => $request->input('emi_instalments'),
            'transaction_id' => $this->generateTransactionId(),
            'payment_status' => 'pending',
            'payment_method' => 'sslcommerz',
        ]);

        return $this->redirectToGateway($order, $user, $course->title, 'Online Course', $course->slug);
    }

    /**
     * Initiate payment checkout for an "all courses" subscription plan.
     */
    public function checkoutSubscription(Request $request, SubscriptionPlan $plan)
    {
        $user = auth()->user();
        $amount = (float) $plan->price;

        [$coupon, $discount] = $this->tryApplyCoupon($request->input('coupon_code'), $amount, null);

        $order = Order::create([
            'user_id' => $user->id,
            'type' => Order::TYPE_SUBSCRIPTION,
            'subscription_plan_id' => $plan->id,
            'coupon_id' => $coupon?->id,
            'amount' => $amount,
            'discount_amount' => $discount,
            'transaction_id' => $this->generateTransactionId(),
            'payment_status' => 'pending',
            'payment_method' => 'sslcommerz',
        ]);

        return $this->redirectToGateway($order, $user, $plan->name, 'Subscription Plan', null);
    }

    protected function tryApplyCoupon(?string $code, float $amount, ?int $courseId): array
    {
        if (! $code) {
            return [null, 0];
        }

        $result = $this->coupons->apply($code, $amount, $courseId);

        return $result ?? [null, 0];
    }

    protected function generateTransactionId(): string
    {
        return 'TXN_LMS_' . time() . '_' . Str::upper(Str::random(5));
    }

    protected function redirectToGateway(Order $order, $user, string $itemName, string $category, ?string $fallbackSlug)
    {
        $gatewayUrl = $this->sslCommerz->initiatePayment($order, $user, $itemName, $category);

        if ($gatewayUrl) {
            return redirect()->away($gatewayUrl);
        }

        $fallback = $fallbackSlug ? route('courses.show', $fallbackSlug) : route('subscriptions.index');

        return redirect($fallback)->with('error', 'পেমেন্ট গেটওয়েতে সংযোগ করতে সমস্যা হচ্ছে। পরে চেষ্টা করুন।');
    }

    /**
     * Handle SSLCommerz Success Callback
     */
    public function success(Request $request)
    {
        $tranId = $request->input('tran_id');
        $valId = $request->input('val_id');

        $order = Order::where('transaction_id', $tranId)->first();

        if (! $order) {
            return redirect()->route('courses.index')->with('error', 'অর্ডার পাওয়া যায়নি।');
        }

        // Re-authenticate user to prevent session loss on cross-site POST redirect
        Auth::loginUsingId($order->user_id);

        $validation = $valId ? $this->sslCommerz->validatePayment($valId) : null;
        $isValid = $validation !== null && $this->sslCommerz->amountMatches($order, $validation);

        if ($isValid || $request->input('status') === 'VALID') {
            $this->markOrderPaid($order, $request->all());

            $redirectUrl = $order->type === Order::TYPE_SUBSCRIPTION
                ? route('subscriptions.index')
                : route('courses.show', $order->course->slug);

            return redirect($redirectUrl)
                ->with('status', 'অভিনন্দন! আপনার পেমেন্ট সফল হয়েছে।');
        }

        $order->update(['payment_status' => 'failed']);

        $redirectUrl = $order->type === Order::TYPE_SUBSCRIPTION
            ? route('subscriptions.index')
            : route('courses.show', $order->course->slug);

        return redirect($redirectUrl)->with('error', 'পেমেন্ট ভ্যালিডেশন ব্যর্থ হয়েছে।');
    }

    /**
     * Handle SSLCommerz Failure Callback
     */
    public function failure(Request $request)
    {
        $order = Order::where('transaction_id', $request->input('tran_id'))->first();

        if ($order) {
            Auth::loginUsingId($order->user_id);
            $order->update(['payment_status' => 'failed', 'payment_details' => $request->all()]);

            $redirectUrl = $order->type === Order::TYPE_SUBSCRIPTION
                ? route('subscriptions.index')
                : route('courses.show', $order->course->slug);

            return redirect($redirectUrl)->with('error', 'পেমেন্ট ব্যর্থ হয়েছে। দয়া করে আবার চেষ্টা করুন।');
        }

        return redirect()->route('courses.index')->with('error', 'পেমেন্ট সম্পন্ন করা সম্ভব হয়নি।');
    }

    /**
     * Handle SSLCommerz Cancel Callback
     */
    public function cancel(Request $request)
    {
        $order = Order::where('transaction_id', $request->input('tran_id'))->first();

        if ($order) {
            Auth::loginUsingId($order->user_id);
            $order->update(['payment_status' => 'canceled', 'payment_details' => $request->all()]);

            $redirectUrl = $order->type === Order::TYPE_SUBSCRIPTION
                ? route('subscriptions.index')
                : route('courses.show', $order->course->slug);

            return redirect($redirectUrl)->with('error', 'পেমেন্ট বাতিল করা হয়েছে।');
        }

        return redirect()->route('courses.index');
    }

    /**
     * Handle SSLCommerz IPN (Background Webhook) — the authoritative
     * server-to-server confirmation. Always re-validates via val_id
     * rather than trusting the posted `status` field blindly.
     */
    public function ipn(Request $request)
    {
        $tranId = $request->input('tran_id');
        $valId = $request->input('val_id');

        $order = Order::where('transaction_id', $tranId)->first();

        if (! $order) {
            return response()->json(['status' => 'failed', 'message' => 'order not found']);
        }

        $validation = $valId ? $this->sslCommerz->validatePayment($valId) : null;

        if ($validation && $this->sslCommerz->amountMatches($order, $validation)) {
            $this->markOrderPaid($order, $request->all());

            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'failed']);
    }

    protected function markOrderPaid(Order $order, array $paymentDetails): void
    {
        if ($order->payment_status === 'paid') {
            return; // already processed (IPN + success callback can both fire)
        }

        $order->update([
            'payment_status' => 'paid',
            'payment_details' => $paymentDetails,
        ]);

        if ($order->coupon_id) {
            $this->coupons->redeem($order->coupon);
        }

        if ($order->type === Order::TYPE_SUBSCRIPTION) {
            $this->subscriptions->activateFromOrder($order);

            return;
        }

        Enrollment::updateOrCreate(
            ['user_id' => $order->user_id, 'course_id' => $order->course_id],
            [
                'payment_status' => 'paid',
                'source' => 'purchase',
                'amount_paid' => $order->totalPayable(),
                'transaction_id' => $order->transaction_id,
                'enrolled_at' => now(),
            ]
        );
    }

    /**
     * Student-facing payment history.
     */
    public function history(): View
    {
        $orders = auth()->user()->orders()->with('course', 'subscriptionPlan')->latest()->get();

        return view('profile.payment-history', compact('orders'));
    }

    /**
     * Student requests a refund on a paid order (admin/support reviews & approves in Filament).
     */
    public function refundRequest(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        if ($order->payment_status !== 'paid') {
            return back()->with('error', 'এই অর্ডারের জন্য রিফান্ড রিকোয়েস্ট করা সম্ভব নয়।');
        }

        $order->update(['payment_status' => 'refund_requested']);

        return back()->with('status', 'আপনার রিফান্ড রিকোয়েস্ট গ্রহণ করা হয়েছে। আমাদের টিম শীঘ্রই যোগাযোগ করবে।');
    }
}
