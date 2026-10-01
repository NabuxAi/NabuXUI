/**
 * TreeView — a collapsible tree with `role="tree"`, full keyboard support
 * and a roving tab stop (React).
 *
 * Thin over css/blocks/tree-view.css. One tab stop per tree: ↑/↓ walk the
 * visible items, →/← expand and collapse (swapped in RTL), Home/End jump to
 * the ends, Enter selects, Space folds a parent. Selection is `value` /
 * `defaultValue` / `onValueChange` over node ids; the open parents are
 * `expanded` / `defaultExpanded` / `onExpandedChange`. Groups fold with the
 * grid-rows 0fr→1fr trick and their children rise in with a short stagger;
 * child counts roll as digits (numberParts/localeDigits).
 */
import {
  type CSSProperties,
  type HTMLAttributes,
  type KeyboardEvent as ReactKeyboardEvent,
  type MouseEvent as ReactMouseEvent,
  type ReactNode,
  useId,
  useMemo,
  useRef,
  useState,
} from 'react';
import type { IconName } from '@nabuxai/ui-core';
import { useControllable, useEvent } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { useLocale, useT } from '../internal/provider';
import { NumberTicker } from '../components/text';

/** The Intl locale: the prop, or the provider's language. */
const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;

/* ---- Types ---------------------------------------------------------------------------- */

export type TreeViewTone = 'accent' | 'success' | 'warning' | 'danger' | 'info' | 'gold';

export interface TreeNode {
  id: string;
  label: string;
  /** A small line after the label — free text ("12 files", "Updated 2h ago"). */
  meta?: string;
  icon?: IconName;
  tone?: TreeViewTone;
  children?: TreeNode[];
}

/** What changed in `onExpandedChange`. */
export interface TreeViewExpand {
  node: TreeNode;
  open: boolean;
}

export interface TreeViewProps extends Omit<HTMLAttributes<HTMLDivElement>, 'children' | 'value' | 'defaultValue' | 'onChange'> {
  nodes?: TreeNode[];
  /** The ids of the open parents (controlled); closed parents hide their group. */
  expanded?: string[];
  defaultExpanded?: string[];
  onExpandedChange?: (ids: string[], change: TreeViewExpand | null) => void;
  /** The selected node id: value / defaultValue / onValueChange. */
  value?: string | null;
  defaultValue?: string | null;
  onValueChange?: (id: string | null, node: TreeNode | null) => void;
  /** Rows are selectable at all (default). */
  selectable?: boolean;
  /** The tree's accessible name. */
  label?: string;
  locale?: string;
}

/** One visible node, with its place in the tree for aria-level/setsize/posinset. */
interface FlatEntry {
  node: TreeNode;
  level: number;
  parent: string | null;
  pos: number;
  size: number;
}

interface TreeContext {
  open: Set<string>;
  selected: string | null;
  tabId: string | null;
  base: string;
  selectable: boolean;
  locale?: string;
  format: (n: number) => string;
  say: (key: 'treeChildren', params: Record<string, string | number>) => string;
  onClick: (node: TreeNode, event: ReactMouseEvent) => void;
}

const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

export function TreeView({
  nodes = [],
  expanded,
  defaultExpanded,
  onExpandedChange,
  value,
  defaultValue = null,
  onValueChange,
  selectable = true,
  label,
  locale,
  className,
  style,
  ...rest
}: TreeViewProps) {
  const t = useT();
  const language = useLocale();
  const number = useMemo(() => new Intl.NumberFormat(locale ?? INTL[language]), [locale, language]);
  const base = `nx-tree-view${useId().replace(/:/g, '')}`;
  const root = useRef<HTMLUListElement>(null);
  const [active, setActive] = useState<string | null>(null);
  const [announced, setAnnounced] = useState('');

  /* The detail of the change in flight, so onExpandedChange can carry it. */
  const change = useRef<TreeViewExpand | null>(null);
  const byId = useMemo(() => {
    const map = new Map<string, TreeNode>();
    const walk = (list: TreeNode[]) => {
      for (const node of list) {
        map.set(node.id, node);
        if (node.children) walk(node.children);
      }
    };
    walk(nodes);
    return map;
  }, [nodes]);

  const [openIds, setOpenIds] = useControllable<string[]>(expanded, defaultExpanded ?? [], (ids) => {
    onExpandedChange?.(ids, change.current);
  });
  const [selected, setSelected] = useControllable<string | null>(value, defaultValue, (id) => {
    onValueChange?.(id, byId.get(id ?? '') ?? null);
  });

  const open = useMemo(() => new Set(openIds), [openIds]);

  /** The visible items in focus order — closed groups are skipped. */
  const flat = useMemo(() => {
    const out: FlatEntry[] = [];
    const walk = (list: TreeNode[], level: number, parent: string | null) => {
      list.forEach((node, i) => {
        out.push({ node, level, parent, pos: i + 1, size: list.length });
        if (node.children?.length && open.has(node.id)) walk(node.children, level + 1, node.id);
      });
    };
    walk(nodes, 1, null);
    return out;
  }, [nodes, open]);

  /** One tab stop per tree: the focused row, else the selection, else the first. */
  const tabId = active ?? selected ?? flat[0]?.node.id ?? null;

  const toggle = useEvent((node: TreeNode, open: boolean) => {
    change.current = { node, open };
    setOpenIds(open ? openIds.concat(node.id) : openIds.filter((id) => id !== node.id));
  });

  const select = useEvent((node: TreeNode) => {
    if (!selectable) return;
    setSelected(node.id);
    setAnnounced(t('treeSelected', { name: node.label }));
  });

  /* ---- Pointer: the twist zone folds, the rest of the row selects ------------------------ */

  const onRowClick = useEvent((node: TreeNode, event: ReactMouseEvent) => {
    setActive(node.id);
    const hasChildren = (node.children?.length ?? 0) > 0;
    if (hasChildren && (event.target as HTMLElement).closest('.nx-tree-view-twist')) {
      toggle(node, !open.has(node.id));
      return;
    }
    select(node);
  });

  /* ---- Keyboard: the ARIA treeview pattern ------------------------------------------------ */

  const onKeyDown = (event: ReactKeyboardEvent<HTMLUListElement>) => {
    const tree = root.current;
    const item = (event.target as HTMLElement).closest<HTMLElement>('.nx-tree-view-item');
    if (!tree || !item?.dataset.id) return;
    const index = flat.findIndex((entry) => entry.node.id === item.dataset.id);
    if (index < 0) return;
    const current = flat[index]!;
    const rtl = getComputedStyle(tree).direction === 'rtl';
    const go = (target: FlatEntry | undefined) => {
      if (!target) return;
      event.preventDefault();
      tree.querySelector<HTMLElement>(`.nx-tree-view-item[data-id="${CSS.escape(target.node.id)}"]`)?.focus();
    };
    const hasChildren = (current.node.children?.length ?? 0) > 0;

    switch (event.key) {
      case 'ArrowDown':
        return go(flat[index + 1]);
      case 'ArrowUp':
        return go(flat[index - 1]);
      case 'Home':
        return go(flat[0]);
      case 'End':
        return go(flat[flat.length - 1]);
      case 'ArrowRight':
      case 'ArrowLeft': {
        // In RTL the keys swap: "inline" is the way the subtree opens toward.
        const inline = rtl ? event.key === 'ArrowLeft' : event.key === 'ArrowRight';
        event.preventDefault();
        if (!hasChildren) return;
        if (inline) {
          if (!open.has(current.node.id)) toggle(current.node, true); // expand, focus stays
          else go(flat[index + 1]); // open already: in comes the first child
        } else if (open.has(current.node.id)) {
          toggle(current.node, false); // collapse, focus stays
        } else {
          go(flat.find((entry) => entry.node.id === current.parent)); // up to the parent
        }
        return;
      }
      case 'Enter':
        event.preventDefault();
        return select(current.node);
      case ' ':
        event.preventDefault();
        return hasChildren ? toggle(current.node, !open.has(current.node.id)) : select(current.node);
    }
  };

  // Focus follows clicks and Tab alike: the roving tab stop tracks it (below,
  // in onFocusCapture), so keyboard and pointer never disagree.

  const ctx = useMemo<TreeContext>(
    () => ({
      open,
      selected,
      tabId,
      base,
      selectable,
      locale,
      format: (n: number) => number.format(n),
      say: (key, params) => t(key, params),
      onClick: onRowClick,
    }),
    [open, selected, tabId, base, selectable, locale, number, t, onRowClick],
  );

  return (
    <div className={className} style={style} {...rest}>
      <ul
        ref={root}
        className="nx-tree-view"
        role="tree"
        aria-label={label ?? t('treeView')}
        onKeyDown={onKeyDown}
        onFocusCapture={(event) => {
          const item = (event.target as HTMLElement).closest<HTMLElement>('.nx-tree-view-item');
          if (item?.dataset.id) setActive(item.dataset.id);
        }}
      >
        {nodes.length === 0 && (
          <li className="nx-tree-view-empty" role="none">
            {t('treeEmpty')}
          </li>
        )}
        {nodes.map((node, i) => (
          <TreeItem key={node.id} entry={{ node, level: 1, parent: null, pos: i + 1, size: nodes.length }} ctx={ctx} />
        ))}
      </ul>
      <p className="nx-visually-hidden" role="status">
        {announced}
      </p>
    </div>
  );
}

/* ---- One node --------------------------------------------------------------------------- */

function TreeItem({ entry, ctx }: { entry: FlatEntry; ctx: TreeContext }) {
  const { node, level, pos, size } = entry;
  const children = node.children ?? [];
  const hasChildren = children.length > 0;
  const isOpen = ctx.open.has(node.id);
  const labelId = `${ctx.base}-${node.id}-label`;

  const body: ReactNode = (
    <div className="nx-tree-view-row" onClick={(event) => ctx.onClick(node, event)}>
      <span className="nx-tree-view-twist" data-leaf={hasChildren ? undefined : ''} aria-hidden="true">
        {hasChildren && <Icon name="chevron-right" />}
      </span>
      {node.icon && (
        <span className="nx-tree-view-icon">
          <Icon name={node.icon} />
        </span>
      )}
      <span className="nx-tree-view-label" id={labelId}>
        {node.label}
      </span>
      {node.meta && <span className="nx-tree-view-meta">{node.meta}</span>}
      {hasChildren && (
        <>
          <span className="nx-tree-view-count" aria-hidden="true">
            <NumberTicker value={children.length} reveal={false} locale={ctx.locale} />
          </span>
          <span className="nx-visually-hidden">{ctx.say('treeChildren', { count: ctx.format(children.length) })}</span>
        </>
      )}
    </div>
  );

  if (!hasChildren) {
    return (
      <li
        className="nx-tree-view-item"
        role="treeitem"
        data-id={node.id}
        data-tone={node.tone}
        aria-level={level}
        aria-setsize={size}
        aria-posinset={pos}
        aria-selected={ctx.selectable ? ctx.selected === node.id : undefined}
        tabIndex={ctx.tabId === node.id ? 0 : -1}
        style={vars({ '--nx-i': pos - 1 })}
      >
        {body}
      </li>
    );
  }

  return (
    <li
      className="nx-tree-view-item"
      role="treeitem"
      data-id={node.id}
      data-tone={node.tone}
      aria-level={level}
      aria-setsize={size}
      aria-posinset={pos}
      aria-expanded={isOpen}
      aria-selected={ctx.selectable ? ctx.selected === node.id : undefined}
      tabIndex={ctx.tabId === node.id ? 0 : -1}
      style={vars({ '--nx-i': pos - 1 })}
    >
      {body}
      <div className="nx-tree-view-group">
        <ul className="nx-tree-view-group-list" role="group" aria-labelledby={labelId}>
          {children.map((child, i) => (
            <TreeItem
              key={child.id}
              entry={{ node: child, level: level + 1, parent: node.id, pos: i + 1, size: children.length }}
              ctx={ctx}
            />
          ))}
        </ul>
      </div>
    </li>
  );
}
