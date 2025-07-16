<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Create New Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-neutral-900">
                    <form method="POST" action="{{ route('requests.store') }}">
                        @csrf

                        <!-- Type -->
                        <div>
                            <x-input-label for="type" :value="__('Request Type')" />
                            <select id="type" name="type" class="block mt-1 w-full border-neutral-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                <option value="">{{ __('Select Type') }}</option>
                                <option value="repair" {{ old('type') == 'repair' ? 'selected' : '' }}>{{ __('Repair') }}</option>
                                <option value="setup" {{ old('type') == 'setup' ? 'selected' : '' }}>{{ __('Setup') }}</option>
                                <option value="purchase" {{ old('type') == 'purchase' ? 'selected' : '' }}>{{ __('Purchase') }}</option>
                                <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="5" class="block mt-1 w-full border-neutral-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Asset (Optional) -->
                        <div class="mt-4">
                            <x-input-label for="asset_id" :value="__('Related Asset (optional)')" />
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

                        <!-- Priority -->
                        <div class="mt-4">
                            <x-input-label for="priority" :value="__('Priority')" />
                            <select id="priority" name="priority" class="block mt-1 w-full border-neutral-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>{{ __('Low') }}</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>{{ __('Medium') }}</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>{{ __('High') }}</option>
                                <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>{{ __('Critical') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Submit Request') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
