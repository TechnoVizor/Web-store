<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        if (! filled(Config::get('services.google.client_id')) || ! filled(Config::get('services.google.client_secret'))) {
            abort(500, 'Google OAuth is not configured. Set GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET in .env.');
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // 1. Ищем пользователя по email (это наш главный идентификатор)
        $user = User::where('email', $googleUser->email)->first();

        if ($user) {
            // 2. Если юзер уже есть в базе, просто обновляем его данные (линкуем Google)
            $user->update([
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
            ]);
        } else {
            // 3. Если это абсолютно новый юзер, создаем профиль
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'password' => bcrypt(str()->random(16)),
                // Генерируем временный системный никнейм, так как поле у нас unique
                'nickname' => 'digi_'.str()->random(6),
            ]);
        }

        Auth::login($user);

        return redirect()->intended('/');
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/');
    }
}
