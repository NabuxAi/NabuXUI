<x-nx::dialog variant="drawer" {{ $attributes }}>
    {{ $slot }}
    @isset($footer)<x-slot:footer>{{ $footer }}</x-slot:footer>@endisset
</x-nx::dialog>
