{{--
    An agent deploying: its log streams in (warnings and an error among them), the
    view follows the end — scroll up and "Jump to latest" appears. Filter by level in
    the header. Lines can also be pushed from anywhere with the nx-terminal-push event.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $labels = [
        'all' => $say('All', 'همه'), 'info' => $say('Info', 'اطلاع'), 'warn' => $say('Warn', 'هشدار'), 'error' => $say('Error', 'خطا'),
        'jump' => $say('Jump to latest', 'پرش به آخرین'), 'empty' => $say('No lines', 'خطی نیست'), 'log' => $say('Output', 'خروجی'),
    ];
    $clock = fn (int $s) => sprintf('10:24:%02d', $s);
    $script = [
        ['text' => '$ php artisan deploy --env=production', 'level' => 'debug', 'time' => $clock(1), 'delay' => 200],
        ['text' => 'Pulling main@4e1c9a2…', 'time' => $clock(2)],
        ['text' => 'Installing composer dependencies (82 packages)', 'time' => $clock(4)],
        ['text' => '[warn] abandoned package: fruitcake/laravel-cors — use the framework middleware', 'time' => $clock(6), 'delay' => 600],
        ['text' => 'Building assets with Vite', 'time' => $clock(9)],
        ['text' => 'WARN: chunk app.js is 612 kB after minification', 'time' => $clock(13), 'delay' => 700],
        ['text' => '✓ assets built in 3.9s', 'time' => $clock(13)],
        ['text' => 'Running migrations', 'time' => $clock(14)],
        ['text' => "ERROR: SQLSTATE[42S21] Duplicate column name 'archived_at'", 'time' => $clock(15), 'delay' => 900],
        ['text' => 'Rolling back the last batch', 'time' => $clock(16)],
        ['text' => '✓ rolled back 2026_10_01_add_archived_at_to_orders', 'time' => $clock(17)],
        ['text' => 'پیام برای تیم: استقرار متوقف شد، جزئیات در کانال #ops', 'time' => $clock(18)],
        ['text' => 'Deploy aborted — the previous release stays live', 'level' => 'warn', 'time' => $clock(18)],
    ];
    $history = [];
    foreach (range(1, 14) as $n) {
        $history[] = ['id' => 'w'.$n, 'time' => sprintf('09:%02d:12', $n * 4), 'text' => $n % 5 === 0 ? "[warn] queue latency {$n}00ms on emails" : "worker#".($n % 3 + 1)." processed job SendInvoice #".(4400 + $n)];
    }
@endphp
<div style="display: grid; gap: 1.25rem; max-inline-size: 52rem" x-data>
    <x-nx::terminal :title="$say('deploy · production', 'استقرار · محیط اصلی')" name="deploy" :script="$script" :labels="$labels"
        command="tail -f storage/logs/deploy.log" height="17rem" />
    <div class="pg-row">
        <x-nx::button size="sm" variant="ghost" icon="play" x-on:click="window.dispatchEvent(new CustomEvent('nx-terminal-push', { detail: { to: 'deploy', action: 'replay' } }))">{{ $say('Replay deploy', 'پخش دوبارهٔ استقرار') }}</x-nx::button>
        <x-nx::button size="sm" variant="ghost" icon="plus" x-on:click="window.dispatchEvent(new CustomEvent('nx-terminal-push', { detail: { to: 'deploy', text: '✓ health check passed (' + new Date().toLocaleTimeString() + ')' } }))">{{ $say('Push a line', 'افزودن یک خط') }}</x-nx::button>
    </div>

    <x-nx::terminal :title="$say('queue:work · last hour', 'queue:work · یک ساعت اخیر')" :lines="$history" :labels="$labels" filter="warn" :prompt="null" height="12rem" />
</div>
