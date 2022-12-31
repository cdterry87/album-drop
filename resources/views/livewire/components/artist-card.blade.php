<div class="card w-full h-64 bg-base-300 shadow-xl image-full">
    <figure>
        <img
            src="{{ $image }}"
            alt="{{ $name }}"
        />
    </figure>
    <div class="card-body h-full flex justify-between gap-6">
        <h2
            class="card-title text-3xl"
            title="{{ $name }}"
            alt="{{ $name }}"
        >
            {{ Str::limit($name, 30) }}
        </h2>
        <div class="card-actions justify-end gap-3">
            @if ($hasAlbums)
                <a
                    href="{{ route('artist-albums', $spotifyId) }}"
                    class="btn btn-accent"
                    title="View Albums"
                    alt="View Albums"
                >
                    <x-icons.album />
                </a>
            @endif
            <a
                href="{{ $url }}"
                class="btn btn-spotify"
                target="_blank"
                title="Open Spotify for this Artist"
                alt="Open Spotify for this Artist"
            >
                <x-icons.spotify />
            </a>

            @if ($isTracked)
                <button
                    class="btn btn-secondary"
                    wire:click.prevent="untrackArtist"
                    title="Stop Tracking Artist"
                    alt="Stop Tracking Artist"
                >
                    <x-icons.cancel />
                </button>
            @else
                <button
                    class="btn btn-primary"
                    wire:click.prevent="trackArtist"
                    title="Track Artist"
                    alt="Track Artist"
                >
                    <x-icons.heart />
                </button>
            @endif
        </div>
    </div>
</div>
