<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\CustomerOrders;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class GoogleController extends Controller
{
    public function redirect()
    {
        if (! $this->googleOAuthIsConfigured()) {
            Log::warning('Google OAuth redirect attempted without configured credentials.');

            return $this->redirectToLoginWithOAuthError();
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        if (! $this->googleOAuthIsConfigured()) {
            Log::warning('Google OAuth callback attempted without configured credentials.');

            return $this->redirectToLoginWithOAuthError();
        }

        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::where('email', $googleUser->email)->first();

            if ($user) {
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                ]);
            } else {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => bcrypt(str()->random(32)),
                    'nickname' => $this->uniqueNickname(),
                ]);
            }

            Auth::login($user);
            CustomerOrders::attachGuestOrders($user);
        } catch (Throwable $exception) {
            Log::warning('Google OAuth login failed.', [
                'exception' => $exception::class,
                'message' => $exception->getMessage(),
            ]);

            return $this->redirectToLoginWithOAuthError();
        }

        return redirect()->intended('/');
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }

    private function googleOAuthIsConfigured(): bool
    {
        return filled(Config::get('services.google.client_id'))
            && filled(Config::get('services.google.client_secret'))
            && filled(Config::get('services.google.redirect'));
    }

    private function redirectToLoginWithOAuthError()
    {
        return redirect()
            ->route('login')
            ->with('auth_error', 'GOOGLE_AUTH_UNAVAILABLE');
    }

    private function uniqueNickname(): string
    {
        do {
            $nickname = 'digi_'.str()->random(10);
        } while (User::where('nickname', $nickname)->exists());

        return $nickname;
    }
}
