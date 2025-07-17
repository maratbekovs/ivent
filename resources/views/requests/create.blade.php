<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-text-primary leading-tight">{{ __('Create New Request') }}</h2>
    </x-slot>
    <div class="max-w-4xl mx-auto">
        <div class="bg-surface p-6 md:p-8 rounded-lg shadow-md">
            <form method="POST" action="{{ route('requests.store') }}">
                @csrf
                <div class="space-y-6">
                    <div>
                        <x-input-label for="asset_id" :value="__('Asset (optional)')" />
                        <select id="asset_id" name="asset_id" class="block mt-1 w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm">
                            <option value="">{{ __('Select Asset') }}</option>
                            @foreach ($assets as $asset)
                                <option value="{{ $asset->id }}" @selected(old('asset_id') == $asset->id)>{{ $asset->inventory_number ?? $asset->serial_number }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('asset_id')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="subject" :value="__('Subject')" />
                        <x-text-input id="subject" class="block mt-1 w-full" type="text" name="subject" :value="old('subject')" required />
                        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" rows="5" class="block mt-1 w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm">{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                </div>
                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('requests.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">{{ __('Cancel') }}</a>
                    <x-primary-button>{{ __('Submit Request') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>