<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar Empleado #{{ $empleado->id }} — bancoApp</title>
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
          <h1 class="text-lg font-semibold leading-none">Editar Empleado #{{ $empleado->id }}</h1>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <a href="{{ route('admin.crudempleados.index') }}" wire:navigate
           class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 px-3 py-1.5 text-sm">
          ← Volver a empleados
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

  <main class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 py-10">
    @if ($errors->any())
      <div class="mb-6 rounded-lg border border-red-200 bg-red-50 text-red-800 px-4 py-3 text-sm">
        <ul class="list-disc list-inside">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.crudempleados.update', $empleado) }}"
          class="rounded-2xl border border-gray-200 bg-white shadow-sm p-6 space-y-5">
      @csrf @method('PUT')

      <div>
        <label class="block text-sm font-medium mb-1" for="nombre">Nombre</label>
        <input id="nombre" name="nombre" value="{{ old('nombre',$empleado->nombre) }}" required
               class="w-full border border-gray-300 rounded-lg p-2.5
                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        @error('nombre') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="block text-sm font-medium mb-1" for="correo">Correo</label>
        <input id="correo" name="correo" type="email" value="{{ old('correo',$empleado->correo) }}" required
               class="w-full border border-gray-300 rounded-lg p-2.5
                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        @error('correo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
      </div>

      <div>
        <div class="flex items-center justify-between">
          <label class="block text-sm font-medium mb-1" for="password">Contraseña (opcional)</label>
          <button type="button"
                  onclick="const i=document.getElementById('password'); i.type = i.type==='password'?'text':'password'; this.classList.toggle('opacity-60')"
                  class="text-xs text-gray-600 hover:text-gray-900">mostrar/ocultar</button>
        </div>
        <input id="password" name="password" type="password" placeholder="Dejar vacío para no cambiar"
               class="w-full border border-gray-300 rounded-lg p-2.5
                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="block text-sm font-medium mb-1" for="rfc">RFC (13 caracteres)</label>
        <input id="rfc" name="rfc" maxlength="13" value="{{ old('rfc',$empleado->rfc) }}" required
               class="w-full border border-gray-300 rounded-lg p-2.5 uppercase tracking-wide
                      focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
        @error('rfc') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
      </div>

      <div class="pt-2 flex items-center gap-2">
        <button class="inline-flex items-center rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 font-medium">
          Actualizar
        </button>
        <a href="{{ route('admin.crudempleados.index') }}" wire:navigate
           class="inline-flex items-center rounded-lg border border-gray-300 bg-white hover:bg-gray-50 px-5 py-2.5 font-medium">
          Cancelar
        </a>
      </div>
    </form>
  </main>

  @livewireScripts
</body>
</html>
