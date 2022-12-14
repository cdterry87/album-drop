<x-guest-layout>
    <div
        class="hero min-h-screen"
        style="background-image:url({{ url('/images/mick-haupt--EoUIP5q2q8-unsplash.jpg') }})"
    >
        <div class="hero-overlay bg-opacity-80"></div>
        <div class="w-full">
            <x-jet-authentication-card>
                <x-slot name="logo">
                    <x-jet-authentication-card-logo class="text-white" />
                </x-slot>

                <div class="mb-4 text-sm text-gray-600">
                    {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                </div>

                <x-jet-validation-errors class="mb-4" />

                <form
                    method="POST"
                    action="{{ route('password.confirm') }}"
                >
                    @csrf

                    <div>
                        <x-jet-label
                            for="password"
                            value="{{ __('Password') }}"
                        />
                        <x-jet-input
                            id="password"
                            class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            autofocus
                        />
                    </div>

                    <div class="flex justify-end mt-4">
                        <x-jet-button class="ml-4">
                            {{ __('Confirm') }}
                        </x-jet-button>
                    </div>
                </form>
            </x-jet-authentication-card>
        </div>
    </div>
</x-guest-layout>
