<x-mail::message>
# Hey {{ $user->name }},

Some new albums just dropped for you!

@foreach ($albums as $album)
<x-mail::panel>
<center>
<div>
<img
src="{{ $album->image }}"
alt="{{ $album->artist->name }} - {{ $album->name }}"
width="200"
/>
</div>

<div>
{{ $album->name }} by {{ $album->artist->name }}
</div>

<div>
Released: {{ $album->release_date }}
</div>

<x-mail::button :url="$album->url">
View On Spotify
</x-mail::button>
</center>
</x-mail::panel>
@endforeach

<x-mail::subcopy>
&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
</x-mail::subcopy>

{{-- unsubscribe goes here --}}
</x-mail::message>
