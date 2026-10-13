// @vitest-environment happy-dom
/**
 * The shop-facing blocks: the product card (pricing, rating, stock, the cart
 * morph), order tracking (the step route, the event log, the live badge) and
 * the profile card (follow morph, rolling stats, tabs) — in several locales
 * plus an unknown one.
 */
import { afterEach, describe, expect, it, vi } from 'vitest';
import { cleanup, fireEvent, render, screen, within } from '@testing-library/react';
import type { Locale } from '@nabuxai/ui-core';
import { ProductCard } from '../src/blocks/product-card';
import { OrderTracking } from '../src/blocks/order-tracking';
import { ProfileCard } from '../src/blocks/profile-card';
import { NabuXUIProvider } from '../src/internal/provider';

afterEach(cleanup);

/* ---- ProductCard -------------------------------------------------------------------------- */

describe('ProductCard', () => {
  it('renders pricing, rating, and the stock and discount badges', () => {
    const { container } = render(
      <ProductCard
        title="Lapis Hoodie"
        category="Apparel"
        image={{ src: '/hoodie.jpg', alt: 'Lapis Hoodie' }}
        price={1_800_000}
        compareAt={2_250_000}
        currency="تومان"
        rating={4.5}
        ratingCount={128}
        stock="low"
        stockCount={2}
      />,
    );
    expect(screen.getByText('Lapis Hoodie')).toBeTruthy();
    expect(screen.getByText('Apparel')).toBeTruthy();
    // money.format with default 0 decimals, the currency riding along.
    expect(container.querySelector('.nx-product-card-price')?.textContent).toContain('1,800,000');
    expect(container.querySelector('.nx-product-card-compare')?.textContent).toContain('2,250,000');
    // round((1 − 1.8/2.25) × 100) = 20.
    expect(screen.getByText('20% off')).toBeTruthy(); // i18n.en.productDiscount
    expect(screen.getByText('Only 2 left')).toBeTruthy(); // i18n.en.productLowStock
    expect(screen.getByRole('img', { name: '4.5 of 5 stars' })).toBeTruthy(); // i18n.en.stars
    expect(screen.getByText('128 reviews')).toBeTruthy(); // i18n.en.productReviews
    expect(container.querySelector('article.nx-product-card')?.getAttribute('data-stock')).toBe('low');
  });

  it('morphs the add-to-cart button and reports both directions', () => {
    const onAdd = vi.fn();
    const onRemove = vi.fn();
    const onInCartChange = vi.fn();
    const { container } = render(<ProductCard title="Mug" price={90_000} onAdd={onAdd} onRemove={onRemove} onInCartChange={onInCartChange} />);
    const button = container.querySelector('button.nx-product-card-add') as HTMLButtonElement;
    const idle = button.querySelector('.nx-product-card-add-label [data-part="idle"]')!;
    const done = button.querySelector('.nx-product-card-add-label [data-part="done"]')!;
    expect(idle.textContent).toBe('Add to cart'); // i18n.en.productAddToCart
    expect(idle.getAttribute('aria-hidden')).toBeNull(); // the resting label is the live one

    fireEvent.click(button);
    expect(onAdd).toHaveBeenCalledOnce();
    expect(onInCartChange).toHaveBeenCalledWith(true);
    expect(button.getAttribute('data-in-cart')).toBe('');
    expect(done.textContent).toBe('Added to cart'); // i18n.en.productAdded
    expect(done.getAttribute('aria-hidden')).toBeNull(); // it took over
    expect(idle.getAttribute('aria-hidden')).toBe('true');
    expect(screen.getByRole('status').textContent).toBe('Added to cart');

    fireEvent.click(button);
    expect(onRemove).toHaveBeenCalledOnce();
    expect(onInCartChange).toHaveBeenLastCalledWith(false);
    expect(screen.getByRole('status').textContent).toBe('Remove from cart'); // i18n.en.productRemove
  });

  it('keeps a sold-out card inert', () => {
    const onAdd = vi.fn();
    const { container } = render(<ProductCard title="Mug" price={5} stock="out" onAdd={onAdd} />);
    const button = container.querySelector('button.nx-product-card-add') as HTMLButtonElement;
    expect(button.disabled).toBe(true);
    expect(button.querySelector('.nx-product-card-add-label [data-part="idle"]')?.textContent).toBe('Out of stock'); // i18n.en.productOutOfStock
    fireEvent.click(button);
    expect(onAdd).not.toHaveBeenCalled();
  });

  it('speaks Persian through the provider, digits and all', () => {
    const { container } = render(
      <NabuXUIProvider locale="fa">
        <ProductCard title="هودی لاجوردی" price={1_800_000} compareAt={2_250_000} rating={4.5} ratingCount={128} stock="low" stockCount={2} />
      </NabuXUIProvider>,
    );
    const button = container.querySelector('button.nx-product-card-add')!;
    expect(button.querySelector('.nx-product-card-add-label [data-part="idle"]')?.textContent).toBe('افزودن به سبد'); // i18n.fa.productAddToCart
    expect(screen.getByText('۲۰٪ تخفیف')).toBeTruthy(); // i18n.fa.productDiscount
    expect(screen.getByText('فقط ۲ عدد مانده')).toBeTruthy(); // i18n.fa.productLowStock
    expect(screen.getByText('۱۲۸ نظر')).toBeTruthy(); // i18n.fa.productReviews
    // The price rolls Persian digits: 1,800,000 with fa-IR grouping.
    expect(container.querySelector('.nx-product-card-price')?.textContent).toMatch(/۱[,٬٫]۸۰۰[,٬٫]۰۰۰/);
  });

  it('falls back to English for a locale the table does not know', () => {
    const unknown = 'xx' as Locale;
    expect(() =>
      render(
        <NabuXUIProvider locale={unknown}>
          <ProductCard title="Mug" price={5} rating={4} />
        </NabuXUIProvider>,
      ),
    ).not.toThrow();
    expect(screen.getByRole('img', { name: '4 of 5 stars' })).toBeTruthy(); // the English table
    expect(screen.getByText('Mug')).toBeTruthy();
  });
});

/* ---- OrderTracking ------------------------------------------------------------------------ */

const steps = [
  { id: 'placed', title: 'Order placed', time: 'Monday' },
  { id: 'packed', title: 'Packed', time: 'Tuesday' },
  { title: 'Out for delivery' },
];

describe('OrderTracking', () => {
  it('renders the header facts, the route, and the event log', () => {
    const { container } = render(
      <OrderTracking
        number="1404-08215"
        status="running"
        carrier="Tipax"
        eta="Tuesday, 10 AM"
        current="packed"
        steps={steps}
        events={[
          { id: 'e1', title: 'Left the warehouse', place: 'Tehran hub', time: 'Yesterday, 18:20', tone: 'success' as const },
          { id: 'e2', title: 'Customs cleared', time: new Date(Date.now() - 3 * 60_000) },
        ]}
      />,
    );
    expect(screen.getByText('Order tracking')).toBeTruthy(); // i18n.en.orderTracking (the kicker)
    expect(screen.getByText('1404-08215')).toBeTruthy();
    expect(screen.getByText('Carrier')).toBeTruthy(); // i18n.en.orderCarrier
    expect(screen.getByText('Tipax')).toBeTruthy();
    expect(screen.getByText('Expected delivery')).toBeTruthy(); // i18n.en.orderEta
    const badge = container.querySelector('.nx-status-badge')!;
    expect(badge.getAttribute('data-status')).toBe('running');
    expect(badge.getAttribute('role')).toBe('status');
    expect(badge.querySelector('.nx-visually-hidden')?.textContent).toBe('Running');

    // The route: complete, current (by id), upcoming — markers in the reader's digits.
    const markers = container.querySelectorAll('.nx-order-tracking-marker');
    expect(markers[1]?.textContent).toBe('2');
    expect(markers[2]?.textContent).toBe('3');
    const route = container.querySelectorAll('.nx-order-tracking-step');
    expect(route[0]?.getAttribute('data-state')).toBe('complete');
    expect(route[1]?.getAttribute('data-state')).toBe('current');
    expect(route[1]?.getAttribute('aria-current')).toBe('step');
    expect(route[2]?.getAttribute('data-state')).toBe('upcoming');

    // The log: plain-label times pass through; real moments go relative.
    expect(screen.getByText('Events')).toBeTruthy(); // i18n.en.orderEvents
    expect(screen.getByText('Left the warehouse')).toBeTruthy();
    expect(screen.getByText('Tehran hub')).toBeTruthy();
    expect(screen.getByText('Yesterday, 18:20')).toBeTruthy();
    expect(container.querySelector('.nx-order-tracking-event-time[datetime]')?.textContent).toBe('3 minutes ago');
    expect(container.querySelector('.nx-order-tracking-event[data-tone="success"]')).toBeTruthy();
  });

  it('takes the current step by index and shows the quiet log without events', () => {
    render(<OrderTracking steps={steps} current={2} events={[]} />);
    const route = document.querySelectorAll('.nx-order-tracking-step');
    expect(route[2]?.getAttribute('aria-current')).toBe('step');
    expect(screen.getByText('No events yet')).toBeTruthy(); // i18n.en.orderNoEvents
  });

  it('speaks Persian through the provider, markers in Persian digits', () => {
    const { container } = render(
      <NabuXUIProvider locale="fa">
        <OrderTracking number="۱۴۰۴-۰۸۲۱۵" current={1} steps={steps} carrier="تیپاکس" status="running" />
      </NabuXUIProvider>,
    );
    expect(screen.getByText('رهگیری سفارش')).toBeTruthy(); // i18n.fa.orderTracking
    expect(screen.getByText('شرکت حمل')).toBeTruthy(); // i18n.fa.orderCarrier
    expect(screen.getByText('تیپاکس')).toBeTruthy();
    expect(screen.getByText('رویدادها')).toBeTruthy(); // i18n.fa.orderEvents
    expect(container.querySelectorAll('.nx-order-tracking-marker')[1]?.textContent).toBe('۲');
    expect(container.querySelector('.nx-status-badge')?.querySelector('.nx-visually-hidden')?.textContent).toBe('در حال اجرا');
  });

  it('falls back to English for a locale the table does not know', () => {
    const unknown = 'xx' as Locale;
    expect(() =>
      render(
        <NabuXUIProvider locale={unknown}>
          <OrderTracking steps={steps} events={[{ title: 'Left', time: 'Monday' }]} />
        </NabuXUIProvider>,
      ),
    ).not.toThrow();
    expect(screen.getByText('Order tracking')).toBeTruthy();
    expect(screen.getByText('Left')).toBeTruthy(); // the event rendered
  });
});

/* ---- ProfileCard -------------------------------------------------------------------------- */

describe('ProfileCard', () => {
  const stats = [
    { label: 'Followers', value: 128 },
    { label: 'Projects', value: 12 },
  ];
  const tabs = [
    { id: 'work', label: 'Work', content: 'Portfolio pieces live here' },
    { id: 'about', label: 'About', content: 'Coffee, code, repeat' },
  ];

  it('renders the identity block, the rolling stats, and the verified seal', () => {
    const { container } = render(
      <ProfileCard name="Sara Mohseni" role="Design lead" handle="@sararazi" verified stats={stats} />,
    );
    expect(screen.getByText('Sara Mohseni')).toBeTruthy();
    expect(screen.getByText('Design lead')).toBeTruthy();
    expect(screen.getByText('@sararazi')).toBeTruthy();
    expect(screen.getByText('Verified account')).toBeTruthy(); // i18n.en.profileVerified
    const avatar = container.querySelector('.nx-profile-card-avatar')!;
    expect(avatar.getAttribute('aria-label')).toBe('Sara Mohseni');
    expect(avatar.querySelector('[aria-hidden="true"]')?.textContent).toBe('SM'); // the initials fallback
    expect(screen.getByText('Followers')).toBeTruthy();
    expect(screen.getByText('Projects')).toBeTruthy();
    // The figures roll through NumberTicker: the number is the visually-hidden copy.
    const figures = Array.from(container.querySelectorAll('.nx-profile-card-stat')).map(
      (stat) => stat.querySelector('.nx-visually-hidden')?.textContent,
    );
    expect(figures).toEqual(['128', '12']);
  });

  it('morphs the follow button, rolls the follower count, and reports both ways', () => {
    const onFollow = vi.fn();
    const onUnfollow = vi.fn();
    const { container } = render(
      <ProfileCard name="Sara Mohseni" stats={stats} followersStat={0} onFollow={onFollow} onUnfollow={onUnfollow} />,
    );
    const button = container.querySelector('button.nx-profile-card-follow') as HTMLButtonElement;
    const idle = button.querySelector('.nx-profile-card-follow-label [data-part="idle"]')!;
    const done = button.querySelector('.nx-profile-card-follow-label [data-part="done"]')!;
    expect(idle.textContent).toBe('Follow'); // i18n.en.profileFollow

    fireEvent.click(button);
    expect(onFollow).toHaveBeenCalledOnce();
    expect(button.getAttribute('data-following')).toBe('');
    expect(done.textContent).toBe('Following'); // i18n.en.profileFollowing
    expect(done.getAttribute('aria-hidden')).toBeNull();
    expect(screen.getByRole('status').textContent).toBe('Following Sara Mohseni'); // i18n.en.profileNowFollowing
    // The follower figure rolls +1 while the button is held down.
    expect(container.querySelector('.nx-profile-card-stat[data-stat="0"] .nx-visually-hidden')?.textContent).toBe('129');

    fireEvent.click(button);
    expect(onUnfollow).toHaveBeenCalledOnce();
    expect(screen.getByRole('status').textContent).toBe('Unfollowed Sara Mohseni'); // i18n.en.profileUnfollowed
    // The figure rides ±1 from its mount-time value, so unfollowing rolls it home.
    expect(container.querySelector('.nx-profile-card-stat[data-stat="0"] .nx-visually-hidden')?.textContent).toBe('128');
  });

  it('messages through a link or a callback, and walks the tabs', () => {
    const onMessage = vi.fn();
    const onTabChange = vi.fn();
    const first = render(<ProfileCard name="Sara Mohseni" onMessage={onMessage} tabs={tabs} />);
    fireEvent.click(screen.getByRole('button', { name: /Message/ })); // i18n.en.profileMessage
    expect(onMessage).toHaveBeenCalledOnce();
    const tablist = screen.getByRole('tablist', { name: 'Sections' }); // i18n.en.profileSections
    expect(within(tablist).getAllByRole('tab')).toHaveLength(2);
    expect(screen.getByRole('tabpanel').textContent).toBe('Portfolio pieces live here');
    cleanup();

    const second = render(<ProfileCard name="Sara Mohseni" messageHref="/messages/sara" tabs={tabs} onTabChange={onTabChange} />);
    expect(second.container.querySelector('a.nx-profile-card-message')?.getAttribute('href')).toBe('/messages/sara');
    fireEvent.click(screen.getByRole('tab', { name: 'About' }));
    expect(onTabChange).toHaveBeenCalledWith('about');
    expect(screen.getByRole('tabpanel').textContent).toBe('Coffee, code, repeat');
    expect(screen.getByRole('tab', { name: 'About' }).getAttribute('aria-selected')).toBe('true');
  });

  it('speaks Persian through the provider', () => {
    const { container } = render(
      <NabuXUIProvider locale="fa">
        <ProfileCard name="سارا محسنی" role="سرپرست طراحی" stats={stats} followersStat={0} />
      </NabuXUIProvider>,
    );
    const button = container.querySelector('button.nx-profile-card-follow')!;
    expect(button.querySelector('.nx-profile-card-follow-label [data-part="idle"]')?.textContent).toBe('دنبال کردن'); // i18n.fa.profileFollow
    fireEvent.click(button);
    expect(button.querySelector('.nx-profile-card-follow-label [data-part="done"]')?.textContent).toBe('دنبال می‌کنید'); // i18n.fa.profileFollowing
    expect(screen.getByRole('status').textContent).toBe('دنبال کردن سارا محسنی'); // i18n.fa.profileNowFollowing
    expect(container.querySelector('.nx-profile-card-stat[data-stat="0"] .nx-visually-hidden')?.textContent).toBe('۱۲۹');
    expect(screen.getByText('پیام')).toBeTruthy(); // i18n.fa.profileMessage
  });

  it('falls back to English for a locale the table does not know', () => {
    const unknown = 'xx' as Locale;
    expect(() =>
      render(
        <NabuXUIProvider locale={unknown}>
          <ProfileCard name="Sara Mohseni" tabs={tabs} />
        </NabuXUIProvider>,
      ),
    ).not.toThrow();
    expect(screen.getByRole('tablist', { name: 'Sections' })).toBeTruthy(); // the English table
    expect(screen.getByText('Sara Mohseni')).toBeTruthy();
  });
});
