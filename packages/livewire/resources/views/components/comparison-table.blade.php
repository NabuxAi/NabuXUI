{{--
    <x-nx::comparison-table caption="Compare plans" recommended="growth"
        :plans="[
            ['id' => 'starter', 'name' => 'Starter', 'price' => '$0', 'period' => '/mo', 'description' => 'Try it out', 'action' => ['label' => 'Start free', 'href' => '#']],
            ['id' => 'growth', 'name' => 'Growth', 'price' => '$49', 'period' => '/mo', 'action' => ['label' => 'Choose Growth', 'href' => '#']],
        ]"
        :features="[
            ['group' => 'Agents', 'label' => 'Languages', 'values' => ['starter' => '3', 'growth' => '12']],
            ['group' => 'Agents', 'label' => 'Voice replies', 'hint' => 'WhatsApp and Telegram', 'values' => ['starter' => false, 'growth' => true]],
        ]" />

    Values are true / false (a check or a dash, named for screen readers) or any text. The
    recommended column gets a lit ring; the marks pop in row by row as the table comes into view.
    The feature column stays put while the plans scroll sideways on a phone.
--}}
@props(['plans' => [], 'features' => [], 'recommended' => null, 'caption' => null, 'featureLabel' => null, 'recommendedLabel' => null, 'includedLabel' => null, 'excludedLabel' => null])
@php
    $lang = substr(app()->getLocale(), 0, 2);
    $words = [
        'fa' => ['features' => "ویژگی\u{200C}ها", 'recommended' => 'پیشنهادی', 'included' => 'دارد', 'excluded' => 'ندارد'],
        'ar' => ['features' => 'الميزات', 'recommended' => 'موصى به', 'included' => 'مشمول', 'excluded' => 'غير مشمول'],
    ][$lang] ?? ['features' => 'Features', 'recommended' => 'Recommended', 'included' => 'Included', 'excluded' => 'Not included'];
    $lastGroup = null;
@endphp
<div {{ $attributes->class('nx-comparison')->merge(['data-nx-reveal' => '']) }} x-data x-nx-reveal>
    <div class="nx-comparison-scroll" tabindex="0" @if ($caption) role="region" aria-label="{{ $caption }}" @endif>
        <table class="nx-comparison-table">
            @if ($caption)<caption class="nx-visually-hidden">{{ $caption }}</caption>@endif
            <thead>
                <tr>
                    <th scope="col" class="nx-comparison-corner">{{ $featureLabel ?? $words['features'] }}</th>
                    @foreach ($plans as $j => $plan)
                        @php $featured = ($plan['id'] ?? null) === $recommended; @endphp
                        <th scope="col" class="nx-comparison-plan" @if ($featured) data-featured @endif style="--nx-j: {{ $j }}">
                            <span class="nx-comparison-plan-inner">
                                @if ($featured)<span class="nx-comparison-flag">{{ $recommendedLabel ?? $words['recommended'] }}</span>@endif
                                <span class="nx-comparison-plan-name">{{ $plan['name'] }}</span>
                                @isset($plan['price'])<span class="nx-comparison-price">{{ $plan['price'] }}@if (! empty($plan['period']))<small>{{ $plan['period'] }}</small>@endif</span>@endisset
                                @if (! empty($plan['description']))<span class="nx-comparison-plan-description">{{ $plan['description'] }}</span>@endif
                                @if (! empty($plan['action']))
                                    <x-nx::button size="sm" :variant="$featured ? 'primary' : 'secondary'" :icon="$plan['action']['icon'] ?? null" :href="$plan['action']['href'] ?? null">{{ $plan['action']['label'] }}</x-nx::button>
                                @endif
                            </span>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($features as $i => $feature)
                    @if (! empty($feature['group']) && $feature['group'] !== $lastGroup)
                        <tr class="nx-comparison-group"><th scope="colgroup" colspan="{{ count($plans) + 1 }}">{{ $feature['group'] }}</th></tr>
                    @endif
                    @php $lastGroup = $feature['group'] ?? $lastGroup; @endphp
                    <tr style="--nx-i: {{ $i }}">
                        <th scope="row" class="nx-comparison-feature">{{ $feature['label'] }}@if (! empty($feature['hint']))<span class="nx-comparison-feature-hint">{{ $feature['hint'] }}</span>@endif</th>
                        @foreach ($plans as $j => $plan)
                            @php
                                $value = $feature['values'][$plan['id']] ?? null;
                                $featured = ($plan['id'] ?? null) === $recommended;
                            @endphp
                            <td @if ($featured) data-featured @endif style="--nx-j: {{ $j }}">
                                @if (is_bool($value) || $value === null)
                                    <span class="nx-comparison-mark" data-value="{{ $value ? 'yes' : 'no' }}" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="{{ $value ? 'M5 12.5l4.5 4.5L19 7.5' : 'M6 12h12' }}"/></svg></span>
                                    <span class="nx-visually-hidden">{{ $value ? ($includedLabel ?? $words['included']) : ($excludedLabel ?? $words['excluded']) }}</span>
                                @else
                                    <span class="nx-comparison-text">{{ $value }}</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
