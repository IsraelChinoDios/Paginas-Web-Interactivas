<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cuenta;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CuentaController extends Controller
{
    /** Listado de cuentas */
    public function index()
    {
        $cuentas = Cuenta::with('usuario:id,nombre,correo')
            ->orderByDesc('id')
            ->paginate(10);

        return view('admin.cuentas.index', compact('cuentas'));
    }

    /** Form para crear */
    public function create()
    {
        $clientes = Usuario::where('rol', 'cliente')
            ->orderBy('nombre')
            ->get(['id','nombre','correo']);

        return view('admin.cuentas.create', compact('clientes'));
    }

    /** Persistir nueva cuenta */
    public function store(Request $request)
    {
        $data = $request->validate([
            'cliente_id' => [
                'required','integer',
                Rule::exists('usuarios', 'id')->where(fn($q) => $q->where('rol','cliente')),
            ],
            'tipo'  => ['required', Rule::in(['nomina','credito'])],
            'saldo' => ['required','string'], // saneamos abajo para permitir "1,234.56"
        ]);

        $saldo = (float) str_replace([',', ' '], ['', ''], $data['saldo']);

        DB::transaction(function () use ($data, $saldo) {
            Cuenta::create([
                'tipo'       => $data['tipo'],
                'usuario_id' => (int) $data['cliente_id'], // FK → usuarios.id
                'saldo'      => $saldo,
            ]);
        });

        $indexRoute = auth()->user()?->rol === 'administrador'
            ? route('admin.cuentas.index')
            : route('empleado.cuentas.index');

        return redirect()->to($indexRoute)->with('ok', 'Cuenta creada correctamente.');
    }

    /** Form para editar */
    public function edit(Cuenta $cuenta)
    {
        $cuenta->load('usuario:id,nombre,correo');

        $clientes = Usuario::where('rol', 'cliente')
            ->orderBy('nombre')
            ->get(['id','nombre','correo']);

        return view('admin.cuentas.edit', compact('cuenta','clientes'));
    }

    /** Actualizar cuenta */
    public function update(Request $request, Cuenta $cuenta)
    {
        $data = $request->validate([
            'cliente_id' => [
                'required','integer',
                Rule::exists('usuarios', 'id')->where(fn($q) => $q->where('rol','cliente')),
            ],
            'tipo'  => ['required', Rule::in(['nomina','credito'])],
            'saldo' => ['required','string'],
        ]);

        $saldo = (float) str_replace([',', ' '], ['', ''], $data['saldo']);

        DB::transaction(function () use ($cuenta, $data, $saldo) {
            $cuenta->update([
                'tipo'       => $data['tipo'],
                'usuario_id' => (int) $data['cliente_id'],
                'saldo'      => $saldo,
            ]);
        });

        $indexRoute = auth()->user()?->rol === 'administrador'
            ? route('admin.cuentas.index')
            : route('empleado.cuentas.index');

        return redirect()->to($indexRoute)->with('ok', 'Cuenta actualizada.');
    }

    /** Eliminar cuenta */
    public function destroy(Cuenta $cuenta)
    {
        DB::transaction(function () use ($cuenta) {
            $cuenta->delete();
        });

        $indexRoute = auth()->user()?->rol === 'administrador'
            ? route('admin.cuentas.index')
            : route('empleado.cuentas.index');

        return redirect()->to($indexRoute)->with('ok', 'Cuenta eliminada.');
    }
}
