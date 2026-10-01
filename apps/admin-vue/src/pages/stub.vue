<script setup lang="ts">
/**
 * The shared shell of a not-yet-filled page: the empty-state block with the
 * page's own title/description from the language file and one honest way out —
 * back to the dashboard. Each stub page file wraps this; the agents that fill a
 * page replace that file and consume keys from ../lang.
 */
import type { IconName } from '@nabuxai/ui-core';
import { s } from '../store';
import { href } from '../router';
import EmptyState from '../components/EmptyState.vue';

defineProps<{ id: 'analytics' | 'calendar' | 'chat' | 'invoices' | 'profile'; icon: IconName }>();

const page = (id: 'analytics' | 'calendar' | 'chat' | 'invoices' | 'profile') => s.value.pages[id];
</script>

<template>
  <EmptyState
    :icon="icon"
    :title="page(id).emptyTitle"
    :description="page(id).emptyBody"
    :action="{ label: s.pages.dash.title, icon: 'grid', href: href('dash') }"
  />
</template>
