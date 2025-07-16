    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
                {{ __('View Permissions') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-neutral-900">
                        <h3 class="text-lg font-medium text-neutral-900 mb-4">{{ __('List of All Permissions') }}</h3>

                        @if ($permissions->isEmpty())
                            <p>{{ __('No permissions found.') }}</p>
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
                                                {{ __('Created At') }}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-neutral-200">
                                        @foreach ($permissions as $permission)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                                                    {{ __($permission->name) }} {{-- Перевод имени разрешения --}}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                                    {{ $permission->guard_name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                                    {{ $permission->created_at->format('Y-m-d H:i') }}
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
    