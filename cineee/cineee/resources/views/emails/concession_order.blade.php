<!DOCTYPE html>
<html lang="es">
<body style="font-family: Arial, sans-serif;">
    <h2>Pedido de dulcería confirmado</h2>
    <p>Hola {{ $order->user?->name ?? 'Cliente' }},</p>
    <p>Hemos registrado tu pedido de dulcería.</p>
    <ul>
        <li><strong>Pedido #:</strong> {{ $order->id }}</li>
        <li><strong>Total:</strong> ${{ number_format($order->total, 2) }}</li>
    </ul>
    <p>Detalles:</p>
    <ul>
        @foreach ($order->items ?? [] as $item)
            <li>{{ $item['cantidad'] ?? 0 }} x {{ $item['nombre'] ?? '' }} — ${{ number_format($item['total'] ?? 0, 2) }}</li>
        @endforeach
    </ul>
    <p>Puedes descargar el PDF desde la aplicación.</p>
    <p>¡Gracias por tu compra!</p>
</body>
</html>
