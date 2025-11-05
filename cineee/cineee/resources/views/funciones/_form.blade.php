@php
    $isEdit = isset($funcion);
    $errorBagName = $errorBag ?? 'default';
    $oldContext = old('context');
    $oldFuncionId = old('funcion_id');
    $shouldUseOld = false;

    if ($oldContext === 'create-funcion' && ! $isEdit) {
        $shouldUseOld = true;
    } elseif ($oldContext === 'edit-funcion' && $isEdit && isset($funcion) && (int) $oldFuncionId === $funcion->id) {
        $shouldUseOld = true;
    }

    $fechaValue = $shouldUseOld
        ? old('fecha', '')
        : (isset($funcion) && $funcion->fecha ? $funcion->fecha->format('Y-m-d\TH:i') : '');

    $peliculaIdValue = $shouldUseOld ? old('pelicula_id', '') : ($funcion->pelicula_id ?? '');
    $salaIdValue = $shouldUseOld ? old('sala_id', '') : ($funcion->sala_id ?? '');
    $tipoValue = $shouldUseOld ? old('tipo', '') : ($funcion->tipo ?? '');
    $costoValue = $shouldUseOld ? old('costo', '') : ($funcion->costo ?? '');
@endphp

<div class="grid grid-cols-1 gap-6">
    <div>
        <label for="fecha" class="block text-left text-sm font-medium text-neutral-700 dark:text-neutral-300">
            {{ __('Fecha y hora') }}
        </label>
        <input
            type="datetime-local"
            id="fecha"
            name="fecha"
            value="{{ $fechaValue }}"
            class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
            required
        >
        @error('fecha', $errorBagName)
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="pelicula_id" class="block text-left text-sm font-medium text-neutral-700 dark:text-neutral-300">
            {{ __('Pelicula') }}
        </label>
        <select
            id="pelicula_id"
            name="pelicula_id"
            class="mt-1 block w-full rounded-md border-neutral-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
            required
        >
            <option value="">{{ __('Seleccione una pelicula') }}</option>
            @foreach ($peliculas as $peliculaOption)
                <option value="{{ $peliculaOption->id }}" @selected((string) $peliculaIdValue === (string) $peliculaOption->id)>
                    {{ $peliculaOption->nombre }}
                </option>
            @endforeach
        </select>
        @error('pelicula_id', $errorBagName)
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="sala_id" class="block text-left text-sm font-medium text-neutral-700 dark:text-neutral-300">
            {{ __('Sala') }}
        </label>
        <select
            id="sala_id"
            name="sala_id"
            class="mt-1 block w-full rounded-md border-neutral-300 bg-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
            required
        >
            <option value="">{{ __('Seleccione una sala') }}</option>
            @foreach ($salas as $salaOption)
                <option value="{{ $salaOption->id }}" @selected((string) $salaIdValue === (string) $salaOption->id)>
                    {{ $salaOption->nombre }}
                    @if ($salaOption->sucursal)
                        ({{ $salaOption->sucursal->nombre }})
                    @endif
                </option>
            @endforeach
        </select>
        @error('sala_id', $errorBagName)
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="tipo" class="block text-left text-sm font-medium text-neutral-700 dark:text-neutral-300">
            {{ __('Tipo de funcion') }}
        </label>
        <input
            type="text"
            id="tipo"
            name="tipo"
            value="{{ $tipoValue }}"
            placeholder="2D, 3D, IMAX"
            class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
            required
        >
        @error('tipo', $errorBagName)
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="costo" class="block text-left text-sm font-medium text-neutral-700 dark:text-neutral-300">
            {{ __('Costo') }}
        </label>
        <input
            type="number"
            id="costo"
            name="costo"
            value="{{ $costoValue }}"
            min="0"
            step="0.01"
            class="mt-1 block w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
            required
        >
        @error('costo', $errorBagName)
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>
</div>
