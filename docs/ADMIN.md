# پنل مدیریت نابو — دو طعم از یک پوسته

پنل مدیریتِ نمایشی نابو **دو بار** پیاده شده است: یک‌بار با Livewire/Blade داخل
پلی‌گراوند (`playground/`) و یک‌بار با React خالص در `apps/admin/`. هر دو روی
همان هسته‌اند — همان توکن‌ها، همان کلاس‌های `nx-admin-*`، همان حس حرکتی — و
هر دو با همان قطعات ساخته شده‌اند که از بسته‌ها مصرف می‌شوند، نه کپیِ جدا.

این سند می‌گوید هر طعم چطور بالا می‌آید، چه صفحه‌هایی دارد، چطور دوزبانه و
دو‌تم می‌شود؛ در پایان هم قراردادهایی که هر تغییرِ بعدی باید نگه دارد. برای
قواعد ساخت بلوک به [docs/BLOCKS.md](./BLOCKS.md) و برای معماری «یک هسته، پنج
حالت» به [docs/FRAMEWORKS.md](./FRAMEWORKS.md).

## یک پوسته، دو طعم

| طعم | کجا | اجرا | نشانی‌ها |
|---|---|---|---|
| **Livewire / Blade** | پلی‌گراوند (`playground/app/Livewire/Admin/*`) | `php artisan serve` | `/admin`، `/login`، `/register`، `/forgot-password` |
| **React** | اپ مستقل (`apps/admin`) | `pnpm --filter admin dev` | `http://localhost:5174` با روتر هش (`#/users`…) |

هر دو طعم یک چیدمان دارند: سایدبار گروهی با نشانِ فنری زیر آیتم فعال، تاپ‌بار
(جست‌وجو → پالت فرمان ⌘K، سوییچ تم، منوی زبان fa/en، زنگ فعالیت، منوی کاربر
با خروج) و ناحیهٔ محتوا که هر صفحه خودش می‌پراند. پوستهٔ مشترک، بلوک
`admin-shell` است (`packages/react/src/blocks/admin-shell.tsx` و
`packages/livewire/resources/views/components/admin-shell.blade.php`).

## راه‌اندازی

پیش‌نیازها: PHP ≥ ۸٫۳ (طبق `playground/composer.json`) — با افزونهٔ **intl**
برای ماه جلالی؛ در نبود intl، `Panel::monthLabel` به Carbon/گریگوری برمی‌گردد —
به‌علاوهٔ Node و pnpm (این پروژه با Node 26 و pnpm 8.7 ساخته و تست شده) و
`composer install` در `playground/` کنار `pnpm install` ریشه.

### طعم Livewire (پلی‌گراوند)

```bash
cd playground
composer install
php artisan serve          # پیش‌فرض http://127.0.0.1:8000
php artisan serve --port=8765   # در این پروژه سرور dev معمولاً همین‌طور بالا می‌آید
```

پورت ۸۷۶۵ چیزی جز `--port` نیست — هیچ تنظیم دیگری در پروژه آن را عوض نمی‌کند؛
هر پورت دیگری هم همان رفتار را دارد.

روت‌ها در `playground/routes/web.php:25` — هر صفحهٔ سایدبار یک کامپوننت
Livewire با نام روت `admin.*`:

| مسیر | نام روت | کامپوننت |
|---|---|---|
| `/admin` | `admin.dashboard` | `App\Livewire\Admin\Dashboard` |
| `/admin/analytics` | `admin.analytics` | `…\Analytics` |
| `/admin/users` | `admin.users` | `…\Users` |
| `/admin/kanban` | `admin.kanban` | `…\Kanban` |
| `/admin/calendar` | `admin.calendar` | `…\Calendar` |
| `/admin/chat` | `admin.chat` | `…\Chat` |
| `/admin/invoice` | `admin.invoice` | `…\Invoice` |
| `/admin/profile` | `admin.profile` | `…\Profile` |
| `/admin/settings` | `admin.settings` | `…\Settings` |
| `/login` | `login` | `App\Livewire\Auth\Gate` (mode=login) |
| `/register` | `register` | `…\Gate` (mode=register) |
| `/forgot-password` | `password.request` | `…\Gate` (mode=forgot) |

احراز هویتِ این دمو **UI خالص است و هیچ محدودیتی ایجاد نمی‌کند**: هیچ گاردی روی
`/admin` نیست (عمداً، تا بررسی‌های curl همیشه ۲۰۰ بمانند) و همهٔ صفحه‌های پنل
بدون ورود هم باز می‌شوند. «ورود» فقط `session(['admin_auth' => true])` می‌گذارد
و به داشبورد می‌رود؛ تنها اثرش toastها و هشدارِ «به پنل بروید» روی `/login` است
(`livewire/auth/gate.blade.php:20`). «خروج» همان کلید را فراموش می‌کند و
برمی‌گردد به `/login` (`Gate.php:34` و `Concerns/AdminPanel.php:24`).

**اعتبارنامه لازم نیست:** سرور اصلاً به ایمیل/رمز نگاه نمی‌کند — هر مقداری در
فیلدها بنویسید و دکمه را بزنید (تست پردازش هم با یک ایمیل/رمز ساختگی کار
می‌کند: `tests/Feature/AdminPanelTest.php:106`). فقط اعتبارسنجی سمت مرورگر
(فیلدهای required فرم) فعال است. برای پنل واقعی، همین‌جا `auth` middleware و
گارد واقعی اضافه کنید.

### طعم React (apps/admin)

```bash
pnpm install                    # یک‌بار در ریشهٔ مونوریپو
pnpm --filter admin dev         # http://localhost:5174 (پورت ثابت)
pnpm --filter admin run build   # خروجی در apps/admin/dist
```

- روتر، هشی و بی‌وابستگی است (`apps/admin/src/router.ts`): `#/dash`،
  `#/users`… و سه روت احراز `#/login`، `#/register`، `#/forgot` که بیرون از
  پوستهٔ پنل رندر می‌شوند (`.adm-auth` در `apps/admin/src/admin.css` وسط‌چینش
  می‌کند).
- سرور توسعه سورس پکیج‌ها را مستقیم می‌خواند (aliasهای `vite.config.ts`)، پس
  ویرایش هسته بدون rebuild دیده می‌شود؛ `build` مثل هر اپ واقعی از خروجی
  ساخته‌شدهٔ بسته‌ها مصرف می‌کند.

## فهرست صفحه‌ها

هر دو طعم ۹ صفحهٔ پنل + ۳ نمای احراز دارند. ستون «شناسه» در Livewire مقدار
`active` است و در React شناسهٔ روت/رجیستری (دو استثنا: `dashboard`↔`dash` و
`invoice`↔`invoices`).

| صفحه | شناسه | Livewire | React | چه چیزی نشان می‌دهد |
|---|---|---|---|---|
| داشبورد | `dashboard` / `dash` | `/admin` | `#/dash` | هیرو روی پس‌زمینهٔ aurora، آمار، نمودار متریک، ماتریس نقطه‌ای/هیت‌مپ، کارت مصرف، جریان فعالیت |
| تحلیل‌ها | `analytics` | `/admin/analytics` | `#/analytics` | کارت آنالیتیکس، نمودار متریک سه‌سنجه، کارت مصرف، جدول مقایسهٔ پلن‌ها |
| کاربران | `users` | `/admin/users` | `#/users` | جدول/کارت کاربران با جست‌وجو و فیلتر، آواتارها، دیالوگ دعوت، حالت خالی |
| کانبان | `kanban` | `/admin/kanban` | `#/kanban` | برد ستونی با درگ‌انددراپ بومی و لغزش FLIP |
| تقویم | `calendar` | `/admin/calendar` | `#/calendar` | تقویم ماه (فارسی: جلالی) با اسلاید جهت‌دار و دست‌ورزی روز/رویداد |
| گفتگو | `chat` | `/admin/chat` | `#/chat` | گفتگوی دوستونه: جست‌وجوی رشته‌ها، اکوی محلی پیام، حالت تایپ |
| فاکتور | `invoice` / `invoices` | `/admin/invoice` | `#/invoices` | سند فاکتور قابل‌چاپ با ردیف‌های reveal و ارقام رولینگ |
| پروفایل | `profile` | `/admin/profile` | `#/profile` | آواتار، تب‌ها، جریان فعالیت، انتخاب زبان و تم |
| تنظیمات | `settings` | `/admin/settings` | `#/settings` | فرم‌ها، سوییچ‌ها/چک‌باکس‌ها، دیالوگ تأیید |
| ورود | — | `/login` | `#/login` | کارت احراز، حالت login |
| ثبت‌نام | — | `/register` | `#/register` | همان کارت، حالت register |
| فراموشی گذرواژه | — | `/forgot-password` | `#/forgot` | همان کارت، حالت forgot |

## دوزبانگی و تم

هر دو طعم فارسی-اول و انگلیسی-دوم‌اند و همهٔ متن‌ها از لایهٔ زبان می‌آید؛
هیچ رشته‌ای سخت‌کد نشده و جهت صفحه (`dir`) با زبان می‌چرخد.

**Livewire:**

- زبان در سشن است (`locale`) و میدل‌ور `App\Http\Middleware\SetLocaleFromSession`
  هر درخواست (از جمله درخواست‌های Livewire) را می‌پوشاند؛ ثبتش در
  `playground/bootstrap/app.php`.
- سوییچ با منوی زبان تاپ‌بار (`wire:model.live="locale"` از تریت
  `SwitchesDemoLocale`) یا لینک سادهٔ `?lang=fa|en`.
- همهٔ واژه‌های پنل در `playground/lang/{fa,en}/admin.php` — دو فایل هم‌کلید.
- اعداد با `NabuXUI::formatNumber($n, 0, app()->getLocale())` فارسی می‌شوند و
  `App\Support\Panel::monthLabel` ماهِ جلالی می‌دهد (IntlCalendar با fallback
  به Carbon؛ `playground/app/Support/Panel.php:100`).

**React:**

- یک دیکشنری تایپ‌شدهٔ کامل `STRINGS` در `apps/admin/src/lang.tsx` — کلیدهای
  `s.app / s.nav / s.common / s.command / s.pages.<id>` — که صفحه‌ها فقط
  مصرف می‌کنند: `useStrings()` برای دیکشنری و `useTr()` برای جفت‌های یک‌بار
  مصرف.
- انتخاب زبان در `localStorage` با کلید `nabuxui.admin.lang` ذخیره می‌شود و
  `document` (lang/dir/title) در `useEffect` دنبالش می‌رود
  (`apps/admin/src/App.tsx:68`).
- داده‌های نمایشی قطعی (seeded) در `apps/admin/src/data.ts` — بدون
  `Math.random` و بدون fetch — تا اسکرین‌شات‌ها و چک‌ها تکرارپذیر بمانند.

**تم:** هر دو طعم همان مخزن تم هسته را می‌نویسند (`nabu.theme` در
localStorage؛ روشن/سیستم/تیره). تاپ‌بار `ThemeToggle` و صفحهٔ تنظیمات/پروفایل
`ThemeSwitch` را می‌گذارند. در طعم React اسکریپت تم **درون‌خطی در
`apps/admin/index.html`** است (همان snippetی که `themeScript` هسته می‌دهد) تا
اولین فریم در تم درست رنگ بگیرد؛ در طعم Livewire همان کار را `@nabuxuiHead`
می‌کند. جزئیات تم و RTL در [docs/FRAMEWORKS.md](./FRAMEWORKS.md) بخش‌های
«تم روشن و تیره» و «راست‌به‌چپ».

## قطعات پنل — کاتالوگ

هشت قطعهٔ تازه پنل، هر دو سوی React و Blade را دارند و از
`@nabuxai/ui-react` / `<x-nx::…>` مصرف می‌شوند (همان جدولِ
[FRAMEWORKS.md](./FRAMEWORKS.md)، این‌جا از نگاه پنل):

| بلوک | تگ یک‌خطی | React | Blade | JS رفتاری |
|---|---|---|---|---|
| admin-shell | پوستهٔ پنل: سایدبار گروهی با نشان فنری، تاپ‌بار، دراور موبایل | `AdminShell` + `AdminSidebar` + `AdminTopbar` | `<x-nx::admin-shell>` + `<x-nx::admin-sidebar>` + `<x-nx::admin-topbar>` | `indicator` + `place` + `lightDismiss` |
| auth-card | فرم سه‌پنهای ورود/ثبت‌نام/فراموشی؛ پن‌ها می‌لغزند و ارتفاع مورف می‌شود | `AuthCard` | `<x-nx::auth-card>` | `morphShell` |
| calendar | تقویم ماه؛ تغییر ماه با اسلاید جهت‌دار و پیمایش کیبوردی روزها | `Calendar` | `<x-nx::calendar>` | بله |
| chat | گفتگوی دوستونه با جست‌وجو، اکوی محلی پیام و حالت تایپ | `Chat` | `<x-nx::chat>` | بله |
| kanban | برد ستونی با درگ‌انددراپ بومی و لغزش FLIP | `Kanban` | `<x-nx::kanban>` | `snapshotRows` + `playRowFlip` |
| invoice | سند فاکتور قابل‌چاپ؛ ردیف‌ها reveal و جمع‌ها رول می‌شوند | `Invoice` | `<x-nx::invoice>` | `reveal` |
| timeline-feed | جریان فعالیت عمودی با اتصال گرادیانی و زمان نسبی | `TimelineFeed` | `<x-nx::timeline-feed>` | `reveal` |
| empty-state | «هنوز چیزی نیست»: بشقاب شناور، مدار خط‌چین و هالهٔ نرم | `EmptyState` | `<x-nx::empty-state>` | `reveal` |

## دموهای مستقل کامپوننت‌ها — /components

کنار پنل، پلی‌گراوند یک کاتالوگ مستقل از دموهای تک‌قطعه هم دارد
(`playground/app/Livewire/ComponentCatalog.php`) — بدون پوستهٔ پنل، روی
layout عمومی:

- **`/components`** — کاتالوگ جست‌وجوپذیر همهٔ قطعات: جست‌وجوی زندهٔ fold شده
  (حروف بزرگ/کوچک، اکسنت‌ها و هم‌ارزهای فارسی/عربی مثل ي↔ی و ك↔ک)، چیپ فیلتر
  گروه و شمارندهٔ کل. از صفحهٔ خوش‌آمد پلی‌گراوند هم لینک شده.
- **`/components/{group}/{slug}`** — یک صفحهٔ کامل برای هر قطعه: سناریوهای
  واقعی (استیج)، جدول props مهم، تکه‌کد قابل‌کپی و ناوبری قبلی/بعدیِ همان
  گروه. گروه‌های فعلی: `base`، `actions`، `cards`، `data`، `glass`، `menus`،
  `text`.
- سناریوها در پارشال `playground/resources/views/demos/components/<group>/<slug>.blade.php`
  زندگی می‌کنند؛ اگر پارشال نباشد صفحه به‌جای خطا «دمو در راه است» با مسیر
  پارشالِ موردنیاز نشان می‌دهد.
- از قطعات پنل، شش‌تا دموی مستقل دارند: `/components/menus/admin-shell` (به‌همراه
  `admin-sidebar` و `admin-topbar`)، `/components/data/kanban`،
  `/components/data/calendar`، `/components/data/timeline-feed`،
  `/components/cards/invoice` و `/components/base/empty-state`. دو قطعهٔ `chat`
  و `auth-card` دموی مستقل ندارند — دموی واقعی‌شان صفحات خود پنل است
  (`/admin/chat` و `/login`).

**افزودن دموی تازه** (بدون ثبت‌کردن چیزی):

1. فایل مانیفست گروه را بسازید/تکمیل کنید: `playground/app/Support/Demos/<Group>.php`
   — نام فایل = شناسهٔ گروه (kebab-case: `^[a-z][a-z0-9-]*$`). هر ورودی:
   `'slug' => ['title' => ['fa'=>…,'en'=>…], 'oneLiner' => …, 'js' => bool,
   'docs' => ?URL, 'icon' => ?coreIcon, 'props' => […], 'code' => ?string]`؛
   کلید رزروشدهٔ `__group` برچسب خود گروه است. کاتالوگ با glob پوشه را
   می‌خواند (`App\Support\DemoCatalog`).
2. پارشال سناریو را کنار بقیه بگذارید. داخل پارشال‌ها `wire:click="ping('…')"`
   (toast) و `wire:click="save('…')"` (toast + خواب ۹۰۰ms برای اسپینر
   `wire:loading`) آزاد است و آرایهٔ `$state` برای `wire:model`.
3. زبان همان سشن `locale` است؛ عددها را با `NabuXUI::formatNumber` بدهید تا
   فارسی شوند. ناشناخته‌ها ۴۰۴ می‌شوند.

## قراردادها برای عامل‌های بعدی

پنل کارِ چند عامل روی هم است؛ این قراردادها را نگه دارید تا لایه‌ها جدا بمانند.

**Livewire — صفحه‌نویسی:**

- هر صفحه فقط `<x-admin.page active="…" :title="…" :subtitle="…">محتوا</x-admin.page>`
  را می‌نویسد، **داخل view خودش نه layout**، تا `wire:*`های تاپ‌بار (زنگ، زبان،
  خروج) داخل ریشهٔ Livewire بمانند. layout مشترکِ HTML فقط
  `playground/resources/views/layouts/admin.blade.php` است (اسکلت + کلاس‌های `.ap-*`).
- مقدارهای `active`: `dashboard / analytics / users / kanban / calendar / chat /
  invoice / profile / settings` — همین idها در `App\Support\Panel::nav()`.
- کلاس‌های چیدمان آماده: `.ap-grid`، `.ap-duo`، `.ap-stats`، `.ap-box`،
  `.ap-row`، `.ap-hero` (تعریف در layout) و `.ap-stub` برای صفحهٔ خالی.
- زبان: فقط مصرف کنید `__('admin.<key>')` — کلیدهای fa و en کامل و هم‌کلیدند و
  دادهٔ نمونهٔ هر صفحه هم از پیش هست (`kanban_column_*`، `calendar_event_*`،
  `chat_person_*`/`chat_msg_*`، `invoice_*`، `users_sample_*`…). اگر واقعاً کلید
  تازه لازم شد، در هر دو فایل بیفزایید و در یادداشت تحویل ذکر کنید.
- زنگ فعالیت: `markAllActivityRead` سشن `admin_activity_read` را set می‌کند و
  `Panel::activity` خوانده‌شدن را از آن می‌خواند؛ خروج: `logout` → toast +
  redirect `/login` (تریت `AdminPanel`).

**React — صفحه‌نویسی:**

- `apps/admin/src/lang.tsx` را ویرایش نکنید؛ کلیدها کامل‌اند. داخل صفحه‌ها
  `useStrings()` و برای جفت‌های یک‌بار مصرف `useTr()`. (خود `App.tsx` بالای
  LangContext نشسته و `STRINGS[lang]` را مستقیم می‌خواند.)
- ناوبری با `go(id)` از `../router` یا `href(id)` برای لینک‌ها. افزودن صفحهٔ
  تازه یعنی افزودن به آرایهٔ `PANEL` در `App.tsx` به‌علاوهٔ کلید زبان — که
  فعلاً نباید اتفاق بیفتد؛ هر صفحه فقط فایل خودش را پر کند.
- داده‌های نمایشی فقط از `apps/admin/src/data.ts` (seeded)؛ `Math.random` و
  `fetch` ممنوع.
- اسکریپت تم درون‌خطی در `index.html` می‌ماند (تزریق از `main.tsx` برای فریم
  اول دیر است).

**پکیج‌ها:** سیم‌کشی exportها و نصب‌ها از قبل انجام شده و نباید دست بخورد —
`packages/react/src/index.ts` همهٔ قطعات تازه را export می‌کند،
`packages/livewire/resources/js/index.ts` هر پنج `install…Blocks` را صدا
می‌زند، و `packages/core/src/css/index.css` هشت CSS تازه را import کرده؛
distها هم ساخته‌شده‌اند. بعد از تغییر رفتار پکیج، rebuild بسته‌ها را فراموش
نکنید.

## نکته‌های ریز

- `.ap-page` پنل را به‌شکل کارت قاب‌دار نشان می‌دهد (`height=calc(100dvh - 3rem)`
  و `radius`/`border` روی admin-shell در
  `playground/resources/views/components/admin/page.blade.php:22`). پوستهٔ
  admin-shell خودش پیش‌فرض fullscreen است؛ برای نمای تمام‌صفحه همان سه prop را
  از `<x-admin.page>` بردارید.
- یک رفتار مشترکِ هر دو طعم: انتخاب آیتم سایدبار داخل دراور موبایل، دراور را
  نمی‌بندد (`popover=auto` فقط با کلیک بیرون/Escape بسته می‌شود). اگر باید
  بسته شود، اصلاح در خود کامپوننت admin-shell (React و Alpine) انجام شود نه
  در اپ‌ها.
- گارد روی `/admin` عمداً نیست (دمو). اگر روزی پنل واقعی شد، همین‌جا شروع
  کنید؛ بقیهٔ صفحات از `admin_auth` فقط برای toastهای ورود/خروج خبر دارند.

## استقرار

### پورت‌ها

| سرویس compose | چه چیزی را بالا می‌آورد | پورت | تعریف |
|---|---|---|---|
| `app` | پلی‌گراوند لاراول + طعم Livewire پنل (`/admin`…) | `${APP_PORT:-8000}` | `docker-compose.yml` |
| `vite` | سرور dev پلی‌گراوند | `${VITE_PORT:-5173}` | `docker-compose.override.yml` |
| `admin` | اپ React پنل | `${ADMIN_PORT:-5174}` | override + `server.port` در `apps/admin/vite.config.ts` |
| `admin-vue` | اپ Vue پنل | `${ADMIN_VUE_PORT:-5175}` | override + `apps/admin-vue/vite.config.ts` |
| `admin-svelte` | اپ Svelte پنل | `${ADMIN_SVELTE_PORT:-5176}` | override |

پورت‌ها عمداً یکتا و پایدارند: هر اپ در `vite.config.ts` خودش پورت را با
`strictPort` قفل می‌کند و سرویس compose هم همان شماره را با `--port`/`--strictPort`
صریح می‌گذارد — پس `pnpm --filter <app> dev` روی میز و `docker compose up`
درون کانتینر همیشه به یک شماره می‌رسند و اگر پورت اشغال باشد بدترین حالت
خروج با خطاست، نه جابه‌جایی پورت.

### اجرای محلی با داکر

`docker-compose.override.yml` فقط لوکال است (`.gitignore:16`) و سرویس‌های dev
را می‌دهد: `vite` برای پلی‌گراوند و `admin` / `admin-vue` / `admin-svelte`
برای اپ‌های پنل — همه `node:22-alpine`، pnpm از طریق corepack مطابق
`packageManager` ریشهٔ مونوریپو. این سه سرویس یک مجموعهٔ مشترک از
node_modules volumeها دارند: اولین سرویسی که بالا می‌آید `pnpm install`
فیلترشده را زیر قفل `node_modules/.install-lock` اجرا می‌کند، بقیه تا آزادشدن
قفل می‌چرخند و بعد هرکدام dev سرور خودش را می‌آورد. node_modulesهای میزبان
(darwin) دست‌نخورده می‌مانند چون هر پوشهٔ node_modules ورک‌اسپیس با volume
مخصوص خودش سایه می‌شود. دو نکته: install ممکن است `pnpm-lock.yaml` میزبان را
به‌روز کند (bind mount؛ سرویس `vite` هم با `npm install` همین را با
package-lock.json می‌کند) و تا وقتی اپی هنوز ساخته نشده، سرویسش با پیام
«No projects matched the filters» می‌ایستد.

### Coolify

نسخهٔ عمومی از همین مخزن روی Coolify (بررسی زنده، ۱ اکتبر ۲۰۲۶) این‌طور
می‌آید: اپ `nabu-x-ui:main-…` در پروژهٔ «Nabuxai.com»، مخزن
`nabuxai/NabuXUi` شاخهٔ `main`، با build pack داکر-کامپوز روی مسیر
`/docker-compose.yml` — یعنی **فقط فایل پایه**؛ چون override در git نیست و
Coolify هم صریحاً `-f docker-compose.yml` می‌زند، bind mountها و سرویس‌های dev
هرگز به سرور نمی‌رسند. دامنه‌ها روی سرویس `app` هستند:
`https://ui.nabuxai.com` و `https://www.ui.nabuxai.com`. کلیدهای
`APP_PORT`/`VITE_PORT` برای جایگزینی `${…}` در compose ست شده‌اند به‌علاوهٔ
متغیرهای لاراولی `APP_*`. سرویس `mysql` پشت profile است و در استقرار
پیش‌فرض بالا نمی‌آید.

اپ‌های React/Vue/Svelte پنل در `docker-compose.yml` سرویس پروداکشن ندارند؛
خروجی `pnpm --filter <app> build` آنها static است (`apps/<app>/dist`) و هر
میزبان static جوابش می‌دهد. روزی که قرار شد یکی از طعم‌ها تولیدی شود، سرویس
آن باید در فایل پایه (که کامیتی است و Coolify می‌بیند) تعریف شود — نه در
override.

## چک‌های تحویل

قبل از بستن هر کار روی پنل:

1. `php -l` روی همهٔ فایل‌های PHP تازه/ویرایش‌شده.
2. `php artisan route:list` — نام روت‌ها راستی‌آزمایی شود.
3. curl صفحات: `/admin` و همهٔ زیرصفحه‌ها و `/login`، `/register`،
   `/forgot-password` باید ۲۰۰ باشند؛ نشانگرهای fa (`dir="rtl"`، ارقام فارسی،
   ماه جلالی) و en (ماه‌های گریگوری، autocomplete درست) هر دو بررسی شوند.
4. `php artisan test` در پلی‌گراوند.
5. طعم React: `npx tsc -p tsconfig.json --noEmit` در `apps/admin` و
   `pnpm --filter admin run build` از ریشه.
6. بعد از rebuild بسته‌ها: grep خروجی dist برای کلاس‌ها/رفتارهای تازه
   (مثل `nx-admin` در CSS و `nxKanban` در JS).
