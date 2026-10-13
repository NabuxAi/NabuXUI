/**
 * Loaders for React.
 *
 * Three honest waiting states, thin over the pure-CSS blocks in
 * @nabuxai/ui-core: the orbit button that really works while its dots chase
 * each other, the morph ring that trades stroke weight for dashes while a
 * thin arc keeps the percent, and the liquid tank whose two out-of-phase
 * waves climb with the level. The components own the state and the level
 * (`--_p`); every loop and one-shot draw lives in the CSS.
 */
import {
  type ButtonHTMLAttributes,
  type CSSProperties,
  type HTMLAttributes,
  type ReactNode,
  forwardRef,
  useId,
} from 'react';
import { formatNumber } from '@nabuxai/ui-core';
import { cx } from '../internal/hooks';
import { useLocale, useT } from '../internal/provider';

/** Where a loader stands: waiting, working, finished, or failed. */
export type LoaderState = 'idle' | 'running' | 'success' | 'error';

const flag = (on: unknown) => (on ? '' : undefined);
const clamp01 = (value: number) => Math.min(1, Math.max(0, value));

/** 42% · ۴۲٪ · ٤٢٪ — the locale decides the digits and where the sign sits. */
function usePercent(value: number): string {
  const locale = useLocale();
  return formatNumber(clamp01(value / 100), locale, { style: 'percent', maximumFractionDigits: 0 });
}

/* ---- Orbit button -------------------------------------------------------------- */

export interface LoaderOrbitProps extends Omit<ButtonHTMLAttributes<HTMLButtonElement>, 'children'> {
  state?: LoaderState;
  /** The idle label — what the button says before and, by default, after the work. */
  label: ReactNode;
  /** While it works (defaults to the built-in Loading word). */
  runningLabel?: ReactNode;
  /** When it lands (defaults to the idle label: the tick and the green carry the state). */
  successLabel?: ReactNode;
  /** When it fails (defaults to the built-in Failed word). */
  errorLabel?: ReactNode;
  /** Dots chasing each other round the orbit, 2–6 (default 4). */
  dots?: number;
  size?: 'sm' | 'md' | 'lg';
  tone?: 'accent' | 'gold';
  /** No press scale, for placements where motion would distract. */
  static?: boolean;
}

/**
 * A real working button whose waiting state is the work: dots lap an
 * elliptical orbit while it runs, a tick draws itself on success, and the
 * error stills everything on red. All four labels stay stacked in one cell,
 * so the button never changes width mid-flight.
 */
export const LoaderOrbit = forwardRef<HTMLButtonElement, LoaderOrbitProps>(function LoaderOrbit(
  { state = 'idle', label, runningLabel, successLabel, errorLabel, dots = 4, size, tone, static: still, className, disabled, ...rest },
  ref,
) {
  const t = useT();
  const running = state === 'running';
  const texts: Record<LoaderState, ReactNode> = {
    idle: label,
    running: runningLabel ?? `${t('loading')}…`,
    success: successLabel ?? label,
    error: errorLabel ?? t('failed'),
  };

  return (
    <button
      ref={ref}
      type="button"
      className={cx('nx-loader-orbit', className)}
      data-state={state === 'idle' ? undefined : state}
      data-size={size && size !== 'md' ? size : undefined}
      data-tone={tone && tone !== 'accent' ? tone : undefined}
      data-static={flag(still)}
      aria-busy={running || undefined}
      disabled={disabled || running}
      {...rest}
    >
      {running && (
        <span className="nx-loader-orbit-orbit" aria-hidden="true">
          {Array.from({ length: Math.max(2, Math.min(6, Math.round(dots))) }, (_, i) => (
            <i key={i} />
          ))}
        </span>
      )}
      {state === 'success' && (
        <svg className="nx-loader-orbit-mark" viewBox="0 0 24 24" aria-hidden="true">
          <path pathLength={24} d="M4.5 12.5l5 5 10-11" />
        </svg>
      )}
      {state === 'error' && (
        <svg className="nx-loader-orbit-mark" viewBox="0 0 24 24" aria-hidden="true">
          <path pathLength={24} d="M6 6l12 12M18 6L6 18" />
        </svg>
      )}
      <span className="nx-loader-orbit-label">
        {(Object.keys(texts) as LoaderState[]).map((key) => (
          <span key={key} data-off={key !== state ? '' : undefined} aria-hidden={key !== state ? true : undefined}>
            {texts[key]}
          </span>
        ))}
      </span>
    </button>
  );
});

/* ---- Morph ring ----------------------------------------------------------------- */

export interface LoaderRingProps extends HTMLAttributes<HTMLDivElement> {
  state?: LoaderState;
  /** Progress 0–100, kept honest by the thin inner arc; the app drives it while it runs. */
  value?: number;
  /** Accessible name of the progressbar. */
  'aria-label'?: string;
  /** The small line under the value — the running / done / failed word. */
  caption?: ReactNode;
  /** Show the percent readout (default true). */
  showValue?: boolean;
  size?: 'sm' | 'md' | 'lg';
}

/** A ring that never holds one shape while it works, and freezes red the moment it fails. */
export const LoaderRing = forwardRef<HTMLDivElement, LoaderRingProps>(function LoaderRing(
  { state = 'idle', value = 0, caption, showValue = true, size, className, style, role = 'progressbar', ...rest },
  ref,
) {
  const percent = usePercent(value);
  const p = clamp01(value / 100);

  return (
    <div
      ref={ref}
      className={cx('nx-loader-ring', className)}
      role={role}
      aria-valuenow={Math.round(value)}
      aria-valuemin={0}
      aria-valuemax={100}
      data-state={state === 'idle' ? undefined : state}
      data-size={size && size !== 'md' ? size : undefined}
      style={{ ...style, '--_p': p } as CSSProperties}
      {...rest}
    >
      <svg viewBox="0 0 120 120" aria-hidden="true">
        <circle className="nx-loader-ring-track" cx={60} cy={60} r={50} pathLength={100} />
        <circle className="nx-loader-ring-morph" cx={60} cy={60} r={50} pathLength={100} />
        <circle className="nx-loader-ring-fill" cx={60} cy={60} r={50} pathLength={100} />
      </svg>
      <span className="nx-loader-ring-read">
        {state === 'success' ? (
          <svg className="nx-loader-ring-tick" viewBox="0 0 24 24" aria-hidden="true">
            <path pathLength={24} d="M4.5 12.5l5 5 10-11" />
          </svg>
        ) : (
          showValue && (
            <b className="nx-loader-ring-value" aria-hidden="true">
              {percent}
            </b>
          )
        )}
        {caption && (
          <small className="nx-loader-ring-caption" aria-live="polite">
            {caption}
          </small>
        )}
      </span>
    </div>
  );
});

/* ---- Liquid tank ---------------------------------------------------------------- */

export interface LoaderLiquidProps extends HTMLAttributes<HTMLDivElement> {
  state?: LoaderState;
  /** How full the tank is, 0–100; the waves and the number climb with it. */
  value?: number;
  /** Accessible name of the progressbar. */
  'aria-label'?: string;
  /** The small line under the value — it rises out of the liquid when the level passes it. */
  caption?: ReactNode;
  /** Show the percent readout (default true). */
  showValue?: boolean;
  size?: 'sm' | 'md' | 'lg';
}

/** Two same-frequency waves out of phase fill the tank while the number counts; grey and still at the error. */
export const LoaderLiquid = forwardRef<HTMLDivElement, LoaderLiquidProps>(function LoaderLiquid(
  { state = 'idle', value = 0, caption, showValue = true, size, className, style, role = 'progressbar', ...rest },
  ref,
) {
  const percent = usePercent(value);
  const p = clamp01(value / 100);
  const uid = useId().replace(/:/g, '');
  const clip = `nx-loader-clip-${uid}`;
  const grad = `nx-loader-grad-${uid}`;

  return (
    <div
      ref={ref}
      className={cx('nx-loader-liquid', className)}
      role={role}
      aria-valuenow={Math.round(value)}
      aria-valuemin={0}
      aria-valuemax={100}
      data-state={state === 'idle' ? undefined : state}
      data-size={size && size !== 'md' ? size : undefined}
      style={{ ...style, '--_p': p } as CSSProperties}
      {...rest}
    >
      <svg viewBox="0 0 200 200" aria-hidden="true">
        <defs>
          <clipPath id={clip}>
            <circle cx={100} cy={100} r={88} />
          </clipPath>
          <linearGradient id={grad} x1="0" y1="0" x2="0" y2="1">
            <stop className="nx-loader-liquid-front-a" offset="0" />
            <stop className="nx-loader-liquid-front-b" offset="1" />
          </linearGradient>
        </defs>
        <circle className="nx-loader-liquid-bowl" cx={100} cy={100} r={88} />
        <g clipPath={`url(#${clip})`}>
          <g className="nx-loader-liquid-level">
            <path className="nx-loader-liquid-wave nx-loader-liquid-back" d="M0 0 Q25 -6 50 0 T100 0 T150 0 T200 0 T250 0 T300 0 T350 0 T400 0 V220 H0 Z" />
            <path className="nx-loader-liquid-wave" fill={`url(#${grad})`} d="M0 0 Q25 -9 50 0 T100 0 T150 0 T200 0 T250 0 T300 0 T350 0 T400 0 V220 H0 Z" />
          </g>
        </g>
        <circle className="nx-loader-liquid-glass" cx={100} cy={100} r={88} />
        <circle className="nx-loader-liquid-rim" cx={100} cy={100} r={88} />
      </svg>
      <span className="nx-loader-liquid-read" data-wet={p > 0.53 ? '' : undefined}>
        {state === 'success' ? (
          <svg className="nx-loader-liquid-tick" viewBox="0 0 24 24" aria-hidden="true">
            <path pathLength={24} d="M4.5 12.5l5 5 10-11" />
          </svg>
        ) : (
          showValue && (
            <b className="nx-loader-liquid-value" aria-hidden="true">
              {percent}
            </b>
          )
        )}
        {caption && (
          <small className="nx-loader-liquid-caption" aria-live="polite">
            {caption}
          </small>
        )}
      </span>
    </div>
  );
});
