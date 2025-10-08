<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Panel Cliente — bancoApp</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css','resources/js/app.js'])
  @livewireStyles
</head>
<body class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 text-gray-800">

  {{-- TOPBAR --}}
  <header class="sticky top-0 z-50 border-b border-gray-200/60 bg-white/75 backdrop-blur">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="h-9 w-9 rounded-xl bg-sky-600/10 grid place-items-center ring-1 ring-sky-600/20"></div>
        <div>
          <h1 class="text-lg font-semibold leading-none">Panel Cliente</h1>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-3 py-1.5 text-sm hover:bg-gray-50">
            Cerrar sesión
          </button>
        </form>
      </div>
    </div>
  </header>

  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    @if (session('ok'))
      <div class="rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-800 px-3 py-2 text-sm">
        {{ session('ok') }}
      </div>
    @endif

    {{-- Selector de cuenta --}}
    <section class="rounded-2xl border border-gray-200 bg-white p-4">
      <form method="GET" action="{{ route('cliente.home') }}" class="flex items-end gap-4">
        <div class="flex-1">
          <label class="block text-sm font-medium mb-1">Selecciona tu cuenta</label>
          <select name="cuenta" class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-sky-500">
            @forelse($cuentas as $c)
              <option value="{{ $c->id }}" @selected($cuentaSel?->id === $c->id)>
                #{{ $c->id }} — {{ ucfirst($c->tipo) }} — ${{ number_format($c->saldo,2) }}
              </option>
            @empty
              <option value="">No tienes cuentas</option>
            @endforelse
          </select>
        </div>
        <button class="rounded-lg bg-sky-600 hover:bg-sky-700 text-white px-4 py-2">Cambiar</button>
      </form>
    </section>

    @if($cuentaSel)
      {{-- Resumen y acciones --}}
      <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6">
          <p class="text-xs text-gray-500">Cuenta seleccionada</p>
          <p class="mt-1 font-semibold">#{{ $cuentaSel->id }} — {{ ucfirst($cuentaSel->tipo) }}</p>
          <p class="mt-4 text-xs text-gray-500">Saldo</p>
          <p class="text-2xl font-semibold tracking-tight">${{ number_format($cuentaSel->saldo, 2) }}</p>
        </div>

        <a href="{{ route('cliente.pagos.create', ['cuenta' => $cuentaSel->id]) }}" wire:navigate
           class="rounded-2xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-white p-6 hover:shadow">
          <p class="text-sm font-medium">+ Nuevo pago</p>
          <p class="text-xs text-gray-600 mt-1">Servicios, referencia, método</p>
        </a>

        <a href="{{ route('cliente.movimientos.create', ['cuenta' => $cuentaSel->id]) }}" wire:navigate
           class="rounded-2xl border border-indigo-200 bg-gradient-to-br from-indigo-50 to-white p-6 hover:shadow">
          <p class="text-sm font-medium">+ Nuevo movimiento</p>
          <p class="text-xs text-gray-600 mt-1">Depósito / Retiro / Transferencia</p>
        </a>
      </section>

      {{-- Listados recientes --}}
      <section class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Pagos --}}
        <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden">
          <div class="px-4 py-3 border-b bg-gray-50 flex items-center justify-between">
            <h3 class="font-semibold">Pagos recientes</h3>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead class="bg-gray-50 text-gray-600">
                <tr>
                  <th class="p-3 text-left">Referencia</th>
                  <th class="p-3 text-left">Servicio</th>
                  <th class="p-3 text-left">Monto</th>
                  <th class="p-3 text-left">Fecha</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                @forelse($pagos as $p)
                  <tr class="hover:bg-gray-50">
                    <td class="p-3">{{ $p->referencia }}</td>
                    <td class="p-3">{{ $p->tipo_servicio }}</td> {{-- <- snake_case --}}
                    <td class="p-3">${{ number_format($p->monto,2) }}</td>
                    <td class="p-3">{{ \Illuminate\Support\Carbon::parse($p->fecha)->format('Y-m-d H:i') }}</td>
                  </tr>
                @empty
                  <tr><td colspan="4" class="p-6 text-center text-gray-500">Sin pagos aún.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        {{-- Movimientos --}}
        <div class="rounded-2xl border border-gray-200 bg-white overflow-hidden">
          <div class="px-4 py-3 border-b bg-gray-50 flex items-center justify-between">
            <h3 class="font-semibold">Movimientos recientes</h3>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-sm">
              <thead class="bg-gray-50 text-gray-600">
                <tr>
                  <th class="p-3 text-left">Tipo</th>
                  <th class="p-3 text-left">Monto</th>
                  <th class="p-3 text-left">Fecha</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-100">
                @forelse($movs as $m)
                  <tr class="hover:bg-gray-50">
                    <td class="p-3 capitalize">{{ $m->tipo_movimiento }}</td>
                    <td class="p-3">${{ number_format($m->monto,2) }}</td>
                    <td class="p-3">{{ \Illuminate\Support\Carbon::parse($m->fecha)->format('Y-m-d H:i') }}</td>
                  </tr>
                @empty
                  <tr><td colspan="3" class="p-6 text-center text-gray-500">Sin movimientos aún.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </section>
    @else
      <section class="rounded-2xl border border-gray-200 bg-white p-6">
        <p class="text-gray-600">Aún no tienes cuentas asignadas.</p>
      </section>
    @endif

  </main>

  @livewireScripts
</body>
</html>
