{{--
    The avatar among people: a roster with presence rings, then an integrations
    grid of square tiles. No photos here — initials are the honest fallback the
    component ships with, and they stay when an image fails.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $roster = [
        ['name' => 'مریم صادقی', 'role' => $say('Product designer', 'طراح محصول'), 'status' => 'online', 'seen' => $say('now', 'الان')],
        ['name' => 'سامان دهقان', 'role' => $say('Backend engineer', 'مهندس بک‌اند'), 'status' => 'busy', 'seen' => $say('in a call', 'در تماس')],
        ['name' => 'Nguyen Linh', 'role' => $say('Support lead', 'سرپرست پشتیبانی'), 'status' => 'away', 'seen' => $say('12 min ago', '۱۲ دقیقه پیش')],
        ['name' => 'هانیه کاظمی', 'role' => $say('Data analyst', 'تحلیلگر داده'), 'status' => null, 'seen' => $say('3 days ago', '۳ روز پیش')],
    ];
    $integrations = [
        ['name' => 'GitHub', 'icon' => 'grid'], ['name' => 'Slack', 'icon' => 'message'],
        ['name' => 'Figma', 'icon' => 'wand'], ['name' => 'Stripe', 'icon' => 'chart'],
        ['name' => 'Notion', 'icon' => 'file'], ['name' => 'Vercel', 'icon' => 'zap'],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The on-call roster, with presence', 'لیست شیفت، با حضور') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The ring says it before the text does: green online, red busy, amber away — and no ring for the rest. The name doubles as the aria label.', 'حلقه قبل از متن می‌گوید: سبز آنلاین، سرخ مشغول، کهربایی غایب — و بدون حلقه برای بقیه. نام، برچسب aria هم هست.') }}
        </p>
    </div>
    <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .5rem">
        @foreach ($roster as $person)
            <li class="pg-row" style="justify-content: space-between; padding: .6rem .9rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
                <span class="pg-row" style="gap: .75rem">
                    <x-nx::avatar :name="$person['name']" size="lg" :status="$person['status']" />
                    <span style="display: grid">
                        <strong style="font-weight: 600">{{ $person['name'] }}</strong>
                        <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $person['role'] }}</span>
                    </span>
                </span>
                <span style="color: var(--nx-text-subtle); font-size: var(--nx-text-sm)">{{ $person['seen'] }}</span>
            </li>
        @endforeach
    </ul>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Square tiles for integrations', 'کاشی‌های مربع برای اتصال‌ها') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('shape="square" turns the avatar into an app tile; initials replace missing logos.', 'با shape="square" آواتار کاشی اپ می‌شود؛ حروف اول به‌جای لوگوی جاافتاده می‌نشیند.') }}</p>
        <div class="pg-row" style="gap: .75rem">
            @foreach ($integrations as $app)
                <x-nx::avatar :name="$app['name']" shape="square" size="lg" />
            @endforeach
        </div>
        <x-nx::button size="sm" variant="secondary" icon="plus" wire:click="ping('{{ $say('Integration gallery opened', 'گالری اتصال‌ها باز شد') }}')">{{ $say('Connect another', 'اتصال یکی دیگر') }}</x-nx::button>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Sizes, one face', 'اندازه‌ها، یک چهره') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('xs to xl with the status ring scaling along; hover a face — its name is the tooltip here.', 'از xs تا xl با حلقهٔ وضعیت که هم‌مقیاس می‌ماند؛ روی چهره بروید — نامش اینجا تول‌تیپ است.') }}</p>
        <div class="pg-row" style="gap: 1rem">
            <x-nx::tooltip text="مریم صادقی — آنلاین"><x-nx::avatar name="مریم صادقی" size="xs" status="online" tabindex="0" /></x-nx::tooltip>
            <x-nx::tooltip text="مریم صادقی — آنلاین"><x-nx::avatar name="مریم صادقی" size="sm" status="online" tabindex="0" /></x-nx::tooltip>
            <x-nx::tooltip text="مریم صادقی — آنلاین"><x-nx::avatar name="مریم صادقی" status="online" tabindex="0" /></x-nx::tooltip>
            <x-nx::tooltip text="مریم صادقی — آنلاین"><x-nx::avatar name="مریم صادقی" size="lg" status="online" tabindex="0" /></x-nx::tooltip>
            <x-nx::tooltip text="مریم صادقی — آنلاین"><x-nx::avatar name="مریم صادقی" size="xl" status="online" tabindex="0" /></x-nx::tooltip>
        </div>
        <p style="margin: 0; color: var(--nx-text-subtle); font-size: var(--nx-text-sm)">{{ $say('Latin names take the first letters of both words; Persian too — no fake photos needed.', 'نام‌های لاتین دو حرف اول را می‌گیرند؛ فارسی هم همین‌طور — عکس جعلی لازم نیست.') }}</p>
    </section>
</div>
