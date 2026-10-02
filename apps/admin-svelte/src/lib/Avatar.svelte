<script lang="ts">
  /**
   * An avatar — the `.nx-avatar` markup contract: initials over an optional
   * photo, with a presence dot (`data-status`) and the same sizes the React/
   * Blade Avatar paints. Falls back to initials when the image fails.
   */
  let {
    name,
    src = undefined,
    size = undefined,
    status = undefined,
    label = undefined,
  }: { name: string; src?: string; size?: 'xs' | 'sm' | 'lg' | 'xl'; status?: 'online' | 'busy' | 'away' | 'offline'; label?: string } = $props();

  let failed = $state(false);

  // A new photo is a new chance for the <img> to work.
  $effect(() => {
    void src;
    failed = false;
  });

  const initials = $derived(
    name
      .split(/\s+/)
      .filter(Boolean)
      .slice(0, 2)
      .map((part) => Array.from(part)[0])
      .join('')
      .toLocaleUpperCase(),
  );
</script>

<span
  class="nx-avatar"
  data-size={size}
  data-status={status}
  role="img"
  aria-label={label ?? (status ? `${name} (${status})` : name)}
>
  {#if src && !failed}
    <img class="nx-avatar-image" {src} alt="" onerror={() => (failed = true)} />
  {:else}
    <span aria-hidden="true">{initials}</span>
  {/if}
</span>
