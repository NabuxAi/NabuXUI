<?php

namespace App\Support;

/**
 * The "browse by use case" axis over the demo catalog: the 41 technical
 * groups answer "what is it", the twelve use cases here answer "what am I
 * building". Each bucket maps to the catalog groups that serve it and carries
 * the keywords the AI studio (App\Livewire\AiStudio) scores prompts against —
 * so "می‌خواهم کاربر وارد شود" and "a sign-up form" both land on auth.
 */
class UseCases
{
    /** @return array<string, array{id: string, label: array, description: array, icon: string, groups: list<string>, keywords: list<string>}> */
    public static function all(): array
    {
        return [
            'auth' => [
                'id' => 'auth',
                'label' => ['fa' => 'ورود و ثبت‌نام', 'en' => 'Sign-in & sign-up'],
                'description' => [
                    'fa' => 'هر چیزی که کاربر را وارد می‌کند: فرم‌ها، کد یک‌بارمصرف، گیت احراز هویت و جریان‌های چندقدمی.',
                    'en' => 'Everything that lets a user in: forms, one-time codes, the auth gate and multi-step flows.',
                ],
                'icon' => 'lock',
                'groups' => ['base', 'flows', 'menus'],
                'keywords' => ['ورود', 'ثبت‌نام', 'ثبت نام', 'رمز', 'لاگین', 'login', 'sign', 'signup', 'register', 'auth', 'password', 'otp', 'onboarding'],
            ],
            'forms' => [
                'id' => 'forms',
                'label' => ['fa' => 'فرم و ورودی', 'en' => 'Forms & inputs'],
                'description' => [
                    'fa' => 'گرفتن داده از کاربر: فیلدها، انتخابگرها، جست‌وجو و اعتبارسنجی.',
                    'en' => 'Collecting data from the user: fields, pickers, search and validation.',
                ],
                'icon' => 'edit',
                'groups' => ['base', 'pickers', 'icon-motion'],
                'keywords' => ['فرم', 'ورودی', 'فیلد', 'انتخاب', 'تاریخ', 'tag', 'form', 'input', 'field', 'picker', 'date', 'select', 'combobox', 'validation'],
            ],
            'data' => [
                'id' => 'data',
                'label' => ['fa' => 'داشبورد و آمار', 'en' => 'Dashboards & analytics'],
                'description' => [
                    'fa' => 'نمودن وضعیت در یک نگاه: کارت‌های آمار، نمودارها، گرید و هیتمپ.',
                    'en' => 'State at a glance: stat cards, charts, grids and heatmaps.',
                ],
                'icon' => 'chart',
                'groups' => ['data', 'charts-plus', 'cards'],
                'keywords' => ['داشبورد', 'آمار', 'نمودار', 'تحلیل', 'فروش', 'dashboard', 'chart', 'graph', 'stat', 'analytics', 'metric', 'report', 'kpi'],
            ],
            'tables' => [
                'id' => 'tables',
                'label' => ['fa' => 'جدول و فهرست', 'en' => 'Tables & lists'],
                'description' => [
                    'fa' => 'ردیف‌های داده: جدول با انتخاب گروهی، فهرست‌های سبک و دیدهای سیستمی.',
                    'en' => 'Rows of data: tables with bulk selection, featherweight lists and system views.',
                ],
                'icon' => 'grid',
                'groups' => ['data', 'carbon', 'primer', 'lightning'],
                'keywords' => ['جدول', 'فهرست', 'لیست', 'ردیف', 'table', 'list', 'grid', 'rows', 'datagrid', 'inventory'],
            ],
            'chat' => [
                'id' => 'chat',
                'label' => ['fa' => 'گفت‌وگو و پشتیبانی', 'en' => 'Chat & support'],
                'description' => [
                    'fa' => 'حرف‌زدن با کاربر: چت، ایجنت‌های هوشمند، جریان پاسخ و ابزارها.',
                    'en' => 'Talking to the user: chat, AI agents, streaming replies and tools.',
                ],
                'icon' => 'message',
                'groups' => ['agent', 'data', 'menus'],
                'keywords' => ['چت', 'گفتگو', 'گفت‌وگو', 'پیام', 'پشتیبانی', 'هوش مصنوعی', 'chat', 'message', 'support', 'agent', 'ai', 'assistant', 'stream'],
            ],
            'shop' => [
                'id' => 'shop',
                'label' => ['fa' => 'فروشگاه و پرداخت', 'en' => 'Shop & checkout'],
                'description' => [
                    'fa' => 'فروختن: کارت محصول، سبد، پرداخت، فاکتور و رهگیری سفارش.',
                    'en' => 'Selling: product cards, cart, checkout, invoices and order tracking.',
                ],
                'icon' => 'zap',
                'groups' => ['cards', 'cta', 'flows'],
                'keywords' => ['فروشگاه', 'خرید', 'پرداخت', 'سبد', 'محصول', 'فاکتور', 'shop', 'store', 'product', 'cart', 'checkout', 'payment', 'invoice', 'pricing', 'order'],
            ],
            'feedback' => [
                'id' => 'feedback',
                'label' => ['fa' => 'اعلان و بازخورد', 'en' => 'Notifications & feedback'],
                'description' => [
                    'fa' => 'باخبرکردن کاربر: تُست، هشدار، بارگذارها و اثرهای تأیید.',
                    'en' => 'Telling the user something: toasts, alerts, loaders and confirmation effects.',
                ],
                'icon' => 'bell',
                'groups' => ['base', 'interact', 'loaders', 'glass'],
                'keywords' => ['اعلان', 'تست', 'هشدار', 'بارگذاری', 'تایید', 'toast', 'notification', 'alert', 'loader', 'spinner', 'progress', 'confirm', 'feedback'],
            ],
            'nav' => [
                'id' => 'nav',
                'label' => ['fa' => 'ناوبری و منو', 'en' => 'Navigation & menus'],
                'description' => [
                    'fa' => 'جابه‌جایی در محصول: هدرها، منوها، نوبارها و داک‌ها.',
                    'en' => 'Getting around the product: headers, menus, navbars and docks.',
                ],
                'icon' => 'globe',
                'groups' => ['menus', 'navbars', 'base'],
                'keywords' => ['منو', 'ناوبری', 'هدر', 'سایدبار', 'تب', 'nav', 'navbar', 'menu', 'header', 'sidebar', 'tab', 'breadcrumb', 'dock'],
            ],
            'media' => [
                'id' => 'media',
                'label' => ['fa' => 'فایل و رسانه', 'en' => 'Files & media'],
                'description' => [
                    'fa' => 'محتوا و فایل: مدیریت فایل، گالری، لایت‌باکس و آپلود.',
                    'en' => 'Content and files: file managers, galleries, lightboxes and uploads.',
                ],
                'icon' => 'folder',
                'groups' => ['data', 'interact', 'cards'],
                'keywords' => ['فایل', 'رسانه', 'گالری', 'عکس', 'آپلود', 'file', 'media', 'gallery', 'image', 'upload', 'lightbox', 'photo'],
            ],
            'schedule' => [
                'id' => 'schedule',
                'label' => ['fa' => 'تقویم و زمان‌بندی', 'en' => 'Calendar & scheduling'],
                'description' => [
                    'fa' => 'زمان: تقویم، گانت، بازهٔ تاریخ و برنامهٔ کاری.',
                    'en' => 'Time: calendars, gantt charts, date ranges and work plans.',
                ],
                'icon' => 'layers',
                'groups' => ['data', 'menus', 'pickers'],
                'keywords' => ['تقویم', 'زمان‌بندی', 'زمانبندی', 'قرار', 'گانت', 'calendar', 'schedule', 'gantt', 'date range', 'booking', 'event', 'task'],
            ],
            'states' => [
                'id' => 'states',
                'label' => ['fa' => 'خطا و حالت خالی', 'en' => 'Errors & empty states'],
                'description' => [
                    'fa' => 'لحظه‌های بدون داده: صفحه‌های خطا، خالی، در دست تعمیر و صفحه‌خوان.',
                    'en' => 'The no-data moments: error pages, empty states, maintenance and screen readers.',
                ],
                'icon' => 'info',
                'groups' => ['base', 'windows'],
                'keywords' => ['خطا', 'خالی', '۴۰۴', 'پیدا نشد', 'تعمیر', 'error', 'empty', '404', 'not found', 'maintenance', 'offline', 'skeleton'],
            ],
            'landing' => [
                'id' => 'landing',
                'label' => ['fa' => 'لندینگ و معرفی', 'en' => 'Landing & marketing'],
                'description' => [
                    'fa' => 'معرفی محصول: هیرو، بخش‌های لندینگ، CTA، فوتر و حرکت اسکرول.',
                    'en' => 'Introducing the product: heroes, landing sections, CTAs, footers and scroll motion.',
                ],
                'icon' => 'home',
                'groups' => ['heroes', 'sections', 'cta', 'footers', 'scroll', 'text-fx'],
                'keywords' => ['لندینگ', 'هیرو', 'معرفی', 'تبلیغ', 'اسکرول', 'landing', 'hero', 'marketing', 'cta', 'footer', 'scroll', 'homepage', 'banner'],
            ],
        ];
    }

    /** One bucket by id, or null. */
    public static function find(string $id): ?array
    {
        return self::all()[$id] ?? null;
    }

    /** Bucket ids in display order. */
    public static function ids(): array
    {
        return array_keys(self::all());
    }

    /** Every catalog group id that at least one bucket claims — for counting. */
    public static function coveredGroups(): array
    {
        $covered = [];
        foreach (self::all() as $bucket) {
            $covered = [...$covered, ...$bucket['groups']];
        }

        return array_unique($covered);
    }
}
