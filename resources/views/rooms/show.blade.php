<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-text-primary leading-tight">{{ __('Room Details') }}</h2>
    </x-slot>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Info Column -->
        <div class="lg:col-span-2 space-y-8">
            <div class="bg-surface p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold text-text-primary mb-4">{{ $room->name }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                    <div><strong class="text-text-secondary">{{ __('Floor') }}:</strong> {{ $room->floor->name ?? '-' }}</div>
                    <div><strong class="text-text-secondary">{{ __('Location') }}:</strong> {{ $room->floor->location->name ?? '-' }}</div>
                    <div class="md:col-span-2"><strong class="text-text-secondary">{{ __('Description') }}:</strong> {{ $room->description ?? '-' }}</div>
                </div>
            </div>
            <div class="bg-surface p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold text-text-primary mb-4">{{ __('Assets in Room') }} ({{ $room->assets->count() }})</h3>
                @if ($room->assets->isNotEmpty())
                    <ul class="divide-y divide-border-color">
                        @foreach ($room->assets as $asset)
                            <li class="py-2 flex justify-between items-center">
                                <a href="{{ route('assets.show', $asset->id) }}" class="text-primary hover:underline">{{ $asset->inventory_number ?? $asset->serial_number }}</a>
                                <span class="text-xs text-text-secondary">{{ $asset->category->name ?? '' }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-text-secondary">{{ __('No assets found in this room.') }}</p>
                @endif
            </div>
        </div>
        <!-- QR Code & Actions Column -->
        <div class="space-y-8">
            <div class="bg-surface p-6 rounded-lg shadow-md text-center">
                <h3 class="text-xl font-bold text-text-primary mb-4">{{ __('QR Code') }}</h3>
                @if ($room->qr_code_data)
                    <div class="flex justify-center mb-4">
                        <div class="border border-border-color p-2 rounded-md">
                            {!! QrCode::size(180)->generate($room->qr_code_data) !!}
                        </div>
                    </div>
                    <a href="{{ route('rooms.qrcode', $room->id) }}" download class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 text-xs font-medium rounded-md hover:bg-gray-300">
                        <i class="fas fa-download mr-2"></i>{{ __('Download') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>