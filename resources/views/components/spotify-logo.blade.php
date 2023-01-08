@props(['imageHeight' => 'h-12'])

<div {{ $attributes->merge([
    'class' => 'text-white',
]) }}>
    Powered by
    <br>
    <a
        href="https://spotify.com"
        class="text-white underline mt-2 inline-block"
    >
        <img
            src="{{ url('/images/Spotify_Logo_RGB_White.png') }}"
            alt="Spotify Logo"
            title="Spotify Logo"
            class="{{ $imageHeight }}"
        />
    </a>
</div>
