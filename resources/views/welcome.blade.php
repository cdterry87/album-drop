<x-guest-layout>
    <div class="navbar bg-base-100 absolute top-0 left-0 bg-opacity-0">
        <div class="w-full navbar-end">
            <ul class="menu menu-horizontal px-1">
                @guest
                    <li>
                        <a
                            href="{{ route('login') }}"
                            class="text-white underline"
                        >Log in</a>
                    </li>
                    @if (Route::has('register'))
                        <li>
                            <a
                                href="{{ route('register') }}"
                                class="text-white underline"
                            >Register</a>
                        </li>
                    @endif
                @endguest
            </ul>
        </div>
    </div>
    <div
        class="hero min-h-screen"
        style="background-image:url({{ url('/images/clay-banks-fEVaiLwWvlU-unsplash.jpg') }})"
    >
        <div class="hero-overlay bg-opacity-80"></div>
        <div class="hero-content text-center text-white">
            <div class="max-w-lg">
                <h1 class="mb-5 text-5xl font-bold">{{ env('APP_NAME') }}</h1>
                <p class="mb-12">Track artists and we'll let you know when their next album drops!</p>
                <a
                    role="button"
                    class="btn btn-primary"
                    href="{{ route('dashboard') }}"
                >Get Started!
                </a>
                <div class="text-white mt-12">
                    Powered by
                    <br>
                    <a
                        href="https://spotify.com"
                        class="text-white underline mt-2 inline-block"
                    >
                        <img
                            src="{{ url('/images/Spotify_Logo_RGB_White.png') }}"
                            alt="Spotify"
                            class="h-12"
                        />
                    </a>
                </div>
            </div>
        </div>
    </div>


</x-guest-layout>
