{{--
    The data table's real scenarios: a member directory with live search and
    browser-side sorting, then a remove flow that confirms through a dialog.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $members = [
        ['id' => 1, 'name' => 'نیلوفر احمدی', 'team' => $say('Design', 'طراحی'), 'status' => 'running', 'tickets' => 342, 'csat' => 0.96, 'budget' => 48200, 'updated' => '2026-09-24'],
        ['id' => 2, 'name' => 'Kenji Sato', 'team' => $say('Motion', 'حرکت'), 'status' => 'success', 'tickets' => 214, 'csat' => 0.91, 'budget' => 31750, 'updated' => '2026-09-18'],
        ['id' => 3, 'name' => 'María López', 'team' => $say('Data', 'داده'), 'status' => 'queued', 'tickets' => 129, 'csat' => 0.88, 'budget' => 12900, 'updated' => '2026-09-25'],
        ['id' => 4, 'name' => 'Amara Okafor', 'team' => $say('Support', 'پشتیبانی'), 'status' => 'running', 'tickets' => 415, 'csat' => 0.97, 'budget' => 22400, 'updated' => '2026-09-21'],
        ['id' => 5, 'name' => 'عمر حداد', 'team' => $say('Frontend', 'فرانت‌اند'), 'status' => 'failed', 'tickets' => 97, 'csat' => 0.84, 'budget' => 67300, 'updated' => '2026-09-26'],
        ['id' => 6, 'name' => 'Priya Nair', 'team' => $say('Program', 'برنامه'), 'status' => 'success', 'tickets' => 288, 'csat' => 0.93, 'budget' => 54100, 'updated' => '2026-09-12'],
        ['id' => 7, 'name' => 'Lena Fischer', 'team' => $say('Research', 'پژوهش'), 'status' => 'canceled', 'tickets' => 61, 'csat' => 0.9, 'budget' => 8800, 'updated' => '2026-08-30'],
        ['id' => 8, 'name' => 'Haruto Sato', 'team' => $say('Support', 'پشتیبانی'), 'status' => 'running', 'tickets' => 176, 'csat' => 0.89, 'budget' => 19600, 'updated' => '2026-09-23'],
        ['id' => 9, 'name' => 'Layla Haddad', 'team' => $say('Design', 'طراحی'), 'status' => 'queued', 'tickets' => 203, 'csat' => 0.92, 'budget' => 26450, 'updated' => '2026-09-26'],
    ];

    // Live search runs on the server: name in any script, team in either language.
    $q = trim((string) ($state['q'] ?? ''));
    $rows = $q === '' ? $members : array_values(array_filter($members, fn ($m) => str_contains(mb_strtolower($m['name'].' '.$m['team']), mb_strtolower($q))));
    $removing = (int) ($state['removing'] ?? 0);
    $removee = $removing ? (collect($members)->firstWhere('id', $removing)['name'] ?? null) : null;
    $removeText = $removee
        ? ($fa ? '«'.$removee.'» به هیچ کانالی دسترسی نخواهد داشت؛ می‌توانید دوباره دعوتش کنید.' : '“'.$removee.'” will lose access to every channel; you can invite them back.')
        : $say('Pick a member above first.', 'اول یکی از اعضای بالا را انتخاب کنید.');
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The member directory', 'فهرست اعضا') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Search hits the server on every keystroke; sorting runs in the browser — click a header and the rows glide to their new places (FLIP), keeping their keys through the morph.', 'جست‌وجو با هر کلید به سرور می‌خورد؛ مرتب‌سازی در مرورگر است — سرصفحه را کلیک کنید تا ردیف‌ها با FLIP به جای تازه بلغزند و کلیدهایشان را در رفت‌وبرگشت morph نگه دارند.') }}
        </p>
    </div>
    <div class="pg-row" style="justify-content: space-between">
        <x-nx::input :label="$say('Search members', 'جست‌وجوی اعضا')" :placeholder="$say('Name or team — «طراحی», «Motion»…', 'نام یا تیم — «طراحی»، «Motion»…')" icon="search" wire:model.live.debounce.300ms="state.q" style="max-inline-size: 22rem" />
        <span style="font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
            {{ NabuXUI::formatNumber(count($rows)) }} / {{ NabuXUI::formatNumber(count($members)) }} {{ $say('members', 'عضو') }}
        </span>
    </div>
    <x-nx::data-table
        caption="{{ $say('Team members', 'اعضای تیم') }}" caption-hidden
        sort="tickets:descending" max-height="22rem" :rows="$rows" :columns="[
            ['key' => 'name', 'label' => $say('Member', 'عضو'), 'sortable' => true],
            ['key' => 'team', 'label' => $say('Team', 'تیم'), 'sortable' => true],
            ['key' => 'status', 'label' => $say('Status', 'وضعیت'), 'format' => 'status', 'sortable' => true],
            ['key' => 'tickets', 'label' => $say('Tickets', 'تیکت'), 'format' => 'number', 'sortable' => true],
            ['key' => 'csat', 'label' => $say('CSAT', 'رضایت'), 'format' => 'percent', 'sortable' => true],
            ['key' => 'budget', 'label' => $say('Budget', 'بودجه'), 'format' => 'currency:USD', 'sortable' => true],
            ['key' => 'updated', 'label' => $say('Updated', 'به‌روزرسانی'), 'format' => 'date', 'sortable' => true],
        ]" />
    @if (count($rows) === 0)
        <x-nx::empty-state icon="search" :title="$say('Nobody by that name', 'کسی با آن نام نیست')" :description="$say('Try the team instead — «طراحی», «Support», «Motion»…', 'تیم را امتحان کنید — «طراحی»، «Support»، «Motion»…')">
            <x-slot:actions>
                <x-nx::button variant="primary" icon="x" wire:click="$set('state.q', '')">{{ $say('Clear the search', 'پاک‌کردن جست‌وجو') }}</x-nx::button>
            </x-slot:actions>
        </x-nx::empty-state>
    @endif
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Removing someone, the careful way', 'حذفِ فرد، با احتیاط') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The same rows in a compact, density-tight table; the delete icon opens a dialog bound to a Livewire property — confirm runs a slow action so the button shows its spinner.', 'همان ردیف‌ها در جدولی فشرده با density؛ آیکون حذف دیالوگی مقید به یک پراپرتی Livewire باز می‌کند — تأیید یک کنش کُند را اجرا می‌کند تا دکمه اسپینرش را نشان دهد.') }}
        </p>
    </div>
    <div class="pg-row">
        @foreach (array_slice($members, 0, 4) as $m)
            <span class="pg-row" style="padding: .35rem .75rem .35rem .35rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-full); background: var(--nx-surface-2)">
                <x-nx::avatar :name="$m['name']" size="xs" />
                <span style="font-size: var(--nx-text-sm); font-weight: 550">{{ $m['name'] }}</span>
                <x-nx::icon-button icon="trash" :label="$say('Remove '.$m['name'], 'حذف '.$m['name'])" size="xs" wire:click="$set('state.removing', {{ $m['id'] }}); $set('state.dialog', true)" />
            </span>
        @endforeach
    </div>
    <x-nx::dialog wire:model="state.dialog" :title="$say('Remove from the workspace?', 'از ورک‌اسپیس حذف شود؟')" :description="$removeText" size="sm">
        <x-slot:footer>
            <div class="pg-row" style="justify-content: flex-end">
                <x-nx::button variant="ghost" wire:click="$set('state.dialog', false)">{{ $say('Keep them', 'نگه‌داشتن') }}</x-nx::button>
                <x-nx::button variant="danger" icon="trash" wire:click="save(@js($say('Member removed — access revoked', 'عضو حذف شد — دسترسی قطع شد'))); $set('state.dialog', false); $set('state.removing', 0)">
                    {{ $say('Remove access', 'قطع دسترسی') }}
                </x-nx::button>
            </div>
        </x-slot:footer>
    </x-nx::dialog>
</section>
