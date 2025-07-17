<div class="flex flex-col h-full">
    <div>
        <h4 class="text-lg font-bold text-text-primary">{{ $room->name }}</h4>
        <span class="px-2 py-0.5 text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 mb-4 inline-block">
            {{ __('Room') }}
        </span>

        <div class="space-y-2 text-sm">
            <p><strong class="text-text-secondary w-32 inline-block">{{ __('Floor') }}:</strong> {{ $room->floor->name ?? '-' }}</p>
            <p><strong class="text-text-secondary w-32 inline-block">{{ __('Building') }}:</strong> {{ $room->floor->location->name ?? '-' }}</p>
            <p><strong class="text-text-secondary w-32 inline-block">{{ __('Assets in room') }}:</strong> {{ $room->assets->count() }}</p>
        </div>
    </div>

    <div class="mt-auto pt-6 border-t border-border-color">
        <a href="{{ route('rooms.show', $room) }}" class="inline-flex items-center justify-center w-full px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark">
            {{ __('View Full Details') }}
        </a>
    </div>
</div>