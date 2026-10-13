{{--
    Pagination over a real list: 12 invoices, 5 per page, links that carry the
    page in the URL. The second pager sits mid-way through a long archive so
    the “…” gaps have somewhere honest to appear.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $invoices = collect(range(1, 12))->map(fn ($i) => [
        'id' => 1400 + $i,
        'who' => [
            $say('Nabu Studio', 'استودیو نابو'), $say('Aban Clinic', 'کلینیک آبان'), $say('Kavir Tech', 'تکنولوژی کویر'),
            $say('Studio Lume', 'استودیو لومه'), $say('Dena Books', 'کتاب‌های دنا'), $say('Rasa Labs', 'آزمایشگاه راسا'),
        ][$i % 6],
        'amount' => 3400000 + $i * 1250000,
    ])->all();

    $pages = (int) ceil(count($invoices) / 5);
    $page = max(1, min($pages, (int) request()->input('page', 1)));
    $slice = array_slice($invoices, ($page - 1) * 5, 5);

    $deepPage = max(1, min(19, (int) request()->input('deep', 7)));
    $deepUrl = fn ($p) => url()->current().'?page='.$page.'&deep='.$p;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The invoices, five at a time', 'فاکتورها، پنج‌تا پنج‌تا') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The links keep the page in the URL, so the back button and sharing both work; the pill under the active number says where you are.', 'لینک‌ها شمارهٔ صفحه را در URL نگه می‌دارند، پس دکمهٔ back و اشتراک‌گذاری هر دو کار می‌کنند؛ pill زیر شمارهٔ فعال می‌گوید کجایید.') }}
        </p>
    </div>
    <p style="margin: 0; color: var(--nx-text-subtle); font-size: var(--nx-text-sm)">
        {{ $say('Showing', 'نمایش') }}
        {{ NabuXUI::formatNumber(($page - 1) * 5 + 1) }}–{{ NabuXUI::formatNumber(min($page * 5, count($invoices))) }}
        {{ $say('of', 'از') }} {{ NabuXUI::formatNumber(count($invoices)) }}
    </p>
    <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .5rem">
        @foreach ($slice as $invoice)
            <li class="pg-row" style="justify-content: space-between; padding: .6rem .9rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
                <span class="pg-row" style="gap: .75rem">
                    <span dir="ltr" style="font: 500 var(--nx-text-sm) var(--nx-font-mono); color: var(--nx-text-muted)">#{{ NabuXUI::formatNumber($invoice['id']) }}</span>
                    <strong style="font-weight: 600">{{ $invoice['who'] }}</strong>
                </span>
                <span>{{ NabuXUI::formatNumber($invoice['amount']).' '.$say('Toman', 'تومان') }}</span>
            </li>
        @endforeach
    </ul>
    <x-nx::pagination :page="$page" :pages="$pages" :url="fn ($p) => url()->current().'?page='.$p" />
</section>

<section class="pg-box" style="gap: 1rem">
    <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Deep in the archive', 'عمق آرشیو') }}</h3>
    <p style="margin: 0; color: var(--nx-text-muted)">
        {{ $say('94 old issues on 19 pages: with a single sibling each side, the far ends fold into gaps — click through and watch the window slide.', '۹۴ شمارهٔ قدیمی در ۱۹ صفحه: با یک همسایه هر طرف، دو سر در «…» جمع می‌شوند — ورق بزنید و ببینید پنجره می‌لغزد.') }}
    </p>
    <div class="pg-row" style="justify-content: space-between">
        <span style="color: var(--nx-text-subtle); font-size: var(--nx-text-sm)">{{ $say('Page', 'صفحهٔ') }} {{ NabuXUI::formatNumber($deepPage) }} {{ $say('of', 'از') }} {{ NabuXUI::formatNumber(19) }}</span>
        <x-nx::pagination :page="$deepPage" :pages="19" :siblings="1" :url="$deepUrl" />
    </div>
</section>
