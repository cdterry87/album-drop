<x-jet-form-section submit="updateSettings">
    <x-slot name="title">
        {{ __('Account Settings') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Enable notifications to receive an email when your tracked artists drop a new album. Album Drop can also create a playlist for you on Spotify based on your tracked artists and new releases if you link your Spotify account.') }}
    </x-slot>

    <x-slot name="form">
        <div class="flex items-center gap-2 col-span-6">
            <x-jet-checkbox
                wire:model="subscribed"
                id="subscribed"
            />
            <div>
                <x-jet-label
                    for="subscribed"
                    value="{{ __('I would like to receive email notifications when my tracked artists release new albums.') }}"
                />
            </div>
        </div>
        @if ($spotify_id)
            <div class="flex items-center gap-2 col-span-6">
                <x-jet-checkbox
                    wire:model="create_mega_playlist"
                    id="create_mega_playlist"
                />
                <div>
                    <x-jet-label
                        for="create_mega_playlist"
                        value="{{ __('I would like Album Drop to create a playlist for me on Spotify consisting of music from all of my tracked artists.') }}"
                    />
                </div>
            </div>
            <div class="flex items-center gap-2 col-span-6">
                <x-jet-checkbox
                    wire:model="create_new_releases_playlist"
                    id="create_new_releases_playlist"
                />
                <div>
                    <x-jet-label
                        for="create_new_releases_playlist"
                        value="{{ __('I would like Album Drop to create a playlist for me on Spotify consisting of new releases from my tracked artists.') }}"
                    />
                </div>
            </div>
            <div class="flex items-center gap-2 col-span-6">
                <x-jet-checkbox
                    wire:model="create_weekly_playlist"
                    id="create_weekly_playlist"
                />
                <div>
                    <x-jet-label
                        for="create_weekly_playlist"
                        value="{{ __('I would like Album Drop to create a weekly playlist for me on Spotify consisting of random songs from my tracked artists.') }}"
                    />
                </div>
            </div>
        @endif
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message
            class="mr-3"
            on="settingsUpdated"
        >
            {{ __('Your settings have been saved!') }}
        </x-jet-action-message>
        <x-jet-button
            wire:loading.attr="disabled"
            wire:target="photo"
        >
            {{ __('Save') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
