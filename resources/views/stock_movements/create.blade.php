<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Add New Movement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-neutral-900">
                    <form method="POST" action="{{ route('stock-movements.store') }}">
                        @csrf

                        <!-- Asset -->
                        <div>
                            <x-input-label for="asset_id" :value="__('Asset')" />
                            <select id="asset_id" name="asset_id" class="block mt-1 w-full border-neutral-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                <option value="">{{ __('Select Asset') }}</option>
                                @foreach ($assets as $asset)
                                    <option value="{{ $asset->id }}" {{ old('asset_id') == $asset->id ? 'selected' : '' }}>
                                        {{ $asset->inventory_number ?? $asset->serial_number }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('asset_id')" class="mt-2" />
                        </div>

                        <!-- Type -->
                        <div class="mt-4">
                            <x-input-label for="type" :value="__('Movement Type')" />
                            <select id="type" name="type" class="block mt-1 w-full border-neutral-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                <option value="">{{ __('Select Type') }}</option>
                                <option value="in" {{ old('type') == 'in' ? 'selected' : '' }}>{{ __('Inbound') }}</option>
                                <option value="out" {{ old('type') == 'out' ? 'selected' : '' }}>{{ __('Outbound') }}</option>
                                <option value="transfer" {{ old('type') == 'transfer' ? 'selected' : '' }}>{{ __('Transfer') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <!-- From Location -->
                        <div class="mt-4">
                            <x-input-label for="from_location_id" :value="__('From Location')" />
                            <select id="from_location_id" name="from_location_id" class="block mt-1 w-full border-neutral-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                <option value="">{{ __('Warehouse (no specific room)') }}</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('from_location_id') == $room->id ? 'selected' : '' }}>
                                        {{ $room->name }} ({{ $room->floor->name ?? '' }} - {{ $room->floor->location->name ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('from_location_id')" class="mt-2" />
                        </div>

                        <!-- To Location -->
                        <div class="mt-4">
                            <x-input-label for="to_location_id" :value="__('To Location')" />
                            <select id="to_location_id" name="to_location_id" class="block mt-1 w-full border-neutral-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                <option value="">{{ __('Warehouse (no specific room)') }}</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('to_location_id') == $room->id ? 'selected' : '' }}>
                                        {{ $room->name }} ({{ $room->floor->name ?? '' }} - {{ $room->floor->location->name ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('to_location_id')" class="mt-2" />
                        </div>

                        <!-- Movement Date -->
                        <div class="mt-4">
                            <x-input-label for="movement_date" :value="__('Movement Date')" />
                            <x-text-input id="movement_date" class="block mt-1 w-full" type="datetime-local" name="movement_date" :value="old('movement_date', now()->format('Y-m-d\TH:i'))" required />
                            <x-input-error :messages="$errors->get('movement_date')" class="mt-2" />
                        </div>

                        <!-- Notes -->
                        <div class="mt-4">
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full border-neutral-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Save Movement') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
