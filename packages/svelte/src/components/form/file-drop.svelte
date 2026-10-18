<script lang="ts">
  /**
   * FileDrop — همتای Svelte برای FileDrop در ui-react/components/form.tsx.
   */
  import { cx } from '../../internal/cx';
  import Icon from '../../internal/icon.svelte';
  import { fieldProps, useField } from '../../internal/field';
  import { nxLang } from '../../internal/locale';
  import { nxTranslate, type NxLang } from '../../i18n';
  import type { MessageKey } from '@nabuxai/ui-core';
  import type { Snippet } from 'svelte';
  import type { HTMLInputAttributes } from 'svelte/elements';

  export interface FileItem {
    name: string;
    size?: number;
    /** 0–100 while uploading. */
    progress?: number;
    status?: 'uploading' | 'done' | 'error';
  }

  interface Props extends Omit<HTMLInputAttributes, 'type' | 'onchange' | 'title' | 'files'> {
    onFiles?: (files: File[]) => void;
    /** Files to list under the drop zone (with their upload progress). */
    files?: FileItem[];
    title?: Snippet | string;
    hint?: Snippet | string;
  }

  let { onFiles, files, title, hint, class: className, disabled, ...rest }: Props = $props();

  const field = useField();
  // فایل‌اینپوت مانند ری‌اکت سیم‌کشی Field را هم می‌گیرد (id/aria) هرچند پنهان است.
  const wiring = $derived(fieldProps(rest, field));
  const t = (key: MessageKey, params: Record<string, string | number> = {}) => nxTranslate(nxLang() as NxLang, key, params);

  let dragging = $state(false);
  let depth = 0;

  const take = (list: FileList | null) => {
    const picked = list ? Array.from(list) : [];
    if (picked.length) onFiles?.(picked);
  };

  // «حجم فایل» با همان فرمول ری‌اکت.
  const formatSize = (bytes?: number) => {
    if (bytes === undefined) return '';
    const units = ['B', 'KB', 'MB', 'GB'];
    let i = 0;
    let size = bytes;
    while (size >= 1024 && i < units.length - 1) {
      size /= 1024;
      i += 1;
    }
    return `${size.toFixed(size < 10 && i > 0 ? 1 : 0)} ${units[i]}`;
  };

  // عنوان پیش‌فرض: «فایل‌ها را اینجا رها کنید یا browse» با زیرخط روی واژهٔ دوم.
  const dropTemplate = $derived(t('dropFiles', { browse: '{browse}' }).split('{browse}'));
</script>

<div class={className}>
  <!-- svelte-ignore a11y_label_has_associated_control -->
  <!-- input پنهان داخل label است؛ همان ساخت ری‌اکت. -->
  <label
    class="nx-filedrop"
    data-dragging={dragging ? '' : undefined}
    ondragenter={(event) => {
      event.preventDefault();
      if (disabled) return;
      depth += 1;
      dragging = true;
    }}
    ondragover={(event) => event.preventDefault()}
    ondragleave={() => {
      depth = Math.max(0, depth - 1);
      if (depth === 0) dragging = false;
    }}
    ondrop={(event) => {
      event.preventDefault();
      depth = 0;
      dragging = false;
      if (!disabled) take(event.dataTransfer?.files ?? null);
    }}
  >
    <input type="file" class="nx-visually-hidden" {disabled} onchange={(event) => take(event.currentTarget.files)} {...rest} {...wiring} />
    <span class="nx-filedrop-icon" aria-hidden="true">
      <Icon name="upload" />
    </span>
    <span class="nx-filedrop-title">
      {#if title}
        {#if typeof title === 'function'}{@render title()}{:else}{title}{/if}
      {:else}
        {dropTemplate[0]}<u>{t('browse')}</u>{dropTemplate[1] ?? ''}
      {/if}
    </span>
    {#if hint}<span class="nx-filedrop-hint">{#if typeof hint === 'function'}{@render hint()}{:else}{hint}{/if}</span>{/if}
  </label>

  {#if files && files.length > 0}
    <ul class="nx-file-list">
      {#each files as file, i (`${file.name}-${i}`)}
        <li class="nx-file" data-status={file.status} style="--nx-i: {i}">
          <Icon name="file" />
          <span class="nx-file-name">{file.name}</span>
          <span
            class="nx-file-state"
            aria-label={file.status ? t(file.status === 'done' ? 'uploaded' : file.status === 'error' ? 'failed' : 'uploading') : undefined}
          >
            {#if file.status === 'done'}
              <Icon name="check-circle" />
            {:else if file.status === 'error'}
              <Icon name="alert-circle" />
            {/if}
          </span>
          <span class="nx-file-meta">{formatSize(file.size)}</span>
          {#if file.status === 'uploading'}
            <progress class="nx-progress" value={file.progress ?? 0} max={100} style="--nx-value: {file.progress ?? 0}" aria-label="{t('uploading')} {file.name}"></progress>
          {/if}
        </li>
      {/each}
    </ul>
  {/if}
</div>
