<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-text-primary leading-tight">{{ __('Edit Service Request') }}</h2>
    </x-slot>
    <div class="max-w-4xl mx-auto">
        <div class="bg-surface p-6 md:p-8 rounded-lg shadow-md">
            <form method="POST" action="{{ route('requests.update', $request) }}">
                @csrf
                @method('PUT')
                <div class="space-y-6">
                    <div>
                        <x-input-label for="asset_id" :value="__('Asset')" />
                        <p class="mt-1 text-sm text-gray-800">{{ $request->asset->inventory_number ?? $request->asset->serial_number ?? __('Not specified') }}</p>
                    </div>
                    <div>
                        <x-input-label for="subject" :value="__('Subject')" />
                        <x-text-input id="subject" class="block mt-1 w-full" type="text" name="subject" :value="old('subject', $request->subject)" required />
                        <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="description" :value="__('Description')" />
                        <textarea id="description" name="description" rows="5" class="block mt-1 w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm">{{ old('description', $request->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="status" :value="__('Status')" />
                        <select id="status" name="status" class="block mt-1 w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm">
                            <option value="new" @selected(old('status', $request->status) == 'new')>{{ __('New') }}</option>
                            <option value="in_progress" @selected(old('status', $request->status) == 'in_progress')>{{ __('In Progress') }}</option>
                            <option value="completed" @selected(old('status', $request->status) == 'completed')>{{ __('Completed') }}</option>
                            <option value="rejected" @selected(old('status', $request->status) == 'rejected')>{{ __('Rejected') }}</option>
                        </select>
                         <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>
                </div>
                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('requests.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">{{ __('Cancel') }}</a>
                    <x-primary-button>{{ __('Update Request') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>