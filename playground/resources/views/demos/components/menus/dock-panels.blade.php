{{--
    The dock as a project app's quick panels: activating an item grows the dock
    itself into that item's panel; Escape or a press outside folds it back.
    The open id is bound to Livewire, and one panel holds a live action.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem; min-block-size: 24rem; align-content: space-between">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Quick panels for the daily tools', 'پنل‌های سریع ابزارهای روزمره') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The dock itself becomes the panel — press an item, then Escape or click outside to fold it back. Arrow keys walk the items.', 'خودِ داک به پنل بدل می‌شود — آیتمی را فشار دهید، بعد Escape یا کلیک بیرون جمعش می‌کند. کلیدهای فلشی بین آیتم‌ها می‌گردند.') }}
        </p>
    </div>
    <div class="pg-row" style="justify-content: center; margin-block-start: auto">
        <x-nx::dock-panels label="{{ $say('Quick panels', 'پنل‌های سریع') }}" :value="$state['dock'] ?? null" wire:model.live="state.dock" :items="[
            ['id' => 'player', 'label' => $say('Now playing', 'در حال پخش'), 'icon' => 'play'],
            ['id' => 'inbox', 'label' => $say('Inbox', 'صندوق'), 'icon' => 'mail'],
            ['id' => 'team', 'label' => $say('Team', 'تیم'), 'icon' => 'users'],
            ['id' => 'ideas', 'label' => $say('Ideas', 'ایده‌ها'), 'icon' => 'sparkles'],
        ]">
            <x-slot:player>
                <h4 class="nx-dock-panels-heading">{{ $say('Now playing', 'در حال پخش') }}</h4>
                <p style="margin: 0 0 .5rem; font-weight: 700">{{ $say('Rain in Tehran — lo-fi for building design systems', 'باران تهران — لوفایای ساخت سیستم طراحی') }}</p>
                <div class="pg-row">
                    <x-nx::button size="sm" variant="secondary" icon="play" wire:click="ping(@js($say('Playing through the office speaker', 'پخش از بلندگوی دفتر')))">{{ $say('Play in office', 'پخش در دفتر') }}</x-nx::button>
                    <x-nx::badge tone="success" dot>{{ $say('listeners', 'شنونده').': '.NabuXUI::formatNumber(3) }}</x-nx::badge>
                </div>
            </x-slot:player>
            <x-slot:inbox>
                <h4 class="nx-dock-panels-heading">{{ $say('Inbox', 'صندوق') }}</h4>
                <ul style="list-style: none; margin: 0 0 .625rem; padding: 0; display: grid; gap: .375rem; font-size: var(--nx-text-sm)">
                    <li><strong>{{ $say('Ava Karimi', 'آوا کریمی') }}</strong> · {{ $say('the invoice block is ready for review', 'بلوک فاکتور آمادهٔ بازبینی است') }}</li>
                    <li><strong>{{ $say('Kian Rajaee', 'کیان رجایی') }}</strong> · {{ $say('fonts shipped', 'فونت‌ها منتشر شد') }}</li>
                </ul>
                <x-nx::button size="sm" variant="primary" icon="mail" wire:click="save(@js($say('Inbox opened', 'صندوق باز شد')))">{{ $say('Open inbox', 'بازکردن صندوق') }}</x-nx::button>
            </x-slot:inbox>
            <x-slot:team>
                <h4 class="nx-dock-panels-heading">{{ $say('Team', 'تیم') }}</h4>
                <div class="pg-row">
                    <x-nx::avatar-group :people="[
                        ['name' => $say('Ava Karimi', 'آوا کریمی')],
                        ['name' => $say('Soheil Nouri', 'سهیل نوری')],
                        ['name' => $say('Mona Ahmadi', 'مونا احمدی')],
                        ['name' => $say('Kian Rajaee', 'کیان رجایی')],
                    ]" max="4" />
                    <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('2 offline', '۲ نفر آفلاین') }}</span>
                </div>
            </x-slot:team>
            <x-slot:ideas>
                <h4 class="nx-dock-panels-heading">{{ $say('Ideas', 'ایده‌ها') }}</h4>
                <p style="margin: 0 0 .625rem; color: var(--nx-text-muted)">
                    {{ $say('Persian keyboard shortcuts for the command palette — from Thursday’s retro.', 'میان‌برهای کیبوردی فارسی برای پالت فرمان — از بازبینی پنجشنبه.') }}
                </p>
                <x-nx::button size="sm" variant="secondary" icon="plus" wire:click="ping(@js($say('Idea moved to the board', 'ایده به برد رفت')))">{{ $say('Move to board', 'بردن به برد') }}</x-nx::button>
            </x-slot:ideas>
        </x-nx::dock-panels>
    </div>
    <p style="margin: 0; color: var(--nx-text-muted)">
        {{ $say('Open panel on the server:', 'پنل باز روی سرور:') }}
        <code>{{ json_encode($state['dock'] ?? null) }}</code>
    </p>
</section>
