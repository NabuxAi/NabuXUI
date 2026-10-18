<script module lang="ts">
  import type { HTMLAttributes } from 'svelte/elements';

  export interface AvatarProps extends Omit<HTMLAttributes<HTMLSpanElement>, 'children' | 'size'> {
    name: string;
    src?: string;
    size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl';
    status?: 'online' | 'busy' | 'away' | 'offline';
    shape?: 'circle' | 'square';
  }

  export function initials(name: string): string {
    return name
      .split(/\s+/)
      .filter(Boolean)
      .slice(0, 2)
      .map((part) => Array.from(part)[0])
      .join('')
      .toLocaleUpperCase();
  }
</script>

<script lang="ts">
  /**
   * Avatar — the Svelte counterpart of ui-react's display family's Avatar:
   * same DOM, same nx-* classes, same props.
   */
  import { cx } from '../../internal/cx';

  let { name, src, size, status, shape, class: className, ...rest }: AvatarProps = $props();

  let failed = $state(false);
</script>

<span
  class={cx('nx-avatar', className)}
  data-size={size === 'md' ? undefined : size}
  data-status={status}
  data-shape={shape === 'square' ? 'square' : undefined}
  role="img"
  aria-label={status ? `${name} (${status})` : name}
  {...rest}
>
  {#if src && !failed}
    <img class="nx-avatar-image" {src} alt="" onerror={() => (failed = true)} />
  {:else}
    <span aria-hidden="true">{initials(name)}</span>
  {/if}
</span>
