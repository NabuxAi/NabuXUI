/**
 * File manager (React) — a folder listing you can browse, search, re-view and
 * upload into.
 *
 * Thin over css/blocks/file-manager.css. The toolbar carries the breadcrumb,
 * a search field, the stock grid/list segmented control and an upload picker;
 * folders come first in every listing, names sorted by the reader's collation,
 * counts roll as digits (numberParts/localeDigits via NumberTicker). Opening a
 * file slides a non-modal details drawer in from the inline-end edge (Escape
 * and the scrim close it); opening a folder navigates. Sizes and dates are
 * formatted for the reader's locale — Persian readers get Persian digits and
 * the Jalali calendar from Intl. State is the entries you pass: navigation and
 * view are controlled (`current`/`view` + `onCurrentChange`/`onViewChange`) or
 * uncontrolled (`defaultCurrent`/`defaultView`), with `onOpen` / `onUpload`
 * detail callbacks.
 */
import {
  type CSSProperties,
  type HTMLAttributes,
  type KeyboardEvent as ReactKeyboardEvent,
  type ReactNode,
  useEffect,
  useId,
  useMemo,
  useRef,
  useState,
} from 'react';
import type { IconName } from '@nabuxai/ui-core';
import { reveal } from '@nabuxai/ui-core';
import { cx, useBehavior, useControllable } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { SmartLink, useLocale, useT } from '../internal/provider';
import { SegmentedControl } from '../components/navigation';
import { NumberTicker } from '../components/text';

/** The Intl locale: the prop, or the provider's language. */
const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;

const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

/** Fold case, accents and the Persian/Arabic letter variants so a search matches either spelling. */
const fold = (text: string) =>
  text
    .toLocaleLowerCase()
    .normalize('NFKD')
    .replace(/[\u0300-\u036f]/g, '')
    .replace(/[يى]/g, 'ی')
    .replace(/ك/g, 'ک')
    .replace(/[\u064b-\u0670\u065f\u200c]/g, '');

/* ---- Types ---------------------------------------------------------------------------- */

export type FileManagerKind = 'folder' | 'image' | 'audio' | 'video' | 'file';
export type FileManagerView = 'grid' | 'list';

export interface FileManagerDetail {
  label: string;
  value: string;
}

export interface FileManagerEntry {
  id: string;
  name: string;
  /** Omitted (or null) for the root folder's entries. */
  parent?: string | null;
  kind?: FileManagerKind;
  /** Bytes; folders usually carry `items` instead. */
  size?: number;
  /** Folders: how many entries live inside. */
  items?: number;
  /** When it last changed: a Date, timestamp or ISO string — or an already formatted label. */
  modified?: string | number | Date;
  /** Where the drawer's Open button goes. */
  href?: string;
  /** A thumbnail for the drawer (images). */
  preview?: string;
  /** Extra rows for the drawer. */
  details?: FileManagerDetail[];
  /** Overrides the meta line under the name. */
  meta?: string;
}

export interface FileManagerProps extends Omit<HTMLAttributes<HTMLElement>, 'children' | 'defaultValue'> {
  /** Every entry of the tree, flat — each knows the folder it lives in (`parent`). */
  items: FileManagerEntry[];
  current?: string | null;
  defaultCurrent?: string | null;
  onCurrentChange?: (folder: string | null) => void;
  view?: FileManagerView;
  defaultView?: FileManagerView;
  onViewChange?: (view: FileManagerView) => void;
  /** Fired when a file card opens its details drawer. */
  onOpen?: (entry: FileManagerEntry) => void;
  /** Fired with the files chosen in the toolbar's upload picker. */
  onUpload?: (files: File[]) => void;
  searchPlaceholder?: string;
  emptyText?: string;
  /** The manager's accessible name (also the root crumb). */
  label?: string;
  /** The listing's height; it scrolls inside ("34rem"). */
  height?: string;
  locale?: string;
}

const KINDS: FileManagerKind[] = ['folder', 'image', 'audio', 'video', 'file'];

const KIND_ICON: Record<FileManagerKind, IconName> = { folder: 'folder', image: 'image', audio: 'music', video: 'play', file: 'file' };

const KIND_KEY: Record<FileManagerKind, 'fmKindFolder' | 'fmKindImage' | 'fmKindAudio' | 'fmKindVideo' | 'fmKindFile'> = {
  folder: 'fmKindFolder',
  image: 'fmKindImage',
  audio: 'fmKindAudio',
  video: 'fmKindVideo',
  file: 'fmKindFile',
};

/** The kind an entry is: the one given, or the one its extension says. */
function kindOf(entry: FileManagerEntry): FileManagerKind {
  if (entry.kind && KINDS.includes(entry.kind)) return entry.kind;
  if (/\.(jpe?g|png|gif|webp|svg|avif|heic)$/i.test(entry.name)) return 'image';
  if (/\.(mp3|wav|ogg|m4a|flac|aac)$/i.test(entry.name)) return 'audio';
  if (/\.(mp4|mov|webm|avi|mkv)$/i.test(entry.name)) return 'video';
  return 'file';
}

const UNITS = ['B', 'KB', 'MB', 'GB', 'TB'];

/** "۲٫۴ MB" — locale digits, a thin space, a unit symbol either language reads. */
function formatBytes(bytes: number, locale: string): string {
  let value = bytes;
  let unit = 0;
  while (value >= 1024 && unit < UNITS.length - 1) {
    value /= 1024;
    unit += 1;
  }
  const number = new Intl.NumberFormat(locale, { maximumFractionDigits: unit === 0 || value >= 100 ? 0 : 1 }).format(value);
  return `${number}\u2009${UNITS[unit]}`;
}

/** A date in the reader's calendar (fa gets Jalali), or the raw label when it does not parse. */
function formatWhen(modified: string | number | Date | undefined, locale: string): string | null {
  if (modified === undefined || modified === null || modified === '') return null;
  const date = modified instanceof Date ? modified : new Date(modified);
  if (Number.isNaN(date.getTime())) return typeof modified === 'string' ? modified : null;
  return new Intl.DateTimeFormat(locale, { dateStyle: 'medium' }).format(date);
}

export function FileManager({
  items,
  current: controlledCurrent,
  defaultCurrent = null,
  onCurrentChange,
  view: controlledView,
  defaultView = 'grid',
  onViewChange,
  onOpen,
  onUpload,
  searchPlaceholder,
  emptyText,
  label,
  height,
  locale,
  className,
  style,
  ...rest
}: FileManagerProps) {
  const t = useT();
  const language = useLocale();
  const intl = locale ?? INTL[language];
  const base = `nx-fm${useId().replace(/:/g, '')}`;
  const root = useRef<HTMLElement>(null);
  const search = useRef<HTMLInputElement>(null);
  const picker = useRef<HTMLInputElement>(null);
  const [folder, setFolder] = useControllable<string | null>(controlledCurrent, defaultCurrent, onCurrentChange);
  const [view, setView] = useControllable<FileManagerView>(controlledView, defaultView, onViewChange);
  const [query, setQuery] = useState('');
  const [selected, setSelected] = useState<string | null>(null);
  const [announced, setAnnounced] = useState('');
  useBehavior(root, reveal, { once: true });

  const number = useMemo(() => new Intl.NumberFormat(intl), [intl]);
  const collator = useMemo(() => new Intl.Collator(intl, { numeric: true, sensitivity: 'base' }), [intl]);
  const byId = useMemo(() => new Map(items.map((entry) => [entry.id, entry])), [items]);

  /** The folders above the current one, outermost first. */
  const trail = useMemo(() => {
    const chain: FileManagerEntry[] = [];
    let at = folder;
    const seen = new Set<string>();
    while (at && !seen.has(at)) {
      seen.add(at);
      const entry = byId.get(at);
      if (!entry) break;
      chain.unshift(entry);
      at = entry.parent ?? null;
    }
    return chain;
  }, [folder, byId]);

  const visible = useMemo(() => {
    const q = fold(query.trim());
    return items
      .filter((entry) => (entry.parent ?? null) === folder && (!q || fold(entry.name).includes(q)))
      .sort((a, b) => {
        const folders = Number(kindOf(b) === 'folder') - Number(kindOf(a) === 'folder');
        return folders !== 0 ? folders : collator.compare(a.name, b.name);
      });
  }, [items, folder, query, collator]);

  // How many entries the query found, spoken once it settles.
  useEffect(() => {
    if (!query.trim()) return;
    setAnnounced(t('fmResults', { count: number.format(visible.length) }));
  }, [query, visible.length, number, t]);

  const openEntry = selected ? byId.get(selected) : undefined;
  const openKind = openEntry ? kindOf(openEntry) : 'file';

  const metaOf = (entry: FileManagerEntry): string => {
    if (entry.meta) return entry.meta;
    const kind = kindOf(entry);
    const parts: string[] = [];
    if (kind === 'folder' && entry.items !== undefined) parts.push(t('fmItems', { count: number.format(entry.items) }));
    if (kind !== 'folder' && entry.size !== undefined) parts.push(formatBytes(entry.size, intl));
    const when = formatWhen(entry.modified, intl);
    if (when) parts.push(when);
    return parts.join(' · ');
  };

  const navigate = (next: string | null) => {
    setFolder(next);
    setSelected(null);
    const entry = next ? byId.get(next) : undefined;
    setAnnounced(entry ? t('fmEntered', { name: entry.name }) : '');
  };

  const activate = (entry: FileManagerEntry) => {
    if (kindOf(entry) === 'folder') {
      navigate(entry.id);
      return;
    }
    // A file card toggles its details drawer.
    if (selected === entry.id) {
      setSelected(null);
      setAnnounced('');
      return;
    }
    setSelected(entry.id);
    setAnnounced(t('fmDetailsFor', { name: entry.name }));
    onOpen?.(entry);
  };

  const close = () => {
    setSelected(null);
    setAnnounced('');
  };

  const onKey = (event: ReactKeyboardEvent<HTMLElement>) => {
    if (event.key === 'Escape' && selected) close();
  };

  const picked = (event: React.ChangeEvent<HTMLInputElement>) => {
    const files = Array.from(event.target.files ?? []);
    if (files.length > 0) onUpload?.(files);
    event.target.value = '';
  };

  const here = folder ? byId.get(folder) : undefined;
  const crumbId = (id: string | null) => `${base}-crumb-${id ?? 'root'}`;

  const drawerRows = useMemo(() => {
    if (!openEntry) return [];
    const rows: Array<[string, string]> = [[t('fmType'), t(KIND_KEY[openKind])]];
    if (openEntry.size !== undefined && openKind !== 'folder') rows.push([t('fmSize'), formatBytes(openEntry.size, intl)]);
    const when = formatWhen(openEntry.modified, intl);
    if (when) rows.push([t('fmModified'), when]);
    if (openKind === 'folder' && openEntry.items !== undefined) rows.push([t('fmContains'), t('fmItems', { count: number.format(openEntry.items) })]);
    for (const detail of openEntry.details ?? []) rows.push([detail.label, detail.value]);
    return rows;
  }, [openEntry, openKind, intl, number, t]);

  return (
    <section
      ref={root}
      className={cx('nx-file-manager', className)}
      aria-label={label ?? t('fileManager')}
      style={{ ...(height ? vars({ '--nx-fm-height': height }) : null), ...style }}
      onKeyDown={onKey}
      {...rest}
    >
      <header className="nx-fm-toolbar">
        <nav className="nx-fm-crumbs" aria-label={t('breadcrumb')}>
          <button type="button" className="nx-fm-crumb" data-folder="" id={crumbId(null)} aria-current={folder ? undefined : 'page'} onClick={() => navigate(null)}>
            <Icon name="home" />
            <span>{label ?? t('fileManager')}</span>
          </button>
          {trail.map((entry) => (
            <button
              key={entry.id}
              type="button"
              className="nx-fm-crumb"
              data-folder={entry.id}
              id={crumbId(entry.id)}
              aria-current={entry.id === folder ? 'page' : undefined}
              onClick={() => navigate(entry.id)}
            >
              <span>{entry.name}</span>
            </button>
          ))}
        </nav>
        <span className="nx-fm-countwrap">
          <span className="nx-fm-count" aria-hidden="true">
            <NumberTicker value={visible.length} reveal={false} locale={locale} />
          </span>
          <span className="nx-visually-hidden">{t('fmItems', { count: number.format(visible.length) })}</span>
        </span>
        <div className="nx-fm-tools">
          <div className="nx-fm-search" data-query={query ? '' : undefined}>
            <Icon name="search" />
            <input
              ref={search}
              className="nx-fm-search-input"
              type="search"
              aria-label={t('search')}
              placeholder={searchPlaceholder ?? t('fmSearchPlaceholder')}
              value={query}
              onChange={(event) => setQuery(event.target.value)}
            />
            <button
              type="button"
              className="nx-fm-clear"
              aria-label={t('fmClearSearch')}
              hidden={!query}
              onClick={() => {
                setQuery('');
                search.current?.focus();
              }}
            >
              <Icon name="x" />
            </button>
          </div>
          <SegmentedControl
            className="nx-fm-view"
            size="sm"
            aria-label={t('fmView')}
            value={view}
            onValueChange={(value) => setView(value === 'list' ? 'list' : 'grid')}
            options={[
              { value: 'grid', label: t('fmGridView'), icon: 'grid' },
              { value: 'list', label: t('fmListView'), icon: 'menu' },
            ]}
          />
          <button type="button" className="nx-fm-upload" onClick={() => picker.current?.click()}>
            <Icon name="upload" />
            <span>{t('fmUpload')}</span>
          </button>
          <input ref={picker} type="file" multiple className="nx-visually-hidden" tabIndex={-1} aria-hidden="true" onChange={picked} />
        </div>
      </header>

      <div className="nx-fm-stage">
        <div className="nx-fm-scroll">
          <ul className="nx-fm-items" data-view={view} aria-labelledby={crumbId(folder)}>
            {visible.map((entry, i) => {
              const kind = kindOf(entry);
              return (
                <li key={entry.id} className="nx-fm-item" data-kind={kind} style={vars({ '--nx-i': i })}>
                  <button
                    type="button"
                    className="nx-fm-card"
                    data-active={selected === entry.id ? '' : undefined}
                    aria-pressed={kind !== 'folder' ? selected === entry.id : undefined}
                    onClick={() => activate(entry)}
                  >
                    <span className="nx-fm-icon" data-kind={kind} aria-hidden="true">
                      <Icon name={KIND_ICON[kind]} />
                    </span>
                    <span className="nx-fm-name">{entry.name}</span>
                    {metaOf(entry) && <span className="nx-fm-meta">{metaOf(entry)}</span>}
                  </button>
                </li>
              );
            })}
          </ul>
          {visible.length === 0 && (
            <div className="nx-fm-empty" data-variant={query.trim() ? 'results' : 'folder'}>
              {query.trim() ? t('noResults') : (emptyText ?? t('fmEmpty'))}
            </div>
          )}
        </div>
        <div className="nx-fm-scrim" aria-hidden="true" data-open={openEntry ? '' : undefined} onClick={close} />
        <aside className="nx-fm-drawer" data-open={openEntry ? '' : undefined} role="region" aria-label={t('fmDetails')}>
          <header className="nx-fm-drawer-head">
            <span className="nx-fm-icon nx-fm-drawer-icon" data-kind={openKind} aria-hidden="true">
              <Icon name={KIND_ICON[openKind]} />
            </span>
            <div className="nx-fm-drawer-id">
              <h3 className="nx-fm-drawer-title">{openEntry?.name ?? ''}</h3>
              <p className="nx-fm-drawer-kind">{openEntry ? t(KIND_KEY[openKind]) : ''}</p>
            </div>
            <button type="button" className="nx-fm-drawer-close" aria-label={t('close')} onClick={close}>
              <Icon name="x" />
            </button>
          </header>
          <div className="nx-fm-drawer-body">
            {openEntry?.preview && (
              <p className="nx-fm-drawer-preview">
                <img src={openEntry.preview} alt="" />
              </p>
            )}
            <dl className="nx-fm-drawer-rows">
              {drawerRows.map(([label, value], i) => (
                <div key={i}>
                  <dt>{label}</dt>
                  <dd>{value}</dd>
                </div>
              ))}
            </dl>
            {openEntry?.href && (
              <SmartLink className="nx-fm-drawer-open" href={openEntry.href}>
                <Icon name="external-link" />
                <span>{t('fmOpen')}</span>
              </SmartLink>
            )}
          </div>
        </aside>
      </div>
      {/* The navigation and the search count, spoken once they land. */}
      <p className="nx-visually-hidden" role="status">
        {announced}
      </p>
    </section>
  );
}
