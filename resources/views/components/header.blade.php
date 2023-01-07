@props(['title', 'subtitle'])

<div id="header">
    <div class="flex flex-col text-center md:flex-row md:text-left items-center justify-between gap-6">
        <div class="flex flex-col gap-1">
            <h2 class="font-bold text-2xl">{{ $title }}</h2>
            @if ($subtitle)
                <h3 class="text-sm">{{ $subtitle }}</h3>
            @endif
        </div>
        {{ $slot }}
    </div>

    <hr class="my-6 border-gray-600">
</div>
