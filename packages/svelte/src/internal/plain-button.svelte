<script lang="ts">
  /**
   * The private button the card family's own controls render — the exact DOM
   * subset of the button family that FlipCard's corner toggle and SwipeStack's
   * verdict buttons use (an icon-only pill with the status layer). Kept here so
   * the card seat ships green before the button seat's export shape exists;
   * same classes, same markup, just pinned to what these controls pass.
   */
  import type { IconName } from '@nabuxai/ui-core';
  import Icon from './icon.svelte';

  interface Props {
    icon: IconName;
    variant?: 'primary' | 'secondary';
    size?: 'sm' | 'lg' | 'md' | 'xs' | 'xl';
    shape?: 'pill' | 'rounded' | 'square' | 'circle';
    pressed?: boolean;
    label: string;
    style?: string;
    onclick?: (event: MouseEvent) => void;
  }

  let { icon, variant = 'secondary', size = 'md', shape, pressed, label, style, onclick }: Props = $props();
</script>

<button
  type="button"
  class="nx-button"
  data-variant={variant}
  data-size={size !== 'md' ? size : undefined}
  data-shape={shape && shape !== 'rounded' ? shape : undefined}
  data-icon-only=""
  aria-pressed={pressed}
  aria-label={label}
  {style}
  {onclick}
>
  <span class="nx-button-label">
    <Icon name={icon} />
  </span>
  <span class="nx-button-status" aria-hidden="true">
    <span class="nx-spinner" data-size="sm"></span>
    <svg class="nx-icon nx-status-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
      <path d="M5 12.5l4.5 4.5L19 7.5" pathLength="1" />
    </svg>
    <svg class="nx-icon nx-status-cross" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
      <path d="M7 7l10 10M17 7L7 17" pathLength="1" />
    </svg>
  </span>
</button>
