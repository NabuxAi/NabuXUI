# DESIGN.md — سیستم طراحی NabuXUI، از فلسفه تا آیین کار

> این سند «همه‌چیزِ» سیستم طراحی نابو است: چرا این‌طور ساخته شده، چه قراردادهایی
> حاکم است و چطور کار درست انجام می‌شود. مکمل‌هایش: [BLOCKS.md](BLOCKS.md) برای
> قرارداد ساخت بلاک، [FRAMEWORKS.md](FRAMEWORKS.md) برای پنج فریم‌ورک و
> [ADMIN.md](ADMIN.md) برای پنل دمو.

---

## ۱. فلسفه — یک هسته، هر فریم‌ورکی

NabuXUI یک سیستم طراحی واحد است که در پنج فریم‌ورک نفس می‌کشد:
**Livewire/Blade، React، Inertia، Vue و Svelte.** اصل اول این است که هیچ
فریم‌ورکی صاحبِ طراحی نیست:

| لایه | خانه | محتوا |
|---|---|---|
| **توکن‌ها و CSS** | `packages/core/src/css` | ظاهر و حرکت — همهٔ فریم‌ورک‌ها همین یک فایل را مصرف می‌کنند |
| **رفتار** | `packages/core/src/js` | DOM-agnostic: هر رفتار `(el, options) => cleanup` |
| **پوسته‌ها** | `packages/react` · `packages/vue` · `packages/svelte` · `packages/livewire` | همان DOM، همان کلاس‌های `nx-*`، فقط state binding بومی فریم‌ورک |
| **دموها** | `playground` (Livewire) و `apps/showcase` (React) | سناریوهای واقعی، نه گرید نمونه‌وار |

نتیجهٔ این جداسازی سه چیز است: یکدستی بصری تضمین‌شده (CSS یکی است)، مهاجرت
ارزان (فریم‌ورک عوض شود طراحی نه)، و تست واحد که فقط یک‌بار لازم است.

### نام‌ها

- پکیج‌ها: `@nabuxai/ui-core`، `@nabuxai/ui-react`، `@nabuxai/ui-vue`،
  `@nabuxai/ui-svelte` و `nabuxai/nabuxui` (Livewire).
- کلاس‌ها: `nx-<block>` و `nx-<block>-<part>`؛ وضعیت فقط با `data-*` و `aria-*`.
- متغیرهای خصوصی کامپوننت با `--_` شروع می‌شوند (`--_gap`، `--_speed`).

---

## ۲. زبان حرکت — فنر، نه مدت

حرکت در نابو «حس فیزیکی» است، نه انیمیشن CSS معمولی. قواعد:

1. **فنرهای استاندارد** — `--nx-spring-gentle / snappy / bouncy / soft` به‌همراه
   `--nx-spring-*-duration`. هیچ‌جا spring دستی تعریف نمی‌کنیم.
2. **ضریب حرکت** — هر جابه‌جایی در `var(--nx-motion)` ضرب می‌شود؛ با
   `prefers-reduced-motion` این ضریب صفر می‌شود و حرکت به fade ساده فرو می‌ریزد:
   `translate: 0 calc(12px * var(--nx-motion));`
3. **مقادیر دقیق جلا** (از BLOCKS.md، بدون استثنا):
   - فشار: `scale: calc(1 - 0.04 * var(--nx-motion))` روی `:active` (هرگز زیر ۰٫۹۵).
   - هیچ‌وقت `transition: all` — پراپرتی‌ها نام‌برده می‌شوند.
   - هاورِ حرکتی فقط داخل `@media (hover: hover) and (pointer: fine)`.
   - هیچ‌چیز از `scale(0)` وارد نمی‌شود: `0.95 + opacity: 0` شروع است.
   - ورود ≤ ۳۰۰ms؛ خروج نرم‌تر و تندتر (~۱۵۰ms). stagger بین ۳۰–۸۰ms
     (`--nx-stagger` = ۵۵ms) و هرگز مسدودکنندهٔ ورودی نیست.
   - کنش‌های روزمرهٔ کیبوردی (کامندپالت) اصلاً انیمیت نمی‌شوند.
   - درگ: pointer capture، rubber-band بیرون مرز، رها با فلیک (سرعت > ۰٫۱۱px/ms).
4. **حرکت هرگز تنها نشانه نیست** — هر حالت متحرک هم‌زمان رنگ/آیکن/برچسب هم عوض می‌کند.
5. **حلقه‌های بی‌پایان** خارج از دید و در reduced-motion متوقف‌اند
   (IntersectionObserver + `matchMedia`)، و شنونده‌ها در `destroy()` بسته می‌شوند.

---

## ۳. توکن‌ها — واژه‌نامهٔ بصری

همه‌چیز از `packages/core/src/css/tokens.css` می‌آید (۴۰۰+ متغیر). حروف خام
(hex) در کامپوننت‌ها ممنوع‌اند. خانواده‌ها:

| خانواده | نمونه | نقش |
|---|---|---|
| سطح‌ها | `--nx-bg`، `--nx-surface`، `-surface-2/3` | پس‌زمینه و لایه‌ها |
| متن | `--nx-text`، `-text-muted`، `-text-subtle` | سلسله‌مراتب خوانایی |
| برند | `--nx-accent`، `-accent-text/-soft/-border`، `--nx-gold*` | هویت و تأکید |
| وضعیت | `--nx-success/warning/danger/info` (+`-text`، `-soft`) | معنا |
| خط و سایه | `--nx-border(-strong)`، `--nx-shadow-xs…xl`، `-glow` | عمق لایه‌ای، نه خط‌کشی |
| شیشه | `--nx-glass`، `--nx-glass-border` | خانوادهٔ liquid-glass |
| گرادیان | `--nx-gradient-brand/text/gold/aurora` | سطح‌های ویژه |
| فضا و شعاع | `--nx-space-1…24`، `--nx-radius-xs…2xl/full` | ریتم و گردی |
| تایپ | `--nx-text-xs…display`، `--nx-font-sans/display/mono`، `--nx-tracking-*` | مقیاس حروف (tracking برای فارسی/عربی صفر) |
| نمودار | `--nx-chart-1…7` (ترتیب ثابت) | سری‌های داده |
| پالت | `--nx-lapis-*`، `--nx-violet-*`، `--nx-cyan-*`، `--nx-gold-*`، `--nx-ink-*` | مقیاس‌های رنگی |
| حرکت | `--nx-spring-*`، `--nx-dur-*`، `--nx-ease-out`، `--nx-motion`، `--nx-stagger`، `--nx-dir` | زبان حرکت |
| چیدمان منطقی | `--nx-dir` (۱ در LTR، −۱ در RTL) | جهت‌دار کردن حرکت |

**شعاع هم‌مرکز:** شعاع بیرونی = شعاع درونی + پدینگ والد. **عمق** با سایه‌های
شفاف لایه‌لایه ساخته می‌شود؛ border فقط برای ساختار است. تصاویر همیشه
`outline: 1px solid` سیاه/سفید با ۱۰٪ و `outline-offset: -1px` می‌گیرند.

---

## ۴. تم — دو آینهٔ اجباری

هر استایل باید هم روشن هم تیره را پوشش دهد، با **دو آینه** (تجربهٔ موج‌ها این
دوگانه را الزامی کرد):

```css
html[data-theme="dark"] .my-thing { … }                     /* سوییچ دستی */
@media (prefers-color-scheme: dark) {
  html:not([data-theme="light"]) .my-thing { … }             /* سیستم عامل */
}
```

سوییچ تم بدون چشمک (`theme` در هسته) و کلاس `.nx-js` نشانهٔ بوت JS است
(حالت‌های پیش از reveal فقط زیرش اعمال می‌شوند).

---

## ۵. RTL و چندزبانگی

- **فقط ویژگی‌های منطقی:** `inset-inline-start`، `margin-inline-end`،
  `padding-inline`؛ هرگز left/right جایی که منظور منطقی است.
- حرکت جهت‌دار در `--nx-dir` ضرب می‌شود تا در RTL برعکس شود.
- **متن شکستنی:** هرگز حروف فارسی/عربی را split نکنید — تقسیم فقط برای لاتین؛
  فارسی در سطح واژه. ابزار: `splitText()` هسته و `\NabuXUI\NabuXUI::split()`؛
  جهت ظرف قطعه‌ها از `textDirection()`/`NabuXUI::direction()`.
- واژه‌های خودِ کامپوننت‌ها (بستن، بارگذاری، صفحه…) از `packages/core/src/js/i18n.ts`
  می‌آیند (fa/en/ar و ۲۵ زبان ثبت‌شده در پلی‌گراوند)؛ بقیهٔ متن‌ها prop است.
- اعداد در رشته‌های فارسی فارسی‌اند (`NabuXUI::formatNumber()`، `localeDigits()`).

---

## ۶. دسترس‌پذیری — مذاکره‌ناپذیر

1. المنت بومی اول: `button`، `a`، `input`، `dialog`، `[popover]`، `details`.
   هیچ دکمهٔ div‌ای وجود ندارد.
2. هر تعاملی با کیبورد می‌رسد و حلقهٔ `:focus-visible` دیده می‌شود
   (`outline: 2px solid var(--nx-ring); outline-offset: 2px`).
3. متن تزئینی/قطعه‌قطعه‌شده: نسخهٔ واقعی در `.nx-visually-hidden`، نسخهٔ متحرک
   `aria-hidden="true"`.
4. نمودارها جدول مخفیِ همان داده را حمل می‌کنند (درس سشن‌ها: جدول مخفی با
   `position: fixed` بیرون می‌رود؛ چیدمان جدول width/contain را نادیده می‌گیرد).
5. دیالوگ‌ها فوکوس را به دام می‌اندازند، Escape می‌بندد، backdrop کلیک را می‌خورد.
6. هدف لمسی ≥ ۴۴px (هسته روی `pointer: coarse` این را تضمین می‌کند).

---

## ۷. معماری محتوا — سه دامنه

| دامنه | چیست | کجا |
|---|---|---|
| **کامپوننت‌ها** (۱۲ خانوادهٔ مرجع) | آجرهای پایه: text، button، form، card، chart، display، feedback، navigation، overlay، header، hero، pricing | `packages/*/src/components` — در چهار فریم‌ورک |
| **بلاک‌ها** (۲۰ خانواده در 0.3.0) | ترکیب‌های حرکت‌دار بالادست: heroes، sections، shaders، flows، maps، depth-3d و… | `packages/*/src/blocks` + `core/src/css|js/blocks` |
| **دموها** (۳۷۴ دمو / ۴۱ گروه) | سناریوهای واقعی تک‌صفحه‌ای با props و تکه‌کد پنج‌فریم‌ورکی | `playground/app/Support/Demos/*.php` + `resources/views/demos/components/**` |

### دو محور مرور دموها (`/components`)

1. **گروه** — محور فنی (۴۱ گروه، از base تا depth-3d).
2. **کاربرد** («دارم می‌سازم») — محور انسانی: ۱۲ سبد واقعی
   (`app/Support/UseCases.php`: ورود، فرم، داشبورد، جدول، گفت‌وگو، فروشگاه،
   بازخورد، ناوبری، فایل، تقویم، حالت‌ها، لندینگ) که گروه‌ها را به کارِ کاربر
   نگاشت می‌کنند؛ مستقل از محور گروه فیلتر می‌کند.

### استودیوی نابوگیت (`/ai`)

پرامپت آزاد («چه می‌سازید؟») → درخواست به **نابوگیت** (درگاه LLM محصول؛
`NABUGATE_ENDPOINT/KEY/MODEL`) → پیش‌نویس زنده در iframe ایزوله. بدون درگاه،
موتور قصد محلی (`App\Livewire\AiStudio`) پرامپت را به کاربرد نگاشت می‌کند و
یکی از ۱۲ دستور پخت زنده (`resources/views/ai/recipes/*`) — ساخته‌شده از همان
کامپوننت‌های واقعی — به‌همراه ۴ دموی هم‌کاربرد سرو می‌کند. این «دمو ساختن با
هوش مصنوعی» است: خروجی قابل‌دیدن، نه متن محض.

---

## ۸. آیین کار — چطور درست انجام شود

### نوشتن

- الگوها را قبل از نوشتن بخوانید: یک بلاک موجود هم‌خانواده + `BLOCKS.md`.
- CSS در `@layer nx.components`؛ فقط توکن؛ هر دو تم؛ کلاس‌های `nx-*`؛
  متغیر خصوصی `--_`؛ وضعیت با `data-*`.
- رفتار هسته `(el, options) => cleanup` — شنونده/RAF/observer در destroy بسته.
- هرگز: `{!! json_encode !!}` خام داخل `x-data` (از
  `{{ json_encode(..., JSON_UNESCAPED_UNICODE) }}`)؛ `$cleanup` خیالی (ندارد —
  init/destroy)؛ `{{` داخل `<style>`؛ `data-nx-reveal` روی صفحاتی که مورف
  می‌شوند (مورف ویژگی را می‌کند و کارت برای همیشه غیب می‌شود)؛ `<style>` به‌عنوان
  اولین عنصر قالب Livewire (`wire:id` به آن می‌خورد و ریشهٔ morph می‌شود —
  به‌روزرسانی‌ها بی‌صدا به style می‌روند؛ style داخل ریشهٔ div).
- در demo partial ها: بدون `position:fixed`، بدون `wire:`، دوزبانه با `$say`،
  اعداد فارسی در fa، مثال‌ها جهانی (سیاست: صفر ارجاع ایران/تهران).

### راستی‌آزمایی — دروازه‌ها به ترتیب ارزان به گران

1. `php -l` روی هر مانیفست/مسیر PHP تازه.
2. شمارش کاتالوگ: `php playground/artisan tinker --execute='echo count(App\Support\DemoCatalog::flat())." ".count(App\Support\DemoCatalog::groups());'`
   (مقایسه با عدد مورد انتظار — دروازهٔ مطلق، نه محاسبهٔ پویا).
3. `curl` تک‌تک صفحات تازه → ۲۰۰ (با حلقهٔ اصلاح).
4. گrep بدون-ایران روی فایل‌های تازه.
5. **پویش کنسول مرورگر روی «همهٔ» صفحات تازه** (pageerror + console.error،
   ~۱٫۲s هر صفحه) — curl به خرابی JS کور است و نمونه‌برداری هم گپ می‌گذارد.
6. تعامل واقعی بعد از `scrollIntoViewIfNeeded` (کلیک بیرون viewport بی‌صدا به
   هیچ می‌خورد)؛ شیدرها با pixel-diff دو اسکرین‌شات سنجیده می‌شوند؛ عکسِ بخش
   متحرک فقط بعد از settle کامل (شات میان‌انیمیشن مدرک نیست).
7. سرریز موبایل ۳۷۵px: `scrollWidth` در برابر `clientWidth` — صفحه و هر بخش.
8. قبل از پروب هر اپ، طرح روتینگش را چک کنید (admin-svelte هش‌بنیاد است —
   `/components` بدون `#` ساکت دشبورد را نشان می‌دهد).

### زنجیرهٔ ساخت

```bash
pnpm build        # همهٔ پکیج‌ها
pnpm typecheck    # tsc / vue-tsc / svelte-check
pnpm test         # vitest
pnpm showcase:build
```

توجه: showcase باندل CSS را از dist رِیکت می‌گیرد — بعد از هر تغییر CSS هسته
ترتیب بازسازی core → react → showcase است. TypeScript 7 فقط باینری Go است و
JS API ندارد: پکیج‌های vue/svelte (به‌خاطر vue-tsc/svelte-check) عمداً روی
`typescript@^5.9` پین‌اند؛ core/react روی TS7.

---

## ۹. نقشهٔ ریپو

```
packages/
  core/      → توکن‌ها + CSS لایه‌ای + رفتارهای JS (زبان مشترک)
  react/     → ۱۲ کامپوننت مرجع + ۲۰ خانوادهٔ بلاک (useBehavior روی هسته)
  vue/       → همان ۱۲ کامپوننت، SFC + کامپوزبل useNxBehavior
  svelte/    → همان ۱۲ کامپوننت، Svelte 5 runes + اکشن‌های nxB
  livewire/  → کامپوننت‌های anonymous Blade + Alpine data
  inertia/ filament/ → پل‌های اختصاصی
apps/
  showcase/      → گالری React (سکتورها + بلاک‌ها)
  admin*/        → پنل دمو در چهار طعم (react/vue/svelte + livewire در پلی‌گراوند)
playground/      → /components (کاتالوگ ۳۷۴ دمو، دو محور)، /ai (استودیوی نابوگیت)،
                   /blocks، /admin، /filament — سرو روی :8765
docs/            → BLOCKS.md · FRAMEWORKS.md · ADMIN.md · DESIGN.md (همین)
```

---

## ۱۰. خلاصهٔ یک‌نگاهی

> **توکن بده، نه hex. فنر بده، نه مدت. دو تم، دو جهت، همیشه. المنت بومی،
> focus-visible، جدول مخفی. cleanup در destroy. curl کافی نیست — کنسول را
> پویش کن. عکسِ وسط انیمیشن مدرک نیست. و هر چیزی که می‌سازی، دموی واقعی‌اش را
> هم بساز.**
