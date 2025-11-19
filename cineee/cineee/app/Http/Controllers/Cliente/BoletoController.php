<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Mail\TicketPurchaseMail;
use App\Models\funcion;
use App\Models\TicketPurchase;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class BoletoController extends Controller
{
    /**
     * Mostrar la cartelera al cliente.
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
                        ->orWhere('fecha', 'like', '%'.$search.'%')
                        ->orWhereHas('pelicula', fn ($peliculaQuery) => $peliculaQuery->where('nombre', 'like', '%'.$search.'%'))
                        ->orWhereHas('sala', function ($salaQuery) use ($search) {
                            $salaQuery
                                ->where('nombre', 'like', '%'.$search.'%')
                                ->orWhereHas('sucursal', fn ($sucursalQuery) => $sucursalQuery->where('nombre', 'like', '%'.$search.'%'));
                        });
                })
            )
            ->orderBy('fecha')
            ->paginate(10)
            ->withQueryString();

        return view('client.cartelera', compact('funciones'));
    }

    /**
     * Guardar compra de boletos del cliente.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'funcion_id' => ['required', 'exists:funcions,id'],
            'cantidad' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        $funcion = funcion::findOrFail($validated['funcion_id']);
        $total = $funcion->costo * $validated['cantidad'];

        $ticket = TicketPurchase::create([
            'user_id' => $request->user()->id,
            'funcion_id' => $funcion->id,
            'cantidad' => $validated['cantidad'],
            'total' => $total,
        ]);

        $ticket->load('funcion.pelicula', 'funcion.sala.sucursal', 'user');
        Mail::to($request->user()->email)->send(new TicketPurchaseMail($ticket));

        return redirect()->route('cliente.cartelera.pdf', $ticket);
    }

    /**
     * Descargar PDF del boleto comprado.
     */
    public function pdf(Request $request, TicketPurchase $ticket): Response
    {
        abort_if($ticket->user_id !== $request->user()->id, 403);

        $ticket->load('funcion.pelicula', 'funcion.sala.sucursal');

        $pdf = Pdf::loadView('client.pdfs.boleto', [
            'ticket' => $ticket,
        ]);

        return $pdf->download('boleto-'.$ticket->id.'.pdf');
    }
}

