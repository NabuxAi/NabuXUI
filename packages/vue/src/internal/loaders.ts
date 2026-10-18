/**
 * The loaders' shared shapes — the wedge paths and per-size dimensions at the
 * bottom of ui-react's components/feedback.tsx.
 */

export const LOADER_SIZE = { sm: '2.25rem', md: '3.5rem', lg: '5rem' } as const;

export const CUNEIFORM_SIZE = { sm: '3rem', md: '4.5rem', lg: '6.5rem' } as const;

/** Wedges pressed into clay one after another — the Nabu scribe at work. */
export const WEDGES = [
  { transform: 'translate(2 7)', d: 'M0 0.5L9 5L0 9.5ZM7.5 4.4H19V5.6H7.5Z' },
  { transform: 'translate(24 1) rotate(90 5 5)', d: 'M0 0.5L9 5L0 9.5ZM7.5 4.4H19V5.6H7.5Z' },
  { transform: 'translate(34 7)', d: 'M0 0.5L9 5L0 9.5ZM7.5 4.4H19V5.6H7.5Z' },
  { transform: 'translate(52 5)', d: 'M10 0L0 7L10 14L6.5 7Z' },
] as const;

export const ORBIT_RINGS = [
  { r: '0deg', c: 'var(--nx-lapis-500)', d: '1.6s' },
  { r: '60deg', c: 'var(--nx-cyan-400)', d: '2.1s' },
  { r: '120deg', c: 'var(--nx-violet-500)', d: '2.6s' },
] as const;

/** The tone → icon table every toaster and alert draws from. */
export const TONE_ICON = {
  neutral: 'bell',
  accent: 'sparkles',
  success: 'check-circle',
  warning: 'alert-triangle',
  danger: 'alert-circle',
  info: 'info',
} as const;
