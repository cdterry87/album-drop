<x-guest-layout>
    <div
        class="hero min-h-screen"
        style="background-image:url({{ url('/images/mick-haupt-vGXHIh3URzc-unsplash.jpg') }})"
    >
        <div class="hero-overlay bg-opacity-80"></div>
        <div class="w-full">
            <x-jet-authentication-card>
                <x-slot name="logo">
                    <x-jet-authentication-card-logo class="text-white" />
                </x-slot>

                <x-jet-validation-errors class="mb-4" />

                <form
                    method="POST"
                    action="{{ route('register') }}"
                >
                    @csrf

                    <div>
                        <x-jet-label
                            for="name"
                            value="{{ __('Name') }}"
                        />
                        <x-jet-input
                            id="name"
                            class="block mt-1 w-full"
                            type="text"
                            name="name"
                            :value="old('name')"
                            required
                            autofocus
                            autocomplete="name"
                        />
                    </div>

                    <div class="mt-4">
                        <x-jet-label
                            for="email"
                            value="{{ __('Email') }}"
                        />
                        <x-jet-input
                            id="email"
                            class="block mt-1 w-full"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                        />
                    </div>

                    <div class="mt-4">
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
                            autocomplete="new-password"
                        />
                    </div>

                    <div class="mt-4">
                        <x-jet-label
                            for="password_confirmation"
                            value="{{ __('Confirm Password') }}"
                        />
                        <x-jet-input
                            id="password_confirmation"
                            class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                        />
                    </div>

                    <div class="flex items-center justify-center mt-4">
                        <a
                            class="underline text-sm text-gray-600 hover:text-gray-900"
                            href="{{ route('login') }}"
                        >
                            {{ __('Already registered?') }}
                        </a>

                        <x-jet-button class="ml-4">
                            {{ __('Register') }}
                        </x-jet-button>
                    </div>

                    <hr class="my-6" />

                    <div class="mb-4 text-center">
                        <a
                            href="{{ route('privacy-policy') }}"
                            class="font-bold"
                        >Privacy Policy</a>
                    </div>
                </form>
            </x-jet-authentication-card>
        </div>
    </div>
</x-guest-layout>
