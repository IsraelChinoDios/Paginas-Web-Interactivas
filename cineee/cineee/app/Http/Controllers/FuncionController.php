<?php

namespace App\Http\Controllers;

use App\Models\funcion;
use App\Models\pelicula;
use App\Models\sala;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FuncionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $funciones = funcion::query()
            ->with(['pelicula', 'sala.sucursal'])
            ->when(
                $search,
                fn ($query) => $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('tipo', 'like', '%'.$search.'%')
                        ->orWhere('costo', 'like', '%'.$search.'%')
                        ->orWhere('fecha', 'like', '%'.$search.'%')
                        ->orWhereHas('pelicula', fn ($peliculaQuery) => $peliculaQuery->where('nombre', 'like', '%'.$search.'%'))
                        ->orWhereHas('sala', function ($salaQuery) use ($search) {
                            $salaQuery
                                ->where('nombre', 'like', '%'.$search.'%')
                                ->orWhereHas('sucursal', fn ($sucursalQuery) => $sucursalQuery->where('nombre', 'like', '%'.$search.'%'));
                        });
                })
            )
            ->orderByDesc('fecha')
            ->paginate(10)
            ->withQueryString();

        $peliculas = pelicula::query()
            ->orderBy('nombre')
            ->get(['id', 'nombre']);

        $salas = sala::query()
            ->with('sucursal:id,nombre')
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'sucursal_id']);

        return view('funciones.index', [
            'funciones' => $funciones,
            'peliculas' => $peliculas,
            'salas' => $salas,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateFuncion($request, 'createFuncion');

        funcion::create($validated);

        return redirect()
            ->route('funciones.index')
            ->with('status', __('Funcion creada correctamente.'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, funcion $funcion): RedirectResponse
    {
        $validated = $this->validateFuncion($request, 'updateFuncion');

        $funcion->update($validated);

        return redirect()
            ->route('funciones.index')
            ->with('status', __('Funcion actualizada correctamente.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(funcion $funcion): RedirectResponse
    {
        $funcion->delete();

        return redirect()
            ->route('funciones.index')
            ->with('status', __('Funcion eliminada correctamente.'));
    }

    /**
     * Validate funcion request data.
     */
    private function validateFuncion(Request $request, string $errorBag = 'default'): array
    {
        $validated = $request->validateWithBag($errorBag, [
            'fecha' => ['required', 'date'],
            'pelicula_id' => ['required', 'exists:peliculas,id'],
            'sala_id' => ['required', 'exists:salas,id'],
            'tipo' => ['required', 'string', 'max:255'],
            'costo' => ['required', 'numeric', 'min:0'],
        ]);

        if (isset($validated['fecha'])) {
            $validated['fecha'] = Carbon::parse($validated['fecha'])->format('Y-m-d H:i:s');
        }

        return $validated;
    }
}
