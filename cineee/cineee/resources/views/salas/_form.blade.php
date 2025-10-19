@php
    $isEdit = isset($sala);
    $errorBagName = $errorBag ?? 'default';
    $oldContext = old('context');
    $oldSalaId = old('sala_id');
    $shouldUseOld = false;

    if ($oldContext === 'create-sala' && ! $isEdit) {
        $shouldUseOld = true;
    } elseif ($oldContext === 'edit-sala' && $isEdit && isset($sala) && (int) $oldSalaId === $sala->id) {
        $shouldUseOld = true;
    }

    $nombreValue = $shouldUseOld ? old('nombre', '') : ($sala->nombre ?? '');
    $capacidadValue = $shouldUseOld ? old('capacidad', '') : ($sala->capacidad ?? '');
    $sucursalIdValue = $shouldUseOld ? old('sucursal_id', '') : ($sala->sucursal_id ?? '');
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
        <label for="capacidad" class="block text-left text-sm font-medium text-neutral-700 dark:text-neutral-300">
            {{ __('Capacidad') }}
        </label>
        <input
            type="number"
            id="capacidad"
            name="capacidad"
            value="{{ $capacidadValue }}"
            min="1"
            class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
            required
        >
        @error('capacidad', $errorBagName)
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="sucursal_id" class="block text-left text-sm font-medium text-neutral-700 dark:text-neutral-300">
            {{ __('Sucursal') }}
        </label>
        <select
            id="sucursal_id"
            name="sucursal_id"
            class="mt-1 block w-full rounded-md border-neutral-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
            required
        >
            <option value="">{{ __('Seleccione una sucursal') }}</option>
            @foreach ($sucursales as $sucursalOption)
                <option value="{{ $sucursalOption->id }}" @selected((string) $sucursalIdValue === (string) $sucursalOption->id)>
                    {{ $sucursalOption->nombre }}
                </option>
            @endforeach
        </select>
        @error('sucursal_id', $errorBagName)
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
