<div>
    <main class="nx-page" wire:transition.navigate="nx-page">
        <div class="pg">
            <nav class="pg-row" aria-label="Groups">
                @foreach (['glass', 'text', 'actions', 'cards', 'data', 'menus', 'backdrops'] as $name)
                    <x-nx::button size="sm" :variant="$name === $group ? 'primary' : 'ghost'" href="/blocks/{{ $name }}" wire:navigate>{{ ucfirst($name) }}</x-nx::button>
                @endforeach
            </nav>
            @include('demos.'.$group)
        </div>
    </main>
</div>
