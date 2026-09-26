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
export * from './components/hero';
export * from './components/pricing';
export * from './components/feedback';
export * from './components/display';
export * from './components/navigation';
export * from './components/overlay';
export * from './components/header';

// The framework-agnostic helpers, for pages that need them directly.
export { theme, transition, toasts, type ToastInput, type ToastTone } from '@nabuxai/ui-core';
