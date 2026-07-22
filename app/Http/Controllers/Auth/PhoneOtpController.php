<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhoneOtpController extends Controller
{
    public function __construct(protected OtpService $otpService)
    {
    }

    /**
     * Send an OTP to the given phone number.
     * Works both for guests (phone login/registration) and authenticated
     * users (verifying a phone number from their profile).
     */
    public function send(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => ['required', 'string', 'regex:/^\+?[0-9]{10,15}$/'],
        ]);

        $this->otpService->generateAndSend($request->phone, auth()->id());

        return back()->with('otp_sent_to', $request->phone)
            ->with('status', 'আপনার ফোন নাম্বারে OTP কোড পাঠানো হয়েছে।');
    }

    /**
     * Verify OTP. If the user is a guest, this logs them in (creating an
     * account if the phone number is new). If authenticated, this marks
     * their phone number as verified.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'phone' => ['required', 'string'],
            'code' => ['required', 'string'],
        ]);

        if (! $this->otpService->verify($request->phone, $request->code)) {
            return back()->withInput()
                ->with('otp_sent_to', $request->phone)
                ->with('error', 'OTP কোডটি সঠিক নয় বা মেয়াদ শেষ হয়ে গেছে।');
        }

        if (auth()->check()) {
            auth()->user()->update([
                'phone' => $request->phone,
                'phone_verified_at' => now(),
            ]);

            return back()->with('status', 'আপনার ফোন নাম্বার সফলভাবে ভেরিফাই হয়েছে।');
        }

        $user = User::where('phone', $request->phone)->first();

        if (! $user) {
            $user = User::create([
                'name' => 'User '.substr($request->phone, -4),
                'phone' => $request->phone,
                'phone_verified_at' => now(),
                'password' => bcrypt(str()->random(32)),
            ]);
            $user->assignRole('student');
        } elseif (! $user->phone_verified_at) {
            $user->update(['phone_verified_at' => now()]);
        }

        Auth::login($user, true);

        return redirect()->intended(route('dashboard'))
            ->with('status', 'সফলভাবে লগইন হয়েছে।');
    }
}
