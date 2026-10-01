/**
 * @nabuxai/ui-react — NabuXUI for React (Vite, Next.js, Inertia).
 *
 *   import '@nabuxai/ui-react/css';
 *   import { Button, Toaster, toast } from '@nabuxai/ui-react';
 */
export { NabuXUIProvider, SmartLink, useLocale, useT, type LinkComponent, type NabuXUIProviderProps } from './internal/provider';
export { Icon, type IconProps, type IconName } from './internal/icon';
export { cx, useBehavior, useControllable } from './internal/hooks';

export * from './components/text';
export * from './components/button';
export * from './components/form';
export * from './components/card';
export * from './components/chart';
// Hero's decorative backdrop is aliased so the plain `Backdrop`/`BackdropVariant`
// names stay with the content-carrying blocks/backdrops block: the hero layer is
// `HeroBackdrop` (a layer that sits behind a positioned section), the block is
// `Backdrop` (the section itself).
export { Hero, type HeroProps, Backdrop as HeroBackdrop, type BackdropVariant as HeroBackdropVariant } from './components/hero';
export * from './components/pricing';
export * from './components/feedback';
export * from './components/display';
export * from './components/navigation';
export * from './components/overlay';
export * from './components/header';

export * from './blocks/text';
export * from './blocks/actions';
export * from './blocks/cards';
export * from './blocks/data';
export * from './blocks/menus';
export * from './blocks/chip-filter';
export * from './blocks/stat-strip';
export * from './blocks/sort-pill';
export * from './blocks/glass';
export * from './blocks/backdrops';
export * from './blocks/admin-shell';
export * from './blocks/auth';
export * from './blocks/calendar';
export * from './blocks/chat';
export * from './blocks/empty';
export * from './blocks/invoice';
export * from './blocks/kanban';
export * from './blocks/timeline-feed';
export * from './blocks/email';
export * from './blocks/file-manager';
export * from './blocks/todo';
export * from './blocks/product-card';
export * from './blocks/order-tracking';
export * from './blocks/bar-chart';
export * from './blocks/donut-chart';
export * from './blocks/gauge';
export * from './blocks/tree-view';
export * from './blocks/gantt';
export * from './blocks/wizard';
export * from './blocks/profile-card';

// The framework-agnostic helpers, for pages that need them directly.
export { theme, transition, toasts, type ToastInput, type ToastTone } from '@nabuxai/ui-core';
