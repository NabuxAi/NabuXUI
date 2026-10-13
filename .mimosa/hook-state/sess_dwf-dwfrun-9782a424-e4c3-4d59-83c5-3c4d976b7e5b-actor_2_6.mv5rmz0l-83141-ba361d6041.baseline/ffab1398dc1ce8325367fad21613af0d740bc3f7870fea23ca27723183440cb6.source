/**
 * Alpine part for the product card block. The Blade component renders the
 * whole card server-side (image, prices, stars, stock badge, the morphing
 * add-to-cart button); this owns the cart toggle — the optimistic flip of
 * the button's morph plus the spoken announcement, the nx-add / nx-remove
 * events, and the optional Livewire round-trip. Out-of-stock cards never
 * reach here: their button ships disabled.
 *
 * Register from the package's installer when the block is wired up:
 *
 *   import { installProductCardBlocks } from './alpine/blocks/product-card';
 *   installProductCardBlocks(Alpine);   // x-data="nxProductCard(config)"
 */
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;

/** The bits of Livewire's $wire these parts use (absent outside Livewire). */
type Wire = { call: (method: string, ...params: unknown[]) => Promise<unknown> };

export interface ProductCardLabels {
  added: string;
  removed: string;
}

export interface ProductCardConfig {
  inCart?: boolean;
  /** Livewire method names called after the optimistic flip (addToCart/removeFromCart). */
  addAction?: string | null;
  removeAction?: string | null;
  /** Context the nx-add / nx-remove events carry. */
  title?: string | null;
  price?: number | null;
  labels?: Partial<ProductCardLabels>;
}

export function installProductCardBlocks(Alpine: AlpineLike): void {
  Alpine.data('nxProductCard', (config: ProductCardConfig) => {
    const labels = { added: '', removed: '', ...config.labels };

    interface ProductCardSelf {
      inCart: boolean;
      busy: boolean;
      announce: string;
      $root: HTMLElement;
      toggle: () => Promise<void>;
    }

    return {
      inCart: !!config.inCart,
      /** True while a wire action is in flight; the button disables on it. */
      busy: false,
      announce: '',

      /** One press toggles the cart state, then syncs the server if asked. */
      async toggle(this: Self<ProductCardSelf>) {
        if (this.busy) return;
        // Sold-out cards ship a disabled button; guard keyboard-driven calls too.
        if (this.$root.querySelector('.nx-product-card-add')?.matches(':disabled')) return;

        const next = !this.inCart;
        this.inCart = next;
        this.announce = next ? labels.added : labels.removed;
        this.$dispatch(next ? 'nx-add' : 'nx-remove', { title: config.title, price: config.price });

        const action = next ? config.addAction : config.removeAction;
        if (!action) return;
        const wire = (this as unknown as { $wire?: Wire }).$wire;
        if (!wire) return;
        this.busy = true;
        try {
          await wire.call(action, config.title, config.price);
        } finally {
          this.busy = false;
        }
      },
    };
  });
}
