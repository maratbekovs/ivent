<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-text-primary leading-tight">{{ __('Edit User') }}</h2>
    </x-slot>
    <div class="max-w-4xl mx-auto">
        <div class="bg-surface p-6 md:p-8 rounded-lg shadow-md">
            <form method="POST" action="{{ route('users.update', $user) }}">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="position" :value="__('Position')" />
                        <x-text-input id="position" class="block mt-1 w-full" type="text" name="position" :value="old('position', $user->position)" />
                        <x-input-error :messages="$errors->get('position')" class="mt-2" />
                    </div>
                    <div>
                        <x-input-label for="phone" :value="__('Phone')" />
                        <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $user->phone)" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>
                    <div class="md:col-span-2">
                        <x-input-label for="roles" :value="__('Roles')" />
                        <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($roles as $role)
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="rounded border-gray-300 text-primary shadow-sm focus:ring-primary" @checked(in_array($role->name, old('roles', $user->getRoleNames()->toArray())))>
                                    <span class="ms-2 text-sm text-gray-600">{{ __($role->name) }}</span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('roles')" class="mt-2" />
                    </div>
                </div>
                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('users.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">{{ __('Cancel') }}</a>
                    <x-primary-button>{{ __('Update User') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>