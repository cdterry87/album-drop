<div>
    <x-header
        title="Recommended Artists"
        subtitle="We recommend these artists based on the artists you are tracking."
    >
        <div class="flex flex-col sm:flex-row items-center gap-2">
            <div class="w-full sm:w-auto">
                <select
                    class="select select-bordered w-full"
                    wire:model="filter_show"
                >
                    <option value="30">Show 30</option>
                    <option value="60">Show 60</option>
                    <option value="90">Show 90</option>
                    <option value="120">Show 120</option>
                </select>
            </div>
            <div class="w-full sm:w-auto">
                <x-inputs.text
                    placeholder="Search recommended artists..."
                    wire:model.debounce.500ms="search"
                />
            </div>
        </div>
    </x-header>

    <div>
        @if ($results->isNotEmpty())
            <div
                id="recommended-artists"
                class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4"
            >
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

            <x-pagination-results :results="$results" />
        @else
            <div>
                <h3
                    id="no-recommended-artists"
                    class="text-center lg:text-left"
                >
                    You do not have any recommendations. Track more artists and check back later to get recommendations.
                </h3>
            </div>
        @endif
    </div>
</div>
