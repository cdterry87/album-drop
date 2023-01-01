@props(['name', 'image', 'artist'])

<div class="card w-full h-44 bg-base-300 shadow-xl image-full">
    <figure>
        <img
            src="{{ $image }}"
            alt="{{ $name }}"
        />
    </figure>
    <div class="card-body h-full flex justify-between gap-6">
        <div class="space-y-1">
            <h2
                class="card-title text-lg"
                title="{{ $name }}"
                alt="{{ $name }}"
            >
                {{ Str::limit($name, 30) }}
            </h2>
            <h3
                class="text-sm"
                title="{{ $artist }}"
                alt="{{ $artist }}"
            >
                {{ Str::limit($artist, 30) }}
            </h3>
        </div>
    </div>
</div>
