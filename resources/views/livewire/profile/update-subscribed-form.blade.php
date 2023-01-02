<x-jet-form-section submit="updateSubscribed">
    <x-slot name="title">
        {{ __('Notifications Settings') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Enable notifications to receive an email when your tracked artists drop a new album.') }}
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
    </x-slot>

    <x-slot name="actions">
        <x-jet-action-message
            class="mr-3"
            on="subscribed"
        >
            {{ __('You are now subscribed!') }}
        </x-jet-action-message>
        <x-jet-action-message
            class="mr-3"
            on="unsubscribed"
        >
            {{ __('You have unsubscribed successfully.') }}
        </x-jet-action-message>
        <x-jet-button
            wire:loading.attr="disabled"
            wire:target="photo"
        >
            {{ __('Save') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
