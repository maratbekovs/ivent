<x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-2xl text-text-primary leading-tight">{{ __('Add New Movement') }}</h2>
        </x-slot>
        <div class="max-w-4xl mx-auto">
            <div class="bg-surface p-6 md:p-8 rounded-lg shadow-md">
                <form method="POST" action="{{ route('stock-movements.store') }}">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <x-input-label for="asset_ids" :value="__('Assets')" />
                            <select id="asset_ids" name="asset_ids[]" multiple placeholder="{{ __('Select assets...') }}" autocomplete="off">
                                @foreach ($assets as $asset)
                                    <option value="{{ $asset->id }}">{{ $asset->inventory_number ?? $asset->serial_number }} ({{ $asset->category->name ?? '' }})</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('asset_ids')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="type" :value="__('Movement Type')" />
                            <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm">
                                <option value="transfer" @selected(old('type') == 'transfer')>{{ __('Transfer') }}</option>
                                <option value="in" @selected(old('type') == 'in')>{{ __('Inbound') }}</option>
                                <option value="out" @selected(old('type') == 'out')>{{ __('Outbound') }}</option>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="movement_date" :value="__('Movement Date')" />
                            <x-text-input id="movement_date" class="block mt-1 w-full" type="datetime-local" name="movement_date" :value="old('movement_date', now()->format('Y-m-d\TH:i'))" required />
                        </div>
                        <div>
                            <x-input-label for="from_location_id" :value="__('From Location')" />
                            <select id="from_location_id" name="from_location_id" class="block mt-1 w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm">
                                <option value="">{{ __('Warehouse') }}</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}">{{ $room->name }} ({{ $room->floor->location->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <x-input-label for="to_location_id" :value="__('To Location')" />
                            <select id="to_location_id" name="to_location_id" class="block mt-1 w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm">
                                <option value="">{{ __('Warehouse') }}</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}">{{ $room->name }} ({{ $room->floor->location->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" rows="4" class="block mt-1 w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('stock-movements.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">{{ __('Cancel') }}</a>
                        <x-primary-button>{{ __('Save Movement') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
        @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                new TomSelect('#asset_ids',{
                    plugins: ['remove_button'],
                    create: false,
                });
            });
        </script>
        @endpush
    </x-app-layout>