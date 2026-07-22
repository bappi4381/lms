<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    protected const ALLOWED_PROVIDERS = ['google', 'facebook'];

    public function redirect(string $provider): RedirectResponse
    {
        $this->ensureProviderAllowed($provider);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        $this->ensureProviderAllowed($provider);

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            Log::error("Social login ({$provider}) failed: ".$e->getMessage());

            return redirect()->route('courses.index')
                ->with('error', 'সোশ্যাল লগইন ব্যর্থ হয়েছে। আবার চেষ্টা করুন।');
        }

        $socialAccount = SocialAccount::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if ($socialAccount) {
            $user = $socialAccount->user;
        } else {
            $user = User::where('email', $socialUser->getEmail())->first();

            if (! $user) {
                $user = User::create([
                    'name' => $socialUser->getName() ?: $socialUser->getNickname() ?: 'User',
                    'email' => $socialUser->getEmail(),
                    'password' => Hash::make(Str::random(32)),
                    'email_verified_at' => now(),
                    'profile_photo_url' => $socialUser->getAvatar(),
                ]);
                $user->assignRole('student');
            }

            SocialAccount::create([
                'user_id' => $user->id,
                'provider' => $provider,
                'provider_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
            ]);
        }

        Auth::login($user, true);

        return redirect()->intended(route('dashboard'))
            ->with('status', 'সফলভাবে লগইন হয়েছে।');
    }

    protected function ensureProviderAllowed(string $provider): void
    {
        abort_unless(in_array($provider, self::ALLOWED_PROVIDERS, true), 404);
    }
}
