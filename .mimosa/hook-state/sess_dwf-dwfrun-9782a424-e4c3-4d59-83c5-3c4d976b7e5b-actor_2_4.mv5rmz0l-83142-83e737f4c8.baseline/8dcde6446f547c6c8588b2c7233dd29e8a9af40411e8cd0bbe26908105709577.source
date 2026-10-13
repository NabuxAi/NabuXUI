<?php

/**
 * Demo manifest of the "polaris" group — Shopify Polaris, the admin design
 * system, rebuilt as faithful, interactive skins: the merchant-data Index
 * Table with its bulk-actions bar, the card-row Resource List, the chip-based
 * Filters bar, status Banners, Setting Toggle cards, the Page Header frame,
 * the Contextual Save Bar, the two-column Annotated Layout and the Color
 * Picker. Scenarios live at
 * resources/views/demos/components/polaris/{slug}.blade.php.
 * See App\Support\DemoCatalog for the entry shape.
 */
return [
    '__group' => ['fa' => 'شاپیفای — پولاریس', 'en' => 'Shopify Polaris'],

    'index-table' => [
        'title' => ['fa' => 'جدول شاخص با انتخاب گروهی', 'en' => 'Index Table'],
        'icon' => 'grid',
        'oneLiner' => [
            'fa' => 'جدول داده‌های ادمین شاپیفای: چک‌باکس هر ردیف، نوار عملیات گروهی که با اولین انتخاب بالا می‌آید و صفحه‌بندی چسبان پایین؛ مدیریت انبوه سفارش‌ها و محصولات همین‌جا شکل گرفت.',
            'en' => 'Shopify admin\'s merchant-data table: per-row checkboxes, a bulk-actions bar that appears on first selection, and pinned pagination — the birthplace of at-scale order and product management.',
        ],
        'js' => true,
        'docs' => 'https://polaris.shopify.com/components/index-table',
        'props' => [
            ['name' => 'selection', 'type' => 'state', 'default' => "'[]'", 'note' => [
                'fa' => 'آرایهٔ ردیف‌های انتخاب‌شده؛ با اولین عضو، نوار عملیات گروهی از پایین بالا می‌آید.',
                'en' => 'The selected-rows array; its first member raises the bulk-actions bar.',
            ]],
            ['name' => '--plit-green', 'type' => 'color', 'default' => "'#008060'", 'note' => [
                'fa' => 'سبز برند شاپیفای برای اکشن اصلی و نوار انتخاب گروهی؛ در تم تیره به #00A97F می‌رسد.',
                'en' => 'Shopify\'s brand green for the primary action and bulk bar; on dark it becomes #00A97F.',
            ]],
            ['name' => 'pagination', 'type' => 'behaviour', 'default' => "'pinned'", 'note' => [
                'fa' => 'صفحه‌بندی پایین جدول می‌نشیند و با اسکرول محتوا جابه‌جا نمی‌شود.',
                'en' => 'Pagination pins to the table footer and never scrolls away with the rows.',
            ]],
            ['name' => 'status', 'type' => 'badge', 'default' => "'paid · pending'", 'note' => [
                'fa' => 'نشان وضعیت هر ردیف با زمینهٔ ملایم همان تُن: پرداخت‌شده سبز، در انتظار کهربایی، مرجوع سرخ.',
                'en' => 'Per-row status badges on subdued tints of their own tone: green, amber, red.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
            <div class="plit" x-data="{ sel: [] }">
                <label><input type="checkbox" x-on:click="toggleAll()"> تمام</label>
                <table>
                    <tr><td><input type="checkbox" x-on:click="sel.push(1001)"></td><td>#۱۰۰۱</td>…</tr>
                </table>
                <div class="plit-bulk" x-show="sel.length">
                    <span x-text="sel.length + ' انتخاب شد'"></span>
                    <button>بسته‌بندی</button>
                </div>
            </div>

            <style>
            .plit { border-radius: 12px; background: #FFF; box-shadow: 0 1px 4px #00000012; }
            .plit-bulk { position: absolute; inset-inline: 12px; inset-block-end: 52px;
                         display: flex; gap: 8px; padding: 10px 14px; border-radius: 8px;
                         background: #1A3B34; color: #F1F7F4; animation: plit-rise .2s ease-out; }
            @keyframes plit-rise { from { translate: 0 100%; opacity: 0; } }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <div class="plit" x-data="{ sel: [] }">
                <label><input type="checkbox" x-on:click="toggleAll()"> All</label>
                <table>
                    <tr><td><input type="checkbox" x-on:click="sel.push(1001)"></td><td>#1001 · Istanbul</td>…</tr>
                </table>
                <div class="plit-bulk" x-show="sel.length">
                    <span x-text="sel.length + ' selected'"></span>
                    <button>Fulfill</button>
                </div>
            </div>

            <style>
            .plit { border-radius: 12px; background: #FFF; box-shadow: 0 1px 4px #00000012; }
            .plit-bulk { position: absolute; inset-inline: 12px; inset-block-end: 52px;
                         display: flex; gap: 8px; padding: 10px 14px; border-radius: 8px;
                         background: #1A3B34; color: #F1F7F4; animation: plit-rise .2s ease-out; }
            @keyframes plit-rise { from { translate: 0 100%; opacity: 0; } }
            </style>
            BLADE,
            ],
            'inertia' => <<<'BLADE'
                // resources/js/Pages/Orders/Index.tsx — the Inertia page mounts the
                // React snippet; app.tsx owns the CSS import and the Provider.
                import { Head, Link } from '@inertiajs/react';
                import IndexTable from '@/components/polaris/IndexTable';

                export default function OrdersIndex() {
                  return (
                    <>
                      <Head title="Orders" />
                      <Link href="/orders/create">Create order</Link>
                      {/* Fulfill/Archive in the bulk bar post via router, then reload props */}
                      <IndexTable />
                    </>
                  );
                }
                BLADE,
            'react' => <<<'BLADE'
                import { useState } from 'react';
                import '@nabuxai/ui-core/css';

                const ORDERS = [
                  { id: 1001, no: '#1001', cust: 'Elif Kaya · Istanbul', sum: '$86.00' },
                  { id: 1002, no: '#1002', cust: 'Jonas Weber · Berlin', sum: '$24.00' },
                  { id: 1003, no: '#1003', cust: 'Mia Rossi · Milan', sum: '$132.50' },
                ];

                export default function IndexTable() {
                  const [sel, setSel] = useState<number[]>([]);
                  const toggle = (id: number) =>
                    setSel((s) => (s.includes(id) ? s.filter((i) => i !== id) : [...s, id]));

                  return (
                    <div className="plit">
                      {sel.length > 0 && (
                        <div className="plit-bulk">
                          <span>{sel.length} selected</span>
                          <button onClick={() => setSel([])}>Fulfill</button>
                        </div>
                      )}
                      <table>
                        <tbody>
                          {ORDERS.map((r) => (
                            <tr key={r.id} data-picked={sel.includes(r.id) || undefined}>
                              <td>
                                <input type="checkbox" checked={sel.includes(r.id)} aria-label={`Select ${r.no}`} onChange={() => toggle(r.id)} />
                              </td>
                              <td>{r.no}</td><td>{r.cust}</td><td>{r.sum}</td>
                            </tr>
                          ))}
                        </tbody>
                      </table>
                      <style>{`
                        .plit { position: relative; border-radius: 12px; background: #FFF; box-shadow: 0 1px 4px #00000012; }
                        .plit-bulk { position: absolute; inset-inline: 12px; inset-block-end: 52px; display: flex; gap: 8px;
                                     padding: 10px 14px; border-radius: 8px; background: #1A3B34; color: #F1F7F4; }
                      `}</style>
                    </div>
                  );
                }
                BLADE,
            'vue' => <<<'BLADE'
                <!-- IndexTable.vue -->
                <script setup lang="ts">
                import { ref } from 'vue';
                import '@nabuxai/ui-core/css';

                interface Order { id: number; no: string; cust: string; sum: string }
                const orders: Order[] = [
                  { id: 1001, no: '#1001', cust: 'Elif Kaya · Istanbul', sum: '$86.00' },
                  { id: 1002, no: '#1002', cust: 'Jonas Weber · Berlin', sum: '$24.00' },
                ];
                const sel = ref<number[]>([]);
                const toggle = (id: number) =>
                  (sel.value = sel.value.includes(id) ? sel.value.filter((i) => i !== id) : [...sel.value, id]);
                </script>

                <template>
                  <div class="plit">
                    <div v-if="sel.length" class="plit-bulk">
                      <span>{{ sel.length }} selected</span>
                      <button @click="sel = []">Fulfill</button>
                    </div>
                    <table>
                      <tbody>
                        <tr v-for="r in orders" :key="r.id" :data-picked="sel.includes(r.id) ? '' : null">
                          <td><input type="checkbox" :checked="sel.includes(r.id)" :aria-label="`Select ${r.no}`" @change="toggle(r.id)" /></td>
                          <td>{{ r.no }}</td><td>{{ r.cust }}</td><td>{{ r.sum }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </template>

                <style scoped>
                .plit { position: relative; border-radius: 12px; background: #fff; box-shadow: 0 1px 4px #00000012; }
                .plit-bulk { position: absolute; inset-inline: 12px; inset-block-end: 52px; display: flex; gap: 8px;
                             padding: 10px 14px; border-radius: 8px; background: #1a3b34; color: #f1f7f4; }
                </style>
                BLADE,
            'svelte' => <<<'BLADE'
                <!-- IndexTable.svelte -->
                <script lang="ts">
                  import '@nabuxai/ui-core/css';

                  interface Order { id: number; no: string; cust: string; sum: string }
                  const orders: Order[] = [
                    { id: 1001, no: '#1001', cust: 'Elif Kaya · Istanbul', sum: '$86.00' },
                    { id: 1002, no: '#1002', cust: 'Jonas Weber · Berlin', sum: '$24.00' },
                  ];
                  let sel = $state<number[]>([]);
                  const toggle = (id: number) =>
                    (sel = sel.includes(id) ? sel.filter((i) => i !== id) : [...sel, id]);
                </script>

                <div class="plit">
                  {#if sel.length > 0}
                    <div class="plit-bulk">
                      <span>{sel.length} selected</span>
                      <button onclick={() => (sel = [])}>Fulfill</button>
                    </div>
                  {/if}
                  <table>
                    <tbody>
                      {#each orders as r (r.id)}
                        <tr data-picked={sel.includes(r.id) || undefined}>
                          <td><input type="checkbox" checked={sel.includes(r.id)} aria-label="Select {r.no}" onchange={() => toggle(r.id)} /></td>
                          <td>{r.no}</td><td>{r.cust}</td><td>{r.sum}</td>
                        </tr>
                      {/each}
                    </tbody>
                  </table>
                </div>

                <style>
                .plit { position: relative; border-radius: 12px; background: #fff; box-shadow: 0 1px 4px #00000012; }
                .plit-bulk { position: absolute; inset-inline: 12px; inset-block-end: 52px; display: flex; gap: 8px;
                             padding: 10px 14px; border-radius: 8px; background: #1a3b34; color: #f1f7f4; }
                </style>
                BLADE,
        ],
    ],

    'resource-list' => [
        'title' => ['fa' => 'فهرست منابع کارتی', 'en' => 'Resource List'],
        'icon' => 'layers',
        'oneLiner' => [
            'fa' => 'هر منبع یک ردیف کارتی با تامبنیل، عنوان، متادیتا و منوی اکشن؛ لیست‌کردن محصول و مشتری به زبان مادری ادمین شاپیفای.',
            'en' => 'Every resource is a card row with thumbnail, title, attributes and an actions menu — listing products and customers in the native tongue of Shopify admin.',
        ],
        'js' => true,
        'docs' => 'https://polaris.shopify.com/components/resource-list',
        'props' => [
            ['name' => 'media', 'type' => 'thumbnail', 'default' => "'48px'", 'note' => [
                'fa' => 'تامبنیل گرد ۴۸ پیکسل در آغاز هر ردیف؛ حذفش فهرست را فشرده می‌کند.',
                'en' => 'A rounded 48px thumbnail opening each row; dropping it densifies the list.',
            ]],
            ['name' => 'actions', 'type' => 'menu', 'default' => "'kebab'", 'note' => [
                'fa' => 'منوی سه‌نقطه در انتهای ردیف: ویرایش، تکثیر، حذف — بیرونش با لمس بیرون بسته می‌شود.',
                'en' => 'The row-end kebab menu: edit, duplicate, delete — outside taps close it.',
            ]],
            ['name' => 'badge', 'type' => 'tone', 'default' => "'success'", 'note' => [
                'fa' => 'نشان وضعیت منبع: فعال با تُن سبز، پیش‌نویس خاکستری.',
                'en' => 'The resource\'s status badge: green for active, gray for draft.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
            <ul class="plrl" x-data="{ open: null }">
                <li>
                    <img class="plrl-media" src="…" alt="">
                    <div><b>شمع سویا مینیمال</b><small>SKU: CS-220 · ۲۴۰٬۰۰۰ تومان</small></div>
                    <span class="plrl-badge">فعال</span>
                    <button class="plrl-kebab" x-on:click="open = open ? null : 1">⋯</button>
                    <div class="plrl-menu" x-show="open === 1" x-on:click.outside="open = null">
                        <button>تکثیر</button><button>حذف</button>
                    </div>
                </li>
            </ul>

            <style>
            .plrl { border-radius: 12px; background: #FFF; box-shadow: 0 1px 4px #00000012; }
            .plrl > li { display: flex; align-items: center; gap: 12px; padding: 12px 16px;
                         border-block-start: 1px solid #E3E3E3; }
            .plrl-media { inline-size: 48px; block-size: 48px; border-radius: 8px; }
            .plrl-kebab + .plrl-menu { position: absolute; inset-inline-end: 16px; border-radius: 8px;
                                       background: #FFF; box-shadow: 0 4px 16px #00000022; }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <ul class="plrl" x-data="{ open: null }">
                <li>
                    <img class="plrl-media" src="…" alt="">
                    <div><b>Soy candle, minimal</b><small>SKU: CS-220 · $24.00</small></div>
                    <span class="plrl-badge">Active</span>
                    <button class="plrl-kebab" x-on:click="open = open ? null : 1">⋯</button>
                    <div class="plrl-menu" x-show="open === 1" x-on:click.outside="open = null">
                        <button>Duplicate</button><button>Delete</button>
                    </div>
                </li>
            </ul>

            <style>
            .plrl { border-radius: 12px; background: #FFF; box-shadow: 0 1px 4px #00000012; }
            .plrl > li { display: flex; align-items: center; gap: 12px; padding: 12px 16px;
                         border-block-start: 1px solid #E3E3E3; }
            .plrl-media { inline-size: 48px; block-size: 48px; border-radius: 8px; }
            .plrl-kebab + .plrl-menu { position: absolute; inset-inline-end: 16px; border-radius: 8px;
                                       background: #FFF; box-shadow: 0 4px 16px #00000022; }
            </style>
            BLADE,
            ],
            'inertia' => <<<'BLADE'
                // resources/js/Pages/Products/Index.tsx — Inertia wraps the React
                // snippet; duplicate/delete can post through router and reload props.
                import { Head, Link, router } from '@inertiajs/react';
                import ResourceList from '@/components/polaris/ResourceList';

                export default function ProductsIndex() {
                  return (
                    <>
                      <Head title="Products" />
                      <Link href="/products/create">New product</Link>
                      {/* The kebab menu calls router.post(`/products/${id}/duplicate`) */}
                      <ResourceList />
                    </>
                  );
                }
                BLADE,
            'react' => <<<'BLADE'
                import { useState } from 'react';
                import '@nabuxai/ui-core/css';

                type Product = { id: number; name: string; sku: string; price: string; tone: 'active' | 'draft' };
                const START: Product[] = [
                  { id: 1, name: 'Soy candle, minimal', sku: 'CD-220', price: '$24.00', tone: 'active' },
                  { id: 2, name: 'Ceramic mug, 350 ml', sku: 'MG-350', price: '$19.50', tone: 'draft' },
                ];

                export default function ResourceList() {
                  const [prods, setProds] = useState(START);
                  const [open, setOpen] = useState<number | null>(null);
                  const dup = (p: Product) =>
                    setProds((l) => [...l, { ...p, id: Date.now(), name: `${p.name} (copy)`, tone: 'draft' }]);
                  const del = (id: number) => setProds((l) => l.filter((p) => p.id !== id));

                  return (
                    <ul className="plrl">
                      {prods.map((p) => (
                        <li key={p.id}>
                          <span className="plrl-body"><b>{p.name}</b><small>SKU: {p.sku} · {p.price}</small></span>
                          <span className="plrl-badge" data-tone={p.tone}>{p.tone === 'active' ? 'Active' : 'Draft'}</span>
                          <button className="plrl-kebab" aria-expanded={open === p.id} onClick={() => setOpen(open === p.id ? null : p.id)}>⋯</button>
                          {open === p.id && (
                            <div className="plrl-menu" role="menu">
                              <button onClick={() => { dup(p); setOpen(null); }}>Duplicate</button>
                              <button data-danger onClick={() => { del(p.id); setOpen(null); }}>Delete</button>
                            </div>
                          )}
                        </li>
                      ))}
                      <style>{`
                        .plrl { border-radius: 12px; background: #FFF; box-shadow: 0 1px 4px #00000012; }
                        .plrl > li { position: relative; display: flex; align-items: center; gap: 12px; padding: 12px 16px;
                                     border-block-start: 1px solid #E3E3E3; }
                        .plrl-kebab + .plrl-menu { position: absolute; inset-inline-end: 16px; border-radius: 8px;
                                                   background: #FFF; box-shadow: 0 4px 16px #00000022; }
                      `}</style>
                    </ul>
                  );
                }
                BLADE,
            'vue' => <<<'BLADE'
                <!-- ResourceList.vue -->
                <script setup lang="ts">
                import { ref } from 'vue';
                import '@nabuxai/ui-core/css';

                interface Product { id: number; name: string; sku: string; price: string; tone: 'active' | 'draft' }
                const prods = ref<Product[]>([
                  { id: 1, name: 'Soy candle, minimal', sku: 'CD-220', price: '$24.00', tone: 'active' },
                  { id: 2, name: 'Ceramic mug, 350 ml', sku: 'MG-350', price: '$19.50', tone: 'draft' },
                ]);
                const open = ref<number | null>(null);
                const dup = (p: Product) =>
                  prods.value.push({ ...p, id: Date.now(), name: `${p.name} (copy)`, tone: 'draft' });
                const del = (id: number) => (prods.value = prods.value.filter((p) => p.id !== id));
                </script>

                <template>
                  <ul class="plrl">
                    <li v-for="p in prods" :key="p.id">
                      <span class="plrl-body"><b>{{ p.name }}</b><small>SKU: {{ p.sku }} · {{ p.price }}</small></span>
                      <span class="plrl-badge" :data-tone="p.tone">{{ p.tone === 'active' ? 'Active' : 'Draft' }}</span>
                      <button class="plrl-kebab" :aria-expanded="open === p.id" @click="open = open === p.id ? null : p.id">⋯</button>
                      <div v-if="open === p.id" class="plrl-menu" role="menu">
                        <button @click="dup(p); open = null">Duplicate</button>
                        <button data-danger @click="del(p.id); open = null">Delete</button>
                      </div>
                    </li>
                  </ul>
                </template>

                <style scoped>
                .plrl { border-radius: 12px; background: #fff; box-shadow: 0 1px 4px #00000012; }
                .plrl > li { position: relative; display: flex; align-items: center; gap: 12px; padding: 12px 16px;
                             border-block-start: 1px solid #e3e3e3; }
                .plrl-kebab + .plrl-menu { position: absolute; inset-inline-end: 16px; border-radius: 8px;
                                           background: #fff; box-shadow: 0 4px 16px #00000022; }
                </style>
                BLADE,
            'svelte' => <<<'BLADE'
                <!-- ResourceList.svelte -->
                <script lang="ts">
                  import '@nabuxai/ui-core/css';

                  interface Product { id: number; name: string; sku: string; price: string; tone: 'active' | 'draft' }
                  let prods = $state<Product[]>([
                    { id: 1, name: 'Soy candle, minimal', sku: 'CD-220', price: '$24.00', tone: 'active' },
                    { id: 2, name: 'Ceramic mug, 350 ml', sku: 'MG-350', price: '$19.50', tone: 'draft' },
                  ]);
                  let open = $state<number | null>(null);
                  const dup = (p: Product) =>
                    prods.push({ ...p, id: Date.now(), name: `${p.name} (copy)`, tone: 'draft' });
                  const del = (id: number) => (prods = prods.filter((p) => p.id !== id));
                </script>

                <ul class="plrl">
                  {#each prods as p (p.id)}
                    <li>
                      <span class="plrl-body"><b>{p.name}</b><small>SKU: {p.sku} · {p.price}</small></span>
                      <span class="plrl-badge" data-tone={p.tone}>{p.tone === 'active' ? 'Active' : 'Draft'}</span>
                      <button class="plrl-kebab" aria-expanded={open === p.id} onclick={() => (open = open === p.id ? null : p.id)}>⋯</button>
                      {#if open === p.id}
                        <div class="plrl-menu" role="menu">
                          <button onclick={() => { dup(p); open = null; }}>Duplicate</button>
                          <button data-danger onclick={() => { del(p.id); open = null; }}>Delete</button>
                        </div>
                      {/if}
                    </li>
                  {/each}
                </ul>

                <style>
                .plrl { border-radius: 12px; background: #fff; box-shadow: 0 1px 4px #00000012; }
                .plrl > li { position: relative; display: flex; align-items: center; gap: 12px; padding: 12px 16px;
                             border-block-start: 1px solid #e3e3e3; }
                .plrl-kebab + .plrl-menu { position: absolute; inset-inline-end: 16px; border-radius: 8px;
                                           background: #fff; box-shadow: 0 4px 16px #00000022; }
                </style>
                BLADE,
        ],
    ],

    'filters' => [
        'title' => ['fa' => 'نوار فیلترها با چیپ', 'en' => 'Filters Bar'],
        'icon' => 'sliders',
        'oneLiner' => [
            'fa' => 'فیلترها بالای لیست به چیپ‌های قابل‌حذف تبدیل می‌شوند و «Clear all filters» همه را یک‌جا می‌زداید؛ همان نوار فیلتر گرید شاپیفای با سرچ، سیوویو و سورت یکپارچه.',
            'en' => 'Applied filters become removable chips above the list with a one-click \'Clear all filters\' — Shopify\'s grid toolbar with search, saved views and sorting in one bar.',
        ],
        'js' => true,
        'docs' => 'https://polaris.shopify.com/components/filters',
        'props' => [
            ['name' => 'query', 'type' => 'search', 'default' => "''", 'note' => [
                'fa' => 'جست‌وجوی زندهٔ نوار؛ با تایپ، فهرست همان لحظه فیلتر می‌شود.',
                'en' => 'The bar\'s live search; the list filters as you type.',
            ]],
            ['name' => 'chip', 'type' => 'removable', 'default' => "'×'", 'note' => [
                'fa' => 'هر فیلتر اعمال‌شده یک چیپ با ضربدر است؛ حذفش فیلتر را همان لحظه برمی‌دارد.',
                'en' => 'Every applied filter is a chip with an ×; removing it lifts the filter at once.',
            ]],
            ['name' => 'clear-all', 'type' => 'action', 'default' => "'one-click'", 'note' => [
                'fa' => '«پاک‌کردن همهٔ فیلترها» فقط وقتی چیپی هست ظاهر می‌شود و همه را یک‌جا می‌زداید.',
                'en' => '\'Clear all filters\' only appears while chips exist and sweeps them in one click.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
            <div class="plfl" x-data="{ q: '', chips: [] }">
                <div class="plfl-bar">
                    <input x-model="q" placeholder="جست‌وجوی سفارش">
                    <button x-on:click="panel = true">فیلتر</button>
                </div>
                <div class="plfl-chips">
                    <template x-for="c in chips"><span class="plfl-chip" x-text="c" x-on:click="drop(c)">×</span></template>
                    <button x-show="chips.length" x-on:click="chips = []">پاک‌کردن همهٔ فیلترها</button>
                </div>
            </div>

            <style>
            .plfl-bar { display: flex; gap: 8px; }
            .plfl-bar input { block-size: 36px; padding-inline: 12px; border: 1px solid #8A8A8A;
                              border-radius: 8px; background: #FFF; }
            .plfl-chip { display: inline-flex; align-items: center; gap: 6px; block-size: 28px;
                         padding-inline: 10px; border-radius: 8px; border: 1px solid #E3E3E3;
                         background: #FFF; }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <div class="plfl" x-data="{ q: '', chips: [] }">
                <div class="plfl-bar">
                    <input x-model="q" placeholder="Search orders">
                    <button x-on:click="panel = true">Filters</button>
                </div>
                <div class="plfl-chips">
                    <template x-for="c in chips"><span class="plfl-chip" x-text="c" x-on:click="drop(c)">×</span></template>
                    <button x-show="chips.length" x-on:click="chips = []">Clear all filters</button>
                </div>
            </div>

            <style>
            .plfl-bar { display: flex; gap: 8px; }
            .plfl-bar input { block-size: 36px; padding-inline: 12px; border: 1px solid #8A8A8A;
                              border-radius: 8px; background: #FFF; }
            .plfl-chip { display: inline-flex; align-items: center; gap: 6px; block-size: 28px;
                         padding-inline: 10px; border-radius: 8px; border: 1px solid #E3E3E3;
                         background: #FFF; }
            </style>
            BLADE,
            ],
            'inertia' => <<<'BLADE'
                // resources/js/Pages/Orders/Browse.tsx — the query lives in URL
                // search params so filters survive reloads and shareables.
                import { Head, usePage } from '@inertiajs/react';
                import FiltersBar from '@/components/polaris/FiltersBar';

                export default function OrdersBrowse() {
                  const { filters } = usePage().props; // { q: string, statuses: string[] }
                  return (
                    <>
                      <Head title="Orders" />
                      {/* The snippet reports chip changes; persist them per route */}
                      <FiltersBar initialQuery={filters.q} initialChips={filters.statuses} />
                    </>
                  );
                }
                BLADE,
            'react' => <<<'BLADE'
                import { useState } from 'react';
                import '@nabuxai/ui-core/css';

                const STATUSES = ['Paid', 'Pending', 'Refunded'] as const;

                export default function FiltersBar() {
                  const [q, setQ] = useState('');
                  const [chips, setChips] = useState<string[]>([]);
                  const clearAll = () => { setQ(''); setChips([]); };

                  return (
                    <div className="plfl">
                      <div className="plfl-bar">
                        <input value={q} onChange={(e) => setQ(e.target.value)} placeholder="Search orders" aria-label="Search orders" />
                        {STATUSES.map((s) => (
                          <button key={s} onClick={() => setChips((c) => (c.includes(s) ? c : [...c, s]))}>{s}</button>
                        ))}
                      </div>
                      <div className="plfl-chips">
                        {q.trim() !== '' && (
                          <span className="plfl-chip">Search: {q.trim()} <button aria-label="Remove search filter" onClick={() => setQ('')}>✕</button></span>
                        )}
                        {chips.map((c) => (
                          <span key={c} className="plfl-chip">
                            {c} <button aria-label={`Remove ${c}`} onClick={() => setChips((list) => list.filter((x) => x !== c))}>✕</button>
                          </span>
                        ))}
                        {(chips.length > 0 || q.trim() !== '') && <button className="plfl-clear" onClick={clearAll}>Clear all filters</button>}
                      </div>
                      <style>{`
                        .plfl-bar { display: flex; gap: 8px; }
                        .plfl-bar input { block-size: 36px; padding-inline: 12px; border: 1px solid #8A8A8A;
                                          border-radius: 8px; background: #FFF; }
                        .plfl-chip { display: inline-flex; align-items: center; gap: 6px; block-size: 28px;
                                     padding-inline: 10px; border-radius: 8px; border: 1px solid #E3E3E3; background: #FFF; }
                      `}</style>
                    </div>
                  );
                }
                BLADE,
            'vue' => <<<'BLADE'
                <!-- FiltersBar.vue -->
                <script setup lang="ts">
                import { ref } from 'vue';
                import '@nabuxai/ui-core/css';

                const STATUSES = ['Paid', 'Pending', 'Refunded'];
                const q = ref('');
                const chips = ref<string[]>([]);
                const add = (s: string) => { if (!chips.value.includes(s)) chips.value.push(s); };
                const drop = (c: string) => (chips.value = chips.value.filter((x) => x !== c));
                const clearAll = () => { q.value = ''; chips.value = []; };
                </script>

                <template>
                  <div class="plfl">
                    <div class="plfl-bar">
                      <input v-model="q" placeholder="Search orders" aria-label="Search orders" />
                      <button v-for="s in STATUSES" :key="s" @click="add(s)">{{ s }}</button>
                    </div>
                    <div class="plfl-chips">
                      <span v-if="q.trim()" class="plfl-chip">Search: {{ q.trim() }} <button aria-label="Remove search filter" @click="q = ''">✕</button></span>
                      <span v-for="c in chips" :key="c" class="plfl-chip">{{ c }} <button :aria-label="`Remove ${c}`" @click="drop(c)">✕</button></span>
                      <button v-if="chips.length || q.trim()" class="plfl-clear" @click="clearAll">Clear all filters</button>
                    </div>
                  </div>
                </template>

                <style scoped>
                .plfl-bar { display: flex; gap: 8px; }
                .plfl-bar input { block-size: 36px; padding-inline: 12px; border: 1px solid #8a8a8a;
                                  border-radius: 8px; background: #fff; }
                .plfl-chip { display: inline-flex; align-items: center; gap: 6px; block-size: 28px;
                             padding-inline: 10px; border-radius: 8px; border: 1px solid #e3e3e3; background: #fff; }
                </style>
                BLADE,
            'svelte' => <<<'BLADE'
                <!-- FiltersBar.svelte -->
                <script lang="ts">
                  import '@nabuxai/ui-core/css';

                  const STATUSES = ['Paid', 'Pending', 'Refunded'];
                  let q = $state('');
                  let chips = $state<string[]>([]);
                  const add = (s: string) => { if (!chips.includes(s)) chips.push(s); };
                  const drop = (c: string) => (chips = chips.filter((x) => x !== c));
                  const clearAll = () => { q = ''; chips = []; };
                </script>

                <div class="plfl">
                  <div class="plfl-bar">
                    <input bind:value={q} placeholder="Search orders" aria-label="Search orders" />
                    {#each STATUSES as s (s)}<button onclick={() => add(s)}>{s}</button>{/each}
                  </div>
                  <div class="plfl-chips">
                    {#if q.trim()}<span class="plfl-chip">Search: {q.trim()} <button aria-label="Remove search filter" onclick={() => (q = '')}>✕</button></span>{/if}
                    {#each chips as c (c)}<span class="plfl-chip">{c} <button aria-label="Remove {c}" onclick={() => drop(c)}>✕</button></span>{/each}
                    {#if chips.length || q.trim()}<button class="plfl-clear" onclick={clearAll}>Clear all filters</button>{/if}
                  </div>
                </div>

                <style>
                .plfl-bar { display: flex; gap: 8px; }
                .plfl-bar input { block-size: 36px; padding-inline: 12px; border: 1px solid #8a8a8a;
                                  border-radius: 8px; background: #fff; }
                .plfl-chip { display: inline-flex; align-items: center; gap: 6px; block-size: 28px;
                             padding-inline: 10px; border-radius: 8px; border: 1px solid #e3e3e3; background: #fff; }
                </style>
                BLADE,
        ],
    ],

    'banner' => [
        'title' => ['fa' => 'بنر وضعیت', 'en' => 'Banner'],
        'icon' => 'info',
        'oneLiner' => [
            'fa' => 'پیام‌های اطلاع‌رسانی، موفق و بحرانی با رنگ برند سبز شاپیفای، آیکون وضعیت، عنوان و امکان بستن؛ زبان رسمی شاپیفای برای خبر خوب و بد.',
            'en' => 'Informational, success and critical messages in Shopify green with a status icon, heading and dismiss — the official Polaris voice for good news and bad.',
        ],
        'js' => true,
        'docs' => 'https://polaris.shopify.com/components/banner',
        'props' => [
            ['name' => 'tone', 'type' => 'string', 'default' => "'info'", 'note' => [
                'fa' => 'info · success · warning · critical؛ هر تُن آیکن و زمینهٔ ملایم خودش را دارد.',
                'en' => 'info · success · warning · critical; each tone brings its icon and subdued tint.',
            ]],
            ['name' => 'dismiss', 'type' => 'button', 'default' => "'×'", 'note' => [
                'fa' => 'ضربدر بستن در انتهای بنر؛ بنر بسته‌شده با «بازگردانی» برمی‌گردد.',
                'en' => 'The banner-end close ×; a restore link brings dismissed banners back.',
            ]],
            ['name' => 'action', 'type' => 'link', 'default' => 'null', 'note' => [
                'fa' => 'یک کنش متنی زیر بدنه، مثل «اتصال مجدد درگاه».',
                'en' => 'One text action under the body, like «reconnect gateway».',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
            <div class="plbn" data-tone="success" x-data>
                <svg class="plbn-icon">✓</svg>
                <div>
                    <b>قالب ذخیره شد</b>
                    <p>تغییرات از ۹:۴۱ روی فروشگاه اعمال است.</p>
                </div>
                <button class="plbn-x" aria-label="بستن">×</button>
            </div>

            <style>
            .plbn { display: flex; gap: 12px; padding: 14px 16px; border-radius: 12px;
                    border: 1px solid #E3E3E3; background: #FFF; }
            .plbn[data-tone='success'] { color: #008060; background: #E3F1ED;
                                         border-color: color-mix(in srgb, #008060 20%, #FFF); }
            .plbn-x { margin-inline-start: auto; border: none; background: none; cursor: pointer; }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <div class="plbn" data-tone="success" x-data>
                <svg class="plbn-icon">✓</svg>
                <div>
                    <b>Template saved</b>
                    <p>The changes have been live on the storefront since 9:41.</p>
                </div>
                <button class="plbn-x" aria-label="Dismiss banner">×</button>
            </div>

            <style>
            .plbn { display: flex; gap: 12px; padding: 14px 16px; border-radius: 12px;
                    border: 1px solid #E3E3E3; background: #FFF; }
            .plbn[data-tone='success'] { color: #008060; background: #E3F1ED;
                                         border-color: color-mix(in srgb, #008060 20%, #FFF); }
            .plbn-x { margin-inline-start: auto; border: none; background: none; cursor: pointer; }
            </style>
            BLADE,
            ],
            'inertia' => <<<'BLADE'
                // resources/js/Pages/Dashboard.tsx — banners reflect flashes sent
                // by the backend; the snippet renders them and handles dismiss.
                import { Head, usePage } from '@inertiajs/react';
                import Banners from '@/components/polaris/Banners';

                export default function Dashboard() {
                  const { flashes } = usePage().props; // [{ tone: 'success', title: 'Template saved' }, …]
                  return (
                    <>
                      <Head title="Dashboard" />
                      <Banners items={flashes} />
                    </>
                  );
                }
                BLADE,
            'react' => <<<'BLADE'
                import { useState } from 'react';
                import '@nabuxai/ui-core/css';

                type Tone = 'info' | 'success' | 'warning' | 'critical';
                const COPY: Record<Tone, string> = {
                  info: 'A collaboration request arrived',
                  success: 'Notification template saved',
                  warning: 'Two products are running low',
                  critical: 'Payment gateway is not responding',
                };

                export default function Banners() {
                  const [open, setOpen] = useState<Record<Tone, boolean>>({ info: true, success: true, warning: true, critical: true });
                  const anyClosed = Object.values(open).some((v) => !v);

                  return (
                    <div className="plbn-root">
                      {(Object.keys(open) as Tone[]).map((tone) =>
                        open[tone] ? (
                          <div key={tone} className="plbn" data-tone={tone} role={tone === 'critical' ? 'alert' : 'status'}>
                            <div><b>{COPY[tone]}</b></div>
                            <button className="plbn-x" aria-label="Dismiss banner" onClick={() => setOpen({ ...open, [tone]: false })}>✕</button>
                          </div>
                        ) : null,
                      )}
                      {anyClosed && (
                        <button className="plbn-restore" onClick={() => setOpen({ info: true, success: true, warning: true, critical: true })}>
                          Restore dismissed banners
                        </button>
                      )}
                      <style>{`
                        .plbn { display: flex; gap: 12px; padding: 14px 16px; border-radius: 12px;
                                border: 1px solid #E3E3E3; background: #FFF; }
                        .plbn[data-tone='success'] { color: #008060; background: #E3F1ED;
                                                     border-color: color-mix(in srgb, #008060 20%, #FFF); }
                        .plbn-x { margin-inline-start: auto; border: none; background: none; cursor: pointer; }
                      `}</style>
                    </div>
                  );
                }
                BLADE,
            'vue' => <<<'BLADE'
                <!-- Banners.vue -->
                <script setup lang="ts">
                import { reactive, computed } from 'vue';
                import '@nabuxai/ui-core/css';

                type Tone = 'info' | 'success' | 'warning' | 'critical';
                const COPY: Record<Tone, string> = {
                  info: 'A collaboration request arrived',
                  success: 'Notification template saved',
                  warning: 'Two products are running low',
                  critical: 'Payment gateway is not responding',
                };
                const open = reactive<Record<Tone, boolean>>({ info: true, success: true, warning: true, critical: true });
                const anyClosed = computed(() => Object.values(open).some((v) => !v));
                const restore = () => Object.assign(open, { info: true, success: true, warning: true, critical: true });
                </script>

                <template>
                  <div class="plbn-root">
                    <template v-for="tone in (Object.keys(open) as Tone[])" :key="tone">
                      <div v-if="open[tone]" class="plbn" :data-tone="tone" :role="tone === 'critical' ? 'alert' : 'status'">
                        <div><b>{{ COPY[tone] }}</b></div>
                        <button class="plbn-x" aria-label="Dismiss banner" @click="open[tone] = false">✕</button>
                      </div>
                    </template>
                    <button v-if="anyClosed" class="plbn-restore" @click="restore">Restore dismissed banners</button>
                  </div>
                </template>

                <style scoped>
                .plbn { display: flex; gap: 12px; padding: 14px 16px; border-radius: 12px;
                        border: 1px solid #e3e3e3; background: #fff; }
                .plbn[data-tone='success'] { color: #008060; background: #e3f1ed;
                                             border-color: color-mix(in srgb, #008060 20%, #fff); }
                .plbn-x { margin-inline-start: auto; border: none; background: none; cursor: pointer; }
                </style>
                BLADE,
            'svelte' => <<<'BLADE'
                <!-- Banners.svelte -->
                <script lang="ts">
                  import '@nabuxai/ui-core/css';

                  type Tone = 'info' | 'success' | 'warning' | 'critical';
                  const COPY: Record<Tone, string> = {
                    info: 'A collaboration request arrived',
                    success: 'Notification template saved',
                    warning: 'Two products are running low',
                    critical: 'Payment gateway is not responding',
                  };
                  let open = $state<Record<Tone, boolean>>({ info: true, success: true, warning: true, critical: true });
                  const anyClosed = $derived(Object.values(open).some((v) => !v));
                  const restore = () => Object.assign(open, { info: true, success: true, warning: true, critical: true });
                </script>

                <div class="plbn-root">
                  {#each Object.keys(open) as tone (tone)}
                    {#if open[tone as Tone]}
                      <div class="plbn" data-tone={tone} role={tone === 'critical' ? 'alert' : 'status'}>
                        <div><b>{COPY[tone as Tone]}</b></div>
                        <button class="plbn-x" aria-label="Dismiss banner" onclick={() => (open[tone as Tone] = false)}>✕</button>
                      </div>
                    {/if}
                  {/each}
                  {#if anyClosed}<button class="plbn-restore" onclick={restore}>Restore dismissed banners</button>{/if}
                </div>

                <style>
                .plbn { display: flex; gap: 12px; padding: 14px 16px; border-radius: 12px;
                        border: 1px solid #e3e3e3; background: #fff; }
                .plbn[data-tone='success'] { color: #008060; background: #e3f1ed;
                                             border-color: color-mix(in srgb, #008060 20%, #fff); }
                .plbn-x { margin-inline-start: auto; border: none; background: none; cursor: pointer; }
                </style>
                BLADE,
        ],
    ],

    'setting-toggle' => [
        'title' => ['fa' => 'کارت کلید تنظیمات', 'en' => 'Setting Toggle'],
        'icon' => 'settings',
        'oneLiner' => [
            'fa' => 'هر تنظیم یک کارت کامل است: توضیح وضعیت فعلی، متن راهنما و یک دکمه/کلید روشن‌وخاموش که متن کارت را هم عوض می‌کند؛ فرم تنظیمات شاپیفای همین‌طور نفس می‌کشد.',
            'en' => 'Every setting is a whole card: a description of the current state, helper text, and one toggle that rewrites the card copy as it flips — how Shopify settings pages breathe.',
        ],
        'js' => true,
        'docs' => 'https://polaris.shopify.com/components/setting-toggle',
        'props' => [
            ['name' => 'enabled', 'type' => 'boolean', 'default' => "'false'", 'note' => [
                'fa' => 'وضعیت تنظیم؛ برخورداندازی، توضیح و متن راهنمای کارت را هم بازنویسی می‌کند.',
                'en' => 'The setting\'s state; flipping it rewrites the card\'s copy as well as the toggle.',
            ]],
            ['name' => 'control', 'type' => 'switch|link', 'default' => "'switch'", 'note' => [
                'fa' => 'سوییچ نوین پولاریس یا دکمهٔ متنی کلاسیک «روشن/خاموش» در انتهای کارت.',
                'en' => 'Polaris\'s modern switch or the classic text button «on/off» at the card end.',
            ]],
            ['name' => 'disabled', 'type' => 'state', 'default' => "'false'", 'note' => [
                'fa' => 'کارت قفل‌شده با کلید کم‌رنگ و یادداشت «در دسترس نیست».',
                'en' => 'A locked card with a faded control and an "unavailable" note.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
            <div class="plst" x-data="{ on: false }">
                <div>
                    <b>پیگیری سفارش با پیامک</b>
                    <p x-text="on ? 'فعال — مشتریان پیامک وضعیت می‌گیرند.' : 'غیرفعال'"></p>
                </div>
                <button class="plst-switch" x-on:click="on = !on"
                        x-bind:data-on="on" role="switch" x-bind:aria-checked="on"><i></i></button>
            </div>

            <style>
            .plst { display: flex; align-items: center; gap: 16px; padding: 16px;
                    border-radius: 12px; background: #FFF; box-shadow: 0 1px 4px #00000012; }
            .plst-switch { inline-size: 40px; block-size: 24px; border-radius: 999px;
                           background: #8A8A8A; border: none; cursor: pointer; }
            .plst-switch[data-on='true'] { background: #008060; }
            .plst-switch i { display: block; inline-size: 20px; aspect-ratio: 1; border-radius: 50%;
                             background: #FFF; translate: 0 0; transition: translate .15s; }
            .plst-switch[data-on='true'] i { translate: 16px 0; }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <div class="plst" x-data="{ on: false }">
                <div>
                    <b>Order tracking by SMS</b>
                    <p x-text="on ? 'On — customers get a text at every step.' : 'Off'"></p>
                </div>
                <button class="plst-switch" x-on:click="on = !on"
                        x-bind:data-on="on" role="switch" x-bind:aria-checked="on"><i></i></button>
            </div>

            <style>
            .plst { display: flex; align-items: center; gap: 16px; padding: 16px;
                    border-radius: 12px; background: #FFF; box-shadow: 0 1px 4px #00000012; }
            .plst-switch { inline-size: 40px; block-size: 24px; border-radius: 999px;
                           background: #8A8A8A; border: none; cursor: pointer; }
            .plst-switch[data-on='true'] { background: #008060; }
            .plst-switch i { display: block; inline-size: 20px; aspect-ratio: 1; border-radius: 50%;
                             background: #FFF; translate: 0 0; transition: translate .15s; }
            .plst-switch[data-on='true'] i { translate: 16px 0; }
            </style>
            BLADE,
            ],
            'inertia' => <<<'BLADE'
                // resources/js/Pages/Settings/Notifications.tsx — the toggle posts
                // on flip; Inertia keeps `settings` fresh after each patch.
                import { Head, router } from '@inertiajs/react';
                import SettingToggle from '@/components/polaris/SettingToggle';

                export default function SettingsNotifications() {
                  return (
                    <>
                      <Head title="Notification settings" />
                      {/* onValueChange={(v) => router.patch('/settings/notifications', v)} */}
                      <SettingToggle />
                    </>
                  );
                }
                BLADE,
            'react' => <<<'BLADE'
                import { useState } from 'react';
                import '@nabuxai/ui-core/css';

                export default function SettingToggle() {
                  const [on, setOn] = useState(false);

                  return (
                    <div className="plst">
                      <div>
                        <b>Order tracking by SMS</b>
                        <p>{on ? 'On — customers get the tracking link the moment an order ships.' : 'Off — customers only get emails.'}</p>
                      </div>
                      <button
                        className="plst-switch" role="switch" aria-checked={on} aria-label="Order tracking by SMS"
                        data-on={on || undefined} onClick={() => setOn(!on)}
                      ><i /></button>
                      <style>{`
                        .plst { display: flex; align-items: center; gap: 16px; padding: 16px;
                                border-radius: 12px; background: #FFF; box-shadow: 0 1px 4px #00000012; }
                        .plst-switch { inline-size: 40px; block-size: 24px; border-radius: 999px;
                                       background: #8A8A8A; border: none; cursor: pointer; }
                        .plst-switch[data-on] { background: #008060; }
                        .plst-switch i { display: block; inline-size: 20px; aspect-ratio: 1; border-radius: 50%;
                                         background: #FFF; translate: 0 0; transition: translate .15s; }
                        .plst-switch[data-on] i { translate: 16px 0; }
                      `}</style>
                    </div>
                  );
                }
                BLADE,
            'vue' => <<<'BLADE'
                <!-- SettingToggle.vue -->
                <script setup lang="ts">
                import { ref } from 'vue';
                import '@nabuxai/ui-core/css';

                const on = ref(false);
                </script>

                <template>
                  <div class="plst">
                    <div>
                      <b>Order tracking by SMS</b>
                      <p>{{ on ? 'On — customers get the tracking link the moment an order ships.' : 'Off — customers only get emails.' }}</p>
                    </div>
                    <button
                      class="plst-switch" role="switch" :aria-checked="on" aria-label="Order tracking by SMS"
                      :data-on="on ? '' : null" @click="on = !on"
                    ><i /></button>
                  </div>
                </template>

                <style scoped>
                .plst { display: flex; align-items: center; gap: 16px; padding: 16px;
                        border-radius: 12px; background: #fff; box-shadow: 0 1px 4px #00000012; }
                .plst-switch { inline-size: 40px; block-size: 24px; border-radius: 999px;
                               background: #8a8a8a; border: none; cursor: pointer; }
                .plst-switch[data-on] { background: #008060; }
                .plst-switch i { display: block; inline-size: 20px; aspect-ratio: 1; border-radius: 50%;
                                 background: #fff; translate: 0 0; transition: translate .15s; }
                .plst-switch[data-on] i { translate: 16px 0; }
                </style>
                BLADE,
            'svelte' => <<<'BLADE'
                <!-- SettingToggle.svelte -->
                <script lang="ts">
                  import '@nabuxai/ui-core/css';

                  let on = $state(false);
                </script>

                <div class="plst">
                  <div>
                    <b>Order tracking by SMS</b>
                    <p>{on ? 'On — customers get the tracking link the moment an order ships.' : 'Off — customers only get emails.'}</p>
                  </div>
                  <button
                    class="plst-switch" role="switch" aria-checked={on} aria-label="Order tracking by SMS"
                    data-on={on || undefined} onclick={() => (on = !on)}
                  ><i /></button>
                </div>

                <style>
                .plst { display: flex; align-items: center; gap: 16px; padding: 16px;
                        border-radius: 12px; background: #fff; box-shadow: 0 1px 4px #00000012; }
                .plst-switch { inline-size: 40px; block-size: 24px; border-radius: 999px;
                               background: #8a8a8a; border: none; cursor: pointer; }
                .plst-switch[data-on] { background: #008060; }
                .plst-switch i { display: block; inline-size: 20px; aspect-ratio: 1; border-radius: 50%;
                                 background: #fff; translate: 0 0; transition: translate .15s; }
                .plst-switch[data-on] i { translate: 16px 0; }
                </style>
                BLADE,
        ],
    ],

    'page-header' => [
        'title' => ['fa' => 'سربرگ صفحهٔ ادمین', 'en' => 'Page Header'],
        'icon' => 'home',
        'oneLiner' => [
            'fa' => 'تایتل درشت با اکشن اولیه سبز، اکشن‌های ثانویه و breadcrumb بالای سر؛ هر صفحه در ادمین شاپیفای با همین قاب آغاز می‌شود.',
            'en' => 'An oversized title with a green primary action, secondary actions and breadcrumbs up top — the standard opening frame of every Shopify admin page.',
        ],
        'js' => true,
        'docs' => 'https://polaris.shopify.com/components/page-header',
        'props' => [
            ['name' => 'breadcrumbs', 'type' => 'trail', 'default' => "'[]'", 'note' => [
                'fa' => 'ردپای بالای عنوان؛ نسخهٔ عمق‌دار با فلش بازگشت جایگزین می‌شود.',
                'en' => 'The trail above the title; a back-arrow variant replaces it on deep pages.',
            ]],
            ['name' => 'primary-action', 'type' => 'button', 'default' => "'green'", 'note' => [
                'fa' => 'اکشن اولیه با سبز برند #008060 در انتهای ردیف عنوان.',
                'en' => 'The primary action in brand green #008060 at the title row\'s end.',
            ]],
            ['name' => 'pagination', 'type' => 'inline', 'default' => 'null', 'note' => [
                'fa' => 'صفحه‌بندی درون‌سربرگی: «۳۲ از ۱۲۸» با دو دکمهٔ فلش، برای رفت‌وآمد میان رکوردها.',
                'en' => 'In-header pagination: «32 of 128» with two arrow buttons for stepping records.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
            <header class="plph">
                <nav class="plph-crumbs"><a>خانه</a><a>فروشگاه</a><span>سفارش‌ها</span></nav>
                <div class="plph-row">
                    <h1>سفارش‌ها</h1>
                    <div class="plph-actions">
                        <button data-secondary>صادرات</button>
                        <button data-primary>ایجاد سفارش</button>
                    </div>
                </div>
            </header>

            <style>
            .plph { display: grid; gap: 8px; padding: 16px; }
            .plph h1 { margin: 0; font: 600 24px/1.2 Inter, system-ui; color: #303030; }
            .plph-actions { display: flex; gap: 8px; margin-inline-start: auto; }
            .plph [data-primary] { block-size: 36px; padding-inline: 16px; border: none;
                                   border-radius: 8px; background: #008060; color: #FFF; }
            .plph [data-secondary] { background: #FFF; border: 1px solid #8A8A8A; border-radius: 8px; }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <header class="plph">
                <nav class="plph-crumbs"><a>Home</a><a>Shop</a><span>Orders</span></nav>
                <div class="plph-row">
                    <h1>Orders</h1>
                    <div class="plph-actions">
                        <button data-secondary>Export</button>
                        <button data-primary>Create order</button>
                    </div>
                </div>
            </header>

            <style>
            .plph { display: grid; gap: 8px; padding: 16px; }
            .plph h1 { margin: 0; font: 600 24px/1.2 Inter, system-ui; color: #303030; }
            .plph-actions { display: flex; gap: 8px; margin-inline-start: auto; }
            .plph [data-primary] { block-size: 36px; padding-inline: 16px; border: none;
                                   border-radius: 8px; background: #008060; color: #FFF; }
            .plph [data-secondary] { background: #FFF; border: 1px solid #8A8A8A; border-radius: 8px; }
            </style>
            BLADE,
            ],
            'inertia' => <<<'BLADE'
                // resources/js/Pages/Orders/Index.tsx — on a real page the crumbs
                // become Inertia Links; the header itself is the React snippet.
                import { Head, Link } from '@inertiajs/react';
                import PageHeader from '@/components/polaris/PageHeader';

                export default function OrdersIndex() {
                  return (
                    <>
                      <Head title="Orders" />
                      {/* Swap the snippet's crumb <a>s for <Link href="/shop">Shop</Link> */}
                      <PageHeader />
                    </>
                  );
                }
                BLADE,
            'react' => <<<'BLADE'
                import { useState } from 'react';
                import '@nabuxai/ui-core/css';

                export default function PageHeader() {
                  const [count, setCount] = useState(32);
                  const [menu, setMenu] = useState(false);

                  return (
                    <header className="plph">
                      <nav className="plph-crumbs" aria-label="Breadcrumbs">
                        <a href="#">Home</a><a href="#">Shop</a><span aria-current="page">Orders</span>
                      </nav>
                      <div className="plph-row">
                        <h1>Orders <span className="plph-count">{count}</span></h1>
                        <div className="plph-actions">
                          <button data-secondary>Export</button>
                          <button data-primary onClick={() => setCount(count + 1)}>Create order</button>
                          <button className="plph-kebab" aria-expanded={menu} aria-label="More actions" onClick={() => setMenu(!menu)}>⋯</button>
                        </div>
                      </div>
                      {menu && (
                        <div className="plph-menu" role="menu">
                          <button onClick={() => setMenu(false)}>View all orders</button>
                          <button onClick={() => setMenu(false)}>Draft orders</button>
                        </div>
                      )}
                      <style>{`
                        .plph { display: grid; gap: 8px; padding: 16px; }
                        .plph h1 { margin: 0; font: 600 24px/1.2 Inter, system-ui; color: #303030; }
                        .plph-actions { display: flex; gap: 8px; margin-inline-start: auto; }
                        .plph [data-primary] { block-size: 36px; padding-inline: 16px; border: none;
                                               border-radius: 8px; background: #008060; color: #FFF; }
                        .plph [data-secondary] { background: #FFF; border: 1px solid #8A8A8A; border-radius: 8px; }
                      `}</style>
                    </header>
                  );
                }
                BLADE,
            'vue' => <<<'BLADE'
                <!-- PageHeader.vue -->
                <script setup lang="ts">
                import { ref } from 'vue';
                import '@nabuxai/ui-core/css';

                const count = ref(32);
                const menu = ref(false);
                </script>

                <template>
                  <header class="plph">
                    <nav class="plph-crumbs" aria-label="Breadcrumbs">
                      <a href="#">Home</a><a href="#">Shop</a><span aria-current="page">Orders</span>
                    </nav>
                    <div class="plph-row">
                      <h1>Orders <span class="plph-count">{{ count }}</span></h1>
                      <div class="plph-actions">
                        <button data-secondary>Export</button>
                        <button data-primary @click="count++">Create order</button>
                        <button class="plph-kebab" :aria-expanded="menu" aria-label="More actions" @click="menu = !menu">⋯</button>
                      </div>
                    </div>
                    <div v-if="menu" class="plph-menu" role="menu">
                      <button @click="menu = false">View all orders</button>
                      <button @click="menu = false">Draft orders</button>
                    </div>
                  </header>
                </template>

                <style scoped>
                .plph { display: grid; gap: 8px; padding: 16px; }
                .plph h1 { margin: 0; font: 600 24px/1.2 Inter, system-ui; color: #303030; }
                .plph-actions { display: flex; gap: 8px; margin-inline-start: auto; }
                .plph [data-primary] { block-size: 36px; padding-inline: 16px; border: none;
                                       border-radius: 8px; background: #008060; color: #fff; }
                .plph [data-secondary] { background: #fff; border: 1px solid #8a8a8a; border-radius: 8px; }
                </style>
                BLADE,
            'svelte' => <<<'BLADE'
                <!-- PageHeader.svelte -->
                <script lang="ts">
                  import '@nabuxai/ui-core/css';

                  let count = $state(32);
                  let menu = $state(false);
                </script>

                <header class="plph">
                  <nav class="plph-crumbs" aria-label="Breadcrumbs">
                    <a href="#/">Home</a><a href="#/shop">Shop</a><span aria-current="page">Orders</span>
                  </nav>
                  <div class="plph-row">
                    <h1>Orders <span class="plph-count">{count}</span></h1>
                    <div class="plph-actions">
                      <button data-secondary>Export</button>
                      <button data-primary onclick={() => count++}>Create order</button>
                      <button class="plph-kebab" aria-expanded={menu} aria-label="More actions" onclick={() => (menu = !menu)}>⋯</button>
                    </div>
                  </div>
                  {#if menu}
                    <div class="plph-menu" role="menu">
                      <button onclick={() => (menu = false)}>View all orders</button>
                      <button onclick={() => (menu = false)}>Draft orders</button>
                    </div>
                  {/if}
                </header>

                <style>
                .plph { display: grid; gap: 8px; padding: 16px; }
                .plph h1 { margin: 0; font: 600 24px/1.2 Inter, system-ui; color: #303030; }
                .plph-actions { display: flex; gap: 8px; margin-inline-start: auto; }
                .plph [data-primary] { block-size: 36px; padding-inline: 16px; border: none;
                                       border-radius: 8px; background: #008060; color: #fff; }
                .plph [data-secondary] { background: #fff; border: 1px solid #8a8a8a; border-radius: 8px; }
                </style>
                BLADE,
        ],
    ],

    'contextual-save-bar' => [
        'title' => ['fa' => 'نوار ذخیرهٔ زمینه‌ای', 'en' => 'Contextual Save Bar'],
        'icon' => 'check-circle',
        'oneLiner' => [
            'fa' => 'با اولین ویرایش فرم، نوار تیره‌ای با Discard و Save از بالای صفحه بالا می‌آید و وضعیت ذخیره‌نشده را لو می‌دهد؛ معروف‌ترین امضای فرم‌های شاپیفای.',
            'en' => 'The moment a form is edited, a dark bar slides in from the top with Discard and Save plus dirty-state status — Shopify\'s most famous form mechanic.',
        ],
        'js' => true,
        'docs' => 'https://polaris.shopify.com/components/contextual-save-bar',
        'props' => [
            ['name' => 'dirty', 'type' => 'state', 'default' => "'false'", 'note' => [
                'fa' => 'اولین کلید تایپ، فرم را «کثیف» می‌کند و نوار تیره را از لبهٔ بالا می‌کشد بیرون.',
                'en' => 'The first keystroke makes the form dirty and drags the dark bar out of the top edge.',
            ]],
            ['name' => 'save', 'type' => 'action', 'default' => "'primary'", 'note' => [
                'fa' => 'ذخیره با سه حالت: آماده، «در حال ذخیره…» با اسپینر، و تیک سبز موفقیت.',
                'en' => 'Save has three states: ready, «saving…» with a spinner, and a green success tick.',
            ]],
            ['name' => 'discard', 'type' => 'action', 'default' => "'plain'", 'note' => [
                'fa' => 'دور انداختن، فیلدها را به مقدار نخستین برمی‌گرداند و نوار را جمع می‌کند.',
                'en' => 'Discard reverts the fields to their originals and collapses the bar.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
            <div class="plsb" x-data="{ dirty: false, saving: false }">
                <div class="plsb-bar" x-show="dirty" x-transition>
                    <span x-text="saving ? 'در حال ذخیره…' : 'تغییرات ذخیره‌نشده'"></span>
                    <button x-on:click="discard()">دور انداختن</button>
                    <button data-primary x-on:click="save()">ذخیره</button>
                </div>
                <input x-on:input="dirty = true">
            </div>

            <style>
            .plsb { position: relative; }
            .plsb-bar { position: absolute; inset-inline: 0; inset-block-start: 0; z-index: 5;
                        display: flex; align-items: center; gap: 12px; padding: 10px 16px;
                        background: #1A1A1A; color: #F1F1F1; border-radius: 12px 12px 0 0; }
            .plsb-bar [data-primary] { background: #008060; color: #FFF; border-radius: 8px; }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <div class="plsb" x-data="{ dirty: false, saving: false }">
                <div class="plsb-bar" x-show="dirty" x-transition>
                    <span x-text="saving ? 'Saving…' : 'Unsaved changes'"></span>
                    <button x-on:click="discard()">Discard</button>
                    <button data-primary x-on:click="save()">Save</button>
                </div>
                <input x-on:input="dirty = true">
            </div>

            <style>
            .plsb { position: relative; }
            .plsb-bar { position: absolute; inset-inline: 0; inset-block-start: 0; z-index: 5;
                        display: flex; align-items: center; gap: 12px; padding: 10px 16px;
                        background: #1A1A1A; color: #F1F1F1; border-radius: 12px 12px 0 0; }
            .plsb-bar [data-primary] { background: #008060; color: #FFF; border-radius: 8px; }
            </style>
            BLADE,
            ],
            'inertia' => <<<'BLADE'
                // resources/js/Pages/Products/Edit.tsx — Save patches the record;
                // Discard re-fetches props from the backend snapshot.
                import { Head, router } from '@inertiajs/react';
                import ContextualSaveBar from '@/components/polaris/ContextualSaveBar';

                export default function ProductEdit() {
                  return (
                    <>
                      <Head title="Edit product" />
                      {/* onSave={(data) => router.patch(`/products/${id}`, data)} */}
                      <ContextualSaveBar />
                    </>
                  );
                }
                BLADE,
            'react' => <<<'BLADE'
                import { useRef, useState } from 'react';
                import '@nabuxai/ui-core/css';

                export default function ContextualSaveBar() {
                  const [name, setName] = useState('Soy candle, minimal');
                  const [saving, setSaving] = useState(false);
                  const [saved, setSaved] = useState(false);
                  const snap = useRef('Soy candle, minimal');
                  const dirty = name !== snap.current;
                  const open = dirty || saving || saved;

                  const save = () => {
                    setSaving(true);
                    setTimeout(() => {           // pretend PATCH, then refresh the snapshot
                      snap.current = name; setSaving(false); setSaved(true);
                      setTimeout(() => setSaved(false), 900);
                    }, 900);
                  };

                  return (
                    <div className="plsb">
                      {open && (
                        <div className="plsb-bar" role="status">
                          <span>{saved ? 'Saved' : saving ? 'Saving…' : 'Unsaved changes'}</span>
                          <button className="plsb-discard" disabled={saving} onClick={() => setName(snap.current)}>Discard</button>
                          <button data-primary disabled={saving || !dirty} onClick={save}>Save</button>
                        </div>
                      )}
                      <input value={name} onChange={(e) => setName(e.target.value)} aria-label="Product title" />
                      <style>{`
                        .plsb { position: relative; display: grid; gap: 12px; }
                        .plsb-bar { position: absolute; inset-inline: 0; inset-block-start: 0; z-index: 5; display: flex; align-items: center;
                                    gap: 12px; padding: 10px 16px; background: #1A1A1A; color: #F1F1F1; border-radius: 12px 12px 0 0; }
                        .plsb-bar [data-primary] { background: #008060; color: #FFF; border-radius: 8px; }
                      `}</style>
                    </div>
                  );
                }
                BLADE,
            'vue' => <<<'BLADE'
                <!-- ContextualSaveBar.vue -->
                <script setup lang="ts">
                import { ref, computed } from 'vue';
                import '@nabuxai/ui-core/css';

                const name = ref('Soy candle, minimal');
                const snap = ref('Soy candle, minimal');
                const saving = ref(false);
                const saved = ref(false);
                const dirty = computed(() => name.value !== snap.value);
                const open = computed(() => dirty.value || saving.value || saved.value);

                function save() {
                  saving.value = true;
                  setTimeout(() => {              // pretend PATCH, then refresh the snapshot
                    snap.value = name.value; saving.value = false; saved.value = true;
                    setTimeout(() => (saved.value = false), 900);
                  }, 900);
                }
                </script>

                <template>
                  <div class="plsb">
                    <div v-if="open" class="plsb-bar" role="status">
                      <span>{{ saved ? 'Saved' : saving ? 'Saving…' : 'Unsaved changes' }}</span>
                      <button class="plsb-discard" :disabled="saving" @click="name = snap">Discard</button>
                      <button data-primary :disabled="saving || !dirty" @click="save">Save</button>
                    </div>
                    <input v-model="name" aria-label="Product title" />
                  </div>
                </template>

                <style scoped>
                .plsb { position: relative; display: grid; gap: 12px; }
                .plsb-bar { position: absolute; inset-inline: 0; inset-block-start: 0; z-index: 5; display: flex; align-items: center;
                            gap: 12px; padding: 10px 16px; background: #1a1a1a; color: #f1f1f1; border-radius: 12px 12px 0 0; }
                .plsb-bar [data-primary] { background: #008060; color: #fff; border-radius: 8px; }
                </style>
                BLADE,
            'svelte' => <<<'BLADE'
                <!-- ContextualSaveBar.svelte -->
                <script lang="ts">
                  import '@nabuxai/ui-core/css';

                  let name = $state('Soy candle, minimal');
                  let snap = $state('Soy candle, minimal');
                  let saving = $state(false);
                  let saved = $state(false);
                  const dirty = $derived(name !== snap);
                  const open = $derived(dirty || saving || saved);

                  function save() {
                    saving = true;
                    setTimeout(() => {            // pretend PATCH, then refresh the snapshot
                      snap = name; saving = false; saved = true;
                      setTimeout(() => (saved = false), 900);
                    }, 900);
                  }
                </script>

                <div class="plsb">
                  {#if open}
                    <div class="plsb-bar" role="status">
                      <span>{saved ? 'Saved' : saving ? 'Saving…' : 'Unsaved changes'}</span>
                      <button class="plsb-discard" disabled={saving} onclick={() => (name = snap)}>Discard</button>
                      <button data-primary disabled={saving || !dirty} onclick={save}>Save</button>
                    </div>
                  {/if}
                  <input bind:value={name} aria-label="Product title" />
                </div>

                <style>
                .plsb { position: relative; display: grid; gap: 12px; }
                .plsb-bar { position: absolute; inset-inline: 0; inset-block-start: 0; z-index: 5; display: flex; align-items: center;
                            gap: 12px; padding: 10px 16px; background: #1a1a1a; color: #f1f1f1; border-radius: 12px 12px 0 0; }
                .plsb-bar [data-primary] { background: #008060; color: #fff; border-radius: 8px; }
                </style>
                BLADE,
        ],
    ],

    'annotated-layout' => [
        'title' => ['fa' => 'چیدمان دوسطری تنظیمات', 'en' => 'Annotated Layout'],
        'icon' => 'file',
        'oneLiner' => [
            'fa' => 'صفحات تنظیمات دوستونه: یک ستون برای عنوان، توضیح و لینک راهنما، ستون دیگر کارت‌های بخش‌بندی‌شدهٔ فیلدها؛ الگوی رسمی ستینگ‌پیج‌های شاپیفای.',
            'en' => 'Two-column settings pages: one column for the title, explanation and help link, the other for sectioned field cards — the official Shopify settings-page pattern.',
        ],
        'js' => true,
        'docs' => 'https://polaris.shopify.com/components/annotated-layout',
        'props' => [
            ['name' => 'annotation', 'type' => 'column', 'default' => "'1fr · 2fr'", 'note' => [
                'fa' => 'نسبت ستون‌ها یک به دو؛ زیر ۷۶۸px روی هم می‌افتند و توضیح بالا می‌ماند.',
                'en' => 'The columns split 1:2; under 768px they stack with the annotation on top.',
            ]],
            ['name' => 'sectioned', 'type' => 'card', 'default' => "'true'", 'note' => [
                'fa' => 'کارت بخش‌بندی‌شده: هر بخش فیلدها جدا با خط زیر خودش.',
                'en' => 'A sectioned card: each field group separated by its own rule.',
            ]],
            ['name' => 'help-link', 'type' => 'link', 'default' => 'null', 'note' => [
                'fa' => 'لینک «بیشتر بدانید» با آیکن بیرونی، پایان ستون توضیح.',
                'en' => 'A «learn more» link with an external icon closing the annotation column.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
            <div class="plal">
                <div class="plal-note">
                    <h2>اطلاع‌رسانی‌ها</h2>
                    <p>تصمیم بدهید مشتری پس از هر سفارش چه خبری بگیرد.</p>
                    <a>بیشتر بدانید ↗</a>
                </div>
                <div class="plal-card">
                    <label><input type="checkbox"> ایمیل تأیید سفارش</label>
                    <hr>
                    <label>فرستنده <select>…</select></label>
                </div>
            </div>

            <style>
            .plal { display: grid; grid-template-columns: 1fr 2fr; gap: 24px; }
            .plal-note h2 { margin: 0; font: 600 16px/1.3 Inter, system-ui; color: #303030; }
            .plal-card { border-radius: 12px; background: #FFF;
                         box-shadow: 0 1px 4px #00000012; padding: 16px; }
            @media (max-width: 768px) { .plal { grid-template-columns: 1fr; } }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <div class="plal">
                <div class="plal-note">
                    <h2>Notifications</h2>
                    <p>Decide what customers hear after each order.</p>
                    <a>Learn more ↗</a>
                </div>
                <div class="plal-card">
                    <label><input type="checkbox"> Order confirmation email</label>
                    <hr>
                    <label>Sender <select>…</select></label>
                </div>
            </div>

            <style>
            .plal { display: grid; grid-template-columns: 1fr 2fr; gap: 24px; }
            .plal-note h2 { margin: 0; font: 600 16px/1.3 Inter, system-ui; color: #303030; }
            .plal-card { border-radius: 12px; background: #FFF;
                         box-shadow: 0 1px 4px #00000012; padding: 16px; }
            @media (max-width: 768px) { .plal { grid-template-columns: 1fr; } }
            </style>
            BLADE,
            ],
            'inertia' => <<<'BLADE'
                // resources/js/Pages/Settings/Notifications.tsx — the annotated
                // layout page; the two-column skeleton is the React snippet.
                import { Head } from '@inertiajs/react';
                import AnnotatedLayout from '@/components/polaris/AnnotatedLayout';

                export default function SettingsNotifications() {
                  return (
                    <>
                      <Head title="Notifications" />
                      {/* Save posts the section, then a back-end flash confirms it */}
                      <AnnotatedLayout />
                    </>
                  );
                }
                BLADE,
            'react' => <<<'BLADE'
                import { useState } from 'react';
                import '@nabuxai/ui-core/css';

                export default function AnnotatedLayout() {
                  const [confirm, setConfirm] = useState(true);
                  const [sender, setSender] = useState('shop');
                  const [flash, setFlash] = useState(false);
                  const touch = () => { setFlash(true); setTimeout(() => setFlash(false), 2200); };

                  return (
                    <div className="plal">
                      <div className="plal-note">
                        <h2>Notifications</h2>
                        <p>Decide what customers hear after each order.</p>
                        <a href="#">Learn more ↗</a>
                      </div>
                      <div className="plal-card">
                        <div className="plal-sec">
                          <label>
                            <input type="checkbox" checked={confirm} onChange={(e) => { setConfirm(e.target.checked); touch(); }} />
                            Order confirmation email
                          </label>
                        </div>
                        <div className="plal-sec">
                          <label>
                            Sender
                            <select value={sender} onChange={(e) => { setSender(e.target.value); touch(); }}>
                              <option value="shop">The shop, from its own address</option>
                              <option value="support">Support desk</option>
                            </select>
                          </label>
                        </div>
                        <div className="plal-foot">
                          {flash ? <span className="plal-flash">✓ Settings saved</span> : <small>Changes apply from the next order.</small>}
                          <button className="plal-save">Save</button>
                        </div>
                      </div>
                      <style>{`
                        .plal { display: grid; grid-template-columns: 1fr 2fr; gap: 24px; }
                        .plal-note h2 { margin: 0; font: 600 16px/1.3 Inter, system-ui; color: #303030; }
                        .plal-card { border-radius: 12px; background: #FFF; box-shadow: 0 1px 4px #00000012;
                                     padding: 16px; display: grid; gap: 16px; }
                        .plal-foot { display: flex; align-items: center; gap: 12px; }
                        .plal-save { margin-inline-start: auto; block-size: 32px; padding-inline: 16px; border: none;
                                     border-radius: 8px; background: #008060; color: #FFF; }
                        @media (max-width: 768px) { .plal { grid-template-columns: 1fr; } }
                      `}</style>
                    </div>
                  );
                }
                BLADE,
            'vue' => <<<'BLADE'
                <!-- AnnotatedLayout.vue -->
                <script setup lang="ts">
                import { ref } from 'vue';
                import '@nabuxai/ui-core/css';

                const confirm = ref(true);
                const sender = ref('shop');
                const flash = ref(false);
                let timer: ReturnType<typeof setTimeout> | undefined;
                const touch = () => {
                  flash.value = true;
                  clearTimeout(timer);
                  timer = setTimeout(() => (flash.value = false), 2200);
                };
                </script>

                <template>
                  <div class="plal">
                    <div class="plal-note">
                      <h2>Notifications</h2>
                      <p>Decide what customers hear after each order.</p>
                      <a href="#">Learn more ↗</a>
                    </div>
                    <div class="plal-card">
                      <div class="plal-sec">
                        <label>
                          <input v-model="confirm" type="checkbox" @change="touch()" />
                          Order confirmation email
                        </label>
                      </div>
                      <div class="plal-sec">
                        <label>
                          Sender
                          <select v-model="sender" @change="touch()">
                            <option value="shop">The shop, from its own address</option>
                            <option value="support">Support desk</option>
                          </select>
                        </label>
                      </div>
                      <div class="plal-foot">
                        <span v-if="flash" class="plal-flash">✓ Settings saved</span>
                        <small v-else>Changes apply from the next order.</small>
                        <button class="plal-save">Save</button>
                      </div>
                    </div>
                  </div>
                </template>

                <style scoped>
                .plal { display: grid; grid-template-columns: 1fr 2fr; gap: 24px; }
                .plal-note h2 { margin: 0; font: 600 16px/1.3 Inter, system-ui; color: #303030; }
                .plal-card { border-radius: 12px; background: #fff; box-shadow: 0 1px 4px #00000012;
                             padding: 16px; display: grid; gap: 16px; }
                .plal-foot { display: flex; align-items: center; gap: 12px; }
                .plal-save { margin-inline-start: auto; block-size: 32px; padding-inline: 16px; border: none;
                             border-radius: 8px; background: #008060; color: #fff; }
                @media (max-width: 768px) { .plal { grid-template-columns: 1fr; } }
                </style>
                BLADE,
            'svelte' => <<<'BLADE'
                <!-- AnnotatedLayout.svelte -->
                <script lang="ts">
                  import '@nabuxai/ui-core/css';

                  let confirm = $state(true);
                  let sender = $state('shop');
                  let flash = $state(false);
                  let timer: ReturnType<typeof setTimeout> | undefined;
                  const touch = () => {
                    flash = true;
                    clearTimeout(timer);
                    timer = setTimeout(() => (flash = false), 2200);
                  };
                </script>

                <div class="plal">
                  <div class="plal-note">
                    <h2>Notifications</h2>
                    <p>Decide what customers hear after each order.</p>
                    <a href="#/">Learn more ↗</a>
                  </div>
                  <div class="plal-card">
                    <div class="plal-sec">
                      <label>
                        <input type="checkbox" bind:checked={confirm} onchange={touch()} />
                        Order confirmation email
                      </label>
                    </div>
                    <div class="plal-sec">
                      <label>
                        Sender
                        <select bind:value={sender} onchange={touch()}>
                          <option value="shop">The shop, from its own address</option>
                          <option value="support">Support desk</option>
                        </select>
                      </label>
                    </div>
                    <div class="plal-foot">
                      {#if flash}<span class="plal-flash">✓ Settings saved</span>{:else}<small>Changes apply from the next order.</small>{/if}
                      <button class="plal-save">Save</button>
                    </div>
                  </div>
                </div>

                <style>
                .plal { display: grid; grid-template-columns: 1fr 2fr; gap: 24px; }
                .plal-note h2 { margin: 0; font: 600 16px/1.3 Inter, system-ui; color: #303030; }
                .plal-card { border-radius: 12px; background: #fff; box-shadow: 0 1px 4px #00000012;
                             padding: 16px; display: grid; gap: 16px; }
                .plal-foot { display: flex; align-items: center; gap: 12px; }
                .plal-save { margin-inline-start: auto; block-size: 32px; padding-inline: 16px; border: none;
                             border-radius: 8px; background: #008060; color: #fff; }
                @media (max-width: 768px) { .plal { grid-template-columns: 1fr; } }
                </style>
                BLADE,
        ],
    ],

    'color-picker' => [
        'title' => ['fa' => 'انتخابگر رنگ شاپیفای', 'en' => 'Color Picker'],
        'icon' => 'wand',
        'oneLiner' => [
            'fa' => 'مستطیل اشباع، اسلایدر Hue و نوار آلفا در یک پنل کامپکت با هگز قابل‌ویرایش؛ انتخاب رنگ واریانت محصول دقیقاً به سبک خود شاپیفای.',
            'en' => 'A saturation rectangle, hue slider and alpha bar in one compact panel with an editable hex field — picking product variant colors the Shopify-native way.',
        ],
        'js' => true,
        'docs' => 'https://polaris.shopify.com/components/color-picker',
        'props' => [
            ['name' => 'color', 'type' => 'hsv', 'default' => "'{h:120,s:60,v:80}'", 'note' => [
                'fa' => 'مدل رنگ پولاریس HSV است؛ مستطیل اشباع، s و v را می‌کشد و اسلایدر h را.',
                'en' => 'Polaris models colour as HSV; the rectangle drags s and v, the slider h.',
            ]],
            ['name' => 'alpha', 'type' => 'slider', 'default' => "'0–1'", 'note' => [
                'fa' => 'نوار آلفا روی شطرنجی شفافیت می‌رود؛ حذفش پنل را فشرده‌تر می‌کند.',
                'en' => 'The alpha bar slides over a transparency checkerboard; dropping it densifies the panel.',
            ]],
            ['name' => 'hex', 'type' => 'input', 'default' => "'#008060'", 'note' => [
                'fa' => 'فیلد هگز قابل‌ویرایش؛ تایپ یا چسپاندن، همان لحظه همهٔ کنترل‌ها را جابه‌جا می‌کند.',
                'en' => 'The editable hex field; typing or pasting moves every control at once.',
            ]],
        ],
        'code' => [
            'livewire' => [
                'fa' => <<<'BLADE'
            <div class="plcp" x-data="{ h: 160, s: 100, v: 50, a: 1 }">
                <div class="plcp-area" x-on:pointerdown="pick($event)">
                    <span class="plcp-thumb" x-bind:style="`left:${s}%;top:${100 - v}%`"></span>
                </div>
                <input type="range" min="0" max="360" x-model.number="h">
                <input class="plcp-hex" x-model="hex">
            </div>

            <style>
            .plcp { display: grid; gap: 12px; padding: 12px; border-radius: 12px;
                    background: #FFF; box-shadow: 0 1px 4px #00000012; }
            .plcp-area { aspect-ratio: 16/9; border-radius: 8px; touch-action: none;
                         background: linear-gradient(to top, #000, transparent),
                                     linear-gradient(to right, #FFF, hsl(var(--hue) 100% 50%)); }
            .plcp-thumb { position: absolute; inline-size: 18px; aspect-ratio: 1;
                          border-radius: 50%; border: 2px solid #FFF; translate: -50% -50%; }
            </style>
            BLADE,
            'en' => <<<'BLADE'
            <div class="plcp" x-data="{ h: 160, s: 100, v: 50, a: 1 }">
                <div class="plcp-area" x-on:pointerdown="pick($event)">
                    <span class="plcp-thumb" x-bind:style="`left:${s}%;top:${100 - v}%`"></span>
                </div>
                <input type="range" min="0" max="360" x-model.number="h">
                <input class="plcp-hex" x-model="hex">
            </div>

            <style>
            .plcp { display: grid; gap: 12px; padding: 12px; border-radius: 12px;
                    background: #FFF; box-shadow: 0 1px 4px #00000012; }
            .plcp-area { aspect-ratio: 16/9; border-radius: 8px; touch-action: none;
                         background: linear-gradient(to top, #000, transparent),
                                     linear-gradient(to right, #FFF, hsl(var(--hue) 100% 50%)); }
            .plcp-thumb { position: absolute; inline-size: 18px; aspect-ratio: 1;
                          border-radius: 50%; border: 2px solid #FFF; translate: -50% -50%; }
            </style>
            BLADE,
            ],
            'inertia' => <<<'BLADE'
                // resources/js/Pages/Products/Variant.tsx — the picker feeds a
                // variant form field; Inertia submits the whole form on save.
                import { Head } from '@inertiajs/react';
                import ColorPicker from '@/components/polaris/ColorPicker';

                export default function ProductVariant() {
                  return (
                    <>
                      <Head title="Edit variant" />
                      {/* bind the hex into <input type="hidden" name="color"> */}
                      <ColorPicker />
                    </>
                  );
                }
                BLADE,
            'react' => <<<'BLADE'
                import { useState } from 'react';
                import '@nabuxai/ui-core/css';

                export default function ColorPicker() {
                  const [h, setH] = useState(162);
                  const [sv, setSv] = useState({ s: 92, v: 58 });
                  const hue = `hsl(${Math.round(h)} 100% 50%)`;

                  const pick = (e: React.PointerEvent<HTMLDivElement>) => {
                    const r = e.currentTarget.getBoundingClientRect();
                    const clamp = (n: number) => Math.min(100, Math.max(0, n));
                    setSv({
                      s: clamp(((e.clientX - r.left) / r.width) * 100),
                      v: clamp(100 - ((e.clientY - r.top) / r.height) * 100),
                    });
                  };

                  return (
                    <div className="plcp">
                      <div
                        className="plcp-area" style={{ backgroundColor: hue }} role="slider" aria-label="Saturation and brightness"
                        onPointerDown={(e) => { e.currentTarget.setPointerCapture(e.pointerId); pick(e); }}
                        onPointerMove={(e) => e.currentTarget.hasPointerCapture(e.pointerId) && pick(e)}
                      >
                        <span className="plcp-thumb" style={{ left: `${sv.s}%`, top: `${100 - sv.v}%`, background: hue }} />
                      </div>
                      <input type="range" min={0} max={360} value={h} onChange={(e) => setH(Number(e.target.value))} aria-label="Hue" />
                      <style>{`
                        .plcp { display: grid; gap: 12px; padding: 12px; border-radius: 12px;
                                background: #FFF; box-shadow: 0 1px 4px #00000012; }
                        .plcp-area { position: relative; aspect-ratio: 16/9; border-radius: 8px; touch-action: none;
                                     background-image: linear-gradient(to top, #000, transparent),
                                                       linear-gradient(to right, #FFF, transparent); }
                        .plcp-thumb { position: absolute; inline-size: 18px; aspect-ratio: 1; border-radius: 50%;
                                      border: 2px solid #FFF; translate: -50% -50%; }
                      `}</style>
                    </div>
                  );
                }
                BLADE,
            'vue' => <<<'BLADE'
                <!-- ColorPicker.vue -->
                <script setup lang="ts">
                import { ref, computed } from 'vue';
                import '@nabuxai/ui-core/css';

                const h = ref(162);
                const sv = ref({ s: 92, v: 58 });
                const hue = computed(() => `hsl(${Math.round(h.value)} 100% 50%)`);

                function pick(e: PointerEvent) {
                  const r = (e.currentTarget as HTMLElement).getBoundingClientRect();
                  const clamp = (n: number) => Math.min(100, Math.max(0, n));
                  sv.value = {
                    s: clamp(((e.clientX - r.left) / r.width) * 100),
                    v: clamp(100 - ((e.clientY - r.top) / r.height) * 100),
                  };
                }
                </script>

                <template>
                  <div class="plcp">
                    <div
                      class="plcp-area" :style="{ backgroundColor: hue }" role="slider" aria-label="Saturation and brightness"
                      @pointerdown="(e: PointerEvent) => { (e.currentTarget as HTMLElement).setPointerCapture(e.pointerId); pick(e); }"
                      @pointermove="(e: PointerEvent) => (e.currentTarget as HTMLElement).hasPointerCapture(e.pointerId) && pick(e)"
                    >
                      <span class="plcp-thumb" :style="{ left: sv.s + '%', top: 100 - sv.v + '%', background: hue }" />
                    </div>
                    <input v-model.number="h" type="range" min="0" max="360" aria-label="Hue" />
                  </div>
                </template>

                <style scoped>
                .plcp { display: grid; gap: 12px; padding: 12px; border-radius: 12px;
                        background: #fff; box-shadow: 0 1px 4px #00000012; }
                .plcp-area { position: relative; aspect-ratio: 16/9; border-radius: 8px; touch-action: none;
                             background-image: linear-gradient(to top, #000, transparent),
                                               linear-gradient(to right, #fff, transparent); }
                .plcp-thumb { position: absolute; inline-size: 18px; aspect-ratio: 1; border-radius: 50%;
                              border: 2px solid #fff; translate: -50% -50%; }
                </style>
                BLADE,
            'svelte' => <<<'BLADE'
                <!-- ColorPicker.svelte -->
                <script lang="ts">
                  import '@nabuxai/ui-core/css';

                  let h = $state(162);
                  let sv = $state({ s: 92, v: 58 });
                  const hue = $derived(`hsl(${Math.round(h)} 100% 50%)`);

                  const pick = (e: PointerEvent) => {
                    const r = (e.currentTarget as HTMLElement).getBoundingClientRect();
                    const clamp = (n: number) => Math.min(100, Math.max(0, n));
                    sv = {
                      s: clamp(((e.clientX - r.left) / r.width) * 100),
                      v: clamp(100 - ((e.clientY - r.top) / r.height) * 100),
                    };
                  };
                </script>

                <div class="plcp">
                  <div
                    class="plcp-area" style:background-color={hue} role="slider" aria-label="Saturation and brightness"
                    onpointerdown={(e: PointerEvent) => { (e.currentTarget as HTMLElement).setPointerCapture(e.pointerId); pick(e); }}
                    onpointermove={(e: PointerEvent) => (e.currentTarget as HTMLElement).hasPointerCapture(e.pointerId) && pick(e)}
                  >
                    <span class="plcp-thumb" style:left="{sv.s}%" style:top="{100 - sv.v}%" style:background={hue}></span>
                  </div>
                  <input type="range" min="0" max="360" bind:value={h} aria-label="Hue" />
                </div>

                <style>
                .plcp { display: grid; gap: 12px; padding: 12px; border-radius: 12px;
                        background: #fff; box-shadow: 0 1px 4px #00000012; }
                .plcp-area { position: relative; aspect-ratio: 16/9; border-radius: 8px; touch-action: none;
                             background-image: linear-gradient(to top, #000, transparent),
                                               linear-gradient(to right, #fff, transparent); }
                .plcp-thumb { position: absolute; inline-size: 18px; aspect-ratio: 1; border-radius: 50%;
                              border: 2px solid #fff; translate: -50% -50%; }
                </style>
                BLADE,
        ],
    ],
];
