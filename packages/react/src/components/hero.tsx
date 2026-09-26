import { type CSSProperties, type HTMLAttributes, type ReactNode, useRef } from 'react';
import { reveal, spotlight } from '@nabuxai/ui-core';
import { cx, useBehavior } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink } from '../internal/provider';
import { Badge } from './feedback';
import { TextReveal } from './text';

export type BackdropVariant = 'aurora' | 'grid' | 'stars' | 'beams' | 'dots';

/** A decorative backdrop, pure CSS. Place it first inside a positioned section. */
export function Backdrop({ variant = 'aurora', className, style }: { variant?: BackdropVariant; className?: string; style?: CSSProperties }) {
  return (
    <div className={cx('nx-backdrop', className)} data-variant={variant} aria-hidden="true" style={style}>
      <i />
      <i />
      <i />
    </div>
  );
}

export interface HeroProps extends Omit<HTMLAttributes<HTMLElement>, 'title'> {
  backdrop?: BackdropVariant | false;
  /** The small pill above the title: text, an optional badge, an optional link. */
  eyebrow?: { label: ReactNode; badge?: ReactNode; href?: string };
  /** A string gets the word-by-word reveal; any node is rendered as is. */
  title: ReactNode;
  subtitle?: ReactNode;
  actions?: ReactNode;
  /** A product shot or illustration that tips into place under the text. */
  media?: ReactNode;
  align?: 'center' | 'start';
}

export function Hero({ backdrop = 'aurora', eyebrow, title, subtitle, actions, media, align = 'center', className, children, ...rest }: HeroProps) {
  const section = useRef<HTMLElement>(null);
  const content = useRef<HTMLDivElement>(null);
  const mediaRef = useRef<HTMLDivElement>(null);
  useBehavior(section, spotlight, undefined as never, backdrop === 'grid');
  useBehavior(content, reveal, { stagger: true, once: true });
  useBehavior(mediaRef, reveal, { once: true, threshold: 0.05 }, !!media);

  const eyebrowInner = eyebrow && (
    <>
      {eyebrow.badge && (
        <Badge tone="accent" variant="solid">
          {eyebrow.badge}
        </Badge>
      )}
      <span>{eyebrow.label}</span>
      {eyebrow.href && <Icon name="arrow-right" />}
    </>
  );

  return (
    <section ref={section} className={cx('nx-hero', className)} data-align={align === 'start' ? 'start' : undefined} {...rest}>
      {backdrop && <Backdrop variant={backdrop} />}
      <div ref={content} className="nx-hero-content" data-nx-reveal="group">
        {eyebrow &&
          (eyebrow.href ? (
            <SmartLink className="nx-hero-eyebrow" href={eyebrow.href}>
              {eyebrowInner}
            </SmartLink>
          ) : (
            <p className="nx-hero-eyebrow">{eyebrowInner}</p>
          ))}
        {typeof title === 'string' ? <TextReveal as="h1" className="nx-hero-title" delay={120}>{title}</TextReveal> : <h1 className="nx-hero-title">{title}</h1>}
        {subtitle && <p className="nx-hero-subtitle">{subtitle}</p>}
        {actions && <div className="nx-hero-actions">{actions}</div>}
        {children}
      </div>
      {media && (
        <div ref={mediaRef} className="nx-hero-media" data-nx-reveal="">
          <div>{media}</div>
        </div>
      )}
    </section>
  );
}
