<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Cuenta;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;


class PagoController extends Controller
{
    // Listado (opcional)
    public function index()
    {
        $userId = auth()->id();

        $cuentas = Cuenta::where('usuario_id', $userId)->orderBy('id')->get();
        $cuentaSel = null;
        if ($id = request('cuenta')) {
            $cuentaSel = Cuenta::where('usuario_id', $userId)->where('id', $id)->first();
        }
        if (!$cuentaSel && $cuentas->isNotEmpty()) $cuentaSel = $cuentas->first();

        $pagos = collect();
        if ($cuentaSel) {
            $pagos = Pago::where('cuenta_id', $cuentaSel->id)->latest('id')->paginate(10);
        }

        return view('cliente.pagos.index', compact('cuentas','cuentaSel','pagos'));
    }

    // Form de creación
    public function create(Request $request)
    {
        $userId = auth()->id();

        $cuentas = Cuenta::where('usuario_id', $userId)->orderBy('id')->get();
        abort_if($cuentas->isEmpty(), 403, 'No tienes cuentas.');

        $cuentaSel = null;
        if ($id = $request->query('cuenta')) {
            $cuentaSel = Cuenta::where('usuario_id', $userId)->where('id', $id)->first();
        }
        if (!$cuentaSel) $cuentaSel = $cuentas->first();

        return view('cliente.pagos.create', compact('cuentas','cuentaSel'));
    }

    // Guardar
    public function store(Request $request)
    {
        $userId = auth()->id();

        $data = $request->validate([
            'cuenta_id'     => ['required','integer',
                \Illuminate\Validation\Rule::exists('cuentas','id')
                    ->where(fn($q)=>$q->where('usuario_id',$userId))],
            'referencia'    => ['required','string','max:50'],
            'tipo_servicio' => ['required','string','max:50'],
            'monto'         => ['required','numeric','min:0.01'],
            'metodo_pago'   => ['required', \Illuminate\Validation\Rule::in(['efectivo','tarjeta'])],
            'fecha'         => ['nullable','date'],
        ]);

        $pago = null;

        DB::transaction(function () use ($data, $userId, &$pago) {
            // Bloquea la fila de la cuenta para evitar condiciones de carrera
            $cuenta = \App\Models\Cuenta::where('id', $data['cuenta_id'])
                ->where('usuario_id', $userId)
                ->lockForUpdate()
                ->firstOrFail();

            // Valida saldo suficiente
            if ($cuenta->saldo < $data['monto']) {
                throw ValidationException::withMessages([
                    'monto' => 'Saldo insuficiente para realizar el pago.',
                ]);
            }

            // Descuenta saldo
            $cuenta->decrement('saldo', $data['monto']);

            // Crea el pago
            $pago = \App\Models\Pago::create([
                'cuenta_id'     => $data['cuenta_id'],
                'referencia'    => $data['referencia'],
                'tipo_servicio' => $data['tipo_servicio'],
                'monto'         => $data['monto'],
                'metodo_pago'   => $data['metodo_pago'],
                'fecha'         => $data['fecha'] ?? now(),
            ]);

            \App\Models\Movimiento::create([
            'cuenta_id'       => $data['cuenta_id'],
            'tipo_movimiento' => 'pago',
            'monto'           => -1 * $data['monto'],
            'fecha'           => $data['fecha'] ?? now(),
        ]);
        });

        return redirect()
            ->route('cliente.home', ['cuenta' => $data['cuenta_id']])
            ->with('ok', 'Pago registrado y saldo actualizado.');
    }
}
