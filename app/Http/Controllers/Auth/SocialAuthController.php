<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ExternalIdentity;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect(string $provider): RedirectResponse
    {
        abort_unless($this->isSupportedProvider($provider), 404);

        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider): RedirectResponse
    {
        abort_unless($this->isSupportedProvider($provider), 404);

        $socialUser = Socialite::driver($provider)->user();

        if (! $socialUser->getEmail()) {
            return redirect()
                ->route('login')
                ->withErrors(['email' => 'No email address was returned by '.$provider.'.']);
        }

        $identity = ExternalIdentity::query()
            ->where('provider', $provider)
            ->where('provider_user_id', $socialUser->getId())
            ->first();

        $user = $identity?->user
            ?? User::query()->where('email', $socialUser->getEmail())->first();

        if ($user) {
            $user->forceFill([
                'avatar_url' => $socialUser->getAvatar() ?: $user->avatar_url,
                'email_verified_at' => $user->email_verified_at ?? now(),
            ])->save();
        } else {
            $user = User::create([
                'name' => $socialUser->getName() ?: $socialUser->getNickname() ?: 'New User',
                'email' => $socialUser->getEmail(),
                'avatar_url' => $socialUser->getAvatar(),
                'email_verified_at' => now(),
            ]);
        }

        $user->externalIdentities()->updateOrCreate(
            ['provider' => $provider],
            [
                'provider_user_id' => $socialUser->getId(),
                'nickname' => $socialUser->getNickname(),
                'avatar_url' => $socialUser->getAvatar(),
            ],
        );

        Auth::login($user, remember: true);

        return redirect()->intended(route('portal', absolute: false));
    }

    private function isSupportedProvider(string $provider): bool
    {
        return array_key_exists($provider, config('oauth.providers', []))
            && (bool) config("oauth.providers.{$provider}.enabled");
    }
}
