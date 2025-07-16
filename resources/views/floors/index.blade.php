<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Floors') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-neutral-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-neutral-900">{{ __('List of Floors') }}</h3>
                        <a href="{{ route('floors.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-neutral-900 uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Add New Floor') }}
                        </a>
                    </div>

                    @if ($floors->isEmpty())
                        <p>{{ __('No floors found.') }}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-neutral-200">
                                <thead class="bg-neutral-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                            {{ __('Name') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                            {{ __('Location') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                            {{ __('Description') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                            {{ __('Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-neutral-200">
                                    @foreach ($floors as $floor)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                                                {{ $floor->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                                {{ $floor->location->name ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                                {{ $floor->description ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                                <a href="{{ route('floors.edit', $floor) }}" class="text-primary-600 hover:text-primary-900 mr-3">
                                                    {{ __('Edit') }}
                                                </a>
                                                <form action="{{ route('floors.destroy', $floor) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-600 hover:text-rose-900" onclick="return confirm('{{ __('Are you sure you want to delete this floor?') }}')">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $floors->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
