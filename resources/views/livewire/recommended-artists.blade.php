<div>
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <h2 class="font-bold text-2xl">Recommended Artists</h2>
    </div>

    <hr class="my-8 border-gray-600">

    <div>
        @if ($results)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach ($results as $result)
                    <livewire:components.artist-card
                        :name="$result->name"
                        :image="$result->image"
                        :url="$result->url"
                        :spotify-id="$result->spotify_artist_id"
                        wire:key="{{ $result->artist_id }}"
                    />
                @endforeach
            </div>
        @else
            <div>
                <h3 class="font-bold lg text-center">
                    You do not have any recommendations. Track more artists and check back later to get recommendations.
                </h3>
            </div>
        @endif
    </div>
</div>
