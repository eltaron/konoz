<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class RegisterController extends Controller
{
    public static function middleware(): array
    {
        return [
            new Middleware('guest', except: ['logout']),
        ];
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        $student = Student::where('email', $request->email)->orWhere('phone', $request->phone ?? '')->first();
        if ($student) {
            $student->update(['user_id' => $user->id, 'email' => $request->email, 'name_ar' => $request->name]);
        } else {
            Student::create([
                'user_id' => $user->id,
                'name_ar' => $request->name,
                'email' => $request->email,
                'status' => 'active',
                'joined_at' => now(),
            ]);
        }

        Auth::login($user);

        return redirect('/student/dashboard');
    }
}
