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
                        @can('view_assets')
                        <div class="bg-primary-50 p-4 rounded-lg shadow"> {{-- Светлый фон для карточки --}}
                            <h3 class="font-semibold text-lg mb-2 text-primary-800">Управление инвентарем</h3>
                            <p class="text-neutral-700">{{ __('View, add, edit, and delete equipment.') }}</p>
                            <a href="{{ route('assets.index') }}" class="mt-3 inline-block text-primary-600 hover:underline">{{ __('Go to Inventory') }}</a>
                        </div>
                        @endcan

                        @can('view_users')
                        <div class="bg-secondary-50 p-4 rounded-lg shadow"> {{-- Светлый фон для карточки --}}
                            <h3 class="font-semibold text-lg mb-2 text-secondary-800">Ответственные лица</h3>
                            <p class="text-neutral-700">{{ __('Manage employees and their assigned equipment.') }}</p>
                            <a href="{{ route('users.index') }}" class="mt-3 inline-block text-secondary-600 hover:underline">{{ __('Manage Users') }}</a>
                        </div>
                        @endcan

                        @can('view_requests')
                        <div class="bg-neutral-200 p-4 rounded-lg shadow"> {{-- Светлый фон для карточки --}}
                            <h3 class="font-semibold text-lg mb-2 text-neutral-800">Заявки и обслуживание</h3>
                            <p class="text-neutral-700">{{ __('View and process repair and maintenance requests.') }}</p>
                            <a href="{{ route('requests.index') }}" class="mt-3 inline-block text-neutral-600 hover:underline">{{ __('View Requests') }}</a>
                        </div>
                        @endcan
                        {{-- Добавьте другие блоки по мере разработки функционала --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
