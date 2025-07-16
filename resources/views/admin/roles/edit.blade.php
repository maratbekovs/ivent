<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Edit Role Permissions') }}: {{ __($role->name) }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-neutral-900">
                    <form method="POST" action="{{ route('admin.roles.update', $role) }}">
                        @csrf
                        @method('PUT')

                        <div>
                            <x-input-label for="name" :value="__('Role Name')" />
                            <x-text-input id="name" class="block mt-1 w-full bg-neutral-100" type="text" name="name" :value="__($role->name)" readonly />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="permissions" :value="__('Permissions')" />
                            {{-- ИСПРАВЛЕНО: Убедимся, что grid классы применяются корректно --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-2 p-4 border border-neutral-200 rounded-md bg-neutral-50">
                                @foreach ($permissions as $permission)
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" class="rounded border-neutral-300 text-primary-600 shadow-sm focus:ring-primary-500"
                                            {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                        <span class="ms-2 text-sm text-neutral-600">{{ __($permission->name) }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('permissions')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Update Role') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
