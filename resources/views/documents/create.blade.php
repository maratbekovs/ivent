<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-text-primary leading-tight">{{ __('Create New Document') }}</h2>
    </x-slot>
    <div class="max-w-4xl mx-auto">
        <div class="bg-surface p-6 md:p-8 rounded-lg shadow-md" x-data="{ docType: '{{ old('type', 'other') }}' }">
            <form method="POST" action="{{ route('documents.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="space-y-6">
                    <!-- Document Type Selector -->
                    <div>
                        <x-input-label for="type" :value="__('Document Type')" />
                        <select id="type" name="type" x-model="docType" class="block mt-1 w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm">
                            <option value="write_off_act">{{ __('Write-off Act') }}</option>
                            <option value="acceptance_act">{{ __('Acceptance Act') }}</option>
                            <option value="warranty">{{ __('Warranty') }}</option>
                            <option value="other">{{ __('Other') }}</option>
                        </select>
                    </div>

                    <!-- Common Field: Title -->
                    <div x-show="docType === 'warranty' || docType === 'other'">
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <!-- Fields for Write-off Act -->
                    <div x-show="docType === 'write_off_act'" style="display: none;" class="space-y-6">
                        <div>
                            <x-input-label for="asset_ids_write_off" :value="__('Assets to Write Off')" />
                            <select id="asset_ids_write_off" name="asset_ids[]" multiple placeholder="{{ __('Select assets...') }}">
                                @foreach ($assets as $asset)
                                    <option value="{{ $asset->id }}">{{ $asset->inventory_number ?? $asset->serial_number }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('asset_ids')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="reason" :value="__('Reason for Write-off')" />
                            <textarea id="reason" name="reason" rows="3" class="block mt-1 w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm">{{ old('reason') }}</textarea>
                            <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="commission" :value="__('Commission Members')" />
                            <p class="text-xs text-gray-500 mt-1 mb-2">{{ __('Enter names, separated by commas. The first name will be the chairman.') }}</p>
                            <textarea id="commission" name="commission" rows="3" placeholder="Иванов И.И., Петров П.П., Сидоров С.С." class="block mt-1 w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm">{{ old('commission') }}</textarea>
                            <x-input-error :messages="$errors->get('commission')" class="mt-2" />
                        </div>
                    </div>
                    
                    <!-- Fields for other types (File Upload) -->
                    <div x-show="docType !== 'write_off_act' && docType !== 'acceptance_act'">
                        <x-input-label for="file" :value="__('File')" />
                        <input id="file" name="file" type="file" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 mt-1">
                        <x-input-error :messages="$errors->get('file')" class="mt-2" />
                    </div>
                </div>
                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('documents.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">{{ __('Cancel') }}</a>
                    <x-primary-button>{{ __('Save Document') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
    @push('scripts')
    <script>
        // Инициализация TomSelect для поля выбора активов
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect('#asset_ids_write_off',{
                plugins: ['remove_button'],
                create: false,
            });
        });
    </script>
    @endpush
</x-app-layout>
