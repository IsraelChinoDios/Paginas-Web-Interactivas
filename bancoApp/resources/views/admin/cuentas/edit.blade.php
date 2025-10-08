<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Editar Cuenta #{{ $cuenta->id }} — bancoApp</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css','resources/js/app.js'])
  @livewireStyles
</head>
<body class="min-h-screen bg-gray-50 text-gray-800">

  @php
    // Prefijo de rutas según el rol actual
    $area = auth()->user()?->rol === 'administrador' ? 'admin' : 'empleado';
  @endphp

  {{-- TOPBAR --}}
  <header class="sticky top-0 z-50 border-b border-gray-200/60 bg-white/75 backdrop-blur">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="h-9 w-9 rounded-xl bg-teal-600/10 grid place-items-center ring-1 ring-teal-600/20">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600" viewBox="0 0 24 24" fill="currentColor">
            <path d="M3 6.75A2.25 2.25 0 0 1 5.25 4.5h13.5A2.25 2.25 0 0 1 21 6.75v10.5A2.25 2.25 0 0 1 18.75 19.5H5.25A2.25 2.25 0 0 1 3 17.25V6.75Z"/>
          </svg>
        </div>
        <div>
          <h1 class="text-lg font-semibold leading-none">Editar Cuenta #{{ $cuenta->id }}</h1>
          <p class="text-xs text-gray-500 leading-none mt-1">Actualiza los datos de la cuenta</p>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <a href="{{ route($area.'.cuentas.index') }}" wire:navigate
           class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 px-3 py-1.5 text-sm">
          ← Volver a cuentas
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
          @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route($area.'.cuentas.update', $cuenta) }}"
          class="rounded-2xl border border-gray-200 bg-white shadow-sm p-6 space-y-5">
      @csrf @method('PUT')

      <div>
        <label class="block text-sm font-medium mb-1" for="cliente_id">Cliente</label>
        <select id="cliente_id" name="cliente_id" required
                class="w-full border border-gray-300 bg-white rounded-lg p-2.5 shadow-sm
                       focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
          @foreach ($clientes as $cli)
            <option value="{{ $cli->id }}"
              @selected(old('cliente_id',$cuenta->usuario_id) == $cli->id)>
              {{ $cli->nombre }} — {{ $cli->correo }}
            </option>
          @endforeach
        </select>
        @error('cliente_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
      </div>

      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium mb-1" for="tipo">Tipo de cuenta</label>
          <select id="tipo" name="tipo" required
                  class="w-full border border-gray-300 bg-white rounded-lg p-2.5 shadow-sm
                         focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
            <option value="nomina"  @selected(old('tipo',$cuenta->tipo)==='nomina')>Nómina</option>
            <option value="credito" @selected(old('tipo',$cuenta->tipo)==='credito')>Crédito</option>
          </select>
          @error('tipo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium mb-1" for="saldo">Saldo</label>
          <input id="saldo" name="saldo" type="number" step="0.01" min="0" value="{{ old('saldo', $cuenta->saldo) }}" required
                 class="w-full border border-gray-300 bg-white rounded-lg p-2.5 shadow-sm
                        focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
          @error('saldo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
      </div>

      <div class="pt-2 flex items-center gap-2">
        <button class="inline-flex items-center rounded-lg bg-teal-600 hover:bg-teal-700 text-white px-5 py-2.5 font-medium">
          Actualizar
        </button>
        <a href="{{ route($area.'.cuentas.index') }}" wire:navigate
           class="inline-flex items-center rounded-lg border border-gray-300 bg-white hover:bg-gray-50 px-5 py-2.5 font-medium">
          Cancelar
        </a>
      </div>
    </form>
  </main>

  @livewireScripts
</body>
</html>
