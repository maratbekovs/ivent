<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-2xl text-text-primary leading-tight">
                {{ __('Documents') }}
            </h2>
            @can('create_documents')
                <a href="{{ route('documents.create') }}" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-dark">
                    <i class="fas fa-plus mr-2"></i>
                    {{ __('Create New Document') }}
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="bg-surface p-6 rounded-lg shadow-md">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('Title') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('Type') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('Created By') }}</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-text-secondary uppercase tracking-wider">{{ __('Date') }}</th>
                        <th scope="col" class="relative px-6 py-3"><span class="sr-only">{{ __('Actions') }}</span></th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($documents as $document)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-text-primary">{{ Str::limit($document->title, 40) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">{{ __($document->type) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">{{ $document->creator->name ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary">{{ $document->created_at->format('Y-m-d') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-4">
                                    @if($document->file_path)
                                        <a href="{{ route('documents.download', $document) }}" class="text-gray-400 hover:text-primary" title="{{ __('Download') }}">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    @endif
                                    @can('delete_documents')
                                    <form action="{{ route('documents.destroy', $document) }}" method="POST" class="inline" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600" title="{{ __('Delete') }}"><i class="fas fa-trash"></i></button>
                                    </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">{{ __('No documents found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($documents->hasPages())
            <div class="mt-4">
                {{ $documents->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
