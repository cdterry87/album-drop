<div>
    <x-header
        title="Recommended Artists"
        subtitle="We recommend these artists based on the artists you are tracking."
    />

    <div>
        @if ($results)
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
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

            <div class="mt-6">
                {{ $results->links() }}
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
