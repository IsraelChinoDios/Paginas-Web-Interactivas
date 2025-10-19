<x-layouts.app :title="__('Salas')">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
                {{ __('Salas') }}
            </h1>

            <flux:modal.trigger name="create-sala">
                <button
                    type="button"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'create-sala')"
                >
                    {{ __('Nueva sala') }}
                </button>
            </flux:modal.trigger>
        </div>

        <flux:modal name="create-sala" :show="$errors->createSala->isNotEmpty()" focusable class="max-w-2xl">
            <form method="POST" action="{{ route('salas.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="context" value="create-sala">

                @include('salas._form', ['sucursales' => $sucursales, 'errorBag' => 'createSala'])

                <div class="flex justify-end gap-2">
                    <flux:modal.close>
                        <flux:button variant="filled">{{ __('Cancelar') }}</flux:button>
                    </flux:modal.close>

                    <flux:button type="submit" variant="filled">{{ __('Crear sala') }}</flux:button>
                </div>
            </form>
        </flux:modal>

        @if (session('status'))
            <div class="rounded-md border border-green-200 bg-green-50 p-4 text-sm text-green-800 dark:border-green-700 dark:bg-green-900/30 dark:text-green-200">
                {{ session('status') }}
            </div>
        @endif

        <form method="GET" action="{{ route('salas.index') }}" class="flex flex-col gap-3 rounded-md border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900 sm:flex-row sm:items-center sm:justify-between">
            <label for="search" class="sr-only">{{ __('Buscar') }}</label>
            <input
                type="text"
                id="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="{{ __('Buscar por nombre, capacidad o sucursal') }}"
                class="w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 sm:max-w-xs"
            >
            @if (request('search'))
                <a
                    href="{{ route('salas.index') }}"
                    class="inline-flex items-center justify-center rounded-md border border-neutral-300 px-3 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:hover:bg-neutral-700"
                >
                    {{ __('Limpiar') }}
                </a>
            @endif
            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-md bg-neutral-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                {{ __('Buscar') }}
            </button>
        </form>

        <div class="overflow-hidden rounded-lg border border-neutral-200 shadow-sm dark:border-neutral-700">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-50 dark:bg-neutral-800">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-300">
                            {{ __('Nombre') }}
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-300">
                            {{ __('Capacidad') }}
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-300">
                            {{ __('Sucursal') }}
                        </th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-300">
                            {{ __('Acciones') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-900">
                    @forelse ($salas as $sala)
                        <tr>
                            <td class="px-4 py-3 text-sm text-neutral-900 dark:text-neutral-100">
                                {{ $sala->nombre }}
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-200">
                                {{ $sala->capacidad }}
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-200">
                                {{ $sala->sucursal->nombre ?? __('Sin sucursal') }}
                            </td>
                            <td class="px-4 py-3 text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <flux:modal.trigger name="edit-sala-{{ $sala->id }}">
                                        <button
                                            type="button"
                                            class="inline-flex items-center rounded-md border border-neutral-300 px-3 py-1.5 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:hover:bg-neutral-700"
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'edit-sala-{{ $sala->id }}')"
                                        >
                                            {{ __('Editar') }}
                                        </button>
                                    </flux:modal.trigger>

                                    <flux:modal
                                        name="edit-sala-{{ $sala->id }}"
                                        :show="$errors->updateSala->isNotEmpty() && old('sala_id') == (string) $sala->id"
                                        focusable
                                        class="max-w-2xl"
                                    >
                                        <form method="POST" action="{{ route('salas.update', $sala) }}" class="space-y-6">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="context" value="edit-sala">
                                            <input type="hidden" name="sala_id" value="{{ $sala->id }}">

                                            @include('salas._form', ['sala' => $sala, 'sucursales' => $sucursales, 'errorBag' => 'updateSala'])

                                            <div class="flex justify-end gap-2">
                                                <flux:modal.close>
                                                    <flux:button variant="filled">{{ __('Cancelar') }}</flux:button>
                                                </flux:modal.close>

                                                <flux:button type="submit" variant="filled">{{ __('Actualizar sala') }}</flux:button>
                                            </div>
                                        </form>
                                    </flux:modal>

                                    <form action="{{ route('salas.destroy', $sala) }}" method="POST" class="inline-flex">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="inline-flex items-center rounded-md border border-red-300 px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 dark:border-red-500/50 dark:bg-neutral-800 dark:text-red-400 dark:hover:bg-neutral-700"
                                            onclick="return confirm('{{ __('Eliminar esta sala?') }}')"
                                        >
                                            {{ __('Eliminar') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-300">
                                {{ __('No hay salas registradas todavia.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $salas->links() }}
        </div>
    </div>
</x-layouts.app>
