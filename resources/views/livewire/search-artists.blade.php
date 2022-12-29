<div>
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <h2 class="font-bold text-2xl">Search Artists</h2>
        @if ($isSearchComplete)
            <button
                class="btn btn-secondary gap-2"
                wire:click.prevent="clearSearch"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-6 h-6"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"
                    />
                </svg>
                Clear Search
            </button>
        @else
            <form wire:submit.prevent="search">
                <div class="flex items-center gap-2">
                    <input
                        type="text"
                        placeholder="Search for an artist"
                        class="input input-bordered input-primary lg:w-full max-w-xs outline-none ring-0"
                        wire:model.defer="search"
                    />
                    <button
                        class="btn btn-primary gap-2"
                        wire:click.prevent="search"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="w-6 h-6"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"
                            />
                        </svg>
                        <span class="hidden md:inline">
                            Search
                        </span>
                    </button>
                </div>
            </form>
        @endif
    </div>

    <hr class="my-8 border-gray-600">

    <div class="text-center">
        @if ($isSearchComplete)
            @if ($results)
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
                    @foreach ($results as $result)
                        <livewire:components.artist-card
                            :name="$result['name']"
                            :image="$result['images'][0]['url'] ?? null"
                            :url="$result['external_urls']['spotify']"
                            :spotify-id="$result['id']"
                            wire:key="{{ $result['id'] }}"
                        />
                    @endforeach
                </div>
            @else
                <div>
                    <h3 class="font-bold lg">
                        No artists were found with that name. Try a different search.
                    </h3>
                </div>
            @endif
        @else
            <div>
                <h3 class="font-bold lg">
                    Search for an artist and your results will appear here.
                </h3>
            </div>
        @endif
    </div>
</div>
