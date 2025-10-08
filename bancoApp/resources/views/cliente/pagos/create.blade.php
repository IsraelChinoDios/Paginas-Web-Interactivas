<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Nuevo Pago — bancoApp</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css','resources/js/app.js'])
  @livewireStyles
</head>
<body class="min-h-screen bg-gray-50 text-gray-800">

  {{-- TOPBAR --}}
  <header class="sticky top-0 z-50 border-b border-gray-200/60 bg-white/75 backdrop-blur">
    <div class="mx-auto max-w-3xl px-4 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="h-9 w-9 rounded-xl bg-emerald-600/10 grid place-items-center ring-1 ring-emerald-600/20">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" viewBox="0 0 24 24" fill="currentColor">
            <path d="M3 6.75A2.25 2.25 0 0 1 5.25 4.5h13.5A2.25 2.25 0 0 1 21 6.75v10.5A2.25 2.25 0 0 1 18.75 19.5H5.25A2.25 2.25 0 0 1 3 17.25V6.75Z"/>
          </svg>
        </div>
        <div>
          <h1 class="text-lg font-semibold leading-none">Nuevo Pago</h1>
          <p class="text-xs text-gray-500 leading-none mt-1">Cuenta #{{ $cuentaSel->id }} — {{ ucfirst($cuentaSel->tipo) }}</p>
        </div>
      </div>

      <a href="{{ route('cliente.home', ['cuenta' => $cuentaSel->id]) }}" wire:navigate
         class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 px-3 py-1.5 text-sm">
        ← Volver
      </a>
    </div>
  </header>

  <main class="mx-auto max-w-3xl px-4 py-8 space-y-6">

    {{-- Alertas de validación --}}
    @if ($errors->any())
      <div class="rounded-lg border border-red-200 bg-red-50 text-red-800 px-4 py-3 text-sm">
        <ul class="list-disc list-inside">
          @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif

    {{-- Resumen rápido de la cuenta --}}
    <section class="rounded-2xl border border-gray-200 bg-white p-4">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs text-gray-500">Cuenta</p>
          <p class="font-medium">#{{ $cuentaSel->id }} — {{ ucfirst($cuentaSel->tipo) }}</p>
        </div>
        <div class="text-right">
          <p class="text-xs text-gray-500">Saldo</p>
          <p class="text-xl font-semibold tracking-tight">${{ number_format($cuentaSel->saldo, 2) }}</p>
        </div>
      </div>
    </section>

    {{-- Formulario --}}
    <form method="POST" action="{{ route('cliente.pagos.store') }}"
          class="rounded-2xl border border-gray-200 bg-white shadow-sm p-6 space-y-5">
      @csrf
      <input type="hidden" name="cuenta_id" value="{{ $cuentaSel->id }}">

      <div>
        <label class="block text-sm font-medium mb-1" for="referencia">Referencia</label>
        <input id="referencia" name="referencia" value="{{ old('referencia') }}" required
               class="w-full border border-gray-300 rounded-lg p-2.5 bg-white shadow-sm
                      focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        @error('referencia') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <div>
        <label class="block text-sm font-medium mb-1" for="tipo_servicio">Servicio</label>
        <input id="tipo_servicio" name="tipo_servicio" value="{{ old('tipo_servicio') }}" required
               class="w-full border border-gray-300 rounded-lg p-2.5 bg-white shadow-sm uppercase tracking-wide
                      focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        @error('tipo_servicio') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1" for="monto">Monto</label>
          <input id="monto" name="monto" type="number" step="0.01" min="0.01" value="{{ old('monto') }}" required
                 class="w-full border border-gray-300 rounded-lg p-2.5 bg-white shadow-sm
                        focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
          @error('monto') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
        <div>
          <label class="block text-sm font-medium mb-1" for="metodo_pago">Método</label>
          <select id="metodo_pago" name="metodo_pago" required
                  class="w-full border border-gray-300 rounded-lg p-2.5 bg-white shadow-sm
                         focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
            <option value="efectivo" @selected(old('metodo_pago')==='efectivo')>Efectivo</option>
            <option value="tarjeta"  @selected(old('metodo_pago')==='tarjeta')>Tarjeta</option>
          </select>
          @error('metodo_pago') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1" for="fecha">Fecha (opcional)</label>
        <input id="fecha" name="fecha" type="datetime-local" value="{{ old('fecha') }}"
               class="w-full border border-gray-300 rounded-lg p-2.5 bg-white shadow-sm
                      focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
        @error('fecha') <p class="text-red-600 text-sm mt-1">{{ $message }}</p> @enderror
      </div>

      <div class="pt-2 flex items-center gap-2">
        <button class="inline-flex items-center rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 font-medium">
          Registrar pago
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
