<script module lang="ts">
  /**
   * NxButton — همتای Svelte برای components/button.tsx در @nabuxai/ui-react.
   * سولت تک‌کامپوننتی است، پس این صندلیِ خانواده است: کامپوننت‌ها را از
   * ./button/ با همان نام‌هایی که همتای React صادر می‌کند بیرون می‌دهد.
   */
  export { default as Button, type ButtonVariant, type ButtonSize, type ButtonStatus } from './button/button.svelte';
  export { default as ButtonStatusMarks } from './button/button-status-marks.svelte';
  export { default as IconButton } from './button/icon-button.svelte';
  export { default as CopyButton } from './button/copy-button.svelte';
  export { default as LikeButton } from './button/like-button.svelte';
</script>
