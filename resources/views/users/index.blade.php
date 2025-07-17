<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-text-primary leading-tight">
                {{ __('Users') }}
            </h2>
            @can('create_users')
                <a href="{{ route('users.create') }}" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-accent focus:ring-offset-2 transition ease-in-out duration-150">
                    <i class="fas fa-plus mr-2"></i>
                    {{ __('Add New User') }}
                </a>
            @endcan
        </div>
    </x-slot>

    <!-- Форма для поиска и фильтрации -->
    <div class="mb-6 bg-surface p-4 rounded-lg shadow-md">
        <form action="{{ route('users.index') }}" method="GET">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label for="search" class="sr-only">{{ __('Search') }}</label>
                    <input type="text" name="search" id="search" placeholder="{{ __('Search by name, email, position...') }}" value="{{ request('search') }}" class="block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm text-sm">
                </div>
                <div>
                    <label for="role" class="sr-only">{{ __('Role') }}</label>
                    <select name="role" id="role" class="block w-full border-gray-300 focus:border-primary focus:ring-primary rounded-md shadow-sm text-sm">
                        <option value="">{{ __('All Roles') }}</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" @selected(request('role') == $role->name)>{{ __($role->name) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <a href="{{ route('users.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4 py-2">{{ __('Reset') }}</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary/80 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary focus:outline-none">
                    {{ __('Filter') }}
                </button>
            </div>
        </form>
    </div>

    <div class="bg-surface p-6 rounded-lg shadow-md">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('Name') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('Email') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('Position') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('Roles') }}</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">{{ __('Actions') }}</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-text-primary">{{ $user->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">{{ $user->email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">{{ $user->position ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">
                                @foreach($user->getRoleNames() as $role)
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-primary/10 text-primary">{{ __($role) }}</span>
                                @endforeach
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    @can('edit_users')
                                    <a href="{{ route('users.edit', $user) }}" class="text-gray-400 hover:text-primary" title="{{ __('Edit') }}"><i class="fas fa-edit"></i></a>
                                    @endcan
                                    @can('delete_users')
                                        @if(auth()->id() !== $user->id)
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-400 hover:text-red-600" title="{{ __('Delete') }}"><i class="fas fa-trash"></i></button>
                                        </form>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ __('No users found matching your criteria.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users->hasPages())
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
