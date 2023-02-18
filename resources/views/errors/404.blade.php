<x-guest-layout without-navigation>
    <div
        class="hero min-h-screen"
        style="background-image:url({{ url('/images/clay-banks-fEVaiLwWvlU-unsplash.jpg') }})"
    >
        <div class="hero-overlay bg-opacity-80"></div>
        <div class="hero-content text-center text-white">
            <div class="max-w-lg mt-10 lg:mt-6">
                <x-jet-application-logo />

                <p class="mt-6 mb-12 max-w-sm">
                    Sorry, we couldn't find the page you were looking for.
                </p>

                <a
                    role="button"
                    class="btn btn-primary"
                    href="{{ route('home') }}"
                >
                    Back to home page
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
