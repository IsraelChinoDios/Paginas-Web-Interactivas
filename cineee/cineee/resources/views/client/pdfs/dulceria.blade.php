<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Pedido de dulcería #{{ $order->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 20px; }
        h1 { font-size: 20px; margin-bottom: 10px; }
        .section { margin-bottom: 12px; }
        .label { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 8px; border: 1px solid #ddd; font-size: 12px; }
        tfoot td { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Pedido de dulcería #{{ $order->id }}</h1>

    <div class="section">
        <div><span class="label">Cliente:</span> {{ $order->user?->name ?? 'N/D' }}</div>
        <div><span class="label">Correo:</span> {{ $order->user?->email ?? 'N/D' }}</div>
        <div><span class="label">Fecha de emisión:</span> {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items ?? [] as $item)
                <tr>
                    <td>{{ $item['nombre'] ?? '' }}</td>
                    <td>${{ number_format($item['precio'] ?? 0, 2) }}</td>
                    <td>{{ $item['cantidad'] ?? 0 }}</td>
                    <td>${{ number_format($item['total'] ?? 0, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" style="text-align:right;">Total</td>
                <td>${{ number_format($order->total, 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
