{{--
    Context menus where people expect them: a file grid in a drive (with a
    "Share" submenu, a disabled item and a destructive one, each pick sent to
    the server) and a kanban card. Right-click, long-press on touch, or focus a
    card and press Shift+F10.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $files = [
        ['name' => $say('Q3 report.pdf', 'گزارش فصل سوم.pdf'), 'meta' => $say('2.4 MB · edited today', '۲٫۴ مگابایت · ویرایش امروز'), 'icon' => 'file'],
        ['name' => $say('Brand assets', 'دارایی‌های برند'), 'meta' => $say('48 items', '۴۸ مورد'), 'icon' => 'folder'],
        ['name' => $say('Launch video.mp4', 'ویدیوی رونمایی.mp4'), 'meta' => $say('186 MB · 2 days ago', '۱۸۶ مگابایت · ۲ روز پیش'), 'icon' => 'play'],
        ['name' => $say('Hero shot.png', 'عکس اصلی.png'), 'meta' => $say('3.1 MB · last week', '۳٫۱ مگابایت · هفتهٔ پیش'), 'icon' => 'image'],
    ];
    $actions = [
        ['heading' => true, 'label' => $say('File', 'فایل')],
        ['id' => 'open', 'label' => $say('Open', 'باز کردن'), 'icon' => 'external-link', 'shortcut' => '↵'],
        ['id' => 'rename', 'label' => $say('Rename', 'تغییر نام'), 'icon' => 'edit', 'shortcut' => 'F2'],
        ['id' => 'copy', 'label' => $say('Duplicate', 'تکثیر'), 'icon' => 'copy', 'shortcut' => '⌘D'],
        ['label' => $say('Share', 'اشتراک'), 'icon' => 'users', 'items' => [
            ['id' => 'share-link', 'label' => $say('Copy link', 'کپی پیوند'), 'icon' => 'copy'],
            ['id' => 'share-mail', 'label' => $say('Send by email', 'ارسال با ایمیل'), 'icon' => 'mail'],
            ['separator' => true],
            ['id' => 'share-team', 'label' => $say('Everyone at Nabu', 'همهٔ اعضای نابو'), 'icon' => 'globe'],
        ]],
        ['id' => 'lock', 'label' => $say('Lock (owners only)', 'قفل (فقط مالک)'), 'icon' => 'lock', 'disabled' => true],
        ['separator' => true],
        ['id' => 'delete', 'label' => $say('Move to trash', 'انتقال به سطل'), 'icon' => 'trash', 'tone' => 'danger', 'shortcut' => '⌫'],
    ];
    $verbs = [
        'open' => $say('Opened', 'باز شد'), 'rename' => $say('Renaming', 'تغییر نام'), 'copy' => $say('Duplicated', 'تکثیر شد'),
        'share-link' => $say('Link copied', 'پیوند کپی شد'), 'share-mail' => $say('Email drafted', 'پیش‌نویس ایمیل آماده شد'),
        'share-team' => $say('Shared with everyone', 'با همه به اشتراک گذاشته شد'), 'delete' => $say('Moved to trash', 'به سطل رفت'),
    ];
@endphp
<style>
    .cm-drive { display: grid; grid-template-columns: repeat(auto-fill, minmax(11rem, 1fr)); gap: .75rem; }
    .cm-file { display: grid; gap: .5rem; padding: 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface); user-select: none; }
    .cm-file:hover { border-color: var(--nx-border-strong); }
    .cm-file .nx-icon { inline-size: 1.75rem; block-size: 1.75rem; color: var(--nx-accent-text); }
    .cm-file strong { font-weight: 600; font-size: var(--nx-text-sm); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .cm-file span { color: var(--nx-text-muted); font-size: var(--nx-text-xs); }
    .cm-card { display: grid; gap: .5rem; max-inline-size: 22rem; padding: 1rem 1.125rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface); box-shadow: var(--nx-shadow-sm); }
</style>

<section style="display: grid; gap: .75rem" aria-labelledby="cm-drive-title">
    <div class="pg-row" style="justify-content: space-between">
        <h3 class="pg-title" id="cm-drive-title" style="margin: 0">{{ $say('Shared drive', 'درایو مشترک') }}</h3>
        <x-nx::badge tone="info">{{ $say('Right-click a file', 'روی فایل راست‌کلیک کنید') }}</x-nx::badge>
    </div>
    <div class="cm-drive">
        @foreach ($files as $file)
            <x-nx::context-menu :items="$actions" :label="$file['name']" class="cm-file"
                x-on:nx-select="$wire.ping(({{ \Illuminate\Support\Js::from($verbs) }})[$event.detail] + ' — ' + {{ \Illuminate\Support\Js::from($file['name']) }})">
                {{ \NabuXUI\NabuXUI::icon($file['icon']) }}
                <strong>{{ $file['name'] }}</strong>
                <span>{{ $file['meta'] }}</span>
            </x-nx::context-menu>
        @endforeach
    </div>
</section>

<section style="display: grid; gap: .75rem" aria-labelledby="cm-board-title">
    <h3 class="pg-title" id="cm-board-title" style="margin: 0">{{ $say('Sprint board card', 'کارت تخته اسپرینت') }}</h3>
    <x-nx::context-menu class="cm-card" :label="$say('Card actions', 'کارهای کارت')" x-on:nx-select="$wire.ping($event.detail)" :items="[
        ['id' => $say('Moved to In progress', 'به «در حال انجام» رفت'), 'label' => $say('Move to In progress', 'انتقال به «در حال انجام»'), 'icon' => 'arrow-right'],
        ['id' => $say('Assigned to you', 'به شما سپرده شد'), 'label' => $say('Assign to me', 'سپردن به من'), 'icon' => 'user'],
        ['id' => $say('Marked urgent', 'فوری شد'), 'label' => $say('Mark urgent', 'علامت فوری'), 'icon' => 'zap'],
        ['separator' => true],
        ['id' => $say('Card archived', 'کارت بایگانی شد'), 'label' => $say('Archive', 'بایگانی'), 'icon' => 'trash', 'tone' => 'danger'],
    ]">
        <x-nx::badge tone="warning" dot>{{ $say('Review', 'بازبینی') }}</x-nx::badge>
        <strong>{{ $say('Fix RTL caret in the search field', 'رفع جهت نشانگر در فیلد جست‌وجو') }}</strong>
        <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('Long-press on a phone, Shift+F10 on a keyboard.', 'روی گوشی انگشت را نگه دارید؛ با کیبورد Shift+F10.') }}</span>
    </x-nx::context-menu>
</section>
