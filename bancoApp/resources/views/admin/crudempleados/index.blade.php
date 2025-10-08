<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>CRUD Empleados — bancoApp</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css','resources/js/app.js'])
  @livewireStyles
</head>
<body class="min-h-screen bg-gray-50 text-gray-800">

  {{-- TOPBAR --}}
  <header class="sticky top-0 z-50 border-b border-gray-200/60 bg-white/75 backdrop-blur">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="h-9 w-9 rounded-xl bg-indigo-600/10 grid place-items-center ring-1 ring-indigo-600/20">
        </div>
        <div>
          <h1 class="text-lg font-semibold leading-none">CRUD Empleados</h1>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <a href="{{ route('admin.home') }}" wire:navigate
           class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 px-3 py-1.5 text-sm">
          ← Panel Admin
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
      <p class="text-sm text-gray-500">Lista de empleados</p>
      <a href="{{ route('admin.crudempleados.create') }}" wire:navigate
         class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 text-sm font-medium shadow-sm">
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
              <th class="p-3 text-left font-medium w-40">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            @forelse ($empleados as $e)
              <tr class="hover:bg-gray-50">
                <td class="p-3">{{ $e->id }}</td>
                <td class="p-3 font-medium text-gray-900">{{ $e->nombre }}</td>
                <td class="p-3 text-gray-700">{{ $e->correo }}</td>
                <td class="p-3 text-gray-700">{{ $e->rfc }}</td>
                <td class="p-3">
                  <div class="flex items-center gap-2">
                    <a href="{{ route('admin.crudempleados.edit', $e) }}" wire:navigate
                       class="inline-flex items-center rounded-md bg-indigo-100 text-indigo-800 hover:bg-indigo-200 px-3 py-1.5 text-xs font-medium">
                      Editar
                    </a>
                    <form action="{{ route('admin.crudempleados.destroy', $e) }}" method="POST"
                          onsubmit="return confirm('¿Eliminar empleado #{{ $e->id }}?')">
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
                  No hay empleados todavía.
                  <a href="{{ route('admin.crudempleados.create') }}" wire:navigate class="text-indigo-700 hover:underline ml-1">Crear uno</a>.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <div class="border-t border-gray-100 px-4 py-3">
        {{ $empleados->links() }}
      </div>
    </div>
  </main>

  @livewireScripts
</body>
</html>
