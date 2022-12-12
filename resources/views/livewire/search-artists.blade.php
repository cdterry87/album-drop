<div>
    <form
        wire:submit.prevent="search"
        class="flex items-center justify-center gap-2"
    >
        <input
            type="text"
            placeholder="Search for an artist"
            class="input input-bordered input-accent lg:w-full max-w-xs"
            wire:model.defer="search"
        />
        <button
            class="btn btn-accent gap-2"
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
    </form>

    <hr class="my-8 border-gray-600">

    <div class="text-center">
        @if ($isSearchComplete)
            @if ($results)
                <div class="flex flex-col gap-10 py-8">
                    @foreach ($results as $result)
                        <x-artist-card
                            :title="$result['name'] ?? 'N/A'"
                            :image="$result['images'][2]['url'] ?? null"
                            :id="$result['id'] ?? null"
                        />
                    @endforeach
                </div>
            @else
                <div>
                    <h3 class="font-bold lg">
                        No results found. Try a different search.
                    </h3>
                </div>
            @endif
        @else
            <div>
                <h3 class="font-bold lg">
                    Search for an artist and your results will appear here.
                </h3>

                {{-- For Testing  --}}
                {{-- <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-1 gap-8 py-8">
                    <x-artist-card
                        :title="'AFI'"
                        :image="'https://i.scdn.co/image/ab6761610000e5ebbdecf4762da0117e96e03fb8'"
                        :id="'12345'"
                    />
                    <x-artist-card
                        :title="'AFI'"
                        :image="'https://i.scdn.co/image/ab6761610000e5ebbdecf4762da0117e96e03fb8'"
                        :id="'12345'"
                    />
                    <x-artist-card
                        :title="'AFI'"
                        :image="'https://i.scdn.co/image/ab6761610000e5ebbdecf4762da0117e96e03fb8'"
                        :id="'12345'"
                    />
                    <x-artist-card
                        :title="'AFI'"
                        :image="'https://i.scdn.co/image/ab6761610000e5ebbdecf4762da0117e96e03fb8'"
                        :id="'12345'"
                    />
                </div> --}}
            </div>
        @endif
    </div>
</div>
