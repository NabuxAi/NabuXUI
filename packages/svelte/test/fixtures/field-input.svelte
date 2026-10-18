<script lang="ts">
  /** Test fixture: an Input nested in a Field, exactly as apps compose them. */
  import { Field, Input } from '../../src/components/form.svelte';
</script>

<Field label="تلفن" hint="با ۰ شروع شود" error="کوتاه است" id="phone">
  <Input />
</Field>
