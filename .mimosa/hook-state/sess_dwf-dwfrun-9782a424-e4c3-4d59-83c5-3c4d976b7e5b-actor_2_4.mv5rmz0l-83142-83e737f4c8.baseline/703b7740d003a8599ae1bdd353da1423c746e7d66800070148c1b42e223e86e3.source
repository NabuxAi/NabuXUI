/**
 * Navbars for React.
 *
 * Three bars over one shared look: the floating pill whose indicator springs
 * across the links and condenses as its container scrolls; the glass bar that
 * floats over a hero and grows glass pills under the pointer; and the mega
 * menu whose panel opens on hover with a close grace, toggles on click, walks
 * in with ArrowDown and shuts on Escape. This family has no core behaviour:
 * the components own their state and the CSS carries the motion.
 */
import {
  type HTMLAttributes,
  type ReactNode,
  type RefObject,
  forwardRef,
  useEffect,
  useRef,
  useState,
} from 'react';
import { type IconName } from '@nabuxai/ui-core';
import { cx, useControllable, useEvent, useIndicator, useIsoLayoutEffect } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useT } from '../internal/provider';

/* ---- Shared prop pieces ------------------------------------------------------- */

/** A plain link in a bar: `href` when it routes, nothing when it only acts. */
export interface NavbarLink {
  label: string;
  href?: string;
  icon?: IconName;
  /** Marks the link of the current page (`aria-current="page"`). */
  current?: boolean;
}

/** The bar's wordmark and where it leads. */
export interface NavbarBrand {
  name: string;
  href?: string;
}

/** The pill's call to action. */
export interface NavbarCta {
  label: string;
  href?: string;
  icon?: IconName;
  onClick?: () => void;
}

/** One link inside a bar, rendered as a link when it routes and a button otherwise. */
function BarLink({ href, className, current, icon, label, onClick }: NavbarLink & { className: string; onClick?: () => void }) {
  const content = (
    <>
      {icon && <Icon name={icon} />}
      <span>{label}</span>
    </>
  );
  const common = {
    className,
    'aria-current': current ? ('page' as const) : undefined,
    onClick,
  };
  return href ? (
    <SmartLink href={href} {...common}>
      {content}
    </SmartLink>
  ) : (
    <button type="button" {...common}>
      {content}
    </button>
  );
}

/** The bar's wordmark: a gradient mark plus the folding word. */
function BarBrand({ brand, mark, word, base }: { brand: NavbarBrand; mark: string; word?: string; base: string }) {
  return (
    <SmartLink href={brand.href ?? '#'} className={base}>
      <span className={mark} aria-hidden="true" />
      {word ? <span className={word}>{brand.name}</span> : brand.name}
    </SmartLink>
  );
}

/** The CTA at the end of a bar: a link when it routes, a button when it acts. */
function BarCta({ cta, className }: { cta: NavbarCta; className: string }) {
  const content = (
    <>
      {cta.label}
      {cta.icon && <Icon name={cta.icon} />}
    </>
  );
  return cta.href ? (
    <SmartLink href={cta.href} className={className}>
      {content}
    </SmartLink>
  ) : (
    <button type="button" className={className} onClick={cta.onClick}>
      {content}
    </button>
  );
}

/** True once the watched scroller passes `threshold` px; cleaned up on unmount. */
function useScrollPast(watch: boolean | RefObject<HTMLElement | null> | undefined, threshold: number): boolean {
  const [past, setPast] = useState(false);
  const key = watch === undefined || watch === false ? 'none' : watch === true ? 'window' : 'ref';
  const container = typeof watch === 'object' ? watch : undefined;

  useEffect(() => {
    if (key === 'none') return;
    const el: Window | HTMLElement | null = key === 'window' ? window : container?.current ?? null;
    if (!el) return;
    const measure = () => {
      const top = key === 'window' ? window.scrollY : (el as HTMLElement).scrollTop;
      setPast(top > threshold);
    };
    measure();
    el.addEventListener('scroll', measure, { passive: true });
    return () => el.removeEventListener('scroll', measure);
  }, [key, container, threshold]);

  return past;
}

/* ---- Floating pill ---------------------------------------------------------------- */

/** A link of the floating pill, addressed by value like a tab. */
export interface FloatingNavLink {
  value: string;
  label: string;
  href?: string;
  icon?: IconName;
}

export interface NavbarFloatingProps extends HTMLAttributes<HTMLElement> {
  links: FloatingNavLink[];
  value?: string;
  defaultValue?: string;
  onValueChange?: (value: string) => void;
  brand?: NavbarBrand;
  cta?: NavbarCta;
  /**
   * Condense past a little scroll: tighter padding, a deeper shadow, the
   * wordmark folding away. `true` watches the window; pass a ref to watch a
   * scrolling container instead.
   */
  condenseOnScroll?: boolean | RefObject<HTMLElement | null>;
  /** Force the condensed look regardless of scroll (specimens, docs). */
  condensed?: boolean;
}

/**
 * A sticky pill navbar: the indicator pill springs to the link you pick, and
 * as its container scrolls the whole pill condenses around what is left.
 */
export const NavbarFloating = forwardRef<HTMLElement, NavbarFloatingProps>(function NavbarFloating(
  { links, value, defaultValue, onValueChange, brand, cta, condenseOnScroll, condensed: forced, className, ...rest },
  ref,
) {
  const [current, setCurrent] = useControllable(value, defaultValue ?? links[0]?.value ?? '', onValueChange);
  const list = useRef<HTMLUListElement>(null);
  const ind = useIndicator(list);
  const scrolled = useScrollPast(condenseOnScroll, 24);
  const condensed = forced ?? scrolled;

  // The pill springs to the current link whenever it or the set of links changes.
  useIsoLayoutEffect(() => {
    ind.current?.update(list.current?.querySelector('[aria-current="page"]') ?? null);
  }, [ind, current, links]);

  // Condensing reshapes every link: re-measure so the pill lands true.
  useEffect(() => {
    ind.current?.refresh();
  }, [ind, condensed]);

  return (
    <nav ref={ref} className={cx('nx-floating-nav', className)} data-condensed={condensed ? '' : undefined} {...rest}>
      {brand && <BarBrand brand={brand} base="nx-floating-nav-brand" mark="nx-floating-nav-mark" word="nx-floating-nav-word" />}
      <ul ref={list} className="nx-floating-nav-links">
        <span className="nx-indicator nx-floating-nav-pill" aria-hidden="true" />
        {links.map((link) => (
          <li key={link.value}>
            <BarLink
              className="nx-floating-nav-link"
              current={link.value === current}
              href={link.href}
              icon={link.icon}
              label={link.label}
              onClick={() => setCurrent(link.value)}
            />
          </li>
        ))}
      </ul>
      {cta && <BarCta cta={cta} className="nx-floating-nav-cta" />}
    </nav>
  );
});

/* ---- Glass bar -------------------------------------------------------------------- */

export interface NavbarGlassProps extends HTMLAttributes<HTMLElement> {
  links: NavbarLink[];
  brand?: NavbarBrand;
  /** Tools at the far end: a mood button, an avatar, a sign-in… */
  actions?: ReactNode;
  /** `overlay` floats at the top of a hero; `sticky` pins to the scroll edge. */
  position?: 'overlay' | 'sticky';
}

/**
 * A bar of glass over a hero: blur and saturation over whatever shines
 * through, links that grow glass pills on hover and focus.
 */
export const NavbarGlass = forwardRef<HTMLElement, NavbarGlassProps>(function NavbarGlass(
  { links, brand, actions, position = 'overlay', className, 'aria-label': label, ...rest },
  ref,
) {
  return (
    <header ref={ref} className={cx('nx-glassbar', className)} data-position={position === 'sticky' ? position : undefined} {...rest}>
      {brand && <BarBrand brand={brand} base="nx-glassbar-brand" mark="nx-glassbar-mark" />}
      <nav aria-label={label}>
        <ul className="nx-glassbar-links">
          {links.map((link) => (
            <li key={link.label}>
              <BarLink className="nx-glassbar-link" {...link} />
            </li>
          ))}
        </ul>
      </nav>
      {actions && <span className="nx-glassbar-end">{actions}</span>}
    </header>
  );
});

/* ---- Mega menu -------------------------------------------------------------------- */

/** One row inside a mega panel section. */
export interface MegaMenuItem {
  label: string;
  description?: string;
  icon?: IconName;
  href?: string;
}

/** A titled column of the mega panel. */
export interface MegaMenuSection {
  title: string;
  items: MegaMenuItem[];
}

/** The gradient card at the far end of the mega panel. */
export interface MegaMenuPromo {
  title: string;
  body: string;
  actionLabel: string;
  href?: string;
  icon?: IconName;
}

export interface NavbarMegaProps extends HTMLAttributes<HTMLDivElement> {
  /** The panel's columns of links. */
  sections: MegaMenuSection[];
  /** The plain links that sit beside the trigger in the bar. */
  links?: NavbarLink[];
  promo?: MegaMenuPromo;
  brand?: NavbarBrand;
  /** Tools at the far end of the bar (a sign-in, a small CTA…). */
  actions?: ReactNode;
  /** The trigger's label; it opens the panel. */
  trigger?: string;
  /** Accessible name of the panel region. */
  panelLabel?: string;
  open?: boolean;
  defaultOpen?: boolean;
  onOpenChange?: (open: boolean) => void;
  /** ms the panel waits before closing, so the pointer can cross the gap. */
  grace?: number;
}

/**
 * A bar whose mega panel opens on hover with a close grace, toggles on click,
 * walks the keyboard in with ArrowDown and snaps shut on Escape.
 */
export const NavbarMega = forwardRef<HTMLDivElement, NavbarMegaProps>(function NavbarMega(
  { sections, links, promo, brand, actions, trigger, panelLabel, open: openProp, defaultOpen = false, onOpenChange, grace = 140, className, 'aria-label': label, ...rest },
  ref,
) {
  const t = useT();
  const [open, setOpen] = useControllable(openProp, defaultOpen, onOpenChange);
  const root = useRef<HTMLDivElement>(null);
  const triggerRef = useRef<HTMLButtonElement>(null);
  const panel = useRef<HTMLDivElement>(null);
  const timer = useRef(0);
  const focusFirst = useRef(false);
  const name = trigger ?? t('menu');

  // The panel outlives `open` for its exit: open → closing → gone.
  const [phase, setPhase] = useState<'open' | 'closing' | 'closed'>((openProp ?? defaultOpen) ? 'open' : 'closed');
  useEffect(() => {
    if (open) {
      setPhase('open');
      return;
    }
    setPhase((was) => (was === 'closed' ? 'closed' : 'closing'));
    const id = window.setTimeout(() => setPhase('closed'), 170);
    return () => window.clearTimeout(id);
  }, [open]);

  useEffect(() => () => window.clearTimeout(timer.current), []);

  // Hover opens at once; leaving waits out the grace so the pointer can cross
  // the gap — unless the keyboard is already working inside the panel.
  const openNow = useEvent(() => {
    window.clearTimeout(timer.current);
    setOpen(true);
  });
  const closeSoon = useEvent(() => {
    const active = typeof document === 'undefined' ? null : document.activeElement;
    if (active && root.current?.contains(active)) return;
    window.clearTimeout(timer.current);
    timer.current = window.setTimeout(() => setOpen(false), grace);
  });

  // Escape snaps it shut, from anywhere, and hands focus back to the trigger.
  useEffect(() => {
    if (!open) return;
    const onKey = (event: KeyboardEvent) => {
      if (event.key === 'Escape') {
        setOpen(false);
        triggerRef.current?.focus();
      }
    };
    window.addEventListener('keydown', onKey);
    return () => window.removeEventListener('keydown', onKey);
  }, [open, setOpen]);

  // ArrowDown walks the keyboard in: open, then land on the panel's first control.
  useEffect(() => {
    if (phase !== 'open' || !focusFirst.current) return;
    focusFirst.current = false;
    panel.current?.querySelector<HTMLElement>('a, button')?.focus();
  }, [phase]);

  return (
    <div ref={ref} className={cx('nx-mega', className)} onMouseEnter={openNow} onMouseLeave={closeSoon} {...rest}>
      <header className="nx-mega-bar">
        {brand && <BarBrand brand={brand} base="nx-mega-brand" mark="nx-mega-mark" />}
        <nav aria-label={label}>
          <button
            ref={triggerRef}
            type="button"
            className="nx-mega-trigger"
            aria-expanded={open}
            aria-haspopup="true"
            onClick={() => setOpen(!open)}
            onKeyDown={(event) => {
              if (event.key === 'ArrowDown') {
                event.preventDefault();
                focusFirst.current = true;
                setOpen(true);
              }
            }}
          >
            {name}
            <Icon name="chevron-down" />
          </button>
          {links?.map((link) => (
            <BarLink key={link.label} className="nx-mega-link" {...link} />
          ))}
        </nav>
        {actions && <span className="nx-mega-end">{actions}</span>}
      </header>

      {phase !== 'closed' && (
        <div ref={panel} className="nx-mega-panel" data-state={phase} role="region" aria-label={panelLabel ?? name}>
          <div className="nx-mega-cols">
            {sections.map((section) => (
              <section key={section.title} className="nx-mega-group">
                <h3 className="nx-mega-heading">{section.title}</h3>
                {section.items.map((item) => (
                  <SmartLink key={item.label} href={item.href ?? '#'} className="nx-mega-item" onClick={() => setOpen(false)}>
                    {item.icon && (
                      <span className="nx-mega-item-icon" aria-hidden="true">
                        <Icon name={item.icon} />
                      </span>
                    )}
                    <b>{item.label}</b>
                    {item.description && <span>{item.description}</span>}
                  </SmartLink>
                ))}
              </section>
            ))}
          </div>
          {promo && (
            <aside className="nx-mega-promo">
              <b className="nx-mega-promo-title">
                {promo.icon && <Icon name={promo.icon} />}
                {promo.title}
              </b>
              <p className="nx-mega-promo-body">{promo.body}</p>
              <SmartLink href={promo.href ?? '#'} className="nx-mega-promo-action" onClick={() => setOpen(false)}>
                {promo.actionLabel}
              </SmartLink>
            </aside>
          )}
        </div>
      )}
    </div>
  );
});
