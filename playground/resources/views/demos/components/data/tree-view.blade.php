{{--
    The tree-view's real scenarios: the repo sidebar whose open file rides
    wire:model back to the server, the help-center picker that only speaks
    through events, and the fold-only org chart where selecting is off.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // The NabuXUI repo itself, the way a docs sidebar would list it.
    $repo = [
        ['id' => 'packages', 'label' => 'packages', 'icon' => 'folder', 'children' => [
            ['id' => 'core', 'label' => 'core', 'icon' => 'folder', 'children' => [
                ['id' => 'tokens.css', 'label' => 'tokens.css', 'icon' => 'file'],
                ['id' => 'data.css', 'label' => 'blocks/data.css', 'icon' => 'file', 'tone' => 'info'],
            ]],
            ['id' => 'livewire', 'label' => 'livewire', 'icon' => 'folder', 'children' => [
                ['id' => 'tree-view.blade.php', 'label' => 'tree-view.blade.php', 'icon' => 'file', 'tone' => 'accent'],
                ['id' => 'tree-view.ts', 'label' => 'alpine/blocks/tree-view.ts', 'icon' => 'file'],
            ]],
            ['id' => 'react', 'label' => 'react', 'icon' => 'folder', 'children' => [
                ['id' => 'TreeView.tsx', 'label' => 'TreeView.tsx', 'icon' => 'file'],
            ]],
        ]],
        ['id' => 'docs', 'label' => 'docs', 'icon' => 'folder', 'meta' => $say('2 guides', '۲ رهنما'), 'children' => [
            ['id' => 'BLOCKS.md', 'label' => 'BLOCKS.md', 'icon' => 'file'],
        ]],
        ['id' => 'playground', 'label' => 'playground', 'icon' => 'folder', 'children' => [
            ['id' => 'demo', 'label' => 'demos/components/data/tree-view.blade.php', 'icon' => 'file', 'tone' => 'gold'],
        ]],
    ];

    // The help-center a ticket gets routed through.
    $help = [
        ['id' => 'start', 'label' => $say('Getting started', 'شروع کار'), 'icon' => 'wand', 'children' => [
            ['id' => 'install', 'label' => $say('Install on your own server', 'نصب روی سرور خودتان'), 'tone' => 'info'],
            ['id' => 'whatsapp', 'label' => $say('Connect a WhatsApp number', 'اتصال شمارهٔ واتساپ'), 'tone' => 'success'],
        ]],
        ['id' => 'conversations', 'label' => $say('Conversations & agents', 'گفت‌وگو و ایجنت‌ها'), 'icon' => 'message', 'children' => [
            ['id' => 'fa-agent', 'label' => $say('Train the Persian agent', 'آموزش ایجنت فارسی'), 'tone' => 'accent'],
            ['id' => 'handover', 'label' => $say('Hand over to a human', 'واگذار به اپراتور'), 'tone' => 'warning'],
        ]],
        ['id' => 'billing', 'label' => $say('Billing', 'صورتحساب'), 'icon' => 'chart', 'meta' => $say('4 articles', '۴ مقاله'), 'children' => [
            ['id' => 'invoice', 'label' => $say('The monthly invoice', 'فاکتور ماهانه'), 'tone' => 'info'],
        ]],
    ];

    // The support team, as an org chart — folding only.
    $org = [
        ['id' => 'nabu', 'label' => $say('Nabu support', 'پشتیبانی نابو'), 'icon' => 'users', 'tone' => 'accent', 'children' => [
            ['id' => 'am', 'label' => $say('Morning shift', 'شیفت صبح'), 'icon' => 'sun', 'meta' => $say('Tehran', 'تهران'), 'children' => [
                ['id' => 'niloofar', 'label' => 'نیلوفر احمدی', 'icon' => 'user', 'meta' => 'فارسی · English', 'tone' => 'success'],
                ['id' => 'omar', 'label' => 'عمر حداد', 'icon' => 'user', 'meta' => 'العربية · English', 'tone' => 'success'],
            ]],
            ['id' => 'pm', 'label' => $say('Night shift', 'شیفت شب'), 'icon' => 'moon', 'meta' => $say('Tokyo', 'توکیو'), 'children' => [
                ['id' => 'kenji', 'label' => 'Kenji Sato', 'icon' => 'user', 'meta' => '日本語 · English', 'tone' => 'warning'],
            ]],
        ]],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem; max-inline-size: 34rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The repo, as the docs sidebar sees it', 'مخزن، از دید سایدبار مستندات') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The whole tree is one tab stop: the arrows walk the visible rows, →/← expand and collapse (swapped in RTL), Home/End run to the ends, Enter picks and Space folds. The open file rides wire:model.live back to the server, and the fold — chevron, colour and group — is a single state change.', 'کل درخت یک توقف تب دارد: فلش‌ها میان ردیف‌های پیداتردیده قدم می‌زنند، →/← باز و بسته می‌کند (در راست‌به‌چپ جابه‌جا می‌شود)، Home/End به دو سر می‌پرد، Enter انتخاب می‌کند و Space تا می‌زند. فایلِ باز با wire:model.live به سرور می‌رود و تا شدن — چرون، رنگ و خودِ گروه — یک تغییر وضعیت است.') }}
        </p>
    </div>
    <x-nx::tree-view
        :label="$say('Repository files', 'فایل‌های مخزن')"
        :nodes="$repo"
        :default-expanded="['packages', 'livewire']"
        :selected="$state['file'] ?? 'tree-view.blade.php'"
        wire:model.live="state.file" />
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('The open file on the server (wire:model.live):', 'فایل باز روی سرور (wire:model.live):') }}
        <code>{{ $state['file'] ?? '—' }}</code>
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem; max-inline-size: 30rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Routing a ticket through the help center', 'مسیر تیکت در مرکز راهنما') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('This one is unwired: the nodes you hand in are the truth, they are never edited in place, and every pick bubbles as nx-select — here it only raises a toast. Folds stay client-side and speak through nx-expand ({ id, open }); parent rows carry their child count in your locale’s digits.', 'این درخت به هیچ سیمی وصل نیست: گره‌هایی که می‌دهید مرجع حقیقت‌اند، هرگز در جا ویرایش نمی‌شوند و هر انتخاب با رویداد nx-select بیرون می‌رود — اینجا فقط توست می‌زند. تاخوها سمت مرورگر می‌مانند و با nx-expand خبر می‌دهند ({ id, open })؛ ردیف‌های پدر هم شمار فرزندانشان را با ارقام زبان شما می‌پوشند.') }}
        </p>
    </div>
    <x-nx::tree-view
        :label="$say('Help center categories', 'دسته‌بندی مرکز راهنما')"
        :nodes="$help"
        :default-expanded="['start']"
        selected="whatsapp"
        x-on:nx-select="$wire.ping('{{ $say('Picked', 'انتخاب شد') }}: ' + $event.detail)" />
</section>

<section class="pg-box" style="gap: 1.25rem; max-inline-size: 30rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The support team chart — folds only', 'چارت تیم پشتیبانی — فقط تا شدن') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('With selectable="false" the rows only fold: aria-selected never lands and Enter does nothing, while the keyboard stays fully alive — the arrows still walk, Space folds a parent and the twist is the pointer affordance. Every fold reports back as nx-expand, which this scene toasts.', 'با selectable="false" ردیف‌ها فقط تا می‌خورند: aria-selected هرگز نمی‌نشیند و Enter بی‌اثر است، اما کیبورد زنده می‌ماند — فلش‌ها همچنان می‌گردند، Space پدر را تا می‌زند و ناحیهٔ چرون دستِ موس است. هر تا شدن با nx-expand خبر می‌دهد که این صحنه توست می‌زند.') }}
        </p>
    </div>
    <x-nx::tree-view
        :label="$say('Support team chart', 'چارت تیم پشتیبانی')"
        :nodes="$org"
        :default-expanded="['nabu', 'am']"
        selectable="false"
        x-on:nx-expand="$wire.ping($event.detail.id + ' · ' + ($event.detail.open ? '{{ $say('open', 'باز') }}' : '{{ $say('closed', 'بسته') }}'))" />
</section>
