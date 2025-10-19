<?php

namespace App\Http\Controllers;

use App\Models\sala;
use App\Models\sucursal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SalaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $salas = sala::query()
            ->with('sucursal')
            ->when(
                $search,
                fn ($query) => $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('nombre', 'like', '%'.$search.'%')
                        ->orWhere('capacidad', 'like', '%'.$search.'%')
                        ->orWhereHas('sucursal', fn ($sucursalQuery) => $sucursalQuery->where('nombre', 'like', '%'.$search.'%'));
                })
            )
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        $sucursales = sucursal::query()
            ->orderBy('nombre')
            ->get(['id', 'nombre']);

        return view('salas.index', [
            'salas' => $salas,
            'sucursales' => $sucursales,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSala($request, 'createSala');

        sala::create($validated);

        return redirect()
            ->route('salas.index')
            ->with('status', __('Sala creada correctamente.'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, sala $sala): RedirectResponse
    {
        $validated = $this->validateSala($request, 'updateSala');

        $sala->update($validated);

        return redirect()
            ->route('salas.index')
            ->with('status', __('Sala actualizada correctamente.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(sala $sala): RedirectResponse
    {
        $sala->delete();

        return redirect()
            ->route('salas.index')
            ->with('status', __('Sala eliminada correctamente.'));
    }

    /**
     * Validate sala request data.
     */
    private function validateSala(Request $request, string $errorBag = 'default'): array
    {
        return $request->validateWithBag($errorBag, [
            'nombre' => ['required', 'string', 'max:255'],
            'capacidad' => ['required', 'integer', 'min:1'],
            'sucursal_id' => ['required', 'exists:sucursals,id'],
        ]);
    }
}
