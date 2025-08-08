<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function index()
    {
        return view('register.index', [
            'title' => 'Register'
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|regex:/^0[8-9][0-9]{7,12}$/',
            'password' => [
                'required',
                'confirmed',
                'min:8',
                'regex:/[A-Z]/',       // setidaknya 1 huruf kapital
                'regex:/[a-z]/',       // setidaknya 1 huruf kecil
                'regex:/[0-9]/',       // setidaknya 1 angka
                'regex:/[@$!%*?&]/'    // setidaknya 1 simbol
            ],
            'role' => 'required|in:user,corporate,admin',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['remember_token'] = Str::random(12);
        $validated['email_verified_at'] = now(); // verifikasi otomatis

        $user = User::create($validated);
        Auth::login($user);
        event(new Registered($user));

        return redirect()->route('verification.notice')->with('success', 'Registration successful! Please Verification.');
    }
}
