{{--
    Skeletons as the honest stand-in: while the members list is "fetching",
    rows of shimmering circles and lines hold the exact shape of the real
    thing, then get out of the way.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $members = [
        ['name' => 'مریم صادقی', 'role' => $say('Product designer', 'طراح محصول'), 'status' => 'online'],
        ['name' => 'سامان دهقان', 'role' => $say('Backend engineer', 'مهندس بک‌اند'), 'status' => 'busy'],
        ['name' => 'Nguyen Linh', 'role' => $say('Support lead', 'سرپرست پشتیبانی'), 'status' => 'away'],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div class="pg-row" style="justify-content: space-between">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The roster, mid-fetch', 'لیست تیم، وسط بارگذاری') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Press reload: the real rows leave, skeletons of the very same shape take their seats, and nothing on the page moves a pixel when the data returns.', 'بارگذاری دوباره را بزنید: ردیف‌های واقعی می‌روند، اسکلتون‌هایی با همان شکل می‌نشینند و وقتی داده برمی‌گردد هیچ پیکسلی جابه‌جا نمی‌شود.') }}
            </p>
        </div>
        <x-nx::button variant="secondary" icon="play" wire:click="save('{{ $say('Roster reloaded', 'لیست دوباره بارگیری شد') }}')">{{ $say('Reload roster', 'بارگذاری دوباره') }}</x-nx::button>
    </div>

    <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .5rem" wire:loading.remove wire:target="save">
        @foreach ($members as $member)
            <li class="pg-row" style="gap: .75rem; padding: .6rem .9rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
                <x-nx::avatar :name="$member['name']" :status="$member['status']" />
                <span style="display: grid; flex: 1">
                    <strong style="font-weight: 600">{{ $member['name'] }}</strong>
                    <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $member['role'] }}</span>
                </span>
                <x-nx::badge tone="neutral">{{ $say('active', 'فعال') }}</x-nx::badge>
            </li>
        @endforeach
    </ul>

    <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .5rem" wire:loading wire:target="save" aria-busy="true" aria-label="{{ $say('Loading the roster', 'بارگذاری لیست تیم') }}">
        @foreach (range(1, 3) as $i)
            <li class="pg-row" style="gap: .75rem; padding: .6rem .9rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
                <x-nx::skeleton shape="circle" width="2.5rem" />
                <span style="display: grid; gap: .5rem; flex: 1">
                    <x-nx::skeleton width="8rem" />
                    <x-nx::skeleton width="5.5rem" />
                </span>
                <x-nx::skeleton width="3.5rem" height="1.25rem" />
            </li>
        @endforeach
    </ul>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A stat card, not yet', 'کارت آمار، هنوز نه') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('Shapes: circle for the avatar, lines for text, block for the sparkline’s seat.', 'شکل‌ها: دایره برای آواتار، خط برای متن، بلوک برای جای اسپارک‌لاین.') }}</p>
        <div style="display: grid; gap: .75rem; padding: 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl)">
            <x-nx::skeleton width="6rem" />
            <x-nx::skeleton width="9rem" height="1.75rem" />
            <x-nx::skeleton shape="block" height="2.75rem" />
        </div>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Multi-line paragraphs', 'پاراگراف چندخطی') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">{{ $say('lines="3" renders a grid of rows with a shorter last line you steer with width — for article teasers.', 'با lines="3" سه ردیف با فاصلهٔ یکسان ساخته می‌شود و کوتاه‌کردن آخرین خط با width است — برای خلاصهٔ مقاله‌ها.') }}</p>
        <x-nx::skeleton :lines="2" />
        <x-nx::skeleton width="60%" />
    </section>
</div>
