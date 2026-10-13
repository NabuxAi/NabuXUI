import { useState } from 'react';
import { ChipFilter, EmptyState, ProfileCard, ProductCard, SortPill, toast } from '@nabuxai/ui-react';
import { Shell } from '../shell';
import { STRINGS, useLang } from '../lang';

/** What GalleryController (Laravel) sends down with the page. */
export interface GalleryProps {
  /** How many blocks the server says this page showcases. */
  blocks: number;
}

type Filter = 'all' | 'commerce' | 'people';

export default function Gallery({ blocks }: GalleryProps) {
  const lang = useLang();
  const s = STRINGS[lang].gallery;
  const [filter, setFilter] = useState<Filter>('all');
  const [sort, setSort] = useState('featured');
  const [cart, setCart] = useState<Record<string, boolean>>({});

  const show = (id: Filter) => filter === 'all' || filter === id;
  const addToCart = (id: string) => {
    setCart((current) => ({ ...current, [id]: true }));
    toast(`${s.product.title} — ${s.product.inCart}`);
  };
  const dropFromCart = (id: string) => setCart((current) => ({ ...current, [id]: false }));

  // The sort pill is live: "Cheapest" flips the two products' order.
  const products = [
    {
      id: 'northstar',
      price: 129,
      compareAt: 179 as number | undefined,
      rating: 4.5,
      ratingCount: 214,
      stock: 'in' as const,
      stockCount: undefined,
    },
    {
      id: 'southcross',
      price: 89,
      compareAt: undefined,
      rating: 4,
      ratingCount: 96,
      stock: 'low' as const,
      stockCount: 3,
    },
  ].toSorted((a, b) => (sort === 'price' ? a.price - b.price : 0));

  return (
    <Shell title={STRINGS[lang].gallery.title}>
      <div className="inr-grid">
        <div className="inr-toolbar">
          <ChipFilter
            items={[
              { value: 'all', label: s.filters.all, count: blocks },
              { value: 'commerce', label: s.filters.commerce },
              { value: 'people', label: s.filters.people },
            ]}
            value={filter}
            onValueChange={(value) => setFilter(value as Filter)}
            aria-label={s.filterLabel}
          />
          <SortPill
            options={[
              { value: 'featured', label: s.sort.featured, icon: 'star' },
              { value: 'newest', label: s.sort.newest, icon: 'zap' },
              { value: 'price', label: s.sort.price, icon: 'grid' },
            ]}
            value={sort}
            onValueChange={setSort}
            label={s.sortLabel}
          />
        </div>

        {show('commerce') && (
          <section className="inr-card">
            <div className="inr-products">
              {products.map((product) => (
                <ProductCard
                  key={product.id}
                  title={product.id === 'northstar' ? s.product.title : s.product.altTitle}
                  category={s.product.category}
                  price={product.price}
                  compareAt={product.compareAt}
                  currency="$"
                  rating={product.rating}
                  ratingCount={product.ratingCount}
                  stock={product.stock}
                  stockCount={product.stockCount}
                  inCart={!!cart[product.id]}
                  onAdd={() => addToCart(product.id)}
                  onRemove={() => dropFromCart(product.id)}
                />
              ))}
            </div>
          </section>
        )}

        {show('people') && (
          <section className="inr-card">
            <ProfileCard
              name={s.profile.name}
              role={s.profile.role}
              handle="maja"
              status="online"
              verified
              stats={[
                { label: s.profile.stats.projects, value: 34 },
                { label: s.profile.stats.followers, value: 2810 },
                { label: s.profile.stats.following, value: 312 },
              ]}
              followersStat={1}
              onFollow={() => toast(s.profile.follow)}
              onMessage={() => toast(s.profile.message)}
            />
          </section>
        )}

        <section className="inr-card">
          <EmptyState
            icon="folder"
            title={s.empty.title}
            description={s.empty.text}
            action={{ label: s.empty.action, icon: 'arrow-right', onClick: () => setFilter('all') }}
          />
        </section>

        <p className="inr-note">{s.count(blocks)}</p>
      </div>
    </Shell>
  );
}
