/**
 * @nabuxai/ui-core — the framework-agnostic half of NabuXUI.
 *
 * Tokens and component CSS live in ./css; these functions drive the motion that
 * CSS cannot do alone. Each behaviour takes an element and returns a cleanup,
 * so React hooks and Alpine directives wrap the same code.
 */
export * from './env';
export * from './spring';
export { palette, light, dark, scale, springTokens, reducedMotion } from './tokens';
export * from './reveal';
export * from './pointer';
export * from './indicator';
export * from './place';
export * from './roving';
export * from './swipe';
export * from './presence';
export * from './text';
export * from './toast';
export * from './theme';
export * from './view-transition';
export * from './charts';
export * from './effects';
export * from './icons';
export * from './i18n';
