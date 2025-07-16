<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Edit Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-neutral-900">
                    <form method="POST" action="{{ route('requests.update', $request) }}">
                        @csrf
                        @method('PUT')

                        <!-- Type (Read-only for existing requests) -->
                        <div>
                            <x-input-label for="type" :value="__('Request Type')" />
                            <x-text-input id="type" class="block mt-1 w-full bg-neutral-100" type="text" :value="__('request_type_' . $request->type)" readonly />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" rows="5" class="block mt-1 w-full border-neutral-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">{{ old('description', $request->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <!-- Asset (Optional) -->
                        <div class="mt-4">
                            <x-input-label for="asset_id" :value="__('Related Asset (optional)')" />
                            <select id="asset_id" name="asset_id" class="block mt-1 w-full border-neutral-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                <option value="">{{ __('Select Asset') }}</option>
                                @foreach ($assets as $asset)
                                    <option value="{{ $asset->id }}" {{ old('asset_id', $request->asset_id) == $asset->id ? 'selected' : '' }}>
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
                                <option value="low" {{ old('priority', $request->priority) == 'low' ? 'selected' : '' }}>{{ __('Low') }}</option>
                                <option value="medium" {{ old('priority', $request->priority) == 'medium' ? 'selected' : '' }}>{{ __('Medium') }}</option>
                                <option value="high" {{ old('priority', $request->priority) == 'high' ? 'selected' : '' }}>{{ __('High') }}</option>
                                <option value="critical" {{ old('priority', $request->priority) == 'critical' ? 'selected' : '' }}>{{ __('Critical') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                        </div>

                        <!-- Status -->
                        <div class="mt-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" name="status" class="block mt-1 w-full border-neutral-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                <option value="pending" {{ old('status', $request->status) == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                <option value="in_progress" {{ old('status', $request->status) == 'in_progress' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
                                <option value="completed" {{ old('status', $request->status) == 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                                <option value="cancelled" {{ old('status', $request->status) == 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <!-- Assigned To -->
                        <div class="mt-4">
                            <x-input-label for="assigned_to_id" :value="__('Assigned To')" />
                            <select id="assigned_to_id" name="assigned_to_id" class="block mt-1 w-full border-neutral-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm">
                                <option value="">{{ __('Unassigned') }}</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" {{ old('assigned_to_id', $request->assigned_to_id) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('assigned_to_id')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Update Request') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
