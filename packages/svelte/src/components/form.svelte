<script module lang="ts">
  /**
   * NxForm — همتای Svelte برای components/form.tsx در @nabuxai/ui-react.
   * سولت تک‌کامپوننتی است، پس این صندلیِ خانواده است: کامپوننت‌ها را از
   * ./form/ با همان نام‌هایی که همتای React صادر می‌کند بیرون می‌دهد.
   */
  export { default as Field } from './form/field.svelte';
  export { default as Input } from './form/input.svelte';
  export { default as Textarea } from './form/textarea.svelte';
  export { default as Select, type SelectOption } from './form/select.svelte';
  export { default as Checkbox } from './form/checkbox.svelte';
  export { default as Switch } from './form/switch.svelte';
  export { default as Radio } from './form/radio.svelte';
  export { default as RadioGroup, type RadioGroupOption } from './form/radio-group.svelte';
  export { default as OtpInput } from './form/otp-input.svelte';
  export { default as Slider } from './form/slider.svelte';
  export { default as FileDrop, type FileItem } from './form/file-drop.svelte';
  export { default as PromptInput } from './form/prompt-input.svelte';
  export { default as Chip } from './form/chip.svelte';
</script>
