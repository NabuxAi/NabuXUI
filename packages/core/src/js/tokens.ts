/**
 * NabuXUI design tokens — the single source of truth.
 *
 * `scripts/build.mjs` turns this file into `src/css/tokens.css`. The palette is
 * named after the brand's story: lapis (the lapis-lazuli blue of Babylon),
 * gold (the scribe's stylus), clay (the tablet), ink, and cyan (the neural glow).
 *
 * Semantic tokens are declared once per colour scheme and switched by selector
 * (`.dark`, `[data-theme]`, or the system preference), which every browser
 * supports and which already matches how the Nabu Livewire apps mark their
 * theme (`html.dark`).
 */
import { springEasing, springs } from './spring';

export const palette = {
  lapis: {
    50: '#eef0ff', 100: '#e0e3ff', 200: '#c6cbff', 300: '#a3a8ff', 400: '#8482fb',
    500: '#6a63f4', 600: '#5647e6', 700: '#4739ca', 800: '#3b31a3', 900: '#322d80', 950: '#1e1a4a',
  },
  violet: { 300: '#c4b5fd', 400: '#a78bfa', 500: '#8b5cf6', 600: '#7c3aed', 700: '#6d28d9' },
  cyan: { 300: '#7df3ff', 400: '#2ee6fb', 500: '#06c7e3', 600: '#0aa0bb', 700: '#0b7288' },
  gold: { 200: '#ffe7b8', 300: '#ffd68a', 400: '#ffc462', 500: '#f4a93c', 600: '#d98a1c', 700: '#9a5b0f' },
  ink: {
    50: '#f7f7fa', 100: '#eeeff4', 200: '#dfe0e8', 300: '#c3c5d3', 400: '#9a9db3', 500: '#6c7088',
    600: '#555a70', 700: '#3f4258', 800: '#2a2d40', 850: '#1d2031', 900: '#141726', 950: '#0a0c17',
  },
  clay: { 50: '#fcfbf8', 100: '#f7f5ef', 200: '#eeeae0', 300: '#e0dacb' },
  green: { 400: '#3ddc97', 500: '#10b981', 600: '#059669', 700: '#047857' },
  amber: { 400: '#fbbf24', 500: '#f59e0b', 600: '#d97706', 700: '#a45207' },
  red: { 400: '#ff7a7a', 500: '#ef4444', 600: '#dc2626', 700: '#c02424' },
} as const;

const mix = (color: string, amount: number) => `color-mix(in oklab, ${color} ${amount}%, transparent)`;

/**
 * Colours and depth for one colour scheme. Every value is literal (no `var()`),
 * so a subtree that switches scheme recomputes all of them in place.
 */
export interface Scheme {
  [token: string]: string;
}

export const light: Scheme = {
  bg: palette.clay[50],
  'bg-subtle': palette.clay[100],
  surface: '#ffffff',
  'surface-2': '#f4f3ef',
  'surface-3': '#eae8e1',
  'surface-inverse': palette.ink[950],
  'text-inverse': '#f5f6fb',
  border: '#e7e4dc',
  'border-strong': '#8f93a9',
  text: '#12141f',
  'text-muted': palette.ink[600],
  'text-subtle': palette.ink[500],

  accent: palette.lapis[600],
  'accent-hover': palette.lapis[700],
  'accent-text': '#4a3dd6',
  'accent-contrast': '#ffffff',
  'accent-soft': mix(palette.lapis[600], 12),
  'accent-border': mix(palette.lapis[600], 35),

  gold: palette.gold[500],
  'gold-text': palette.gold[700],
  'gold-contrast': '#1a1206',
  'gold-soft': mix(palette.gold[500], 18),
  glow: palette.cyan[500],

  success: palette.green[600],
  'success-text': palette.green[700],
  'success-soft': mix(palette.green[500], 14),
  warning: palette.amber[500],
  'warning-text': palette.amber[700],
  'warning-soft': mix(palette.amber[500], 16),
  danger: palette.red[600],
  'danger-hover': palette.red[700],
  'danger-text': palette.red[700],
  'danger-soft': mix(palette.red[500], 12),
  info: palette.cyan[600],
  'info-text': palette.cyan[700],
  'info-soft': mix(palette.cyan[500], 14),

  ring: palette.lapis[600],
  backdrop: 'rgb(10 12 23 / 0.42)',
  glass: 'rgb(255 255 255 / 0.72)',
  'glass-border': 'rgb(255 255 255 / 0.6)',
  skeleton: '#ebe9e2',
  'skeleton-shine': 'rgb(255 255 255 / 0.75)',
  highlight: 'rgb(255 255 255 / 0.9)',
  'backdrop-intensity': '0.28',
  star: palette.lapis[400],

  'shadow-xs': '0 1px 2px rgb(17 19 31 / 0.06)',
  'shadow-sm': '0 1px 2px rgb(17 19 31 / 0.06), 0 2px 8px -2px rgb(17 19 31 / 0.08)',
  'shadow-md': '0 2px 4px rgb(17 19 31 / 0.04), 0 10px 24px -6px rgb(17 19 31 / 0.12)',
  'shadow-lg': '0 4px 8px rgb(17 19 31 / 0.04), 0 24px 48px -12px rgb(17 19 31 / 0.2)',
  'shadow-xl': '0 8px 16px rgb(17 19 31 / 0.05), 0 40px 80px -20px rgb(17 19 31 / 0.3)',
  'shadow-glow': `0 0 0 1px ${mix(palette.lapis[600], 25)}, 0 10px 32px -8px ${mix(palette.lapis[600], 55)}`,

  /* Chart series, in fixed order. Validated with the dataviz palette checks
     (lightness band, chroma floor, adjacent CVD ΔE ≥ 13, contrast ≥ 3:1) on #fff. */
  'chart-1': '#5647e6',
  'chart-2': '#d6428a',
  'chart-3': '#b8870b',
  'chart-4': '#0f9fb4',
  'chart-5': '#e07a12',
  'chart-6': '#9a6bf0',
  'chart-7': '#1f9d57',
  'chart-grid': '#ebe9e3',

  'gradient-brand': `linear-gradient(135deg, ${palette.lapis[500]}, ${palette.violet[600]})`,
  'gradient-text': `linear-gradient(100deg, ${palette.lapis[700]}, ${palette.violet[600]} 45%, ${palette.cyan[600]})`,
  'gradient-gold': `linear-gradient(100deg, ${palette.gold[500]}, ${palette.gold[600]})`,
  'gradient-aurora': `conic-gradient(from 210deg, ${palette.lapis[500]}, ${palette.violet[500]}, ${palette.cyan[400]}, ${palette.gold[400]}, ${palette.lapis[500]})`,
};

export const dark: Scheme = {
  bg: '#070812',
  'bg-subtle': '#0b0d19',
  surface: '#0f1120',
  'surface-2': '#161a2c',
  'surface-3': '#1f2338',
  'surface-inverse': '#f5f6fb',
  'text-inverse': palette.ink[950],
  border: '#23273f',
  'border-strong': '#5b6083',
  text: '#eef0f8',
  'text-muted': '#a4a8bf',
  'text-subtle': '#80859e',

  accent: '#6259f0',
  'accent-hover': palette.lapis[400],
  'accent-text': '#aeb1ff',
  'accent-contrast': '#ffffff',
  'accent-soft': mix(palette.lapis[500], 20),
  'accent-border': mix(palette.lapis[400], 40),

  gold: palette.gold[400],
  'gold-text': '#ffcf7a',
  'gold-contrast': '#1a1206',
  'gold-soft': mix(palette.gold[400], 16),
  glow: palette.cyan[400],

  success: palette.green[500],
  'success-text': palette.green[400],
  'success-soft': mix(palette.green[500], 18),
  warning: palette.amber[400],
  'warning-text': palette.amber[400],
  'warning-soft': mix(palette.amber[400], 16),
  danger: palette.red[600],
  'danger-hover': palette.red[500],
  'danger-text': palette.red[400],
  'danger-soft': mix(palette.red[500], 18),
  info: palette.cyan[400],
  'info-text': '#40e0f5',
  'info-soft': mix(palette.cyan[400], 16),

  ring: '#aeb1ff',
  backdrop: 'rgb(2 3 8 / 0.62)',
  glass: 'rgb(18 21 36 / 0.62)',
  'glass-border': 'rgb(255 255 255 / 0.08)',
  skeleton: '#191c2e',
  'skeleton-shine': 'rgb(255 255 255 / 0.06)',
  highlight: 'rgb(255 255 255 / 0.07)',
  'backdrop-intensity': '0.45',
  star: '#ffffff',

  'shadow-xs': '0 1px 2px rgb(0 0 0 / 0.4)',
  'shadow-sm': '0 1px 2px rgb(0 0 0 / 0.4), 0 2px 8px -2px rgb(0 0 0 / 0.45)',
  'shadow-md': '0 2px 4px rgb(0 0 0 / 0.35), 0 12px 28px -6px rgb(0 0 0 / 0.55)',
  'shadow-lg': '0 4px 8px rgb(0 0 0 / 0.35), 0 28px 56px -12px rgb(0 0 0 / 0.65)',
  'shadow-xl': '0 8px 16px rgb(0 0 0 / 0.4), 0 44px 88px -20px rgb(0 0 0 / 0.75)',
  'shadow-glow': `0 0 0 1px ${mix(palette.lapis[400], 30)}, 0 12px 40px -8px ${mix(palette.lapis[500], 70)}`,

  /* The same seven hues stepped for the dark surface (validated on #0f1120). */
  'chart-1': '#7c78f6',
  'chart-2': '#d9508f',
  'chart-3': '#b08812',
  'chart-4': '#159fb3',
  'chart-5': '#d27421',
  'chart-6': '#9270ea',
  'chart-7': '#27a05f',
  'chart-grid': '#20243a',

  'gradient-brand': `linear-gradient(135deg, ${palette.lapis[500]}, ${palette.violet[600]})`,
  'gradient-text': `linear-gradient(100deg, ${palette.lapis[300]}, ${palette.violet[300]} 45%, ${palette.cyan[300]})`,
  'gradient-gold': `linear-gradient(100deg, ${palette.gold[300]}, ${palette.gold[500]})`,
  'gradient-aurora': `conic-gradient(from 210deg, ${palette.lapis[500]}, ${palette.violet[500]}, ${palette.cyan[400]}, ${palette.gold[400]}, ${palette.lapis[500]})`,
};

/** Tokens that do not change with the colour scheme. */
export const scale = {
  'font-sans': '"Inter", "Vazirmatn", ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif',
  'font-display': '"Bricolage Grotesque", "Vazirmatn", "Inter", ui-sans-serif, system-ui, sans-serif',
  'font-mono': '"JetBrains Mono", ui-monospace, "SFMono-Regular", Menlo, monospace',

  'text-xs': '0.75rem',
  'text-sm': '0.875rem',
  'text-md': '1rem',
  'text-lg': '1.125rem',
  'text-xl': '1.25rem',
  'text-2xl': '1.5rem',
  'text-3xl': '1.875rem',
  'text-4xl': 'clamp(1.875rem, 1.4rem + 1.6vw, 2.5rem)',
  'text-5xl': 'clamp(2.25rem, 1.5rem + 3vw, 3.5rem)',
  'text-display': 'clamp(2.6rem, 1.4rem + 5vw, 5rem)',

  'space-1': '0.25rem',
  'space-2': '0.5rem',
  'space-3': '0.75rem',
  'space-4': '1rem',
  'space-5': '1.25rem',
  'space-6': '1.5rem',
  'space-8': '2rem',
  'space-10': '2.5rem',
  'space-12': '3rem',
  'space-16': '4rem',
  'space-24': '6rem',

  /* Letter-spacing. Persian and Arabic set all of these to 0 (base.css):
     tracking pulls joined letters apart. */
  'tracking-tightest': '-0.035em',
  'tracking-tighter': '-0.025em',
  'tracking-tight': '-0.01em',
  'tracking-wide': '0.02em',
  'tracking-wider': '0.08em',

  'radius-xs': '6px',
  'radius-sm': '8px',
  'radius-md': '12px',
  'radius-lg': '16px',
  'radius-xl': '22px',
  'radius-2xl': '28px',
  'radius-full': '999px',

  'z-sticky': '40',
  'z-dropdown': '50',
  'z-overlay': '60',
  'z-toast': '70',
  'z-tooltip': '80',

  'dur-instant': '90ms',
  'dur-fast': '150ms',
  'dur-base': '220ms',
  'dur-slow': '380ms',
  'dur-slower': '600ms',
  stagger: '55ms',

  'ease-out': 'cubic-bezier(0.16, 1, 0.3, 1)',
  'ease-in': 'cubic-bezier(0.7, 0, 0.84, 0)',
  'ease-in-out': 'cubic-bezier(0.65, 0, 0.35, 1)',
  'ease-emphasized': 'cubic-bezier(0.2, 0, 0, 1)',

  /** Multiplies every travel distance; reduced motion sets it to 0 so movement becomes a fade. */
  motion: '1',
} as const;

/** `--nx-spring-<name>` easings and their `--nx-spring-<name>-duration`. */
export const springTokens: Record<string, string> = Object.fromEntries(
  Object.entries(springs).flatMap(([name, config]) => {
    const { easing, duration } = springEasing(config);
    return [
      [`spring-${name}`, easing],
      [`spring-${name}-duration`, `${duration}ms`],
    ];
  }),
);

/** What reduced motion swaps in: no travel, no overshoot, short fades. */
export const reducedMotion: Record<string, string> = {
  motion: '0',
  'dur-slow': '160ms',
  'dur-slower': '200ms',
  ...Object.fromEntries(
    Object.keys(springs).flatMap((name) => [
      [`spring-${name}`, 'cubic-bezier(0.16, 1, 0.3, 1)'],
      [`spring-${name}-duration`, '180ms'],
    ]),
  ),
};
