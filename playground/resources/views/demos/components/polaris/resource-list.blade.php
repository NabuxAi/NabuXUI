{{--
    Polaris Resource List as the product shelf of a real shop: card rows with
    thumbnail, title, SKU/price metadata and status badge, a working kebab
    menu (duplicate really duplicates, delete really removes), an inline flash,
    and the Empty State with its CTA once the shelf is cleared.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $products = [
        ['id' => 1, 'name' => $say('Soy candle, minimal', 'شمع سویا مینیمال'), 'sku' => 'CD-220', 'price' => $say('$24.00', '۲۴۰٬۰۰۰ تومان'), 'qty' => 42, 'st' => 'active', 'c' => 'linear-gradient(140deg, #34d399, #008060)', 'l' => $say('S', 'ش')],
        ['id' => 2, 'name' => $say('Hand-stitched leather notebook', 'دفترچهٔ چرمی دست‌دوز'), 'sku' => 'NT-114', 'price' => $say('$38.00', '۳۸۰٬۰۰۰ تومان'), 'qty' => 17, 'st' => 'active', 'c' => 'linear-gradient(140deg, #c084fc, #7e22ce)', 'l' => $say('N', 'د')],
        ['id' => 3, 'name' => $say('Ceramic mug, 350 ml', 'ماگ سرامیکی ۳۵۰ میلی‌لیتر'), 'sku' => 'MG-350', 'price' => $say('$19.50', '۱۹۵٬۰۰۰ تومان'), 'qty' => 0, 'st' => 'draft', 'c' => 'linear-gradient(140deg, #60a5fa, #2c6ecb)', 'l' => $say('M', 'م')],
        ['id' => 4, 'name' => $say('Single-origin coffee, 250 g', 'قهوهٔ تک‌خاستگاه ۲۵۰ گرمی'), 'sku' => 'CF-250', 'price' => $say('$46.00', '۴۶۰٬۰۰۰ تومان'), 'qty' => 8, 'st' => 'active', 'c' => 'linear-gradient(140deg, #fbbf24, #b45309)', 'l' => $say('C', 'ق')],
    ];
    $prodsJson = e(json_encode($products));
    $copySuffix = e(json_encode($say(' (copy)', ' (تکثیر)')));
@endphp
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
    [x-cloak] { display: none !important; }
    .plrl-root {
        --plrl-surface: #FFFFFF; --plrl-raised: #F6F6F6; --plrl-text: #303030; --plrl-subdued: #616161;
        --plrl-border: #E3E3E3; --plrl-strong: #8A8A8A; --plrl-hover: color-mix(in srgb, var(--plrl-text) 4%, var(--plrl-surface));
        --plrl-green: #008060; --plrl-on-green: #FFFFFF; --plrl-green-hover: #004C3F;
        --plrl-tint: color-mix(in srgb, var(--plrl-green) 9%, var(--plrl-surface));
        --plrl-critical: #D72C0D;
        --plrl-focus: #005BD3;
        font-family: 'Inter', 'Vazirmatn', sans-serif;
        display: grid; gap: 1.5rem; justify-items: center;
    }
    html[data-theme="dark"] .plrl-root {
        --plrl-surface: #202020; --plrl-raised: #2B2B2B; --plrl-text: #F1F1F1; --plrl-subdued: #B5B5B5;
        --plrl-border: #454545; --plrl-strong: #8A8A8A;
        --plrl-green: #00A97F; --plrl-on-green: #08211A; --plrl-green-hover: #00BA93;
        --plrl-critical: #FF8D75;
    }
    @media (prefers-color-scheme: dark) {
        html:not([data-theme="light"]) .plrl-root {
            --plrl-surface: #202020; --plrl-raised: #2B2B2B; --plrl-text: #F1F1F1; --plrl-subdued: #B5B5B5;
            --plrl-border: #454545; --plrl-strong: #8A8A8A;
            --plrl-green: #00A97F; --plrl-on-green: #08211A; --plrl-green-hover: #00BA93;
            --plrl-critical: #FF8D75;
        }
    }
    .plrl-frame { position: relative; inline-size: min(100%, 34rem); border: 1px solid var(--plrl-border); border-radius: 12px; background: var(--plrl-surface); box-shadow: 0 1px 4px rgba(0, 0, 0, .07); }
    .plrl-head { display: flex; align-items: center; gap: .75rem; padding: .9rem 1rem; border-block-end: 1px solid var(--plrl-border); }
    .plrl-head b { font: 600 .95rem/1.3 Inter, system-ui, sans-serif; color: var(--plrl-text); }
    .plrl-head small { margin-inline-start: auto; font: 400 .78rem/1 Inter, system-ui, sans-serif; color: var(--plrl-subdued); }
    .plrl-item { position: relative; display: flex; align-items: center; gap: .8rem; padding: .8rem 1rem; }
    .plrl-item + .plrl-item { border-block-start: 1px solid var(--plrl-border); }
    .plrl-item:hover { background: var(--plrl-hover); }
    .plrl-media { flex: none; display: grid; place-items: center; inline-size: 2.9rem; aspect-ratio: 1; border-radius: 8px; color: #fff; font: 600 .95rem/1 Inter, system-ui, sans-serif; }
    .plrl-item[data-compact] { padding-block: .5rem; }
    .plrl-item[data-compact] .plrl-media { inline-size: 2rem; font-size: .7rem; }
    .plrl-body { min-inline-size: 0; }
    .plrl-body b { display: block; font: 600 .85rem/1.35 Inter, system-ui, sans-serif; color: var(--plrl-text); overflow: clip; text-overflow: ellipsis; white-space: nowrap; }
    .plrl-body small { display: block; margin-block-start: .1rem; font: 400 .74rem/1.4 Inter, system-ui, sans-serif; color: var(--plrl-subdued); font-variant-numeric: tabular-nums; }
    .plrl-badge { flex: none; padding: .16rem .55rem; border-radius: 999px; font: 500 .7rem/1.4 Inter, system-ui, sans-serif; background: color-mix(in srgb, var(--plrl-tone) 12%, var(--plrl-surface)); color: var(--plrl-tone); }
    .plrl-badge[data-tone="active"] { --plrl-tone: var(--plrl-green); }
    .plrl-badge[data-tone="draft"] { --plrl-tone: var(--plrl-subdued); }
    .plrl-kebab { flex: none; display: grid; place-items: center; inline-size: 1.9rem; aspect-ratio: 1; margin-inline-start: auto; border: none; border-radius: 8px; background: transparent; color: var(--plrl-subdued); cursor: pointer; }
    .plrl-kebab:hover { background: var(--plrl-hover); color: var(--plrl-text); }
    .plrl-menu { position: absolute; inset-inline-end: .8rem; inset-block-start: 70%; z-index: 6; min-inline-size: 9.5rem; padding: .35rem; border-radius: 10px; background: var(--plrl-surface); border: 1px solid var(--plrl-border); box-shadow: 0 10px 28px -10px rgba(0, 0, 0, .3); }
    .plrl-menu button { display: flex; inline-size: 100%; align-items: center; gap: .5rem; padding: .5rem .6rem; border: none; border-radius: 8px; background: transparent; color: var(--plrl-text); cursor: pointer; font: 400 .8rem/1.2 Inter, system-ui, sans-serif; text-align: start; }
    .plrl-menu button:hover { background: var(--plrl-hover); }
    .plrl-menu button[data-danger] { color: var(--plrl-critical); }
    .plrl-kebab:focus-visible, .plrl-menu button:focus-visible, .plrl-cta:focus-visible { outline: 2px solid var(--plrl-focus); outline-offset: 2px; }
    .plrl-empty { display: grid; justify-items: center; gap: .5rem; padding: 2rem 1rem 2.2rem; text-align: center; }
    .plrl-empty i { display: grid; place-items: center; inline-size: 2.8rem; aspect-ratio: 1; border-radius: 50%; background: var(--plrl-raised); color: var(--plrl-subdued); }
    .plrl-empty b { font: 600 .9rem/1.3 Inter, system-ui, sans-serif; color: var(--plrl-text); }
    .plrl-empty p { margin: 0; font: 400 .8rem/1.5 Inter, system-ui, sans-serif; color: var(--plrl-subdued); max-inline-size: 30ch; }
    .plrl-cta { margin-block-start: .35rem; display: inline-flex; align-items: center; gap: .45rem; block-size: 2.1rem; padding-inline: 1rem; border: none; border-radius: 8px; background: var(--plrl-green); color: var(--plrl-on-green); cursor: pointer; font: 500 .82rem/1 Inter, system-ui, sans-serif; }
    .plrl-cta:hover { background: var(--plrl-green-hover); }
    .plrl-flash { margin: 0; font: 400 .82rem/1.5 Inter, system-ui, sans-serif; color: var(--plrl-green); }
    .plrl-variants { inline-size: min(100%, 46rem); display: flex; flex-wrap: wrap; gap: 1.25rem 2rem; justify-content: center; align-items: flex-start; }
    .plrl-variants .plrl-frame { box-shadow: none; }
    .plrl-vcell { display: grid; gap: .55rem; justify-items: center; }
    .plrl-vcell > small { font: 500 .72rem/1 Inter, system-ui, sans-serif; color: var(--nx-text-muted); }
    :where(.nx-js) .pg:has(.plrl-root) .nx-data-table[data-nx-reveal] tbody tr {
        opacity: 1; translate: none;
    }
    @media (max-width: 480px) {
        .pg:has(.plrl-root) .nx-data-table :is(th, td) {
            white-space: normal; padding-inline: .5rem; overflow-wrap: break-word;
        }
        .pg:has(.plrl-root) pre code { white-space: pre-wrap; overflow-wrap: anywhere; }
    }
    @media (prefers-reduced-motion: reduce) {
        .plrl-root * { transition-duration: .01ms !important; }
    }
</style>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The shelf and its kebab menus', 'قفسه و منوهای سه‌نقطه‌اش') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Open a row menu: «Duplicate» really clones the product, «Delete» really removes it — clear the shelf and the Polaris Empty State steps in with its CTA.', 'منوی هر ردیف را باز کنید: «تکثیر» واقعاً محصول را شبیه می‌کند و «حذف» واقعاً برمی‌داردش — قفسه را خالی کنید تا حالت خالی پولاریس با دکمهٔ کنشش سر در بیاورد.') }}
        </p>
    </div>

    <div class="plrl-root" style="inline-size: 100%"
        x-data="{
            prods: {!! $prodsJson !!},
            open: null, flash: '', nextId: 5,
            copySuffix: {!! $copySuffix !!},
            fd(x) { return {{ $fa ? 'true' : 'false' }} ? String(x).replace(/[0-9]/g, d => '۰۱۲۳۴۵۶۷۸۹'[+d]) : String(x) },
            dup(i) {
                const p = this.prods[i];
                this.prods.splice(i + 1, 0, { ...p, id: this.nextId++, name: p.name + this.copySuffix, st: 'draft', qty: p.qty });
                this.open = null;
                this.say('{{ $say('A copy was created as draft', 'یک نسخه به‌صورت پیش‌نویس ساخته شد') }}');
            },
            del(i) {
                const p = this.prods[i];
                this.prods.splice(i, 1); this.open = null;
                this.say(p.name + ' {{ $say('deleted', 'حذف شد') }}');
            },
            restock() {
                this.prods.push({ id: this.nextId++, name: '{{ $say('Soy candle, minimal', 'شمع سویا مینیمال') }}', sku: 'CD-220', price: '{{ $say('$24.00', '۲۴۰٬۰۰۰ تومان') }}', qty: 42, st: 'active', c: 'linear-gradient(140deg, #34d399, #008060)', l: '{{ $say('S', 'ش') }}' });
                this.say('{{ $say('Starter product is back on the shelf', 'محصول اولیه به قفسه برگشت') }}');
            },
            say(m) { this.flash = m; clearTimeout(this.t); this.t = setTimeout(() => this.flash = '', 2600) },
        }">
        <div class="plrl-frame">
            <div class="plrl-head">
                <b>{{ $say('Products', 'محصولات') }}</b>
                <small x-text="fd(prods.length) + ' {{ $say('of 12 shown', 'از ۱۲ نمایش‌داده‌شده') }}'">{{ $say('4 of 12', '۴ از ۱۲') }}</small>
            </div>
            <template x-for="(p, i) in prods" :key="p.id">
                <div class="plrl-item">
                    <span class="plrl-media" x-bind:style="{ background: p.c }" x-text="p.l" aria-hidden="true"></span>
                    <span class="plrl-body">
                        <b x-text="p.name"></b>
                        <small x-text="'SKU: ' + p.sku + ' · ' + p.price + ' · ' + (p.qty > 0 ? fd(p.qty) + ' {{ $say('in stock', 'عدد موجود') }}' : '{{ $say('out of stock', 'ناموجود') }}')"></small>
                    </span>
                    <span class="plrl-badge" x-bind:data-tone="p.st" x-text="p.st === 'active' ? '{{ $say('Active', 'فعال') }}' : '{{ $say('Draft', 'پیش‌نویس') }}'"></span>
                    <button type="button" class="plrl-kebab" x-on:click="open = open === p.id ? null : p.id" x-bind:aria-expanded="open === p.id ? 'true' : 'false'" x-bind:aria-label="'{{ $say('Actions for', 'اقدام‌های') }} ' + p.name">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><circle cx="12" cy="5" r="1.7"/><circle cx="12" cy="12" r="1.7"/><circle cx="12" cy="19" r="1.7"/></svg>
                    </button>
                    <div class="plrl-menu" x-show="open === p.id" x-cloak x-on:click.outside="open = null" role="menu">
                        <button type="button" role="menuitem" x-on:click="say('{{ $say('Opening on the storefront', 'در فروشگاه باز می‌شود') }}'); open = null">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h6"/><path d="M15 3h6v6"/><path d="M10 14 21 3"/></svg>
                            {{ $say('View', 'مشاهده') }}
                        </button>
                        <button type="button" role="menuitem" x-on:click="dup(i)">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="9" y="9" width="12" height="12" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
                            {{ $say('Duplicate', 'تکثیر') }}
                        </button>
                        <button type="button" role="menuitem" data-danger x-on:click="del(i)">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 7h16M9 7V5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2m3 0-1 13a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 7"/></svg>
                            {{ $say('Delete', 'حذف') }}
                        </button>
                    </div>
                </div>
            </template>
            <div class="plrl-empty" x-show="!prods.length" x-cloak>
                <i aria-hidden="true"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg></i>
                <b>{{ $say('No products on the shelf', 'محصولی روی قفسه نیست') }}</b>
                <p>{{ $say('Everything you listed has been deleted. Bring the starter product back to keep browsing.', 'همهٔ اقلام فهرست حذف شدند. محصول اولیه را برگردانید تا ادامه بدهید.') }}</p>
                <button type="button" class="plrl-cta" x-on:click="restock()">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                    {{ $say('Add product', 'افزودن محصول') }}
                </button>
            </div>
        </div>
        <p class="plrl-flash" x-show="flash" x-cloak aria-live="polite" x-text="flash"></p>
    </div>
</section>

<section class="pg-box" style="justify-items: center">
    <div style="display: grid; gap: .35rem; justify-items: center; text-align: center">
        <h3 class="pg-title" style="font-size: var(--nx-text-lg)">{{ $say('Row variants', 'گونه‌های ردیف') }}</h3>
        <p style="margin: 0; max-width: 52ch; color: var(--nx-text-muted)">
            {{ $say('Without media the row densifies; compact mode trims the padding; low stock earns a warning count.', 'بدون رسانه، ردیف فشرده‌تر می‌شود؛ حالت جمع‌ونرم پدینگ را کم می‌کند و موجودی کم، شمارش هشدار می‌گیرد.') }}
        </p>
    </div>
    <div class="plrl-root plrl-variants">
        <div class="plrl-vcell">
            <div class="plrl-frame">
                <div class="plrl-item">
                    <span class="plrl-body">
                        <b>{{ $say('Gift card, digital', 'کارت هدیهٔ دیجیتال') }}</b>
                        <small>SKU: GC-99 · {{ $say('$10.00 · always in stock', '۱۰۰٬۰۰۰ تومان · همیشه موجود') }}</small>
                    </span>
                </div>
            </div>
            <small>no media</small>
        </div>
        <div class="plrl-vcell">
            <div class="plrl-frame">
                <div class="plrl-item" data-compact>
                    <span class="plrl-media" style="background: linear-gradient(140deg, #f472b6, #be123c)" aria-hidden="true">{{ $say('T', 'ت') }}</span>
                    <span class="plrl-body">
                        <b>{{ $say('Oversized tee, ecru', 'تیشرت اورسایز اِکرو') }}</b>
                        <small>TS-OS · {{ $say('$18.00', '۱۸۰٬۰۰۰ تومان') }}</small>
                    </span>
                    <span class="plrl-badge" style="--plrl-tone: #b45309" data-tone="active">{{ $say('3 left', '۳ عدد مانده') }}</span>
                </div>
            </div>
            <small>compact</small>
        </div>
    </div>
</section>
