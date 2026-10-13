/**
 * The product card block (React): a shop card — the image zooms inside its
 * frame, the pre-discount price is crossed out beside the current one, stars
 * fill to the fraction of the rating, the stock badge speaks in states, and
 * the add-to-cart button morphs into its added state (icon swap + label
 * crossfade + accent→success recolour; css/blocks/product-card.css). `inCart`
 * is the truth: controlled (`inCart` + `onInCartChange`) or uncontrolled
 * (`defaultInCart`), with `onAdd` / `onRemove` detail callbacks.
 */
import {
  type CSSProperties,
  type HTMLAttributes,
  type ReactNode,
  useMemo,
  useRef,
  useState,
} from 'react';
import type { MessageKey } from '@nabuxai/ui-core';
import { reveal } from '@nabuxai/ui-core';
import { cx, useBehavior, useControllable, useEvent } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useLocale, useT } from '../internal/provider';

const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;
const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

export type ProductStock = 'in' | 'low' | 'out';

/** The words the card says itself; override any of them with `labels`. */
type ProductWord =
  | 'addToCart' | 'added' | 'remove'
  | 'inStock' | 'lowStock' | 'outOfStock'
  | 'discount' | 'reviews' | 'stars';

/** Where each of them lives in the core i18n table. */
const WORD_KEYS: Record<ProductWord, MessageKey> = {
  addToCart: 'productAddToCart',
  added: 'productAdded',
  remove: 'productRemove',
  inStock: 'productInStock',
  lowStock: 'productLowStock',
  outOfStock: 'productOutOfStock',
  discount: 'productDiscount',
  reviews: 'productReviews',
  stars: 'stars',
};

export interface ProductCardProps extends Omit<HTMLAttributes<HTMLElement>, 'children' | 'title'> {
  title: ReactNode;
  /** The title becomes a link (through the app's router for internal paths). */
  href?: string;
  category?: ReactNode;
  image?: { src: string; alt?: string };
  price: number;
  /** The price before the discount; crossing it out shows the saving. */
  compareAt?: number;
  /** The unit shown beside every figure: "$", "تومان", "USD"… */
  currency?: ReactNode;
  /** Fraction digits (0 for tomans, 2 for dollars). */
  decimals?: number;
  /** 0–5; halves render as a partial star. */
  rating?: number;
  /** How many reviews the rating counts; hidden when absent. */
  ratingCount?: number;
  stock?: ProductStock;
  /** Units left, spoken by the low-stock badge ("Only 2 left"). */
  stockCount?: number;
  inCart?: boolean;
  defaultInCart?: boolean;
  onInCartChange?: (inCart: boolean) => void;
  onAdd?: () => void;
  onRemove?: () => void;
  labels?: Partial<Record<ProductWord, string>>;
  locale?: string;
  /** No motion feedback (press scale, hover lift) where it would distract. */
  static?: boolean;
}

export function ProductCard({
  title,
  href,
  category,
  image,
  price,
  compareAt,
  currency,
  decimals = 0,
  rating,
  ratingCount,
  stock,
  stockCount,
  inCart: controlled,
  defaultInCart = false,
  onInCartChange,
  onAdd,
  onRemove,
  labels,
  locale,
  static: still,
  className,
  ...rest
}: ProductCardProps) {
  const t = useT();
  const language = useLocale();
  const intl = locale ?? INTL[language];
  // `labels` may override any word; the rest come from the core table.
  const say = (key: ProductWord, params: Record<string, string | number> = {}) => {
    const text = labels?.[key] ?? t(WORD_KEYS[key]);
    return text.replace(/\{(\w+)\}/g, (_, name: string) => String(params[name] ?? `{${name}}`));
  };
  const [inCart, setInCart] = useControllable(controlled, defaultInCart, onInCartChange);
  const [announced, setAnnounced] = useState('');
  const root = useRef<HTMLElement>(null);
  useBehavior(root, reveal, { once: true });

  const money = useMemo(
    () => new Intl.NumberFormat(intl, { minimumFractionDigits: decimals, maximumFractionDigits: decimals }),
    [intl, decimals],
  );
  const plain = useMemo(() => new Intl.NumberFormat(intl, { maximumFractionDigits: 1 }), [intl]);
  const counted = useMemo(() => new Intl.NumberFormat(intl), [intl]);

  /** The saving, as a whole percent — only when the price really dropped. */
  const percent = useMemo(() => {
    if (compareAt === undefined || !(compareAt > price) || compareAt <= 0) return null;
    return Math.round((1 - price / compareAt) * 100);
  }, [price, compareAt]);

  const stars = rating !== undefined && rating > 0 ? Math.min(Math.max(rating, 0), 5) : null;
  const soldOut = stock === 'out';

  const stockText =
    stock === 'in' ? say('inStock')
    : stock === 'low' ? say('lowStock', { count: counted.format(stockCount ?? 1) })
    : stock === 'out' ? say('outOfStock')
    : null;

  // One press toggles the cart state; both sides also report through callbacks.
  const toggle = useEvent(() => {
    if (soldOut) return;
    const next = !inCart;
    setInCart(next);
    if (next) onAdd?.();
    else onRemove?.();
    setAnnounced(next ? say('added') : say('remove'));
  });

  const heading = (
    <h3 className="nx-product-card-title">
      {href ? (
        <SmartLink href={href} className="nx-product-card-link">
          {title}
        </SmartLink>
      ) : (
        title
      )}
    </h3>
  );

  return (
    <article
      ref={root}
      className={cx('nx-product-card', className)}
      data-stock={stock}
      data-nx-reveal=""
      {...rest}
    >
      {image && (
        <div className="nx-product-card-media">
          <img className="nx-product-card-img" src={image.src} alt={image.alt ?? (typeof title === 'string' ? title : '')} loading="lazy" />
          {(percent !== null || stock) && (
            <div className="nx-product-card-tags">
              {percent !== null && <span className="nx-product-card-discount">{say('discount', { value: counted.format(percent) })}</span>}
              {stockText && (
                <span className="nx-product-card-stock" data-stock={stock}>
                  <i className="nx-product-card-stock-dot" aria-hidden="true" />
                  <span>{stockText}</span>
                </span>
              )}
            </div>
          )}
        </div>
      )}

      <div className="nx-product-card-body">
        {!image && (percent !== null || stock) && (
          // No photo: the tags ride along the body's top edge instead of over it.
          <div className="nx-product-card-tags">
            {percent !== null && <span className="nx-product-card-discount">{say('discount', { value: counted.format(percent) })}</span>}
            {stockText && (
              <span className="nx-product-card-stock" data-stock={stock}>
                <i className="nx-product-card-stock-dot" aria-hidden="true" />
                <span>{stockText}</span>
              </span>
            )}
          </div>
        )}
        {category && <p className="nx-product-card-category">{category}</p>}
        {heading}

        {(stars !== null || ratingCount !== undefined) && (
          <div
            className="nx-product-card-rating"
            role={stars !== null ? 'img' : undefined}
            aria-label={stars !== null ? say('stars', { count: plain.format(stars), total: counted.format(5) }) : undefined}
          >
            {stars !== null && (
              <span className="nx-product-card-stars" style={vars({ '--nx-product-rating': stars })} aria-hidden="true">
                <span className="nx-product-card-stars-row nx-product-card-stars-bg">
                  {Array.from({ length: 5 }, (_, i) => (
                    <Icon key={i} name="star" />
                  ))}
                </span>
                <span className="nx-product-card-stars-fill">
                  <span className="nx-product-card-stars-row">
                    {Array.from({ length: 5 }, (_, i) => (
                      <Icon key={i} name="star" />
                    ))}
                  </span>
                </span>
              </span>
            )}
            {stars !== null && <span className="nx-product-card-rating-value">{plain.format(stars)}</span>}
            {ratingCount !== undefined && (
              <span className="nx-product-card-rating-count">{say('reviews', { count: counted.format(ratingCount) })}</span>
            )}
          </div>
        )}

        <div className="nx-product-card-pricing">
          <span className="nx-product-card-price">
            {money.format(price)}
            {currency && <span className="nx-product-card-currency">{currency}</span>}
          </span>
          {compareAt !== undefined && compareAt > price && (
            <s className="nx-product-card-compare">
              {money.format(compareAt)}
              {currency && <span className="nx-product-card-currency">{currency}</span>}
            </s>
          )}
        </div>

        <button
          type="button"
          className="nx-product-card-add"
          data-in-cart={inCart ? '' : undefined}
          data-static={still ? '' : undefined}
          disabled={soldOut}
          onClick={toggle}
        >
          <span className="nx-product-card-add-icon" aria-hidden="true">
            <span data-part="idle">
              <Icon name="plus" />
            </span>
            <span data-part="done">
              <Icon name="check" />
            </span>
          </span>
          {/* Both labels live on one grid cell; the resting one is opacity-0,
              so it must also leave the accessibility tree. */}
          <span className="nx-product-card-add-label">
            <span data-part="idle" aria-hidden={inCart || undefined}>
              {soldOut ? say('outOfStock') : say('addToCart')}
            </span>
            <span data-part="done" aria-hidden={!inCart || undefined}>
              {say('added')}
            </span>
          </span>
        </button>
      </div>
      {/* The cart change, spoken once it lands. */}
      <p className="nx-visually-hidden" role="status">
        {announced}
      </p>
    </article>
  );
}
