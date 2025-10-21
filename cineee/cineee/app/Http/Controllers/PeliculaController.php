<?php

namespace App\Http\Controllers;

use App\Models\pelicula;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PeliculaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $peliculas = pelicula::query()
            ->when(
                $search,
                fn ($query) => $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('nombre', 'like', '%'.$search.'%')
                        ->orWhere('director', 'like', '%'.$search.'%')
                        ->orWhere('genero', 'like', '%'.$search.'%')
                        ->orWhere('duracion', 'like', '%'.$search.'%');
                })
            )
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('peliculas.index', [
            'peliculas' => $peliculas,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePelicula($request, 'createPelicula');

        pelicula::create($validated);

        return redirect()
            ->route('peliculas.index')
            ->with('status', __('Pelicula creada correctamente.'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, pelicula $pelicula): RedirectResponse
    {
        $validated = $this->validatePelicula($request, 'updatePelicula');

        $pelicula->update($validated);

        return redirect()
            ->route('peliculas.index')
            ->with('status', __('Pelicula actualizada correctamente.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(pelicula $pelicula): RedirectResponse
    {
        $pelicula->delete();

        return redirect()
            ->route('peliculas.index')
            ->with('status', __('Pelicula eliminada correctamente.'));
    }

    /**
     * Validate pelicula request data.
     */
    private function validatePelicula(Request $request, string $errorBag = 'default'): array
    {
        return $request->validateWithBag($errorBag, [
            'nombre' => ['required', 'string', 'max:255'],
            'director' => ['required', 'string', 'max:255'],
            'duracion' => ['required', 'string', 'max:255'],
            'genero' => ['required', 'string', 'max:255'],
        ]);
    }
}
