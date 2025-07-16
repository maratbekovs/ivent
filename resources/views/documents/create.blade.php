<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Create New Document') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-neutral-900">
                    <form method="POST" action="{{ route('documents.store') }}">
                        @csrf

                        <!-- Document Type -->
                        <div>
                            <x-input-label for="document_type" :value="__('Document Type')" />
                            <select id="document_type" name="document_type" class="block mt-1 w-full border-neutral-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                <option value="">{{ __('Select Document Type') }}</option>
                                <option value="acceptance" {{ old('document_type') == 'acceptance' ? 'selected' : '' }}>{{ __('Acceptance Act') }}</option>
                                <option value="disposal" {{ old('document_type') == 'disposal' ? 'selected' : '' }}>{{ __('Disposal Act') }}</option>
                                <option value="repair_request" {{ old('document_type') == 'repair_request' ? 'selected' : '' }}>{{ __('Repair Request Document') }}</option>
                                <option value="inventory_order" {{ old('document_type') == 'inventory_order' ? 'selected' : '' }}>{{ __('Inventory Order') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('document_type')" class="mt-2" />
                        </div>

                        <!-- Related Asset (Optional) -->
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

                        <!-- Related Request (Optional) -->
                        <div class="mt-4">
                            <x-input-label for="related_request_id" :value="__('Related Request (optional)')" />
                            <select id="related_request_id" name="related_request_id" class="block mt-1 w-full border-neutral-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                <option value="">{{ __('Select Request') }}</option>
                                @foreach ($requests as $requestItem)
                                    <option value="{{ $requestItem->id }}" {{ old('related_request_id') == $requestItem->id ? 'selected' : '' }}>
                                        {{ $requestItem->id }} - {{ Str::limit($requestItem->description, 30) }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('related_request_id')" class="mt-2" />
                        </div>

                        <!-- Notes -->
                        <div class="mt-4">
                            <x-input-label for="notes" :value="__('Notes')" />
                            <textarea id="notes" name="notes" rows="5" class="block mt-1 w-full border-neutral-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">{{ old('notes') }}</textarea>
                            <x-input-error :messages="$errors->get('notes')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Save Document') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
