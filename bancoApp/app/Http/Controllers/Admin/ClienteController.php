<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Usuario::where('rol', 'cliente')->latest()->paginate(10);
        return view('admin.crudclientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('admin.crudclientes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'   => ['required','string','max:255'],
            'correo'   => ['required','string','max:255','email','unique:usuarios,correo'],
            'password' => ['required','string','min:6'],
            'rfc'      => ['required','string','size:13','unique:usuarios,rfc'],
        ]);

        $data['rol']      = 'cliente';
        $data['rfc']      = strtoupper($data['rfc']);
        $data['password'] = Hash::make($data['password']);

        Usuario::create($data);

        $indexRoute = auth()->user()?->rol === 'administrador'
            ? route('admin.crudclientes.index')
            : route('empleado.crudclientes.index');

        return redirect()->to($indexRoute)->with('ok','Cliente creado');
    }

    public function edit(Usuario $cliente)
    {
        abort_if($cliente->rol !== 'cliente', 404);
        return view('admin.crudclientes.edit', compact('cliente'));
    }

    public function update(Request $request, Usuario $cliente)
    {
        abort_if($cliente->rol !== 'cliente', 404);

        $data = $request->validate([
            'nombre'   => ['required','string','max:255'],
            'correo'   => ['required','string','max:255','email',
                Rule::unique('usuarios','correo')->ignore($cliente->id)],
            'password' => ['nullable','string','min:6'],
            'rfc'      => ['required','string','size:13',
                Rule::unique('usuarios','rfc')->ignore($cliente->id)],
        ]);

        $data['rfc'] = strtoupper($data['rfc']);

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $cliente->update($data);

        $indexRoute = auth()->user()?->rol === 'administrador'
            ? route('admin.crudclientes.index')
            : route('empleado.crudclientes.index');

        return redirect()->to($indexRoute)->with('ok','Cliente actualizado');
    }

    public function destroy(Usuario $cliente)
    {
        abort_if($cliente->rol !== 'cliente', 404);
        $cliente->delete();

        $indexRoute = auth()->user()?->rol === 'administrador'
            ? route('admin.crudclientes.index')
            : route('empleado.crudclientes.index');

        return redirect()->to($indexRoute)->with('ok','Cliente eliminado');
    }
}
