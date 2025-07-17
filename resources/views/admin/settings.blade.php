<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-text-primary leading-tight">
            {{ __('Admin Panel') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Manage Roles Card -->
        @can('manage_roles')
        <a href="{{ route('admin.roles.index') }}" class="block p-6 bg-surface rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
            <div class="flex items-center">
                <div class="bg-primary/10 p-4 rounded-full mr-4">
                    <i class="fas fa-user-shield text-2xl text-primary"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-lg text-text-primary">{{ __('Manage Roles') }}</h3>
                    <p class="text-sm text-text-secondary mt-1">{{ __('Create and edit roles, assign permissions.') }}</p>
                </div>
            </div>
        </a>
        @endcan
        
        {{-- Карточка для управления правами будет добавлена, когда появится соответствующий функционал --}}
    </div>
</x-app-layout>