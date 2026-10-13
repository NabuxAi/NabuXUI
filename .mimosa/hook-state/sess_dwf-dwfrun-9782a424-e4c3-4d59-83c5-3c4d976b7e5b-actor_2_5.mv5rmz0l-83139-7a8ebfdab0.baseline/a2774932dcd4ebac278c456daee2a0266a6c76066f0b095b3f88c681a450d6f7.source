{{--
    A research assistant answering with sources: the answer streams in word by word,
    [n] markers open the source card. The second card is always Persian with English
    terms inside it — letters stay joined, mixed runs keep their order.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $sources = [
        ['title' => $say('Istanbul — climate overview', 'استانبول — نمای کلی اقلیم'), 'url' => 'https://en.wikipedia.org/wiki/Istanbul', 'snippet' => $say('Istanbul has a humid subtropical climate with hot, humid summers and cool, wet winters; most rain falls between November and April.', 'استانبول اقلیمی نیمه‌گرمسیری مرطوب دارد؛ تابستان‌های گرم و شرجی و زمستان‌های خنک و بارانی، و بیشتر بارش میان آبان تا اردیبهشت است.')],
        ['title' => $say('Air quality index — monthly averages', 'شاخص کیفیت هوا — میانگین ماهانه'), 'url' => 'https://www.iqair.com/turkey/istanbul', 'snippet' => $say('Autumn and early winter show the highest particulate readings, as temperature inversions trap pollution over the city.', 'پاییز و آغاز زمستان بیشترین ذرات معلق را نشان می‌دهند، چون وارونگی دما آلودگی را روی شهر نگه می‌دارد.')],
        ['title' => $say('Visiting Türkiye: best seasons', 'سفر به ترکیه: بهترین فصل‌ها'), 'domain' => 'lonelyplanet.com', 'snippet' => $say('Spring (March–May) is mild across most of the country and coincides with tulip season.', 'بهار (فروردین تا خرداد) در بیشتر کشور معتدل است و با فصل لاله هم‌زمان می‌شود.')],
    ];
    $answer = $say(
        "The best time to visit Istanbul is spring, from late March to May [1]. Days are mild, the tulips are in full bloom, and the city is green after the winter rains [3].\n\nAutumn is pleasant too, but late in the season air quality drops as inversions trap smog over the city [2], so plan outdoor days early in October.",
        "بهترین زمان سفر به استانبول بهار است، از اواخر اسفند تا اردیبهشت [1]. روزها معتدل‌اند، لاله‌ها به اوج شکوفایی رسیده‌اند و شهر پس از باران‌های زمستان سبز است [3].\n\nپاییز هم دلپذیر است، اما در اواخر فصل کیفیت هوا افت می‌کند چون وارونگی دما دود را روی شهر نگه می‌دارد [2]؛ پس روزهای بیرون از خانه را برای اوایل مهر بگذارید."
    );
    $mixed = 'برای فرم‌های بلند در Livewire و Inertia، اعتبارسنجی را سمت سرور نگه دارید و فقط پیام خطا را زنده نشان دهید [1]. کتابخانهٔ NabuXUI هر دو را با یک CSS پوشش می‌دهد.';
@endphp
<style>
    .agd-answer { display: grid; gap: .875rem; max-inline-size: 44rem; padding: 1.25rem 1.375rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-bg-subtle); }
    .agd-q { margin: 0; font: 650 var(--nx-text-lg) / 1.4 var(--nx-font-display); }
    .agd-meta { display: flex; align-items: center; gap: .5rem; margin: 0; color: var(--nx-text-subtle); font-size: var(--nx-text-xs); }
</style>

<article class="agd-answer" x-data>
    <p class="agd-q">{{ $say('When is the best time of year to visit Istanbul?', 'بهترین زمان سال برای سفر به استانبول کی است؟') }}</p>
    <x-nx::streaming-text :text="$answer" :sources="$sources" simulate :interval="70"
        :cite-label="$say('Source :n', 'منبع :n')" :open-label="$say('Open source', 'باز کردن منبع')" />
    <div class="pg-row" style="justify-content: space-between">
        <p class="agd-meta">{{ \NabuXUI\NabuXUI::icon('globe') }} {{ $say('3 sources · tap a number to see it', '۳ منبع · روی شماره بزنید تا ببینید') }}</p>
        <x-nx::button size="sm" variant="ghost" icon="sparkles" x-on:click="$el.closest('article').querySelector('.nx-stream').dispatchEvent(new CustomEvent('nx-replay'))">{{ $say('Regenerate', 'تولید دوباره') }}</x-nx::button>
    </div>
</article>

<article class="agd-answer" dir="rtl" lang="fa" x-data>
    <p class="agd-q">اعتبارسنجی فرم را کجا انجام بدهم؟</p>
    <x-nx::streaming-text :text="$mixed" simulate :interval="90" cite-label="منبع :n" open-label="باز کردن منبع" :sources="[
        ['title' => 'Validation — Livewire docs', 'url' => 'https://livewire.laravel.com/docs/validation', 'snippet' => 'Livewire runs validation on the server and re-renders only the error messages that changed.'],
    ]" />
    <div class="pg-row">
        <x-nx::button size="sm" variant="ghost" icon="sparkles" x-on:click="$el.closest('article').querySelector('.nx-stream').dispatchEvent(new CustomEvent('nx-replay'))">دوباره</x-nx::button>
    </div>
</article>
