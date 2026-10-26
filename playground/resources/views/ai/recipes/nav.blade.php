{{-- nav: getting around — breadcrumbs, tabs, and the action row. --}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $e, string $f) => $fa ? $f : $e;
    $crumbs = [['label' => $say('Workspace', 'ورک‌اسپیس')], ['label' => $say('Projects', 'پروژه‌ها')], ['label' => $say('Aurora', 'اورورا')]];
@endphp
<div style="max-inline-size: 36rem; margin-inline: auto; display: grid; gap: 1rem">
    <div class="aist-recipe-row" style="justify-content: space-between">
        <x-nx::breadcrumbs :items="$crumbs" />
        <x-nx::avatar-group :items="[
            ['initials' => 'MK', 'tone' => 'accent'],
            ['initials' => 'SA', 'tone' => 'success'],
            ['initials' => '+4'],
        ]" />
    </div>
    <x-nx::tabs :items="[
        ['value' => 'overview', 'label' => $say('Overview', 'نمای کلی')],
        ['value' => 'tasks', 'label' => $say('Tasks', 'کارها')],
        ['value' => 'files', 'label' => $say('Files', 'فایل‌ها')],
        ['value' => 'activity', 'label' => $say('Activity', 'فعالیت')],
    ]" value="overview" />
    <div class="aist-recipe-row" style="justify-content: space-between">
        <span style="font-size: var(--nx-text-sm); color: var(--nx-text-muted)">{{ $say('Last edit 4 min ago by Mina', 'آخرین ویرایش ۴ دقیقه پیش، مینا') }}</span>
        <x-nx::button variant="primary" size="sm" icon="edit">{{ $say('Edit', 'ویرایش') }}</x-nx::button>
    </div>
</div>
