<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterControllersementara extends Controller 
{
    public function showForm()
    {
        return view('registersementara');
    }

    public function register(Request $request)
{
    $validated = $request->validate([
        'username' => 'required|string|max:50|unique:users,username',
        'gmail' => 'required|email|max:100|unique:users,gmail',
        'password' => 'required|string|min:6|confirmed',
        'role' => 'required|in:admin,teknisi',
    ], [
        'username.required' => 'Username wajib diisi.',
        'gmail.required' => 'Gmail wajib diisi.',
        'gmail.email' => 'Format Gmail tidak valid.',
        'password.required' => 'Password wajib diisi.',
        'password.confirmed' => 'Konfirmasi password tidak cocok.',
        'role.required' => 'Silakan pilih role pengguna.',
    ]);

    \App\Models\User::create([
        'username' => $validated['username'],
        'gmail' => $validated['gmail'],
        'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
        'role' => $validated['role'],
    ]);

    return redirect()->route('register.form')->with('success', 'Registrasi berhasil!');
}

}
