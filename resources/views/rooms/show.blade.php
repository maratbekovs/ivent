<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Room Details') }}: {{ $room->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-neutral-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-neutral-900 mb-4">{{ __('Room Information') }}</h3>
                            <p class="mb-2"><strong>{{ __('Name') }}:</strong> {{ $room->name }}</p>
                            <p class="mb-2"><strong>{{ __('Floor') }}:</strong> {{ $room->floor->name ?? '-' }}</p>
                            <p class="mb-2"><strong>{{ __('Location') }}:</strong> {{ $room->floor->location->name ?? '-' }}</p>
                            <p class="mb-2"><strong>{{ __('Description') }}:</strong> {{ $room->description ?? '-' }}</p>
                            <p class="mb-2"><strong>{{ __('Assets in Room') }}:</strong> {{ $room->assets->count() }}</p>
                            <h4 class="font-semibold text-md text-neutral-800 mt-4 mb-2">{{ __('List of Assets in this Room') }}:</h4>
                            @if ($room->assets->isEmpty())
                                <p class="text-neutral-600">{{ __('No assets found in this room.') }}</p>
                            @else
                                <ul class="list-disc list-inside text-neutral-700">
                                    @foreach ($room->assets as $asset)
                                        <li><a href="{{ route('assets.show', $asset->id) }}" class="text-primary-600 hover:underline">{{ $asset->inventory_number ?? $asset->serial_number }}</a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-neutral-900 mb-4">{{ __('QR Code for Room') }}</h3>
                            <p class="text-neutral-600 mb-4">{{ __('Scan this QR code with our website scanner to quickly view room details and assets within it.') }}</p>
                            @if ($room->qr_code_data)
                                <div class="flex flex-col items-center sm:items-start">
                                    {{-- Генерируем QR-код на лету как SVG --}}
                                    <div class="border border-neutral-300 p-2 rounded-md mb-4 w-48 h-48 sm:w-64 sm:h-64 flex items-center justify-center">
                                        {!! QrCode::size(200)->generate($room->qr_code_data) !!}
                                    </div>
                                    {{-- Ссылка для скачивания QR-кода --}}
                                    <a href="{{ route('rooms.qrcode', $room->id) }}" download="{{ $room->name }}_qrcode.svg" class="inline-flex items-center px-4 py-2 bg-secondary-600 border border-transparent rounded-md font-semibold text-xs text-neutral-900 uppercase tracking-widest hover:bg-secondary-700 focus:bg-secondary-700 active:bg-secondary-900 focus:outline-none focus:ring-2 focus:ring-secondary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        {{ __('Download QR Code') }}
                                    </a>
                                </div>
                            @else
                                <p class="text-rose-600">{{ __('QR Code not generated yet or missing. Please edit the room to generate it.') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <a href="{{ route('rooms.edit', $room) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Edit Room') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
