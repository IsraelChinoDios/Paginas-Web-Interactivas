@php
    $isEdit = isset($sucursal);
    $errorBagName = $errorBag ?? 'default';
    $oldContext = old('context');
    $oldSucursalId = old('sucursal_id');
    $shouldUseOld = false;

    if ($oldContext === 'create-sucursal' && ! $isEdit) {
        $shouldUseOld = true;
    } elseif ($oldContext === 'edit-sucursal' && $isEdit && isset($sucursal) && (int) $oldSucursalId === $sucursal->id) {
        $shouldUseOld = true;
    }

    $nombreValue = $shouldUseOld ? old('nombre', '') : ($sucursal->nombre ?? '');
    $direccionValue = $shouldUseOld ? old('direccion', '') : ($sucursal->direccion ?? '');
    $directorValue = $shouldUseOld ? old('director', '') : ($sucursal->director ?? '');
    $telefonoValue = $shouldUseOld ? old('telefono', '') : ($sucursal->telefono ?? '');
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
        <label for="direccion" class="block text-left text-sm font-medium text-neutral-700 dark:text-neutral-300">
            {{ __('Direccion') }}
        </label>
        <input
            type="text"
            id="direccion"
            name="direccion"
            value="{{ $direccionValue }}"
            class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
            required
        >
        @error('direccion', $errorBagName)
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
        <label for="telefono" class="block text-left text-sm font-medium text-neutral-700 dark:text-neutral-300">
            {{ __('Telefono') }}
        </label>
        <input
            type="text"
            id="telefono"
            name="telefono"
            value="{{ $telefonoValue }}"
            class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
            required
        >
        @error('telefono', $errorBagName)
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
