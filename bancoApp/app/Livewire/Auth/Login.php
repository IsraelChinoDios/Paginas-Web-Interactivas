<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component
{
    public string $correo = '';
    public string $password = '';
    public bool $remember = false;
    public bool $isLoading = false;

    protected function rules(): array
    {
        return [
            'correo'   => ['required', 'string', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ];
    }

    public function login()
    {
        $this->validate();
        $this->isLoading = true;

        // Importante: usamos 'correo' como credencial
        if (! Auth::attempt(['correo' => $this->correo, 'password' => $this->password], $this->remember)) {
            $this->isLoading = false;
            throw ValidationException::withMessages([
                'correo' => __('Estas credenciales no coinciden con nuestros registros.'),
            ]);
        }

        request()->session()->regenerate();

        // Redirigir por rol
        $rol = Auth::user()->rol; // administrador | empleado | cliente
        return match ($rol) {
            'administrador' => redirect()->intended(route('admin.dashboard')),
            'empleado'      => redirect()->intended(route('empleado.dashboard')),
            default         => redirect()->intended(route('cliente.dashboard')),
        };
    }

    public function render()
    {
        return view('livewire.auth.login')->layout('layouts.app');
    }
}
