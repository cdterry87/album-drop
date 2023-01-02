<div class="navbar bg-base-100 absolute top-0 left-0 bg-opacity-0">
    <div class="w-full navbar-end">
        <ul class="menu menu-horizontal px-1">
            @guest
                <li>
                    <a
                        href="{{ route('login') }}"
                        class="text-white underline"
                    >Log in</a>
                </li>
                @if (Route::has('register'))
                    <li>
                        <a
                            href="{{ route('register') }}"
                            class="text-white underline"
                        >Register</a>
                    </li>
                @endif
            @endguest
        </ul>
    </div>
</div>
