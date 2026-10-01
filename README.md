# NabuXUI — سیستم طراحی Nabu

**یک هسته، هر فریم‌ورکی.** کامپوننت‌های متحرک و دسترس‌پذیر با یک زبان حرکت
مشترک برای **Livewire/Blade، React، Inertia، Vue و Svelte** — دوزبانه
(فارسی/انگلیسی)، دوتم (روشن/تیره) و راست‌به‌چپ از روز اول.

- **نمایشگاه React:** `pnpm showcase` → `http://localhost:5199`
- **پلی‌گراوند Livewire:** `docker compose up` یا `php artisan serve` در `playground/`
- **پنل مدیریت دمو:** `/admin` در پلی‌گراوند و `pnpm --filter admin dev` (React) — [docs/ADMIN.md](docs/ADMIN.md)

## چرا NabuXUI؟

| | |
|---|---|
| **یک زبان حرکت** | فنرهای استاندارد (`--nx-spring-gentle/snappy/bouncy/soft`) و ضریب `--nx-motion` که با `prefers-reduced-motion` صفر می‌شود — هر کامپوننت در هر فریم‌ورکی یکسان حرکت می‌کند. |
| **یک هستهٔ مشترک** | CSS لایه‌ای (`@layer nx.*`) + رفتارهای JS خالص (`(el, options) => cleanup`) در `@nabuxai/ui-core`. لایه‌های React و Blade/Alpine فقط پوستهٔ همان هستند؛ Vue و Svelte همان CSS را مستقیم مصرف می‌کنند. |
| **RTL و چندزبان** | خصوصیات منطقی همه‌جا، `--nx-dir` برای حرکت‌های جهت‌دار، ارقام محلی و واژه‌نامهٔ fa/en/ar برای متن‌های خود کامپوننت‌ها. |
| **دسترس‌پذیری** | پاپ‌اور بومی، ARIA کامل، فوکوس‌رورینگ با کلیدهای جهت‌دار، `forced-colors` و کاهش‌حرکت پشتیبانی‌شده. |

## نصب و استفادهٔ سریع

### Livewire / Blade (Laravel)

```bash
composer require nabuxai/nabuxui
```

```blade
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    {!! \NabuXUI\NabuXUI::head() !!}   {{-- تم بدون چشمک + استایل + اسکریپت‌ها --}}
</head>
<body>
    <x-nx::button variant="primary" icon="zap">شروع کنید</x-nx::button>
    <x-nx::theme-switch />
</body>
</html>
```

دایرکتیوهای رفتاری (`x-nx-reveal`، `x-nx-magnetic`، `x-nx-tilt`، …) و جادوهای
`$nxToast` / `$nxTheme` خودکار نصب می‌شوند.

### React / Next.js / Inertia

```bash
pnpm add @nabuxai/ui-react
```

```tsx
import '@nabuxai/ui-react/css';
import { NabuXUIProvider, Button, ThemeSwitch, Toaster } from '@nabuxai/ui-react';

export default function Layout({ children }) {
  return (
    <NabuXUIProvider locale="fa">
      {children}
      <Toaster />
    </NabuXUIProvider>
  );
}
```

برای Inertia همین پکیج است؛ فقط `linkComponent={Link}` را به Provider بدهید تا
لینک‌های داخلی از روتر Inertia بگذرند.

### Vue و Svelte

wrapper رسمی ندارند؛ مسیر پشتیبانی‌شده، مصرف مستقیم CSS و رفتارهای هسته است:

```bash
pnpm add @nabuxai/ui-core
```

```css
@import "@nabuxai/ui-core/nabuxui.css";
```

markup با کلاس‌های `nx-*` (خروجی Blade را الگو بگیرید) و رفتارها با
`import { reveal, indicator, theme } from '@nabuxai/ui-core'` در `onMounted` /
`useEffect` خودتان وصل می‌شوند. نمونه‌های کامل SFC در [docs/FRAMEWORKS.md](docs/FRAMEWORKS.md).

## تم روشن / تیره

کامپوننت‌های تم: `ThemeToggle` (دو حالته) و `ThemeSwitch` (روشن / سیستم / تیره).
هر دو روی مخزن مشترک هسته می‌نشینند:

- کلید ذخیره: `localStorage['nabu.theme']` (`light` | `dark` | حذف = سیستم)
- سه‌گانهٔ هم‌زمان: `data-theme="dark"` روی `<html>` + کلاس `dark` + `color-scheme`
- رویداد همگام‌سازی بین تب‌ها و اجزا: `nx-theme-change`
- بدون چشمک: `themeScript` را درون‌خطی در `<head>` بگذارید (Livewire: `NabuXUI::head()` خودش می‌گذارد؛ React: `import { themeScript } from '@nabuxai/ui-react'`)

## راست‌به‌چپ

`<html dir="rtl">` کافی است؛ همه‌چیز منطقی است (`inset-inline-start`،
`margin-inline-end`…). حرکت‌های جهت‌دار در `--nx-dir` ضرب می‌شوند و آیکون‌های
جهت‌دار خودکار آینه می‌شوند. ارقام با `Intl` محلی‌سازی می‌شوند (فارسی: ۰۱۲۳…).

## کاتالوگ (گزیده)

**کامپوننت‌های پایه:** Button (و انواع متحرک: blob/fill/flip/border/glass/pulse)،
Input/Field، Checkbox، Switch، Tabs، Segmented، Accordion، Dialog/Drawer، Tooltip،
Popover، Toast، Avatar، Badge، Kbd، Card، Chartها (خط/میله/دونات/gauge/heatmap)،
Header + MegaMenu، پالت فرمان، Pagination و…

**بلوک‌های ترکیبی (morphin-style):** FoldMenu، DockPanels، MorphMenu، MorphTabs،
StackMenu، AudioRoom، ActivityDropdown، MemberSelector، RegistrationCard،
VoiceRecorder، StackedAccordion و بلوک‌های تازه:

| بلوک | React | Blade | یک خط |
|---|---|---|---|
| انتخاب شبکه | `MultiChainSelector` | `<x-nx::chain-selector>` | گلایف تریگر به شبکهٔ انتخابی مورف می‌شود |
| منوی زبان | `LanguageMenu` | `<x-nx::language-menu>` | کد زبان در تریگر می‌غلتد (wedoflow) |
| کلید پوسته | `ThemeSwitch` | `<x-nx::theme-switch>` | روشن/سیستم/تیره با دستهٔ فنری (motionin) |
| نوار اطلاعیه | `PromoBar` | `<x-nx::promo-bar>` | بنر تخفیف تاشو با یادآوری جلسه (wedoflow) |
| فیلتر چیپی | `ChipFilter` | `<x-nx::chip-filter>` | قرص‌های فیلتر با دستهٔ فنری و شمارش (landdding) |
| نوار آمار | `StatStrip` | `<x-nx::stat-strip>` | شمارش بالاروندهٔ ارقام هنگام دیده‌شدن (landdding) |
| انتخاب‌گر مرتب‌سازی | `SortPill` | `<x-nx::sort-pill>` | برچسب تریگر روی انتخاب می‌غلتد (motionin) |
| پس‌زمینهٔ آرورا | `Backdrop variant="aurora"` | `<x-nx::backdrop-aurora>` | پرده‌های گرادیانی شناور (motionsites) |
| میدان ستاره | `Backdrop variant="starfield"` | `<x-nx::backdrop-starfield>` | پارالاکس سه‌لایهٔ CSS-خالص (motionsites) |
| مش گرادیان | `Backdrop variant="mesh"` | `<x-nx::backdrop-mesh>` | لکه‌های رنگی روان با blend (motionsites) |
| دایتر موج‌دار | `Backdrop variant="dither"` | `<x-nx::backdrop-dither>` | بافت نقطه‌ای موج‌دار (motionsites) |

فهرست کامل با جدول «React / Blade / نیاز به JS» در [docs/FRAMEWORKS.md](docs/FRAMEWORKS.md).

## توسعهٔ خود مخزن

```bash
pnpm install
pnpm build          # بیلد هر سه پکیج (core / react / livewire dist)
pnpm typecheck      # tsc هر پکیج
pnpm test           # تست‌های core و livewire
pnpm showcase       # نمایشگاه React روی 5199
```

- **ساخت بلوک تازه:** قواعد لایه‌ها، فنرها و RTL در [docs/BLOCKS.md](docs/BLOCKS.md)
- **معماری یک‌هسته-پنج‌حالت + کاتالوگ کامل:** [docs/FRAMEWORKS.md](docs/FRAMEWORKS.md)
- **دموی پنل مدیریت (دو طعم):** [docs/ADMIN.md](docs/ADMIN.md)
- **استقرار پلی‌گراوند با Coolify:** `docker-compose.yml` ریشه (پورت‌ها با
  `APP_PORT`/`VITE_PORT` قابل تغییرند)

## ساختار مخزن

```
packages/core      توکن‌ها، CSS لایه‌ای، رفتارهای JS، آیکون‌ها، i18n
packages/react     @nabuxai/ui-react — کامپوننت‌های React
packages/livewire  nabuxai/nabuxui — کامپوننت‌های Blade + پلاگین Alpine
apps/showcase      نمایشگاه React (دموی همه‌چیز + سوییچر فریم‌ورک نمونه‌کد)
apps/admin         پنل مدیریت دموی React
playground         پلی‌گراوند Livewire (دموی بلوک‌ها + پنل مدیریت + RTL)
docs               BLOCKS / FRAMEWORKS / ADMIN
```

---

ساخته‌شده با ❤️ توسط [NabuAi](https://github.com/NabuxAi) — الهام‌های بصری از
modulify.ai، wedoflow.com، landdding.com، motionin.design و motionsites.ai
(رفتارها از صفر بازنویسی شده‌اند، نه کپی).
