/**
 * Liquid glass for React.
 *
 * Panes bend their backdrop at the rim (Chromium; frosted elsewhere). Lenses
 * bend the live content under them in every browser: the segmented control's
 * selection, the dock's magnifier, the switch and slider thumbs while held,
 * the tab bar's pill and the reading glass. The optics and the gestures live
 * in @nabuxai/ui-core; these components render the markup and own the state.
 */
import {
  type ButtonHTMLAttributes,
  type CSSProperties,
  type HTMLAttributes,
  type InputHTMLAttributes,
  type PointerEvent as ReactPointerEvent,
  type ReactNode,
  type RefObject,
  forwardRef,
  useId,
  useRef,
} from 'react';
import {
  type GlassController,
  type IconName,
  type LensTuning,
  followLight,
  glassDock,
  glassPane,
  glassSegmented,
  glassSlider,
  glassSwitch,
  glassTabBar,
  lensTunings,
  liquidRipple,
  readingGlass,
  sweepLight,
} from '@nabuxai/ui-core';
import { cx, mergeRefs, useBehavior, useControllable, useEvent, useIsoLayoutEffect } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useT } from '../internal/provider';

export type GlassPreset = 'regular' | 'clear' | 'frost' | 'thick';
export type { LensTuning };

const preset = (value?: GlassPreset) => (value && value !== 'regular' ? value : undefined);
const flag = (on: unknown) => (on ? '' : undefined);

/** A core glass controller on `ref` for as long as it is mounted; rebuilt when `key` changes. */
function useGlass<E extends HTMLElement>(ref: RefObject<E | null>, create: (el: E) => GlassController, key: string): RefObject<GlassController | null> {
  const ctrl = useRef<GlassController | null>(null);
  useIsoLayoutEffect(() => {
    if (!ref.current) return;
    ctrl.current = create(ref.current);
    return () => {
      ctrl.current?.destroy();
      ctrl.current = null;
    };
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [ref, key]);
  return ctrl;
}

/* ---- Panel ------------------------------------------------------------------ */

export interface GlassPanelProps extends HTMLAttributes<HTMLElement> {
  preset?: GlassPreset;
  /** Bend the backdrop at the rim (Chromium; other engines stay frosted). */
  refract?: boolean;
  /** A thin film of colour along the rim. */
  iridescent?: boolean;
  /** Turn the rim light toward the pointer. */
  followLight?: boolean;
  as?: 'div' | 'section' | 'article' | 'aside' | 'header' | 'footer' | 'nav';
}

/** A glass surface: tint, frost, a rim that catches the light, soft depth. */
export const GlassPanel = forwardRef<HTMLElement, GlassPanelProps>(function GlassPanel(
  { preset: look, refract = true, iridescent, followLight: follow, as = 'div', className, ...rest },
  ref,
) {
  const own = useRef<HTMLElement>(null);
  useBehavior(own, glassPane, {}, refract);
  useBehavior(own, followLight, null, !!follow);
  const Tag = as as 'div';
  return (
    <Tag
      ref={mergeRefs(ref, own) as never}
      className={cx('nx-glass nx-glass-panel', className)}
      data-preset={preset(look)}
      data-iridescent={flag(iridescent)}
      {...rest}
    />
  );
});

/* ---- Button ----------------------------------------------------------------- */

export interface GlassButtonProps extends ButtonHTMLAttributes<HTMLButtonElement> {
  href?: string;
  size?: 'sm' | 'md' | 'lg';
  icon?: IconName;
  iconEnd?: IconName;
  preset?: GlassPreset;
  iridescent?: boolean;
  refract?: boolean;
  /** Send the light once around every glass rim on the page when pressed. */
  shimmer?: boolean;
  /** No press scale, for buttons where motion would distract. */
  static?: boolean;
}

/** A pressable pane: the tint lifts on hover and the glass gives under the finger. */
export const GlassButton = forwardRef<HTMLButtonElement, GlassButtonProps>(function GlassButton(
  { href, size, icon, iconEnd, preset: look, iridescent, refract = true, shimmer, static: still, className, children, onPointerDown, ...rest },
  ref,
) {
  const own = useRef<HTMLElement>(null);
  useBehavior(own, glassPane, {}, refract);
  const props = {
    className: cx('nx-glass nx-glass-button', className),
    'data-interactive': '',
    'data-size': size && size !== 'md' ? size : undefined,
    'data-preset': preset(look),
    'data-iridescent': flag(iridescent),
    'data-static': flag(still),
    'data-icon-only': flag(!children && (icon || iconEnd)),
  };
  const press = (event: ReactPointerEvent<HTMLElement>) => {
    if (shimmer) sweepLight();
    onPointerDown?.(event as ReactPointerEvent<HTMLButtonElement>);
  };
  const content = (
    <>
      {icon && <Icon name={icon} />}
      {children}
      {iconEnd && <Icon name={iconEnd} />}
    </>
  );

  if (href) {
    return (
      <SmartLink ref={mergeRefs(ref as never, own) as never} href={href} {...props} {...(rest as HTMLAttributes<HTMLAnchorElement>)} onPointerDown={press}>
        {content}
      </SmartLink>
    );
  }
  return (
    <button ref={mergeRefs(ref, own) as never} type="button" {...props} {...rest} onPointerDown={press}>
      {content}
    </button>
  );
});

/* ---- Segmented control ------------------------------------------------------ */

export interface GlassSegmentedOption {
  value: string;
  label: ReactNode;
  icon?: IconName;
  disabled?: boolean;
}

export interface GlassSegmentedProps {
  options: GlassSegmentedOption[];
  value?: string;
  defaultValue?: string;
  onValueChange?: (value: string) => void;
  name?: string;
  preset?: GlassPreset;
  refract?: boolean;
  lens?: LensTuning;
  'aria-label'?: string;
  className?: string;
}

/**
 * A segmented control whose selection is a lens: it magnifies the label under
 * it, glides between options, swells while held and can be dragged; letting
 * go snaps it to the nearest option.
 */
export function GlassSegmented({ options, value, defaultValue, onValueChange, name, preset: look, refract = true, lens = lensTunings.segmented, 'aria-label': label, className }: GlassSegmentedProps) {
  const generated = useId();
  const group = name ?? `nx-gseg${generated.replace(/:/g, '')}`;
  const [current, setCurrent] = useControllable(value, defaultValue ?? options[0]?.value ?? '', onValueChange);
  const pick = useEvent(setCurrent);
  const root = useRef<HTMLDivElement>(null);
  const ctrl = useGlass(root, (el) => glassSegmented(el, { lens, refract, onPick: pick }), JSON.stringify([lens, refract]));

  useIsoLayoutEffect(() => {
    ctrl.current?.refresh();
  }, [current, options]);

  return (
    <div ref={root} className={cx('nx-glass nx-glass-seg', className)} role="radiogroup" aria-label={label} data-preset={preset(look)}>
      <div className="nx-glass-seg-track">
        {options.map((option) => (
          <label key={option.value} className="nx-glass-seg-option">
            <input type="radio" name={group} value={option.value} checked={current === option.value} disabled={option.disabled} onChange={() => setCurrent(option.value)} />
            {option.icon && <Icon name={option.icon} />}
            <span>{option.label}</span>
          </label>
        ))}
      </div>
      <span className="nx-indicator nx-lens nx-glass-seg-lens" aria-hidden="true" />
    </div>
  );
}

/* ---- Dock ------------------------------------------------------------------- */

export interface GlassDockItem {
  id: string;
  label: string;
  icon: IconName;
  href?: string;
  onClick?: () => void;
  tone?: 'lapis' | 'violet' | 'cyan' | 'gold' | 'ink' | 'rose' | 'green';
  current?: boolean;
}

export interface GlassDockProps {
  items: GlassDockItem[];
  preset?: GlassPreset;
  refract?: boolean;
  lens?: LensTuning;
  'aria-label'?: string;
  className?: string;
}

/** A dock whose magnifier glides to the item under the pointer or focus. */
export function GlassDock({ items, preset: look, refract = true, lens = lensTunings.dock, 'aria-label': label, className }: GlassDockProps) {
  const root = useRef<HTMLElement>(null);
  useBehavior(root, glassDock, { lens, refract });

  return (
    <nav ref={root} className={cx('nx-glass nx-glass-dock', className)} aria-label={label} data-preset={preset(look)}>
      <ul className="nx-glass-dock-items">
        {items.map((item) => {
          const common = {
            className: 'nx-glass-dock-item',
            'aria-label': item.label,
            'aria-current': item.current ? ('page' as const) : undefined,
            'data-tone': item.tone,
          };
          return (
            <li key={item.id}>
              {item.href ? (
                <SmartLink href={item.href} {...common}>
                  <Icon name={item.icon} />
                </SmartLink>
              ) : (
                <button type="button" onClick={item.onClick} {...common}>
                  <Icon name={item.icon} />
                </button>
              )}
            </li>
          );
        })}
      </ul>
      <span className="nx-indicator nx-lens nx-glass-dock-lens" aria-hidden="true" />
      {/* The behaviour writes the hovered item's name here. */}
      <span className="nx-glass-dock-tip" aria-hidden="true" />
    </nav>
  );
}

/* ---- Switch ----------------------------------------------------------------- */

export interface GlassSwitchProps extends Omit<InputHTMLAttributes<HTMLInputElement>, 'type' | 'onChange' | 'checked' | 'defaultChecked'> {
  checked?: boolean;
  defaultChecked?: boolean;
  onCheckedChange?: (checked: boolean) => void;
  label?: ReactNode;
  lens?: LensTuning;
}

/** A switch whose knob clears to glass while you hold it, bending the track beneath. */
export const GlassSwitch = forwardRef<HTMLInputElement, GlassSwitchProps>(function GlassSwitch(
  { checked, defaultChecked = false, onCheckedChange, label, lens = lensTunings.switch, className, ...rest },
  ref,
) {
  const [on, setOn] = useControllable(checked, defaultChecked, onCheckedChange);
  const root = useRef<HTMLLabelElement>(null);
  useBehavior(root, glassSwitch, { lens });

  return (
    <label ref={root} className={cx('nx-glass-switch', className)}>
      <span className="nx-glass-switch-control">
        <span className="nx-glass-switch-track">
          <span className="nx-glass-switch-fill" />
        </span>
        <span className="nx-lens nx-glass-switch-thumb" aria-hidden="true" />
        <input ref={ref} type="checkbox" role="switch" className="nx-glass-switch-input" checked={on} onChange={(event) => setOn(event.target.checked)} {...rest} />
      </span>
      {label && <span className="nx-glass-switch-label">{label}</span>}
    </label>
  );
});

/* ---- Slider ----------------------------------------------------------------- */

export interface GlassSliderProps extends Omit<InputHTMLAttributes<HTMLInputElement>, 'type' | 'value' | 'defaultValue' | 'onChange' | 'min' | 'max' | 'step'> {
  value?: number;
  defaultValue?: number;
  onValueChange?: (value: number) => void;
  min?: number;
  max?: number;
  step?: number;
  /** Tick marks along the track (the magnifier bends them). 0 hides them. */
  ticks?: number;
  /** Show the value above the thumb while dragging. */
  showValue?: boolean;
  format?: (value: number) => string;
  lens?: LensTuning;
}

/** A native range whose thumb turns into a magnifier while you drag it. */
export const GlassSlider = forwardRef<HTMLInputElement, GlassSliderProps>(function GlassSlider(
  { value, defaultValue = 50, onValueChange, min = 0, max = 100, step = 1, ticks = 11, showValue = true, format, lens = lensTunings.slider, className, style, ...rest },
  ref,
) {
  const [current, setCurrent] = useControllable(value, defaultValue, onValueChange);
  const root = useRef<HTMLDivElement>(null);
  // React renders --p from its own state; the behaviour only drives the lens and the dragging state.
  const ctrl = useGlass(root, (el) => glassSlider(el, { lens, track: false }), JSON.stringify(lens));
  const p = max > min ? (Math.min(max, Math.max(min, current)) - min) / (max - min) : 0;

  useIsoLayoutEffect(() => {
    ctrl.current?.refresh();
  }, [p]);

  return (
    <div ref={root} className={cx('nx-glass-slider', className)} style={{ ...style, '--p': p } as CSSProperties}>
      <div className="nx-glass-slider-bed">
        {ticks > 1 && (
          <div className="nx-glass-slider-ticks" aria-hidden="true">
            {Array.from({ length: ticks }, (_, i) => (
              <span key={i} />
            ))}
          </div>
        )}
        <div className="nx-glass-slider-track">
          <div className="nx-glass-slider-fill" />
        </div>
      </div>
      <span className="nx-lens nx-glass-slider-thumb" aria-hidden="true" />
      {showValue && (
        <span className="nx-glass-slider-value" aria-hidden="true">
          {format ? format(current) : current}
        </span>
      )}
      <input ref={ref} type="range" className="nx-glass-slider-input" min={min} max={max} step={step} value={current} onChange={(event) => setCurrent(Number(event.target.value))} {...rest} />
    </div>
  );
});

/* ---- Tab bar ---------------------------------------------------------------- */

export interface GlassTabBarItem {
  value: string;
  label: string;
  icon: IconName;
  href?: string;
}

export interface GlassTabBarProps {
  items: GlassTabBarItem[];
  value?: string;
  defaultValue?: string;
  onValueChange?: (value: string) => void;
  /** Force the compact bar (icons only). */
  compact?: boolean;
  /**
   * Shrink while the page scrolls down and grow back on the way up. `true`
   * watches the window; pass a ref to watch a scrolling container instead.
   */
  minimizeOnScroll?: boolean | RefObject<HTMLElement | null>;
  preset?: GlassPreset;
  refract?: boolean;
  lens?: LensTuning;
  'aria-label'?: string;
  className?: string;
}

/** A floating glass tab bar: the active tab sits under a lens, and the bar shrinks while you read. */
export function GlassTabBar({ items, value, defaultValue, onValueChange, compact, minimizeOnScroll, preset: look, refract = true, lens = lensTunings.tabBar, 'aria-label': label, className }: GlassTabBarProps) {
  const [current, setCurrent] = useControllable(value, defaultValue ?? items[0]?.value ?? '', onValueChange);
  const root = useRef<HTMLElement>(null);
  const watch = typeof minimizeOnScroll === 'object' ? 'ref' : !!minimizeOnScroll;
  const ctrl = useGlass(
    root,
    (el) =>
      glassTabBar(el, {
        lens,
        refract,
        minimize: typeof minimizeOnScroll === 'object' ? minimizeOnScroll.current : !!minimizeOnScroll,
      }),
    JSON.stringify([lens, refract, watch]),
  );

  useIsoLayoutEffect(() => {
    ctrl.current?.refresh();
  }, [current, items]);

  return (
    <nav ref={root} className={cx('nx-glass nx-glass-tabbar', className)} aria-label={label} data-preset={preset(look)} data-compact={compact === undefined ? undefined : flag(compact)}>
      <ul className="nx-glass-tabbar-items">
        {items.map((item) => {
          const common = {
            className: 'nx-glass-tab',
            'aria-current': item.value === current ? ('page' as const) : undefined,
            'aria-label': item.label,
            onClick: () => setCurrent(item.value),
          };
          const content = (
            <>
              <Icon name={item.icon} />
              <span aria-hidden="true">{item.label}</span>
            </>
          );
          return (
            <li key={item.value}>
              {item.href ? (
                <SmartLink href={item.href} {...common}>
                  {content}
                </SmartLink>
              ) : (
                <button type="button" {...common}>
                  {content}
                </button>
              )}
            </li>
          );
        })}
      </ul>
      <span className="nx-indicator nx-lens nx-glass-tabbar-lens" aria-hidden="true" />
    </nav>
  );
}

/* ---- Reading glass ---------------------------------------------------------- */

export interface ReadingGlassProps extends HTMLAttributes<HTMLDivElement> {
  /** Lens diameter (short side) in px. */
  size?: number;
  shape?: 'circle' | 'pill' | 'rounded';
  /** Where the lens starts, as fractions of the area (0–1). */
  defaultPosition?: { x: number; y: number };
  lens?: LensTuning;
  /** Accessible name of the lens handle. */
  label?: string;
}

/**
 * A lens over live content that you can pick up, throw and nudge with the
 * arrow keys. The content stays selectable and clickable around it.
 */
export function ReadingGlass({ size = 144, shape = 'circle', defaultPosition = { x: 0.5, y: 0.5 }, lens = lensTunings.readingGlass, label, className, children, ...rest }: ReadingGlassProps) {
  const t = useT();
  const host = useRef<HTMLDivElement>(null);
  useBehavior(host, readingGlass, { lens, x: defaultPosition.x, y: defaultPosition.y });

  return (
    <div ref={host} className={cx('nx-reading-glass', className)} {...rest}>
      <div className="nx-reading-glass-content">{children}</div>
      <button
        type="button"
        className="nx-lens nx-reading-glass-lens"
        data-shape={shape === 'circle' ? undefined : shape}
        style={{ '--_size': `${size}px` } as CSSProperties}
        aria-label={label ?? t('magnifier')}
      />
    </div>
  );
}

/* ---- Ripple ----------------------------------------------------------------- */

export interface LiquidRippleProps extends HTMLAttributes<HTMLDivElement> {
  /** Peak displacement in px. */
  strength?: number;
  /** ms from touch to calm. */
  duration?: number;
}

/** Every press sends a ripple through the live content inside. */
export function LiquidRipple({ strength, duration, className, ...rest }: LiquidRippleProps) {
  const ref = useRef<HTMLDivElement>(null);
  useBehavior(ref, liquidRipple, { strength, duration });
  return <div ref={ref} className={cx('nx-liquid-ripple', className)} {...rest} />;
}

export { sweepLight };
