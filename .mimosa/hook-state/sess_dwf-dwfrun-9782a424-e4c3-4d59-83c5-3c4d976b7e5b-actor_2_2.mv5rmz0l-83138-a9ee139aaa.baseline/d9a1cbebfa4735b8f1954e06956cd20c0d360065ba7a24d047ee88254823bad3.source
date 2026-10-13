import {
  Children,
  type CSSProperties,
  type KeyboardEvent,
  type ReactElement,
  type ReactNode,
  cloneElement,
  isValidElement,
  useEffect,
  useId,
  useMemo,
  useRef,
  useState,
} from 'react';
import { type IconName, hotkey, indicator, modKeyLabel, place } from '@nabuxai/ui-core';
import { cx, mergeRefs, useControllable, useIsoLayoutEffect } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useT } from '../internal/provider';
import { Kbd } from './display';

/* ---- Dialog / drawer / sheet ---------------------------------------------------- */

export interface DialogProps {
  open?: boolean;
  defaultOpen?: boolean;
  onOpenChange?: (open: boolean) => void;
  /** An element that opens the dialog when clicked. */
  trigger?: ReactElement;
  title?: ReactNode;
  description?: ReactNode;
  footer?: ReactNode;
  size?: 'sm' | 'md' | 'lg' | 'xl' | 'full';
  variant?: 'modal' | 'drawer' | 'sheet';
  /** Drawer edge: inline end (default) or start. */
  side?: 'start' | 'end';
  /** Close when the backdrop is clicked (default true). */
  closeOnBackdrop?: boolean;
  hideClose?: boolean;
  className?: string;
  style?: CSSProperties;
  children?: ReactNode;
}

export function Dialog({
  open,
  defaultOpen = false,
  onOpenChange,
  trigger,
  title,
  description,
  footer,
  size,
  variant = 'modal',
  side,
  closeOnBackdrop = true,
  hideClose,
  className,
  style,
  children,
}: DialogProps) {
  const t = useT();
  const base = `nx-dialog${useId().replace(/:/g, '')}`;
  const [isOpen, setOpen] = useControllable(open, defaultOpen, onOpenChange);
  const ref = useRef<HTMLDialogElement>(null);

  useEffect(() => {
    const el = ref.current;
    if (!el) return;
    if (isOpen && !el.open) el.showModal();
    if (!isOpen && el.open) el.close();
  }, [isOpen]);

  useEffect(() => {
    const el = ref.current;
    if (!el) return;
    // Escape, form[method=dialog] and .close() all end here.
    const onClose = () => setOpen(false);
    el.addEventListener('close', onClose);
    return () => el.removeEventListener('close', onClose);
  }, [setOpen]);

  const onBackdrop = (event: React.MouseEvent<HTMLDialogElement>) => {
    if (!closeOnBackdrop || event.target !== event.currentTarget) return;
    const r = event.currentTarget.getBoundingClientRect();
    const inside = event.clientX >= r.left && event.clientX <= r.right && event.clientY >= r.top && event.clientY <= r.bottom;
    if (!inside) event.currentTarget.close();
  };

  return (
    <>
      {trigger && isValidElement(trigger)
        ? cloneElement(trigger as ReactElement<Record<string, unknown>>, {
            'aria-haspopup': 'dialog',
            onClick: (event: React.MouseEvent) => {
              ((trigger.props as { onClick?: (e: React.MouseEvent) => void }).onClick)?.(event);
              setOpen(true);
            },
          })
        : null}
      <dialog
        ref={ref}
        className={cx('nx-dialog', className)}
        data-size={size && size !== 'md' ? size : undefined}
        data-variant={variant === 'modal' ? undefined : variant}
        data-side={side === 'start' ? 'start' : undefined}
        aria-labelledby={title ? `${base}-title` : undefined}
        aria-describedby={description ? `${base}-description` : undefined}
        style={style}
        onClick={onBackdrop}
      >
        {(title || description) && (
          <header className="nx-dialog-header">
            {title && (
              <h2 className="nx-dialog-title" id={`${base}-title`}>
                {title}
              </h2>
            )}
            {description && (
              <p className="nx-dialog-description" id={`${base}-description`}>
                {description}
              </p>
            )}
          </header>
        )}
        <div className="nx-dialog-body">{children}</div>
        {footer && <footer className="nx-dialog-footer">{footer}</footer>}
        {!hideClose && (
          <button type="button" className="nx-dialog-close" aria-label={t('close')} onClick={() => ref.current?.close()}>
            <Icon name="x" />
          </button>
        )}
      </dialog>
    </>
  );
}

export function Drawer(props: Omit<DialogProps, 'variant'>) {
  return <Dialog variant="drawer" {...props} />;
}

export function Sheet(props: Omit<DialogProps, 'variant' | 'side'>) {
  return <Dialog variant="sheet" {...props} />;
}

/* ---- Popover ----------------------------------------------------------------------- */

const supportsPopover = () => typeof HTMLElement !== 'undefined' && 'popover' in HTMLElement.prototype;

export interface PopoverProps {
  /** A <button> that toggles the popover. */
  trigger: ReactElement;
  children: ReactNode;
  side?: 'top' | 'bottom' | 'start' | 'end';
  align?: 'start' | 'center' | 'end';
  /** Name for the panel, read when focus enters it. */
  label?: string;
  className?: string;
}

export function Popover({ trigger, children, side = 'bottom', align = 'center', label, className }: PopoverProps) {
  const id = `nx-pop${useId().replace(/:/g, '')}`;
  const panel = useRef<HTMLDivElement>(null);
  const button = useRef<HTMLElement | null>(null);
  const [open, setOpen] = useState(false);

  useEffect(() => {
    if (supportsPopover()) button.current?.setAttribute('popovertarget', id);
    const el = panel.current;
    if (!el) return;
    const onToggle = (event: Event) => setOpen((event as ToggleEvent).newState === 'open');
    el.addEventListener('toggle', onToggle);
    return () => el.removeEventListener('toggle', onToggle);
  }, [id]);

  useEffect(() => {
    if (!open || !panel.current || !button.current) return;
    const sides = { top: 'top', bottom: 'bottom', start: 'inline-start', end: 'inline-end' } as const;
    return place(button.current, panel.current, { side: sides[side], align, offset: 8 });
  }, [open, side, align]);

  const child = Children.only(trigger);
  if (!isValidElement(child)) return null;
  const childProps = child.props as Record<string, unknown>;

  return (
    <>
      {cloneElement(child as ReactElement<Record<string, unknown>>, {
        ref: mergeRefs((childProps.ref as React.Ref<HTMLElement>) ?? ((child as unknown as { ref?: React.Ref<HTMLElement> }).ref || undefined), button),
        'aria-expanded': open,
        'aria-controls': id,
        onClick: (event: React.MouseEvent) => {
          (childProps.onClick as ((e: React.MouseEvent) => void) | undefined)?.(event);
          if (!supportsPopover()) {
            const el = panel.current;
            el?.toggleAttribute('data-open');
            setOpen(!!el?.hasAttribute('data-open'));
          }
        },
      })}
      <div ref={panel} id={id} className={cx('nx-popover', className)} role="dialog" aria-label={label} {...{ popover: 'auto' }}>
        {children}
      </div>
    </>
  );
}

/* ---- Command palette ---------------------------------------------------------------- */

export interface CommandItem {
  id: string;
  label: string;
  icon?: IconName;
  hint?: string;
  shortcut?: string;
  keywords?: string[];
  href?: string;
  onSelect?: () => void;
  disabled?: boolean;
}

export interface CommandGroup {
  label: string;
  items: CommandItem[];
}

export interface CommandPaletteProps {
  groups: CommandGroup[];
  open?: boolean;
  defaultOpen?: boolean;
  onOpenChange?: (open: boolean) => void;
  /** Keyboard shortcut that toggles the palette; null for none. */
  hotkey?: string | null;
  placeholder?: string;
  emptyText?: ReactNode;
}

/** Fold the variants of a Persian or Arabic letter together so either spelling matches. */
const normalise = (text: string) =>
  text
    .toLocaleLowerCase()
    .replace(/[يى]/g, 'ی')
    .replace(/ك/g, 'ک')
    .replace(/[ً-ٰٟ‌]/g, '')
    .normalize('NFKC');

export function CommandPalette({ groups, open, defaultOpen = false, onOpenChange, hotkey: combo = 'mod+k', placeholder, emptyText }: CommandPaletteProps) {
  const t = useT();
  const base = `nx-cmd${useId().replace(/:/g, '')}`;
  const [isOpen, setOpen] = useControllable(open, defaultOpen, onOpenChange);
  const [query, setQuery] = useState('');
  const [active, setActive] = useState(0);
  const list = useRef<HTMLDivElement>(null);
  const input = useRef<HTMLInputElement>(null);
  const ind = useRef<ReturnType<typeof indicator> | null>(null);

  useEffect(() => (combo ? hotkey(combo, () => setOpen(!isOpen)) : undefined), [combo, isOpen, setOpen]);

  const filtered = useMemo(() => {
    const q = normalise(query.trim());
    return groups
      .map((group) => ({
        ...group,
        items: group.items.filter((item) => !q || normalise([item.label, item.hint, ...(item.keywords ?? [])].join(' ')).includes(q)),
      }))
      .filter((group) => group.items.length > 0);
  }, [groups, query]);

  const flat = filtered.flatMap((group) => group.items);

  useEffect(() => {
    if (isOpen) {
      setQuery('');
      setActive(0);
      requestAnimationFrame(() => input.current?.focus());
    }
  }, [isOpen]);

  useEffect(() => setActive(0), [query]);

  useIsoLayoutEffect(() => {
    if (!list.current) return;
    ind.current ??= indicator(list.current);
    const el = list.current.querySelector<HTMLElement>(`#${base}-opt-${active}`);
    ind.current.update(el);
    el?.scrollIntoView({ block: 'nearest' });
  });

  const choose = (item: CommandItem | undefined) => {
    if (!item || item.disabled) return;
    setOpen(false);
    if (item.href) {
      list.current?.querySelector<HTMLAnchorElement>(`[data-command-link="${CSS.escape(item.id)}"]`)?.click();
    }
    item.onSelect?.();
  };

  const onKeyDown = (event: KeyboardEvent<HTMLInputElement>) => {
    if (event.key === 'ArrowDown') {
      event.preventDefault();
      setActive((i) => (flat.length ? (i + 1) % flat.length : 0));
    } else if (event.key === 'ArrowUp') {
      event.preventDefault();
      setActive((i) => (flat.length ? (i - 1 + flat.length) % flat.length : 0));
    } else if (event.key === 'Enter') {
      event.preventDefault();
      choose(flat[active]);
    }
  };

  let index = -1;

  return (
    <Dialog open={isOpen} onOpenChange={setOpen} hideClose className="nx-command">
      <div className="nx-command-search">
        <Icon name="search" />
        <input
          ref={input}
          className="nx-command-input"
          role="combobox"
          aria-expanded="true"
          aria-controls={`${base}-list`}
          aria-activedescendant={flat.length ? `${base}-opt-${active}` : undefined}
          aria-autocomplete="list"
          aria-label={t('search')}
          placeholder={placeholder ?? t('searchPlaceholder')}
          value={query}
          onChange={(event) => setQuery(event.target.value)}
          onKeyDown={onKeyDown}
        />
        <Kbd>Esc</Kbd>
      </div>
      <div ref={list} className="nx-command-list" id={`${base}-list`} role="listbox" aria-label={t('search')}>
        <span className="nx-indicator" aria-hidden="true" />
        {filtered.length === 0 && <p className="nx-command-empty">{emptyText ?? t('noResults')}</p>}
        {filtered.map((group, g) => (
          <div key={group.label} className="nx-command-group" role="group" aria-labelledby={`${base}-group-${g}`}>
            <div className="nx-command-group-label" id={`${base}-group-${g}`}>
              {group.label}
            </div>
            {group.items.map((item) => {
              index += 1;
              const i = index;
              return (
                <div
                  key={item.id}
                  id={`${base}-opt-${i}`}
                  className="nx-command-item"
                  role="option"
                  aria-selected={i === active}
                  aria-disabled={item.disabled || undefined}
                  style={{ '--nx-i': i } as CSSProperties}
                  onPointerMove={() => i !== active && setActive(i)}
                  onClick={() => choose(item)}
                >
                  {item.icon && <Icon name={item.icon} />}
                  <span>{item.label}</span>
                  {item.hint && <span className="nx-command-item-hint">{item.hint}</span>}
                  {item.shortcut && <Kbd>{item.shortcut}</Kbd>}
                  {item.href && <SmartLink href={item.href} data-command-link={item.id} tabIndex={-1} hidden aria-hidden="true" />}
                </div>
              );
            })}
          </div>
        ))}
      </div>
      <footer className="nx-command-footer">
        <span>
          <Kbd>↑</Kbd>
          <Kbd>↓</Kbd> {t('navigate')}
        </span>
        <span>
          <Kbd>↵</Kbd> {t('select')}
        </span>
        <span>
          <Kbd>Esc</Kbd> {t('toClose')}
        </span>
        {combo && (
          <span style={{ marginInlineStart: 'auto' }}>
            <Kbd>{combo.replace('mod', modKeyLabel()).replace('+', ' ').toUpperCase()}</Kbd>
          </span>
        )}
      </footer>
    </Dialog>
  );
}
