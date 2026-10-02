{{--
    The divider where it earns its keep: the sign-in card's «یا» between the
    Google button and the email form, the checkout's whole phrase «یا پرداخت
    با» between the bank gateway and the Nabu wallet — the label is not always
    one word — and the teammate card's quiet rule between the bio and the
    stats, where the empty slot collapses into a single hairline.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // 1) Sign-in — the classic «or» between the two ways in.
    $googleMsg = $say('Choose a Google account…', 'یک حساب گوگل انتخاب کنید…');
    $loginMsg = $say('Signed in — welcome back', 'ورود انجام شد — خوش آمدید');

    // 2) Checkout — the bank gateway or the Nabu wallet; the divider carries
    //    a whole phrase this time, not a single word.
    $total = 1560000;
    $wallet = 1840000;
    $pay = $state['pay'] ?? 'gateway';
    $walletRest = max(0, $wallet - $total);
    $payMsg = $pay === 'wallet'
        ? $say('Paid with the Nabu wallet', 'با کیف پول نابو پرداخت شد')
        : $say('Off to the bank gateway', 'در حال انتقال به درگاه بانکی');
    $picked = fn (bool $on) => implode(';', [
        'border: 1px solid '.($on ? 'var(--nx-accent-border)' : 'var(--nx-border)'),
        'background: '.($on ? 'var(--nx-accent-soft)' : 'var(--nx-surface)'),
    ]);

    // 3) The teammate card's quiet rule — empty slot, one hairline.
    $stats = [
        ['label' => $say('projects', 'پروژه'), 'value' => 12],
        ['label' => $say('reviews given', 'بازخورد'), 'value' => 48],
        ['label' => $say('member since', 'عضو از'), 'value' => 1402],
    ];
    $messageMsg = $say('Chat opened with Sara', 'گفت‌وگو با سارا باز شد');
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The sign-in card', 'کارت ورود') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The slot carries the «or» and a hairline grows on both sides, splitting the two ways in. For the screen reader the same mark is structural — the role=separator is always on the output, so the Google button and the email form read as two groups.', 'اسلات واژهٔ «یا» را برمی‌دارد و دو طرفش خط مویی می‌کشد؛ دو راه ورود از هم جدا می‌شوند. برای صفحه‌خوان همین نشان ساختاری است — role=separator همیشه روی خروجی نشسته تا دکمهٔ گوگل و فرم ایمیل، دو گروه جدا خوانده شوند.') }}
        </p>
    </div>
    <div style="display: grid; gap: .9rem; inline-size: min(100%, 24rem); margin-inline: auto; padding: 1.5rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-2xl); background: var(--nx-surface-2)">
        <x-nx::button variant="outline" icon="globe" block wire:click="ping('{{ $googleMsg }}')">
            {{ $say('Continue with Google', 'ورود با گوگل') }}
        </x-nx::button>
        <x-nx::divider>{{ $say('or', 'یا') }}</x-nx::divider>
        <x-nx::input :label="$say('Work email', 'ایمیل سازمانی')" type="email" icon="mail" placeholder="you@nabu.shop" wire:model="state.loginEmail" />
        <x-nx::input :label="$say('Password', 'رمز عبور')" type="password" wire:model="state.loginPassword" />
        <x-nx::button variant="primary" icon="arrow-right" block wire:click="save('{{ $loginMsg }}')">
            {{ $say('Sign in', 'ورود') }}
        </x-nx::button>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Two ways to pay at checkout', 'دو راه پرداخت در تسویهٔ سفارش') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The label is not always one word: here a whole phrase — «یا پرداخت با» — bridges the bank gateway and the Nabu wallet. Both options are plain buttons, so the keyboard picks them as naturally as the pointer.', 'برچسب همیشه یک واژه نیست: این‌جا یک عبارت کامل — «یا پرداخت با» — درگاه بانکی را به کیف پول نابو می‌رساند. هر دو گزینه دکمهٔ ساده‌اند تا کیبورد هم مثل اشاره‌گر انتخابشان کند.') }}
        </p>
    </div>
    <div style="display: grid; inline-size: min(100%, 26rem); margin-inline: auto">
        <button type="button" wire:click="$set('state.pay', 'gateway')" aria-pressed="{{ $pay === 'gateway' ? 'true' : 'false' }}"
            style="text-align: start; cursor: pointer; font: inherit; color: inherit; display: grid; gap: .2rem; padding: 1rem 1.1rem; border-radius: var(--nx-radius-lg); {{ $picked($pay === 'gateway') }}">
            <strong style="font-weight: 600">{{ $say('Bank gateway', 'درگاه بانکی') }}</strong>
            <span style="font-size: var(--nx-text-sm); color: var(--nx-text-muted)">{{ $say('All Shetab cards · the bank’s secure page', 'همهٔ کارت‌های شتاب · صفحهٔ امن بانک') }}</span>
        </button>
        <x-nx::divider>{{ $say('or pay with', 'یا پرداخت با') }}</x-nx::divider>
        <button type="button" wire:click="$set('state.pay', 'wallet')" aria-pressed="{{ $pay === 'wallet' ? 'true' : 'false' }}"
            style="text-align: start; cursor: pointer; font: inherit; color: inherit; display: grid; gap: .2rem; padding: 1rem 1.1rem; border-radius: var(--nx-radius-lg); {{ $picked($pay === 'wallet') }}">
            <strong style="font-weight: 600">{{ $say('Nabu wallet', 'کیف پول نابو') }}</strong>
            <span style="font-size: var(--nx-text-sm); color: var(--nx-text-muted)">{{ $say('Balance', 'موجودی').': '.NabuXUI::formatNumber($wallet).' '.$say('Toman', 'تومان') }}</span>
        </button>
    </div>
    <div class="pg-row" style="justify-content: space-between; inline-size: min(100%, 26rem); margin-inline: auto">
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
            @if ($pay === 'wallet')
                {{ $say('After this payment '.NabuXUI::formatNumber($walletRest).' tomans stay in the wallet.', 'پس از این پرداخت '.NabuXUI::formatNumber($walletRest).' تومان در کیف پول می‌ماند.') }}
            @else
                {{ $say('Order total', 'جمع سفارش').': '.NabuXUI::formatNumber($total).' '.$say('Toman', 'تومان') }}
            @endif
        </p>
        <x-nx::button variant="primary" icon="check" wire:click="save('{{ $payMsg }}')">
            {{ $say('Pay', 'پرداخت').' '.NabuXUI::formatNumber($total) }}
        </x-nx::button>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The teammate card’s quiet rule', 'خط آرام کارت هم‌تیمی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('With no slot the two hairlines become one continuous line — :empty collapses the gap to zero. The default block margin (var(--nx-space-6)) is sized for page flow; inside a gapped container it usually wants taming with margin-block: 0, as it is here.', 'بدون اسلات دو خط مویی یک خط یکپارچه می‌شوند — :empty گپ را صفر می‌کند. حاشیهٔ پیش‌فرض (var(--nx-space-6)) برای جریان صفحه اندازه شده؛ داخل ظرف gap‌دار معمولاً با margin-block: 0 رام می‌شود، مثل همین‌جا.') }}
        </p>
    </div>
    <div style="display: grid; gap: 1rem; inline-size: min(100%, 24rem); margin-inline: auto; padding: 1.5rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-2xl); background: var(--nx-surface)">
        <div class="pg-row" style="gap: .85rem">
            <x-nx::avatar name="سارا محمدی" size="lg" status="online" />
            <span style="display: grid">
                <strong style="font-weight: 600">سارا محمدی</strong>
                <span style="font-size: var(--nx-text-sm); color: var(--nx-text-muted)">{{ $say('Product designer · storefront team', 'طراح محصول · تیم ویترین') }}</span>
            </span>
        </div>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Keeps the design system honest — her last pass matched the storefront’s dark theme to the fresh tokens.', 'مراقب نظام طراحی نابو است — آخرین کارش هماهنگ‌کردن حالت تیرهٔ ویترین با توکن‌های تازه بود.') }}
        </p>
        <x-nx::divider style="margin-block: 0" />
        <div class="pg-row" style="justify-content: space-between">
            <span class="pg-row" style="gap: 1rem; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
                @foreach ($stats as $stat)
                    <span>{{ NabuXUI::formatNumber($stat['value']).' '.$stat['label'] }}</span>
                @endforeach
            </span>
            <x-nx::button size="sm" variant="secondary" icon="message" wire:click="ping('{{ $messageMsg }}')">
                {{ $say('Message', 'پیام') }}
            </x-nx::button>
        </div>
    </div>
</section>
