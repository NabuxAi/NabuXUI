{{--
    <x-nx::pricing :plans="$plans" yearly-note="2 months free" />
    Each plan: ['id', 'name', 'description', 'price' => ['monthly' => 19, 'yearly' => 15], 'currency' => '$',
                'period' => ['monthly' => '/mo', 'yearly' => '/mo'], 'featured' => true, 'flag' => 'Popular',
                'features' => [['label' => '…', 'included' => true]], 'cta' => ['label' => '…', 'href' => '…']]
--}}
@props(['plans' => [], 'billing' => 'monthly', 'yearlyNote' => null, 'monthlyLabel' => 'Monthly', 'yearlyLabel' => 'Yearly'])
@php $group = \NabuXUI\NabuXUI::id('nx-billing'); @endphp
<section {{ $attributes->class('nx-pricing') }} x-data="nxPricing(@js($billing), @js(str_replace('_', '-', app()->getLocale())))">
    <div class="nx-pricing-toggle">
        <div class="nx-segmented" role="radiogroup" aria-label="{{ $monthlyLabel }} / {{ $yearlyLabel }}" x-data="nxSegmented()">
            <span class="nx-indicator" aria-hidden="true"></span>
            @foreach (['monthly' => $monthlyLabel, 'yearly' => $yearlyLabel] as $value => $label)
                <label class="nx-segment"><input class="nx-segment-input" type="radio" name="{{ $group }}" value="{{ $value }}" x-model="billing" @checked($billing === $value)>{{ $label }}</label>
            @endforeach
        </div>
        @if ($yearlyNote)<x-nx::badge tone="success" dot>{{ $yearlyNote }}</x-nx::badge>@endif
    </div>
    <div class="nx-pricing-grid">
        @foreach ($plans as $plan)
            <article class="nx-plan" @if (! empty($plan['featured'])) data-featured @endif>
                @if (! empty($plan['featured']) && ! empty($plan['flag']))<span class="nx-plan-flag">{{ $plan['flag'] }}</span>@endif
                <h3 class="nx-plan-name">{{ $plan['name'] }}</h3>
                @if (! empty($plan['description']))<p class="nx-plan-description">{{ $plan['description'] }}</p>@endif
                <p class="nx-plan-price">
                    @if (! empty($plan['currency']))<span class="nx-plan-currency">{{ $plan['currency'] }}</span>@endif
                    <x-nx::number :value="$plan['price'][$billing] ?? 0" :reveal="false" data-prices="{{ json_encode($plan['price']) }}" />
                    @if (! empty($plan['period']))<span class="nx-plan-period" x-text="@js($plan['period'])[billing]">{{ $plan['period'][$billing] ?? '' }}</span>@endif
                </p>
                <ul class="nx-plan-features">
                    @foreach ($plan['features'] ?? [] as $feature)
                        <li @if (($feature['included'] ?? true) === false) data-excluded @endif>{{ \NabuXUI\NabuXUI::icon(($feature['included'] ?? true) === false ? 'minus' : 'check') }}<span>{{ $feature['label'] }}</span></li>
                    @endforeach
                </ul>
                <div class="nx-plan-action">
                    <x-nx::button block size="lg" :variant="! empty($plan['featured']) ? 'primary' : 'secondary'" :effect="! empty($plan['featured']) ? 'shine' : null" :href="$plan['cta']['href'] ?? null">{{ $plan['cta']['label'] ?? '' }}</x-nx::button>
                </div>
            </article>
        @endforeach
    </div>
</section>
