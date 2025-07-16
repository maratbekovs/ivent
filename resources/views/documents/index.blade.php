<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-neutral-800 leading-tight">
            {{ __('Document Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-neutral-900">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-neutral-900">{{ __('List of Documents') }}</h3>
                        @can('create_documents')
                        <a href="{{ route('documents.create') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-neutral-900 uppercase tracking-widest hover:bg-primary-700 focus:bg-primary-700 active:bg-primary-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Create New Document') }}
                        </a>
                        @endcan
                    </div>

                    @if ($documents->isEmpty())
                        <p>{{ __('No documents found.') }}</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-neutral-200">
                                <thead class="bg-neutral-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                            {{ __('ID') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                            {{ __('Document Type') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                            {{ __('Related Asset') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                            {{ __('Related Request') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                            {{ __('Creator') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                            {{ __('Signed By') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-start text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                            {{ __('Signed At') }}
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-end text-xs font-medium text-neutral-500 uppercase tracking-wider">
                                            {{ __('Actions') }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-neutral-200">
                                    @foreach ($documents as $document)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900">
                                                {{ $document->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                                {{ __('document_type_' . $document->document_type) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                                {{ $document->asset->inventory_number ?? $document->asset->serial_number ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                                {{ $document->relatedRequest->id ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                                {{ $document->creator->name ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                                {{ $document->signedBy->name ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-500">
                                                {{ $document->signed_at?->format('Y-m-d H:i') ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-end text-sm font-medium">
                                                @can('edit_documents')
                                                <a href="{{ route('documents.edit', $document) }}" class="text-primary-600 hover:text-primary-900 mr-3">
                                                    {{ __('Edit') }}
                                                </a>
                                                @endcan
                                                @can('sign_documents')
                                                @if (!$document->signed_at)
                                                <form action="{{ route('documents.sign', $document) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="text-secondary-600 hover:text-secondary-900 mr-3">
                                                        {{ __('Sign') }}
                                                    </button>
                                                </form>
                                                @endif
                                                @endcan
                                                @can('delete_documents')
                                                <form action="{{ route('documents.destroy', $document) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-600 hover:text-rose-900" onclick="return confirm('{{ __('Are you sure you want to delete this document?') }}')">
                                                        {{ __('Delete') }}
                                                    </button>
                                                </form>
                                                @endcan
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $documents->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
