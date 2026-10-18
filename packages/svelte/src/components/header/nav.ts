import type { Snippet } from 'svelte';
import type { IconName } from '@nabuxai/ui-core';

/** The shared navigation model of the header seat — ui-react's header.tsx shapes. */
export interface NavChild {
  label: string | Snippet;
  href: string;
  description?: string | Snippet;
  icon?: IconName;
}

export interface NavItem {
  label: string | Snippet;
  /** A plain link… */
  href?: string;
  current?: boolean;
  /** …or a panel of links (mega menu on desktop, a sub-level on mobile). */
  children?: NavChild[];
  /** Columns for the panel's link grid. */
  columns?: number;
  /** Extra content in the panel beside the links (a featured card, say). */
  aside?: string | Snippet;
}
