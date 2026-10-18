<script module lang="ts">
  import type { NavItem } from './nav';

  export interface MegaMenuProps {
    items: NavItem[];
    'aria-label'?: string;
    class?: string;
  }

  type Motion = 'from-start' | 'from-end' | 'to-start' | 'to-end' | undefined;
</script>

<script lang="ts">
  import { type IndicatorController, indicator, nextId } from '@nabuxai/ui-core';
  import { cx } from '../../internal/cx';
  import Icon from '../../internal/icon.svelte';
  import SmartLink from '../../internal/smart-link.svelte';
  import Content from '../../internal/content.svelte';

  let { items, 'aria-label': label, class: className }: MegaMenuProps = $props();

  const base = nextId('nx-mega');
  let nav = $state<HTMLElement>();
  let viewport = $state<HTMLDivElement>();
  let ind: IndicatorController | undefined;
  let closeTimer: ReturnType<typeof setTimeout> | undefined;
  let focusFrame: ReturnType<typeof requestAnimationFrame> | undefined;

  let active = $state<number | null>(null);
  let leaving = $state<{ index: number; motion: Motion } | null>(null);
  let motion = $state<Motion>(undefined);
  let fresh = $state(true);

  const open = (index: number) => {
    clearTimeout(closeTimer);
    if (index === active) return;
    if (active === null) {
      fresh = true;
      motion = undefined;
      leaving = null;
    } else {
      // Slide from the side the pointer came from, in reading order.
      const forward = index > active;
      fresh = false;
      motion = forward ? 'from-end' : 'from-start';
      leaving = { index: active, motion: forward ? 'to-start' : 'to-end' };
    }
    active = index;
  };

  const close = (delay = 0) => {
    clearTimeout(closeTimer);
    closeTimer = setTimeout(() => {
      active = null;
      leaving = null;
    }, delay);
  };

  // Whatever timer or frame is pending when the menu leaves the tree, close it.
  $effect(() => {
    return () => {
      clearTimeout(closeTimer);
      cancelAnimationFrame(focusFrame!);
    };
  });

  $effect(() => {
    ind = indicator(nav!);
    return () => {
      ind?.destroy();
      ind = undefined;
    };
  });

  const home = () => nav?.querySelector('.nx-mega-list [aria-current="page"]') ?? null;

  // Pill follows the open trigger, or goes home to the current page.
  $effect(() => {
    const trigger = active === null ? home() : nav?.querySelector(`#${base}-trigger-${active}`);
    ind?.update(trigger ?? null);
  });

  // Size and place the viewport around the active panel.
  $effect(() => {
    const view = viewport;
    const root = nav;
    if (active === null || !view || !root) return;
    const panel = view.querySelector<HTMLElement>(`#${base}-panel-${active}`);
    const trigger = root.querySelector<HTMLElement>(`#${base}-trigger-${active}`);
    if (!panel || !trigger) return;
    const w = panel.offsetWidth;
    const h = panel.offsetHeight;
    const navRect = root.getBoundingClientRect();
    const t = trigger.getBoundingClientRect();
    const ideal = t.left + t.width / 2 - navRect.left - w / 2;
    const min = -navRect.left + 12;
    const max = document.documentElement.clientWidth - navRect.left - w - 12;
    view.style.setProperty('--nx-mega-w', `${w}px`);
    view.style.setProperty('--nx-mega-h', `${h}px`);
    view.style.setProperty('--nx-mega-x', `${Math.round(Math.min(Math.max(ideal, min), Math.max(min, max)))}px`);
  });

  const onTriggerKey = (event: KeyboardEvent, index: number) => {
    if (event.key === 'ArrowDown' || ((event.key === 'Enter' || event.key === ' ') && active !== index)) {
      event.preventDefault();
      open(index);
      focusFrame = requestAnimationFrame(() => viewport?.querySelector<HTMLElement>(`#${base}-panel-${index} a`)?.focus());
    }
  };

  const onPanelKey = (event: KeyboardEvent) => {
    if (event.key !== 'Escape' || active === null) return;
    const index = active;
    close();
    nav?.querySelector<HTMLElement>(`#${base}-trigger-${index}`)?.focus();
  };
</script>

<!-- svelte-ignore a11y_no_static_element_interactions -- hover navigation; the triggers and links carry the keyboard -->
<nav
  class={cx('nx-mega', className)}
  aria-label={label}
  onpointerleave={() => close(220)}
  onpointerenter={() => clearTimeout(closeTimer)}
  onfocusout={(event) => !event.currentTarget.contains(event.relatedTarget as Node) && close()}
  bind:this={nav}
>
  <ul class="nx-mega-list">
    {#each items as item, i}
      {#if item.children}
        <li>
          <button
            type="button"
            id={`${base}-trigger-${i}`}
            class="nx-mega-trigger"
            aria-expanded={active === i}
            aria-controls={`${base}-panel-${i}`}
            onpointerenter={(event) => event.pointerType === 'mouse' && open(i)}
            onclick={() => (active === i ? close() : open(i))}
            onkeydown={(event) => onTriggerKey(event, i)}
          >
            <Content of={item.label} />
            <Icon name="chevron-down" />
          </button>
        </li>
      {:else}
        <li>
          <SmartLink
            class="nx-mega-link"
            href={item.href ?? '#'}
            aria-current={item.current ? 'page' : undefined}
            data-mega-link={i}
            onpointerenter={() => {
              close(120);
              ind?.update(nav?.querySelector(`[data-mega-link="${i}"]`) ?? null);
            }}
          >
            <Content of={item.label} />
          </SmartLink>
        </li>
      {/if}
    {/each}
  </ul>
  <span class="nx-indicator" aria-hidden="true"></span>
  <div
    class="nx-mega-viewport"
    data-state={active === null ? 'closed' : 'open'}
    data-fresh={fresh ? '' : undefined}
    onkeydown={onPanelKey}
    bind:this={viewport}
  >
    {#each items as item, i}
      {#if item.children}
        <div
          id={`${base}-panel-${i}`}
          class="nx-mega-panel"
          hidden={i !== active && leaving?.index !== i}
          data-motion={i === active ? motion : leaving?.index === i ? leaving.motion : undefined}
          onanimationend={() => leaving?.index === i && (leaving = null)}
        >
          <div style="display: flex; gap: var(--nx-space-4)">
            <ul class="nx-mega-grid" style={`--nx-mega-cols: ${item.columns ?? 2}`}>
              {#each item.children as child, c}
                <li>
                  <SmartLink class="nx-mega-item" href={child.href} onclick={() => close()}>
                    <span class="nx-mega-item-icon" aria-hidden="true">
                      <Icon name={child.icon ?? 'arrow-right'} />
                    </span>
                    <span class="nx-mega-item-title">
                      <Content of={child.label} />
                    </span>
                    {#if child.description}
                      <span class="nx-mega-item-description">
                        <Content of={child.description} />
                      </span>
                    {/if}
                  </SmartLink>
                </li>
              {/each}
            </ul>
            {#if item.aside}
              <Content of={item.aside} />
            {/if}
          </div>
        </div>
      {/if}
    {/each}
  </div>
</nav>
