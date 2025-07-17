<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-text-primary leading-tight">
                {{ __('Inventory') }}
            </h2>
            @can('create_assets')
                <a href="{{ route('assets.create') }}" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-plus mr-2"></i>
                    {{ __('Add New Asset') }}
                </a>
            @endcan
        </div>
    </x-slot>

    <!-- ИЗМЕНЕНИЕ: Добавлена форма для поиска и фильтрации -->
    <div class="mb-6 bg-surface p-4 rounded-lg shadow-md">
        <form action="{{ route('assets.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <div class="lg:col-span-2">
                    <label for="search" class="sr-only">{{ __('Search') }}</label>
                    <input type="text" name="search" id="search" placeholder="{{ __('Search by S/N or Inv. Number...') }}" value="{{ request('search') }}" class="block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm text-sm">
                </div>
                <div>
                    <label for="category_id" class="sr-only">{{ __('Category') }}</label>
                    <select name="category_id" id="category_id" class="block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm text-sm">
                        <option value="">{{ __('All Categories') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="status_id" class="sr-only">{{ __('Status') }}</label>
                    <select name="status_id" id="status_id" class="block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm text-sm">
                        <option value="">{{ __('All Statuses') }}</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status->id }}" @selected(request('status_id') == $status->id)>{{ $status->name }}</option>
                        @endforeach
                    </select>
                </div>
                 <div>
                    <label for="room_id" class="sr-only">{{ __('Location') }}</label>
                    <select name="room_id" id="room_id" class="block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm text-sm">
                        <option value="">{{ __('All Locations') }}</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" @selected(request('room_id') == $room->id)>{{ $room->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <a href="{{ route('assets.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4 py-2">{{ __('Reset') }}</a>
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
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('Inventory Number') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('Serial Number') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('Category') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('Status') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('Location') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('Responsible Person') }}</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">{{ __('Actions') }}</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($assets as $asset)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-text-primary">{{ $asset->inventory_number ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">{{ $asset->serial_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">{{ $asset->category->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $asset->status->name ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">{{ $asset->room->name ?? __('Warehouse') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">{{ $asset->currentUser->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    <a href="{{ route('assets.show', $asset) }}" class="text-gray-400 hover:text-primary" title="{{ __('View') }}"><i class="fas fa-eye"></i></a>
                                    @can('edit_assets')
                                    <a href="{{ route('assets.edit', $asset) }}" class="text-gray-400 hover:text-primary" title="{{ __('Edit') }}"><i class="fas fa-edit"></i></a>
                                    @endcan
                                    @can('delete_assets')
                                    <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure you want to delete this asset?') }}');">
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
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">
                                {{ __('No assets found matching your criteria.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($assets->hasPages())
            <div class="mt-4">
                {{ $assets->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
