<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boleto #{{ $ticket->id }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 20px; }
        h1 { font-size: 20px; margin-bottom: 10px; }
        .section { margin-bottom: 12px; }
        .label { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 8px; border: 1px solid #ddd; font-size: 12px; }
    </style>
</head>
<body>
    <h1>Boleto de cine #{{ $ticket->id }}</h1>

    <div class="section">
        <div><span class="label">Cliente:</span> {{ $ticket->user?->name ?? 'N/D' }}</div>
        <div><span class="label">Correo:</span> {{ $ticket->user?->email ?? 'N/D' }}</div>
        <div><span class="label">Fecha de emisión:</span> {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Película</th>
                <th>Sucursal</th>
                <th>Sala</th>
                <th>Fecha</th>
                <th>Tipo</th>
                <th>Cantidad</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $ticket->funcion?->pelicula?->nombre }}</td>
                <td>{{ $ticket->funcion?->sala?->sucursal?->nombre }}</td>
                <td>{{ $ticket->funcion?->sala?->nombre }}</td>
                <td>{{ optional($ticket->funcion?->fecha)->format('d/m/Y H:i') }}</td>
                <td>{{ $ticket->funcion?->tipo }}</td>
                <td>{{ $ticket->cantidad }}</td>
                <td>${{ number_format($ticket->total, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
