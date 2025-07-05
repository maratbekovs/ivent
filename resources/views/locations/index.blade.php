<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight"> {{-- Использование neutral-800 --}}
            {{ __('Inventory Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg"> {{-- Убрали dark:bg-gray-800 --}}
                <div class="p-6 text-neutral-900"> {{-- Использование neutral-900 --}}
                    <p>{{ __('This is the inventory management page.') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
