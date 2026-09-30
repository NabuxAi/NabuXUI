{{--
    The chain selector as a wallet’s network picker: the glyph in the trigger
    morphs into the chosen network and the panel searches names, symbols and
    tags. The card beside it re-prices the mock gas fee from the server state.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $chains = [
        ['id' => 'ethereum', 'name' => 'Ethereum', 'symbol' => 'ETH', 'tag' => 'L1', 'icon' => 'zap', 'tone' => 'lapis'],
        ['id' => 'polygon', 'name' => 'Polygon', 'symbol' => 'POL', 'tag' => 'L2', 'icon' => 'layers', 'tone' => 'violet'],
        ['id' => 'arbitrum', 'name' => 'Arbitrum', 'symbol' => 'ARB', 'tag' => 'L2', 'icon' => 'cpu', 'tone' => 'cyan'],
        ['id' => 'optimism', 'name' => 'Optimism', 'symbol' => 'OP', 'tag' => 'L2', 'icon' => 'trend-up', 'tone' => 'gold'],
        ['id' => 'base', 'name' => 'Base', 'symbol' => 'ETH', 'tag' => 'L2', 'icon' => 'globe', 'tone' => 'lapis'],
        ['id' => 'solana', 'name' => 'Solana', 'symbol' => 'SOL', 'tag' => 'L1', 'icon' => 'chart', 'tone' => 'violet'],
    ];
    $gas = ['ethereum' => 14.2, 'polygon' => 0.8, 'arbitrum' => 1.1, 'optimism' => 0.9, 'base' => 0.5, 'solana' => 0.2];
    $chain = $state['network'] ?? 'ethereum';
    $current = collect($chains)->firstWhere('id', $chain) ?? $chains[0];
@endphp

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Pay with the network you trust', 'با شبکه‌ای که به آن اعتماد دارید بپردازید') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Every network’s glyph is stacked inside the trigger; the chosen one morphs in while the panel folds away. The panel search matches names, symbols and tags — try “l2”.', 'گلیف همهٔ شبکه‌ها داخل تریگر انباشته است؛ انتخاب‌شده morph می‌شود و پنل جمع می‌شود. جست‌وجوی پنل نام، نماد و برچسب را می‌گردد — «l2» را امتحان کنید.') }}
            </p>
        </div>
        <div class="pg-row" style="min-block-size: 14rem; align-items: start">
            <x-nx::chain-selector name="network" :chains="$chains" :value="$chain" wire:model.live="state.network" />
        </div>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('On the server:', 'روی سرور:') }} <code>{{ $chain }}</code>
        </p>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The receipt follows the choice', 'رسید دنبال انتخاب می‌آید') }}</h3>
        <div style="display: grid; gap: .75rem; padding: 1.125rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface-2)">
            <div class="pg-row" style="justify-content: space-between">
                <span class="pg-row" style="gap: .625rem; font-weight: 700">
                    {{ \NabuXUI\NabuXUI::icon($current['icon']) }}
                    {{ $current['name'] }}
                    <x-nx::badge>{{ $current['tag'] }}</x-nx::badge>
                </span>
                <span dir="ltr" style="color: var(--nx-text-muted)">{{ $current['symbol'] }}</span>
            </div>
            <div class="pg-row" style="justify-content: space-between; font-size: var(--nx-text-sm)">
                <span style="color: var(--nx-text-muted)">{{ $say('Estimated gas', 'کارمزد تخمینی شبکه') }}</span>
                <strong>{{ NabuXUI::formatNumber($gas[$current['id']], 1).' gwei' }}</strong>
            </div>
            <x-nx::button variant="primary" icon="check" wire:click="save(@js($say('Swap prepared on '.$current['name'], 'معامله روی '.$current['name'].' آماده شد')))">
                {{ $say('Prepare the swap', 'آماده‌سازی معامله') }}
            </x-nx::button>
        </div>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Change the network above — the morph plays in the trigger and this card re-renders with the new fee.', 'شبکهٔ بالا را عوض کنید — مورف در تریگر اجرا می‌شود و این کارت با کارمزد تازه دوباره رندر می‌شود.') }}
        </p>
    </section>
</div>
