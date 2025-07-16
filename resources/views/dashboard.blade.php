<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-neutral-900">
                    {{ __("You're logged in!") }}

                    <p class="mt-4">
                        {{ __('Welcome to the inventory system!') }}
                        {{ __('Here you will see summary information and quick links available based on your role.') }}
                    </p>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <!-- Total Assets Card -->
                        <div class="bg-primary-50 p-4 rounded-lg shadow flex flex-col items-center justify-center text-center">
                            <h3 class="font-semibold text-4xl text-primary-800">{{ $totalAssets }}</h3>
                            <p class="text-neutral-700 mt-2">{{ __('Total Assets') }}</p>
                            @can('view_assets')
                                <a href="{{ route('assets.index') }}" class="mt-3 inline-block text-primary-600 hover:underline text-sm">{{ __('View All') }}</a>
                            @endcan
                        </div>

                        <!-- Open Requests Card -->
                        <div class="bg-rose-50 p-4 rounded-lg shadow flex flex-col items-center justify-center text-center">
                            <h3 class="font-semibold text-4xl text-rose-800">{{ $openRequests }}</h3>
                            <p class="text-neutral-700 mt-2">{{ __('Open Requests') }}</p>
                            @can('view_requests')
                                <a href="{{ route('requests.index') }}" class="mt-3 inline-block text-rose-600 hover:underline text-sm">{{ __('View Requests') }}</a>
                            @endcan
                        </div>

                        <!-- Assets by Status Summary -->
                        <div class="bg-rose-50 p-4 rounded-lg shadow flex flex-col items-center justify-center text-center">
                            <h3 class="font-semibold text-4xl text-rose-800">{{ __('Assets by Status') }}</h3>
                            <ul class="list-disc list-inside text-neutral-700">
                                @foreach ($assetsByStatus as $status)
                                    <li>{{ __($status->name) }}: {{ $status->assets_count }}</li>
                                @endforeach
                            </ul>
                            @can('view_reports')
                                <a href="{{ route('reports.index') }}" class="mt-3 inline-block text-neutral-600 hover:underline text-sm">{{ __('View Reports') }}</a>
                            @endcan
                        </div>

                        <!-- Total Locations Card -->
                        <div class="bg-secondary-50 p-4 rounded-lg shadow flex flex-col items-center justify-center text-center">
                            <h3 class="font-semibold text-4xl text-secondary-800">{{ $totalLocations }}</h3>
                            <p class="text-neutral-700 mt-2">{{ __('Total Locations') }}</p>
                            @can('view_locations')
                                <a href="{{ route('locations.index') }}" class="mt-3 inline-block text-secondary-600 hover:underline text-sm">{{ __('View All') }}</a>
                            @endcan
                        </div>

                        <!-- Total Floors Card -->
                        <div class="bg-secondary-50 p-4 rounded-lg shadow flex flex-col items-center justify-center text-center">
                            <h3 class="font-semibold text-4xl text-secondary-800">{{ $totalFloors }}</h3>
                            <p class="text-neutral-700 mt-2">{{ __('Total Floors') }}</p>
                            @can('view_locations')
                                <a href="{{ route('floors.index') }}" class="mt-3 inline-block text-secondary-600 hover:underline text-sm">{{ __('View All') }}</a>
                            @endcan
                        </div>

                        <!-- Total Rooms Card -->
                        <div class="bg-secondary-50 p-4 rounded-lg shadow flex flex-col items-center justify-center text-center">
                            <h3 class="font-semibold text-4xl text-secondary-800">{{ $totalRooms }}</h3>
                            <p class="text-neutral-700 mt-2">{{ __('Total Rooms') }}</p>
                            @can('view_locations')
                                <a href="{{ route('rooms.index') }}" class="mt-3 inline-block text-secondary-600 hover:underline text-sm">{{ __('View All') }}</a>
                            @endcan
                        </div>

                        <!-- Total Users Card -->
                        <div class="bg-primary-50 p-4 rounded-lg shadow flex flex-col items-center justify-center text-center">
                            <h3 class="font-semibold text-4xl text-primary-800">{{ $totalUsers }}</h3>
                            <p class="text-neutral-700 mt-2">{{ __('Total Users') }}</p>
                            @can('view_users')
                                <a href="{{ route('users.index') }}" class="mt-3 inline-block text-primary-600 hover:underline text-sm">{{ __('View All') }}</a>
                            @endcan
                        </div>


                        {{-- Quick Links / Action Cards (can be moved or removed if dashboard is purely summary) --}}
                        {{-- @can('view_assets')
                        <div class="bg-primary-50 p-4 rounded-lg shadow">
                            <h3 class="font-semibold text-lg mb-2 text-primary-800">{{ __('Inventory Management') }}</h3>
                            <p class="text-neutral-700">{{ __('View, add, edit, and delete equipment.') }}</p>
                            <a href="{{ route('assets.index') }}" class="mt-3 inline-block text-primary-600 hover:underline">{{ __('Go to Inventory') }}</a>
                        </div>
                        @endcan

                        @can('view_users')
                        <div class="bg-secondary-50 p-4 rounded-lg shadow">
                            <h3 class="font-semibold text-lg mb-2 text-secondary-800">{{ __('Responsible Persons') }}</h3>
                            <p class="text-neutral-700">{{ __('Manage employees and their assigned equipment.') }}</p>
                            <a href="{{ route('users.index') }}" class="mt-3 inline-block text-secondary-600 hover:underline">{{ __('Manage Users') }}</a>
                        </div>
                        @endcan

                        @can('view_requests')
                        <div class="bg-neutral-200 p-4 rounded-lg shadow">
                            <h3 class="font-semibold text-lg mb-2 text-neutral-800">{{ __('Requests & Service') }}</h3>
                            <p class="text-neutral-700">{{ __('View and process repair and maintenance requests.') }}</p>
                            <a href="{{ route('requests.index') }}" class="mt-3 inline-block text-neutral-600 hover:underline">{{ __('View Requests') }}</a>
                        </div>
                        @endcan --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
