<script lang="ts">
  /**
   * An anchor with NabuXUI's link contract — the Svelte counterpart of
   * ui-react's SmartLink. This flavour has no provider seat to register a
   * router link component through, so every href renders the plain `<a>`
   * SmartLink falls back to when nothing is configured.
   */
  import type { HTMLAnchorAttributes } from 'svelte/elements';

  interface Props extends HTMLAnchorAttributes {
    href: string;
  }

  let { href, children, ...rest }: Props = $props();
</script>

<a {href} {...rest}>{@render children?.()}</a>
