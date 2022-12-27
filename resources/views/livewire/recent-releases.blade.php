<div>
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <h2 class="font-bold text-2xl">Recent Releases</h2>
    </div>

    <hr class="my-8 border-gray-600">

    @if ($results->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 w-full">
            @foreach ($results as $result)
                <livewire:components.album-card
                    :name="$result->album->name"
                    :artist="$result->album->artist->name"
                    :release-date="$result->album->release_date"
                    :image="$result->album->image"
                    :url="$result->album->url"
                    :spotify-id="$result->album->spotify_album_id"
                    wire:key="{{ $result->album->spotify_album_id }}"
                />
            @endforeach
        </div>
    @else
        <div>
            <h3 class="font-bold lg text-center">
                No artists were found with that name. Try a different search.
            </h3>
        </div>
    @endif
</div>
