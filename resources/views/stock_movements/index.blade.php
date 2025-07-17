<x-app-layout>
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-2xl text-text-primary leading-tight">
                    {{ __('Warehouse & Movements') }}
                </h2>
                @can('create_stock_movements')
                    <a href="{{ route('stock-movements.create') }}" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition ease-in-out duration-150">
                        <i class="fas fa-plus mr-2"></i>
                        {{ __('Add New Movement') }}
                    </a>
                @endcan
            </div>
        </x-slot>

        <div class="mb-6 bg-surface p-4 rounded-lg shadow-md">
            <form action="{{ route('stock-movements.index') }}" method="GET">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <input type="text" name="search" placeholder="{{ __('Search by asset S/N or Inv. Number...') }}" value="{{ request('search') }}" class="block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm text-sm">
                    </div>
                    <div>
                        <select name="type" class="block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm text-sm">
                            <option value="">{{ __('All Movement Types') }}</option>
                            <option value="in" @selected(request('type') == 'in')>{{ __('Inbound') }}</option>
                            <option value="out" @selected(request('type') == 'out')>{{ __('Outbound') }}</option>
                            <option value="transfer" @selected(request('type') == 'transfer')>{{ __('Transfer') }}</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <a href="{{ route('stock-movements.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4 py-2">{{ __('Reset') }}</a>
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
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('Assets') }}</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('Type') }}</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('From') }}</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('To') }}</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('Moved By') }}</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('Date') }}</th>
                            <th scope="col" class="relative px-6 py-3"><span class="sr-only">{{ __('Actions') }}</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($movements as $movement)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-text-primary">
                                    <span class="font-bold">{{ $movement->assets->count() }}</span> {{ trans_choice('asset', $movement->assets->count()) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                                    @if($movement->type === 'in')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ __('Inbound') }}</span>
                                    @elseif($movement->type === 'out')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">{{ __('Outbound') }}</span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ __('Transfer') }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">{{ $movement->fromLocation->name ?? __('Warehouse') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">{{ $movement->toLocation->name ?? __('Warehouse') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">{{ $movement->user->name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">{{ $movement->movement_date->format('Y-m-d H:i') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-4">
                                        <a href="{{ route('stock-movements.pdf', $movement) }}" class="text-gray-400 hover:text-primary" title="{{ __('Download PDF') }}">
                                            <i class="fas fa-file-pdf"></i>
                                        </a>
                                        @can('edit_stock_movements')
                                        <a href="{{ route('stock-movements.edit', $movement) }}" class="text-gray-400 hover:text-primary" title="{{ __('Edit') }}"><i class="fas fa-edit"></i></a>
                                        @endcan
                                        @can('delete_stock_movements')
                                        <form action="{{ route('stock-movements.destroy', $movement) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}');">
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
                                <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ __('No stock movements found matching your criteria.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($movements->hasPages())
                <div class="mt-4">
                    {{ $movements->links() }}
                </div>
            @endif
        </div>
    </x-app-layout>