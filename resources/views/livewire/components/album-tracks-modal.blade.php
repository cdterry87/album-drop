<div>
    <input
        type="checkbox"
        id="album-tracks--modal"
        class="modal-toggle"
        wire:model="isModalShown"
    />
    <div class="modal modal-bottom sm:modal-middle">
        <div class="modal-box bg-base-300">
            @if ($results)
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h3 class="font-bold text-lg">{{ $album }}</h3>
                        <h4 class="font-semibold text-sm">
                            Released: {{ Carbon\Carbon::parse($releaseDate)->format('F j, Y') }}
                        </h4>
                    </div>
                    <label
                        for="album-tracks--modal"
                        wire:click.prevent="closeModal"
                        class="cursor-pointer"
                    >
                        <x-icons.close />
                    </label>
                </div>

                <div class="overflow-x-auto text-sm pt-6 bg-base-300">
                    <table class="table table-zebra table-compact md:table-base w-full">
                        <tbody>
                            @foreach ($results as $result)
                                <tr class="h-12">
                                    <td>{{ $result['track_number'] }}</td>
                                    <td
                                        title="{{ $result['name'] }}"
                                        alt="{{ $result['name'] }}"
                                        class="w-full"
                                    >
                                        {{ Str::limit($result['name'], 30) }}
                                    </td>
                                    <td>
                                        <a
                                            href="{{ $result['href'] }}"
                                            target="_blank"
                                        >
                                            <x-icons.spotify class="text-spotify" />
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <h3 class="text-center lg:text-left">
                    Sorry, we don't have any track data for this album at the moment. Check back later for updates!
                </h3>
            @endif
        </div>
    </div>
</div>
