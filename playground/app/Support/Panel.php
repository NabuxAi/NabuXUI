<?php

namespace App\Support;

use Illuminate\Support\Carbon;
use NabuXUI\NabuXUI;

/**
 * Shared data for every admin page: the sidebar's groups, the topbar's user
 * chip and activity bell, and the command palette's entries. One place, so
 * <x-admin.page> (the common shell layout) and the pages always agree.
 */
class Panel
{
    /** The fa/en pair the topbar's language menu offers. */
    public static function languages(): array
    {
        return [
            ['id' => 'fa', 'name' => 'فارسی', 'short' => 'فا'],
            ['id' => 'en', 'name' => 'English', 'short' => 'EN'],
        ];
    }

    /** Sidebar groups → items. Ids match the pages' `active` value. */
    public static function nav(): array
    {
        return [
            [
                'label' => __('admin.group_main'),
                'items' => [
                    ['id' => 'dashboard', 'label' => __('admin.dashboard'), 'icon' => 'grid', 'href' => route('admin.dashboard'), 'navigate' => true],
                    ['id' => 'analytics', 'label' => __('admin.analytics'), 'icon' => 'chart', 'href' => route('admin.analytics'), 'navigate' => true],
                    ['id' => 'users', 'label' => __('admin.users'), 'icon' => 'users', 'href' => route('admin.users'), 'navigate' => true, 'badge' => 2],
                ],
            ],
            [
                'label' => __('admin.group_shop'),
                'items' => [
                    ['id' => 'products', 'label' => __('admin.products'), 'icon' => 'heart', 'href' => route('admin.products'), 'navigate' => true],
                    ['id' => 'orders', 'label' => __('admin.orders'), 'icon' => 'zap', 'href' => route('admin.orders'), 'navigate' => true, 'badge' => 3],
                ],
            ],
            [
                'label' => __('admin.group_work'),
                'items' => [
                    ['id' => 'kanban', 'label' => __('admin.kanban'), 'icon' => 'layers', 'href' => route('admin.kanban'), 'navigate' => true],
                    ['id' => 'calendar', 'label' => __('admin.calendar'), 'icon' => 'file', 'href' => route('admin.calendar'), 'navigate' => true],
                    ['id' => 'chat', 'label' => __('admin.chat'), 'icon' => 'message', 'href' => route('admin.chat'), 'navigate' => true, 'badge' => 5],
                    ['id' => 'invoice', 'label' => __('admin.invoice'), 'icon' => 'copy', 'href' => route('admin.invoice'), 'navigate' => true],
                ],
            ],
            [
                'label' => __('admin.group_pages'),
                'items' => [
                    ['id' => 'email', 'label' => __('admin.email'), 'icon' => 'mail', 'href' => route('admin.email'), 'navigate' => true, 'badge' => 3],
                    ['id' => 'files', 'label' => __('admin.files'), 'icon' => 'folder', 'href' => route('admin.files'), 'navigate' => true],
                    ['id' => 'todo', 'label' => __('admin.todo'), 'icon' => 'check-circle', 'href' => route('admin.todo'), 'navigate' => true],
                    ['id' => 'roles', 'label' => __('admin.roles'), 'icon' => 'shield', 'href' => route('admin.roles'), 'navigate' => true],
                    ['id' => 'errors', 'label' => __('admin.errors'), 'icon' => 'alert-triangle', 'href' => route('admin.errors.404'), 'navigate' => true],
                ],
            ],
            [
                'label' => __('admin.group_account'),
                'items' => [
                    ['id' => 'profile', 'label' => __('admin.profile'), 'icon' => 'user', 'href' => route('admin.profile'), 'navigate' => true],
                    ['id' => 'settings', 'label' => __('admin.settings'), 'icon' => 'settings', 'href' => route('admin.settings'), 'navigate' => true],
                ],
            ],
        ];
    }

    /** The signed-in person for the topbar's avatar chip. */
    public static function user(): array
    {
        return [
            'name' => __('admin.user_name'),
            'role' => __('admin.user_role'),
        ];
    }

    /** The topbar bell's dropdown; unread until "mark all read" flips the session flag. */
    public static function activity(): array
    {
        $read = (bool) session('admin_activity_read', false);
        $fmt = fn (int $n) => NabuXUI::formatNumber($n, 0, app()->getLocale());
        $say = fn (string $key, array $replace = []) => __($key, $replace);

        return [
            ['id' => 'a1', 'actor' => ['name' => __('admin.person_1')], 'text' => $say('admin.activity_confirmed', ['target' => '#'.$fmt(1248)]), 'time' => now()->subMinutes(3), 'unread' => ! $read, 'href' => route('admin.invoice')],
            ['id' => 'a2', 'actor' => ['name' => __('admin.person_3')], 'text' => $say('admin.activity_joined'), 'time' => now()->subHours(1), 'unread' => ! $read, 'href' => route('admin.users')],
            ['id' => 'a3', 'icon' => 'upload', 'actor' => ['name' => __('admin.person_2')], 'text' => $say('admin.activity_backup'), 'time' => now()->subHours(5)],
            ['id' => 'a4', 'actor' => ['name' => __('admin.person_2')], 'text' => $say('admin.activity_shipped', ['target' => $fmt(201)]), 'time' => now()->subDays(1), 'href' => route('admin.kanban')],
        ];
    }

    /** The ⌘K palette: every page plus a few quick actions. */
    public static function command(): array
    {
        $pages = [];
        foreach (self::nav() as $group) {
            foreach ($group['items'] as $item) {
                $pages[] = ['id' => $item['id'], 'label' => $item['label'], 'icon' => $item['icon'], 'href' => $item['href']];
            }
        }

        return [
            ['label' => __('admin.command_pages'), 'items' => $pages],
            ['label' => __('admin.command_actions'), 'items' => [
                ['id' => 'invite', 'label' => __('admin.command_invite'), 'icon' => 'users', 'href' => route('admin.users')],
                ['id' => 'new-invoice', 'label' => __('admin.command_new_invoice'), 'icon' => 'copy', 'href' => route('admin.invoice')],
                ['id' => 'site', 'label' => __('admin.command_site'), 'icon' => 'globe', 'href' => url('/')],
            ]],
        ];
    }

    /** A date's short month in the panel's calendar style: Jalali for fa, Gregorian otherwise. */
    public static function monthLabel(Carbon $date): string
    {
        $locale = app()->getLocale();

        if (class_exists(\IntlDateFormatter::class)) {
            // ICU 78 ignores a "@calendar=persian" locale keyword once a pattern
            // is given, so the calendar rides in as an IntlCalendar object.
            $calendar = null;
            if ($locale === 'fa' && class_exists(\IntlCalendar::class)) {
                $calendar = \IntlCalendar::createInstance('UTC', 'fa@calendar=persian');
            }

            $format = new \IntlDateFormatter($locale, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, 'UTC', $calendar, 'MMM');

            return (string) $format->format($date->getTimestamp());
        }

        return $date->locale($locale)->shortMonthName;
    }
}
