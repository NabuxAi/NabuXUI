/**
 * Shader stages (React): thin over the WebGL1 engine in @nabuxai/ui-core's
 * `shaderStage` and css/blocks/shaders.css.
 *
 *   ShaderStage    the raw engine: your own frag, uniforms, speed, fallback
 *   ShaderPlasma   five phase-shifted sine fields under a three-stop palette;
 *                  the pointer bends the flow
 *   ShaderNoise    domain-warped fbm; `scale` and `speed` retune it live
 *   ShaderRipples  a pond: every press (or Enter) drops a decaying wave;
 *                  the handle can drop, rain and calm the surface
 *
 * Uniform values go through functions that read the latest props, so moving a
 * slider never rebuilds the GL program — the engine re-reads them each frame
 * (and retunes the still frame under reduced motion).
 */
import {
  type CSSProperties,
  type HTMLAttributes,
  type KeyboardEvent as ReactKeyboardEvent,
  type PointerEvent as ReactPointerEvent,
  type ReactNode,
  forwardRef,
  useEffect,
  useImperativeHandle,
  useMemo,
  useRef,
} from 'react';
import {
  type ShaderUniforms,
  noiseFrag,
  plasmaFrag,
  prefersReducedMotion,
  rippleLife,
  rippleSlots,
  ripplesFrag,
  shaderStage,
} from '@nabuxai/ui-core';
import { cx, mergeRefs, useBehavior, useEvent, useIsoLayoutEffect } from '../internal/hooks';

/** The three colour stops every shipped shader paints with (any CSS colour; tokens welcome). */
export type ShaderColors = [string, string, string];

/* ---- Stage ---------------------------------------------------------------------------------- */

export interface ShaderStageProps extends HTMLAttributes<HTMLDivElement> {
  /** GLSL ES 1.00 fragment source (default: the plasma). */
  frag?: string;
  /** Flow-of-time multiplier; live after mount. */
  speed?: number;
  /** Background for the fallback layer, shown while (or if) WebGL does not prove itself. */
  fallback?: string;
  /** Extra uniforms, merged over the palette defaults; functions are re-read every frame. */
  uniforms?: ShaderUniforms;
  /** Canvas pixels per CSS pixel (default 0.5). */
  resolution?: number;
  /** Feed the eased pointer position as u_pointer (−1…1). */
  pointer?: boolean;
  children?: ReactNode;
}

/** A WebGL shader running behind whatever you put inside; a gradient when WebGL is missing. */
export const ShaderStage = forwardRef<HTMLDivElement, ShaderStageProps>(function ShaderStage(
  { frag, speed = 1, fallback, uniforms, resolution, pointer, className, children, ...rest },
  forwarded,
) {
  const own = useRef<HTMLDivElement>(null);
  // Through a ref: speed changes retune the flow instead of rebuilding the program.
  const flow = useRef(speed);
  useIsoLayoutEffect(() => {
    flow.current = speed;
  });
  useBehavior(own, shaderStage, {
    frag,
    speed: () => flow.current,
    uniforms,
    resolution,
    pointer,
  });

  return (
    <div ref={mergeRefs(forwarded, own)} className={cx('nx-shader', className)} data-fallback="" {...rest}>
      <canvas className="nx-shader-canvas" aria-hidden="true" />
      <div className="nx-shader-fallback" aria-hidden="true" style={fallback ? ({ background: fallback } as CSSProperties) : undefined} />
      {children}
    </div>
  );
});

/* ---- Plasma --------------------------------------------------------------------------------- */

const PLASMA_DEFAULT: ShaderColors = ['var(--nx-lapis-950)', 'var(--nx-violet-500)', 'var(--nx-cyan-300)'];

export interface ShaderPlasmaProps extends HTMLAttributes<HTMLDivElement> {
  /** The three stops of the palette (default: lapis → violet → cyan). */
  colors?: ShaderColors;
  /** Flow-of-time multiplier (default 1). */
  speed?: number;
  children?: ReactNode;
}

/** A boiling plasma behind the content; the pointer bends the flow. */
export const ShaderPlasma = forwardRef<HTMLDivElement, ShaderPlasmaProps>(function ShaderPlasma(
  { colors = PLASMA_DEFAULT, speed = 1, className, children, ...rest },
  forwarded,
) {
  const live = useRef(colors);
  useIsoLayoutEffect(() => {
    live.current = colors;
  });
  const uniforms = useMemo<ShaderUniforms>(
    () => ({
      u_a: () => live.current[0],
      u_b: () => live.current[1],
      u_c: () => live.current[2],
    }),
    [],
  );

  return (
    <ShaderStage ref={forwarded} frag={plasmaFrag} pointer speed={speed} uniforms={uniforms} fallback={`linear-gradient(135deg, ${colors.join(', ')})`} className={className} {...rest}>
      {children}
    </ShaderStage>
  );
});

/* ---- Noise ---------------------------------------------------------------------------------- */

const NOISE_DEFAULT: ShaderColors = ['var(--nx-lapis-950)', 'var(--nx-violet-500)', 'var(--nx-cyan-300)'];

export interface ShaderNoiseProps extends HTMLAttributes<HTMLDivElement> {
  /** Field detail — bigger numbers make the folds tighter (default 1.6). */
  scale?: number;
  /** Flow-of-time multiplier (default 1). */
  speed?: number;
  /** The three stops of the ramp (default: lapis → violet → cyan). */
  colors?: ShaderColors;
  children?: ReactNode;
}

/** A domain-warped noise field — the texture of an engine room without a single image. */
export const ShaderNoise = forwardRef<HTMLDivElement, ShaderNoiseProps>(function ShaderNoise(
  { scale = 1.6, speed = 1, colors = NOISE_DEFAULT, className, children, ...rest },
  forwarded,
) {
  const live = useRef({ scale, colors });
  useIsoLayoutEffect(() => {
    live.current = { scale, colors };
  });
  const uniforms = useMemo<ShaderUniforms>(
    () => ({
      u_scale: () => live.current.scale,
      u_lo: () => live.current.colors[0],
      u_mid: () => live.current.colors[1],
      u_hi: () => live.current.colors[2],
    }),
    [],
  );

  return (
    <ShaderStage ref={forwarded} frag={noiseFrag} speed={speed} uniforms={uniforms} fallback={`linear-gradient(160deg, ${colors.join(', ')})`} className={className} {...rest}>
      {children}
    </ShaderStage>
  );
});

/* ---- Ripples -------------------------------------------------------------------------------- */

const WATER_DEFAULT: ShaderColors = ['var(--nx-ink-950)', 'var(--nx-cyan-700)', 'var(--nx-cyan-300)'];

/** What a ShaderRipples ref can do: drop waves on the surface. */
export interface ShaderRipplesHandle {
  /** Drop one wave at shader coordinates (x toward inline-end-ish right, y up, about ±1; default the centre). */
  drop(x?: number, y?: number, strength?: number): void;
  /** Drop `count` waves at random spots, staggered (default 3). */
  rain(count?: number): void;
  /** Wipe the surface flat. */
  calm(): void;
}

export interface ShaderRipplesProps extends HTMLAttributes<HTMLDivElement> {
  /** Accessible name of the pond — it is focusable, and Enter or Space drops a wave. */
  label?: string;
  /** Water colours: deep, shallows, crest highlight (default: ink → cyan). */
  colors?: ShaderColors;
  /** Called whenever the number of live waves changes. */
  onWavesChange?: (count: number) => void;
}

/** A pond that answers the touch: every press writes a wave into a ring buffer the shader sums. */
export const ShaderRipples = forwardRef<ShaderRipplesHandle, ShaderRipplesProps>(function ShaderRipples(
  { label = 'Interactive pond. Click or press Enter to drop a wave.', colors = WATER_DEFAULT, onWavesChange, className, ...rest },
  forwarded,
) {
  const own = useRef<HTMLDivElement>(null);
  const live = useRef(colors);
  useIsoLayoutEffect(() => {
    live.current = colors;
  });
  const report = useEvent(onWavesChange);

  // Twelve slots (x, y, birth, strength); unborn waves sit at t = −1000.
  const pond = useMemo(() => {
    const buf = new Float32Array(rippleSlots * 4);
    for (let i = 0; i < rippleSlots; i++) buf[i * 4 + 2] = -1e3;
    return { buf, cursor: 0, clock: -1 };
  }, []);
  const liveWaves = useRef(0);
  const timers = useRef<number[]>([]);
  useEffect(() => () => {
    timers.current.forEach((timer) => window.clearTimeout(timer));
    timers.current = [];
  }, []);

  const uniforms = useMemo<ShaderUniforms>(
    () => ({
      u_water: () => live.current[0],
      u_shallow: () => live.current[1],
      u_crest: () => live.current[2],
      u_r: (t: number) => {
        pond.clock = t;
        let active = 0;
        for (let i = 0; i < rippleSlots; i++) {
          const born = pond.buf[i * 4 + 2]!;
          if (born > 0 && t - born < rippleLife) active++;
        }
        if (active !== liveWaves.current) {
          liveWaves.current = active;
          report(active);
        }
        return pond.buf;
      },
    }),
    [pond, report],
  );

  const drop = useEvent((x?: number, y?: number, strength?: number) => {
    // Reduced motion keeps the water still; a dead engine has no clock to stamp.
    if (prefersReducedMotion() || pond.clock < 0) return;
    const i = pond.cursor;
    pond.cursor = (pond.cursor + 1) % rippleSlots;
    pond.buf[i * 4] = x ?? 0;
    pond.buf[i * 4 + 1] = y ?? 0;
    pond.buf[i * 4 + 2] = pond.clock;
    pond.buf[i * 4 + 3] = strength ?? 1;
  });
  const rain = useEvent((count?: number) => {
    const n = count ?? 3;
    for (let j = 0; j < n; j++) {
      timers.current.push(
        window.setTimeout(() => drop((Math.random() - 0.5) * 1.5, (Math.random() - 0.5) * 0.9, 0.7 + Math.random() * 0.3), j * 170),
      );
    }
  });
  const calm = useEvent(() => {
    timers.current.forEach((timer) => window.clearTimeout(timer));
    timers.current = [];
    for (let i = 0; i < rippleSlots; i++) pond.buf[i * 4 + 2] = -1e3;
  });
  useImperativeHandle(forwarded, () => ({ drop, rain, calm }), [drop, rain, calm]);

  const press = (event: ReactPointerEvent<HTMLDivElement>): void => {
    const rect = own.current?.getBoundingClientRect();
    if (!rect || rect.width === 0 || rect.height === 0) return;
    const span = Math.min(rect.width, rect.height);
    drop(((event.clientX - rect.left) * 2 - rect.width) / span, (rect.height - (event.clientY - rect.top) * 2) / span);
  };
  const key = (event: ReactKeyboardEvent<HTMLDivElement>): void => {
    if (event.key === 'Enter') {
      event.preventDefault();
      drop(0, 0);
    } else if (event.key === ' ' || event.code === 'Space') {
      event.preventDefault();
      drop(0.15, -0.12);
    }
  };

  return (
    <ShaderStage
      ref={own}
      frag={ripplesFrag}
      uniforms={uniforms}
      className={cx('nx-shader-pond', className)}
      role="img"
      tabIndex={0}
      aria-label={label}
      onPointerDown={press}
      onKeyDown={key}
      {...rest}
    />
  );
});
