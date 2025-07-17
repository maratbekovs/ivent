<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-text-primary leading-tight">
            {{ __('Edit Role') }}: {{ __($role->name) }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="bg-surface p-6 md:p-8 rounded-lg shadow-md">
            <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                @csrf
                @method('PUT')
                <div>
                    <h3 class="text-lg font-medium text-text-primary mb-4">{{ __('Permissions') }}</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach ($permissions as $permission)
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary"
                                @checked($role->hasPermissionTo($permission->name))>
                                <span class="ms-2 text-sm text-gray-600">{{ __($permission->name) }}</span>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('permissions')" class="mt-2" />
                </div>
                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('admin.roles.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">{{ __('Cancel') }}</a>
                    <x-primary-button>{{ __('Update Role') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>