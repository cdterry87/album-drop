<x-guest-layout>
    <div
        class="hero min-h-screen"
        style="background-image:url({{ url('/images/mick-haupt-jzPjVrT2Xqg-unsplash.jpg') }})"
    >
        <div class="hero-overlay bg-opacity-80"></div>
        <div class="w-full">
            <x-jet-authentication-card>
                <x-slot name="logo">
                    <x-jet-authentication-card-logo class="text-white" />
                </x-slot>

                <x-jet-validation-errors class="mb-4" />

                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('login') }}"
                >
                    @csrf

                    <div>
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
                            autofocus
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
                            autocomplete="current-password"
                        />
                    </div>

                    <div class="block mt-4">
                        <label
                            for="remember_me"
                            class="flex items-center"
                        >
                            <x-jet-checkbox
                                id="remember_me"
                                name="remember"
                            />
                            <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                        </label>
                    </div>

                    <div class="flex items-center justify-center mt-4">
                        @if (Route::has('password.request'))
                            <a
                                class="underline text-sm text-gray-600 hover:text-gray-900"
                                href="{{ route('password.request') }}"
                            >
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif

                        <x-jet-button class="ml-4">
                            {{ __('Log in') }}
                        </x-jet-button>
                    </div>

                    <hr class="my-6">

                    <div class="flex flex-col gap-6 mb-4">
                        <div class="flex flex-col items-center justify-around text-sm text-gray-600 gap-2">
                            <a
                                href="{{ route('login.spotify') }}"
                                class="btn btn-spotify flex items-center gap-1"
                            >
                                <x-icons.spotify />
                                <span>{{ __('Login with Spotify') }}</span>
                            </a>
                            <span>
                                or
                            </span>
                            <a
                                class="underline hover:text-gray-900"
                                href="{{ route('register') }}"
                            >
                                {{ __('Register for an Account!') }}
                            </a>
                        </div>
                    </div>
                </form>
            </x-jet-authentication-card>
        </div>
    </div>
</x-guest-layout>
