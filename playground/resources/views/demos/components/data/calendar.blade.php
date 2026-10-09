{{--
    The calendar's real scenarios: the team month — events as chips, the
    selected day's agenda under the grid — then a compact release calendar
    keyed to the work week.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $events = [
        ['date' => '2026-09-03', 'label' => $say('Design review', 'بازبینی طراحی'), 'time' => '10:00', 'tone' => 'accent'],
        ['date' => '2026-09-03', 'label' => $say('Copy deadline', 'مهلت متن‌ها'), 'time' => '17:00', 'tone' => 'danger'],
        ['date' => '2026-09-08', 'label' => $say('Kanban block ships', 'انتشار بلوک کانبان'), 'tone' => 'success'],
        ['date' => '2026-09-10', 'label' => $say('Support handover · Tokyo', 'تحویل شیفت توکیو'), 'time' => '09:30', 'tone' => 'info'],
        ['date' => '2026-09-14', 'label' => $say('Design review', 'بازبینی طراحی'), 'time' => '10:00', 'tone' => 'accent'],
        ['date' => '2026-09-14', 'label' => $say('Ship v2.5', 'انتشار نسخهٔ ۲٫۵'), 'time' => '16:30', 'tone' => 'danger', 'href' => '#'],
        ['date' => '2026-09-14', 'label' => $say('Release party', 'جشن انتشار'), 'time' => '19:00', 'tone' => 'gold'],
        ['date' => '2026-09-14', 'label' => $say('On-call rotates', 'چرخش روی‌کال'), 'tone' => 'neutral'],
        ['date' => '2026-09-22', 'label' => $say('Team offsite · Caspian', 'گردشوری تیم · خزر'), 'tone' => 'gold'],
        ['date' => '2026-09-24', 'label' => $say('Board meeting', 'جلسهٔ هیئت'), 'time' => '11:00', 'tone' => 'info', 'href' => '#'],
        ['date' => '2026-09-30', 'label' => $say('Quarter closes', 'پایان فصل'), 'tone' => 'warning'],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('September, the shipping month', 'سپتامبر، ماهِ انتشار') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Pick a day — the 14th is full, so its last event folds into a “+1” tally. The agenda under the grid restacks per day; picking the selected day clears it. Arrow keys walk the days, Home/End run to the week’s edges.', 'یک روز را انتخاب کنید — ۱۴ام پر است پس آخرین رویدادش در شمارندهٔ «+۱» جمع می‌شود. دستور کار زیر شبکه برای هر روز دوباره چیده می‌شود؛ انتخابِ روزِ انتخاب‌شده آن را پاک می‌کند. فلش‌ها روزها را قدم می‌زنند و Home/End به دو سر هفته می‌روند.') }}
        </p>
    </div>
    <x-nx::calendar
        :events="$events"
        value="2026-09-14"
        month="2026-09"
        today="2026-09-30"
        :max-per-cell="3"
        :label="$say('Team calendar', 'تقویم تیم')"
        wire:model="state.day" />
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('Selected day on the server (wire:model):', 'روز انتخابی روی سرور (wire:model):') }}
        <code>{{ $state['day'] ?? '—' }}</code>
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem; max-inline-size: 34rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The release calendar', 'تقویم انتشار') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('week-start pins Saturday for a Saturday-first work week and every chip stays a link to its release note. Switching months is client-side: the new month slides in from its side, mirrored in RTL.', 'week-start شنبه را برای هفتهٔ کاری شنبه‌شروع می‌گذارد و هر چیپ لینکِ یادداشت انتشار خودش می‌ماند. تعویض ماه سمت مرورگر است: ماه تازه از سمتش می‌آید و در راست‌به‌چپ آینه می‌شود.') }}
        </p>
    </div>
    <x-nx::calendar
        :label="$say('Releases', 'انتشارها')"
        week-start="6"
        :max-per-cell="2"
        :events="[
            ['date' => '2026-09-05', 'label' => 'v2.4.1', 'tone' => 'success', 'href' => '#'],
            ['date' => '2026-09-14', 'label' => 'v2.5.0', 'tone' => 'danger', 'href' => '#'],
            ['date' => '2026-09-28', 'label' => 'v2.5.1', 'tone' => 'accent', 'href' => '#'],
        ]" />
</section>
