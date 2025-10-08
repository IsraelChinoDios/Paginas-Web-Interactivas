<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Iniciar sesión</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex items-center justify-center bg-gray-100">
  <div class="w-full max-w-md bg-white rounded-xl shadow p-6 space-y-4">
    <h1 class="text-2xl font-semibold text-center">Iniciar sesión</h1>

    @if ($errors->any())
      <div class="rounded-md bg-red-50 p-3 text-sm text-red-700">
        <ul class="list-disc list-inside">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
      @csrf
      <div>
        <label class="block text-sm font-medium mb-1" for="correo">Correo</label>
        <input id="correo" name="correo" type="text" value="{{ old('correo') }}"
               class="w-full rounded border-gray-300 focus:ring focus:ring-indigo-200" required autofocus>
      </div>

      <div>
        <label class="block text-sm font-medium mb-1" for="password">Contraseña</label>
        <input id="password" name="password" type="password"
               class="w-full rounded border-gray-300 focus:ring focus:ring-indigo-200" required>
      </div>

      <label class="inline-flex items-center gap-2 text-sm">
        <input type="checkbox" name="remember" value="1" class="rounded border-gray-300">
        Recuérdame
      </label>

      <button type="submit"
              class="w-full rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white py-2 font-medium">
        Entrar
      </button>
    </form>
  </div>
</body>
</html>
