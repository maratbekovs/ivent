<div class="flex flex-col h-full">
    <div>
        <h4 class="text-lg font-bold text-text-primary">{{ $asset->inventory_number ?? $asset->serial_number }}</h4>
        <span class="px-2 py-0.5 text-xs leading-5 font-semibold rounded-full bg-primary/10 text-primary mb-4 inline-block">
            {{ __('Asset') }}
        </span>

        <div class="space-y-2 text-sm">
            <p><strong class="text-text-secondary w-32 inline-block">{{ __('Status') }}:</strong> {{ $asset->status->name ?? '-' }}</p>
            <p><strong class="text-text-secondary w-32 inline-block">{{ __('Category') }}:</strong> {{ $asset->category->name ?? '-' }}</p>
            <p><strong class="text-text-secondary w-32 inline-block">{{ __('Location') }}:</strong> {{ $asset->room->name ?? __('Warehouse') }}</p>
            <p><strong class="text-text-secondary w-32 inline-block">{{ __('Responsible') }}:</strong> {{ $asset->currentUser->name ?? '-' }}</p>
        </div>
    </div>

    <div class="mt-auto pt-6 border-t border-border-color">
        <a href="{{ route('assets.show', $asset) }}" class="inline-flex items-center justify-center w-full px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark">
            {{ __('View Full Details') }}
        </a>
    </div>
</div>