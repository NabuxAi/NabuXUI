/**
 * Framework-agnostic behaviours for the shader blocks.
 *
 *   shaderStage   the WebGL1 boilerplate every shader shares: it builds the
 *                 program, drives the render loop (parked while off screen,
 *                 one still frame under reduced motion — redrawn only when a
 *                 uniform really changes), resolves CSS colour tokens to
 *                 uniform values per theme, eases the pointer into u_pointer
 *                 and falls back to a gradient layer when WebGL is missing or
 *                 the context is lost.
 *
 * The shipped fragment shaders (plasma, fbm noise, ripple pond) are exported
 * so every framework renders the same art; only the uniforms differ. Engine
 * uniforms every frag may read: u_res (vec2), u_time (float, already scaled
 * by `speed`) and u_pointer (vec2, −1…1, eased, when `pointer` is on).
 */
import { type Cleanup, isBrowser, onReducedMotionChange, prefersReducedMotion } from '../env';

/* ---- Types ----------------------------------------------------------------------------- */

/** A uniform value as evidence: a number, a CSS colour (token vars welcome), a vector, or a matrix-free array. */
export type ShaderPlain = number | string | readonly number[] | Float32Array;

/**
 * Uniform values may be read fresh on every frame — pass a function and it
 * receives the current (speed-scaled) clock, so sliders and ring buffers feed
 * the shader live without rebuilding the program.
 */
export type ShaderValue = ShaderPlain | ((time: number) => ShaderPlain);

export type ShaderUniforms = Record<string, ShaderValue>;

/** The flow-of-time multiplier; a function is re-read every frame. */
export type ShaderSpeed = number | (() => number);

export interface ShaderStageOptions {
  /** GLSL ES 1.00 fragment source (default: `plasmaFrag`). */
  frag?: string;
  /** Flow-of-time multiplier (default 1). */
  speed?: ShaderSpeed;
  /** A CSS background for the fallback layer, shown when WebGL is missing. */
  fallback?: string;
  /** Extra uniforms, merged over the palette defaults (u_a/u_b/u_c, u_lo/u_mid/u_hi, u_water/u_shallow/u_crest). */
  uniforms?: ShaderUniforms;
  /** Canvas pixels per CSS pixel (default 0.5 — shaders are backgrounds, not type). */
  resolution?: number;
  /** Feed the eased pointer position as u_pointer (default false). */
  pointer?: boolean;
}

/* ---- The shipped shaders ------------------------------------------------------------------ */

/** Plasma: five phase-shifted sine fields folded into a three-stop palette. */
export const plasmaFrag = `
precision mediump float;
uniform vec2 u_res;
uniform float u_time;
uniform vec2 u_pointer;
uniform vec3 u_a;
uniform vec3 u_b;
uniform vec3 u_c;

void main() {
  vec2 uv = (gl_FragCoord.xy * 2.0 - u_res) / min(u_res.x, u_res.y);
  vec2 m = u_pointer * 0.5;
  float t = u_time * 0.6;
  vec2 q = vec2(uv.x - uv.y, uv.x + uv.y) * 0.7071;
  float v = sin(uv.x * 2.3 + m.x + t)
          + sin(uv.y * 2.7 - m.y - t * 0.7)
          + sin(q.x * 1.8 + m.y * 0.5 + t * 0.45)
          + sin(q.y * 2.1 - m.x * 0.5 - t * 0.35)
          + sin(length(uv - m) * 3.8 + t * 1.1);
  float k = v / 10.0 + 0.5;
  vec3 col = mix(u_a, u_b, smoothstep(0.05, 0.6, k));
  col = mix(col, u_c, smoothstep(0.58, 0.98, k));
  gl_FragColor = vec4(col, 1.0);
}`;

/** Noise: four octaves of value noise, the domain warped by a second fbm pass so the field folds instead of scrolling. */
export const noiseFrag = `
precision mediump float;
uniform vec2 u_res;
uniform float u_time;
uniform float u_scale;
uniform vec3 u_lo;
uniform vec3 u_mid;
uniform vec3 u_hi;

float hash(vec2 p) { return fract(sin(dot(p, vec2(41.71, 289.13))) * 43758.55); }
float vnoise(vec2 p) {
  vec2 i = floor(p);
  vec2 f = fract(p);
  vec2 s = f * f * (3.0 - 2.0 * f);
  return mix(mix(hash(i), hash(i + vec2(1.0, 0.0)), s.x),
             mix(hash(i + vec2(0.0, 1.0)), hash(i + vec2(1.0, 1.0)), s.x), s.y);
}
float fbm(vec2 p) {
  float v = 0.0;
  float a = 0.5;
  for (int k = 0; k < 4; k++) {
    v += a * vnoise(p);
    p = p * 2.13 + vec2(7.3, 11.9);
    a *= 0.5;
  }
  return v;
}

void main() {
  vec2 uv = (gl_FragCoord.xy * 2.0 - u_res) / min(u_res.x, u_res.y);
  vec2 q = uv * u_scale;
  vec2 warp = vec2(fbm(q + vec2(0.0, u_time * 0.06)), fbm(q + vec2(5.2, 1.3 - u_time * 0.05)));
  float n = fbm(q + warp * 0.9);
  vec3 col = mix(u_lo, u_mid, smoothstep(0.2, 0.72, n));
  col = mix(col, u_hi, smoothstep(0.7, 0.96, n));
  gl_FragColor = vec4(col, 1.0);
}`;

/** Ripples share one surface before the oldest silently gives way. */
export const rippleSlots = 12;
/** Seconds a wave stays alive after it is born. */
export const rippleLife = 3;

/** Ripple pond: every drop writes x, y, birth time and strength into a slot of `u_r`; sine rings decay exponentially. */
export const ripplesFrag = `
precision mediump float;
uniform vec2 u_res;
uniform float u_time;
uniform vec3 u_water;
uniform vec3 u_shallow;
uniform vec3 u_crest;
uniform vec4 u_r[${rippleSlots}];

void main() {
  vec2 uv = (gl_FragCoord.xy * 2.0 - u_res) / min(u_res.x, u_res.y);
  vec3 col = mix(u_water, u_shallow, clamp(uv.y * 0.5 + 0.5, 0.0, 1.0));
  float ring = 0.0;
  for (int i = 0; i < ${rippleSlots}; i++) {
    vec4 r = u_r[i];
    float age = u_time - r.z;
    if (age > 0.0 && age < ${rippleLife}.0) {
      float d = length(uv - r.xy);
      ring += sin(d * 22.0 - age * 7.0) * exp(-age * 1.6) * exp(-d * 1.1) * r.w;
    }
  }
  col += u_crest * max(ring, 0.0) * 0.9;
  col -= u_shallow * max(-ring, 0.0) * 0.35;
  float vig = 1.0 - 0.3 * dot(uv * 0.6, uv * 0.6);
  gl_FragColor = vec4(col * vig, 1.0);
}`;

/** Palette defaults for the shipped shaders — colour tokens, resolved against the element so both themes read. */
const DEFAULT_UNIFORMS: ShaderUniforms = {
  u_a: 'var(--nx-lapis-950)',
  u_b: 'var(--nx-violet-500)',
  u_c: 'var(--nx-cyan-300)',
  u_lo: 'var(--nx-lapis-950)',
  u_mid: 'var(--nx-violet-500)',
  u_hi: 'var(--nx-cyan-300)',
  u_water: 'var(--nx-ink-950)',
  u_shallow: 'var(--nx-cyan-700)',
  u_crest: 'var(--nx-cyan-300)',
};

/* ---- Engine ------------------------------------------------------------------------------ */

const VERTEX_SRC = 'attribute vec2 a;\nvoid main() { gl_Position = vec4(a, 0.0, 1.0); }';
const QUAD = new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]);
const GL_ATTRIBUTES: WebGLContextAttributes = { antialias: false, alpha: false, depth: false, stencil: false, powerPreference: 'low-power' };
/** The frame a reduced-motion still lands on — a phase where every shipped shader already reads. */
const STILL_TIME = 3.4;
/** While frozen, uniforms are re-read this often so sliders still retune the still frame. */
const CHECK_MS = 300;

/** An `rgb()` / `rgba()` / `color(srgb …)` string to 0–1 channels (percent forms included). */
function parseColor(text: string): [number, number, number] {
  const raw = text.match(/-?[\d.]+%?/g);
  if (!raw || raw.length < 3) return [0, 0, 0];
  const percent = text.includes('%');
  const channel = (part: string): number => {
    const n = Number.parseFloat(part);
    if (!Number.isFinite(n)) return 0;
    const v = percent ? n / 100 : n > 1 ? n / 255 : n;
    return Math.min(1, Math.max(0, v));
  };
  return [channel(raw[0]!), channel(raw[1]!), channel(raw[2]!)];
}

/**
 * Wire a `.nx-shader` stage: builds the WebGL1 program from `frag`, renders on
 * a RAF loop that parks while the stage is off screen, freezes to one still
 * frame under reduced motion (redrawn when a uniform value changes), re-resolves
 * colour tokens when the theme flips, eases the pointer when `pointer` is set,
 * and shows the fallback layer (`data-fallback`) when WebGL is missing or lost.
 * The canvas and fallback layer are created when the caller did not render
 * them. Cleans up everything it touched.
 */
export function shaderStage(el: HTMLElement, { frag = plasmaFrag, speed = 1, fallback, uniforms = {}, resolution = 0.5, pointer = false }: ShaderStageOptions = {}): Cleanup {
  if (!isBrowser) return () => {};
  const initialFallback = el.hasAttribute('data-fallback');
  const merged: ShaderUniforms = { ...DEFAULT_UNIFORMS, ...uniforms };

  // The stage markup: a canvas and a gradient layer under the content.
  const foundCanvas = el.querySelector<HTMLCanvasElement>('canvas.nx-shader-canvas');
  const canvas = foundCanvas ?? document.createElement('canvas');
  const madeCanvas = !foundCanvas;
  if (madeCanvas) {
    canvas.className = 'nx-shader-canvas';
    canvas.setAttribute('aria-hidden', 'true');
    el.prepend(canvas);
  }
  const foundLayer = el.querySelector<HTMLElement>('.nx-shader-fallback');
  const layer = foundLayer ?? document.createElement('div');
  const madeLayer = !foundLayer;
  if (madeLayer) {
    layer.className = 'nx-shader-fallback';
    layer.setAttribute('aria-hidden', 'true');
    canvas.insertAdjacentElement('afterend', layer);
  }
  if (fallback) layer.style.background = fallback;

  let gl: WebGLRenderingContext | null = null;
  let program: WebGLProgram | null = null;
  let buffer: WebGLBuffer | null = null;
  let lost = false;
  let visible = true;
  let raf = 0;
  let checkTimer = 0;
  let clock = 0;
  let lastNow = performance.now();
  let lastTime = -1;
  let snapshot = '';
  let probe: HTMLSpanElement | null = null;
  const locations = new Map<string, WebGLUniformLocation | null>();
  const colorCache = new Map<string, [number, number, number]>();
  const speedNow = (): number => (typeof speed === 'function' ? speed() : speed);

  /* Colours: resolved through a probe inside the stage, so var() tokens and
     color-mix() land as the themed RGB the current theme actually shows. */
  const cssColor = (value: string): [number, number, number] => {
    const cached = colorCache.get(value);
    if (cached) return cached;
    if (!probe) {
      probe = document.createElement('span');
      probe.className = 'nx-shader-probe';
      probe.setAttribute('aria-hidden', 'true');
      el.appendChild(probe);
    }
    probe.style.color = '';
    probe.style.color = value;
    const rgb = parseColor(getComputedStyle(probe).color);
    colorCache.set(value, rgb);
    return rgb;
  };

  const size = () => {
    const factor = Math.min(1, Math.max(0.1, resolution));
    const w = Math.max(2, Math.round(canvas.clientWidth * factor));
    const h = Math.max(2, Math.round(canvas.clientHeight * factor));
    if (canvas.width !== w || canvas.height !== h) {
      canvas.width = w;
      canvas.height = h;
    }
  };

  const build = (): boolean => {
    const context = canvas.getContext('webgl', GL_ATTRIBUTES) ?? (canvas.getContext('experimental-webgl', GL_ATTRIBUTES) as WebGLRenderingContext | null);
    const ctx = context as WebGLRenderingContext | null;
    if (!ctx) return false;
    gl = ctx;
    if (program) gl.deleteProgram(program);
    if (buffer) gl.deleteBuffer(buffer);
    const compile = (kind: number, src: string): WebGLShader | null => {
      const shader = gl!.createShader(kind);
      if (!shader) return null;
      gl!.shaderSource(shader, src);
      gl!.compileShader(shader);
      if (!gl!.getShaderParameter(shader, gl!.COMPILE_STATUS)) {
        console.warn(`[nabuxui] shaderStage could not compile: ${gl!.getShaderInfoLog(shader) ?? ''}`);
        gl!.deleteShader(shader);
        return null;
      }
      return shader;
    };
    const vs = compile(gl.VERTEX_SHADER, VERTEX_SRC);
    const fs = compile(gl.FRAGMENT_SHADER, frag);
    const prog = vs && fs ? gl.createProgram() : null;
    if (!vs || !fs || !prog) return false;
    gl.attachShader(prog, vs);
    gl.attachShader(prog, fs);
    gl.linkProgram(prog);
    gl.deleteShader(vs);
    gl.deleteShader(fs);
    if (!gl.getProgramParameter(prog, gl.LINK_STATUS)) {
      console.warn(`[nabuxui] shaderStage could not link: ${gl.getProgramInfoLog(prog) ?? ''}`);
      gl.deleteProgram(prog);
      return false;
    }
    gl.useProgram(prog);
    buffer = gl.createBuffer();
    gl.bindBuffer(gl.ARRAY_BUFFER, buffer);
    gl.bufferData(gl.ARRAY_BUFFER, QUAD, gl.STATIC_DRAW);
    const attr = gl.getAttribLocation(prog, 'a');
    gl.enableVertexAttribArray(attr);
    gl.vertexAttribPointer(attr, 2, gl.FLOAT, false, 0, 0);
    program = prog;
    locations.clear();
    return true;
  };

  const at = (name: string): WebGLUniformLocation | null => {
    if (!locations.has(name)) {
      const ctx = gl;
      const prog = program;
      locations.set(name, ctx && prog ? (ctx.getUniformLocation(prog, name) ?? ctx.getUniformLocation(prog, `${name}[0]`)) : null);
    }
    return locations.get(name) ?? null;
  };

  const resolved = (value: ShaderValue, t: number): ShaderPlain => (typeof value === 'function' ? value(t) : value);

  const apply = (name: string, plain: ShaderPlain): void => {
    const loc = at(name);
    const ctx = gl;
    if (!ctx || !loc) return;
    if (typeof plain === 'number') {
      ctx.uniform1f(loc, plain);
    } else if (typeof plain === 'string') {
      const [r, g, b] = cssColor(plain);
      ctx.uniform3f(loc, r, g, b);
    } else {
      const n = plain.length;
      if (n === 2) ctx.uniform2f(loc, plain[0]!, plain[1]!);
      else if (n === 3) ctx.uniform3f(loc, plain[0]!, plain[1]!, plain[2]!);
      else if (n === 4) ctx.uniform4f(loc, plain[0]!, plain[1]!, plain[2]!, plain[3]!);
      else {
        const values = plain instanceof Float32Array ? plain : new Float32Array(plain);
        if (n % 4 === 0) ctx.uniform4fv(loc, values);
        else if (n % 3 === 0) ctx.uniform3fv(loc, values);
        else if (n % 2 === 0) ctx.uniform2fv(loc, values);
        else ctx.uniform1fv(loc, values);
      }
    }
  };

  let pointerX = 0;
  let pointerY = 0;
  let targetX = 0;
  let targetY = 0;

  const draw = (t: number): void => {
    const ctx = gl;
    if (!ctx || !program) return;
    size();
    pointerX += (targetX - pointerX) * 0.09;
    pointerY += (targetY - pointerY) * 0.09;
    ctx.viewport(0, 0, ctx.drawingBufferWidth, ctx.drawingBufferHeight);
    apply('u_res', [ctx.drawingBufferWidth, ctx.drawingBufferHeight]);
    apply('u_time', t);
    if (pointer) apply('u_pointer', [pointerX, pointerY]);
    for (const [name, value] of Object.entries(merged)) apply(name, resolved(value, t));
    ctx.drawArrays(ctx.TRIANGLE_STRIP, 0, 4);
    if (el.hasAttribute('data-fallback')) el.removeAttribute('data-fallback');
  };

  /** The frozen frame: the last live clock (or a pleasant phase when it never ran). */
  const still = (): void => {
    const t = lastTime < 0 ? STILL_TIME : lastTime;
    draw(t);
    snapshot = valuesKey(t);
  };

  const valuesKey = (t: number): string =>
    Object.entries(merged)
      .map(([name, value]) => {
        const plain = resolved(value, t);
        const body = plain instanceof Float32Array ? Array.from(plain).join(',') : Array.isArray(plain) ? plain.join(',') : String(plain);
        return `${name}=${body}`;
      })
      .join('|');

  /** While frozen, retune the still frame only when a uniform really changed. */
  const checkChanges = (): void => {
    if (!visible) return;
    const t = lastTime < 0 ? STILL_TIME : lastTime;
    const key = valuesKey(t);
    if (key !== snapshot) {
      snapshot = key;
      still();
    }
  };

  const advance = (now: number): number => {
    clock += Math.max(0, (now - lastNow) / 1000) * speedNow();
    lastNow = now;
    return clock;
  };

  const loop = (now: number): void => {
    lastTime = advance(now);
    draw(lastTime);
    raf = requestAnimationFrame(loop);
  };

  const run = (): void => {
    window.cancelAnimationFrame(raf);
    raf = 0;
    if (!gl || !program || !visible || lost) return;
    window.clearInterval(checkTimer);
    if (prefersReducedMotion()) {
      still();
      checkTimer = window.setInterval(checkChanges, CHECK_MS);
      return;
    }
    lastNow = performance.now();
    raf = requestAnimationFrame(loop);
  };

  /* Watches */
  const observer =
    'IntersectionObserver' in window
      ? new IntersectionObserver(([entry]) => {
          visible = !!entry?.isIntersecting;
          run();
        }, { rootMargin: '64px' })
      : null;
  observer?.observe(el);
  const sizes = 'ResizeObserver' in window ? new ResizeObserver(() => { size(); if (!raf) still(); }) : null;
  sizes?.observe(canvas);
  const stopMotion = onReducedMotionChange(run);

  /** Any theme flip (data-theme, dark/light classes, scheme change) re-resolves every colour. */
  const themeChanged = (): void => {
    colorCache.clear();
    if (!raf && visible) still();
  };
  const themes = new MutationObserver(themeChanged);
  themes.observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme', 'class'] });
  const scheme = window.matchMedia('(prefers-color-scheme: dark)');
  scheme.addEventListener('change', themeChanged);

  const move = (event: PointerEvent): void => {
    if (prefersReducedMotion()) return;
    const rect = el.getBoundingClientRect();
    if (rect.width === 0 || rect.height === 0) return;
    targetX = ((event.clientX - rect.left) / rect.width) * 2 - 1;
    targetY = 1 - ((event.clientY - rect.top) / rect.height) * 2;
  };
  const calm = (): void => {
    targetX = 0;
    targetY = 0;
  };
  if (pointer) {
    el.addEventListener('pointermove', move);
    el.addEventListener('pointerleave', calm);
  }

  const onLost = (event: Event): void => {
    event.preventDefault();
    lost = true;
    window.cancelAnimationFrame(raf);
    raf = 0;
    window.clearInterval(checkTimer);
    el.setAttribute('data-fallback', '');
  };
  const onRestored = (): void => {
    lost = false;
    program = null;
    buffer = null;
    if (build()) run();
  };
  canvas.addEventListener('webglcontextlost', onLost);
  canvas.addEventListener('webglcontextrestored', onRestored);

  const restore = (): void => {
    window.cancelAnimationFrame(raf);
    window.clearInterval(checkTimer);
    observer?.disconnect();
    sizes?.disconnect();
    themes.disconnect();
    scheme.removeEventListener('change', themeChanged);
    el.removeEventListener('pointermove', move);
    el.removeEventListener('pointerleave', calm);
    canvas.removeEventListener('webglcontextlost', onLost);
    canvas.removeEventListener('webglcontextrestored', onRestored);
    probe?.remove();
    if (gl) {
      if (buffer) gl.deleteBuffer(buffer);
      if (program) gl.deleteProgram(program);
      gl.getExtension('WEBGL_lose_context')?.loseContext();
    }
    if (madeCanvas) canvas.remove();
    if (madeLayer) layer.remove();
    el.toggleAttribute('data-fallback', initialFallback);
  };

  if (!build()) {
    el.setAttribute('data-fallback', '');
    return restore;
  }
  size();
  run();
  return restore;
}
