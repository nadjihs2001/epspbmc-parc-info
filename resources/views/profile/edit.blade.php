<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section class="max-w-xl">
                    <header>
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Profile Information') }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Update your account name and email address.') }}</p>
                    </header>

                    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('patch')

                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>

                            @if (session('status') === 'profile-updated')
                                <p class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                            @endif
                        </div>
                    </form>
                </section>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section class="max-w-xl">
                    <header>
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Update Password') }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>
                    </header>

                    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('put')

                        <div>
                            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="update_password_password" :value="__('New Password')" />
                            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
                            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>

                            @if (session('status') === 'password-updated')
                                <p class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                            @endif
                        </div>
                    </form>
                </section>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <section class="max-w-xl">
                    <header>
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Delete Account') }}</h3>
                        <p class="mt-1 text-sm text-gray-600">{{ __('Once your account is deleted, all of its resources and data will be permanently deleted.') }}</p>
                    </header>

                    <form method="post" action="{{ route('profile.destroy') }}" class="mt-6 space-y-6">
                        @csrf
                        @method('delete')

                        <div>
                            <x-input-label for="delete_password" :value="__('Current Password')" />
                            <x-text-input id="delete_password" name="password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <x-danger-button>{{ __('Delete Account') }}</x-danger-button>
                    </form>
                </section>
            </div>
        </div>
    </div>
</x-app-layout>
