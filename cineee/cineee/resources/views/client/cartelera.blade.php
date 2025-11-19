<x-layouts.app :title="__('Cartelera')">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <h1 class="text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
                {{ __('Cartelera') }}
            </h1>

            <form method="GET" action="{{ route('cliente.cartelera') }}" class="flex gap-2">
                <label for="search" class="sr-only">{{ __('Buscar') }}</label>
                <input
                    id="search"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="{{ __('Buscar película, sala o sucursal') }}"
                    class="rounded-md border-neutral-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
                >
                <button
                    type="submit"
                    class="rounded-md bg-neutral-900 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-neutral-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    {{ __('Buscar') }}
                </button>
            </form>
        </div>

        @if (session('status'))
            <div class="rounded-md border border-green-200 bg-green-50 p-4 text-sm text-green-800 dark:border-green-700 dark:bg-green-900/30 dark:text-green-200">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-700 dark:bg-red-900/30 dark:text-red-200">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="overflow-hidden rounded-lg border border-neutral-200 shadow-sm dark:border-neutral-700">
            <table class="min-w-full divide-y divide-neutral-200 dark:divide-neutral-700">
                <thead class="bg-neutral-50 dark:bg-neutral-800">
                    <tr>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-300">
                            {{ __('Película') }}
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-300">
                            {{ __('Sucursal / Sala') }}
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-300">
                            {{ __('Fecha') }}
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-300">
                            {{ __('Tipo') }}
                        </th>
                        <th scope="col" class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-300">
                            {{ __('Costo') }}
                        </th>
                        <th scope="col" class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-neutral-500 dark:text-neutral-300">
                            {{ __('Comprar') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700 bg-white dark:bg-neutral-900">
                    @forelse ($funciones as $funcion)
                        <tr>
                            <td class="px-4 py-3 text-sm text-neutral-900 dark:text-neutral-100">
                                {{ $funcion->pelicula?->nombre }}
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-200">
                                {{ $funcion->sala?->nombre }} — {{ $funcion->sala?->sucursal?->nombre }}
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-200">
                                {{ $funcion->fecha?->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-200">
                                {{ $funcion->tipo }}
                            </td>
                            <td class="px-4 py-3 text-sm text-neutral-700 dark:text-neutral-200">
                                ${{ number_format($funcion->costo, 2) }}
                            </td>
                            <td class="px-4 py-3 text-right text-sm text-neutral-700 dark:text-neutral-200">
                                <form method="POST" action="{{ route('cliente.cartelera.comprar') }}" class="flex items-center justify-end gap-2">
                                    @csrf
                                    <input type="hidden" name="funcion_id" value="{{ $funcion->id }}">
                                    <label class="sr-only" for="cantidad-{{ $funcion->id }}">{{ __('Cantidad') }}</label>
                                    <input
                                        id="cantidad-{{ $funcion->id }}"
                                        type="number"
                                        name="cantidad"
                                        value="1"
                                        min="1"
                                        max="10"
                                        class="w-20 rounded-md border-neutral-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
                                    >
                                    <button
                                        type="submit"
                                        class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                    >
                                        {{ __('Comprar') }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-sm text-neutral-500 dark:text-neutral-300">
                                {{ __('No hay funciones disponibles.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $funciones->links() }}
        </div>
    </div>
</x-layouts.app>
