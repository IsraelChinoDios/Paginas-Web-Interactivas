<x-layouts.app :title="__('Peliculas')">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
                {{ __('Peliculas') }}
            </h1>

            <flux:modal.trigger name="create-pelicula">
                <button
                    type="button"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'create-pelicula')"
                >
                    {{ __('Nueva pelicula') }}
                </button>
            </flux:modal.trigger>
        </div>

        <flux:modal name="create-pelicula" :show="$errors->createPelicula->isNotEmpty()" focusable class="max-w-2xl">
            <form method="POST" action="{{ route('peliculas.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="context" value="create-pelicula">

                @include('peliculas._form', ['errorBag' => 'createPelicula'])

                <div class="flex justify-end gap-2">
                    <flux:modal.close>
                        <flux:button variant="filled">{{ __('Cancelar') }}</flux:button>
                    </flux:modal.close>

                    <flux:button type="submit" variant="filled">{{ __('Crear pelicula') }}</flux:button>
                </div>
            </form>
        </flux:modal>

        @if (session('status'))
            <div class="rounded-md border border-green-200 bg-green-50 p-4 text-sm text-green-800 dark:border-green-700 dark:bg-green-900/30 dark:text-green-200">
                {{ session('status') }}
            </div>
        @endif

        <form method="GET" action="{{ route('peliculas.index') }}" class="flex flex-col gap-3 rounded-md border border-neutral-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900 sm:flex-row sm:items-center sm:justify-between">
            <label for="search" class="sr-only">{{ __('Buscar') }}</label>
            <input
                type="text"
                id="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="{{ __('Buscar por nombre, director o genero') }}"
                class="w-full rounded-md border-neutral-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 sm:max-w-xs"
            >
            @if (request('search'))
                <a
                    href="{{ route('peliculas.index') }}"
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
                            {{ __('Director') }}
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-300">
                            {{ __('Duracion') }}
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-300">
                            {{ __('Genero') }}
                        </th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-300">
                            {{ __('Acciones') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 bg-white dark:divide-neutral-700 dark:bg-neutral-900">
                    @forelse ($peliculas as $pelicula)
                        <tr>
                            <td class="px-4 py-3 text-sm text-neutral-900 dark:text-neutral-100">
                                {{ $pelicula->nombre }}
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-200">
                                {{ $pelicula->director }}
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-200">
                                {{ $pelicula->duracion }}
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-600 dark:text-neutral-200">
                                {{ $pelicula->genero }}
                            </td>
                            <td class="px-4 py-3 text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <flux:modal.trigger name="edit-pelicula-{{ $pelicula->id }}">
                                        <button
                                            type="button"
                                            class="inline-flex items-center rounded-md border border-neutral-300 px-3 py-1.5 text-sm font-medium text-neutral-700 hover:bg-neutral-50 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100 dark:hover:bg-neutral-700"
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'edit-pelicula-{{ $pelicula->id }}')"
                                        >
                                            {{ __('Editar') }}
                                        </button>
                                    </flux:modal.trigger>

                                    <flux:modal
                                        name="edit-pelicula-{{ $pelicula->id }}"
                                        :show="$errors->updatePelicula->isNotEmpty() && old('pelicula_id') == (string) $pelicula->id"
                                        focusable
                                        class="max-w-2xl"
                                    >
                                        <form method="POST" action="{{ route('peliculas.update', $pelicula) }}" class="space-y-6">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="context" value="edit-pelicula">
                                            <input type="hidden" name="pelicula_id" value="{{ $pelicula->id }}">

                                            @include('peliculas._form', ['pelicula' => $pelicula, 'errorBag' => 'updatePelicula'])

                                            <div class="flex justify-end gap-2">
                                                <flux:modal.close>
                                                    <flux:button variant="filled">{{ __('Cancelar') }}</flux:button>
                                                </flux:modal.close>

                                                <flux:button type="submit" variant="filled">{{ __('Actualizar pelicula') }}</flux:button>
                                            </div>
                                        </form>
                                    </flux:modal>

                                    <form action="{{ route('peliculas.destroy', $pelicula) }}" method="POST" class="inline-flex">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="inline-flex items-center rounded-md border border-red-300 px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 dark:border-red-500/50 dark:bg-neutral-800 dark:text-red-400 dark:hover:bg-neutral-700"
                                            onclick="return confirm('{{ __('Eliminar esta pelicula?') }}')"
                                        >
                                            {{ __('Eliminar') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-300">
                                {{ __('No hay peliculas registradas todavia.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $peliculas->links() }}
        </div>
    </div>
</x-layouts.app>
