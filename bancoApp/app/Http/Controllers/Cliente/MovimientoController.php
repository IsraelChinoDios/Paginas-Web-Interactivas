<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Cuenta;
use App\Models\Movimiento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class MovimientoController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $cuentas = Cuenta::where('usuario_id', $userId)->orderBy('id')->get();
        $cuentaSel = null;

        if ($id = request('cuenta')) {
            $cuentaSel = Cuenta::where('usuario_id', $userId)->where('id', $id)->first();
        }
        if (!$cuentaSel && $cuentas->isNotEmpty()) {
            $cuentaSel = $cuentas->first();
        }

        $movs = collect();
        if ($cuentaSel) {
            $movs = Movimiento::where('cuenta_id', $cuentaSel->id)
                ->latest('id')
                ->paginate(10);
        }

        return view('cliente.movimientos.index', compact('cuentas', 'cuentaSel', 'movs'));
    }

    public function create(Request $request)
    {
        $userId = auth()->id();

        $cuentas = Cuenta::where('usuario_id', $userId)->orderBy('id')->get();
        abort_if($cuentas->isEmpty(), 403, 'No tienes cuentas.');

        $cuentaSel = null;
        if ($id = $request->query('cuenta')) {
            $cuentaSel = Cuenta::where('usuario_id', $userId)->where('id', $id)->first();
        }
        if (!$cuentaSel) {
            $cuentaSel = $cuentas->first();
        }

        // Opciones válidas para el <select>
        $tipos = [
            'deposito' => 'Depósito',
            'retiro'   => 'Retiro',
            // 'transferencia' => 'Transferencia', // si la agregas, recuerda validarlo abajo
        ];

        return view('cliente.movimientos.create', compact('cuentas', 'cuentaSel', 'tipos'));
    }

    public function store(Request $request)
    {
        $userId = auth()->id();

        // Compatibilidad si algún form todavía envía camelCase
        if ($request->has('tipoMovimiento') && !$request->has('tipo_movimiento')) {
            $request->merge(['tipo_movimiento' => $request->input('tipoMovimiento')]);
        }

        // Normaliza (ej. "Depósito" -> "deposito")
        if ($request->filled('tipo_movimiento')) {
            $request->merge([
                'tipo_movimiento' => Str::lower(Str::ascii($request->input('tipo_movimiento')))
            ]);
        }

        $data = $request->validate([
            'cuenta_id'       => ['required', 'integer',
                Rule::exists('cuentas', 'id')->where(fn ($q) => $q->where('usuario_id', $userId))],
            'tipo_movimiento' => ['required', Rule::in(['deposito', 'retiro'])],
            // si agregas 'transferencia', inclúyela aquí también ↑
            'monto'           => ['required', 'numeric', 'min:0.01'],
            'fecha'           => ['nullable', 'date'],
        ]);

        DB::transaction(function () use ($data, $userId) {
            // Bloquea la fila de la cuenta para evitar race conditions
            $cuenta = Cuenta::where('id', $data['cuenta_id'])
                ->where('usuario_id', $userId)
                ->lockForUpdate()
                ->firstOrFail();

            $fecha = isset($data['fecha']) ? Carbon::parse($data['fecha']) : now();
            $delta = $data['monto'];

            if ($data['tipo_movimiento'] === 'deposito') {
                // Depósito: suma saldo y registra positivo
                $cuenta->increment('saldo', $delta);
            } else { // retiro
                if ($cuenta->saldo < $delta) {
                    throw ValidationException::withMessages([
                        'monto' => 'Saldo insuficiente para el retiro.',
                    ]);
                }
                // Retiro: resta saldo y registra negativo
                $cuenta->decrement('saldo', $delta);
                $delta = -$delta;
            }

            Movimiento::create([
                'cuenta_id'       => $cuenta->id,
                'tipo_movimiento' => $data['tipo_movimiento'],
                'monto'           => $delta,   // +depósito, -retiro
                'fecha'           => $fecha,
            ]);
        });

        return redirect()
            ->route('cliente.home', ['cuenta' => $data['cuenta_id']])
            ->with('ok', 'Movimiento registrado y saldo actualizado.');
    }
}
