{{--
    Morph tabs twice: a project header whose tabs carry real panels (the pill
    springs to the active one, which opens to show its label), and a centered
    icon-only toolbar with no panels at all.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A project header', 'سربرگ پروژه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The active tab opens to name itself while the shared pill springs under it; the panels below swap through the morph and the choice syncs to Livewire.', 'تب فعال باز می‌شود تا خودش را معرفی کند و pill مشترک با فنر زیرش می‌نشیند؛ پنل‌ها با morph عوض می‌شوند و انتخاب به Livewire سینک می‌شود.') }}
        </p>
    </div>
    <x-nx::morph-tabs label="{{ $say('Project sections', 'بخش‌های پروژه') }}" :value="$state['projectTab'] ?? 'board'" wire:model.live="state.projectTab" :items="[
        ['value' => 'board', 'label' => $say('Board', 'برد'), 'icon' => 'grid'],
        ['value' => 'inbox', 'label' => $say('Inbox', 'صندوق'), 'icon' => 'mail'],
        ['value' => 'reports', 'label' => $say('Reports', 'گزارش‌ها'), 'icon' => 'chart'],
        ['value' => 'team', 'label' => $say('Team', 'تیم'), 'icon' => 'users'],
    ]">
        <x-slot:board>
            <div class="pg-row" style="align-items: start">
                @foreach ([
                    ['label' => $say('To do', 'انجام‌نشده'), 'count' => 5, 'tone' => 'neutral'],
                    ['label' => $say('In progress', 'در جریان'), 'count' => 3, 'tone' => 'info'],
                    ['label' => $say('Done this week', 'انجام‌شده این هفته'), 'count' => 11, 'tone' => 'success'],
                ] as $column)
                    <div style="display: grid; gap: .5rem; padding: .875rem; min-inline-size: 10rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface-2)">
                        <div class="pg-row" style="justify-content: space-between">
                            <strong style="font-size: var(--nx-text-sm)">{{ $column['label'] }}</strong>
                            <x-nx::badge :tone="$column['tone']">{{ NabuXUI::formatNumber($column['count']) }}</x-nx::badge>
                        </div>
                        @foreach (range(1, min(2, $column['count'])) as $card)
                            <div style="padding: .5rem .625rem; border-radius: var(--nx-radius-md); background: var(--nx-surface); font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
                                {{ $say('Card', 'کارت').' '.NabuXUI::formatNumber($card) }}
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </x-slot:board>
        <x-slot:inbox>
            <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .5rem">
                @foreach ([
                    ['who' => $say('Soheil Nouri', 'سهیل نوری'), 'what' => $say('moved “Checkout revamp” to Review', '«بازطراحی تسویه» را به بازبینی برد')],
                    ['who' => $say('Ava Karimi', 'آوا کریمی'), 'what' => $say('commented on the invoice block', 'روی بلوک فاکتور نظر داد')],
                    ['who' => $say('Kian Rajaee', 'کیان رجایی'), 'what' => $say('merged the font fallbacks', 'فال‌بک‌های فونت را ادغام کرد')],
                ] as $row)
                    <li class="pg-row" style="gap: .75rem; padding: .625rem .875rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
                        <x-nx::avatar :name="$row['who']" size="sm" />
                        <span style="font-size: var(--nx-text-sm)"><strong>{{ $row['who'] }}</strong> {{ $row['what'] }}</span>
                    </li>
                @endforeach
            </ul>
        </x-slot:inbox>
        <x-slot:reports>
            <div class="pg-row" style="gap: 2rem">
                @foreach ([
                    ['label' => $say('Shipped this month', 'منتشرشده این ماه'), 'value' => 27],
                    ['label' => $say('Cycle time', 'زمان چرخه'), 'value' => 2],
                    ['label' => $say('Open bugs', 'باگ باز'), 'value' => 4],
                ] as $metric)
                    <div style="display: grid; gap: .25rem">
                        <span style="font: 700 var(--nx-text-2xl) / 1.1 var(--nx-font-display)">{{ NabuXUI::formatNumber($metric['value']) }}</span>
                        <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $metric['label'] }}</span>
                    </div>
                @endforeach
            </div>
        </x-slot:reports>
        <x-slot:team>
            <div class="pg-row">
                <x-nx::avatar-group :people="[
                    ['name' => $say('Ava Karimi', 'آوا کریمی')],
                    ['name' => $say('Soheil Nouri', 'سهیل نوری')],
                    ['name' => $say('Mona Ahmadi', 'مونا احمدی')],
                    ['name' => $say('Kian Rajaee', 'کیان رجایی')],
                    ['name' => $say('Sara Mohammadi', 'سارا محمدی')],
                ]" max="5" />
                <x-nx::button size="sm" variant="secondary" icon="plus" wire:click="ping(@js($say('Invite sheet opened', 'برگهٔ دعوت باز شد')))">{{ $say('Invite', 'دعوت') }}</x-nx::button>
            </div>
        </x-slot:team>
    </x-nx::morph-tabs>
    <p style="margin: 0; color: var(--nx-text-muted)">
        {{ $say('Active tab on the server:', 'تب فعال روی سرور:') }}
        <code>{{ $state['projectTab'] ?? 'board' }}</code>
    </p>
</section>

<section class="pg-box" style="gap: 1rem">
    <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A quiet icon toolbar', 'نوار ابزار آیکونیِ ساکت') }}</h3>
    <p style="margin: 0; color: var(--nx-text-muted)">
        {{ $say('Without panels the tabs stay icons — tooltips carry the names. justify moves the row.', 'بدون پنل، تب‌ها آیکون می‌مانند — نام‌ها در راهنما هستند. با justify ردیف جابه‌جا می‌شود.') }}
    </p>
    <x-nx::morph-tabs justify="center" label="{{ $say('Canvas tools', 'ابزار بوم') }}" :items="[
        ['value' => 'select', 'label' => $say('Select', 'انتخاب'), 'icon' => 'check'],
        ['value' => 'draw', 'label' => $say('Draw', 'ترسیم'), 'icon' => 'edit'],
        ['value' => 'note', 'label' => $say('Note', 'یادداشت'), 'icon' => 'message'],
        ['value' => 'image', 'label' => $say('Image', 'تصویر'), 'icon' => 'image'],
        ['value' => 'settings', 'label' => $say('Canvas settings', 'تنظیمات بوم'), 'icon' => 'settings', 'disabled' => true],
    ]" />
</section>
