<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
     public function logout(Request $request)
    {
        Auth::logout();                      // Hapus session login
        $request->session()->invalidate();   // Hapus semua session
        $request->session()->regenerateToken(); // Regenerasi CSRF token

        return redirect()->route('login')->with('success', 'Anda berhasil logout!');
    }
    public function showLogin()
    {
        return view('login');
    }
public function login(Request $request)
{
    $credentials = $request->validate([
        'gmail' => 'required|string',
        'password' => 'required',
    ]);

    $user = User::where('gmail', $credentials['gmail'])
                ->orWhere('username', $credentials['gmail'])
                ->first();

    if (!$user) {
        return back()->withErrors(['loginError' => 'Akun tidak ditemukan!']);
    }

    if (!Hash::check($credentials['password'], $user->password)) {
        return back()->withErrors(['loginError' => 'Password salah!']);
    }

    Auth::login($user);

    if ($user->role === 'admin') {
        return redirect()->route('laporan.index');
    } elseif ($user->role === 'teknisi') {
        return redirect()->route('teknisi.dashboard');
    } else {
        return redirect()->route('login')->withErrors(['loginError' => 'Role tidak dikenali!']);
    }
}

}
