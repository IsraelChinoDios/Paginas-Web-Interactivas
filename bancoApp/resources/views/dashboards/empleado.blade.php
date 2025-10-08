<!doctype html>
<html lang="es" class="h-full scroll-smooth">
<head>
  <meta charset="utf-8">
  <title>Panel Empleado — bancoApp</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>
<body class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-950 text-gray-800 dark:text-gray-100">

  @php
    // Prefijo dinámico para reutilizar la vista (admin/empleado)
    $area = auth()->user()?->rol === 'administrador' ? 'admin' : 'empleado';
  @endphp

  <!-- Topbar -->
  <header class="sticky top-0 z-50 border-b border-gray-200/60 dark:border-white/10 bg-white/75 dark:bg-gray-900/75 backdrop-blur">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="h-9 w-9 rounded-xl bg-emerald-600/10 dark:bg-emerald-500/15 grid place-items-center ring-1 ring-emerald-600/20">
          <!-- heroicon: briefcase -->
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24" fill="currentColor">
            <path d="M9 3.75A2.25 2.25 0 0 0 6.75 6v.75H5.25A2.25 2.25 0 0 0 3 9v8.25A2.25 2.25 0 0 0 5.25 19.5h13.5A2.25 2.25 0 0 0 21 17.25V9a2.25 2.25 0 0 0-2.25-2.25h-1.5V6A2.25 2.25 0 0 0 15 3.75H9Zm.75 3V6A.75.75 0 0 1 10.5 5.25h3A.75.75 0 0 1 14.25 6v.75h-4.5Z"/>
          </svg>
        </div>
        <div>
          <h1 class="text-lg font-semibold leading-none">Panel Empleado</h1>
          <p class="text-xs text-gray-500 dark:text-gray-300 leading-none mt-1">Altas de clientes y cuentas</p>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <div class="hidden sm:block text-sm text-gray-600 dark:text-gray-300">
          @auth
            Hola, <span class="font-medium">{{ auth()->user()->nombre ?? 'Empleado' }}</span>
          @endauth
        </div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300/70 dark:border-white/10 px-3 py-1.5 text-sm font-medium hover:bg-gray-50 dark:hover:bg-white/5 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 opacity-75" viewBox="0 0 24 24" fill="currentColor">
              <path fill-rule="evenodd" d="M3.75 5.25A2.25 2.25 0 0 1 6 3h6a2.25 2.25 0 0 1 2.25 2.25V6a.75.75 0 0 1-1.5 0v-.75A.75.75 0 0 0 12 4.5H6a.75.75 0 0 0-.75.75v13.5A.75.75 0 0 0 6 19.5h6a.75.75 0 0 0 .75-.75V18a.75.75 0 0 1 1.5 0v.75A2.25 2.25 0 0 1 12 21H6a2.25 2.25 0 0 1-2.25-2.25V5.25Zm14.03 2.47a.75.75 0 0 1 1.06 0l3 3a.75.75 0 0 1 0 1.06l-3 3a.75.75 0 1 1-1.06-1.06l1.72-1.72H10.5a.75.75 0 0 1 0-1.5h7l-1.72-1.72a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/>
            </svg>
            Salir
          </button>
        </form>
      </div>
    </div>
  </header>

  <!-- Main -->
  <main class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-8 space-y-8">

    @php
      $totalClientes  = \App\Models\Usuario::where('rol','cliente')->count();
      $cuentasActivas = \App\Models\Cuenta::count();
    @endphp

    <!-- Stats -->
    <section class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <div class="rounded-2xl border border-gray-200/70 dark:border-white/10 bg-white/70 dark:bg-gray-900/60 p-4">
        <p class="text-xs text-gray-500 dark:text-gray-400">Clientes</p>
        <p class="mt-1 text-2xl font-semibold tracking-tight">{{ number_format($totalClientes) }}</p>
      </div>
      <div class="rounded-2xl border border-gray-200/70 dark:border-white/10 bg-white/70 dark:bg-gray-900/60 p-4">
        <p class="text-xs text-gray-500 dark:text-gray-400">Cuentas activas</p>
        <p class="mt-1 text-2xl font-semibold tracking-tight">{{ number_format($cuentasActivas) }}</p>
      </div>
    </section>

    <!-- Acciones permitidas -->
    <section>
      <h2 class="sr-only">Acciones rápidas</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <!-- Alta Cliente -->
        <a href="{{ route($area.'.crudclientes.index') }}"
           wire:navigate
           class="group relative overflow-hidden rounded-2xl border border-emerald-200/60 dark:border-emerald-400/25 bg-gradient-to-br from-emerald-50 to-white dark:from-emerald-950/40 dark:to-gray-900 p-6 transition shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-emerald-500">
          <div class="absolute -top-10 -right-10 h-40 w-40 rounded-full bg-emerald-500/10 blur-2xl"></div>
          <div class="flex items-start gap-4 relative">
            <div class="h-12 w-12 rounded-xl bg-emerald-600 grid place-items-center text-white shadow-sm ring-1 ring-white/10"></div>
            <div>
              <h3 class="text-lg font-semibold">Alta de Cliente</h3>
            </div>
          </div>
          <div class="mt-4 flex items-center gap-2 text-emerald-700 dark:text-emerald-300 font-medium">
            Ir ahora >
          </div>
        </a>

        <!-- Alta Cuenta -->
        <a href="{{ route($area.'.cuentas.index') }}"
           wire:navigate
           class="group relative overflow-hidden rounded-2xl border border-red-200/60 dark:border-red-400/25 bg-gradient-to-br from-red-50 to-white dark:from-red-950/40 dark:to-gray-900 p-6 transition shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-red-500">
          <div class="absolute -top-10 -right-10 h-40 w-40 rounded-full bg-red-500/10 blur-2xl"></div>
          <div class="flex items-start gap-4 relative">
            <div class="h-12 w-12 rounded-xl bg-red-600 grid place-items-center text-white shadow-sm ring-1 ring-white/10"></div>
            <div>
              <h3 class="text-lg font-semibold">Crear Cuenta</h3>
            </div>
          </div>
          <div class="mt-4 flex items-center gap-2 text-red-700 dark:text-red-300 font-medium">
            Ir ahora >
          </div>
        </a>
      </div>
    </section>

    <!-- Accesos rápidos -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <a href="{{ route($area.'.crudclientes.create') }}" wire:navigate
         class="rounded-xl border border-gray-200/70 dark:border-white/10 bg-white/70 dark:bg-gray-900/60 p-4 hover:bg-white dark:hover:bg-gray-900 transition">
        <p class="text-sm font-medium">+ Alta rápida de cliente</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Incorpora nuevos clientes</p>
      </a>
      <a href="{{ route($area.'.cuentas.create') }}" wire:navigate
         class="rounded-xl border border-gray-200/70 dark:border-white/10 bg-white/70 dark:bg-gray-900/60 p-4 hover:bg-white dark:hover:bg-gray-900 transition">
        <p class="text-sm font-medium">+ Crear cuenta</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Nómina o crédito</p>
      </a>
    </section>

  </main>

  @livewireScripts
</body>
</html>
