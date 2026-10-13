<?php

namespace App\Livewire;

use App\Livewire\Concerns\SwitchesDemoLocale;
use App\Support\DemoCatalog;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * /components — the searchable catalog of the standalone component demos.
 * Every group's demos come from its manifest in app/Support/Demos.
 */
#[Layout('layouts.app')]
class ComponentCatalog extends Component
{
    use SwitchesDemoLocale;

    public string $search = '';

    public string $group = 'all';

    public function mount(): void
    {
        $this->rememberLocale();
    }

    public function resetFilters(): void
    {
        $this->search = '';
        $this->group = 'all';
    }

    /**
     * The demos left after the group chip and the live search. The search
     * folds case, accents and the Persian letter variants, so either
     * spelling of a title matches.
     *
     * @return array<int, array{group: string, slug: string, title: string, oneLiner: string, js: bool, icon: ?string}>
     */
    public function filtered(): array
    {
        $needle = self::fold($this->search);
        $locale = app()->getLocale();

        $demos = [];
        foreach (DemoCatalog::flat() as $demo) {
            if ($this->group !== 'all' && $this->group !== $demo['group']) {
                continue;
            }

            $haystack = self::fold(implode(' ', [
                DemoCatalog::both($demo['title'] ?? ''),
                DemoCatalog::both($demo['oneLiner'] ?? ''),
                $demo['slug'] ?? '',
                $demo['group'] ?? '',
            ]));

            if ($needle !== '' && ! str_contains($haystack, $needle)) {
                continue;
            }

            $demos[] = [
                'group' => $demo['group'],
                'slug' => $demo['slug'],
                'title' => DemoCatalog::pick($demo['title'] ?? '', $locale),
                'oneLiner' => DemoCatalog::pick($demo['oneLiner'] ?? '', $locale),
                'js' => (bool) ($demo['js'] ?? false),
                'icon' => $demo['icon'] ?? null,
            ];
        }

        return $demos;
    }

    public function render()
    {
        return view('livewire.component-catalog', [
            'demos' => $this->filtered(),
            'groups' => DemoCatalog::groups(),
            'total' => count(DemoCatalog::flat()),
        ]);
    }

    /** Fold case, accents and the Persian/Arabic letter variants (the React blocks' `fold`). */
    private static function fold(string $text): string
    {
        $folded = mb_strtolower($text);
        $folded = normalizer_normalize($folded, \Normalizer::FORM_KD) ?: $folded;
        $folded = preg_replace('/[\x{0300}-\x{036f}]/u', '', $folded) ?? $folded;
        $folded = str_replace(['ي', 'ى'], 'ی', $folded);
        $folded = str_replace('ك', 'ک', $folded);

        return preg_replace('/[\x{064b}-\x{0670}\x{065f}\x{200c}]/u', '', $folded) ?? $folded;
    }
}
