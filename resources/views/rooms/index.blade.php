<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-text-primary leading-tight">{{ __('Rooms') }}</h2>
            @can('manage_locations')
                <a href="{{ route('rooms.create') }}" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md text-xs font-semibold uppercase hover:bg-primary-dark">
                    <i class="fas fa-plus mr-2"></i>{{ __('Add New Room') }}
                </a>
            @endcan
        </div>
    </x-slot>

    <!-- Форма для поиска и фильтрации -->
    <div class="mb-6 bg-surface p-4 rounded-lg shadow-md">
        <form action="{{ route('rooms.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <input type="text" name="search" placeholder="{{ __('Search by room name...') }}" value="{{ request('search') }}" class="block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm text-sm">
                </div>
                <div>
                    <select name="floor_id" class="block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm text-sm">
                        <option value="">{{ __('All Floors') }}</option>
                        @foreach($floors as $floor)
                            <option value="{{ $floor->id }}" @selected(request('floor_id') == $floor->id)>{{ $floor->name }} ({{ $floor->location->name }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <a href="{{ route('rooms.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4 py-2">{{ __('Reset') }}</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary/80 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary focus:outline-none">
                    {{ __('Filter') }}
                </button>
            </div>
        </form>
    </div>

    <div class="bg-surface p-6 rounded-lg shadow-md">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('Name') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('Floor') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('Location') }}</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">{{ __('Actions') }}</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($rooms as $room)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-text-primary">{{ $room->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">{{ $room->floor->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">{{ $room->floor->location->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    <a href="{{ route('rooms.show', $room) }}" class="text-gray-400 hover:text-primary" title="{{ __('View') }}"><i class="fas fa-eye"></i></a>
                                    @can('manage_locations')
                                    <a href="{{ route('rooms.edit', $room) }}" class="text-gray-400 hover:text-primary" title="{{ __('Edit') }}"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('rooms.destroy', $room) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600" title="{{ __('Delete') }}"><i class="fas fa-trash"></i></button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ __('No rooms found matching your criteria.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($rooms->hasPages())
            <div class="mt-4">
                {{ $rooms->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
