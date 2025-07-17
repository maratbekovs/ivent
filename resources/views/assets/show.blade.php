<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-text-primary leading-tight">
            {{ __('Asset Details') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Info Column -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Asset Information Card -->
            <div class="bg-surface p-6 rounded-lg shadow-md">
                <div class="flex justify-between items-start">
                    <h3 class="text-xl font-bold text-text-primary mb-4">{{ $asset->inventory_number ?? $asset->serial_number }}</h3>
                    <span class="px-3 py-1 text-xs leading-5 font-semibold rounded-full bg-primary/10 text-primary">
                        {{ $asset->status->name ?? '-' }}
                    </span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div><strong class="text-text-secondary">{{ __('Serial Number') }}:</strong> {{ $asset->serial_number }}</div>
                    <div><strong class="text-text-secondary">{{ __('Inventory Number') }}:</strong> {{ $asset->inventory_number ?? '-' }}</div>
                    <div><strong class="text-text-secondary">{{ __('Category') }}:</strong> {{ $asset->category->name ?? '-' }}</div>
                    <div><strong class="text-text-secondary">{{ __('MAC Address') }}:</strong> {{ $asset->mac_address ?? '-' }}</div>
                    <div><strong class="text-text-secondary">{{ __('Purchase Year') }}:</strong> {{ $asset->purchase_year ?? '-' }}</div>
                    <div><strong class="text-text-secondary">{{ __('Warranty Expiration') }}:</strong> {{ $asset->warranty_expires_at?->format('Y-m-d') ?? '-' }}</div>
                </div>
                @if($asset->notes)
                <div class="mt-4 pt-4 border-t border-border-color">
                    <strong class="text-text-secondary text-sm">{{ __('Notes') }}:</strong>
                    <p class="text-sm text-text-primary mt-1">{{ $asset->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Location & Responsible Person Card -->
            <div class="bg-surface p-6 rounded-lg shadow-md">
                 <h3 class="text-xl font-bold text-text-primary mb-4">{{ __('Assignment') }}</h3>
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div><strong class="text-text-secondary">{{ __('Responsible Person') }}:</strong> {{ $asset->currentUser->name ?? '-' }}</div>
                    <div><strong class="text-text-secondary">{{ __('Position') }}:</strong> {{ $asset->currentUser->position ?? '-' }}</div>
                    <div><strong class="text-text-secondary">{{ __('Location') }}:</strong> {{ $asset->room->name ?? __('Warehouse') }}</div>
                    @if ($asset->room)
                        <div><strong class="text-text-secondary">{{ __('Building/Floor') }}:</strong> {{ $asset->room->floor->location->name ?? '-' }} / {{ $asset->room->floor->name ?? '-' }}</div>
                    @endif
                 </div>
            </div>

            <!-- ИЗМЕНЕНИЕ: Добавлен блок с историей -->
            <div class="bg-surface p-6 rounded-lg shadow-md" x-data="{ tab: 'assignments' }">
                <div class="border-b border-gray-200 mb-4">
                    <nav class="-mb-px flex space-x-6">
                        <button @click="tab = 'assignments'" :class="{'border-primary text-primary': tab === 'assignments', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'assignments'}" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">
                            {{ __('Assignment History') }}
                        </button>
                        <button @click="tab = 'movements'" :class="{'border-primary text-primary': tab === 'movements', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': tab !== 'movements'}" class="whitespace-nowrap py-3 px-1 border-b-2 font-medium text-sm">
                            {{ __('Movement History') }}
                        </button>
                    </nav>
                </div>

                <!-- Assignment History Tab -->
                <div x-show="tab === 'assignments'">
                    <ul class="space-y-3">
                        @forelse($asset->userHistory as $history)
                            <li class="flex items-center justify-between text-sm">
                                <div>
                                    <span class="font-semibold text-text-primary">{{ $history->user->name ?? __('Unknown User') }}</span>
                                    <span class="text-text-secondary">{{ __('was assigned') }}</span>
                                </div>
                                <span class="text-xs text-gray-400">{{ $history->created_at->format('Y-m-d H:i') }}</span>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500">{{ __('No assignment history found.') }}</li>
                        @endforelse
                    </ul>
                </div>

                <!-- Movement History Tab -->
                <div x-show="tab === 'movements'" style="display: none;">
                     <ul class="space-y-3">
                        @forelse($asset->stockMovements as $movement)
                            <li class="flex items-center justify-between text-sm">
                                <div>
                                    <span class="font-semibold text-text-primary">{{ $movement->user->name ?? __('System') }}</span>
                                    <span class="text-text-secondary">{{ __('moved from') }}</span>
                                    <span class="font-semibold text-text-primary">{{ $movement->fromLocation->name ?? __('Warehouse') }}</span>
                                    <span class="text-text-secondary">{{ __('to') }}</span>
                                    <span class="font-semibold text-text-primary">{{ $movement->toLocation->name ?? __('Warehouse') }}</span>
                                </div>
                                <span class="text-xs text-gray-400">{{ $movement->movement_date->format('Y-m-d H:i') }}</span>
                            </li>
                        @empty
                            <li class="text-sm text-gray-500">{{ __('No movement history found.') }}</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- QR Code & Actions Column -->
        <div class="space-y-8">
            <div class="bg-surface p-6 rounded-lg shadow-md text-center">
                <h3 class="text-xl font-bold text-text-primary mb-4">{{ __('QR Code') }}</h3>
                @if ($asset->qr_code_data)
                    <div class="flex justify-center mb-4">
                        <div class="border border-border-color p-2 rounded-md">
                            {!! QrCode::size(180)->generate($asset->qr_code_data) !!}
                        </div>
                    </div>
                    <a href="{{ route('assets.qrcode', $asset->id) }}" download class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-xs font-medium rounded-md hover:bg-gray-300">
                        <i class="fas fa-download mr-2"></i>
                        {{ __('Download') }}
                    </a>
                @else
                    <p class="text-sm text-text-secondary">{{ __('QR Code not generated.') }}</p>
                @endif
            </div>
            <div class="bg-surface p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold text-text-primary mb-4">{{ __('Actions') }}</h3>
                <div class="space-y-3">
                    @can('edit_assets')
                    <a href="{{ route('assets.edit', $asset) }}" class="w-full inline-flex justify-center items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark">
                        {{ __('Edit Asset') }}
                    </a>
                    @endcan
                     @can('delete_assets')
                    <form action="{{ route('assets.destroy', $asset) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                            {{ __('Delete Asset') }}
                        </button>
                    </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
