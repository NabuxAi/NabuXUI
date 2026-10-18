<script module lang="ts">
  /**
   * NxHero — همتای Svelte برای components/hero.tsx در @nabuxai/ui-react.
   * سولت تک‌کامپوننتی است، پس این صندلیِ خانواده است: کامپوننت‌ها را از
   * ./hero/ با همان نام‌هایی که همتای React صادر می‌کند بیرون می‌دهد.
   * لایهٔ پشت hero همان Backdrop است؛ برای بی‌ابهامی مثل index ری‌اکت با نام
   * HeroBackdrop بیرون می‌رود تا Backdrop آزاد بماند برای بلاک محتوایی.
   */
  import Backdrop, { type BackdropVariant } from './hero/backdrop.svelte';

  export { default as Hero, type HeroProps } from './hero/hero.svelte';
  export { Backdrop as HeroBackdrop, type BackdropVariant as HeroBackdropVariant };
</script>
