<x-layouts.app :title="__('Dulcería')">
    <div class="flex flex-col gap-6">
        <h1 class="text-2xl font-semibold text-neutral-900 dark:text-neutral-100">
            {{ __('Dulcería') }}
        </h1>

        @if (session('status'))
            <div class="rounded-md border border-green-200 bg-green-50 p-4 text-sm text-green-800 dark:border-green-700 dark:bg-green-900/30 dark:text-green-200">
                {{ session('status') }}
            </div>
        @endif

        @error('items')
            <div class="rounded-md border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-700 dark:bg-red-900/30 dark:text-red-200">
                {{ $message }}
            </div>
        @enderror

        <form method="POST" action="{{ route('cliente.dulceria.comprar') }}" class="grid gap-4 sm:grid-cols-2">
            @csrf
            @foreach ($productos as $producto)
                <div class="rounded-lg border border-neutral-200 p-4 shadow-sm dark:border-neutral-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-base font-semibold text-neutral-900 dark:text-neutral-100">{{ $producto['nombre'] }}</p>
                            <p class="text-sm text-neutral-600 dark:text-neutral-300">
                                ${{ number_format($producto['precio'], 2) }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="sr-only" for="producto-{{ $producto['id'] }}">{{ __('Cantidad') }}</label>
                            <input
                                id="producto-{{ $producto['id'] }}"
                                type="number"
                                name="items[{{ $loop->index }}][cantidad]"
                                value="0"
                                min="0"
                                max="20"
                                class="w-20 rounded-md border-neutral-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-neutral-600 dark:bg-neutral-800 dark:text-neutral-100"
                            >
                            <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $producto['id'] }}">
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="sm:col-span-2 flex justify-end">
                <button
                    type="submit"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    {{ __('Comprar') }}
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
