<div
    id="guest-navigation"
    class="navbar bg-base-100 absolute top-0 left-0 bg-opacity-0"
>
    <div class="w-full navbar-end">
        <ul class="menu menu-horizontal px-1">
            @guest
                <li>
                    <a
                        href="{{ route('login') }}"
                        class="text-white underline"
                    >Log in</a>
                </li>
                <li>
                    <a
                        href="{{ route('register') }}"
                        class="text-white underline"
                    >Register</a>
                </li>
            @endguest
            @auth
                <li>
                    <a
                        href="{{ route('home') }}"
                        class="text-white underline"
                    >Home</a>
                </li>
                <li>
                    <form
                        id="logout-form"
                        action="{{ route('logout') }}"
                        method="POST"
                    >
                        @csrf
                        <a
                            href="{{ route('logout') }}"
                            class="text-white underline"
                            onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                        >Logout</a>
                    </form>
                </li>
            @endauth
        </ul>
    </div>
</div>
