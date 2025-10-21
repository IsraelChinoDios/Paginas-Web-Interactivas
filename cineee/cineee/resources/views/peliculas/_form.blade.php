@php
    $isEdit = isset($pelicula);
    $errorBagName = $errorBag ?? 'default';
    $oldContext = old('context');
    $oldPeliculaId = old('pelicula_id');
    $shouldUseOld = false;

    if ($oldContext === 'create-pelicula' && ! $isEdit) {
        $shouldUseOld = true;
    } elseif ($oldContext === 'edit-pelicula' && $isEdit && isset($pelicula) && (int) $oldPeliculaId === $pelicula->id) {
        $shouldUseOld = true;
    }

    $nombreValue = $shouldUseOld ? old('nombre', '') : ($pelicula->nombre ?? '');
    $directorValue = $shouldUseOld ? old('director', '') : ($pelicula->director ?? '');
    $duracionValue = $shouldUseOld ? old('duracion', '') : ($pelicula->duracion ?? '');
    $generoValue = $shouldUseOld ? old('genero', '') : ($pelicula->genero ?? '');
@endphp

<div class="grid grid-cols-1 gap-6">
    <div>
        <label for="nombre" class="block text-left text-sm font-medium text-neutral-700 dark:text-neutral-300">
            {{ __('Nombre') }}
        </label>
        <input
            type="text"
            id="nombre"
            name="nombre"
            value="{{ $nombreValue }}"
            class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
            required
        >
        @error('nombre', $errorBagName)
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="director" class="block text-left text-sm font-medium text-neutral-700 dark:text-neutral-300">
            {{ __('Director') }}
        </label>
        <input
            type="text"
            id="director"
            name="director"
            value="{{ $directorValue }}"
            class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
            required
        >
        @error('director', $errorBagName)
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="duracion" class="block text-left text-sm font-medium text-neutral-700 dark:text-neutral-300">
            {{ __('Duracion') }}
        </label>
        <input
            type="text"
            id="duracion"
            name="duracion"
            value="{{ $duracionValue }}"
            class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
            required
        >
        @error('duracion', $errorBagName)
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="genero" class="block text-left text-sm font-medium text-neutral-700 dark:text-neutral-300">
            {{ __('Genero') }}
        </label>
        <input
            type="text"
            id="genero"
            name="genero"
            value="{{ $generoValue }}"
            class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
            required
        >
        @error('genero', $errorBagName)
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
