    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
                {{ __('Manage Roles') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-neutral-900">
                        <h3 class="text-lg font-medium text-neutral-900 mb-4">{{ __('List of Roles') }}</h3>

                        @if ($roles->isEmpty())
                            <p>{{ __('No roles found.') }}</p>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-neutral-200">
                                    <thead class="bg-neutral-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                                {{ __('Name') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                                {{ __('Guard Name') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                                {{ __('Permissions Count') }}
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                                {{ __('Actions') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-neutral-200">
                                        @foreach ($roles as $role)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                                                    {{ __($role->name) }} {{-- Перевод имени роли --}}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                                    {{ $role->guard_name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                                    {{ $role->permissions->count() }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                                    <a href="{{ route('admin.roles.edit', $role) }}" class="text-primary-600 hover:text-primary-900 mr-3">
                                                        {{ __('Edit Permissions') }}
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
    