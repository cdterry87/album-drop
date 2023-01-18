<div>
    <x-header
        title="New Releases"
        subtitle="Check out the latest releases from your tracked artists!"
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
                <select
                    class="select select-bordered w-full max-w-xs"
                    wire:model="filter_days"
                >
                    <option value="30">Past 30 Days</option>
                    <option value="90">Past 90 Days</option>
                    <option value="180">Past 180 Days</option>
                    <option value="365">Past 365 Days</option>
                    <option value="">All Time</option>
                </select>
            </div>
        </div>
    </x-header>

    @if ($results->isNotEmpty())
        <div
            id="new-releases"
            class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 w-full"
        >
            @foreach ($results as $result)
                <livewire:components.album-card
                    :name="$result->name"
                    :artist="$result->artist->name"
                    :release-date="$result->release_date"
                    :image="$result->image"
                    :url="$result->url"
                    :spotify-id="$result->spotify_album_id"
                    wire:key="{{ $result->spotify_album_id }}"
                />
            @endforeach
        </div>

        <x-pagination-results :results="$results" />
    @else
        <div>
            <h3
                id="no-new-releases"
                class="text-center lg:text-left"
            >
                There are no new releases at the moment. Track your favorite artists to get notified when they release
                new music!
            </h3>
        </div>
    @endif
</div>
