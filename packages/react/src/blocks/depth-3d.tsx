/**
 * 3D depth (React): scenes built from perspective + preserve-3d, no WebGL.
 * Thin over css/blocks/depth-3d.css and the core behaviours in
 * js/blocks/depth-3d.ts — the components render the markup and own the state.
 *
 *   ProductRoom  a multi-layer product staged along the Z axis; drag orbits
 *                with inertia and idle drift, the exploded view lifts the
 *                layers apart and pins a billboarded label to each one
 *   FlipCube     six selling points folded into one true cube; drag spins and
 *                tilts, the release snaps onto the nearest face, and buttons,
 *                dots and arrow keys walk face by face
 *   OpenBox      the gift box as the hero: hover (or focus + Enter) swings the
 *                lid off its hinge on an overshooting spring while the goods
 *                leap above the rim; a click pins the open state
 */
import {
  type CSSProperties,
  type HTMLAttributes,
  type KeyboardEvent as ReactKeyboardEvent,
  type PointerEvent as ReactPointerEvent,
  type ReactNode,
  forwardRef,
  useRef,
  useState,
} from 'react';
import {
  type DragRotateCommand,
  type DragRotateState,
  type IconName,
  dragRotate,
  pauseWhileOffscreen,
  tilt,
} from '@nabuxai/ui-core';
import { cx, mergeRefs, useBehavior, useControllable, useEvent } from '../internal/hooks';
import { Icon } from '../internal/icon';

/** Tell an element running `dragRotate` to move — the one control surface. */
const rotate = (el: HTMLElement | null, detail: DragRotateCommand): void => {
  el?.dispatchEvent(new CustomEvent<DragRotateCommand>('nx-rotate', { detail }));
};

const indexStyle = (index: number): CSSProperties => ({ '--nx-i': index }) as CSSProperties;

/* ---- Product room ------------------------------------------------------------------ */

export interface ProductRoomLayer {
  id: string;
  /** The layer's name, shown on its exploded-view label. */
  label: ReactNode;
  /** One line of specification under the label. */
  spec?: ReactNode;
  /** The slab's material (default a plain disc). */
  tone?: 'pedestal' | 'mesh' | 'driver' | 'ring' | 'dome';
}

export interface ProductRoomStatus {
  spin: number;
  tilt: number;
  exploded: boolean;
}

export interface ProductRoomProps extends HTMLAttributes<HTMLDivElement> {
  /** The product's layers, bottom-up. */
  layers: ProductRoomLayer[];
  /** Accessible name of the stage — it is focusable and the arrow keys orbit it. */
  label?: string;
  /** The corner chip's text (default “Drag to orbit”). */
  caption?: string;
  /** Copy of the exploded-view toggle. */
  explodeLabel?: string;
  /** Copy of the reset-view button. */
  resetLabel?: string;
  /** The live status line; override to localise (default English). */
  statusLabel?: (status: ProductRoomStatus) => ReactNode;
  /** Exploded view (controlled). */
  exploded?: boolean;
  defaultExploded?: boolean;
  onExplodedChange?: (exploded: boolean) => void;
}

/**
 * A product staged as a stack of layers in one perspective world. Dragging
 * anywhere spins and tilts the room — it coasts on the finger's speed, then
 * drifts slowly while idle. The exploded view lifts the layers apart and pins
 * a label to each one that counter-rotates so it always reads facing you.
 */
export const ProductRoom = forwardRef<HTMLDivElement, ProductRoomProps>(function ProductRoom(
  {
    layers,
    label = 'Three-dimensional product room; drag to spin and tilt',
    caption = 'Drag to orbit',
    explodeLabel = 'Exploded view',
    resetLabel = 'Reset view',
    statusLabel,
    exploded,
    defaultExploded = false,
    onExplodedChange,
    className,
    ...rest
  },
  forwarded,
) {
  const own = useRef<HTMLDivElement>(null);
  const stage = useRef<HTMLDivElement>(null);
  const [readout, setReadout] = useState<DragRotateState>({ spin: -28, tilt: 62, dragging: false });
  const [isExploded, setExploded] = useControllable(exploded, defaultExploded, onExplodedChange);

  const onRotate = useEvent((state: DragRotateState) => setReadout(state));
  useBehavior(stage, dragRotate, { inertia: true, spin: -28, tilt: 62, minTilt: 30, maxTilt: 80, onRotate });

  const status =
    statusLabel?.({ spin: readout.spin, tilt: readout.tilt, exploded: isExploded }) ??
    `spin ${readout.spin}° · tilt ${readout.tilt}° · ${isExploded ? 'exploded' : 'stacked'}`;

  return (
    <div
      ref={mergeRefs(forwarded, own)}
      className={cx('nx-product-room', className)}
      data-exploded={isExploded ? '' : undefined}
      {...rest}
    >
      <div
        ref={stage}
        className="nx-3d-stage nx-product-room-stage"
        tabIndex={0}
        role="img"
        aria-label={label}
      >
        {caption !== undefined && <span className="nx-3d-cap">{caption}</span>}
        <div
          className="nx-product-room-world"
          style={{ '--nx-n': layers.length } as CSSProperties}
        >
          {layers.map((layer, index) => (
            <div
              key={layer.id}
              className="nx-product-room-slab"
              data-tone={layer.tone}
              style={indexStyle(index)}
            >
              <span className="nx-product-room-pin">
                <span className="nx-product-room-tag" style={indexStyle(index)}>
                  <span className="nx-product-room-tag-title">{layer.label}</span>
                  {layer.spec !== undefined && (
                    <span className="nx-product-room-tag-spec">{layer.spec}</span>
                  )}
                </span>
              </span>
            </div>
          ))}
        </div>
      </div>

      <div className="nx-3d-dock">
        <button
          type="button"
          className="nx-3d-btn"
          aria-pressed={isExploded}
          onClick={() => setExploded(!isExploded)}
        >
          <Icon name="layers" />
          <span>{explodeLabel}</span>
        </button>
        <button type="button" className="nx-3d-btn" onClick={() => rotate(stage.current, { reset: true })}>
          <span>{resetLabel}</span>
        </button>
        <span className="nx-3d-status" role="status">
          {status}
        </span>
      </div>
    </div>
  );
});

/* ---- Flip cube ------------------------------------------------------------------------ */

/** Where each face sits: the first four ring the Y axis, the last two cap it on X. */
const FACE_TURNS = ['rotateY(0deg)', 'rotateY(90deg)', 'rotateY(180deg)', 'rotateY(270deg)', 'rotateX(90deg)', 'rotateX(-90deg)'] as const;

export interface FlipCubeFace {
  id: string;
  /** A decorative icon on the face. */
  icon?: IconName;
  /** The selling point — the face's heading. */
  title: ReactNode;
  /** One line of detail under it. */
  note?: ReactNode;
}

export interface FlipCubeStatus {
  /** Which face currently faces the camera (0–5). */
  face: number;
  /** How many faces the cube has. */
  total: number;
  /** The facing face's title. */
  title: ReactNode;
}

export interface FlipCubeProps extends HTMLAttributes<HTMLDivElement> {
  /** Six faces: the first four ring the sides, the fifth caps the top, the sixth the bottom. */
  faces: FlipCubeFace[];
  /** Accessible name of the stage — it is focusable and the arrow keys walk the faces. */
  label?: string;
  /** The corner chip's text (default “Drag · arrow keys”). */
  caption?: string;
  /** Copy of the previous-face button. */
  prevLabel?: string;
  /** Copy of the next-face button. */
  nextLabel?: string;
  /** Accessible name of each face's dot (default “Face 1”…). */
  faceLabel?: (face: FlipCubeFace, index: number) => string;
  /** The live status line; override to localise (default English). */
  statusLabel?: (status: FlipCubeStatus) => ReactNode;
}

/** The face facing the camera for a spin/tilt pair, as the stylesheet renders them. */
function facingFace(spin: number, tilt: number): number {
  const steps = Math.round(tilt / 90);
  if (steps === -1) return 4;
  if (steps === 1) return 5;
  return ((Math.round(-spin / 90) % 4) + 4) % 4;
}

/**
 * Six selling points folded into one CSS-3D cube. Drag anywhere to spin and
 * tilt it — on release it reads the finger's speed and springs onto the
 * nearest face. The face toward you lights up, mirrored by the dot rail; the
 * buttons and the arrow keys walk face by face (up/down flip to the caps),
 * and every dot jumps by the shortest path.
 */
export const FlipCube = forwardRef<HTMLDivElement, FlipCubeProps>(function FlipCube(
  {
    faces,
    label = 'Feature cube; drag to spin, or use the arrow keys',
    caption = 'Drag · arrow keys',
    prevLabel = 'Previous face',
    nextLabel = 'Next face',
    faceLabel,
    statusLabel,
    className,
    ...rest
  },
  forwarded,
) {
  const stage = useRef<HTMLDivElement>(null);
  const angles = useRef({ spin: -24, tilt: 0 });
  const [readout, setReadout] = useState<DragRotateState>({ spin: -24, tilt: 0, dragging: false });

  const onRotate = useEvent((state: DragRotateState) => {
    angles.current = { spin: state.spin, tilt: state.tilt };
    setReadout(state);
  });
  useBehavior(stage, dragRotate, { snap: 90, inertia: false, spin: -24, tilt: 0, minTilt: -90, maxTilt: 90, onRotate });

  const face = facingFace(readout.spin, readout.tilt);

  /** Walk the ring by 90° (the arrow keys do the same through the behaviour). */
  const step = (delta: number): void => rotate(stage.current, { bySpin: delta * 90 });

  /** Jump to a face by the shortest path around the ring. */
  const jump = (index: number): void => {
    if (index === 4 || index === 5) {
      rotate(stage.current, { toTilt: index === 4 ? -90 : 90 });
      return;
    }
    const current = ((angles.current.spin % 360) + 360) % 360;
    const desired = ((-90 * index) % 360 + 360) % 360;
    let delta = desired - current;
    if (delta > 180) delta -= 360;
    if (delta < -180) delta += 360;
    rotate(stage.current, { toTilt: 0, bySpin: delta });
  };

  const status =
    statusLabel?.({ face, total: faces.length, title: faces[face]?.title }) ??
    <>
      {`Face ${face + 1} of ${faces.length}`}
      {faces[face]?.title !== undefined && <> · {faces[face]?.title}</>}
    </>;

  return (
    <div ref={forwarded} className={cx('nx-flip-cube', className)} {...rest}>
      <div
        ref={stage}
        className="nx-3d-stage nx-flip-cube-stage"
        tabIndex={0}
        role="group"
        aria-label={label}
      >
        {caption !== undefined && <span className="nx-3d-cap">{caption}</span>}
        <div className="nx-flip-cube-cube">
          {faces.slice(0, FACE_TURNS.length).map((item, index) => (
            <div
              key={item.id}
              className="nx-flip-cube-face"
              data-on={face === index ? '' : undefined}
              style={{ '--ft': FACE_TURNS[index] } as CSSProperties}
            >
              {item.icon !== undefined && <Icon name={item.icon} />}
              <span className="nx-flip-cube-title">{item.title}</span>
              {item.note !== undefined && <span className="nx-flip-cube-note">{item.note}</span>}
            </div>
          ))}
        </div>
      </div>

      <div className="nx-3d-dock">
        <button type="button" className="nx-3d-btn" onClick={() => step(1)} aria-label={prevLabel}>
          <Icon name="chevron-left" />
        </button>
        <span className="nx-flip-cube-dots">
          {faces.slice(0, FACE_TURNS.length).map((item, index) => (
            <button
              key={item.id}
              type="button"
              className="nx-flip-cube-dot"
              aria-current={face === index ? 'true' : undefined}
              aria-label={faceLabel?.(item, index) ?? `Face ${index + 1}`}
              onClick={() => jump(index)}
            />
          ))}
        </span>
        <button type="button" className="nx-3d-btn" onClick={() => step(-1)} aria-label={nextLabel}>
          <Icon name="chevron-right" />
        </button>
        <span className="nx-3d-status" role="status">
          {status}
        </span>
      </div>
    </div>
  );
});

/* ---- Open box -------------------------------------------------------------------------- */

export interface OpenBoxStatus {
  /** Whether the lid is open (hover counts while it lasts). */
  open: boolean;
  /** Whether the open state is pinned by a click / Enter / Space. */
  pinned: boolean;
}

export interface OpenBoxProps extends Omit<HTMLAttributes<HTMLDivElement>, 'title'> {
  /** The brand line on the box front. */
  title?: ReactNode;
  /** The small line under it. */
  subtitle?: ReactNode;
  /** The price on the two-sided tag. */
  price?: ReactNode;
  /** The product's name — the tag's and the goods pill's small line. */
  name?: ReactNode;
  /** Accessible name of the stage (it is a button: hover or click opens). */
  label?: string;
  /** The corner chip's text (default “Hover · click”). */
  caption?: string;
  /** Copy of the open/close toggle. */
  openLabel?: string;
  closeLabel?: string;
  /** The live status line; override to localise (default English). */
  statusLabel?: (status: OpenBoxStatus) => ReactNode;
  /** Pin the box open (controlled). */
  open?: boolean;
  defaultOpen?: boolean;
  onOpenChange?: (open: boolean) => void;
}

/**
 * The gift box as the hero of a product page. Hovering swings the lid 110°
 * off its back hinge on an overshooting spring while the goods leap above
 * the rim; a click (or Enter / Space — the stage is a real button) pins the
 * open state, and `aria-expanded` announces it either way. The pointer tilts
 * the box by a few degrees on a short delay, and the two-sided price tag
 * keeps turning on its own axis until the stage leaves the view.
 */
export const OpenBox = forwardRef<HTMLDivElement, OpenBoxProps>(function OpenBox(
  {
    title,
    subtitle,
    price,
    name,
    label = 'Gift box; hover or click to open',
    caption = 'Hover · click',
    openLabel = 'Open the box',
    closeLabel = 'Close the box',
    statusLabel,
    open,
    defaultOpen = false,
    onOpenChange,
    className,
    ...rest
  },
  forwarded,
) {
  const own = useRef<HTMLDivElement>(null);
  const stage = useRef<HTMLDivElement>(null);
  const [pinned, setPinned] = useControllable(open, defaultOpen, onOpenChange);
  const [hovering, setHovering] = useState(false);
  const isOpen = pinned || hovering;

  // The pointer lean: the shared core tilt writes --nx-rx/--nx-ry on the stage.
  useBehavior(stage, tilt, { max: 7 });
  // The price tag's loop parks while the whole box is off screen.
  useBehavior(own, pauseWhileOffscreen, {});

  const toggle = (): void => setPinned(!pinned);
  const onStageKeyDown = (event: ReactKeyboardEvent<HTMLDivElement>): void => {
    if (event.key === 'Enter' || event.key === ' ' || event.code === 'Space') {
      event.preventDefault();
      toggle();
    }
  };
  // Only a fine pointer hovers the box open; touch uses the button or a tap.
  const onEnter = (event: ReactPointerEvent<HTMLDivElement>): void => {
    if (event.pointerType === 'mouse' || event.pointerType === 'pen') setHovering(true);
  };

  const status =
    statusLabel?.({ open: isOpen, pinned }) ??
    (isOpen ? `lid open · 110° · ${pinned ? 'pinned by click' : 'open on hover'}` : 'box closed · hover or click');

  return (
    <div
      ref={mergeRefs(forwarded, own)}
      className={cx('nx-open-box', className)}
      data-open={isOpen ? '' : undefined}
      {...rest}
    >
      <div
        ref={stage}
        className="nx-3d-stage nx-open-box-stage"
        tabIndex={0}
        role="button"
        aria-expanded={isOpen}
        aria-label={label}
        onPointerEnter={onEnter}
        onPointerLeave={() => setHovering(false)}
        onClick={toggle}
        onKeyDown={onStageKeyDown}
      >
        {caption !== undefined && <span className="nx-3d-cap">{caption}</span>}
        <span className="nx-open-box-shadow" aria-hidden="true" />
        <div className="nx-open-box-tilt">
          <div className="nx-open-box-box">
            <div className="nx-open-box-face nx-open-box-front">
              {(title !== undefined || subtitle !== undefined) && (
                <span className="nx-open-box-mark" aria-hidden="true" />
              )}
              {title !== undefined && <span className="nx-open-box-brand">{title}</span>}
              {subtitle !== undefined && <span className="nx-open-box-sub">{subtitle}</span>}
            </div>
            <div className="nx-open-box-face nx-open-box-back" aria-hidden="true" />
            <div className="nx-open-box-face nx-open-box-left" aria-hidden="true" />
            <div className="nx-open-box-face nx-open-box-right" aria-hidden="true" />
            <div className="nx-open-box-floor" aria-hidden="true" />
            <div className="nx-open-box-goods" aria-hidden="true">
              <span className="nx-open-box-budsrow">
                <span className="nx-open-box-bud" />
                <span className="nx-open-box-bud" />
              </span>
              <span className="nx-open-box-case" />
              {name !== undefined && <span className="nx-open-box-goods-name">{name}</span>}
            </div>
            <div className="nx-open-box-lid" aria-hidden="true" />
          </div>
        </div>
        {price !== undefined && (
          <div className="nx-open-box-tagwrap">
            <div className="nx-open-box-tag">
              {[0, 1].map((side) => (
                <div key={side} className="nx-open-box-tface">
                  <span className="nx-open-box-price">{price}</span>
                  {name !== undefined && <span className="nx-open-box-price-note">{name}</span>}
                </div>
              ))}
            </div>
          </div>
        )}
      </div>

      <div className="nx-3d-dock">
        <button type="button" className="nx-3d-btn" aria-expanded={isOpen} onClick={toggle}>
          <Icon name="sparkles" />
          <span>{isOpen ? closeLabel : openLabel}</span>
        </button>
        <span className="nx-3d-status" role="status">
          {status}
        </span>
      </div>
    </div>
  );
});
