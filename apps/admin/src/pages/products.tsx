/**
 * #/products — the shelves, live: the product-card grid over seeded bilingual
 * data. Search, a category chip filter and a status chip filter narrow the
 * shelf, a sort pill orders it (newest / cheapest / priciest / most loved) and
 * the grid/list switch changes its rhythm. Every card is the real
 * product-card block — springy add-to-cart, star fractions, stock badges,
 * crossed-out compare-at prices — and "New product" is a dialog that really
 * appends a card. The donut beside the grid splits the *filtered* shelf's
 * sales by category, so it follows every filter. Deterministic: no
 * Math.random, no fetch; prices are tomans in the reader's digits.
 */
import { useState } from 'react';
import {
  Button,
  ChipFilter,
  Dialog,
  DonutRing,
  EmptyState,
  Field,
  Input,
  ProductCard,
  SegmentedControl,
  Select,
  SortPill,
  toast,
  type ProductStock,
} from '@nabuxai/ui-react';
import { useLang, useStrings, useTr } from '../lang';

const INTL = { fa: 'fa-IR', en: 'en-US' } as const;

/** Read a number as typed: Persian and Arabic-Indic digits (۰-۹/٠-٩) count too. */
const readDigits = (raw: string) =>
  Number(
    raw
      .replace(/[۰-۹]/g, (d) => String(d.charCodeAt(0) - 0x06f0))
      .replace(/[٠-٩]/g, (d) => String(d.charCodeAt(0) - 0x0660))
      .replace(/[^0-9]/g, ''),
  );

type Category = 'bags' | 'scarves' | 'watches' | 'home';
type Status = 'active' | 'draft';
type Sort = 'newest' | 'cheapest' | 'priciest' | 'popular';

type Product = {
  id: string;
  fa: string;
  en: string;
  sku: string;
  category: Category;
  status: Status;
  /** Tomans. */
  price: number;
  compareAt?: number;
  rating?: number;
  ratingCount?: number;
  sales: number;
  stock: ProductStock;
  stockCount?: number;
  /** Days since it joined the shelf; smaller is newer. */
  addedDaysAgo: number;
};

const SEED: Product[] = [
  { id: 'p1', fa: 'کیف چرمی نابو', en: 'Nabu leather bag', sku: 'NB-1042', category: 'bags', status: 'active', price: 2_850_000, compareAt: 3_200_000, rating: 4.8, ratingCount: 214, sales: 312, stock: 'in', addedDaysAgo: 5 },
  { id: 'p2', fa: 'شال کشمیر نابو', en: 'Nabu cashmere scarf', sku: 'NB-2210', category: 'scarves', status: 'active', price: 1_480_000, rating: 4.6, ratingCount: 168, sales: 254, stock: 'low', stockCount: 3, addedDaysAgo: 8 },
  { id: 'p3', fa: 'ساعت نابو کلاسیک', en: 'Nabu classic watch', sku: 'NB-3051', category: 'watches', status: 'active', price: 5_900_000, compareAt: 6_500_000, rating: 4.9, ratingCount: 96, sales: 87, stock: 'in', addedDaysAgo: 1 },
  { id: 'p4', fa: 'کوسن مخمل لاجوردی', en: 'Lapis velvet cushion', sku: 'NB-4107', category: 'home', status: 'active', price: 640_000, rating: 4.3, ratingCount: 58, sales: 141, stock: 'in', addedDaysAgo: 12 },
  { id: 'p5', fa: 'دفتر چرمی دست‌دوز', en: 'Hand-stitched leather journal', sku: 'NB-1088', category: 'bags', status: 'active', price: 380_000, rating: 4.4, ratingCount: 73, sales: 198, stock: 'in', addedDaysAgo: 2 },
  { id: 'p6', fa: 'شال پشمی رنگین', en: 'Rangin wool scarf', sku: 'NB-2264', category: 'scarves', status: 'active', price: 980_000, compareAt: 1_150_000, rating: 4.1, ratingCount: 41, sales: 77, stock: 'low', stockCount: 5, addedDaysAgo: 3 },
  { id: 'p7', fa: 'ساعت دیواری چوبی', en: 'Wooden wall clock', sku: 'NB-3099', category: 'watches', status: 'active', price: 1_250_000, rating: 4.5, ratingCount: 34, sales: 45, stock: 'out', addedDaysAgo: 18 },
  { id: 'p8', fa: 'کمربند چرم دست‌دوز', en: 'Hand-made leather belt', sku: 'NB-1121', category: 'bags', status: 'active', price: 720_000, rating: 4.7, ratingCount: 88, sales: 165, stock: 'in', addedDaysAgo: 6 },
  { id: 'p9', fa: 'بستهٔ هدیهٔ نابو', en: 'Nabu gift wrap set', sku: 'NB-4001', category: 'home', status: 'active', price: 180_000, rating: 4.9, ratingCount: 302, sales: 540, stock: 'in', addedDaysAgo: 4 },
  { id: 'p10', fa: 'ماگ سرامیکی لاجورد', en: 'Lapis ceramic mug', sku: 'NB-4150', category: 'home', status: 'draft', price: 320_000, sales: 0, stock: 'in', addedDaysAgo: 0 },
];

export function ProductsPage() {
  const lang = useLang();
  const tr = useTr();
  const s = useStrings();
  const p = s.pages.products;
  const number = new Intl.NumberFormat(INTL[lang]);

  const [rows, setRows] = useState<Product[]>(SEED);
  const [query, setQuery] = useState('');
  const [category, setCategory] = useState('all');
  const [status, setStatus] = useState('all');
  const [sort, setSort] = useState<Sort>('newest');
  const [view, setView] = useState<'grid' | 'list'>('grid');
  const [creating, setCreating] = useState(false);
  const [draft, setDraft] = useState({ name: '', category: 'bags' as Category, price: '' });

  const catLabel: Record<Category, string> = { bags: p.catBags, scarves: p.catScarves, watches: p.catWatches, home: p.catHome };
  const nameOf = (row: Product) => (lang === 'fa' ? row.fa : row.en);

  const needle = query.trim().toLowerCase();
  const visible = rows
    .filter(
      (row) =>
        (category === 'all' || row.category === category) &&
        (status === 'all' ||
          (status === 'active' && row.status === 'active') ||
          (status === 'draft' && row.status === 'draft') ||
          (status === 'low' && row.stock === 'low') ||
          (status === 'out' && row.stock === 'out')) &&
        (!needle || row.fa.toLowerCase().includes(needle) || row.en.toLowerCase().includes(needle) || row.sku.toLowerCase().includes(needle)),
    )
    .sort((a, b) =>
      sort === 'newest' ? a.addedDaysAgo - b.addedDaysAgo
      : sort === 'cheapest' ? a.price - b.price
      : sort === 'priciest' ? b.price - a.price
      : b.sales - a.sales,
    );

  const counts = {
    all: rows.length,
    active: rows.filter((row) => row.status === 'active').length,
    draft: rows.filter((row) => row.status === 'draft').length,
    low: rows.filter((row) => row.stock === 'low').length,
    out: rows.filter((row) => row.stock === 'out').length,
  };
  const byCategory = (['bags', 'scarves', 'watches', 'home'] as const)
    .map((id) => ({ label: catLabel[id], value: visible.filter((row) => row.category === id).reduce((sum, row) => sum + row.sales, 0) }))
    .filter((slice) => slice.value > 0);

  const create = () => {
    const name = draft.name.trim();
    const price = readDigits(draft.price);
    if (!name || !price) return;
    setRows((prev) => [
      { id: `p-${Date.now().toString(36)}`, fa: name, en: name, sku: `NB-${5000 + prev.length + 1}`, category: draft.category, status: 'active', price, sales: 0, stock: 'in', addedDaysAgo: 0 },
      ...prev,
    ]);
    setCreating(false);
    setDraft({ name: '', category: 'bags', price: '' });
    toast(tr(`«${name}» به قفسه اضافه شد`, `“${name}” was added to the shelf`));
  };

  return (
    <div className="adm-dashboard">
      <div className="adm-wide" style={{ display: 'flex', flexWrap: 'wrap', gap: 'var(--nx-space-3)', alignItems: 'center', justifyContent: 'space-between' }}>
        <div style={{ display: 'flex', flexWrap: 'wrap', gap: 'var(--nx-space-2)', alignItems: 'center' }}>
          <Input
            type="search"
            value={query}
            onChange={(event) => setQuery(event.target.value)}
            placeholder={p.search}
            aria-label={s.common.search}
            startAddon="search"
            style={{ inlineSize: 'min(17rem, 60vw)' }}
          />
          <ChipFilter
            aria-label={p.category}
            value={category}
            onValueChange={setCategory}
            items={[{ value: 'all', label: p.categoryAll, count: counts.all }, ...(['bags', 'scarves', 'watches', 'home'] as const).map((id) => ({ value: id, label: catLabel[id], count: rows.filter((row) => row.category === id).length }))]}
          />
          <ChipFilter
            aria-label={p.status}
            value={status}
            onValueChange={setStatus}
            items={[
              { value: 'all', label: s.common.all, count: counts.all },
              { value: 'active', label: p.statusActive, count: counts.active },
              { value: 'draft', label: p.statusDraft, count: counts.draft },
              { value: 'low', label: p.statusLow, count: counts.low },
              { value: 'out', label: p.statusOut, count: counts.out },
            ]}
          />
        </div>
        <div style={{ display: 'flex', flexWrap: 'wrap', gap: 'var(--nx-space-2)', alignItems: 'center' }}>
          <SortPill
            label={p.sortBy}
            value={sort}
            onValueChange={(next) => setSort(next as Sort)}
            options={[
              { value: 'newest', label: p.sortNewest },
              { value: 'cheapest', label: p.sortCheapest },
              { value: 'priciest', label: p.sortPriciest },
              { value: 'popular', label: p.sortPopular },
            ]}
          />
          <SegmentedControl
            size="sm"
            aria-label={p.viewGrid}
            value={view}
            onValueChange={(next) => setView(next === 'list' ? 'list' : 'grid')}
            options={[
              { value: 'grid', label: p.viewGrid, icon: 'grid' },
              { value: 'list', label: p.viewList, icon: 'menu' },
            ]}
          />
          <Button variant="primary" icon="plus" onClick={() => setCreating(true)}>
            {p.addProduct}
          </Button>
        </div>
      </div>

      <div className="adm-wide" style={{ display: 'flex', flexWrap: 'wrap', gap: 'var(--nx-space-2)', alignItems: 'center' }}>
        <span className="nx-badge" data-tone="accent" data-dot="">
          {number.format(visible.length)} {p.countLabel}
        </span>
        <span className="nx-badge">{p.statusActive}: {number.format(counts.active)}</span>
        <span className="nx-badge">{p.statusLow}: {number.format(counts.low)}</span>
        <span className="nx-badge">{p.statusOut}: {number.format(counts.out)}</span>
        {counts.low > 0 && (
          <span style={{ color: 'var(--nx-text-subtle)', fontSize: 'var(--nx-text-sm)' }}>{p.lowStockHint}</span>
        )}
      </div>

      {visible.length > 0 ? (
        <div
          className="adm-wide"
          style={{
            display: 'grid',
            gap: 'var(--nx-space-4)',
            gridTemplateColumns: view === 'grid' ? 'repeat(auto-fill, minmax(15.5rem, 1fr))' : 'minmax(0, 1fr)',
            alignItems: 'start',
          }}
        >
          {visible.map((row) => (
            <ProductCard
              key={row.id}
              title={nameOf(row)}
              category={
                <>
                  {catLabel[row.category]}
                  {' · '}
                  <span dir="ltr">{row.sku}</span>
                  {row.status === 'draft' ? ` · ${p.statusDraft}` : ''}
                </>
              }
              price={row.price}
              compareAt={row.compareAt}
              currency={tr('تومان', 'Toman')}
              rating={row.rating}
              ratingCount={row.ratingCount}
              stock={row.stock}
              stockCount={row.stockCount}
            />
          ))}
        </div>
      ) : (
        <div className="adm-wide">
          <EmptyState icon="search" title={p.noMatch} size="sm" />
        </div>
      )}

      <div className="adm-side">
        {byCategory.length > 0 && (
          <DonutRing
            title={p.category}
            subtitle={p.sales}
            data={byCategory}
            centerLabel=""
          />
        )}
      </div>

      <Dialog
        open={creating}
        onOpenChange={setCreating}
        size="sm"
        title={p.addProduct}
        footer={
          <>
            <Button variant="secondary" onClick={() => setCreating(false)}>
              {s.common.cancel}
            </Button>
            <Button variant="primary" icon="plus" disabled={!draft.name.trim() || !readDigits(draft.price)} onClick={create}>
              {s.common.add}
            </Button>
          </>
        }
      >
        <div style={{ display: 'grid', gap: 'var(--nx-space-3)' }}>
          <Field label={tr('نام محصول', 'Product name')} required>
            <Input value={draft.name} onChange={(event) => setDraft({ ...draft, name: event.target.value })} autoComplete="off" />
          </Field>
          <Field label={p.category}>
            <Select
              value={draft.category}
              onChange={(event) => setDraft({ ...draft, category: event.target.value as Category })}
              options={(['bags', 'scarves', 'watches', 'home'] as const).map((id) => ({ value: id, label: catLabel[id] }))}
            />
          </Field>
          <Field label={p.price} required hint={tr('تومان', 'Toman')}>
            <Input inputMode="numeric" dir="ltr" value={draft.price} onChange={(event) => setDraft({ ...draft, price: event.target.value })} autoComplete="off" />
          </Field>
        </div>
      </Dialog>
    </div>
  );
}
