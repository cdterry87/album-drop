<div>
    <x-header
        title="{!! $albumName !!}"
        subtitle="{!! $artistName !!}"
    >
        <div class="flex flex-col sm:flex-row items-center gap-2">
            <a
                href="{{ $albumUrl }}"
                class="btn btn-spotify"
                target="_blank"
            >
                <span class="mr-2">
                    Listen on Spotify
                </span>
                <x-icons.spotify />
            </a>
        </div>
    </x-header>

    @if ($albumTracks)
        <div
            id="album-tracks"
            class="overflow-x-auto"
        >

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="col-span-2">
                    <table class="table table-zebra table-compact md:table-base w-full">
                        <tbody>
                            @foreach ($albumTracks as $track)
                                <tr class="h-12">
                                    <td>{{ $track['track_number'] }}</td>
                                    <td class="w-full">
                                        {{ $track['name'] }}
                                    </td>
                                    <td>
                                        {{-- @todo --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="flex justify-center order-first md:order-last">
                    <div class="space-y-4">
                        <div class="card w-64 shadow-xl image-full">
                            <figure>
                                <img
                                    src="{{ $albumImage }}"
                                    alt="{{ $albumName }} by {{ $artistName }}"
                                />
                            </figure>
                        </div>
                        <div class="text-sm text-center">
                            Released: {{ getFormalDate($albumReleaseDate) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div>
            <h3
                id="no-album-tracks"
                class="text-center lg:text-left"
            >
                Sorry, we don't have any track data for this album at the moment. Check back later for updates!
            </h3>
        </div>
    @endif
</div>
