<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Google tag (gtag.js) -->
    <script
        async
        src="https://www.googletagmanager.com/gtag/js?id=G-6DT2KSTGEL"
    ></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-6DT2KSTGEL');
    </script>

    <meta charset="utf-8">
    <meta
        name="author"
        content="Chase Terry"
    >
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link
        rel="apple-touch-icon"
        sizes="180x180"
        href="/apple-touch-icon.png"
    >
    <link
        rel="icon"
        type="image/png"
        sizes="32x32"
        href="/favicon-32x32.png"
    >
    <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="/favicon-16x16.png"
    >
    <link
        rel="manifest"
        href="/site.webmanifest"
    >

    <!-- Fonts -->
    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >
    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >
    <link
        href="https://fonts.googleapis.com/css2?family=Alata&family=Mulish:wght@200;400;800&display=swap"
        rel="stylesheet"
    >

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>

<body
    class="font-sans antialiased"
    style="visibility: hidden;"
>
    <div class="drawer drawer-mobile">
        <input
            id="sidebar"
            type="checkbox"
            class="drawer-toggle"
        />
        <div class="drawer-content flex flex-col">
            <main id="main">
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
                        <x-icons.close />
                    </label>
                </header>

                <div class="px-8 pb-16">
                    {{ $slot }}
                </div>
            </main>
        </div>
        <div class="drawer-side">
            <label
                for="sidebar"
                class="drawer-overlay"
            ></label>
            <div class="menu p-4 w-80 bg-base-300 text-base-content">
                <a href="{{ route('home') }}">
                    <h1 class="text-3xl font-bold">{{ env('APP_NAME') }}</h1>
                </a>
                <label
                    for="sidebar"
                    class="drawer-overlay cursor-pointer absolute top-6 right-6 lg:hidden"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                        class="w-8 h-8"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </label>
                <hr class=" mt-6 mb-4 border-gray-600">
                <ul>
                    <li>
                        <a
                            href="{{ route('home') }}"
                            class="{{ request()->routeIs('home') ? 'active' : '' }}"
                        >
                            <x-icons.home />
                            Home
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('search-artists') }}"
                            class="{{ request()->routeIs('search-artists') ? 'active' : '' }}"
                        >
                            <x-icons.search />
                            Search Artists
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('tracked-artists') }}"
                            class="{{ request()->routeIs('tracked-artists') ? 'active' : '' }}"
                        >
                            <x-icons.heart />
                            Tracked Artists
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('new-releases') }}"
                            class="{{ request()->routeIs('new-releases') ? 'active' : '' }}"
                        >
                            <x-icons.new />
                            New Releases
                        </a>
                    </li>
                    <li>
                        <a
                            href="{{ route('recommended-artists') }}"
                            class="{{ request()->routeIs('recommended-artists') ? 'active' : '' }}"
                        >
                            <x-icons.fire />
                            Recommended Artists
                        </a>
                    </li>
                </ul>

                <x-spotify-logo
                    class="mt-12 px-6"
                    image-height="h-8"
                />
            </div>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')

    <x-scripts.dom-ready />
</body>

</html>
