<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Nuevo Movimiento — bancoApp</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css','resources/js/app.js'])
  @livewireStyles
</head>
<body class="min-h-screen bg-gray-50 text-gray-800">

  {{-- TOPBAR --}}
  <header class="sticky top-0 z-50 border-b border-gray-200/60 bg-white/75 backdrop-blur">
    <div class="mx-auto max-w-3xl px-4 h-16 flex items-center justify-between">
      <h1 class="text-lg font-semibold leading-none">Nuevo Movimiento</h1>
      <a href="{{ route('cliente.home', ['cuenta' => $cuentaSel->id]) }}" wire:navigate
         class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 px-3 py-1.5 text-sm">
        ← Volver
      </a>
    </div>
  </header>

  <main class="mx-auto max-w-3xl px-4 py-8">
    @if ($errors->any())
      <div class="mb-4 rounded border border-red-200 bg-red-50 text-red-700 px-3 py-2 text-sm">
        <ul class="list-disc list-inside">
          @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('cliente.movimientos.store') }}"
          class="rounded-2xl border border-gray-200 bg-white shadow-sm p-6 space-y-6">
      @csrf
      <input type="hidden" name="cuenta_id" value="{{ $cuentaSel->id }}">

      <div>
        <label class="block text-sm font-medium mb-1" for="tipo_movimiento">Tipo de movimiento</label>
        <select id="tipo_movimiento" name="tipo_movimiento" required
                class="w-full border border-gray-300 bg-white rounded-lg p-2.5 shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
          @foreach ($tipos as $value => $label)
            <option value="{{ $value }}" @selected(old('tipo_movimiento')===$value)>{{ $label }}</option>
          @endforeach
        </select>
        @error('tipo_movimiento') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1" for="monto">Monto</label>
          <input id="monto" name="monto" type="number" step="0.01" min="0.01" value="{{ old('monto') }}" required
                 class="w-full border border-gray-300 bg-white rounded-lg p-2.5 shadow-sm
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
          @error('monto') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
          <label class="block text-sm font-medium mb-1" for="fecha">Fecha (opcional)</label>
          <input id="fecha" name="fecha" type="datetime-local" value="{{ old('fecha') }}"
                 class="w-full border border-gray-300 bg-white rounded-lg p-2.5 shadow-sm
                        focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
          @error('fecha') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      <div class="pt-2 flex items-center gap-2">
        <button class="inline-flex items-center rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 font-medium">
          Guardar
        </button>
        <a href="{{ route('cliente.home', ['cuenta' => $cuentaSel->id]) }}" wire:navigate
           class="inline-flex items-center rounded-lg border border-gray-300 bg-white hover:bg-gray-50 px-5 py-2.5 font-medium">
          Cancelar
        </a>
      </div>
    </form>
  </main>

  @livewireScripts
</body>
</html>
