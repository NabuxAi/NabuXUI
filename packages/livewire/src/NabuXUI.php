<?php

namespace NabuXUI;

use Illuminate\Support\HtmlString;

/**
 * The server half of NabuXUI's Blade components: asset tags, flash toasts, and
 * the few text helpers the components need to render the same markup as the
 * React package (icons, split text, rolling-number digits).
 */
class NabuXUI
{
    /** The theme script, inlined in <head> so the page never paints in the wrong theme. */
    public static function head(): HtmlString
    {
        $script = @file_get_contents(self::dist('head.js')) ?: '';
        $transition = e(config('nabuxui.transition', 'fade'));

        return new HtmlString(
            '<script data-navigate-once>'.$script.'document.documentElement.setAttribute("data-nx-transition","'.$transition.'");</script>'
        );
    }

    public static function styles(): HtmlString
    {
        return new HtmlString('<link rel="stylesheet" href="'.e(self::assetUrl('nabuxui.css')).'" data-navigate-track>');
    }

    /** Include before Livewire's (or Alpine's) own script tag; it registers on alpine:init. */
    public static function scripts(): HtmlString
    {
        return new HtmlString('<script type="module" src="'.e(self::assetUrl('nabuxui.js')).'" data-navigate-once></script>');
    }

    public static function assetUrl(string $file): string
    {
        $path = self::dist($file);
        $version = is_file($path) ? substr(md5((string) filemtime($path).filesize($path)), 0, 10) : '0';

        if (! config('nabuxui.serve_assets', true)) {
            return asset('vendor/nabuxui/'.$file).'?v='.$version;
        }

        return route('nabuxui.asset', ['file' => $file, 'v' => $version]);
    }

    public static function dist(string $file): string
    {
        return __DIR__.'/../dist/'.$file;
    }

    /* ---- Toasts -------------------------------------------------------------- */

    /**
     * Queue a toast for the next page load (after a redirect). The <x-nx::toaster>
     * on that page shows it.
     */
    public static function flashToast(string $title, ?string $description = null, string $tone = 'neutral', ?int $duration = null): void
    {
        $toasts = session()->get('nabuxui.toasts', []);
        $toasts[] = array_filter(compact('title', 'description', 'tone', 'duration'), fn ($v) => $v !== null);
        session()->flash('nabuxui.toasts', $toasts);
    }

    /** @return array<int, array<string, mixed>> */
    public static function flashedToasts(): array
    {
        return function_exists('session') && app()->bound('session') ? (array) session('nabuxui.toasts', []) : [];
    }

    /* ---- Icons ----------------------------------------------------------------- */

    /** @var array<string, array{d: string, filled?: bool, directional?: bool}>|null */
    private static ?array $icons = null;

    /** @return array<string, array{d: string, filled?: bool, directional?: bool}> */
    public static function icons(): array
    {
        return self::$icons ??= json_decode((string) @file_get_contents(self::dist('icons.json')), true) ?: [];
    }

    public static function hasIcon(string $name): bool
    {
        return isset(self::icons()[$name]);
    }

    /** One built-in icon as SVG; decorative (hidden from assistive tech) unless labelled. */
    public static function icon(string $name, string $class = '', ?string $label = null): HtmlString
    {
        $icon = self::icons()[$name] ?? null;
        if (! $icon) {
            return new HtmlString('');
        }

        $paint = ($icon['filled'] ?? false)
            ? 'fill="currentColor" stroke="none"'
            : 'fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"';
        $a11y = $label !== null ? 'role="img" aria-label="'.e($label).'"' : 'aria-hidden="true"';
        $dir = ($icon['directional'] ?? false) ? ' data-directional' : '';

        return new HtmlString('<svg class="'.e(trim('nx-icon '.$class)).'" viewBox="0 0 24 24" '.$paint.' '.$a11y.$dir.'><path d="'.e($icon['d']).'"/></svg>');
    }

    /* ---- Text -------------------------------------------------------------------- */

    private const RTL = '/[\x{0590}-\x{08FF}\x{FB1D}-\x{FDFF}\x{FE70}-\x{FEFF}]/u';

    /** Any letter of a left-to-right script (Latin, CJK, Devanagari, Cyrillic…): every letter that is not RTL. */
    private const LTR = '/(?![\x{0590}-\x{08FF}\x{FB1D}-\x{FDFF}\x{FE70}-\x{FEFF}])\p{L}/u';

    private const JOINING = '/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{08A0}-\x{08FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF}]/u';

    /** The direction text reads in, from its first strong character (mirrors the core's textDirection). */
    public static function direction(string $text): string
    {
        foreach (mb_str_split($text) as $char) {
            if (preg_match(self::RTL, $char)) {
                return 'rtl';
            }
            if (preg_match(self::LTR, $char)) {
                return 'ltr';
            }
        }

        return 'ltr';
    }

    /**
     * Split text into animatable pieces, exactly as the core's splitText does:
     * joining scripts are never split into letters, and a run of words written
     * the other way stays together so it keeps its order.
     *
     * @return list<string>
     */
    public static function split(string $text, string $by = 'word'): array
    {
        if ($by === 'char' && ! preg_match(self::JOINING, $text)) {
            return preg_split('/(\X)/u', $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY) ?: [];
        }

        preg_match_all('/\S+\s*|\s+/u', $text, $matches);
        $base = self::direction($text);
        $other = $base === 'rtl' ? self::LTR : self::RTL;
        $own = $base === 'rtl' ? self::RTL : self::LTR;
        $pieces = [];
        $run = '';

        foreach ($matches[0] as $word) {
            $foreign = preg_match($other, $word) && ! preg_match($own, $word);
            $neutral = ! preg_match(self::RTL, $word) && ! preg_match(self::LTR, $word);
            if ($foreign || ($run !== '' && $neutral)) {
                $run .= $word;

                continue;
            }
            if ($run !== '') {
                $pieces[] = $run;
                $run = '';
            }
            $pieces[] = $word;
        }
        if ($run !== '') {
            $pieces[] = $run;
        }

        return $pieces;
    }

    /* ---- Numbers ------------------------------------------------------------------ */

    /** @return list<string> the ten digits 0–9 in the locale's numbering system */
    public static function digits(?string $locale = null): array
    {
        $locale = strtolower(substr($locale ?? app()->getLocale(), 0, 2));

        return match ($locale) {
            'fa' => ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'],
            'ar' => ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'],
            default => ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'],
        };
    }

    public static function formatNumber(float|int $value, int $decimals = 0, ?string $locale = null): string
    {
        $locale = strtolower(substr($locale ?? app()->getLocale(), 0, 2));
        [$thousands, $point] = match ($locale) {
            'fa' => ['٬', '٫'],
            'ar' => ['٬', '٫'],
            'de', 'es', 'tr', 'id', 'it', 'pt', 'nl' => ['.', ','],
            'fr', 'ru' => ["\u{202F}", ','],
            default => [',', '.'],
        };
        $western = number_format((float) $value, $decimals, '.', ',');
        $digits = self::digits($locale);

        return strtr($western, [',' => $thousands, '.' => $point] + array_combine(range(0, 9), $digits));
    }

    /**
     * Digit columns and fixed characters for a rolling number.
     *
     * @return list<array{kind: 'digit'|'static', value: int, char: string}>
     */
    public static function numberParts(float|int $value, int $decimals = 0, ?string $locale = null): array
    {
        $digits = self::digits($locale);
        $parts = [];
        foreach (mb_str_split(self::formatNumber($value, $decimals, $locale)) as $char) {
            $index = array_search($char, $digits, true);
            $parts[] = $index === false
                ? ['kind' => 'static', 'value' => 0, 'char' => $char]
                : ['kind' => 'digit', 'value' => (int) $index, 'char' => $char];
        }

        return $parts;
    }

    /* ---- Chart geometry (server-rendered sparklines and donuts) ----------------------- */

    /**
     * A monotone cubic line through the points (the same curve as the core's
     * linePath): it never overshoots, so it cannot invent a peak.
     *
     * @param  list<array{0: float, 1: float}>  $points
     */
    public static function linePath(array $points, bool $smooth = true): string
    {
        $r = fn (float $n) => round($n, 2);
        $n = count($points);
        if ($n === 0) {
            return '';
        }
        if (! $smooth || $n < 3) {
            return implode('', array_map(fn ($p, $i) => ($i ? 'L' : 'M').$r($p[0]).','.$r($p[1]), $points, array_keys($points)));
        }
        $dx = $slope = [];
        for ($i = 0; $i < $n - 1; $i++) {
            $dx[$i] = $points[$i + 1][0] - $points[$i][0];
            $slope[$i] = ($points[$i + 1][1] - $points[$i][1]) / ($dx[$i] ?: 1);
        }
        $tangent = [$slope[0]];
        for ($i = 1; $i < $n - 1; $i++) {
            $a = $slope[$i - 1];
            $b = $slope[$i];
            $tangent[$i] = $a * $b <= 0 ? 0 : (3 * ($dx[$i - 1] + $dx[$i])) / ((2 * $dx[$i] + $dx[$i - 1]) / $a + ($dx[$i] + 2 * $dx[$i - 1]) / $b);
        }
        $tangent[$n - 1] = $slope[$n - 2];
        $d = 'M'.$r($points[0][0]).','.$r($points[0][1]);
        for ($i = 0; $i < $n - 1; $i++) {
            [$x0, $y0] = $points[$i];
            [$x1, $y1] = $points[$i + 1];
            $h = $dx[$i] / 3;
            $d .= 'C'.$r($x0 + $h).','.$r($y0 + $tangent[$i] * $h).' '.$r($x1 - $h).','.$r($y1 - $tangent[$i + 1] * $h).' '.$r($x1).','.$r($y1);
        }

        return $d;
    }

    /** @param list<array{0: float, 1: float}> $points */
    public static function areaPath(array $points, float $baseline, bool $smooth = true): string
    {
        if (! $points) {
            return '';
        }
        $first = $points[0];
        $last = $points[count($points) - 1];

        return self::linePath($points, $smooth).'L'.round($last[0], 2).','.round($baseline, 2).'L'.round($first[0], 2).','.round($baseline, 2).'Z';
    }

    /**
     * Paths for a sparkline in a 120×40 box.
     *
     * @param  list<float|int>  $values
     * @return array{line: string, area: string, end: array{0: float, 1: float}}
     */
    public static function sparkline(array $values, int $width = 120, int $height = 40): array
    {
        $values = array_values($values);
        $min = min($values);
        $max = max($values);
        $span = ($max - $min) ?: 1;
        $count = max(1, count($values) - 1);
        $points = array_map(fn ($v, $i) => [2 + ($i / $count) * ($width - 6), ($height - 3) - (($v - $min) / $span) * ($height - 6)], $values, array_keys($values));

        return ['line' => self::linePath($points), 'area' => self::areaPath($points, $height), 'end' => $points[count($points) - 1]];
    }

    /**
     * Ring segments (pathLength 100) with a surface gap between neighbours.
     *
     * @param  list<float|int>  $values
     * @return list<array{length: float, offset: float, share: float}>
     */
    public static function donut(array $values, float $gap = 1.2): array
    {
        $total = array_sum(array_map(fn ($v) => max(0, $v), $values)) ?: 1;
        $gaps = count(array_filter($values, fn ($v) => $v > 0)) > 1 ? $gap : 0;
        $cursor = 0;
        $out = [];
        foreach ($values as $value) {
            $share = max(0, $value) / $total;
            $out[] = ['length' => round(max(0, $share * 100 - $gaps), 2), 'offset' => round(-$cursor, 2), 'share' => $share];
            $cursor += $share * 100;
        }

        return $out;
    }

    /* ---- Attributes ------------------------------------------------------------------- */

    /** The wire:model binding on a component (its property path), whatever its modifiers. */
    public static function model(\Illuminate\View\ComponentAttributeBag $attributes): ?string
    {
        foreach ($attributes->getAttributes() as $key => $value) {
            if (str_starts_with($key, 'wire:model')) {
                return (string) $value;
            }
        }

        return null;
    }

    /** The field's name for validation errors: its name attribute, or what wire:model binds. */
    public static function fieldName(\Illuminate\View\ComponentAttributeBag $attributes): ?string
    {
        return $attributes->get('name') ?? self::model($attributes);
    }

    /** The first validation error for a field, from the shared $errors bag. */
    public static function error(?string $field): ?string
    {
        if (! $field || ! app()->bound('view')) {
            return null;
        }
        $errors = view()->shared('errors');

        return $errors && method_exists($errors, 'first') ? ($errors->first($field) ?: null) : null;
    }

    /** A stable id for aria wiring when the caller gave none. */
    public static function id(string $prefix = 'nx'): string
    {
        static $n = 0;

        return $prefix.'-'.base_convert((string) (++$n), 10, 36).substr(md5((string) mt_rand()), 0, 4);
    }
}
