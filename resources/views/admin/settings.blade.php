    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
                {{ __('Admin Settings') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-neutral-900">
                        <h3 class="text-lg font-medium text-neutral-900 mb-4">{{ __('Admin Dashboard') }}</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @can('manage_roles')
                            <div class="bg-primary-50 p-6 rounded-lg shadow">
                                <h4 class="font-semibold text-md text-primary-800 mb-2">{{ __('Manage Roles') }}</h4>
                                <p class="text-neutral-700">{{ __('Create, edit, and assign roles to users.') }}</p>
                                <a href="{{ route('admin.roles.index') }}" class="mt-3 inline-block text-primary-600 hover:underline">{{ __('Go to Roles Management') }}</a>
                            </div>
                            @endcan

                            {{-- УДАЛЕНА ССЫЛКА НА ПРОСМОТР РАЗРЕШЕНИЙ --}}
                            {{-- @can('manage_permissions')
                            <div class="bg-secondary-50 p-6 rounded-lg shadow">
                                <h4 class="font-semibold text-md text-secondary-800 mb-2">{{ __('View Permissions') }}</h4>
                                <p class="text-neutral-700">{{ __('View all available permissions in the system.') }}</p>
                                <a href="{{ route('admin.permissions.index') }}" class="mt-3 inline-block text-secondary-600 hover:underline">{{ __('Go to Permissions List') }}</a>
                            </div>
                            @endcan --}}

                            {{-- Добавьте другие административные настройки здесь --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
    