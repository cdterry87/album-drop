<x-guest-layout>
    <div
        class="hero min-h-screen"
        style="background-image:url({{ url('/images/clay-banks-fEVaiLwWvlU-unsplash.jpg') }})"
    >
        <div class="hero-overlay bg-opacity-80"></div>
        <div class="hero-content text-center text-white">
            <div class="max-w-lg mt-10 lg:mt-6">
                <x-jet-application-logo />
                <p class="mt-6 mb-12 max-w-sm">
                    Track your favorite artists and we'll let you know when their next album drops!
                </p>
                <a
                    role="button"
                    class="btn btn-primary"
                    href="{{ route('home') }}"
                >Get Started!
                </a>

                <x-spotify-logo class="mt-12" />

                <div class="mt-16">
                    <a
                        href="{{ route('privacy-policy') }}"
                        class="text-white font-bold"
                    >Privacy Policy</a>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
