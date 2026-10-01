<script lang="ts">
  /**
   * One of NabuXUI's built-in icons, drawn from the same paths the React/Blade
   * components use (`icons` in ui-core). Decorative unless `label` is given.
   */
  import { icons, type IconDefinition, type IconName } from '@nabuxai/ui-core';

  let { name, label, class: className }: { name: IconName; label?: string; class?: string } = $props();

  const def = $derived<IconDefinition>(icons[name]);
</script>

<svg
  class={className ? `nx-icon ${className}` : 'nx-icon'}
  viewBox="0 0 24 24"
  fill={def.filled ? 'currentColor' : 'none'}
  stroke={def.filled ? 'none' : 'currentColor'}
  stroke-linecap="round"
  stroke-linejoin="round"
  data-directional={def.directional ? '' : undefined}
  role={label ? 'img' : undefined}
  aria-label={label}
  aria-hidden={label ? undefined : 'true'}
>
  <path d={def.d} />
</svg>
