<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Edit Asset') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-neutral-900">
                    <form method="POST" action="{{ route('assets.update', $asset) }}">
                        @csrf
                        @method('PUT')

                        <!-- Serial Number -->
                        <div>
                            <x-input-label for="serial_number" :value="__('Serial Number')" />
                            <x-text-input id="serial_number" class="block mt-1 w-full" type="text" name="serial_number" :value="old('serial_number', $asset->serial_number)" required autofocus />
                            <x-input-error :messages="$errors->get('serial_number')" class="mt-2" />
                        </div>

                        <!-- Inventory Number -->
                        <div class="mt-4">
                            <x-input-label for="inventory_number" :value="__('Inventory Number')" />
                            <x-text-input id="inventory_number" class="block mt-1 w-full" type="text" name="inventory_number" :value="old('inventory_number', $asset->inventory_number)" />
                            <x-input-error :messages="$errors->get('inventory_number')" class="mt-2" />
                        </div>

                        <!-- MAC Address -->
                        <div class="mt-4">
                            <x-input-label for="mac_address" :value="__('MAC Address')" />
                            <x-text-input id="mac_address" class="block mt-1 w-full" type="text" name="mac_address" :value="old('mac_address', $asset->mac_address)" />
                            <x-input-error :messages="$errors->get('mac_address')" class="mt-2" />
                        </div>

                        <!-- Category -->
                        <div class="mt-4">
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select id="category_id" name="category_id" class="block mt-1 w-full border-neutral-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                <option value="">{{ __('Select Category') }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $asset->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div class="mt-4">
                            <x-input-label for="asset_status_id" :value="__('Status')" />
                            <select id="asset_status_id" name="asset_status_id" class="block mt-1 w-full border-neutral-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                <option value="">{{ __('Select Status') }}</option>
                                @foreach ($statuses as $status)
                                    <option value="{{ $status->id }}" {{ old('asset_status_id', $asset->asset_status_id) == $status->id ? 'selected' : '' }}>
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('asset_status_id')" class="mt-2" />
                        </div>

                        <!-- Location (Room) -->
                        <div class="mt-4">
                            <x-input-label for="room_id" :value="__('Location (Room)')" />
                            <select id="room_id" name="room_id" class="block mt-1 w-full border-neutral-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                <option value="">{{ __('Select Room (optional)') }}</option>
                                @foreach ($rooms as $room)
                                    <option value="{{ $room->id }}" {{ old('room_id', $asset->room_id) == $room->id ? 'selected' : '' }}>
                                        {{ $room->name }} ({{ $room->floor->name ?? '' }} - {{ $room->floor->location->name ?? '' }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('room_id')" class="mt-2" />
                        </div>

                        <!-- Current User (Responsible Person) -->
                        <div class="mt-4">
                            <x-input-label for="current_user_id" :value="__('Responsible Person')" />
                            <select id="current_user_id" name="current_user_id" class="block mt-1 w-full border-neutral-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                <option value="">{{ __('Select Responsible Person (optional)') }}</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('current_user_id', $asset->current_user_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('current_user_id')" class="mt-2" />
                        </div>

                        <!-- Purchase Year -->
                        <div class="mt-4">
                            <x-input-label for="purchase_year" :value="__('Purchase Year')" />
                            <x-text-input id="purchase_year" class="block mt-1 w-full" type="number" name="purchase_year" :value="old('purchase_year', $asset->purchase_year)" min="1900" max="{{ date('Y') }}" />
                            <x-input-error :messages="$errors->get('purchase_year')" class="mt-2" />
                        </div>

                        <!-- Warranty Expires At -->
                        <div class="mt-4">
                            <x-input-label for="warranty_expires_at" :value="__('Warranty Expiration Date')" />
                            <x-text-input id="warranty_expires_at" class="block mt-1 w-full" type="date" name="warranty_expires_at" :value="old('warranty_expires_at', $asset->warranty_expires_at?->format('Y-m-d'))" />
                            <x-input-error :messages="$errors->get('warranty_expires_at')" class="mt-2" />
                        </div>

                        <!-- Notes -->
                        <div class="mt-4">
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" rows="3" class="block mt-1 w-full border-neutral-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">{{ old('notes', $asset->notes) }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Update Asset') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
