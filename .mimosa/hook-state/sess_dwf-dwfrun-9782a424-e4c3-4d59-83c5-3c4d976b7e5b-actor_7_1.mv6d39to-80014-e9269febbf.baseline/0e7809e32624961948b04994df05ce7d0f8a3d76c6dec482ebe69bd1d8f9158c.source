{{--
    The segmented control as it lives in Persian products: the plan's billing
    cycle re-priced the moment you pick (two options over one radiogroup), the
    sales report's range picker in a toolbar (sm + accent, with the 90-day
    option locked behind a higher plan) and the storefront's layout switch —
    grid or list, with icons inside the segments.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    /* 1 — the billing cycle, priced live */
    $cycle = (string) ($state['cycle'] ?? 'monthly');
    $monthlyPrice = 490000;
    $yearlyPrice = 4900000;
    $yearlySave = 12 * $monthlyPrice - $yearlyPrice; // two free months

    /* 2 — the report range */
    $range = (string) ($state['range'] ?? 'week');
    $ranges = [
        'today' => ['label' => $say('Today', 'امروز'), 'orders' => 34, 'revenue' => 18700000, 'basket' => 549000],
        'week' => ['label' => $say('This week', 'این هفته'), 'orders' => 212, 'revenue' => 114900000, 'basket' => 542000],
        'month' => ['label' => $say('This month', 'این ماه'), 'orders' => 894, 'revenue' => 482600000, 'basket' => 540000],
        'quarter' => ['label' => $say('90 days', '۹۰ روزه'), 'disabled' => true],
    ];
    $current = $ranges[$range] ?? $ranges['week'];

    /* 3 — the storefront layout */
    $view = (string) ($state['view'] ?? 'grid');
    $views = [
        'grid' => ['label' => $say('Grid', 'شبکه‌ای'), 'icon' => 'grid'],
        'list' => ['label' => $say('List', 'فهرستی'), 'icon' => 'menu'],
    ];
    $products = [
        ['name' => $say('Pocket lined notebook', 'دفتر خط‌دار جیبی'), 'price' => 86000, 'tag' => $say('Bestseller', 'پرفروش'), 'tone' => 'gold'],
        ['name' => $say('Blue gel pen', 'خودکار ژلی آبی'), 'price' => 45000, 'tag' => null, 'tone' => null],
        ['name' => $say('Whiteboard marker', 'ماژیک تابلوی سفید'), 'price' => 72000, 'tag' => $say('New', 'تازه'), 'tone' => 'accent'],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The plan’s billing cycle, re-priced as you pick', 'دورهٔ پرداخت پلن، همان‌جا که برمی‌گزینید دوباره قیمت می‌خورد') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Two segments over one radiogroup: the thumb springs to the checked radio and, because the radios are native, the arrow keys roam the group while the screen reader reads it under its label. wire:model.live re-renders the price line — the yearly pick spells out its two free months.', 'دو سگمنت روی یک radiogroup: thumb به رادیوی انتخاب‌شده می‌پرد و چون رادیوها بومی‌اند فلش‌های کیبورد در گروه می‌گردند و صفحه‌خوان کل گروه را زیر برچسبش می‌خواند. wire:model.live خط قیمت را بازمی‌سازد — انتخاب سالانه دو ماه رایگانش را هم رو می‌کند.') }}
        </p>
    </div>
    <div class="pg-row" style="justify-content: space-between">
        <x-nx::segmented
            :label="$say('Billing cycle', 'دورهٔ پرداخت')"
            :value="$cycle"
            :options="['monthly' => $say('Monthly', 'ماهانه'), 'yearly' => $say('Yearly', 'سالانه')]"
            wire:model.live="state.cycle" />
        @if ($cycle === 'yearly')
            <x-nx::badge tone="gold">{{ $say('2 months free', '۲ ماه رایگان') }}</x-nx::badge>
        @endif
    </div>
    <p style="margin: 0; color: var(--nx-text-muted)" aria-live="polite">
        @if ($cycle === 'yearly')
            {{ $say('One year for', 'یک سال:') }}
            <strong style="color: var(--nx-text); font-variant-numeric: tabular-nums">{{ NabuXUI::formatNumber($yearlyPrice).' '.$say('tomans', 'تومان') }}</strong>
            ·
            {{ $say('saves', 'کمتر از پرداخت ماهانه:') }}
            <strong style="color: var(--nx-text); font-variant-numeric: tabular-nums">{{ NabuXUI::formatNumber($yearlySave).' '.$say('tomans', 'تومان') }}</strong>
        @else
            {{ $say('Every month:', 'هر ماه:') }}
            <strong style="color: var(--nx-text); font-variant-numeric: tabular-nums">{{ NabuXUI::formatNumber($monthlyPrice).' '.$say('tomans', 'تومان') }}</strong>
            ·
            {{ $say('switch to yearly anytime', 'هر زمان خواستید سالانه‌اش کنید') }}
        @endif
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The sales report’s range picker', 'انتخاب بازهٔ گزارش فروش') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('size="sm" for a toolbar corner and tone="accent" so the thumb carries the brand colour. The 90-day option arrives disabled with its reason — it belongs to the Business plan — instead of vanishing; the stats row beneath follows the pick live, in the locale’s digits.', 'با size="sm" برای گوشهٔ نوار ابزار و tone="accent" تا thumb رنگ برند را با خودش ببرد. گزینهٔ ۹۰ روزه ازکارافتاده با دلیلش می‌آید — مال پلن کسب‌وکار است — نه اینکه غیب شود؛ ردیف آمار زیرین انتخاب را زنده و با ارقام همان زبان دنبال می‌کند.') }}
        </p>
    </div>
    <x-nx::segmented size="sm" tone="accent" :label="$say('Report range', 'بازهٔ گزارش')" :value="$range" :options="$ranges" wire:model.live="state.range" />
    <div class="pg-row" style="justify-content: space-between; padding: .9rem 1.1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)" aria-live="polite">
        <span style="display: grid; gap: .15rem">
            <span style="color: var(--nx-text-muted)">{{ $say('Range', 'بازه') }}</span>
            <strong style="font-weight: 600">{{ $current['label'] }}</strong>
        </span>
        <span style="display: grid; gap: .15rem">
            <span style="color: var(--nx-text-muted)">{{ $say('Orders', 'سفارش‌ها') }}</span>
            <strong style="font-weight: 600; font-variant-numeric: tabular-nums">{{ NabuXUI::formatNumber($current['orders']) }}</strong>
        </span>
        <span style="display: grid; gap: .15rem">
            <span style="color: var(--nx-text-muted)">{{ $say('Revenue', 'درآمد') }}</span>
            <strong style="font-weight: 600; font-variant-numeric: tabular-nums">{{ NabuXUI::formatNumber($current['revenue']).' '.$say('tomans', 'تومان') }}</strong>
        </span>
        <span style="display: grid; gap: .15rem">
            <span style="color: var(--nx-text-muted)">{{ $say('Average basket', 'میانگین سبد') }}</span>
            <strong style="font-weight: 600; font-variant-numeric: tabular-nums">{{ NabuXUI::formatNumber($current['basket']).' '.$say('tomans', 'تومان') }}</strong>
        </span>
    </div>
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('A disabled segment keeps its place and fades — the keyboard skips it and the screen reader announces it as dimmed, so the locked door stays visible.', 'سگمنت ازکارافتاده جایش را نگه می‌دارد و کم‌رنگ می‌شود — کیبورد از آن می‌پرد و صفحه‌خوان ازکارافتاده‌بودنش را اعلام می‌کند، پس درِ بسته از پنهان نمی‌رود.') }}
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The storefront’s layout switch', 'کلید چیدمان ویترین') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The icon-carrying face of the control: grid or list, each segment drawing its glyph from the core icon set. wire:model.live swaps the real product list below — the tiles become rows without leaving the page.', 'چهرهٔ آیکون‌دار کنترل: شبکه‌ای یا فهرستی، هر سگمنت نگاره‌اش را از مجموعهٔ آیکون هسته می‌کشد. wire:model.live فهرست واقعی کالاها را زیر عوض می‌کند — کاشی‌ها بدون ترک صفحه ردیف می‌شوند.') }}
        </p>
    </div>
    <x-nx::segmented :label="$say('Product layout', 'چیدمان کالاها')" :value="$view" :options="$views" wire:model.live="state.view" />
    @if ($view === 'list')
        <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .5rem">
            @foreach ($products as $product)
                <li class="pg-row" style="justify-content: space-between; padding: .6rem .9rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
                    <span class="pg-row" style="gap: .6rem">
                        <strong style="font-weight: 600">{{ $product['name'] }}</strong>
                        @if ($product['tag'])
                            <x-nx::badge :tone="$product['tone']">{{ $product['tag'] }}</x-nx::badge>
                        @endif
                    </span>
                    <strong style="font-weight: 600; font-variant-numeric: tabular-nums">{{ NabuXUI::formatNumber($product['price']).' '.$say('tomans', 'تومان') }}</strong>
                </li>
            @endforeach
        </ul>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(min(100%, 11rem), 1fr)); gap: 1rem">
            @foreach ($products as $product)
                <div style="display: grid; gap: .6rem; padding: .9rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface)">
                    <span aria-hidden="true" style="display: grid; place-items: center; block-size: 4.5rem; border-radius: var(--nx-radius-md); background: var(--nx-surface-2); color: var(--nx-text-subtle)">{{ NabuXUI::icon('image') }}</span>
                    <span class="pg-row" style="justify-content: space-between">
                        <strong style="font-weight: 600">{{ $product['name'] }}</strong>
                        @if ($product['tag'])
                            <x-nx::badge :tone="$product['tone']">{{ $product['tag'] }}</x-nx::badge>
                        @endif
                    </span>
                    <span style="color: var(--nx-text-muted); font-variant-numeric: tabular-nums">{{ NabuXUI::formatNumber($product['price']).' '.$say('tomans', 'تومان') }}</span>
                </div>
            @endforeach
        </div>
    @endif
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('The same two-way pick drives the file manager’s grid|list corner — with two or three options the segmented control beats a select: every choice stays one glance away, and the thumb’s spring tells you it landed.', 'همین انتخاب دوتایی، گوشهٔ grid|list مدیر فایل را هم می‌چرخاند — با دو سه گزینه سگمنت از select جلوتر است: هر انتخاب یک نگاه دورتر است و فنر thumb می‌گوید که جا افتاده.') }}
    </p>
</section>
