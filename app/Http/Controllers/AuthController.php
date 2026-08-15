<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function processLogin(Request $request)
    {
        $credentials = $request->validate([
            'nia' => ['required', 'string'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Redirect based on role
            if (Auth::user()->role === 'pelatih') {
                return redirect()->intended('pelatih/dashboard');
            }
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'nia' => 'Kombinasi NIA dan password salah.',
        ])->onlyInput('nia');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function processRegister(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // Generate NIA format SFC-001, SFC-002, etc.
        $lastUser = User::whereNotNull('nia')->orderBy('id', 'desc')->first();
        $lastNumber = 0;
        
        if ($lastUser && preg_match('/SFC-(\d+)/', $lastUser->nia, $matches)) {
            $lastNumber = (int)$matches[1];
        }
        
        $newNumber = $lastNumber + 1;
        $nia = 'SFC-' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nia' => $nia,
            'role' => 'member'
        ]);

        // Auto-login dinonaktifkan sesuai permintaan agar user harus login manual menggunakan NIA
        // Auth::login($user);

        // Simpan NIA ke session secara permanen sementara untuk ditampilkan, atau gunakan flash yang lebih kuat
        $request->session()->flash('registered_nia', $nia);

        return redirect()->route('welcome.member', ['nia' => $nia]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
