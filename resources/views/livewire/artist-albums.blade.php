<div>
    <x-header
        title="All Albums"
        subtitle="for {{ $artistName }}"
    />

    @if ($results->isNotEmpty())
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 w-full">
            @foreach ($results as $result)
                <livewire:components.album-card
                    :name="$result->name"
                    :release-date="$result->release_date"
                    :image="$result->image"
                    :url="$result->url"
                    wire:key="{{ $result->spotify_album_id }}"
                />
            @endforeach
        </div>

        <x-pagination-results :results="$results" />
    @else
        <div>
            <h3 class="text-center lg:text-left">
                Sorry, we don't have any album data for this artist at the moment. Check back later for updates!
            </h3>
        </div>
    @endif
</div>
