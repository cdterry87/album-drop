<header
    id="navbar"
    class="navbar flex gap-4 justify-end px-6 py-4"
>
    @auth
        <div class="flex-none">
            <ul class="menu menu-horizontal px-1">
                <li tabindex="0">
                    <a>
                        <x-icons.user />
                        {{ auth()->user()->name }}
                        <svg
                            class="fill-current"
                            xmlns="http://www.w3.org/2000/svg"
                            width="20"
                            height="20"
                            viewBox="0 0 24 24"
                        >
                            <path d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z" />
                        </svg>
                    </a>
                    <ul class="p-2 bg-base-300 z-40 border border-gray-500">
                        @if (auth()->user()->spotify_id)
                            <li>
                                <a
                                    href="#"
                                    wire:click.prevent="syncSpotifyArtists"
                                >
                                    <x-icons.sync />
                                    Sync Spotify Artists
                                </a>
                            </li>
                        @endif
                        <li>
                            <a href="{{ route('profile.show') }}">
                                <x-icons.settings />
                                Settings
                            </a>
                        </li>
                        <li>

                            <form
                                method="POST"
                                action="{{ route('logout') }}"
                                class="inline-block"
                            >
                                @csrf
                                <a
                                    href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="flex items-center gap-4"
                                >
                                    <x-icons.logout />
                                    Logout
                                </a>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    @endauth
    <label
        for="sidebar"
        class="drawer-overlay cursor-pointer lg:hidden"
    >
        <x-icons.menu />
    </label>
</header>
