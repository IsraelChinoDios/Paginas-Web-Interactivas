<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>{{ $title ?? 'bancoApp' }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @livewireStyles
</head>
<body class="min-h-screen bg-gray-50 text-gray-900">
  <main class="max-w-5xl mx-auto p-6">
    {{ $slot }}
  </main>

  @livewireScripts
</body>
</html>
