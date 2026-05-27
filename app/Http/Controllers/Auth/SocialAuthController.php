<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect(string $provider): RedirectResponse
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        $socialUser = Socialite::driver($provider)->user();

        if (! $socialUser->getEmail()) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'No email address was returned by '.$provider.'.']);
        }

        $providerColumn = $provider.'_id';

        $user = User::query()
            ->where($providerColumn, $socialUser->getId())
            ->orWhere('email', $socialUser->getEmail())
            ->first();

        if ($user) {
            $user->forceFill([
                $providerColumn => $socialUser->getId(),
                'avatar_url' => $socialUser->getAvatar() ?: $user->avatar_url,
                'email_verified_at' => $user->email_verified_at ?? now(),
            ])->save();
        } else {
            $user = User::create([
                'name' => $socialUser->getName() ?: $socialUser->getNickname() ?: 'New User',
                'email' => $socialUser->getEmail(),
                $providerColumn => $socialUser->getId(),
                'avatar_url' => $socialUser->getAvatar(),
                'email_verified_at' => now(),
            ]);
        }

        Auth::login($user, remember: true);

        return redirect()->intended(route('portal', absolute: false));
    }
}
