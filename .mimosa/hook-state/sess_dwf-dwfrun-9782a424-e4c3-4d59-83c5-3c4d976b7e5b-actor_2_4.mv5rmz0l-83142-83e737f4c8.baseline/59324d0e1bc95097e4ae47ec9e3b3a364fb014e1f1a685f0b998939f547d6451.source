{{--
    An agent's proposed edits under review: a long translation file where only two
    lines changed (the rest folds into "expand"), shown unified, and a config rewrite
    shown side by side.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $labels = [
        'unified' => $say('Unified', 'یکپارچه'), 'split' => $say('Split', 'دوستونه'),
        'expand' => $say('Expand :count unchanged lines', ':count خط بدون تغییر را باز کن'),
        'noChanges' => $say('No changes', 'بدون تغییر'), 'summary' => $say(':added additions, :removed deletions', ':added افزوده، :removed حذف'),
    ];

    $keys = ['welcome' => 'خوش آمدید', 'login' => 'ورود', 'logout' => 'خروج', 'profile' => 'نمایه', 'settings' => 'تنظیمات', 'orders' => 'سفارش‌ها',
        'cart' => 'سبد خرید', 'checkout' => 'پرداخت', 'search' => 'جست‌وجو', 'help' => 'راهنما', 'contact' => 'تماس', 'about' => 'درباره',
        'privacy' => 'حریم خصوصی', 'terms' => 'قوانین', 'language' => 'زبان', 'theme' => 'پوسته', 'save' => 'ذخیره', 'cancel' => 'انصراف'];
    $line = fn ($key, $value) => "    '{$key}' => '{$value}',";
    $oldLang = "<?php\n\nreturn [\n".implode("\n", array_map($line, array_keys($keys), $keys))."\n];";
    $newKeys = $keys;
    $newKeys['checkout'] = 'تسویه‌حساب';
    $newKeys['search'] = 'جستجو در فروشگاه';
    $newLang = "<?php\n\nreturn [\n".implode("\n", array_map($line, array_keys($newKeys), $newKeys))."\n];";

    $oldYaml = "name: tests\non: [push]\njobs:\n  test:\n    runs-on: ubuntu-latest\n    steps:\n      - uses: actions/checkout@v3\n      - uses: shivammathur/setup-php@v2\n        with:\n          php-version: '8.2'\n      - run: composer install\n      - run: vendor/bin/phpunit";
    $newYaml = "name: tests\non: [push, pull_request]\njobs:\n  test:\n    runs-on: ubuntu-latest\n    strategy:\n      matrix:\n        php: ['8.3', '8.4']\n    steps:\n      - uses: actions/checkout@v4\n      - uses: shivammathur/setup-php@v2\n        with:\n          php-version: \${{ matrix.php }}\n      - run: composer install --no-progress\n      - run: vendor/bin/pest --parallel";
@endphp
<div style="display: grid; gap: 1.25rem; max-inline-size: 52rem">
    <x-nx::diff-viewer filename="lang/fa/shop.php" :old-text="$oldLang" :new-text="$newLang" :context="2" :labels="$labels" />
    <x-nx::diff-viewer filename=".github/workflows/tests.yml" :old-text="$oldYaml" :new-text="$newYaml" view="split" :labels="$labels" max-height="24rem" />
</div>
