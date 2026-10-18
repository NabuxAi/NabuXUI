/**
 * The feedback family's public seat parts, mirroring ui-react's
 * components/feedback.tsx — plus the `toast` push function itself, re-exported
 * from the core exactly like the React flavour does.
 */
export { toast } from '@nabuxai/ui-core';
export type { ToastStore, ToastTone } from '@nabuxai/ui-core';

/** Where the toaster sits on the page. */
export type ToasterPosition = 'bottom-end' | 'bottom-start' | 'bottom-center' | 'top-end' | 'top-start' | 'top-center';

export { default as NxToaster } from './nx-toaster.vue';
export { default as NxAlert } from './nx-alert.vue';
export { default as NxBadge } from './nx-badge.vue';
export { NxTooltip } from './nx-tooltip';
export type { NxTooltipProps } from './nx-tooltip';
export { default as NxProgress } from './nx-progress.vue';
export { default as NxSkeleton } from './nx-skeleton.vue';
export { default as NxSpinner } from './nx-spinner.vue';
export { default as NxOrbitLoader } from './nx-orbit-loader.vue';
export { default as NxDotsLoader } from './nx-dots-loader.vue';
export { default as NxCuneiformLoader } from './nx-cuneiform-loader.vue';
