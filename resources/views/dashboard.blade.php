<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-text-primary leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <!-- Total Assets Card -->
        <div class="bg-surface p-6 rounded-lg shadow-md flex items-center">
            <div class="bg-primary/10 p-4 rounded-full mr-4">
                <i class="fas fa-boxes text-2xl text-primary"></i>
            </div>
            <div>
                <p class="text-sm text-text-secondary">{{ __('Total Assets') }}</p>
                <p class="text-2xl font-bold text-text-primary">{{ $totalAssets }}</p>
            </div>
        </div>

        <!-- Open Requests Card -->
        <div class="bg-surface p-6 rounded-lg shadow-md flex items-center">
            <div class="bg-accent/10 p-4 rounded-full mr-4">
                <i class="fas fa-tools text-2xl text-accent"></i>
            </div>
            <div>
                <p class="text-sm text-text-secondary">{{ __('Open Requests') }}</p>
                <p class="text-2xl font-bold text-text-primary">{{ $openRequests }}</p>
            </div>
        </div>

        <!-- Total Users Card -->
        <div class="bg-surface p-6 rounded-lg shadow-md flex items-center">
            <div class="bg-green-500/10 p-4 rounded-full mr-4">
                <i class="fas fa-users text-2xl text-green-500"></i>
            </div>
            <div>
                <p class="text-sm text-text-secondary">{{ __('Total Users') }}</p>
                <p class="text-2xl font-bold text-text-primary">{{ $totalUsers }}</p>
            </div>
        </div>

        <!-- Total Rooms Card -->
        <div class="bg-surface p-6 rounded-lg shadow-md flex items-center">
            <div class="bg-blue-500/10 p-4 rounded-full mr-4">
                <i class="fas fa-door-open text-2xl text-blue-500"></i>
            </div>
            <div>
                <p class="text-sm text-text-secondary">{{ __('Total Rooms') }}</p>
                <p class="text-2xl font-bold text-text-primary">{{ $totalRooms }}</p>
            </div>
        </div>
    </div>

    <!-- Assets by Status -->
    <div class="mt-8 bg-surface p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-text-primary mb-4">{{ __('Assets by Status') }}</h3>
        <div class="space-y-3">
            @foreach ($assetsByStatus as $status)
                <div>
                    <div class="flex justify-between mb-1">
                        <span class="text-sm font-medium text-text-secondary">{{ __($status->name) }}</span>
                        <span class="text-sm font-medium text-text-secondary">{{ $status->assets_count }}</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        @php
                            $percentage = $totalAssets > 0 ? ($status->assets_count / $totalAssets) * 100 : 0;
                        @endphp
                        <div class="bg-primary h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
