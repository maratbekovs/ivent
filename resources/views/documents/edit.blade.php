<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-text-primary leading-tight">{{ __('Edit Document') }}</h2>
    </x-slot>
    <div class="max-w-4xl mx-auto">
        <div class="bg-surface p-6 md:p-8 rounded-lg shadow-md">
            <form method="POST" action="{{ route('documents.update', $document) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $document->title)" required />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                     <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="type" :value="__('Document Type')" />
                            <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm">
                                <option value="acceptance_act" @selected(old('type', $document->type) == 'acceptance_act')>{{ __('Acceptance Act') }}</option>
                                <option value="write_off_act" @selected(old('type', $document->type) == 'write_off_act')>{{ __('Write-off Act') }}</option>
                                <option value="warranty" @selected(old('type', $document->type) == 'warranty')>{{ __('Warranty') }}</option>
                                <option value="other" @selected(old('type', $document->type) == 'other')>{{ __('Other') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('type')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="asset_id" :value="__('Related Asset (optional)')" />
                            <select id="asset_id" name="asset_id" class="block mt-1 w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm">
                                <option value="">{{ __('Select Asset') }}</option>
                                @foreach ($assets as $asset)
                                    <option value="{{ $asset->id }}" @selected(old('asset_id', $document->asset_id) == $asset->id)>{{ $asset->inventory_number ?? $asset->serial_number }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('asset_id')" class="mt-2" />
                        </div>
                    </div>
                    <div>
                        <x-input-label for="file" :value="__('New File (optional)')" />
                        <input id="file" name="file" type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 mt-1">
                        <x-input-error :messages="$errors->get('file')" class="mt-2" />
                        @if($document->file_path)
                            <p class="text-xs text-gray-500 mt-2">{{ __('Current file') }}: <a href="{{ Storage::url($document->file_path) }}" target="_blank" class="text-primary hover:underline">{{ $document->file_path }}</a></p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('documents.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">{{ __('Cancel') }}</a>
                    <x-primary-button>{{ __('Update Document') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>