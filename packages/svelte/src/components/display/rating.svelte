<script module lang="ts">
  export interface RatingProps {
    name?: string;
    value?: number;
    defaultValue?: number;
    onValueChange?: (value: number) => void;
    max?: number;
    label?: string;
    disabled?: boolean;
    size?: string;
    class?: string;
  }
</script>

<script lang="ts">
  /**
   * Rating — the Svelte counterpart of ui-react's display family's Rating:
   * native radios, painted and previewed by CSS. Controlled while `value` is
   * passed, internal otherwise — the same value/defaultValue convention.
   */
  import { cx } from '../../internal/cx';
  import { nxLang } from '../../internal/locale';
  import { nxTranslate } from '../../i18n';
  import Icon from '../../internal/icon.svelte';

  let { name, value, defaultValue = 0, onValueChange, max = 5, label, disabled, size, class: className }: RatingProps = $props();

  const lang = nxLang();
  const id = $props.id();
  const group = name ?? `nx-rating${id}`;

  let internal = $state(defaultValue);
  const current = $derived(value !== undefined ? value : internal);

  function set(next: number) {
    if (value === undefined) internal = next;
    onValueChange?.(next);
  }
</script>

<fieldset class={cx('nx-rating', className)} disabled={disabled || undefined} style:--nx-rating-size={size || undefined}>
  <legend class="nx-visually-hidden">{label ?? nxTranslate(lang, 'rating')}</legend>
  {#each Array.from({ length: max }, (_, i) => i + 1) as star (star)}
    <label class="nx-rating-star">
      <input
        class="nx-rating-input"
        type="radio"
        name={group}
        value={star}
        checked={current === star}
        onchange={() => set(star)}
        aria-label={nxTranslate(lang, 'stars', { count: star, total: max })}
      />
      <Icon name="star" />
    </label>
  {/each}
</fieldset>
