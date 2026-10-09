<?php

namespace App\Support;

/**
 * The playground's single registry of supported languages — the one place a
 * language exists. Adding a new language is ONE entry in REGISTRY below: the
 * code becomes a valid `?lang=…`, the session locale, an html lang/dir and a
 * language-menu row, everywhere at once. Nothing else to register.
 *
 * Demo content itself is only bilingual (fa/en); every other language gets a
 * correct UI — direction from dir(), Intl formatting from the locale, and
 * untranslated strings fall back to English (Laravel's fallback_locale) —
 * never a fake translation.
 */
class Locales
{
    /** code => ['name' => native name, 'dir' => ltr|rtl], menu order. */
    private const REGISTRY = [
        'fa' => ['name' => 'فارسی', 'dir' => 'rtl'],
        'en' => ['name' => 'English', 'dir' => 'ltr'],
        'ar' => ['name' => 'العربية', 'dir' => 'rtl'],
        'he' => ['name' => 'עברית', 'dir' => 'rtl'],
        'ur' => ['name' => 'اردو', 'dir' => 'rtl'],
        'tr' => ['name' => 'Türkçe', 'dir' => 'ltr'],
        'de' => ['name' => 'Deutsch', 'dir' => 'ltr'],
        'fr' => ['name' => 'Français', 'dir' => 'ltr'],
        'es' => ['name' => 'Español', 'dir' => 'ltr'],
        'it' => ['name' => 'Italiano', 'dir' => 'ltr'],
        'pt' => ['name' => 'Português', 'dir' => 'ltr'],
        'nl' => ['name' => 'Nederlands', 'dir' => 'ltr'],
        'sv' => ['name' => 'Svenska', 'dir' => 'ltr'],
        'pl' => ['name' => 'Polski', 'dir' => 'ltr'],
        'uk' => ['name' => 'Українська', 'dir' => 'ltr'],
        'ru' => ['name' => 'Русский', 'dir' => 'ltr'],
        'zh' => ['name' => '中文', 'dir' => 'ltr'],
        'ja' => ['name' => '日本語', 'dir' => 'ltr'],
        'ko' => ['name' => '한국어', 'dir' => 'ltr'],
        'hi' => ['name' => 'हिन्दी', 'dir' => 'ltr'],
        'id' => ['name' => 'Bahasa Indonesia', 'dir' => 'ltr'],
        'vi' => ['name' => 'Tiếng Việt', 'dir' => 'ltr'],
        'th' => ['name' => 'ไทย', 'dir' => 'ltr'],
        'el' => ['name' => 'Ελληνικά', 'dir' => 'ltr'],
        'az' => ['name' => 'Azərbaycan', 'dir' => 'ltr'],
    ];

    /** The whole registry, keyed by code. */
    public static function all(): array
    {
        return self::REGISTRY;
    }

    /** Every registered language code, in menu order. */
    public static function codes(): array
    {
        return array_keys(self::REGISTRY);
    }

    /** A code's writing direction — "rtl" for fa/ar/he/ur, "ltr" otherwise. */
    public static function dir(string $code): string
    {
        return self::REGISTRY[$code]['dir'] ?? 'ltr';
    }

    /** The registry as x-nx::language-menu rows: ['id', 'name', 'short']. */
    public static function forMenu(): array
    {
        $rows = [];
        foreach (self::REGISTRY as $code => $entry) {
            $rows[] = [
                'id' => $code,
                'name' => $entry['name'],
                'short' => strtoupper($code),
            ];
        }

        return $rows;
    }
}
