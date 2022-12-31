<div>
    <x-header
        title="Search Artists"
        subtitle="Track artists to receive notifications when they release new albums."
    >
        @if ($isSearchComplete)
            <button
                class="btn btn-secondary gap-2"
                wire:click.prevent="clearSearch"
            >
                <x-icons.cancel />
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
                        <x-icons.search />
                        <span class="hidden md:inline">
                            Search
                        </span>
                    </button>
                </div>
            </form>
        @endif
    </x-header>

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
