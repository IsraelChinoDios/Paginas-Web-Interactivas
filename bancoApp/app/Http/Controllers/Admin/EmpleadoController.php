<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmpleadoController extends Controller
{
    public function index()
    {
        $empleados = \App\Models\Usuario::where('rol', 'empleado')->latest()->paginate(10);
        return view('admin.crudempleados.index', compact('empleados'));
    }

    public function create()
    {
        return view('admin.crudempleados.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'   => ['required','string','max:255'],
            'correo'   => ['required','string','max:255','email','unique:usuarios,correo'],
            'password' => ['required','string','min:6'],
            'rfc'      => ['required','string','size:13','unique:usuarios,rfc'],
        ]);

        $data['rol'] = 'empleado';
        \App\Models\Usuario::create($data);

        return redirect()->route('admin.crudempleados.index')->with('ok','Empleado creado'); // ← aquí
    }

    public function edit(\App\Models\Usuario $empleado)
    {
        abort_if($empleado->rol !== 'empleado', 404);
        return view('admin.crudempleados.edit', compact('empleado'));
    }

    public function update(Request $request, \App\Models\Usuario $empleado)
    {
        abort_if($empleado->rol !== 'empleado', 404);

        $data = $request->validate([
            'nombre'   => ['required','string','max:255'],
            'correo'   => ['required','string','max:255','email',
                \Illuminate\Validation\Rule::unique('usuarios','correo')->ignore($empleado->id)],
            'password' => ['nullable','string','min:6'],
            'rfc'      => ['required','string','size:13',
                \Illuminate\Validation\Rule::unique('usuarios','rfc')->ignore($empleado->id)],
        ]);

        if (empty($data['password'])) unset($data['password']);
        $empleado->update($data);

        return redirect()->route('admin.crudempleados.index')->with('ok','Empleado actualizado'); // ← aquí
    }

    public function destroy(\App\Models\Usuario $empleado)
    {
        abort_if($empleado->rol !== 'empleado', 404);
        $empleado->delete();

        return redirect()->route('admin.crudempleados.index')->with('ok','Empleado eliminado'); // ← opcional
    }
}
