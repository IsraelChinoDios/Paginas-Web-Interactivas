<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function show()
    {
        return view('livewire.auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('correo', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $rol = Auth::user()->rol;

            return match ($rol) {
                'administrador' => redirect()->intended(route('admin.home')),
                'empleado'      => redirect()->intended(route('empleado.home')),
                'cliente'       => redirect()->intended(route('cliente.home')),
                default         => redirect()->intended('/'),
            };
        }

        return back()->withErrors([
            'correo' => 'Las credenciales no son correctas.',
        ])->onlyInput('correo');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
