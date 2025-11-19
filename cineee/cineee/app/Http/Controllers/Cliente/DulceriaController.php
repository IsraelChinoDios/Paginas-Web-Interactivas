<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Mail\ConcessionOrderMail;
use App\Models\ConcessionOrder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class DulceriaController extends Controller
{
    private const PRODUCTOS = [
        ['id' => 'palomitas', 'nombre' => 'Palomitas', 'precio' => 60],
        ['id' => 'refresco', 'nombre' => 'Refresco', 'precio' => 45],
        ['id' => 'nachos', 'nombre' => 'Nachos', 'precio' => 70],
        ['id' => 'dulces', 'nombre' => 'Dulces', 'precio' => 35],
    ];

    /**
     * Mostrar menú de dulcería.
     */
    public function index(): View
    {
        return view('client.dulceria', [
            'productos' => self::PRODUCTOS,
        ]);
    }

    /**
     * Guardar pedido de dulcería.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'string'],
            'items.*.cantidad' => ['required', 'integer', 'min:0', 'max:20'],
        ]);

        $catalogo = collect(self::PRODUCTOS)->keyBy('id');
        $items = [];
        $total = 0;

        foreach ($validated['items'] as $item) {
            $producto = $catalogo[$item['id']] ?? null;
            $cantidad = (int) $item['cantidad'];

            if (! $producto || $cantidad === 0) {
                continue;
            }

            $linea = $producto['precio'] * $cantidad;
            $items[] = [
                'id' => $producto['id'],
                'nombre' => $producto['nombre'],
                'precio' => $producto['precio'],
                'cantidad' => $cantidad,
                'total' => $linea,
            ];
            $total += $linea;
        }

        if ($total <= 0) {
            return back()->withErrors(['items' => __('Selecciona al menos un producto')]);
        }

        $order = ConcessionOrder::create([
            'user_id' => $request->user()->id,
            'items' => $items,
            'total' => $total,
        ]);

        $order->load('user');
        Mail::to($request->user()->email)->send(new ConcessionOrderMail($order));

        return redirect()->route('cliente.dulceria.pdf', $order);
    }

    /**
     * Descargar PDF del pedido de dulcería.
     */
    public function pdf(Request $request, ConcessionOrder $order): Response
    {
        abort_if($order->user_id !== $request->user()->id, 403);

        $pdf = Pdf::loadView('client.pdfs.dulceria', [
            'order' => $order,
        ]);

        return $pdf->download('dulceria-'.$order->id.'.pdf');
    }
}

