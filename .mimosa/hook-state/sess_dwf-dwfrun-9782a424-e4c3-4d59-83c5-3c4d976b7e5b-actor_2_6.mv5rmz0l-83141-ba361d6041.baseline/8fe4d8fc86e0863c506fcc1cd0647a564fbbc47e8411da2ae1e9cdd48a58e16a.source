{{--
    <x-nx::parametric-loader />                                            a five-petal rose
    <x-nx::parametric-loader kind="spiro" size="lg" label="Thinking" />   a spirograph flower
    <x-nx::parametric-loader kind="lissajous" :options="['a' => 5, 'b' => 4]" />

    A path drawn from parametric equations (the same maths as the core's
    parametricPath, in a 100×100 box), stroked faintly, with a glowing head and a
    fading trail travelling along it. Options: rose n, d · spiro R, r, offset ·
    lissajous a, b, phase · samples, padding. `duration` is one lap in ms.
--}}
@props([
    'kind' => 'rose',
    'options' => [],
    'size' => 'md',
    'duration' => null,
    'label' => null,
])
@php
    $o = (array) $options;
    $tau = M_PI * 2;
    $gcd = function ($a, $b): int {
        $x = abs((int) round($a));
        $y = abs((int) round($b));
        while ($y) {
            [$x, $y] = [$y, $x % $y];
        }

        return $x ?: 1;
    };

    if ($kind === 'spiro') {
        $bigR = $o['R'] ?? 7;
        $smallR = $o['r'] ?? 3;
        $pen = $o['offset'] ?? 5;
        $ratio = ($bigR - $smallR) / $smallR;
        $period = $tau * (round($smallR) / $gcd($bigR, $smallR));
        $point = fn ($t) => [($bigR - $smallR) * cos($t) + $pen * cos($ratio * $t), ($bigR - $smallR) * sin($t) - $pen * sin($ratio * $t)];
    } elseif ($kind === 'lissajous') {
        $fa = $o['a'] ?? 3;
        $fb = $o['b'] ?? 2;
        $phase = $o['phase'] ?? M_PI / 2;
        $period = $tau;
        $point = fn ($t) => [sin($fa * $t + $phase), sin($fb * $t)];
    } else {
        $kind = 'rose';
        $divisor = $gcd($o['n'] ?? 5, $o['d'] ?? 1);
        $petalN = round($o['n'] ?? 5) / $divisor;
        $petalD = round($o['d'] ?? 1) / $divisor;
        $k = $petalN / $petalD;
        // Closes after π·d when n·d is odd, 2π·d otherwise.
        $period = M_PI * $petalD * (((int) round($petalN * $petalD)) % 2 === 1 ? 1 : 2);
        $point = fn ($t) => [cos($k * $t) * cos($t), cos($k * $t) * sin($t)];
    }

    $samples = max(12, (int) round($o['samples'] ?? min(480, max(160, ($period / $tau) * 100))));
    $points = [];
    $reach = 0;
    for ($i = 0; $i < $samples; $i++) {
        [$px, $py] = $point(($i / $samples) * $period);
        $points[] = [$px, $py];
        $reach = max($reach, abs($px), abs($py));
    }
    $scale = (50 - ($o['padding'] ?? 8)) / ($reach ?: 1);
    $fmt = fn ($value) => (string) (round($value * 10) / 10);
    $path = '';
    foreach ($points as $i => [$px, $py]) {
        $path .= ($i ? 'L' : 'M').$fmt(50 + $px * $scale).','.$fmt(50 + $py * $scale);
    }
    $path .= 'Z';

    $sizes = ['sm' => '2.25rem', 'md' => '3.5rem', 'lg' => '5rem'];
    $style = '--nx-loader-size: '.($sizes[$size] ?? $sizes['md']).($duration ? '; --_dur: '.(int) $duration.'ms' : '');
    // Tail first: longer and fainter behind, short and bright at the head.
    $trail = [[0.34, 0.12], [0.22, 0.24], [0.12, 0.5], [0.05, 1]];
@endphp
<span {{ $attributes->class('nx-parametric')->merge(['role' => 'status', 'data-kind' => $kind, 'style' => $style]) }}>
    <svg viewBox="0 0 100 100" aria-hidden="true" focusable="false">
        <path class="nx-parametric-track" d="{{ $path }}"/>
        @foreach ($trail as [$len, $alpha])
            <path class="nx-parametric-trail" d="{{ $path }}" pathLength="1" style="--_len: {{ $len }}; --_alpha: {{ $alpha }}" @if ($loop->last) data-head @endif/>
        @endforeach
    </svg>
    <span class="nx-visually-hidden">{{ $label ?? __('nabuxui::ui.loading') }}</span>
</span>
