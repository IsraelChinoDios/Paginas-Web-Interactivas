<?php

namespace App\Http\Controllers;

use App\Models\sucursal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SucursalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $sucursales = sucursal::query()
            ->when(
                $search,
                fn ($query) => $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('nombre', 'like', '%'.$search.'%')
                        ->orWhere('direccion', 'like', '%'.$search.'%')
                        ->orWhere('director', 'like', '%'.$search.'%');
                })
            )
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('sucursales.index', compact('sucursales'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSucursal($request, 'createSucursal');

        sucursal::create($validated);

        return redirect()
            ->route('sucursales.index')
            ->with('status', __('Sucursal creada correctamente.'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, sucursal $sucursal): RedirectResponse
    {
        $validated = $this->validateSucursal($request, 'updateSucursal');

        $sucursal->update($validated);

        return redirect()
            ->route('sucursales.index')
            ->with('status', __('Sucursal actualizada correctamente.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(sucursal $sucursal): RedirectResponse
    {
        $sucursal->delete();

        return redirect()
            ->route('sucursales.index')
            ->with('status', __('Sucursal eliminada correctamente.'));
    }

    /**
     * Validate sucursal request data.
     */
    private function validateSucursal(Request $request, string $errorBag = 'default'): array
    {
        return $request->validateWithBag($errorBag, [
            'nombre' => ['required', 'string', 'max:255'],
            'direccion' => ['required', 'string', 'max:255'],
            'director' => ['required', 'string', 'max:255'],
            'telefono' => ['required', 'string', 'max:25'],
        ]);
    }
}
