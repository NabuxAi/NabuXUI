<script module lang="ts">
  import type { AvatarProps } from './avatar.svelte';

  export interface AvatarGroupProps {
    people: Array<{ name: string; src?: string }>;
    /** Show this many; the rest become "+N". */
    max?: number;
    size?: AvatarProps['size'];
    label?: string;
    class?: string;
  }
</script>

<script lang="ts">
  /**
   * AvatarGroup — the Svelte counterpart of ui-react's display family's
   * AvatarGroup: each avatar wrapped in a Tooltip, the overflow as "+N".
   */
  import { cx } from '../../internal/cx';
  import { nxLang } from '../../internal/locale';
  import { nxTranslate } from '../../i18n';
  import Avatar from './avatar.svelte';
  import Tooltip from '../feedback/tooltip.svelte';

  let { people, max = 5, size, label, class: className }: AvatarGroupProps = $props();

  const lang = nxLang();
  const shown = $derived(people.slice(0, max));
  const rest = $derived(people.length - shown.length);
</script>

<div class={cx('nx-avatar-group', className)} role="group" aria-label={label}>
  {#each shown as person (person.name)}
    <Tooltip content={person.name}>
      {#snippet children(anchor)}
        <Avatar name={person.name} src={person.src} {size} tabindex={0} {...anchor} />
      {/snippet}
    </Tooltip>
  {/each}
  {#if rest > 0}
    <span class="nx-avatar nx-avatar-more" data-size={size === 'md' ? undefined : size} role="img" aria-label={nxTranslate(lang, 'more', { count: rest })}>
      <span aria-hidden="true">+{rest}</span>
    </span>
  {/if}
</div>
