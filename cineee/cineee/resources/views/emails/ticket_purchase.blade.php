<!DOCTYPE html>
<html lang="es">
<body style="font-family: Arial, sans-serif;">
    <h2>Gracias por tu compra</h2>
    <p>Hola {{ $ticket->user?->name ?? 'Cliente' }},</p>
    <p>Hemos registrado tu compra de boletos.</p>
    <ul>
        <li><strong>Boleto #:</strong> {{ $ticket->id }}</li>
        <li><strong>Película:</strong> {{ $ticket->funcion?->pelicula?->nombre }}</li>
        <li><strong>Sucursal / Sala:</strong> {{ $ticket->funcion?->sala?->sucursal?->nombre }} - {{ $ticket->funcion?->sala?->nombre }}</li>
        <li><strong>Fecha:</strong> {{ optional($ticket->funcion?->fecha)->format('d/m/Y H:i') }}</li>
        <li><strong>Tipo:</strong> {{ $ticket->funcion?->tipo }}</li>
        <li><strong>Cantidad:</strong> {{ $ticket->cantidad }}</li>
        <li><strong>Total:</strong> ${{ number_format($ticket->total, 2) }}</li>
    </ul>
    <p>Puedes descargar tu PDF desde la aplicación.</p>
    <p>¡Disfruta la función!</p>
</body>
</html>
