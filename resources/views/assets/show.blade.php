<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Asset Details') }}: {{ $asset->inventory_number ?? $asset->serial_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-neutral-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-neutral-900 mb-4">{{ __('Asset Information') }}</h3>
                            <p class="mb-2"><strong>{{ __('Serial Number') }}:</strong> {{ $asset->serial_number }}</p>
                            <p class="mb-2"><strong>{{ __('Inventory Number') }}:</strong> {{ $asset->inventory_number ?? '-' }}</p>
                            <p class="mb-2"><strong>{{ __('MAC Address') }}:</strong> {{ $asset->mac_address ?? '-' }}</p>
                            <p class="mb-2"><strong>{{ __('Category') }}:</strong> {{ $asset->category->name ?? '-' }}</p>
                            <p class="mb-2"><strong>{{ __('Status') }}:</strong> {{ $asset->status->name ?? '-' }}</p>
                            <p class="mb-2"><strong>{{ __('Purchase Year') }}:</strong> {{ $asset->purchase_year ?? '-' }}</p>
                            <p class="mb-2"><strong>{{ __('Warranty Expiration Date') }}:</strong> {{ $asset->warranty_expires_at?->format('Y-m-d') ?? '-' }}</p>
                            <p class="mb-2"><strong>{{ __('Notes') }}:</strong> {{ $asset->notes ?? '-' }}</p>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-neutral-900 mb-4">{{ __('Location & Responsible Person') }}</h3>
                            <p class="mb-2"><strong>{{ __('Location') }}:</strong> {{ $asset->room->name ?? __('Warehouse') }}</p>
                            @if ($asset->room)
                                <p class="mb-2"><strong>{{ __('Floor') }}:</strong> {{ $asset->room->floor->name ?? '-' }}</p>
                                <p class="mb-2"><strong>{{ __('Building') }}:</strong> {{ $asset->room->floor->location->name ?? '-' }}</p>
                            @endif
                            <p class="mb-2"><strong>{{ __('Responsible Person') }}:</strong> {{ $asset->currentUser->name ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="mt-8 border-t border-neutral-200 pt-6">
                        <h3 class="text-lg font-medium text-neutral-900 mb-4">{{ __('QR Code for Asset') }}</h3>
                        <p class="text-neutral-600 mb-4">{{ __('Scan this QR code with our website scanner to quickly view asset details.') }}</p>
                        @if ($asset->qr_code_data)
                            <div class="flex flex-col items-center sm:items-start">
                                {{-- Генерируем QR-код на лету как SVG --}}
                                <div class="border border-neutral-300 p-2 rounded-md mb-4 w-48 h-48 sm:w-64 sm:h-64 flex items-center justify-center">
                                    {!! QrCode::size(200)->generate($asset->qr_code_data) !!}
                                </div>
                                {{-- Ссылка для скачивания QR-кода --}}
                                <a href="{{ route('assets.qrcode', $asset->id) }}" download="{{ $asset->inventory_number ?? $asset->serial_number }}_qrcode.svg" class="inline-flex items-center px-4 py-2 bg-secondary-600 border border-transparent rounded-md font-semibold text-xs text-neutral-900 uppercase tracking-widest hover:bg-secondary-700 focus:bg-secondary-700 active:bg-secondary-900 focus:outline-none focus:ring-2 focus:ring-secondary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Download QR Code') }}
                                </a>
                            </div>
                        @else
                            <p class="text-rose-600">{{ __('QR Code not generated yet or missing. Please edit the asset to generate it.') }}</p>
                        @endif
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('assets.edit', $asset) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Edit Asset') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
