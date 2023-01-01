@props(['image', 'name'])

<div class="card w-full h-44 bg-base-300 shadow-xl image-full">
    <figure>
        <img
            src="{{ $image }}"
            alt="{{ $name }}"
        />
    </figure>
    <div class="card-body h-full flex justify-between gap-6">
        <h2
            class="card-title text-lg"
            title="{{ $name }}"
            alt="{{ $name }}"
        >
            {{ Str::limit($name, 30) }}
        </h2>
    </div>
</div>
