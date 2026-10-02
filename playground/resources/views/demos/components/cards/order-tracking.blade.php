{{--
    The order-tracking block's real scenarios: a parcel in transit whose route
    advances through a Livewire round trip (the fill, the badge and the event
    log morph into their new state), a failed delivery where each step carries
    its own state, and a just-placed order with an empty event log.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // Scenario 1 — a parcel on the road. `current` rides a Livewire property,
    // so one button walks the whole route and every part morphs on re-render.
    $stage = min(5, max(0, (int) ($state['stage'] ?? 3)));
    $next = ($stage + 1) % 6;
    $advanceLabel = $stage >= 5 ? $say('Track a new parcel', 'پیگیری از نو') : $say('Advance one step', 'یک گام جلوتر');
    $advancePing = $stage >= 5
        ? $say('A fresh parcel is on its way', 'مرسولهٔ تازه در راه است')
        : $say('The parcel moved on', 'مرسوله یک گام جلوتر رفت');
    // @js() is not compiled inside a component tag's attributes, so the JS
    // literal for the toast is built here and echoed into wire:click instead.
    $advancePingJs = Illuminate\Support\Js::from($advancePing);

    $steps = [
        ['id' => 'placed', 'title' => $say('Order placed', 'ثبت سفارش'), 'icon' => 'file', 'time' => now()->subDays(3)],
        ['id' => 'paid', 'title' => $say('Paid', 'پرداخت'), 'icon' => 'check', 'time' => now()->subHours(71)],
        ['id' => 'packed', 'title' => $say('Packed', 'بسته‌بندی'), 'icon' => 'layers', 'time' => now()->subHours(30)],
        ['id' => 'transit', 'title' => $say('In transit', 'در مسیر'), 'description' => $say('Qom → Tehran', 'قم → تهران'), 'icon' => 'zap', 'time' => now()->subHours(20)],
        ['id' => 'out', 'title' => $say('With the courier', 'تحویل به پیک'), 'icon' => 'user'],
        ['id' => 'done', 'title' => $say('Delivered', 'تحویل شد'), 'icon' => 'home'],
    ];

    $allEvents = [
        ['id' => 'e1', 'title' => $say('Order placed and sent to the warehouse', 'سفارش ثبت و به انبار فرستاده شد'), 'place' => $say('Tehran', 'تهران'), 'time' => now()->subDays(3), 'tone' => 'success'],
        ['id' => 'e2', 'title' => $say('Payment confirmed', 'پرداخت تأیید شد'), 'description' => $say('Bank gateway', 'درگاه بانکی'), 'time' => now()->subHours(71), 'tone' => 'info'],
        ['id' => 'e3', 'title' => $say('Packing ran 40 minutes late', 'بسته‌بندی ۴۰ دقیقه دیرتر انجام شد'), 'place' => $say('Qom', 'قم'), 'time' => now()->subHours(30), 'tone' => 'warning'],
        ['id' => 'e4', 'title' => $say('Left the distribution centre', 'بسته از مرکز توزیع خارج شد'), 'place' => $say('Qom', 'قم'), 'time' => now()->subHours(20), 'tone' => 'success'],
        ['id' => 'e5', 'title' => $say('Handed to the local courier', 'تحویل به پیک محلی'), 'description' => $say('Courier code ۷۷۴۱', 'کد پیک: ۷۷۴۱'), 'time' => now()->subHours(2), 'tone' => 'success'],
        ['id' => 'e6', 'title' => $say('Delivered', 'تحویل شد'), 'description' => $say('Signed by the recipient', 'امضا شد توسط گیرنده'), 'time' => now()->subMinutes(10), 'tone' => 'success'],
    ];
    $events = array_slice($allEvents, 0, $stage + 1);

    $status = $stage >= 5 ? 'success' : 'running';
    $eta = $stage >= 5
        ? $say('Delivered', 'تحویل شد')
        : ($stage >= 4 ? $say('Today', 'امروز') : $say('In 2 days', '۲ روز دیگر'));

    // Scenario 2 — a failed delivery: no `current` at all, every step carries
    // its own state, and the event log stitches warning and danger rows.
    $failedSteps = [
        ['id' => 'placed', 'title' => $say('Order placed', 'ثبت سفارش'), 'state' => 'complete', 'icon' => 'file', 'time' => now()->subDays(2)],
        ['id' => 'transit', 'title' => $say('In transit', 'در مسیر'), 'state' => 'complete', 'icon' => 'zap', 'time' => now()->subHours(26)],
        ['id' => 'out', 'title' => $say('With the courier', 'تحویل به پیک'), 'state' => 'current', 'icon' => 'user', 'time' => now()->subHours(3)],
        ['id' => 'done', 'title' => $say('Delivered', 'تحویل شد'), 'state' => 'upcoming', 'icon' => 'home'],
        ['id' => 'back', 'title' => $say('Back to the warehouse', 'بازگشت به انبار'), 'state' => 'upcoming', 'icon' => 'layers'],
    ];
    $failedEvents = [
        ['id' => 'f1', 'title' => $say('Delivery failed — recipient unreachable', 'تحویل ناموفق — گیرنده در دسترس نبود'), 'place' => $say('Tehran, district ۶', 'تهران، منطقهٔ ۶'), 'time' => now()->subHours(3), 'tone' => 'danger'],
        ['id' => 'f2', 'title' => $say('SMS sent to the recipient', 'پیامک به گیرنده ارسال شد'), 'description' => $say('Second attempt tomorrow', 'تلاش دوم فردا'), 'time' => now()->subHours(2), 'tone' => 'info'],
        ['id' => 'f3', 'title' => $say('Held at the courier hub', 'نزد پیک نگه داشته شد'), 'description' => $say('Returns to the warehouse after 3 days', 'پس از ۳ روز به انبار برمی‌گردد'), 'time' => now()->subMinutes(40), 'tone' => 'warning'],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div class="pg-row" style="justify-content: space-between">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A parcel on the Qom → Tehran road', 'مرسولهٔ در راه قم → تهران') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('The fill draws itself up to the current step (a pinging marker while it travels), the badge breathes while the order runs and the event log slides in row by row. current rides a Livewire property here, so the button walks the whole route — and on a real page wire:poll.30s does it without anyone clicking.', 'پرشدنِ خط تا گام جلوترِ رهگیری خودش کشیده می‌شود (در حین حرکت نشانگر پینگ می‌زند)، نشانِ وضعیت تا وقتی سفارش در جریان است نفس می‌کشد و رویدادها یکی‌یکی سُر می‌خورند. اینجا current روی یک پراپرتی Livewire نشسته، پس دکمه کل مسیر را طی می‌کند — و در صفحهٔ واقعی wire:poll.30s همان کار را بی‌کلیک انجام می‌دهد.') }}
            </p>
        </div>
        <x-nx::button variant="primary" icon="zap"
            wire:click="$set('state.stage', {{ $next }}); ping({{ $advancePingJs }})">
            {{ $advanceLabel }}
        </x-nx::button>
    </div>
    <x-nx::order-tracking
        number="۱۴۰۴-۰۸۲۱۵"
        :status="$status"
        :carrier="$say('Iran Post', 'پست ایران')"
        :eta="$eta"
        :label="$say('Tracking order 1404-08215', 'رهگیری سفارش ۱۴۰۴-۰۸۲۱۵')"
        :steps="$steps"
        :current="$stage"
        :events="$events" />
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('Dates can be real dates (they read as relative time in the app’s language) or plain labels; the step numbers are formatted in the app’s digits.', 'تاریخ‌ها می‌توانند تاریخ واقعی باشند (به‌صورت زمان نسبی به زبان اپ خوانده می‌شوند) یا برچسب ساده؛ شمارهٔ گام‌ها هم با ارقام همان زبان قالب‌بندی می‌شود.') }}
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A delivery that failed', 'تحویلی که ناموفق بود') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('No current here at all — every step carries its own state, so the route can fork away from a straight line (the parcel may yet go back to the warehouse). The failed badge stops breathing with :live="false" and the log stitches danger, info and warning rows by tone.', 'اینجا اصلاً current نداریم — هر گام وضعیت خودش را با خودش دارد، پس مسیر می‌تواند از خط مستقیم جدا شود (مرسوله ممکن است به انبار برگردد). نشانِ failed با :live="false" از نفس‌کشیدن می‌ایستد و گزارش، ردیف‌های danger و info و warning را بر اساس رنگ دوخت می‌زند.') }}
        </p>
    </div>
    <x-nx::order-tracking
        :number="981120"
        status="failed"
        :status-label="$say('Delivery failed', 'تحویل ناموفق')"
        :carrier="$say('Tipax', 'تیپاکس')"
        :eta="$say('Second attempt: tomorrow ۱۰–۱۴', 'تلاش دوم: فردا ۱۰ تا ۱۴')"
        :live="false"
        :steps="$failedSteps"
        :events="$failedEvents" />
</section>

<section class="pg-box" style="gap: 1.25rem; max-inline-size: 40rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('An order that just landed', 'سفارشی که همین حالا ثبت شده') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The bare minimum: three steps, current at the first, no events yet — the block says so itself instead of an empty box. In a shop this is the screen right after checkout.', 'حداقلِ مطلق: سه گام، current روی اولین، هنوز هیچ رویدادی نیست — بلوک خودش همین را می‌گوید، نه یک جعبهٔ خالی. در فروشگاه این همان صفحهٔ درستِ بعد از پرداخت است.') }}
        </p>
    </div>
    <x-nx::order-tracking
        number="۴۵۲۱"
        status="queued"
        :steps="[
            ['id' => 'placed', 'title' => $say('Order placed', 'ثبت سفارش'), 'time' => now()->subMinutes(4)],
            ['id' => 'packed', 'title' => $say('Packing', 'بسته‌بندی')],
            ['id' => 'done', 'title' => $say('Delivered', 'تحویل شد')],
        ]"
        current="0" />
</section>
