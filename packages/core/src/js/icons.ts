/**
 * The small icon set NabuXUI's own components draw with (24×24, 1.75 stroke).
 * One path per icon so React and Blade render them identically; the Livewire
 * package reads the generated dist/icons.json. Apps are free to pass any other
 * icon into the components' icon slots.
 */
export interface IconDefinition {
  d: string;
  /** Drawn with fill instead of stroke. */
  filled?: boolean;
  /** Points along the reading direction: mirrored in right-to-left pages. */
  directional?: boolean;
}

export const icons = {
  check: { d: 'M4.5 12.5l5 5L19.5 6.5' },
  x: { d: 'M6 6l12 12M18 6L6 18' },
  plus: { d: 'M12 5v14M5 12h14' },
  minus: { d: 'M5 12h14' },
  'chevron-down': { d: 'M6 9l6 6 6-6' },
  'chevron-up': { d: 'M6 15l6-6 6 6' },
  'chevron-right': { d: 'M9 6l6 6-6 6', directional: true },
  'chevron-left': { d: 'M15 6l-6 6 6 6', directional: true },
  'arrow-right': { d: 'M5 12h14M13 6l6 6-6 6', directional: true },
  'arrow-left': { d: 'M19 12H5M11 6l-6 6 6 6', directional: true },
  'arrow-up': { d: 'M12 19V5M6 11l6-6 6 6' },
  'trend-up': { d: 'M7 17L17 7M8 7h9v9' },
  'trend-down': { d: 'M7 7l10 10M17 8v9H8' },
  'external-link': { d: 'M14 4h6v6M20 4l-9 9M18 14v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h5' },
  search: { d: 'M11 18a7 7 0 1 0 0-14 7 7 0 0 0 0 14zM20 20l-4-4' },
  copy: { d: 'M9 11a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-7a2 2 0 0 1-2-2zM5 15V6a2 2 0 0 1 2-2h8' },
  heart: { d: 'M12 20s-7.5-4.6-7.5-10.2A4.3 4.3 0 0 1 12 7.1a4.3 4.3 0 0 1 7.5 2.7C19.5 15.4 12 20 12 20z' },
  star: { d: 'M12 3.5l2.6 5.3 5.9.9-4.25 4.1 1 5.85L12 16.9l-5.25 2.75 1-5.85L3.5 9.7l5.9-.9z', filled: true },
  sun: { d: 'M12 16a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4' },
  moon: { d: 'M20.5 14.5A8.5 8.5 0 0 1 9.5 3.5a8.5 8.5 0 1 0 11 11z' },
  info: { d: 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18zM12 11v5M12 8h.01' },
  'check-circle': { d: 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18zM8.5 12.5l2.5 2.5 4.5-5' },
  'alert-circle': { d: 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18zM12 8v5M12 16h.01' },
  'alert-triangle': { d: 'M10.3 4.1L2.6 17.5a2 2 0 0 0 1.7 3h15.4a2 2 0 0 0 1.7-3L13.7 4.1a2 2 0 0 0-3.4 0zM12 9.5v4M12 17h.01' },
  upload: { d: 'M7 18a4.5 4.5 0 0 1-.6-8.96A6 6 0 0 1 18 9a4 4 0 0 1 0 8M12 12v8M9 15l3-3 3 3' },
  file: { d: 'M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8zM14 3v5h5' },
  paperclip: { d: 'M20 11.5l-7.8 7.8a5 5 0 0 1-7.1-7.1l8-8a3.3 3.3 0 0 1 4.7 4.7l-8 8a1.7 1.7 0 0 1-2.4-2.4l7.3-7.3' },
  mic: { d: 'M12 15a3 3 0 0 0 3-3V6a3 3 0 0 0-6 0v6a3 3 0 0 0 3 3zM19 11a7 7 0 0 1-14 0M12 18v3' },
  stop: { d: 'M7 7h10v10H7z', filled: true },
  play: { d: 'M7 5l12 7-12 7z', filled: true },
  pause: { d: 'M8 5v14M16 5v14' },
  menu: { d: 'M4 7h16M4 12h16M4 17h16' },
  sparkles: { d: 'M12 3l1.8 4.7 4.7 1.8-4.7 1.8L12 16l-1.8-4.7-4.7-1.8 4.7-1.8zM19 15l.8 2.2 2.2.8-2.2.8L19 21l-.8-2.2L16 18l2.2-.8z' },
  globe: { d: 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18zM3 12h18M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18' },
  home: { d: 'M4 11l8-7 8 7v8a1 1 0 0 1-1 1h-4v-6H9v6H5a1 1 0 0 1-1-1z' },
  sliders: { d: 'M4 7h10M18 7h2M4 17h4M12 17h8M14 5v4M8 15v4' },
  user: { d: 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM4 21a8 8 0 0 1 16 0' },
  users: { d: 'M9 11a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7zM2.5 20a6.5 6.5 0 0 1 13 0M16 4.2a3.5 3.5 0 0 1 0 6.6M18 14a6.5 6.5 0 0 1 3.5 6' },
  bell: { d: 'M6 16v-5a6 6 0 1 1 12 0v5l1.5 2h-15zM10 21h4' },
  trash: { d: 'M4 7h16M10 11v6M14 11v6M6 7l1 13h10l1-13M9 7V4h6v3' },
  edit: { d: 'M4 20h4L19 9l-4-4L4 16zM13.5 6.5l4 4' },
  grid: { d: 'M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z' },
  chart: { d: 'M5 20V10M12 20V4M19 20v-7' },
  zap: { d: 'M13 2L4 14h7l-1 8 9-12h-7z' },
  shield: { d: 'M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z' },
  layers: { d: 'M12 3l9 5-9 5-9-5zM3 13l9 5 9-5' },
  message: { d: 'M4 5h16v11H9l-5 4z' },
  lock: { d: 'M6 11h12v10H6zM8 11V7a4 4 0 0 1 8 0v4' },
  mail: { d: 'M4 6h16v12H4zM4 7l8 6 8-6' },
  command: { d: 'M9 6a3 3 0 1 0-3 3h12a3 3 0 1 0-3-3v12a3 3 0 1 0 3-3H6a3 3 0 1 0 3 3z' },
  cpu: { d: 'M7 7h10v10H7zM10 10h4v4h-4zM9 3v4M15 3v4M9 17v4M15 17v4M3 9h4M3 15h4M17 9h4M17 15h4' },
  wand: { d: 'M4 20L15 9M14 4l1 2 2 1-2 1-1 2-1-2-2-1 2-1zM19 11l.6 1.4L21 13l-1.4.6L19 15l-.6-1.4L17 13l1.4-.6z' },
} satisfies Record<string, IconDefinition>;

export type IconName = keyof typeof icons;

const escapeAttr = (value: string) => value.replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;');

/** An icon as an SVG string — for places that build markup by hand. Decorative unless labelled. */
export function iconSvg(name: IconName, { className = 'nx-icon', label }: { className?: string; label?: string } = {}): string {
  const icon: IconDefinition = icons[name];
  const paint = icon.filled ? 'fill="currentColor" stroke="none"' : 'fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"';
  const a11y = label ? `role="img" aria-label="${escapeAttr(label)}"` : 'aria-hidden="true"';
  const dir = icon.directional ? ' data-directional' : '';
  return `<svg class="${escapeAttr(className)}" viewBox="0 0 24 24" ${paint} ${a11y}${dir}><path d="${icon.d}"/></svg>`;
}
