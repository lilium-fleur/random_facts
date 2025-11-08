<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function showLoginForm(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('auth.login');
    }

    public function showRegistrationForm(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('auth.register');
    }

    public function showChangePasswordForm(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('auth.change-password');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return redirect()->route('login')->withErrors('status', 'Registered successfully!');
    }

    public function login(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($data)) {
            return redirect()->intended('profile');
        }

        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    public function logout(Request $request): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();
        return redirect()->intended('login');
    }

    public function profile(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $user = Auth::user();
        return view('auth.profile', compact('user'));
    }

    public function updateProfile(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . Auth::id(),
            'password' => 'string|min:8|confirmed',
        ]);
        $user = Auth::user();
        $user->update($data);

        return redirect()->route('profile')->withErrors('status', 'Profile updated successfully!');
    }

    public function changePassword(Request $request): \Illuminate\Http\RedirectResponse
    {
        $data = $request->validate([
            'current_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors('current_password', 'Your current password is incorrect!');
        }
        $user->password = Hash::make($data['new_password']);
        $user->save();

        return redirect()->route('profile')->withErrors('status', 'Password updated successfully!');
    }
}
