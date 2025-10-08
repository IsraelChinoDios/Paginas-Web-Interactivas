<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Cuenta;
use App\Models\Pago;
use App\Models\Movimiento;

class HomeController extends Controller
{
    public function index()
    {
        $userId  = auth()->id();

        $cuentas = \App\Models\Cuenta::where('usuario_id', $userId)
            ->orderBy('id')
            ->get();

        $selectedId = request('cuenta');
        $cuentaSel  = null;

        if ($selectedId) {
            $cuentaSel = \App\Models\Cuenta::where('usuario_id', $userId)
                ->where('id', $selectedId)
                ->first();
        }

        if (!$cuentaSel && $cuentas->isNotEmpty()) {
            $cuentaSel = $cuentas->first();
        }

        $pagos = collect();
        $movs  = collect();
        if ($cuentaSel) {
            $pagos = \App\Models\Pago::where('cuenta_id', $cuentaSel->id)
                        ->latest('id')
                        ->limit(10)->get();

            $movs  = \App\Models\Movimiento::where('cuenta_id', $cuentaSel->id)
                        ->latest('id')
                        ->limit(10)->get();
        }

        return view('dashboards.cliente', compact('cuentas', 'cuentaSel', 'pagos', 'movs'));
    }
}
