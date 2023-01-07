<div>
    <x-header
        title="Tracked Artists"
        subtitle="You will receive notifications when your tracked artists below release new albums."
    >
        <div class="flex flex-col sm:flex-row items-center gap-2">
            <div class="w-full sm:w-auto">
                <select
                    class="select select-bordered w-full"
                    wire:model="filter_show"
                >
                    <option value="12">Show 12</option>
                    <option value="36">Show 36</option>
                    <option value="60">Show 60</option>
                    <option value="96">Show 96</option>
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

    @if ($results->isNotEmpty())
        <div
            id="tracked-artists"
            class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 w-full"
        >
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
            <h3
                id="no-tracked-artists"
                class="text-center lg:text-left"
            >
                You are not tracking any artists. <a
                    href="{{ route('search-artists') }}"
                    class="underline"
                >Search for artists</a>, start tracking them, and they will appear here.
            </h3>
        </div>
    @endif
</div>
