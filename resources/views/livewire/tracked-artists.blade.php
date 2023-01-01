<div>
    <x-header
        title="Tracked Artists"
        subtitle="You will receive notifications when your tracked artists below release new albums."
    >
        <div class="flex items-center gap-2">
            <x-inputs.text
                placeholder="Search your tracked artists..."
                wire:model.debounce.500ms="search"
            />
        </div>
    </x-header>

    @if ($results->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 w-full">
            @foreach ($results as $result)
                <livewire:components.artist-card
                    :name="$result->name"
                    :image="$result->image"
                    :url="$result->url"
                    :spotify-id="$result->spotify_artist_id"
                    wire:key="{{ $result->spotify_artist_id }}"
                    has-albums
                />
            @endforeach
        </div>

        <div class="mt-6">
            {{ $results->links() }}
        </div>
    @else
        <div>
            <h3 class="text-center lg:text-left">
                You are not tracking any artists. <a
                    href="{{ route('search-artists') }}"
                    class="underline"
                >Search for artists</a>, start tracking them, and they will appear here.
            </h3>
        </div>
    @endif
</div>
