{{--
    kbd where it belongs: a shortcuts panel beside the command palette story,
    and inline references inside a sentence. Key glyphs stay LTR even in the
    Persian page — the row wraps them in dir="ltr".
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $shortcuts = [
        ['do' => $say('Open the command palette', 'باز کردن پالت فرمان'), 'keys' => ['⌘', 'K']],
        ['do' => $say('New task on this board', 'تسک تازه روی این برد'), 'keys' => ['N']],
        ['do' => $say('Search everything', 'جست‌وجوی همه‌چیز'), 'keys' => ['/', 'S']],
        ['do' => $say('Toggle the theme', 'عوض کردن تم'), 'keys' => ['⌘', '⇧', 'L']],
        ['do' => $say('Close any dialog or drawer', 'بستن هر دیالوگ و کشویی'), 'keys' => ['Esc']],
        ['do' => $say('Move between tabs', 'جابه‌جایی بین تب‌ها'), 'keys' => ['←', '→']],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The shortcut card people actually keep open', 'کارت میانبرهایی که واقعاً باز نگه می‌دارند') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Stack one kbd per key with a plus or nothing between them — the mono face and the pressed edge do the rest.', 'برای هر کلید یک kbd پشت‌سرهم — فونت مونو و لبهٔ فشرده بقیه‌اش را می‌کند.') }}
        </p>
    </div>
    <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .5rem">
        @foreach ($shortcuts as $shortcut)
            <li class="pg-row" style="justify-content: space-between; padding: .55rem .9rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
                <span>{{ $shortcut['do'] }}</span>
                <span class="pg-row" dir="ltr" style="gap: .3rem">
                    @foreach ($shortcut['keys'] as $key)
                        <x-nx::kbd>{{ $key }}</x-nx::kbd>
                    @endforeach
                </span>
            </li>
        @endforeach
    </ul>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Inside a sentence', 'درون جمله') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('Refer to a key mid-thought without breaking the line height — kbd is sized to the text around it.', 'وسط جمله به کلیدی ارجاع بدهید بدون شکستن ارتفاع خط — kbd هم‌اندازهٔ متن اطرافش است.') }}</p>
        <p style="margin: 0">
            {{ $say('Press', 'دکمهٔ') }}
            <x-nx::kbd>⌘</x-nx::kbd> <x-nx::kbd>K</x-nx::kbd>
            {{ $say('to jump anywhere; hold', 'را بزنید تا هرجا بپرید؛') }}
            <x-nx::kbd>Shift</x-nx::kbd>
            {{ $say('while clicking to select a range, and', 'را نگه دارید و بزنید تا بازه انتخاب شود و') }}
            <x-nx::kbd>?</x-nx::kbd>
            {{ $say('brings this card back.', 'همین کارت را برمی‌گرداند.') }}
        </p>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('On Linux and Windows', 'در لینوکس و ویندوز') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('Nothing is hardcoded to the Mac — swap the glyph per platform and the component could not care less.', 'هیچ‌چیز به مک سخت‌کد نشده — نشان را برای هر پلتفرم عوض کنید؛ کامپوننت کاری به آن ندارد.') }}</p>
        <div class="pg-row" dir="ltr" style="gap: .3rem">
            <x-nx::kbd>Ctrl</x-nx::kbd> <x-nx::kbd>K</x-nx::kbd>
            <span style="color: var(--nx-text-subtle)">·</span>
            <x-nx::kbd>Ctrl</x-nx::kbd> <x-nx::kbd>Shift</x-nx::kbd> <x-nx::kbd>L</x-nx::kbd>
        </div>
        <p style="margin: 0; color: var(--nx-text-subtle); font-size: var(--nx-text-sm)">
            {{ $say('A kbd a day keeps the “how do I…” tickets away.', 'روزی یک kbd، تیکت‌های «چطور این کار را…» را دور می‌کند.') }}
        </p>
    </section>
</div>
