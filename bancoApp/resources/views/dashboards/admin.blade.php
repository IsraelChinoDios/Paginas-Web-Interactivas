<!doctype html>
<html lang="es" class="h-full scroll-smooth">
<head>
  <meta charset="utf-8">
  <title>Panel Administrador — bancoApp</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>
<body class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-950 text-gray-800 dark:text-gray-100">

  <!-- Topbar -->
  <header class="sticky top-0 z-50 border-b border-gray-200/60 dark:border-white/10 bg-white/75 dark:bg-gray-900/75 backdrop-blur">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="h-9 w-9 rounded-xl bg-indigo-600/10 dark:bg-indigo-500/15 grid place-items-center ring-1 ring-indigo-600/20">
          <!-- heroicon: shield-check -->
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600 dark:text-indigo-400" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M12 3.75a.75.75 0 0 1 .374.098l6.75 3.75a.75.75 0 0 1 .376.65V12c0 4.97-3.37 8.96-7.5 10.125C7.87 20.96 4.5 16.97 4.5 12V8.248a.75.75 0 0 1 .376-.65l6.75-3.75A.75.75 0 0 1 12 3.75Zm3.53 6.47a.75.75 0 0 0-1.06-1.06L11 12.63l-1.47-1.47a.75.75 0 1 0-1.06 1.06l2 2a.75.75 0 0 0 1.06 0l4-4Z" clip-rule="evenodd"/>
          </svg>
        </div>
        <div>
          <h1 class="text-lg font-semibold leading-none">Panel Administrador</h1>
        </div>
      </div>

      <div class="flex items-center gap-3">
        <div class="hidden sm:block text-sm text-gray-600 dark:text-gray-300">
          @auth
            Hola, <span class="font-medium">{{ auth()->user()->nombre ?? 'Admin' }}</span>
          @endauth
        </div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300/70 dark:border-white/10 px-3 py-1.5 text-sm font-medium hover:bg-gray-50 dark:hover:bg-white/5 transition">
            <!-- heroicon: arrow-right-on-rectangle -->
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
    $totalUsuarios = \App\Models\Usuario::count();
    $totalEmpleados = \App\Models\Usuario::where('rol','empleado')->count();
    $totalClientes  = \App\Models\Usuario::where('rol','cliente')->count();
    $cuentasActivas = \App\Models\Cuenta::count();
  @endphp

    {{-- <livewire:admin.quick-stats wire:navigate /> --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
      <div class="rounded-2xl border border-gray-200/70 dark:border-white/10 bg-white/70 dark:bg-gray-900/60 p-4">
        <p class="text-xs text-gray-500 dark:text-gray-400">Usuarios totales</p>
        <p class="mt-1 text-2xl font-semibold tracking-tight">{{ number_format($totalUsuarios) }}</p>
      </div>

      <div class="rounded-2xl border border-gray-200/70 dark:border-white/10 bg-white/70 dark:bg-gray-900/60 p-4">
        <p class="text-xs text-gray-500 dark:text-gray-400">Empleados</p>
        <p class="mt-1 text-2xl font-semibold tracking-tight">{{ number_format($totalEmpleados) }}</p>
      </div>

      <div class="rounded-2xl border border-gray-200/70 dark:border-white/10 bg-white/70 dark:bg-gray-900/60 p-4">
        <p class="text-xs text-gray-500 dark:text-gray-400">Clientes</p>
        <p class="mt-1 text-2xl font-semibold tracking-tight">{{ number_format($totalClientes) }}</p>
      </div>

      <div class="rounded-2xl border border-gray-200/70 dark:border-white/10 bg-white/70 dark:bg-gray-900/60 p-4">
        <p class="text-xs text-gray-500 dark:text-gray-400">Cuentas activas</p>
        <p class="mt-1 text-2xl font-semibold tracking-tight">{{ number_format($cuentasActivas) }}</p>
      </div>
    </section>

    <!-- Actions -->
    <section>
      <h2 class="sr-only">Acciones rápidas</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <!-- Card Empleados -->
        <a href="{{ route('admin.crudempleados.index') }}"
           wire:navigate
           class="group relative overflow-hidden rounded-2xl border border-indigo-200/60 dark:border-indigo-400/25 bg-gradient-to-br from-indigo-50 to-white dark:from-indigo-950/40 dark:to-gray-900 p-6 transition shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
          <div class="absolute -top-10 -right-10 h-40 w-40 rounded-full bg-indigo-500/10 blur-2xl"></div>
          <div class="flex items-start gap-4 relative">
            <div class="h-12 w-12 rounded-xl bg-indigo-600 grid place-items-center text-white shadow-sm ring-1 ring-white/10">
            </div>
            <div>
              <h3 class="text-lg font-semibold">CRUD Empleados</h3>
            </div>
          </div>
          <div class="mt-4 flex items-center gap-2 text-indigo-700 dark:text-indigo-300 font-medium">
            Ir ahora >
          </div>
        </a>

        <!-- Card Clientes -->
        <a href="{{ route('admin.crudclientes.index') }}"
           wire:navigate
           class="group relative overflow-hidden rounded-2xl border border-emerald-200/60 dark:border-emerald-400/25 bg-gradient-to-br from-emerald-50 to-white dark:from-emerald-950/40 dark:to-gray-900 p-6 transition shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-emerald-500">
          <div class="absolute -top-10 -right-10 h-40 w-40 rounded-full bg-emerald-500/10 blur-2xl"></div>
          <div class="flex items-start gap-4 relative">
            <div class="h-12 w-12 rounded-xl bg-emerald-600 grid place-items-center text-white shadow-sm ring-1 ring-white/10">
            </div>
            <div>
              <h3 class="text-lg font-semibold">CRUD Clientes</h3>
            </div>
          </div>
          <div class="mt-4 flex items-center gap-2 text-emerald-700 dark:text-emerald-300 font-medium">
            Ir ahora >
          </div>
        </a>

        <!-- Card Cuentas -->
        <a href="{{ route('admin.cuentas.index') }}"
          wire:navigate
          class="group relative overflow-hidden rounded-2xl border border-red-200/60 bg-gradient-to-br from-red-50 to-white p-6 transition shadow-sm hover:shadow-md focus:outline-none focus:ring-2 focus:ring-red-500">
          <div class="absolute -top-10 -right-10 h-40 w-40 rounded-full bg-red-500/10 blur-2xl"></div>
          <div class="flex items-start gap-4 relative">
            <div class="h-12 w-12 rounded-xl bg-red-600 grid place-items-center text-white shadow-sm ring-1 ring-white/10">
            </div>
            <div>
              <h3 class="text-lg font-semibold">Crear cuenta</h3>
            </div>
          </div>
          <div class="mt-4 flex items-center gap-2 text-red-700 font-medium">
            Ir ahora >
          </div>
        </a>

      </div>
    </section>

    <!-- accesos rápidos -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <a href="{{ route('admin.crudempleados.create') }}" wire:navigate
         class="rounded-xl border border-gray-200/70 dark:border-white/10 bg-white/70 dark:bg-gray-900/60 p-4 hover:bg-white dark:hover:bg-gray-900 transition">
        <p class="text-sm font-medium">+ Alta rápida de empleado</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Crea un nuevo registro en segundos</p>
      </a>
      <a href="{{ route('admin.crudclientes.create') }}" wire:navigate
         class="rounded-xl border border-gray-200/70 dark:border-white/10 bg-white/70 dark:bg-gray-900/60 p-4 hover:bg-white dark:hover:bg-gray-900 transition">
        <p class="text-sm font-medium">+ Alta rápida de cliente</p>
        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Incorpora nuevos clientes</p>
      </a>
    </section>

  </main>

  @livewireScripts
</body>
</html>
