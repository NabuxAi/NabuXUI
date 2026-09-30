<?php

namespace App\Support;

/**
 * The registry of the standalone component demos (/components).
 *
 * Each group owns one manifest file in app/Support/Demos: `Base.php` is the
 * group "base", `Form.php` would be "form", and so on — drop a new file in
 * and the catalog picks it up, nothing to register. A manifest returns an
 * array of `['slug' => […]]` where every entry describes one demo:
 *
 *   'title'     => ['fa' => 'دکمه', 'en' => 'Button']   // or ['دکمه', 'Button']
 *   'oneLiner'  => ['fa' => …, 'en' => …]
 *   'js'        => true|false   — needs behavioural JS, or pure CSS
 *   'docs'      => 'https://…'  — optional link, hidden when falsy
 *   'icon'      => 'zap'        — optional catalog icon (core icon names)
 *   'props'     => [['name' => …, 'type' => …, 'default' => …, 'note' => ['fa' => …, 'en' => …]], …]
 *   'code'      => '<x-nx::button …>'  — the copyable snippet
 *
 * A reserved `__group` key labels the group itself (['fa' => …, 'en' => …]);
 * without it the group id is capitalised. The demo's scenarios live in the
 * partial `resources/views/demos/components/{group}/{slug}.blade.php` —
 * authors add that partial next to their manifest entry.
 */
class DemoCatalog
{
    /** Group and slug ids are kebab-case, letter first. */
    private const ID = '/^[a-z][a-z0-9-]*$/';

    private static ?array $cache = null;

    /** @return array<string, array{label: array, items: array}> every group, sorted by id. */
    public static function groups(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        $groups = [];
        foreach (glob(app_path('Support/Demos/*.php')) ?: [] as $file) {
            $group = strtolower(basename($file, '.php'));
            $items = require $file;
            if (! is_array($items)) {
                continue;
            }

            $label = $items['__group'] ?? null;
            unset($items['__group']);
            $groups[$group] = [
                'label' => is_array($label) ? $label : ['fa' => $group, 'en' => ucfirst($group)],
                'items' => $items,
            ];
        }

        ksort($groups);

        return self::$cache = $groups;
    }

    /** @return array<int, array{group: string, slug: string, title: array, oneLiner: array, js: bool}> flat, in catalog order. */
    public static function flat(): array
    {
        $flat = [];
        foreach (self::groups() as $group => $manifest) {
            foreach ($manifest['items'] as $slug => $item) {
                $flat[] = ['group' => (string) $group, 'slug' => (string) $slug] + (array) $item;
            }
        }

        return $flat;
    }

    /** One demo's manifest entry, or null when the group/slug is unknown. */
    public static function find(string $group, string $slug): ?array
    {
        if (preg_match(self::ID, $group) !== 1 || preg_match(self::ID, $slug) !== 1) {
            return null;
        }

        return self::groups()[$group]['items'][$slug] ?? null;
    }

    /** The previous and next demo within the same group (null at the edges). */
    public static function neighbors(string $group, string $slug): array
    {
        $slugs = array_keys(self::groups()[$group]['items'] ?? []);
        $index = array_search($slug, $slugs, true);

        return [
            'prev' => $index === false || $index === 0 ? null : ['group' => $group, 'slug' => $slugs[$index - 1]],
            'next' => $index === false || $index === count($slugs) - 1 ? null : ['group' => $group, 'slug' => $slugs[$index + 1]],
        ];
    }

    /** The demo partial's view name for a group/slug. */
    public static function viewName(string $group, string $slug): string
    {
        return "demos.components.{$group}.{$slug}";
    }

    /** Whether the demo partial exists yet (manifest entries may ship before their scenarios). */
    public static function hasScenarios(string $group, string $slug): bool
    {
        return view()->exists(self::viewName($group, $slug));
    }

    /**
     * Pick a bilingual value (['fa' => …, 'en' => …] or ['فارسی', 'English'])
     * for the active — or the given — locale, falling back to whatever is there.
     */
    public static function pick(array|string $value, ?string $locale = null): string
    {
        if (is_string($value)) {
            return $value;
        }

        $locale = substr($locale ?? app()->getLocale(), 0, 2);
        if (isset($value[$locale]) && (is_string($value[$locale]) || is_numeric($value[$locale]))) {
            return (string) $value[$locale];
        }

        return (string) (reset($value) ?: '');
    }

    /** Both languages of a bilingual value as one string ("فارسی English") — for searching. */
    public static function both(array|string $value): string
    {
        return is_string($value) ? $value : implode(' ', array_map(
            static fn ($part) => is_scalar($part) ? (string) $part : '',
            array_values($value),
        ));
    }
}
