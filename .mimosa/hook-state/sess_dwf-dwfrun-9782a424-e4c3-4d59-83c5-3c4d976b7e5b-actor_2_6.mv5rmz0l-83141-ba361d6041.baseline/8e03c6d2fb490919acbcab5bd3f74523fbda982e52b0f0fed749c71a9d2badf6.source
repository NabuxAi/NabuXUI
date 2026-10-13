/**
 * Alpine part for the profile card block. The Blade component renders the
 * cover, avatar, stats, buttons and small tabs server-side; this owns the
 * follow toggle — the optimistic flip of the button's morph plus the rolling
 * ±1 of the followers stat, the spoken announcement, the nx-follow /
 * nx-unfollow / nx-message events, and the optional Livewire round-trip —
 * and the tabs: the springing indicator, the roving keyboard focus and the
 * aria bookkeeping, the same behaviours the React component implements over
 * the same CSS.
 *
 * Register from the package's installer when the block is wired up:
 *
 *   import { installProfileCardBlocks } from './alpine/blocks/profile-card';
 *   installProfileCardBlocks(Alpine);   // x-data="nxProfileCard(config)"
 */
import { indicator } from '@nabuxai/ui-core';
import { renderNumber } from '../number';
import type { AlpineLike, Magics } from '../types';

type Self<T> = T & Magics;

/** The bits of Livewire's $wire these parts use (absent outside Livewire). */
type Wire = { call: (method: string, ...params: unknown[]) => Promise<unknown> };
type LivewireGlobal = { hook?: (name: string, callback: (payload: { el: Element }) => void) => (() => void) | void };

export interface ProfileCardLabels {
  nowFollowing: string;
  unfollowed: string;
}

export interface ProfileCardConfig {
  following?: boolean;
  tab?: string;
  /** Livewire method names called after the optimistic flip (follow/unfollow/message). */
  followAction?: string | null;
  unfollowAction?: string | null;
  messageAction?: string | null;
  /** Context the events and announcements carry. */
  name?: string | null;
  /** The index into `stats` of the follower count; it rolls ±1 with the button. */
  followersStat?: number | null;
  /** The stat values, in order — the source the optimistic roll starts from. */
  stats?: number[];
  locale?: string;
  labels?: Partial<ProfileCardLabels>;
}

/** `{name}` → the value, in the template the core i18n table wrote. */
const withParams = (template: string, params: Record<string, string | number>) =>
  template.replace(/\{(\w+)\}/g, (_, name: string) => String(params[name] ?? `{${name}}`));

/** Arrow keys along the tab row: the next index, null to leave, undefined when not handled. */
function stepIndex(event: KeyboardEvent, active: number, count: number, dir: 1 | -1): number | null | undefined {
  if (!count) return undefined;
  switch (event.key) {
    case 'ArrowRight':
      return Math.min(count - 1, Math.max(0, active + dir));
    case 'ArrowLeft':
      return Math.min(count - 1, Math.max(0, active - dir));
    case 'Home':
      return 0;
    case 'End':
      return count - 1;
    case 'Escape':
      return null;
    default:
      return undefined;
  }
}

export function installProfileCardBlocks(Alpine: AlpineLike): void {
  Alpine.data('nxProfileCard', (config: ProfileCardConfig) => {
    const stats = (config.stats ?? []).map((value) => value);
    let tabs: ReturnType<typeof indicator> | null = null;
    /** The tab ids in row order — the roving focus and the keys must agree. */
    let keys: string[] = [];
    let unhook: () => void = () => {};

    interface ProfileCardSelf {
      following: boolean;
      tab: string;
      busy: boolean;
      announce: string;
      $root: HTMLElement;
      moveTab: () => void;
      toggle: () => Promise<void>;
      message: () => Promise<void>;
      select: (tab: string) => void;
      tabKey: (event: KeyboardEvent) => void;
      paintFollowers: () => void;
    }

    return {
      following: !!config.following,
      /** The open section; the panels and the tab row both follow it. */
      tab: (config.tab ?? '') as string,
      /** True while a wire action is in flight; the follow button disables on it. */
      busy: false,
      announce: '',

      init(this: Self<ProfileCardSelf>) {
        // The tab row the indicator lives in (absent when the card has no tabs).
        const row = this.$root.querySelector<HTMLElement>('.nx-profile-card-tabs');
        if (row) {
          keys = Array.from(row.querySelectorAll<HTMLButtonElement>('[role="tab"]'))
            .map((button) => button.dataset.value ?? '')
            .filter(Boolean);
          tabs = indicator(row);
          this.moveTab();
        }
        this.$watch('tab', () => this.moveTab());

        // A Livewire re-render re-renders everything server-side; keep the
        // indicator placed after the morph.
        const livewire = (window as unknown as { Livewire?: LivewireGlobal }).Livewire;
        const root = this.$root;
        if (livewire?.hook) {
          unhook =
            livewire.hook('morphed', ({ el }) => {
              if (el.contains(root)) requestAnimationFrame(() => this.moveTab());
            }) ?? (() => {});
        }
      },

      destroy(this: Self<ProfileCardSelf>) {
        unhook();
        tabs?.destroy();
      },

      /** Park the springing indicator on the open tab (the row keeps it measured). */
      moveTab(this: Self<ProfileCardSelf>) {
        const row = this.$root.querySelector<HTMLElement>('.nx-profile-card-tabs');
        if (!row || !tabs) return;
        tabs.update(row.querySelector(`[data-value="${CSS.escape(this.tab)}"]`));
      },

      /* ---- Follow: the optimistic morph, then the server ----------------------- */

      async toggle(this: Self<ProfileCardSelf>) {
        if (this.busy) return;
        const next = !this.following;
        this.following = next;
        const template = next ? config.labels?.nowFollowing : config.labels?.unfollowed;
        this.announce = template === undefined ? '' : withParams(template, { name: config.name ?? '' });
        this.$dispatch(next ? 'nx-follow' : 'nx-unfollow', { name: config.name });
        this.paintFollowers();

        const action = next ? config.followAction : config.unfollowAction;
        if (!action) return;
        const wire = (this as unknown as { $wire?: Wire }).$wire;
        if (!wire) return;
        this.busy = true;
        try {
          await wire.call(action);
        } finally {
          this.busy = false;
        }
      },

      /** The followers stat rolls ±1 with the button, until the server's number lands. */
      paintFollowers(this: Self<ProfileCardSelf>) {
        const index = config.followersStat;
        if (index === null || index === undefined) return;
        const number = this.$root.querySelector<HTMLElement>(`.nx-profile-card-stat[data-stat="${index}"] .nx-number`);
        if (!number || stats[index] === undefined) return;
        const value = stats[index]! + (this.following === !!config.following ? 0 : this.following ? 1 : -1);
        renderNumber(number, value, config.locale);
      },

      /* ---- Message: a wire method, or an event for the app ---------------------- */

      async message(this: Self<ProfileCardSelf>) {
        this.$dispatch('nx-message', { name: config.name });
        if (!config.messageAction) return;
        const wire = (this as unknown as { $wire?: Wire }).$wire;
        if (!wire) return;
        this.busy = true;
        try {
          await wire.call(config.messageAction);
        } finally {
          this.busy = false;
        }
      },

      /* ---- The small tabs -------------------------------------------------------- */

      select(this: Self<ProfileCardSelf>, tab: string) {
        this.tab = tab;
      },

      /** Roving arrows along the row (RTL aware); focus follows the selection. */
      tabKey(this: Self<ProfileCardSelf>, event: KeyboardEvent) {
        const row = this.$root.querySelector<HTMLElement>('.nx-profile-card-tabs');
        if (!row) return;
        const dir = getComputedStyle(row).direction === 'rtl' ? -1 : 1;
        const next = stepIndex(event, Math.max(0, keys.indexOf(this.tab)), keys.length, dir);
        if (next === undefined || next === null) return;
        event.preventDefault();
        this.tab = keys[next] ?? this.tab;
        row.querySelectorAll<HTMLButtonElement>('[role="tab"]')[next]?.focus();
      },
    };
  });
}
