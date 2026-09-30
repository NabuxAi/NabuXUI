/**
 * Backdrops (React): animated backgrounds that carry their content.
 *
 * The block is the section itself — an isolated paint stack whose empty layer
 * spans (nx-backdrop-layer) sit under the children and are styled per variant
 * by css/blocks/backdrops.css. This file only renders markup: no state, no
 * behaviour, nothing that waits for JavaScript.
 *
 * Variants follow the hero system's convention of a few empty layers every
 * variant styles its own way — three by default, five for the mesh ('dither'
 * may later swap the spans for a canvas, as dither-backdrop.blade.php does).
 */
import { type HTMLAttributes, type ReactNode, forwardRef } from 'react';
import { cx } from '../internal/hooks';

export type BackdropVariant = 'aurora' | 'starfield' | 'mesh' | 'dither' | 'plain';

/** Layer spans per variant, as the stylesheet expects them: the mesh paints five fields, the rest three. */
const layerCount: Record<BackdropVariant, number> = { aurora: 3, starfield: 3, mesh: 5, dither: 3, plain: 3 };

export interface BackdropProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children'> {
  /** The look of the layers behind the content. */
  variant?: BackdropVariant;
  /** What sits over the layers. */
  children: ReactNode;
}

/**
 * Content over an animated background: drifting aurora curtains by default.
 * Extra classes land on the container; the rest of the native props pass
 * through to it.
 */
export const Backdrop = forwardRef<HTMLDivElement, BackdropProps>(function Backdrop(
  { variant = 'aurora', className, children, ...rest },
  ref,
) {
  return (
    <div ref={ref} className={cx(`nx-backdrop-${variant}`, className)} {...rest}>
      {Array.from({ length: layerCount[variant] }, (_, i) => (
        <span key={i} className="nx-backdrop-layer" aria-hidden="true" />
      ))}
      {children}
    </div>
  );
});
