{{--
    The avatar group in real rooms: the header of a shared document (where the
    +n fold earns its keep), the footer of a project card (size="sm", tight
    rows) and a meeting room at the large size where everybody fits. No photos
    here — initials are the honest fallback the component ships with; give a
    person a src and the real face takes over.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // Everyone with the release notes open right now.
    $viewers = [
        ['name' => 'مریم صادقی'],
        ['name' => 'سامان دهقان'],
        ['name' => 'Nguyen Linh'],
        ['name' => 'هانیه کاظمی'],
        ['name' => 'عمر حداد'],
        ['name' => 'آمارا اوکافور'],
        ['name' => 'لیلا حداد'],
    ];

    // The shop-billing project crew.
    $team = [
        ['name' => 'نگار رستمی'], ['name' => 'Kenji Sato'], ['name' => 'مریم صادقی'],
        ['name' => 'سامان دهقان'], ['name' => 'هانیه کاظمی'], ['name' => 'عمر حداد'],
        ['name' => 'آمارا اوکافور'], ['name' => 'لیلا حداد'], ['name' => 'Nguyen Linh'],
    ];

    // The weekly product review's guests.
    $guests = [
        ['name' => 'لیلا حداد'], ['name' => 'Kenji Sato'], ['name' => 'آمارا اوکافور'], ['name' => 'مریم صادقی'],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Who has the document open', 'چه کسانی سند را باز کرده‌اند') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The header of a shared file: the ceiling max="4" folds the room into +n, hovering — or tabbing to — a face reveals its name, and the whole strip answers as one aria-labelled group.', 'سربرگ یک فایل مشترک: سقف max="4" اتاق را داخل +n جمع می‌کند، رفتن روی چهره — یا Tab تا آن — نامش را نشان می‌دهد و کل ردیف به‌عنوان یک گروهِ دارای برچسب aria پاسخ می‌دهد.') }}
        </p>
    </div>
    <div class="pg-row" style="justify-content: space-between; padding: 1rem 1.25rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface-2)">
        <span class="pg-row" style="gap: 1rem; min-inline-size: 0">
            <span style="display: grid; gap: .15rem; min-inline-size: 0">
                <strong style="font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap">{{ $say('Release notes · v2.4', 'یادداشت‌های انتشار · نسخهٔ ۲٫۴') }}</strong>
                <span style="font-size: var(--nx-text-sm); color: var(--nx-text-muted)">{{ NabuXUI::formatNumber(count($viewers)).' '.$say('people in this file right now', 'نفر الان در این فایل هستند') }}</span>
            </span>
            <x-nx::avatar-group :people="$viewers" :max="4" :label="$say('People viewing the release notes', 'بینندگان یادداشت‌های انتشار')" />
        </span>
        <x-nx::button size="sm" variant="secondary" icon="plus" wire:click="ping('{{ $say('Invite sent', 'دعوت‌نامه رفت') }}')">{{ $say('Invite', 'دعوت') }}</x-nx::button>
    </div>
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-subtle)">
        {{ $say('Four of the seven shown, three folded away. A src per person seats the real face; the initials stay underneath as the fallback.', 'هفت نفرند، چهار نفر دیده می‌شوند و سه نفر جمع می‌شوند. با src برای هر نفر چهرهٔ واقعی می‌نشیند و حروف اول، fallback زیرین می‌مانند.') }}
    </p>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The crew on a project card', 'تیمِ یک کارت پروژه') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('size="sm" for tight rows: the group rides the card footer beside its own member count, and max="4" keeps the footer one line tall.', 'size="sm" برای ردیف‌های کم‌جا: گروه در پانویس کارت کنار شمارِ اعضایش می‌نشیند و max="4" پانویس را یک‌خطی نگه می‌دارد.') }}
        </p>
        <article style="display: grid; gap: .9rem; padding: 1.1rem 1.25rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
            <div class="pg-row" style="justify-content: space-between">
                <strong style="font-weight: 600">{{ $say('Shop billing', 'مالیِ فروشگاه') }}</strong>
                <x-nx::badge tone="warning" dot>{{ $say('Due Thursday', 'موعد پنجشنبه') }}</x-nx::badge>
            </div>
            <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
                {{ NabuXUI::formatNumber(3).' '.$say('open issues', 'مشکل باز').' · '.$say('last deploy', 'آخرین انتشار').': '.NabuXUI::formatNumber(2).' '.$say('days ago', 'روز پیش') }}
            </p>
            <div class="pg-row" style="justify-content: space-between">
                <span class="pg-row" style="gap: .75rem">
                    <x-nx::avatar-group :people="$team" :max="4" size="sm" :label="$say('Shop billing team', 'تیم مالی فروشگاه')" />
                    <span style="font-size: var(--nx-text-sm); color: var(--nx-text-muted)">{{ NabuXUI::formatNumber(count($team)).' '.$say('members', 'عضو') }}</span>
                </span>
                <x-nx::button size="sm" variant="ghost" wire:click="ping('{{ $say('Team panel opened', 'پنل تیم باز شد') }}')">{{ $say('Manage', 'مدیریت') }}</x-nx::button>
            </div>
        </article>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The weekly review’s guests', 'مهمانان بازبینی هفتگی') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('size="lg" when the row has room, and a ceiling above the list’s length shows everyone — so no +n appears at all.', 'وقتی ردیف جا دارد size="lg"؛ سقفی بالاتر از طول فهرست همه را نشان می‌دهد — پس +n اصلاً ظاهر نمی‌شود.') }}
        </p>
        <div class="pg-row" style="gap: 1.25rem; justify-content: space-between; padding: 1rem 1.25rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface-2)">
            <x-nx::avatar-group :people="$guests" size="lg" :max="8" :label="$say('Guests of the weekly product review', 'مهمانان بازبینی هفتگی محصول')" />
            <x-nx::button size="sm" variant="secondary" icon="plus" wire:click="ping('{{ $say('Invite link copied', 'پیوند دعوت کپی شد') }}')">{{ $say('Invite a guest', 'دعوت مهمان') }}</x-nx::button>
        </div>
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-subtle)">
            {{ $say('Every face is a tab stop with its name as the tooltip — the group label names the gathering for screen readers.', 'هر چهره یک توقف Tab است و نامش تول‌تیپ می‌شود — برچسب گروه هم نامِ همین جمع را به صفحه‌خوان می‌گوید.') }}
        </p>
    </section>
</div>
