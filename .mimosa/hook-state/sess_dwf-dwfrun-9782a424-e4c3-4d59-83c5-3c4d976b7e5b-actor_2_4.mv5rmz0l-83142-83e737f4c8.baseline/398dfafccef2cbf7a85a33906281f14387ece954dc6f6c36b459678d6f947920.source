{{--
    The usage card's real scenarios: a storage card about to turn warning,
    and a model-token card past the danger line with a slot action.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<div class="pg-grid">
    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Storage on the Pro plan', 'فضای ذخیره در پلن پرو') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('The bar fills segment by segment as the card scrolls in; at 74% the percentage is still calm — one more upload and it turns warning.', 'نوار با رسیدن کارت به دید، سگمنت‌به‌سگمنت پر می‌شود؛ در ۷۴٪ درصد هنوز آرام است — یک آپلود دیگر و به هشدار می‌رود.') }}
            </p>
        </div>
        <x-nx::usage-card
            :title="$say('Storage', 'فضای ذخیره')" plan="{{ $say('Pro', 'پرو') }}" :limit="10" unit="GB" :decimals="1"
            :note="$say('Resets on the 1st', 'روز اول ماه ریست می‌شود')"
            :categories="[
                $say('Documents', 'اسناد') => 3.2,
                $say('Voice notes', 'یادداشت‌های صوتی') => 2.1,
                $say('Images', 'تصویرها') => 1.4,
                $say('Embeddings', 'بردارها') => 0.7,
            ]"
            :action="['label' => $say('Upgrade plan', 'ارتقای پلن'), 'href' => '#']" />
    </section>

    <section class="pg-box" style="gap: 1.25rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Model tokens, past the line', 'توکن مدل، از خط گذشته') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('93% used: the percentage reads danger and the slot button carries a live wire:click with a spinner while it works.', '۹۳٪ مصرف‌شده: درصد به خطر می‌رود و دکمهٔ اسلات یک wire:click زنده دارد که هنگام کار اسپینر نشان می‌دهد.') }}
            </p>
        </div>
        <x-nx::usage-card
            :title="$say('Model tokens', 'توکن مدل')" plan="{{ $say('Growth', 'رشد') }}" :limit="5" unit="M" :decimals="2"
            :note="$fa ? '۹۳٪ مصرف‌شده: ایجنت‌ها در ۱۰۰٪ می‌ایستند' : '93% used: agents pause at 100%'"
            :categories="[
                $say('Replies', 'پاسخ‌ها') => 2.9,
                $say('Summaries', 'خلاصه‌ها') => 1.1,
                $say('Translations', 'ترجمه‌ها') => 0.66,
            ]">
            <x-slot:actions>
                <x-nx::button size="sm" variant="primary" effect="shine" icon="zap" wire:click="save(@js($say('Upgrade requested — the team will reach out', 'درخواست ارتقا ثبت شد — تیم با شما تماس می‌گیرد')))">
                    {{ $say('Upgrade now', 'همین حالا ارتقا') }}
                </x-nx::button>
            </x-slot:actions>
        </x-nx::usage-card>
    </section>
</div>
