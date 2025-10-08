<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Cuentas — bancoApp</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css','resources/js/app.js'])
  @livewireStyles
</head>
<body class="min-h-screen bg-gray-50 text-gray-800">

  @php
    // Prefijo de rutas y panel según el rol actual
    $area  = auth()->user()?->rol === 'administrador' ? 'admin' : 'empleado';
    $panel = $area.'.home';
  @endphp

  {{-- TOPBAR --}}
  <header class="sticky top-0 z-50 border-b border-gray-200/60 bg-white/75 backdrop-blur">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="h-9 w-9 rounded-xl bg-red-600/10 grid place-items-center ring-1 ring-red-600/20"></div>
        <div>
          <h1 class="text-lg font-semibold leading-none">Cuentas</h1>
          <p class="text-xs text-gray-500 leading-none mt-1">Relación de cuentas por cliente</p>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <a href="{{ route($panel) }}" wire:navigate
           class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 px-3 py-1.5 text-sm">
          ← Panel
        </a>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-3 py-1.5 text-sm hover:bg-gray-50">
            Cerrar sesión
          </button>
        </form>
      </div>
    </div>
  </header>

  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8">

    {{-- BARRA DE ACCIONES --}}
    <div class="flex items-center justify-between mb-6">
      <p class="text-sm text-gray-500">Lista de cuentas</p>
      <a href="{{ route($area.'.cuentas.create') }}" wire:navigate
         class="inline-flex items-center gap-2 rounded-lg bg-red-600 hover:bg-red-700 text-white px-4 py-2 text-sm font-medium shadow-sm">
        + Nueva
      </a>
    </div>

    @if (session('ok'))
      <div class="mb-4 rounded-lg border border-red-200 bg-red-50 text-red-800 px-3 py-2 text-sm">
        {{ session('ok') }}
      </div>
    @endif

    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 text-gray-600">
            <tr>
              <th class="p-3 text-left font-medium">ID</th>
              <th class="p-3 text-left font-medium">Cliente</th>
              <th class="p-3 text-left font-medium">Tipo</th>
              <th class="p-3 text-left font-medium">Saldo</th>
              <th class="p-3 text-left font-medium">Creación</th>
              <th class="p-3 text-left font-medium w-44">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse ($cuentas as $cuenta)
              <tr class="hover:bg-gray-50">
                <td class="p-3">{{ $cuenta->id }}</td>
                <td class="p-3">
                  {{ $cuenta->usuario->nombre ?? '—' }}
                  <span class="text-gray-500">({{ $cuenta->usuario->correo ?? '—' }})</span>
                </td>
                <td class="p-3 capitalize">{{ $cuenta->tipo }}</td>
                <td class="p-3 font-medium">${{ number_format($cuenta->saldo, 2) }}</td>
                <td class="p-3 text-gray-600">{{ $cuenta->created_at?->format('Y-m-d H:i') }}</td>
                <td class="p-3">
                  <div class="flex items-center gap-2">
                    <a href="{{ route($area.'.cuentas.edit', $cuenta) }}" wire:navigate
                       class="inline-flex items-center rounded-md bg-white-100 text-red-800 hover:bg-red-200 px-3 py-1.5 text-xs font-medium">
                      Editar
                    </a>
                    <form method="POST" action="{{ route($area.'.cuentas.destroy', $cuenta) }}"
                          onsubmit="return confirm('¿Eliminar cuenta #{{ $cuenta->id }}?')">
                      @csrf @method('DELETE')
                      <button
                        class="inline-flex items-center rounded-md bg-white-100 text-red-700 hover:bg-red-200 px-3 py-1.5 text-xs font-medium">
                        Eliminar
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="p-8 text-center text-gray-500">
                  Aún no hay cuentas.
                  <a href="{{ route($area.'.cuentas.create') }}" wire:navigate class="text-red-700 hover:underline">Crea la primera</a>.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="border-top border-gray-100 px-4 py-3">
        {{ $cuentas->links() }}
      </div>
    </div>
  </main>

  @livewireScripts
</body>
</html>
