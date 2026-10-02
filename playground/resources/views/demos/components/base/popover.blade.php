{{--
    The popover where it does real work: a domain monitor whose info buttons
    open a rich detail card (status, uptime, a 24-hour latency sparkline), a
    quota setting explained by a help popover that carries the actual usage
    bar, and a chat mention that opens the teammate's card. Panels ride the
    native Popover API — the top layer, Esc and an outside click close them
    (popover=auto), clicks inside never do — and the panel actions toast
    client-side ($nxToast) so the panel stays open.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $num = fn (float|int $value, int $decimals = 0) => $fa
        ? NabuXUI::formatNumber($value, $decimals)
        : number_format($value, $decimals);
    $pct = fn (float|int $value, int $decimals = 0) => $num($value, $decimals).($fa ? '٪' : '%');
    $pick = fn (array $value) => $value[$fa ? 'fa' : 'en'];

    $domains = [
        ['host' => 'api.nabu.shop', 'tone' => 'success', 'status' => ['en' => 'Healthy', 'fa' => 'سالم'], 'uptime' => 99.98, 'latency' => 142, 'series' => [180, 165, 172, 150, 148, 155, 142, 138, 146, 142]],
        ['host' => 'cdn.nabu.shop', 'tone' => 'warning', 'status' => ['en' => 'Degraded', 'fa' => 'تنزل‌یافته'], 'uptime' => 99.71, 'latency' => 318, 'series' => [140, 152, 148, 176, 190, 212, 240, 268, 290, 318]],
        ['host' => 'panel.nabu.shop', 'tone' => 'info', 'status' => ['en' => 'Maintained', 'fa' => 'در نگه‌داری'], 'uptime' => 99.95, 'latency' => 96, 'series' => [88, 92, 90, 96, 94, 99, 96, 93, 97, 96]],
    ];

    $quotaTotal = 200;
    $quotaUsed = 64;
    $quotaLeft = 72;
    $mention = '@'.$say('Sara', 'سارا');
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The domain monitor', 'تابلوی پایش دامنه‌ها') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Every row’s info button opens a detail card with side="bottom" and align="end" — right under the trigger. The panel lives in the top layer, Esc or an outside click closes it, and because it carries role=dialog with a label, screen readers know what opened. Tab reaches the trigger like any button.', 'دکمهٔ اطلاعات هر ردیف یک کارت جزئیات با side="bottom" و align="end" باز می‌کند — درست زیرِ تریگر. پنل در top layer می‌ماند، با Esc یا کلیک بیرون بسته می‌شود و چون نقش dialog و برچسب دارد، صفحه‌خوان هم می‌فهمد چه باز شده. تریگر مثل هر دکمه‌ای با Tab در دسترس است.') }}
        </p>
    </div>
    <div style="display: grid; gap: .5rem">
        @foreach ($domains as $d)
            @php
                $status = $pick($d['status']);
                $degraded = $d['tone'] === 'warning';
            @endphp
            <div class="pg-row" style="justify-content: space-between; padding: .6rem 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface)">
                <div class="pg-row" style="gap: .75rem; min-inline-size: 0">
                    <x-nx::badge :tone="$d['tone']" dot>{{ $status }}</x-nx::badge>
                    <code dir="ltr" style="font: 500 var(--nx-text-sm) var(--nx-font-mono); color: var(--nx-text)">{{ $d['host'] }}</code>
                </div>
                <x-nx::popover side="bottom" align="end" :label="$say($d['host'].' details', 'جزئیات '.$d['host'])">
                    <x-slot:trigger>
                        <x-nx::button variant="ghost" icon="info" icon-only :aria-label="$say('Domain details', 'جزئیات دامنه')" />
                    </x-slot:trigger>
                    <div style="display: grid; gap: .75rem">
                        <div class="pg-row" style="justify-content: space-between; gap: 1rem">
                            <code dir="ltr" style="font: 500 var(--nx-text-sm) var(--nx-font-mono)">{{ $d['host'] }}</code>
                            <x-nx::badge :tone="$d['tone']" dot :pulse="$degraded ? true : false">{{ $status }}</x-nx::badge>
                        </div>
                        <div style="display: grid; gap: .25rem">
                            <x-nx::sparkline :data="$d['series']" />
                            <span style="font-size: var(--nx-text-xs); color: var(--nx-text-muted)">{{ $say('Response latency, last 24 hours', 'تأخیر پاسخ — ۲۴ ساعت گذشته') }}</span>
                        </div>
                        <dl style="display: grid; gap: .4rem; margin: 0; font-size: var(--nx-text-sm)">
                            <div class="pg-row" style="justify-content: space-between; gap: 1rem">
                                <dt style="color: var(--nx-text-muted)">{{ $say('Uptime this month', 'آپ‌تایم این ماه') }}</dt>
                                <dd style="margin: 0; font-weight: 600">{{ $pct($d['uptime'], 2) }}</dd>
                            </div>
                            <div class="pg-row" style="justify-content: space-between; gap: 1rem">
                                <dt style="color: var(--nx-text-muted)">{{ $say('Average response', 'میانگین پاسخ') }}</dt>
                                <dd style="margin: 0; font-weight: 600">{{ $num($d['latency']) }} {{ $say('ms', 'میلی‌ثانیه') }}</dd>
                            </div>
                        </dl>
                        <div class="pg-row" style="justify-content: flex-end">
                            <x-nx::button variant="secondary" size="sm" icon="chart"
                                x-on:click="$nxToast(@js($say('Opening the logs of '.$d['host'], 'لاگ‌های '.$d['host'].' باز شد')))">
                                {{ $say('View logs', 'دیدن لاگ‌ها') }}
                            </x-nx::button>
                        </div>
                    </div>
                </x-nx::popover>
            </div>
        @endforeach
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A rich hint beside the setting', 'راهنمای غنی کنار تنظیم') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('side="top" with align="start" drops the panel right onto the setting instead of the page’s end. Anything may live inside — here the quota’s own usage bar stands in the hint, so the number above and the explanation below never part ways.', 'side="top" همراه align="start" پنل را درست روی خودِ تنظیم می‌آورد، نه ته صفحه. داخل پنل هر چیزی می‌شود گذاشت — این‌جا خودِ نوار مصرفِ سهمیه داخل راهنما ایستاده تا عدد بالا و توضیح پایین از هم جدا نشوند.') }}
        </p>
    </div>
    <div class="pg-row" style="justify-content: space-between; padding: .6rem 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface-2)">
        <div>
            <div style="font-weight: 600">{{ $say('Monthly bandwidth', 'پهنای باند ماهانه') }}</div>
            <div style="font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
                {{ $pct($quotaUsed) }} — {{ $num($quotaUsed * $quotaTotal / 100) }} {{ $say('GB of', 'از') }} {{ $num($quotaTotal) }} GB {{ $say('used', 'مصرف‌شده') }}
            </div>
        </div>
        <x-nx::popover side="top" align="start" :label="$say('About the bandwidth quota', 'دربارهٔ سهمیهٔ پهنای باند')">
            <x-slot:trigger>
                <x-nx::button variant="ghost" icon="info" icon-only :aria-label="$say('About the bandwidth quota', 'دربارهٔ سهمیهٔ پهنای باند')" />
            </x-slot:trigger>
            <div style="display: grid; gap: .75rem">
                <strong style="font-weight: 700">{{ $say('What counts toward the quota?', 'چه چیزی داخل سهمیه شمرده می‌شود؟') }}</strong>
                <ul style="margin: 0; padding-inline-start: 1.25rem; display: grid; gap: .3rem; font-size: var(--nx-text-sm)">
                    <li>{{ $say('Media uploaded to the gallery', 'رسانه‌های آپلودشده در گالری') }}</li>
                    <li>{{ $say('Files delivered from the CDN', 'تحویل فایل‌ها از CDN') }}</li>
                    <li>{{ $say('Backup downloads', 'دانلود پشتیبان‌ها') }}</li>
                </ul>
                <div style="display: grid; gap: .4rem">
                    <x-nx::progress :value="$quotaUsed" :label="$say('Bandwidth used', 'پهنای باند مصرف‌شده')" />
                    <div class="pg-row" style="justify-content: space-between; font-size: var(--nx-text-xs); color: var(--nx-text-muted)">
                        <span>{{ $num($quotaLeft) }} GB {{ $say('left', 'باقی‌مانده') }}</span>
                        <span>{{ $num($quotaTotal) }} GB</span>
                    </div>
                </div>
                <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
                    {{ $say('Admin-panel traffic is free; the quota renews with the 12 Aban invoice.', 'ترافیک پنل مدیریت شمرده نمی‌شود؛ سهمیه با صورت‌حساب ۱۲ آبان تمدید می‌شود.') }}
                </p>
                <div class="pg-row" style="justify-content: flex-end">
                    <x-nx::button variant="primary" size="sm" icon="trend-up"
                        x-on:click="$nxToast(@js($say('Taking you to the plans', 'رفتن به صفحهٔ پلن‌ها')))">
                        {{ $say('Upgrade the plan', 'ارتقای پلن') }}
                    </x-nx::button>
                </div>
            </div>
        </x-nx::popover>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The teammate card on a mention', 'کارت هم‌تیمی روی منشن') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('In the team thread the mention itself is the trigger: side="end" opens the panel from the reading direction (it mirrors in RTL by itself) and align="start" keeps it flush with the message. Clicks on the buttons inside never close the panel — only Esc or an outside click does.', 'در گفت‌وگوی تیم، خودِ منشن تریگر است: side="end" پنل را از سمتِ جهتِ خواندن باز می‌کند (در RTL خودش برمی‌گردد) و align="start" آن را هم‌تراز پیام نگه می‌دارد. کلیک روی دکمه‌های داخل پنل آن را نمی‌بندد — فقط Esc یا کلیک بیرون.') }}
        </p>
    </div>
    <figure style="margin: 0; display: grid; gap: .5rem; justify-items: start">
        <figcaption class="pg-row" style="gap: .5rem">
            <x-nx::avatar name="کیان رستمی" size="sm" />
            <span style="font-weight: 600">{{ $say('Kian Rostami', 'کیان رستمی') }}</span>
            <span style="font-size: var(--nx-text-xs); color: var(--nx-text-muted)">{{ $say('10:42 · #product', '۱۰:۴۲ · #محصول') }}</span>
        </figcaption>
        <div style="max-inline-size: 38rem; padding: .75rem 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface-2); line-height: 1.9">
            <x-nx::popover side="end" align="start" :label="$say('Sara Mohammadi’s card', 'کارت سارا محمدی')">
                <x-slot:trigger>
                    <x-nx::button variant="link" :aria-label="$say('Sara Mohammadi’s card', 'کارت سارا محمدی')">{{ $mention }}
</x-nx::button>
                </x-slot:trigger>
                <div style="display: grid; gap: .75rem">
                    <div class="pg-row" style="gap: .75rem">
                        <x-nx::avatar name="سارا محمدی" size="lg" status="online" />
                        <div style="display: grid; gap: .3rem">
                            <span style="font-weight: 700">{{ $say('Sara Mohammadi', 'سارا محمدی') }}</span>
                            <x-nx::badge tone="accent">{{ $say('Product owner', 'مالک محصول') }}</x-nx:badge>
                        </div>
                    </div>
                    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
                        {{ $say('Last seen 2 hours ago · local time 14:05', 'آخرین حضور ۲ ساعت پیش · ساعت محلی ۱۴:۰۵') }}
                    </p>
                    <div class="pg-row" style="gap: .5rem">
                        <x-nx::button variant="primary" size="sm" icon="mail"
                            x-on:click="$nxToast(@js($say('Draft message to Sara opened', 'پیش‌نویس پیام به سارا باز شد')))">
                            {{ $say('Send message', 'ارسال پیام') }}
                        </x-nx::button>
                        <x-nx::button variant="ghost" size="sm" icon="user"
                            x-on:click="$nxToast(@js($say('Sara’s profile opened', 'پروفایل سارا باز شد')))">
                            {{ $say('Profile', 'پروفایل') }}
                        </x-nx::button>
                    </div>
                </div>
            </x-nx::popover>{{ $say(' — the Aban invoice is ready; I uploaded it so the whole team can review it.', '، فاکتور آبان آماده شد؛ برای بازبینی تیم همان‌جا آپلودش کردم.') }}
        </div>
    </figure>
</section>
