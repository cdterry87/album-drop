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
                <livewire:header />

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
                    <x-icons.close />
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
