<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        @if (! auth()->user()->force_password_change)
            <div>
                <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                <div class="relative mt-1">
                    <x-text-input id="update_password_current_password" name="current_password" type="password" class="block w-full pr-12" autocomplete="current-password" />
                    @include('partials.password-visibility-toggle', ['inputId' => 'update_password_current_password'])
                </div>
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>
        @else
            <p class="rounded-md border border-blue-300/50 bg-blue-500/10 px-3 py-2 text-sm text-blue-200">
                Zmień hasło startowe przy pierwszym logowaniu.
            </p>
        @endif

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <div class="relative mt-1">
                <x-text-input id="update_password_password" name="password" type="password" class="block w-full pr-12" autocomplete="new-password" />
                @include('partials.password-visibility-toggle', ['inputId' => 'update_password_password'])
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            @include('partials.password-rules', ['passwordId' => 'update_password_password', 'confirmationId' => 'update_password_password_confirmation'])
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <div class="relative mt-1">
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full pr-12" autocomplete="new-password" />
                @include('partials.password-visibility-toggle', ['inputId' => 'update_password_password_confirmation'])
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>




