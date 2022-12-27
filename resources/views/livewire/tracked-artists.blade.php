<div>
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <h2 class="font-bold text-2xl">Tracked Artists</h2>
        <div class="flex items-center gap-2">
            <input
                type="text"
                placeholder="Search tracked artists"
                class="input input-bordered input-primary lg:w-full max-w-xs outline-none ring-0"
                wire:model.debounce.500ms="search"
            />
        </div>
    </div>

    <hr class="my-8 border-gray-600">

    @if ($results->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 w-full">
            @foreach ($results as $result)
                <livewire:components.artist-card
                    :name="$result->name"
                    :image="$result->image"
                    :url="$result->url"
                    :spotify-id="$result->spotify_artist_id"
                    wire:key="{{ $result->spotify_artist_id }}"
                />
            @endforeach
        </div>
    @else
        <div>
            <h3 class="font-bold lg text-center">
                You are not tracking any artists. <a
                    href="{{ route('search-artists') }}"
                    class="underline"
                >Search for artists</a>, start tracking them, and they will appear here.
            </h3>
        </div>
    @endif
</div>
