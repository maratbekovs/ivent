<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div>
                <h2 class="font-semibold text-2xl text-text-primary leading-tight">
                    {{ __('Inventory Report') }}
                </h2>
                <p class="text-sm text-text-secondary mt-1">
                    {{ __('Session') }} #{{ $session->id }} - {{ $session->room->name }}
                </p>
            </div>
            <div>
                <a href="{{ route('global-inventory.create') }}" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark">
                    <i class="fas fa-plus mr-2"></i>
                    {{ __('Start New Session') }}
                </a>
            </div>
        </div>
    </x-slot>

    <!-- Session Summary -->
    <div class="bg-surface p-6 rounded-lg shadow-md mb-6">
        <h3 class="font-semibold text-lg text-text-primary mb-4">{{ __('Session Summary') }}</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
            <div>
                <p class="text-text-secondary">{{ __('Room') }}</p>
                <p class="font-semibold text-text-primary">{{ $session->room->name }}</p>
            </div>
            <div>
                <p class="text-text-secondary">{{ __('Conducted by') }}</p>
                <p class="font-semibold text-text-primary">{{ $session->user->name }}</p>
            </div>
            <div>
                <p class="text-text-secondary">{{ __('Started At') }}</p>
                <p class="font-semibold text-text-primary">{{ $session->started_at?->format('Y-m-d H:i') }}</p>
            </div>
            <div>
                <p class="text-text-secondary">{{ __('Completed At') }}</p>
                {{-- ИЗМЕНЕНИЕ: Добавлена проверка на null с помощью оператора `?->` --}}
                <p class="font-semibold text-text-primary">{{ $session->completed_at?->format('Y-m-d H:i') ?? __('In Progress') }}</p>
            </div>
        </div>
    </div>

    @php
        $foundItems = $session->items->where('status', 'found');
        $missingItems = $session->items->where('status', 'missing');
        $extraItems = $session->items->where('status', 'extra');
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Found -->
        <div class="bg-green-50 border border-green-200 p-6 rounded-lg">
            <h3 class="font-semibold text-lg text-green-800 mb-4">{{ __('Found') }} ({{ $foundItems->count() }})</h3>
            <ul class="space-y-2">
                @forelse($foundItems as $item)
                    <li class="text-sm text-green-700">{{ $item->asset->inventory_number ?? $item->asset->serial_number }}</li>
                @empty
                    <li class="text-sm text-green-700">{{ __('No items were found.') }}</li>
                @endforelse
            </ul>
        </div>

        <!-- Missing -->
        <div class="bg-red-50 border border-red-200 p-6 rounded-lg">
            <h3 class="font-semibold text-lg text-red-800 mb-4">{{ __('Missing') }} ({{ $missingItems->count() }})</h3>
            <ul class="space-y-2">
                @forelse($missingItems as $item)
                    <li class="text-sm text-red-700">{{ $item->asset->inventory_number ?? $item->asset->serial_number }}</li>
                @empty
                    <li class="text-sm text-red-700">{{ __('No items were missing.') }}</li>
                @endforelse
            </ul>
        </div>

        <!-- Extra -->
        <div class="bg-yellow-50 border border-yellow-200 p-6 rounded-lg">
            <h3 class="font-semibold text-lg text-yellow-800 mb-4">{{ __('Extra') }} ({{ $extraItems->count() }})</h3>
            <ul class="space-y-2">
                @forelse($extraItems as $item)
                    <li class="text-sm text-yellow-700">
                        {{ $item->asset->inventory_number ?? $item->asset->serial_number }}
                        <span class="text-xs">({{ __('Expected in') }}: {{ $item->asset->expectedRoom->name ?? __('Warehouse') }})</span>
                    </li>
                @empty
                    <li class="text-sm text-yellow-700">{{ __('No extra items were found.') }}</li>
                @endforelse
            </ul>
        </div>
    </div>

</x-app-layout>
