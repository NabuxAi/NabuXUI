<?php

/**
 * Demo manifest of the "glass" group — the liquid-glass family. The file name
 * is the group id, every key below is a demo slug and its scenarios live at
 * resources/views/demos/components/glass/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'شیشه', 'en' => 'Glass'],

    'glass-panel' => [
        'title' => ['fa' => 'پنل شیشه‌ای', 'en' => 'Glass panel'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'سطح شیشه با tint و rim نورگیر؛ refract پشتوانه را در لبه می‌خمَد و follow-light درخشش را دنبال موس می‌برد.',
            'en' => 'A glass surface with a tint and a light-catching rim; refract bends the backdrop at the edge and follow-light carries the gleam after the pointer.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'preset', 'type' => 'string', 'default' => 'regular', 'note' => [
                'fa' => 'ضخامت و تیرگی شیشه: regular · clear · frost · thick.',
                'en' => 'The glass’ body: regular · clear · frost · thick.',
            ]],
            ['name' => 'refract', 'type' => 'bool', 'default' => 'true', 'note' => [
                'fa' => 'خم‌کردن پشتوانه در لبه (رفتار glassPane؛ در Chromium دقیق‌تر).',
                'en' => 'Bends the backdrop at the rim (the glassPane behaviour; finest in Chromium).',
            ]],
            ['name' => 'iridescent', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'رنگین‌کمیِ لبه در جهت نور.',
                'en' => 'A rainbow shimmer along the rim.',
            ]],
            ['name' => 'follow-light', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'درخشش لبه دنبال اشاره‌گر می‌رود.',
                'en' => 'The rim’s gleam follows the pointer.',
            ]],
            ['name' => 'as', 'type' => 'string', 'default' => 'div', 'note' => [
                'fa' => 'تگ ریشه (مثلاً article یا section).',
                'en' => 'The root tag (e.g. article or section).',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::glass-panel preset="frost" iridescent follow-light class="player-card">
            …محتوای کارت…
        </x-nx::glass-panel>
        BLADE,
    ],

    'glass-button' => [
        'title' => ['fa' => 'دکمهٔ شیشه‌ای', 'en' => 'Glass button'],
        'icon' => 'zap',
        'oneLiner' => [
            'fa' => 'دکمهٔ پنل شیشه‌ای با فشرده‌شدن فنری؛ shimmer هنگام فشردن یک‌بار نور را دور rim می‌گرداند.',
            'en' => 'A pressable glass pane with springy press feedback; shimmer sends the light once around the rim when pressed.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'icon / icon-end', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'آیکون ابتدا یا انتهای برچسب؛ بدون متن، دکمهٔ فقط-آیکونی می‌شود (برچسب را aria-label بدهید).',
                'en' => 'An icon leading or trailing the label; with no text the button is icon-only (give it an aria-label).',
            ]],
            ['name' => 'size / preset', 'type' => 'string', 'default' => 'md · regular', 'note' => [
                'fa' => 'اندازه (sm · md · lg) و جنس شیشه (regular · clear · frost · thick).',
                'en' => 'Size (sm · md · lg) and the glass body (regular · clear · frost · thick).',
            ]],
            ['name' => 'shimmer', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'با هر فشار، نور یک دور کامل دور rim می‌رود.',
                'en' => 'Every press sends the light around the rim once.',
            ]],
            ['name' => 'iridescent / static', 'type' => 'bool', 'default' => 'false', 'note' => [
                'fa' => 'iridescent: لبهٔ رنگین‌کمون؛ static: کوچک‌شدن هنگام فشار خاموش.',
                'en' => 'iridescent: rainbow rim; static: no press scale.',
            ]],
            ['name' => 'href', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'با href دکمه به لینک تبدیل می‌شود.',
                'en' => 'With href the button becomes a link.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::glass-button icon="play" shimmer wire:click="play">پخش</x-nx::glass-button>
        <x-nx::glass-button icon="heart" iridescent>ذخیره</x-nx::glass-button>
        <x-nx::glass-button icon="settings" preset="thick" aria-label="تنظیمات" />
        BLADE,
    ],

    'glass-segmented' => [
        'title' => ['fa' => 'سگمنت شیشه‌ای', 'en' => 'Glass segmented'],
        'icon' => 'check',
        'oneLiner' => [
            'fa' => 'سگمنت انتخاب‌تکی که انتخابش یک عدسی قابل‌درگ است؛ روی رادیوهای بومی سوار است و با wire:model سینک می‌شود.',
            'en' => 'A single-choice segmented control whose selection is a lens you can drag; it rides native radios and syncs through wire:model.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'options', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'کلید → برچسب، یا آرایه‌ای با کلیدهای «label»، «icon» و «disabled».',
                'en' => 'Key → a label, or an array with «label», «icon» and «disabled».',
            ]],
            ['name' => 'value / wire:model', 'type' => 'string', 'default' => 'کلید اول', 'note' => [
                'fa' => 'گزینهٔ فعال؛ عدسی با فنر یا درگ به آن می‌رود.',
                'en' => 'The active option; the lens springs or is dragged to it.',
            ]],
            ['name' => 'preset / label', 'type' => 'string', 'default' => 'regular · null', 'note' => [
                'fa' => 'جنس شیشه و برچسب دسترس‌پذیری گروه.',
                'en' => 'The glass body and the group’s accessible label.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::glass-segmented label="دوره" :value="$state['period'] ?? 'week'" wire:model.live="state.period"
            :options="['day' => 'روز', 'week' => 'هفته', 'month' => 'ماه']" />
        BLADE,
    ],

    'glass-dock' => [
        'title' => ['fa' => 'داک شیشه‌ای', 'en' => 'Glass dock'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'داک شیشه‌ای که ذره‌بینش به آیتم زیر موس یا فوکوس می‌لغزد و راهنمای نام کنارش می‌آید.',
            'en' => 'A glass dock whose magnifier glides to the item under the pointer or focus, with the name tooltip alongside.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر آیتم: label، icon، tone (lapis · violet · cyan · gold · ink · rose · green)، href یا click (اکشن Livewire) و current.',
                'en' => 'Each item: label, icon, tone (lapis · violet · cyan · gold · ink · rose · green), href or click (a Livewire action), and current.',
            ]],
            ['name' => 'label / preset', 'type' => 'string', 'default' => 'null · regular', 'note' => [
                'fa' => 'برچسب دسترس‌پذیری و جنس شیشه.',
                'en' => 'The accessible label and the glass body.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::glass-dock label="برنامه‌ها" :items="[
            ['label' => 'فایل‌ها', 'icon' => 'folder', 'tone' => 'lapis', 'current' => true],
            ['label' => 'ایمیل', 'icon' => 'mail', 'tone' => 'cyan', 'click' => 'ping(\'ایمیل باز شد\')'],
            ['label' => 'موسیقی', 'icon' => 'music', 'tone' => 'rose'],
        ]" />
        BLADE,
    ],

    'glass-tab-bar' => [
        'title' => ['fa' => 'تب‌بار شیشه‌ای', 'en' => 'Glass tab bar'],
        'icon' => 'home',
        'oneLiner' => [
            'fa' => 'تب‌بار شیشه‌ای شناور؛ تب فعال زیر عدسی می‌نشیند و با اسکرولِ محتوا خودش را جمع می‌کند.',
            'en' => 'A floating glass tab bar; the active tab sits under the lens and it shrinks itself as the content scrolls.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'items', 'type' => 'array', 'default' => '[]', 'note' => [
                'fa' => 'هر تب: value، label و icon؛ با href لینک می‌شود و بدون آن دکمه‌ای به value (و wire:model) بسته است.',
                'en' => 'Each tab: value, label and icon; with href it links, without it the button binds to value (and wire:model).',
            ]],
            ['name' => 'value / wire:model', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'تب فعال.',
                'en' => 'The active tab.',
            ]],
            ['name' => 'minimize-on-scroll', 'type' => 'bool · selector', 'default' => 'false', 'note' => [
                'fa' => 'true یعنی پنجره؛ یک سلکتور CSS یعنی همان ظرفِ اسکرول‌شونده.',
                'en' => 'true watches the window; a CSS selector watches that scrolling container.',
            ]],
            ['name' => 'compact / preset', 'type' => 'bool · string', 'default' => 'false · regular', 'note' => [
                'fa' => 'شروعِ جمع‌شده و جنس شیشه.',
                'en' => 'Start shrunk, and the glass body.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::glass-tab-bar label="اصلی" :value="$state['tab'] ?? 'home'" wire:model.live="state.tab"
            minimize-on-scroll=".feed-scroll" :items="[
                ['value' => 'home', 'label' => 'خانه', 'icon' => 'home'],
                ['value' => 'saved', 'label' => 'ذخیره‌ها', 'icon' => 'heart'],
            ]" />
        BLADE,
    ],

    'glass-switch' => [
        'title' => ['fa' => 'سوییچ شیشه‌ای', 'en' => 'Glass switch'],
        'icon' => 'settings',
        'oneLiner' => [
            'fa' => 'سوییچ روی checkbox بومی؛ knob هنگام گرفتن به شیشه بدل می‌شود و بقیهٔ ویژگی‌ها (wire:model، disabled) مستقیم روی ورودی می‌نشینند.',
            'en' => 'A switch on a native checkbox; the knob clears to glass while held and every other attribute (wire:model, disabled) lands on the input itself.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'برچسب متنِ کنار سوییچ (داخل label، پس کلیک روی متن هم کار می‌کند).',
                'en' => 'The text beside the switch (inside the label, so clicking the text works too).',
            ]],
            ['name' => 'wire:model / native', 'type' => '—', 'default' => '—', 'note' => [
                'fa' => 'همهٔ ویژگی‌های بومی checkbox پذیرفته می‌شود: checked، disabled، name و wire:model.',
                'en' => 'Every native checkbox attribute is accepted: checked, disabled, name and wire:model.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::glass-switch label="خبرنامهٔ هفتگی" wire:model.live="state.newsletter" />
        BLADE,
    ],

    'glass-slider' => [
        'title' => ['fa' => 'اسلایدر شیشه‌ای', 'en' => 'Glass slider'],
        'icon' => 'sliders',
        'oneLiner' => [
            'fa' => 'رِنج بومی که thumbش هنگام کشیدن ذره‌بین می‌شود؛ تیک‌ها زیر پر می‌شوند و عدد کنارش زنده می‌غلتد.',
            'en' => 'A native range whose thumb becomes a magnifier while dragged; the ticks fill under it and the figure beside it rolls live.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'value / wire:model', 'type' => 'number', 'default' => 'وسط بازه', 'note' => [
                'fa' => 'مقدار فعلی؛ با wire:model به سرور بسته می‌شود.',
                'en' => 'The current value; binds to the server through wire:model.',
            ]],
            ['name' => 'min / max / step', 'type' => 'number', 'default' => '0 · 100 · 1', 'note' => [
                'fa' => 'بازه و گام — همان ورودی range بومی.',
                'en' => 'The range and step — the native range input as is.',
            ]],
            ['name' => 'ticks / show-value / suffix', 'type' => 'int · bool · string', 'default' => "11 · true · ''", 'note' => [
                'fa' => 'شمار تیک‌ها، نمایش عدد و پسوند آن (مثل ٪).',
                'en' => 'Tick count, whether the figure shows, and its suffix (e.g. %).',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => 'null', 'note' => [
                'fa' => 'برچسب دسترس‌پذیری ورودی.',
                'en' => 'The input’s accessible label.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::glass-slider label="بلندی صدا" :value="$state['volume'] ?? 40" wire:model.live="state.volume" suffix="٪" :ticks="9" />
        BLADE,
    ],

    'reading-glass' => [
        'title' => ['fa' => 'عدسی مطالعه', 'en' => 'Reading glass'],
        'icon' => 'search',
        'oneLiner' => [
            'fa' => 'عدسی روی محتوای زنده: بکشیدش، پرتش کنید یا با فلش‌های کیبورد حرکتش دهید — متنِ زیرش انتخاب‌پذیر و دکمه‌ها کلیک‌پذیر می‌مانند.',
            'en' => 'A lens over live content: drag it, throw it or move it with the arrow keys — the text under it stays selectable and the buttons clickable.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'size / shape', 'type' => 'int · string', 'default' => '144 · circle', 'note' => [
                'fa' => 'قطر عدسی به پیکسل و شکلش: circle · pill · rounded.',
                'en' => 'The lens diameter in px and its shape: circle · pill · rounded.',
            ]],
            ['name' => 'x / y', 'type' => 'float', 'default' => '0.5 · 0.5', 'note' => [
                'fa' => 'جای اولیهٔ عدسی به نسبت ظرف (۰ تا ۱).',
                'en' => 'The lens’ starting spot as a fraction of the container (0 to 1).',
            ]],
            ['name' => 'label', 'type' => 'string', 'default' => '«ذره‌بین»', 'note' => [
                'fa' => 'برچسب دسترس‌پذیری دکمهٔ عدسی.',
                'en' => 'The lens button’s accessible label.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::reading-glass :x="0.5" :y="0.35" size="160" shape="pill">
            …متن درشت یا جزئیات تصویر…
        </x-nx::reading-glass>
        BLADE,
    ],

    'liquid-ripple' => [
        'title' => ['fa' => 'موج مایع', 'en' => 'Liquid ripple'],
        'icon' => 'image',
        'oneLiner' => [
            'fa' => 'هر فشار، موجی در محتوای زندهٔ داخل می‌فرستد؛ قدرت و زمان موج قابل تنظیم است و محتوا مثل همیشه کار می‌کند.',
            'en' => 'Every press sends a ripple through the live content inside; strength and timing are tunable and the content keeps working as ever.',
        ],
        'js' => true,
        'docs' => null,
        'props' => [
            ['name' => 'strength', 'type' => 'number', 'default' => '18', 'note' => [
                'fa' => 'شدت موج (جابه‌جایی پیکسلی محتوا).',
                'en' => 'The ripple’s force (the content’s pixel travel).',
            ]],
            ['name' => 'duration', 'type' => 'number', 'default' => '1100', 'note' => [
                'fa' => 'زمان نشستن موج به میلی‌ثانیه.',
                'en' => 'How long the ripple settles, in milliseconds.',
            ]],
        ],
        'code' => <<<'BLADE'
        <x-nx::liquid-ripple :strength="22">
            …هر محتوای زنده — گالری، فهرست، فرم…
        </x-nx::liquid-ripple>
        BLADE,
    ],

    'glass-input' => [
        'title' => ['fa' => 'ورودی شیشه‌ای', 'en' => 'Glass input'],
        'icon' => 'mail',
        'oneLiner' => [
            'fa' => 'فیلد متنی و جست‌وجوی شیشه‌ای روی میدان رنگیِ متحرک؛ فوکوس هالهٔ نور دور کادر می‌نشیند، دکمهٔ پاک‌کردن با اولین حرف می‌رسد و خطا و موفقیت رنگ rim را عوض می‌کنند.',
            'en' => 'A glass text and search field over a drifting colour field; focus settles a halo of light around the box, the clear button arrives with the first keystroke, and error or success re-tint the rim.',
        ],
        'js' => true,
        'docs' => 'https://glass.samasante.com',
        'props' => [
            ['name' => 'backdrop', 'type' => 'blur · saturate', 'default' => "'16px · 1.4'", 'note' => [
                'fa' => 'شیشه چقدر از رنگِ پشت را بلور و اشباع می‌کند؛ هرچه کمتر، رنگ‌ها تر تر دیده می‌شوند.',
                'en' => 'How much of the colour behind the glass gets blurred and saturated; less of it keeps the hues wetter.',
            ]],
            ['name' => 'halo', 'type' => 'focus ring', 'default' => "'4px · accent 26%'", 'note' => [
                'fa' => 'هالهٔ نورگیر دور کادر هنگام فوکوس؛ رنگ و پهنای آن از رنگِ حالت پیروی می‌کند (گلبهی برای خطا، سبز برای موفقیت).',
                'en' => 'The halo of light around the focused box; its colour and width follow the state (rose for error, green for success).',
            ]],
            ['name' => 'state', 'type' => 'idle · error · success', 'default' => "'idle'", 'note' => [
                'fa' => 'حالت کادر؛ با اعتبارسنجی زندهٔ نشانی جابه‌جا می‌شود و پیام زیرین را با خود می‌آورد.',
                'en' => 'The box’s state; it moves with live address validation and brings the note beneath with it.',
            ]],
            ['name' => 'clearable', 'type' => 'boolean', 'default' => 'true', 'note' => [
                'fa' => 'دکمهٔ پاک‌کردن فقط وقتی مقداری هست سر می‌کشد؛ با aria-label جدا.',
                'en' => 'The clear button only slides in once there is a value; it carries its own aria-label.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <label class="gin-field" @error('email') data-state="error" @enderror>
            <svg aria-hidden="true">…mail…</svg>
            <input type="email" wire:model.live="email" placeholder="mia@northwind.dev">
            <button type="button" wire:click="$set('email', '')" aria-label="پاک‌کردن نشانی">
                <svg>…x…</svg>
            </button>
        </label>

        <style>
        .gin-field { display: flex; align-items: center; gap: .55rem; inline-size: min(100%, 22rem);
                     padding: .85rem .95rem; border-radius: .9rem;
                     background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.44);
                     backdrop-filter: blur(16px) saturate(1.4);
                     box-shadow: inset 0 1px 0 rgba(255,255,255,.5), 0 10px 30px #00000022;
                     transition: border-color .2s ease, box-shadow .25s ease; }
        .gin-field input { flex: 1; min-inline-size: 0; border: none; background: none;
                           outline: none; color: #10142e; }
        .gin-field:focus-within { border-color: #aab4ff;
                     box-shadow: inset 0 1px 0 rgba(255,255,255,.5),
                                 0 0 0 4px #5a6cff42, 0 14px 34px #5a6cff3a; }
        .gin-field[data-state='error'] { border-color: #ff9da0; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <label class="gin-field" @error('email') data-state="error" @enderror>
        <svg aria-hidden="true">…mail…</svg>
        <input type="email" wire:model.live="email" placeholder="mia@northwind.dev">
        <button type="button" wire:click="$set('email', '')" aria-label="Clear the address">
            <svg>…x…</svg>
        </button>
        </label>

        <style>
        .gin-field { display: flex; align-items: center; gap: .55rem; inline-size: min(100%, 22rem);
                     padding: .85rem .95rem; border-radius: .9rem;
                     background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.44);
                     backdrop-filter: blur(16px) saturate(1.4);
                     box-shadow: inset 0 1px 0 rgba(255,255,255,.5), 0 10px 30px #00000022;
                     transition: border-color .2s ease, box-shadow .25s ease; }
        .gin-field input { flex: 1; min-inline-size: 0; border: none; background: none;
                           outline: none; color: #10142e; }
        .gin-field:focus-within { border-color: #aab4ff;
                     box-shadow: inset 0 1px 0 rgba(255,255,255,.5),
                                 0 0 0 4px #5a6cff42, 0 14px 34px #5a6cff3a; }
        .gin-field[data-state='error'] { border-color: #ff9da0; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Invite.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import InviteForm from '@/components/InviteForm';

        export default function Invite() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <InviteForm />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // InviteForm.jsx — glass input with focus halo, clear button and states
        import { useState } from 'react';
        import '@nabuxai/ui-core/css';

        export default function InviteForm() {
          const [email, setEmail] = useState('');
          const ok = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email.trim());
          const bad = email.trim().length > 3 && !ok;
          const state = ok ? 'success' : bad ? 'error' : '';

          return (
            <>
              <style>{`
                .gin-stage { position: relative; overflow: clip; display: grid; place-items: center;
                             inline-size: min(100%, 26rem); padding: 3rem 1.25rem; border-radius: 1.25rem;
                             background: #eef0fb; }
                .gin-stage::before, .gin-stage::after { content: ''; position: absolute; z-index: 0;
                             inline-size: 46%; aspect-ratio: 1; border-radius: 50%; filter: blur(30px) saturate(1.25); }
                .gin-stage::before { inset-block-start: -14%; inset-inline-start: -8%; background: #5470ff; }
                .gin-stage::after { inset-block-end: -20%; inset-inline-end: -10%; background: #9a6cff; }
                .gin-field { position: relative; z-index: 1; display: flex; align-items: center; gap: .55rem;
                             inline-size: min(100%, 22rem); padding: .85rem .95rem; border-radius: .9rem;
                             background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.44);
                             backdrop-filter: blur(16px) saturate(1.4);
                             box-shadow: inset 0 1px 0 rgba(255,255,255,.5), 0 10px 30px #0003;
                             transition: border-color .2s ease, box-shadow .25s ease; }
                .gin-field:focus-within { border-color: #aab4ff;
                             box-shadow: inset 0 1px 0 rgba(255,255,255,.5),
                                         0 0 0 4px #5a6cff42, 0 14px 34px #5a6cff3a; }
                .gin-field[data-state='error'] { border-color: #ff9da0; }
                .gin-field[data-state='success'] { border-color: #7ce3ac; }
                .gin-field input { flex: 1; min-inline-size: 0; border: none; background: none;
                                   outline: none; color: #10142e; font: 500 .95rem system-ui; }
                .gin-clear { display: grid; place-items: center; inline-size: 1.6rem; aspect-ratio: 1;
                             border: 1px solid rgba(255,255,255,.44); border-radius: 50%;
                             background: rgba(255,255,255,.15); cursor: pointer; }
                .gin-note { min-block-size: 1.2em; font-size: .78rem; }
                .gin-note[data-tone='error'] { color: #e5484d; }
                .gin-note[data-tone='success'] { color: #12a150; }
              `}</style>
              <div className="gin-stage">
                <label className="gin-field" data-state={state}>
                  <svg aria-hidden="true" viewBox="0 0 24 24" width="18" height="18" fill="none"
                       stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"
                       /><path d="m3 7 9 6 9-6" /></svg>
                  <input value={email} onChange={(e) => setEmail(e.target.value)}
                         type="email" placeholder="mia@northwind.dev" aria-label="Work email" />
                  {email && (
                    <button type="button" className="gin-clear" aria-label="Clear the address"
                            onClick={() => setEmail('')}>
                      <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor"
                           stroke-width="2"><path d="M18 6 6 18M6 6l12 12" /></svg>
                    </button>
                  )}
                </label>
                <p className="gin-note" data-tone={bad ? 'error' : ok ? 'success' : undefined}>
                  {bad ? 'That address looks incomplete.' : ok ? 'A magic link, no password to remember.' : ''}
                </p>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- InviteForm.vue — glass input with focus halo, clear button and states -->
        <script setup lang="ts">
        import { computed, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const email = ref('');
        const ok = computed(() => /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email.value.trim()));
        const bad = computed(() => email.value.trim().length > 3 && !ok.value);
        const state = computed(() => (ok.value ? 'success' : bad.value ? 'error' : ''));
        </script>

        <template>
          <div class="gin-stage">
            <label class="gin-field" :data-state="state">
              <svg aria-hidden="true" viewBox="0 0 24 24" width="18" height="18" fill="none"
                   stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2" /><path d="m3 7 9 6 9-6" /></svg>
              <input v-model="email" type="email" placeholder="mia@northwind.dev" aria-label="Work email" />
              <button v-if="email" type="button" class="gin-clear" aria-label="Clear the address"
                      @click="email = ''">
                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor"
                     stroke-width="2"><path d="M18 6 6 18M6 6l12 12" /></svg>
              </button>
            </label>
            <p class="gin-note" :data-tone="bad ? 'error' : ok ? 'success' : undefined">
              {{ bad ? 'That address looks incomplete.' : ok ? 'A magic link, no password to remember.' : '' }}
            </p>
          </div>
        </template>

        <style scoped>
        .gin-stage { position: relative; overflow: clip; display: grid; place-items: center; gap: .6rem;
                     inline-size: min(100%, 26rem); padding: 3rem 1.25rem; border-radius: 1.25rem; background: #eef0fb; }
        .gin-stage::before, .gin-stage::after { content: ''; position: absolute; z-index: 0;
                     inline-size: 46%; aspect-ratio: 1; border-radius: 50%; filter: blur(30px) saturate(1.25); }
        .gin-stage::before { inset-block-start: -14%; inset-inline-start: -8%; background: #5470ff; }
        .gin-stage::after { inset-block-end: -20%; inset-inline-end: -10%; background: #9a6cff; }
        .gin-field { position: relative; z-index: 1; display: flex; align-items: center; gap: .55rem;
                     inline-size: min(100%, 22rem); padding: .85rem .95rem; border-radius: .9rem;
                     background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.44);
                     backdrop-filter: blur(16px) saturate(1.4);
                     box-shadow: inset 0 1px 0 rgba(255,255,255,.5), 0 10px 30px #0003;
                     transition: border-color .2s ease, box-shadow .25s ease; }
        .gin-field:focus-within { border-color: #aab4ff;
                     box-shadow: inset 0 1px 0 rgba(255,255,255,.5),
                                 0 0 0 4px #5a6cff42, 0 14px 34px #5a6cff3a; }
        .gin-field[data-state='error'] { border-color: #ff9da0; }
        .gin-field[data-state='success'] { border-color: #7ce3ac; }
        .gin-field input { flex: 1; min-inline-size: 0; border: none; background: none;
                           outline: none; color: #10142e; font: 500 .95rem system-ui; }
        .gin-clear { display: grid; place-items: center; inline-size: 1.6rem; aspect-ratio: 1;
                     border: 1px solid rgba(255,255,255,.44); border-radius: 50%;
                     background: rgba(255,255,255,.15); cursor: pointer; }
        .gin-note { min-block-size: 1.2em; font-size: .78rem; }
        .gin-note[data-tone='error'] { color: #e5484d; }
        .gin-note[data-tone='success'] { color: #12a150; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- InviteForm.svelte — glass input with focus halo, clear button and states -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          let email = $state('');
          const ok = $derived(/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email.trim()));
          const bad = $derived(email.trim().length > 3 && !ok);
          const state = $derived(ok ? 'success' : bad ? 'error' : '');
        </script>

        <div class="gin-stage">
          <label class="gin-field" data-state={state}>
            <svg aria-hidden="true" viewBox="0 0 24 24" width="18" height="18" fill="none"
                 stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2" /><path d="m3 7 9 6 9-6" /></svg>
            <input bind:value={email} type="email" placeholder="mia@northwind.dev" aria-label="Work email" />
            {#if email}
              <button type="button" class="gin-clear" aria-label="Clear the address"
                      onclick={() => (email = '')}>
                <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor"
                     stroke-width="2"><path d="M18 6 6 18M6 6l12 12" /></svg>
              </button>
            {/if}
          </label>
          <p class="gin-note" data-tone={bad ? 'error' : ok ? 'success' : undefined}>
            {bad ? 'That address looks incomplete.' : ok ? 'A magic link, no password to remember.' : ''}
          </p>
        </div>

        <style>
        .gin-stage { position: relative; overflow: clip; display: grid; place-items: center; gap: .6rem;
                     inline-size: min(100%, 26rem); padding: 3rem 1.25rem; border-radius: 1.25rem; background: #eef0fb; }
        .gin-stage::before, .gin-stage::after { content: ''; position: absolute; z-index: 0;
                     inline-size: 46%; aspect-ratio: 1; border-radius: 50%; filter: blur(30px) saturate(1.25); }
        .gin-stage::before { inset-block-start: -14%; inset-inline-start: -8%; background: #5470ff; }
        .gin-stage::after { inset-block-end: -20%; inset-inline-end: -10%; background: #9a6cff; }
        .gin-field { position: relative; z-index: 1; display: flex; align-items: center; gap: .55rem;
                     inline-size: min(100%, 22rem); padding: .85rem .95rem; border-radius: .9rem;
                     background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.44);
                     backdrop-filter: blur(16px) saturate(1.4);
                     box-shadow: inset 0 1px 0 rgba(255,255,255,.5), 0 10px 30px #0003;
                     transition: border-color .2s ease, box-shadow .25s ease; }
        .gin-field:focus-within { border-color: #aab4ff;
                     box-shadow: inset 0 1px 0 rgba(255,255,255,.5),
                                 0 0 0 4px #5a6cff42, 0 14px 34px #5a6cff3a; }
        .gin-field[data-state='error'] { border-color: #ff9da0; }
        .gin-field[data-state='success'] { border-color: #7ce3ac; }
        .gin-field input { flex: 1; min-inline-size: 0; border: none; background: none;
                           outline: none; color: #10142e; font: 500 .95rem system-ui; }
        .gin-clear { display: grid; place-items: center; inline-size: 1.6rem; aspect-ratio: 1;
                     border: 1px solid rgba(255,255,255,.44); border-radius: 50%;
                     background: rgba(255,255,255,.15); cursor: pointer; }
        .gin-note { min-block-size: 1.2em; font-size: .78rem; }
        .gin-note[data-tone='error'] { color: #e5484d; }
        .gin-note[data-tone='success'] { color: #12a150; }
        </style>
        SVELTE,
        ],
    ],

    'glass-notification' => [
        'title' => ['fa' => 'اعلان شیشه‌ای', 'en' => 'Glass notification'],
        'icon' => 'bell',
        'oneLiner' => [
            'fa' => 'پیل اعلان شیشه‌ای (آیکون + متن + کنش) و استک سه‌تایی؛ تایمرِ خودبستن به‌شکل نوار پیشرفت می‌گذرد، با هاور یا فوکوس می‌ایستد و با دکمه هم می‌توان بست.',
            'en' => 'A glass notification pill (icon + copy + action) and a stack of three; the self-close timer runs out as a progress bar, freezes under the pointer or focus, and a button closes it anyway.',
        ],
        'js' => true,
        'docs' => 'https://glass.samasante.com',
        'props' => [
            ['name' => 'duration', 'type' => 'ms', 'default' => "'7000'", 'note' => [
                'fa' => 'عمر اعلان؛ تایمر به‌شکل خطِ باریکِ پایین تخلیه می‌شود و تهش اعلان خودش می‌بندد.',
                'en' => 'The toast’s lifetime; the timer drains along the hairline at the bottom and the toast closes itself at the end.',
            ]],
            ['name' => 'pause-on-hover', 'type' => 'boolean', 'default' => 'true', 'note' => [
                'fa' => 'هاور یا فوکوسِ کیبورد ساعت را نگه می‌دارد؛ بدون آن اعلان زیر دستِ تو می‌میرد.',
                'en' => 'Hover or keyboard focus holds the clock; without it the toast dies under your hand.',
            ]],
            ['name' => 'tone', 'type' => 'success · warning · info', 'default' => "'info'", 'note' => [
                'fa' => 'رنگ آیکون و نوارِ پیشرفت؛ warning نقش alert و بقیه status می‌گیرند.',
                'en' => 'Tints the icon chip and the progress bar; warning takes the alert role, the rest status.',
            ]],
            ['name' => 'action', 'type' => 'label · handler', 'default' => 'null', 'note' => [
                'fa' => 'کنشِ انتهای پیل؛ کنار دکمهٔ بستن می‌نشیند و هم‌قوارهٔ همان توقفِ هاوری است.',
                'en' => 'The action at the pill’s end; it sits beside the close button and shares the same hover-hold.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="gnt-toast" data-tone="success" role="status"
             @mouseenter="$set('paused', true)" @mouseleave="$set('paused', false)">
            <span class="gnt-icon">…check…</span>
            <div class="gnt-body">
                <p class="gnt-title">بیلد ۸۴۲ به فرانکفورت رسید</p>
                <p class="gnt-meta">۲ دقیقه پیش · واگرد تا یک ساعت باز</p>
            </div>
            <button type="button" wire:click="viewBuild">دیدن</button>
            <button type="button" wire:click="dismiss" aria-label="بستن اعلان">…x…</button>
            <i class="gnt-bar" style="inline-size: 64%"></i>
        </div>

        <style>
        .gnt-toast { position: relative; display: flex; align-items: center; gap: .75rem;
                     inline-size: min(100%, 28rem); padding: .8rem .9rem; border-radius: 1.1rem; overflow: clip;
                     background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.44);
                     backdrop-filter: blur(16px) saturate(1.4);
                     box-shadow: inset 0 1px 0 rgba(255,255,255,.5), 0 14px 34px #00000029; }
        .gnt-bar { position: absolute; inset-block-end: 0; inset-inline-start: 0; block-size: 3px;
                   border-radius: 999px; background: #12a150; transition: inline-size .12s linear; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="gnt-toast" data-tone="success" role="status"
             @mouseenter="$set('paused', true)" @mouseleave="$set('paused', false)">
        <span class="gnt-icon">…check…</span>
        <div class="gnt-body">
        <p class="gnt-title">Build 842 shipped to Frankfurt</p>
        <p class="gnt-meta">2 minutes ago · rollback open for an hour</p>
        </div>
        <button type="button" wire:click="viewBuild">View</button>
        <button type="button" wire:click="dismiss" aria-label="Close notification">…x…</button>
        <i class="gnt-bar" style="inline-size: 64%"></i>
        </div>

        <style>
        .gnt-toast { position: relative; display: flex; align-items: center; gap: .75rem;
                     inline-size: min(100%, 28rem); padding: .8rem .9rem; border-radius: 1.1rem; overflow: clip;
                     background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.44);
                     backdrop-filter: blur(16px) saturate(1.4);
                     box-shadow: inset 0 1px 0 rgba(255,255,255,.5), 0 14px 34px #00000029; }
        .gnt-bar { position: absolute; inset-block-end: 0; inset-inline-start: 0; block-size: 3px;
                   border-radius: 999px; background: #12a150; transition: inline-size .12s linear; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Feed.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import NotificationFeed from '@/components/NotificationFeed';

        export default function Feed() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <NotificationFeed />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // NotificationFeed.jsx — glass toasts, self-closing, hover freezes the clock
        import { useEffect, useRef, useState } from 'react';
        import '@nabuxai/ui-core/css';

        const FEED = [
          { id: 1, tone: 'success', title: 'Build 842 shipped to Frankfurt',
            meta: '2 minutes ago · rollback open for an hour', action: 'View', dur: 6200 },
          { id: 2, tone: 'warning', title: 'Error rate climbing in Dublin',
            meta: 'Checkout API · 2.4% of requests 5xx', action: 'Inspect', dur: 10600 },
          { id: 3, tone: 'info', title: 'Your weekly digest is ready',
            meta: '12 deploys · 3 incidents', action: 'Read', dur: 8800 },
        ];

        export default function NotificationFeed() {
          const [left, setLeft] = useState(() => Object.fromEntries(FEED.map((n) => [n.id, n.dur])));
          const [paused, setPaused] = useState(null);
          const raf = useRef();

          useEffect(() => {
            let was = performance.now();
            const step = (now) => {
              const dt = now - was; was = now;
              setLeft((l) => {
                const next = {};
                for (const [id, v] of Object.entries(l)) {
                  next[id] = +id === paused || v <= 0 ? v : Math.max(0, v - dt);
                }
                return next;
              });
              raf.current = requestAnimationFrame(step);
            };
            raf.current = requestAnimationFrame(step);
            return () => cancelAnimationFrame(raf.current);
          }, [paused]);

          return (
            <>
              <style>{`
                .gnt-toast { position: relative; display: flex; align-items: center; gap: .75rem;
                             inline-size: min(100%, 28rem); padding: .8rem .9rem; border-radius: 1.1rem; overflow: clip;
                             background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.44);
                             backdrop-filter: blur(16px) saturate(1.4);
                             box-shadow: inset 0 1px 0 rgba(255,255,255,.5), 0 14px 34px #0003; }
                .gnt-toast[data-tone='success'] { --tone: #12a150; }
                .gnt-toast[data-tone='warning'] { --tone: #c07807; }
                .gnt-toast[data-tone='info'] { --tone: #3d63dd; }
                .gnt-icon { display: grid; place-items: center; inline-size: 2.15rem; aspect-ratio: 1;
                            border-radius: 50%; background: color-mix(in oklab, var(--tone) 24%, transparent); }
                .gnt-bar { position: absolute; inset-block-end: 0; inset-inline-start: 0; block-size: 3px;
                           background: var(--tone); }
                .gnt-x { margin-inline-start: auto; border: none; background: none; cursor: pointer; }
              `}</style>
              <div className="gnt-stack" style={{ display: 'grid', gap: '.8rem', justifyItems: 'center' }}>
                {FEED.filter((n) => left[n.id] > 0).map((n) => (
                  <div key={n.id} className="gnt-toast" data-tone={n.tone} role="status"
                       onMouseEnter={() => setPaused(n.id)} onMouseLeave={() => setPaused(null)}
                       onFocus={() => setPaused(n.id)} onBlur={() => setPaused(null)}>
                    <span className="gnt-icon" style={{ color: 'var(--tone)' }}>●</span>
                    <div className="gnt-body">
                      <strong>{n.title}</strong>
                      <small>{n.meta}</small>
                    </div>
                    <button type="button" className="gnt-act">{n.action}</button>
                    <button type="button" className="gnt-x" aria-label="Close notification"
                            onClick={() => setLeft((l) => ({ ...l, [n.id]: 0 }))}>×</button>
                    <i className="gnt-bar" style={{ inlineSize: `${Math.max(0, (left[n.id] / n.dur) * 100)}%` }} />
                  </div>
                ))}
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- NotificationFeed.vue — glass toasts, self-closing, hover freezes the clock -->
        <script setup lang="ts">
        import { onMounted, onUnmounted, reactive, ref } from 'vue';
        import '@nabuxai/ui-core/css';

        const feed = [
          { id: 1, tone: 'success', title: 'Build 842 shipped to Frankfurt',
            meta: '2 minutes ago · rollback open for an hour', action: 'View', dur: 6200 },
          { id: 2, tone: 'warning', title: 'Error rate climbing in Dublin',
            meta: 'Checkout API · 2.4% of requests 5xx', action: 'Inspect', dur: 10600 },
          { id: 3, tone: 'info', title: 'Your weekly digest is ready',
            meta: '12 deploys · 3 incidents', action: 'Read', dur: 8800 },
        ];
        const left = reactive(Object.fromEntries(feed.map((n) => [n.id, n.dur])));
        const paused = ref<number | null>(null);
        let timer: number | undefined;

        onMounted(() => {
          timer = window.setInterval(() => {
            for (const n of feed) {
              if (paused.value === n.id || left[n.id] <= 0) continue;
              left[n.id] = Math.max(0, left[n.id] - 100);
            }
          }, 100);
        });
        onUnmounted(() => window.clearInterval(timer));
        const pct = (id: number) => (left[id] / feed.find((n) => n.id === id)!.dur) * 100;
        </script>

        <template>
          <div class="gnt-stack">
            <div v-for="n in feed.filter((n) => left[n.id] > 0)" :key="n.id"
                 class="gnt-toast" :data-tone="n.tone" role="status"
                 @mouseenter="paused = n.id" @mouseleave="paused = null"
                 @focusin="paused = n.id" @focusout="paused = null">
              <span class="gnt-icon">●</span>
              <div class="gnt-body">
                <strong>{{ n.title }}</strong>
                <small>{{ n.meta }}</small>
              </div>
              <button type="button" class="gnt-act">{{ n.action }}</button>
              <button type="button" class="gnt-x" aria-label="Close notification"
                      @click="left[n.id] = 0">×</button>
              <i class="gnt-bar" :style="{ inlineSize: pct(n.id) + '%' }"></i>
            </div>
          </div>
        </template>

        <style scoped>
        .gnt-toast { position: relative; display: flex; align-items: center; gap: .75rem;
                     inline-size: min(100%, 28rem); padding: .8rem .9rem; border-radius: 1.1rem; overflow: clip;
                     background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.44);
                     backdrop-filter: blur(16px) saturate(1.4);
                     box-shadow: inset 0 1px 0 rgba(255,255,255,.5), 0 14px 34px #0003; }
        .gnt-toast[data-tone='success'] { --tone: #12a150; }
        .gnt-toast[data-tone='warning'] { --tone: #c07807; }
        .gnt-toast[data-tone='info'] { --tone: #3d63dd; }
        .gnt-icon { display: grid; place-items: center; inline-size: 2.15rem; aspect-ratio: 1;
                    border-radius: 50%; background: color-mix(in oklab, var(--tone) 24%, transparent); color: var(--tone); }
        .gnt-bar { position: absolute; inset-block-end: 0; inset-inline-start: 0; block-size: 3px;
                   background: var(--tone); transition: inline-size .12s linear; }
        .gnt-x { margin-inline-start: auto; border: none; background: none; cursor: pointer; }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- NotificationFeed.svelte — glass toasts, self-closing, hover freezes the clock -->
        <script lang="ts">
          import { onMount } from 'svelte';
          import '@nabuxai/ui-core/css';

          const feed = [
            { id: 1, tone: 'success', title: 'Build 842 shipped to Frankfurt',
              meta: '2 minutes ago · rollback open for an hour', action: 'View', dur: 6200 },
            { id: 2, tone: 'warning', title: 'Error rate climbing in Dublin',
              meta: 'Checkout API · 2.4% of requests 5xx', action: 'Inspect', dur: 10600 },
            { id: 3, tone: 'info', title: 'Your weekly digest is ready',
              meta: '12 deploys · 3 incidents', action: 'Read', dur: 8800 },
          ];
          let left = $state(Object.fromEntries(feed.map((n) => [n.id, n.dur])));
          let paused = $state<number | null>(null);
          const pct = (id: number) => (Math.max(0, left[id]) / feed.find((n) => n.id === id)!.dur) * 100;

          onMount(() => {
            const timer = setInterval(() => {
              for (const n of feed) {
                if (paused === n.id || left[n.id] <= 0) continue;
                left[n.id] = Math.max(0, left[n.id] - 100);
              }
            }, 100);
            return () => clearInterval(timer);
          });
        </script>

        <div class="gnt-stack">
          {#each feed.filter((n) => left[n.id] > 0) as n (n.id)}
            <div class="gnt-toast" data-tone={n.tone} role="status"
                 onmouseenter={() => (paused = n.id)} onmouseleave={() => (paused = null)}
                 onfocusin={() => (paused = n.id)} onfocusout={() => (paused = null)}>
              <span class="gnt-icon">●</span>
              <div class="gnt-body">
                <strong>{n.title}</strong>
                <small>{n.meta}</small>
              </div>
              <button type="button" class="gnt-act">{n.action}</button>
              <button type="button" class="gnt-x" aria-label="Close notification"
                      onclick={() => (left[n.id] = 0)}>×</button>
              <i class="gnt-bar" style="inline-size: {pct(n.id)}%"></i>
            </div>
          {/each}
        </div>

        <style>
        .gnt-toast { position: relative; display: flex; align-items: center; gap: .75rem;
                     inline-size: min(100%, 28rem); padding: .8rem .9rem; border-radius: 1.1rem; overflow: clip;
                     background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.44);
                     backdrop-filter: blur(16px) saturate(1.4);
                     box-shadow: inset 0 1px 0 rgba(255,255,255,.5), 0 14px 34px #0003; }
        .gnt-toast[data-tone='success'] { --tone: #12a150; }
        .gnt-toast[data-tone='warning'] { --tone: #c07807; }
        .gnt-toast[data-tone='info'] { --tone: #3d63dd; }
        .gnt-icon { display: grid; place-items: center; inline-size: 2.15rem; aspect-ratio: 1;
                    border-radius: 50%; background: color-mix(in oklab, var(--tone) 24%, transparent); color: var(--tone); }
        .gnt-bar { position: absolute; inset-block-end: 0; inset-inline-start: 0; block-size: 3px;
                   background: var(--tone); transition: inline-size .12s linear; }
        .gnt-x { margin-inline-start: auto; border: none; background: none; cursor: pointer; }
        </style>
        SVELTE,
        ],
    ],

    'glass-pricing' => [
        'title' => ['fa' => 'قیمت‌گذاری شیشه‌ای', 'en' => 'Glass pricing'],
        'icon' => 'star',
        'oneLiner' => [
            'fa' => 'سه کارت قیمت شیشه‌ای روی بلاب‌های گرادیانی؛ کارت میانی با rim نورگیرِ نفس‌کش و بج «محبوب» برجسته می‌شود و دکمه‌ها با هاور شیمر از برق می‌گذرند.',
            'en' => 'Three glass pricing cards over gradient blobs; the middle one stands out with a breathing luminous rim and the popular badge, and every button throws a shimmer across its face on hover.',
        ],
        'js' => false,
        'docs' => 'https://glass.samasante.com',
        'props' => [
            ['name' => 'highlight', 'type' => 'boolean', 'default' => 'false', 'note' => [
                'fa' => 'کارت را بلند می‌کند و rimش را به گرادیان سه‌رنگ بدل می‌کند که آرام نور می‌کشد و می‌خاموشد.',
                'en' => 'Lifts the card and turns its rim into a three-colour gradient that slowly brightens and dims.',
            ]],
            ['name' => 'badge', 'type' => 'string', 'default' => "'Popular'", 'note' => [
                'fa' => 'برچسب تاجِ کارت برجسته؛ با آیکون ستاره و پس‌زمینهٔ طلایی.',
                'en' => 'The label on the highlighted card’s crown; star icon on a gold ground.',
            ]],
            ['name' => 'shimmer', 'type' => 'boolean', 'default' => 'true', 'note' => [
                'fa' => 'هاورِ دکمه یک برقِ کج از صورتش می‌گذراند؛ با prefers-reduced-motion به‌کلی خاموش می‌شود.',
                'en' => 'Hovering the button sweeps a slanted gleam across it; prefers-reduced-motion switches it off entirely.',
            ]],
            ['name' => 'layout', 'type' => 'auto-fit grid', 'default' => "'15rem · 1fr'", 'note' => [
                'fa' => 'سه کارت در یک ردیف که در باریک‌ترین صفحه‌ها تک‌ستونه می‌شوند؛ بدون سرریز افقی تا ۳۷۵px.',
                'en' => 'Three cards in a row that goes single-column on narrow screens; no horizontal overflow down to 375px.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
        <div class="gpc-grid">
            <article class="gpc-card gpc-card--hot">
                <span class="gpc-badge">★ محبوب</span>
                <h4>رشد</h4>
                <div class="gpc-figure"><span>$</span><b>۲۴</b><small>به‌ازای هر صندلی در ماه</small></div>
                <ul class="gpc-feats"><li>✓ ورک‌اسپیس نامحدود</li></ul>
                <button class="gpc-go gpc-go--primary" wire:click="trial">شروع ۱۴ روز آزمایشی</button>
            </article>
        </div>

        <style>
        .gpc-card { display: grid; gap: 1rem; justify-items: center; padding: 1.75rem 1.35rem;
                    border-radius: 1.25rem; background: rgba(255,255,255,.15);
                    border: 1px solid rgba(255,255,255,.44); backdrop-filter: blur(18px) saturate(1.4);
                    box-shadow: inset 0 1px 0 rgba(255,255,255,.5), 0 16px 38px #0d103029; }
        .gpc-card--hot { border: 1.5px solid transparent;
                    background: linear-gradient(125deg, rgba(255,255,255,.15), rgba(255,255,255,.06)) padding-box,
                                linear-gradient(160deg, #5470ff, #58e0d0 55%, #9a6cff) border-box; }
        .gpc-go { position: relative; overflow: clip; inline-size: 100%; block-size: 2.85rem;
                  border: none; border-radius: 999px; background: linear-gradient(120deg, #5470ff, #9a6cff); color: #fff; }
        .gpc-go::after { content: ''; position: absolute; inset-block: -60%; inset-inline-start: -75%;
                  inline-size: 45%; background: linear-gradient(105deg, transparent, #ffffff80, transparent);
                  transform: skewX(-18deg); transition: inset-inline-start .6s ease; }
        .gpc-go:hover::after { inset-inline-start: 135%; }
        </style>
        BLADE,
                'en' => <<<'BLADE'
        <div class="gpc-grid">
        <article class="gpc-card gpc-card--hot">
            <span class="gpc-badge">★ Popular</span>
            <h4>Growth</h4>
            <div class="gpc-figure"><span>$</span><b>24</b><small>per seat / month</small></div>
            <ul class="gpc-feats"><li>✓ Unlimited workspaces</li></ul>
            <button class="gpc-go gpc-go--primary" wire:click="trial">Start 14-day trial</button>
        </article>
        </div>

        <style>
        .gpc-card { display: grid; gap: 1rem; justify-items: center; padding: 1.75rem 1.35rem;
                    border-radius: 1.25rem; background: rgba(255,255,255,.15);
                    border: 1px solid rgba(255,255,255,.44); backdrop-filter: blur(18px) saturate(1.4);
                    box-shadow: inset 0 1px 0 rgba(255,255,255,.5), 0 16px 38px #0d103029; }
        .gpc-card--hot { border: 1.5px solid transparent;
                    background: linear-gradient(125deg, rgba(255,255,255,.15), rgba(255,255,255,.06)) padding-box,
                                linear-gradient(160deg, #5470ff, #58e0d0 55%, #9a6cff) border-box; }
        .gpc-go { position: relative; overflow: clip; inline-size: 100%; block-size: 2.85rem;
                  border: none; border-radius: 999px; background: linear-gradient(120deg, #5470ff, #9a6cff); color: #fff; }
        .gpc-go::after { content: ''; position: absolute; inset-block: -60%; inset-inline-start: -75%;
                  inline-size: 45%; background: linear-gradient(105deg, transparent, #ffffff80, transparent);
                  transform: skewX(-18deg); transition: inset-inline-start .6s ease; }
        .gpc-go:hover::after { inset-inline-start: 135%; }
        </style>
        BLADE,
            ],
            'inertia' => <<<'TSX'
        // app/Pages/Pricing.jsx — Inertia (React) page; renders the React
        // snippet below unchanged, with Inertia's Link wired into the provider.
        import { Link } from '@inertiajs/react';
        import { NabuXUIProvider } from '@nabuxai/ui-react';
        import PricingCards from '@/components/PricingCards';

        export default function Pricing() {
          return (
            <NabuXUIProvider locale="en" linkComponent={Link}>
              <main className="nx-page">
                <PricingCards />
              </main>
            </NabuXUIProvider>
          );
        }
        TSX,
            'react' => <<<'REACT'
        // PricingCards.jsx — glass pricing over gradient blobs, hover shimmer
        import '@nabuxai/ui-core/css';

        const PLANS = [
          { name: 'Starter', price: 0, per: 'forever', hot: false, cta: 'Start free',
            feats: ['1 workspace, 3 seats', '7-day trace retention', 'Community support'] },
          { name: 'Growth', price: 24, per: 'per seat / month', hot: true, cta: 'Start 14-day trial',
            feats: ['Unlimited workspaces', '30-day trace retention', 'Slack + webhook alerts', 'Error budgets & SLOs'] },
          { name: 'Scale', price: 79, per: 'per seat / month', hot: false, cta: 'Talk to sales',
            feats: ['SSO / SAML & SCIM', '1-year retention, EU or Singapore', 'Audit log & dedicated support'] },
        ];

        export default function PricingCards() {
          return (
            <>
              <style>{`
                .gpc-stage { position: relative; overflow: clip; display: grid; place-items: center;
                             padding: 4rem 1.25rem; border-radius: 1.5rem; background: #eef0fb; }
                .gpc-stage::before { content: ''; position: absolute; inset: 0;
                             background: radial-gradient(42% 55% at 15% 20%, #5470ff88, transparent 60%),
                                         radial-gradient(40% 50% at 85% 15%, #58e0d077, transparent 60%),
                                         radial-gradient(45% 55% at 70% 90%, #9a6cff88, transparent 60%);
                             filter: blur(30px) saturate(1.25); }
                .gpc-grid { position: relative; display: grid; gap: 1.4rem; align-items: end;
                             grid-template-columns: repeat(auto-fit, minmax(15rem, 1fr));
                             inline-size: min(100%, 63rem); }
                .gpc-card { display: grid; gap: 1rem; justify-items: center; text-align: center;
                             padding: 1.75rem 1.35rem; border-radius: 1.25rem;
                             background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.44);
                             backdrop-filter: blur(18px) saturate(1.4); color: #10142e;
                             box-shadow: inset 0 1px 0 rgba(255,255,255,.5), 0 16px 38px #0d103029;
                             transition: translate .25s ease; }
                .gpc-card:hover { translate: 0 -.35rem; }
                .gpc-card--hot { position: relative; border: 1.5px solid transparent; translate: 0 -.55rem;
                             background: linear-gradient(125deg, rgba(255,255,255,.15), rgba(255,255,255,.06)) padding-box,
                                         linear-gradient(160deg, #5470ff, #58e0d0 55%, #9a6cff) border-box; }
                .gpc-badge { position: absolute; inset-block-start: -.8rem; margin-inline: auto;
                             padding: .32rem .8rem; border-radius: 999px;
                             background: linear-gradient(120deg, #ffc94d, #e8a013); color: #241a02;
                             font: 700 .72rem/1 system-ui; }
                .gpc-amount { font: 800 3rem/1 system-ui; }
                .gpc-feats { display: grid; gap: .55rem; justify-items: start; inline-size: 100%;
                             margin: 0; padding: 0; list-style: none; }
                .gpc-go { position: relative; overflow: clip; display: inline-flex; align-items: center;
                             justify-content: center; gap: .45rem; inline-size: 100%; block-size: 2.85rem;
                             border-radius: 999px; border: 1px solid rgba(255,255,255,.44);
                             background: rgba(255,255,255,.15); color: #10142e; cursor: pointer;
                             font: 600 .88rem/1 system-ui; transition: translate .2s ease; }
                .gpc-go--primary { border: none; color: #fff;
                             background: linear-gradient(120deg, #5470ff, #9a6cff); }
                .gpc-go:hover { translate: 0 -2px; }
                .gpc-go::after { content: ''; position: absolute; inset-block: -60%; inset-inline-start: -75%;
                             inline-size: 45%; background: linear-gradient(105deg, transparent, #ffffff80, transparent);
                             transform: skewX(-18deg); transition: inset-inline-start .6s ease; }
                .gpc-go:hover::after { inset-inline-start: 135%; }
                @media (prefers-reduced-motion: reduce) { .gpc-go::after { display: none; } }
              `}</style>
              <div className="gpc-stage">
                <div className="gpc-grid">
                  {PLANS.map((p) => (
                    <article key={p.name} className={`gpc-card${p.hot ? ' gpc-card--hot' : ''}`}>
                      {p.hot && <span className="gpc-badge">★ Popular</span>}
                      <h4>{p.name}</h4>
                      <div className="gpc-figure">
                        <span>$</span><span className="gpc-amount">{p.price}</span><small>{p.per}</small>
                      </div>
                      <ul className="gpc-feats">
                        {p.feats.map((f) => <li key={f}>✓ {f}</li>)}
                      </ul>
                      <button type="button" className={`gpc-go${p.hot ? ' gpc-go--primary' : ''}`}>{p.cta}</button>
                    </article>
                  ))}
                </div>
              </div>
            </>
          );
        }
        REACT,
            'vue' => <<<'VUE'
        <!-- PricingCards.vue — glass pricing over gradient blobs, hover shimmer -->
        <script setup lang="ts">
        import '@nabuxai/ui-core/css';

        const plans = [
          { name: 'Starter', price: 0, per: 'forever', hot: false, cta: 'Start free',
            feats: ['1 workspace, 3 seats', '7-day trace retention', 'Community support'] },
          { name: 'Growth', price: 24, per: 'per seat / month', hot: true, cta: 'Start 14-day trial',
            feats: ['Unlimited workspaces', '30-day trace retention', 'Slack + webhook alerts', 'Error budgets & SLOs'] },
          { name: 'Scale', price: 79, per: 'per seat / month', hot: false, cta: 'Talk to sales',
            feats: ['SSO / SAML & SCIM', '1-year retention, EU or Singapore', 'Audit log & dedicated support'] },
        ];
        </script>

        <template>
          <div class="gpc-stage">
            <div class="gpc-grid">
              <article v-for="p in plans" :key="p.name" class="gpc-card" :class="{ 'gpc-card--hot': p.hot }">
                <span v-if="p.hot" class="gpc-badge">★ Popular</span>
                <h4>{{ p.name }}</h4>
                <div class="gpc-figure">
                  <span>$</span><span class="gpc-amount">{{ p.price }}</span><small>{{ p.per }}</small>
                </div>
                <ul class="gpc-feats">
                  <li v-for="f in p.feats" :key="f">✓ {{ f }}</li>
                </ul>
                <button type="button" class="gpc-go" :class="{ 'gpc-go--primary': p.hot }">{{ p.cta }}</button>
              </article>
            </div>
          </div>
        </template>

        <style scoped>
        .gpc-stage { position: relative; overflow: clip; display: grid; place-items: center;
                     padding: 4rem 1.25rem; border-radius: 1.5rem; background: #eef0fb; }
        .gpc-stage::before { content: ''; position: absolute; inset: 0;
                     background: radial-gradient(42% 55% at 15% 20%, #5470ff88, transparent 60%),
                                 radial-gradient(40% 50% at 85% 15%, #58e0d077, transparent 60%),
                                 radial-gradient(45% 55% at 70% 90%, #9a6cff88, transparent 60%);
                     filter: blur(30px) saturate(1.25); }
        .gpc-grid { position: relative; display: grid; gap: 1.4rem; align-items: end;
                     grid-template-columns: repeat(auto-fit, minmax(15rem, 1fr)); inline-size: min(100%, 63rem); }
        .gpc-card { display: grid; gap: 1rem; justify-items: center; text-align: center;
                     padding: 1.75rem 1.35rem; border-radius: 1.25rem;
                     background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.44);
                     backdrop-filter: blur(18px) saturate(1.4); color: #10142e;
                     box-shadow: inset 0 1px 0 rgba(255,255,255,.5), 0 16px 38px #0d103029;
                     transition: translate .25s ease; }
        .gpc-card:hover { translate: 0 -.35rem; }
        .gpc-card--hot { position: relative; border: 1.5px solid transparent; translate: 0 -.55rem;
                     background: linear-gradient(125deg, rgba(255,255,255,.15), rgba(255,255,255,.06)) padding-box,
                                 linear-gradient(160deg, #5470ff, #58e0d0 55%, #9a6cff) border-box; }
        .gpc-badge { position: absolute; inset-block-start: -.8rem; margin-inline: auto;
                     padding: .32rem .8rem; border-radius: 999px;
                     background: linear-gradient(120deg, #ffc94d, #e8a013); color: #241a02;
                     font: 700 .72rem/1 system-ui; }
        .gpc-amount { font: 800 3rem/1 system-ui; }
        .gpc-feats { display: grid; gap: .55rem; justify-items: start; inline-size: 100%;
                     margin: 0; padding: 0; list-style: none; }
        .gpc-go { position: relative; overflow: clip; display: inline-flex; align-items: center;
                     justify-content: center; gap: .45rem; inline-size: 100%; block-size: 2.85rem;
                     border-radius: 999px; border: 1px solid rgba(255,255,255,.44);
                     background: rgba(255,255,255,.15); color: #10142e; cursor: pointer;
                     font: 600 .88rem/1 system-ui; transition: translate .2s ease; }
        .gpc-go--primary { border: none; color: #fff; background: linear-gradient(120deg, #5470ff, #9a6cff); }
        .gpc-go:hover { translate: 0 -2px; }
        .gpc-go::after { content: ''; position: absolute; inset-block: -60%; inset-inline-start: -75%;
                     inline-size: 45%; background: linear-gradient(105deg, transparent, #ffffff80, transparent);
                     transform: skewX(-18deg); transition: inset-inline-start .6s ease; }
        .gpc-go:hover::after { inset-inline-start: 135%; }
        @media (prefers-reduced-motion: reduce) { .gpc-go::after { display: none; } }
        </style>
        VUE,
            'svelte' => <<<'SVELTE'
        <!-- PricingCards.svelte — glass pricing over gradient blobs, hover shimmer -->
        <script lang="ts">
          import '@nabuxai/ui-core/css';

          const plans = [
            { name: 'Starter', price: 0, per: 'forever', hot: false, cta: 'Start free',
              feats: ['1 workspace, 3 seats', '7-day trace retention', 'Community support'] },
            { name: 'Growth', price: 24, per: 'per seat / month', hot: true, cta: 'Start 14-day trial',
              feats: ['Unlimited workspaces', '30-day trace retention', 'Slack + webhook alerts', 'Error budgets & SLOs'] },
            { name: 'Scale', price: 79, per: 'per seat / month', hot: false, cta: 'Talk to sales',
              feats: ['SSO / SAML & SCIM', '1-year retention, EU or Singapore', 'Audit log & dedicated support'] },
          ];
        </script>

        <div class="gpc-stage">
          <div class="gpc-grid">
            {#each plans as p (p.name)}
              <article class="gpc-card" class:gpc-card--hot={p.hot}>
                {#if p.hot}<span class="gpc-badge">★ Popular</span>{/if}
                <h4>{p.name}</h4>
                <div class="gpc-figure">
                  <span>$</span><span class="gpc-amount">{p.price}</span><small>{p.per}</small>
                </div>
                <ul class="gpc-feats">
                  {#each p.feats as f (f)}<li>✓ {f}</li>{/each}
                </ul>
                <button type="button" class="gpc-go" class:gpc-go--primary={p.hot}>{p.cta}</button>
              </article>
            {/each}
          </div>
        </div>

        <style>
        .gpc-stage { position: relative; overflow: clip; display: grid; place-items: center;
                     padding: 4rem 1.25rem; border-radius: 1.5rem; background: #eef0fb; }
        .gpc-stage::before { content: ''; position: absolute; inset: 0;
                     background: radial-gradient(42% 55% at 15% 20%, #5470ff88, transparent 60%),
                                 radial-gradient(40% 50% at 85% 15%, #58e0d077, transparent 60%),
                                 radial-gradient(45% 55% at 70% 90%, #9a6cff88, transparent 60%);
                     filter: blur(30px) saturate(1.25); }
        .gpc-grid { position: relative; display: grid; gap: 1.4rem; align-items: end;
                     grid-template-columns: repeat(auto-fit, minmax(15rem, 1fr)); inline-size: min(100%, 63rem); }
        .gpc-card { display: grid; gap: 1rem; justify-items: center; text-align: center;
                     padding: 1.75rem 1.35rem; border-radius: 1.25rem;
                     background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.44);
                     backdrop-filter: blur(18px) saturate(1.4); color: #10142e;
                     box-shadow: inset 0 1px 0 rgba(255,255,255,.5), 0 16px 38px #0d103029;
                     transition: translate .25s ease; }
        .gpc-card:hover { translate: 0 -.35rem; }
        .gpc-card--hot { position: relative; border: 1.5px solid transparent; translate: 0 -.55rem;
                     background: linear-gradient(125deg, rgba(255,255,255,.15), rgba(255,255,255,.06)) padding-box,
                                 linear-gradient(160deg, #5470ff, #58e0d0 55%, #9a6cff) border-box; }
        .gpc-badge { position: absolute; inset-block-start: -.8rem; margin-inline: auto;
                     padding: .32rem .8rem; border-radius: 999px;
                     background: linear-gradient(120deg, #ffc94d, #e8a013); color: #241a02;
                     font: 700 .72rem/1 system-ui; }
        .gpc-amount { font: 800 3rem/1 system-ui; }
        .gpc-feats { display: grid; gap: .55rem; justify-items: start; inline-size: 100%;
                     margin: 0; padding: 0; list-style: none; }
        .gpc-go { position: relative; overflow: clip; display: inline-flex; align-items: center;
                     justify-content: center; gap: .45rem; inline-size: 100%; block-size: 2.85rem;
                     border-radius: 999px; border: 1px solid rgba(255,255,255,.44);
                     background: rgba(255,255,255,.15); color: #10142e; cursor: pointer;
                     font: 600 .88rem/1 system-ui; transition: translate .2s ease; }
        .gpc-go--primary { border: none; color: #fff; background: linear-gradient(120deg, #5470ff, #9a6cff); }
        .gpc-go:hover { translate: 0 -2px; }
        .gpc-go::after { content: ''; position: absolute; inset-block: -60%; inset-inline-start: -75%;
                     inline-size: 45%; background: linear-gradient(105deg, transparent, #ffffff80, transparent);
                     transform: skewX(-18deg); transition: inset-inline-start .6s ease; }
        .gpc-go:hover::after { inset-inline-start: 135%; }
        @media (prefers-reduced-motion: reduce) { .gpc-go::after { display: none; } }
        </style>
        SVELTE,
        ],
    ],
];
