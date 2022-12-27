<div class="card w-full h-64 bg-base-300 shadow-xl image-full">
    <figure>
        <img
            src="{{ $image }}"
            alt="{{ $name }}"
        />
    </figure>
    <div class="card-body h-full flex justify-between gap-6">
        <div class="space-y-1">
            <h2 class="card-title text-2xl">{{ $name }}</h2>
            <h3 class="text-sm">by <span class="text-xl">{{ $artist }}</span></h3>
            <h4 class="text-xs">on {{ $releaseDate->format('F j, Y') }}</h4>
        </div>
        <div class="card-actions justify-end">
            <a
                href="{{ $url }}"
                class="btn btn-spotify"
                target="_blank"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    fill="currentColor"
                    class="w-6 h-6"
                    viewBox="0 0 16 16"
                >
                    <path
                        d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zm3.669 11.538a.498.498 0 0 1-.686.165c-1.879-1.147-4.243-1.407-7.028-.77a.499.499 0 0 1-.222-.973c3.048-.696 5.662-.397 7.77.892a.5.5 0 0 1 .166.686zm.979-2.178a.624.624 0 0 1-.858.205c-2.15-1.321-5.428-1.704-7.972-.932a.625.625 0 0 1-.362-1.194c2.905-.881 6.517-.454 8.986 1.063a.624.624 0 0 1 .206.858zm.084-2.268C10.154 5.56 5.9 5.419 3.438 6.166a.748.748 0 1 1-.434-1.432c2.825-.857 7.523-.692 10.492 1.07a.747.747 0 1 1-.764 1.288z"
                    />
                </svg>
            </a>
        </div>
    </div>
</div>
