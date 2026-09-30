{{--
    The comparison table's real scenarios: the pricing page with a
    recommended plan, then a shorter hardware-spec table with text values.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The pricing page, verbatim', 'صفحهٔ قیمت‌گذاری، واژه‌به‌واژه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The Growth column carries the lit ring; the checks pop in row by row as the table arrives, and true/false values are spoken as words by screen readers.', 'ستون رشد حلقهٔ روشن را دارد؛ تیک‌ها با آمدن جدول ردیف‌به‌ردیف پاپ می‌شوند و مقدارهای بله/خیر برای صفحه‌خوان‌ها با واژه خوانده می‌شوند.') }}
        </p>
    </div>
    <x-nx::comparison-table
        :caption="$say('Compare Nabu plans', 'مقایسهٔ پلن‌های نابو')" recommended="growth"
        :recommended-label="$fa ? 'پیشنهاد ما' : 'Our pick'"
        :plans="[
            ['id' => 'starter', 'name' => $say('Starter', 'آغاز'), 'price' => $fa ? '۰' : '$0', 'period' => $say('/mo', '/ماه'), 'description' => $say('For trying Nabu out', 'برای امتحانِ نابو'), 'action' => ['label' => $say('Start free', 'شروع رایگان'), 'href' => '#']],
            ['id' => 'growth', 'name' => $say('Growth', 'رشد'), 'price' => $fa ? '۴۹$' : '$49', 'period' => $say('/mo', '/ماه'), 'description' => $say('For growing support teams', 'برای تیم‌های پشتیبانیِ روبه‌رشد'), 'action' => ['label' => $say('Choose Growth', 'انتخاب رشد'), 'href' => '#']],
            ['id' => 'scale', 'name' => $say('Scale', 'مقیاس'), 'price' => $fa ? '۱۹۹$' : '$199', 'period' => $say('/mo', '/ماه'), 'description' => $say('For global operations', 'برای عملیات جهانی'), 'action' => ['label' => $say('Talk to sales', 'گفت‌وگو با فروش'), 'href' => '#']],
        ]"
        :features="[
            ['group' => $say('Agents', 'ایجنت‌ها'), 'label' => $say('Languages', 'زبان‌ها'), 'values' => ['starter' => $fa ? '۳' : '3', 'growth' => $fa ? '۱۲' : '12', 'scale' => '40+']],
            ['group' => $say('Agents', 'ایجنت‌ها'), 'label' => $say('Voice replies', 'پاسخ صوتی'), 'hint' => $say('WhatsApp and Telegram', 'واتساپ و تلگرام'), 'values' => ['starter' => false, 'growth' => true, 'scale' => true]],
            ['group' => $say('Agents', 'ایجنت‌ها'), 'label' => $say('Custom knowledge', 'دانش اختصاصی'), 'values' => ['starter' => true, 'growth' => true, 'scale' => true]],
            ['group' => $say('Channels', 'کانال‌ها'), 'label' => $say('WhatsApp & Telegram', 'واتساپ و تلگرام'), 'values' => ['starter' => true, 'growth' => true, 'scale' => true]],
            ['group' => $say('Channels', 'کانال‌ها'), 'label' => $say('Phone lines', 'خطوط تلفنی'), 'values' => ['starter' => false, 'growth' => false, 'scale' => true]],
            ['group' => $say('Security', 'امنیت'), 'label' => 'SSO / SAML', 'values' => ['starter' => false, 'growth' => false, 'scale' => true]],
            ['group' => $say('Security', 'امنیت'), 'label' => $say('Audit log', 'گزارش حسابرسی'), 'values' => ['starter' => false, 'growth' => true, 'scale' => true]],
            ['group' => $say('Security', 'امنیت'), 'label' => $say('Data residency', 'محل نگهداری داده'), 'values' => ['starter' => '—', 'growth' => 'EU', 'scale' => $fa ? 'اروپا · آمریکا · آسیا' : 'EU · US · Asia']],
        ]" />
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Not only pricing', 'فقط قیمت‌گذاری نیست') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The same block comparing hardware — text values, no recommended column, the caption kept visible as a heading.', 'همان بلوک برای مقایسهٔ سخت‌افزار — مقدارهای متنی، بدون ستون پیشنهادی، و عنوان به‌عنوان سرصفحه نمایان.') }}
        </p>
    </div>
    <x-nx::comparison-table
        :caption="$say('The reader line, side by side', 'خط خواندن، کنار هم')" :caption-hidden="false"
        :plans="[
            ['id' => 'paper', 'name' => $say('Paper', 'کاغذ'), 'description' => $say('The original', 'نسخهٔ اصلی')],
            ['id' => 'ink', 'name' => $say('Ink', 'جوهر'), 'description' => $say('E-ink, front-lit', 'جوهر الکترونیکی، نور از جلو')],
            ['id' => 'glass', 'name' => $say('Glass', 'شیشه'), 'description' => $say('The tablet', 'تبلت')],
        ]"
        :features="[
            ['group' => $say('Display', 'نمایشگر'), 'label' => $say('Reads in sunlight', 'خواندن زیر آفتاب'), 'values' => ['paper' => true, 'ink' => true, 'glass' => false]],
            ['group' => $say('Display', 'نمایشگر'), 'label' => $say('Weight', 'وزن'), 'values' => ['paper' => $fa ? '۳۱۰ گرم' : '310 g', 'ink' => $fa ? '۱۹۵ گرم' : '195 g', 'glass' => $fa ? '۴۴۰ گرم' : '440 g']],
            ['group' => $say('Battery', 'باتری'), 'label' => $say('Page turns per charge', 'صفحه‌در هر شارژ'), 'values' => ['paper' => '∞', 'ink' => $fa ? '۲۱٬۰۰۰' : '21,000', 'glass' => $fa ? '۱۰ ساعت' : '10 h']],
            ['group' => $say('Battery', 'باتری'), 'label' => $say('Charges by', 'شارژ با'), 'values' => ['paper' => '—', 'ink' => 'USB-C', 'glass' => 'USB-C']],
        ]" />
</section>
