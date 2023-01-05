<div class="flex flex-col gap-10">
    @if ($isUserSubscribed)
        <div
            id="subscribed--alert"
            class="alert alert-success shadow-lg"
        >
            <div class="flex flex-col md:flex-row items-center gap-4">
                <x-icons.check />
                <span>
                    Notifications are enabled. You will receive emails when your tracked artists release new albums.
                </span>
            </div>
        </div>
    @else
        <div
            id="not-subscribed--alert"
            class="alert alert-warning shadow-lg"
        >
            <div class="flex flex-col items-center gap-4">
                <div class="flex flex-col items-center gap-4 lg:flex-row">
                    <x-icons.warning />
                    <p>
                        You must enable notifications to receive email notifications when your tracked artists release
                        new albums. Would you like to enable notifications now?
                    </p>
                </div>
                <button
                    class="btn btn-sm btn-black"
                    wire:click.prevent="subscribe"
                >Yes, enable notifications!</button>
            </div>
        </div>
    @endif

    <div class="stats stats-vertical lg:stats-horizontal shadow bg-base-300 w-full">
        <div class="stat">
            <div class="stat-title">Artists You're Tracking</div>
            <div class="stat-value text-info text-5xl my-2 flex items-center gap-2">
                <x-icons.heart />
                {{ $trackedArtistsCount }}
            </div>
            <div class="stat-desc">to be notified when a new album drops</div>
        </div>
        <div class="stat">
            <div class="stat-title">New Releases</div>
            <div class="stat-value text-warning text-5xl my-2 flex items-center gap-2">
                <x-icons.new />
                {{ $newReleasesCount }}
            </div>
            <div class="stat-desc">in the last 30 days</div>
        </div>
        <div class="stat">
            <div class="stat-title">Recommended Artists</div>
            <div class="stat-value text-error text-5xl my-2 flex items-center gap-2">
                <x-icons.fire />
                {{ $recommendedArtistsCount }}
            </div>
            <div class="stat-desc">based on your tracked artists</div>
        </div>
    </div>

    {{-- Latest Tracked Artists --}}
    <div class="flex flex-col gap-3">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold">Latest Tracked Artists</h2>
        </div>
        @if ($latestTrackedArtists->isNotEmpty())
            <div
                id="latest-tracked-artists"
                class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 w-full"
            >
                @foreach ($latestTrackedArtists as $artist)
                    <x-cards.artist
                        :name="$artist->name"
                        :image="$artist->image"
                    />
                @endforeach
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
                    >Search for artists</a> to track and receive notifications when they release new albums.
                </h3>
            </div>
        @endif
    </div>

    {{-- Latest New Releases --}}
    <div class="flex flex-col gap-3">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold">Latest New Releases</h2>
        </div>
        @if ($latestNewReleases->isNotEmpty())
            <div
                id="latest-new-releases"
                class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 w-full"
            >
                @foreach ($latestNewReleases as $artist)
                    <x-cards.album
                        :name="$artist->name"
                        :image="$artist->image"
                        :artist="$artist->artist->name"
                    />
                @endforeach
            </div>
        @else
            <div>
                <h3
                    id="no-new-releases"
                    class="text-center lg:text-left"
                >
                    There are no new releases at this time. Start tracking artists to see new releases.
                </h3>
            </div>
        @endif
    </div>

    {{-- Recommended For You --}}
    <div class="flex flex-col gap-3">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold">Recommended For You</h2>
        </div>
        @if ($randomRecommendedArtists->isNotEmpty())
            <div
                id="recommended-artists"
                class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 w-full"
            >
                @foreach ($randomRecommendedArtists as $artist)
                    <x-cards.artist
                        :name="$artist->name"
                        :image="$artist->image"
                    />
                @endforeach
            </div>
        @else
            <div>
                <h3
                    id="no-recommended-artists"
                    class="text-center lg:text-left"
                >
                    There are no recommendations at this time. Start tracking artists to receive recommendations.
                </h3>
            </div>
        @endif
    </div>
</div>
