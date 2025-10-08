<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>CRUD Clientes — bancoApp</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css','resources/js/app.js'])
  @livewireStyles
</head>
<body class="min-h-screen bg-gray-50 text-gray-800">

  @php
    // Prefijo de rutas y panel según rol
    $area  = auth()->user()?->rol === 'administrador' ? 'admin' : 'empleado';
    $panel = $area.'.home';
  @endphp

  {{-- TOPBAR --}}
  <header class="sticky top-0 z-50 border-b border-gray-200/60 bg-white/75 backdrop-blur">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="h-9 w-9 rounded-xl bg-emerald-600/10 grid place-items-center ring-1 ring-emerald-600/20">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" viewBox="0 0 24 24" fill="currentColor"/>
        </div>
        <div>
          <h1 class="text-lg font-semibold leading-none">CRUD Clientes</h1>
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
    <div class="flex items-center justify-between mb-6">
      <div class="space-y-1">
        <p class="text-sm text-gray-500">Lista de clientes</p>
      </div>
      <a href="{{ route($area.'.crudclientes.create') }}" wire:navigate
         class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 text-sm font-medium shadow-sm">
        + Nuevo
      </a>
    </div>

    @if (session('ok'))
      <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-800 px-3 py-2 text-sm">
        {{ session('ok') }}
      </div>
    @endif

    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-50 text-gray-600">
            <tr>
              <th class="p-3 text-left font-medium">ID</th>
              <th class="p-3 text-left font-medium">Nombre</th>
              <th class="p-3 text-left font-medium">Correo</th>
              <th class="p-3 text-left font-medium">RFC</th>
              <th class="p-3 text-left font-medium w-44">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse ($clientes as $c)
              <tr class="hover:bg-gray-50">
                <td class="p-3">{{ $c->id }}</td>
                <td class="p-3 font-medium text-gray-900">{{ $c->nombre }}</td>
                <td class="p-3 text-gray-700">{{ $c->correo }}</td>
                <td class="p-3 text-gray-700">{{ $c->rfc }}</td>
                <td class="p-3">
                  <div class="flex items-center gap-2">
                    <a href="{{ route($area.'.crudclientes.edit', $c) }}" wire:navigate
                       class="inline-flex items-center rounded-md bg-emerald-100 text-emerald-800 hover:bg-emerald-200 px-3 py-1.5 text-xs font-medium">
                      Editar
                    </a>
                    <form action="{{ route($area.'.crudclientes.destroy', $c) }}" method="POST"
                          onsubmit="return confirm('¿Eliminar cliente #{{ $c->id }}?')">
                      @csrf @method('DELETE')
                      <button class="inline-flex items-center rounded-md bg-red-100 text-red-700 hover:bg-red-200 px-3 py-1.5 text-xs font-medium">
                        Eliminar
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="p-8 text-center text-gray-500">
                  No hay clientes todavía.
                  <a href="{{ route($area.'.crudclientes.create') }}" wire:navigate class="text-emerald-700 hover:underline ml-1">Crear uno</a>.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="border-t border-gray-100 px-4 py-3">
        {{ $clientes->links() }}
      </div>
    </div>
  </main>

  @livewireScripts
</body>
</html>
