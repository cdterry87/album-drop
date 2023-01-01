<div>
    <x-header
        title="Search Artists"
        subtitle="Track artists to receive notifications when they release new albums."
    >
        @if ($isSearchComplete)
            <x-inputs.button
                class="btn-secondary"
                wire:click.prevent="clearSearch"
            >
                <div class="flex items-center gap-2">
                    <x-icons.cancel />
                    Clear Search
                </div>
            </x-inputs.button>
        @else
            <form wire:submit.prevent="search">
                <div class="flex items-center gap-2">
                    <x-inputs.text
                        placeholder="Search for an artist..."
                        wire:model.defer="search"
                    />
                    <x-inputs.button
                        class="btn-primary"
                        wire:click.prevent="search"
                    >
                        <div class="flex items-center gap-2">
                            <x-icons.search />
                            <span class="hidden md:inline">
                                Search
                            </span>
                        </div>
                    </x-inputs.button>
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
