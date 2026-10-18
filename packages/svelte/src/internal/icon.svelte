<script lang="ts">
  /**
   * One of NabuXUI's built-in icons — the Svelte counterpart of the same
   * 24×24 one-path svg ui-react renders from the core's icon table.
   */
  import { icons, type IconDefinition, type IconName } from '@nabuxai/ui-core';

  interface Props {
    name: IconName;
    /** Give the icon a name when it carries meaning on its own; otherwise it is hidden from assistive tech. */
    label?: string;
    class?: string;
  }

  let { name, label, class: className }: Props = $props();

  const icon: IconDefinition = $derived(icons[name]);
</script>

<svg
  class={className ? `nx-icon ${className}` : 'nx-icon'}
  viewBox="0 0 24 24"
  fill={icon.filled ? 'currentColor' : 'none'}
  stroke={icon.filled ? 'none' : 'currentColor'}
  stroke-linecap={icon.filled ? undefined : 'round'}
  stroke-linejoin={icon.filled ? undefined : 'round'}
  role={label ? 'img' : undefined}
  aria-label={label}
  aria-hidden={label ? undefined : true}
  data-directional={icon.directional ? '' : undefined}
>
  <path d={icon.d} />
</svg>
