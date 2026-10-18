<script lang="ts">
  /**
   * Chip — همتای Svelte برای Chip در ui-react/components/form.tsx.
   */
  import { cx } from '../../internal/cx';
  import Icon from '../../internal/icon.svelte';
  import { nxLang } from '../../internal/locale';
  import { nxTranslate } from '../../i18n';

  interface Props {
    /** متن چیپ — همتای children رشته‌ای در React. */
    text: string;
    onRemove?: () => void;
    class?: string;
  }

  let { text, onRemove, class: className }: Props = $props();
</script>

<span class={cx('nx-chip', className)}>
  <span>{text}</span>
  {#if onRemove}
    <button
      type="button"
      class="nx-chip-remove"
      aria-label={nxTranslate(nxLang(), 'remove', { name: text })}
      onclick={onRemove}
    >
      <Icon name="x" />
    </button>
  {/if}
</span>
