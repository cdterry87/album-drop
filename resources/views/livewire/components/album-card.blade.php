<div class="card w-full h-64 bg-base-300 shadow-xl image-full">
    <figure>
        <img
            src="{{ $image }}"
            alt="{{ $name }}"
        />
    </figure>
    <div class="card-body h-full flex justify-between gap-6">
        <div class="space-y-1">
            <h2
                class="card-title text-2xl"
                title="{{ $name }}"
                alt="{{ $name }}"
            >
                {{ Str::limit($name, 30) }}
            </h2>
            @if ($artist)
                <h3
                    class="text-sm"
                    title="{{ $artist }}"
                    alt="{{ $artist }}"
                >
                    <span class="text-xl">{{ Str::limit($artist, 30) }}</span>
                </h3>
            @endif
            <h4 class="text-xs">Released: {{ $releaseDate->format('F j, Y') }}</h4>
        </div>
        <div class="card-actions justify-end">
            <a
                href="{{ $url }}"
                class="btn btn-spotify"
                target="_blank"
            >
                <x-icons.spotify />
            </a>
        </div>
    </div>
</div>
