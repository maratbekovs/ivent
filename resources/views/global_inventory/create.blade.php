<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-text-primary leading-tight">
            {{ __('Start New Inventory Session') }}
        </h2>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-surface p-6 md:p-8 rounded-lg shadow-md">
            <form method="POST" action="{{ route('global-inventory.store') }}">
                @csrf
                <div class="space-y-6">
                    <div>
                        <x-input-label for="room_id" :value="__('Select a Room to Inventory')" />
                        <p class="text-sm text-text-secondary mt-1 mb-2">
                            {{ __('Choose the location where you want to start the inventory process.') }}
                        </p>
                        <select id="room_id" name="room_id" class="block mt-1 w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm" required>
                            <option value="" disabled selected>{{ __('Select a room...') }}</option>
                            @foreach ($rooms as $room)
                                <option value="{{ $room->id }}">{{ $room->name }} ({{ $room->floor->location->name }})</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('room_id')" class="mt-2" />
                    </div>
                </div>
                <div class="flex items-center justify-end mt-8">
                    <x-primary-button>
                        <i class="fas fa-play mr-2"></i>
                        {{ __('Start Session') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
