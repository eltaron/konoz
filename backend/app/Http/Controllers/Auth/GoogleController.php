<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', __('messages.social_login_failed'));
        }

        $email = $googleUser->getEmail();
        if (!$email) {
            return redirect()->route('login')->with('error', __('messages.social_login_failed'));
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName() ?? $googleUser->getNickname() ?? $email,
                'email' => $email,
                'password' => Hash::make(Str::random(32)),
                'role' => 'student',
            ]);

            Student::firstOrCreate(
                ['email' => $email],
                [
                    'user_id' => $user->id,
                    'name_ar' => $user->name,
                    'email' => $email,
                    'status' => 'active',
                    'joined_at' => now(),
                ]
            );

            Mail::to($user->email)->send(new WelcomeMail($user->name));
        }

        Auth::login($user);
        $user->update(['last_login_at' => now()]);

        if ($user->role === 'teacher') {
            return redirect()->intended('/teacher/dashboard');
        }
        if ($user->role === 'student') {
            return redirect()->intended('/student/dashboard');
        }
        return redirect()->intended('/');
    }
}