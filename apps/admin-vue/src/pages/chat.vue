<script setup lang="ts">
/**
 * The conversations page — the NxChat block over seeded data (times hang off
 * the AGO anchors in ../data, so the day separators and the relative column
 * stamps stay honest on every load). Two unread conversations carry the three
 * unread messages the sidebar badge counts; one thread is typing so the
 * indicator is one click away. Sending echoes into the thread locally —
 * there is no backend in the demo panel.
 */
import { computed } from 'vue';
import { AGO } from '../data';
import { s } from '../store';
import NxChat, { type ChatConversation } from '../components/NxChat.vue';

const c = computed(() => s.value.pages.chat);
const people = computed(() => s.value.people);

const conversations = computed<ChatConversation[]>(() => [
  {
    id: 'maryam',
    name: people.value.maryam,
    status: 'online',
    role: c.value.roleMaryam,
    preview: c.value.maryamPreview,
    time: AGO.minutes18.at,
    unread: 2,
    messages: [
      { id: 'm1', side: 'in', text: c.value.maryamM1, time: AGO.hours2.at },
      { id: 'm2', side: 'in', text: c.value.maryamM2, time: AGO.hours2.at },
      { id: 'm3', side: 'out', text: c.value.maryamM3, time: AGO.hour1.at },
      { id: 'm4', side: 'in', text: c.value.maryamM4, time: AGO.minutes18.at },
    ],
  },
  {
    id: 'ali',
    name: people.value.ali,
    status: 'busy',
    role: c.value.roleAli,
    preview: c.value.aliPreview,
    time: AGO.minutes3.at,
    unread: 1,
    typing: true,
    messages: [
      { id: 'a1', side: 'out', text: c.value.aliA1, time: AGO.hour1.at },
      { id: 'a2', side: 'in', text: c.value.aliA2, time: AGO.minutes3.at },
    ],
  },
  {
    id: 'sara',
    name: people.value.sara,
    status: 'away',
    role: c.value.roleSara,
    preview: c.value.saraPreview,
    time: AGO.hours5.at,
    messages: [
      { id: 's1', side: 'in', text: c.value.saraS1, time: AGO.day1.at },
      { id: 's2', side: 'out', text: c.value.saraS2, time: AGO.day1.at },
      { id: 's3', side: 'in', text: c.value.saraS3, time: AGO.hours5.at },
    ],
  },
  {
    id: 'acme',
    name: c.value.acmeAccounts,
    status: 'offline',
    role: c.value.roleAcme,
    preview: c.value.acmePreview,
    time: AGO.day1.at,
    messages: [
      { id: 'ac1', side: 'in', text: c.value.acmeAc1, time: AGO.day1.at },
      { id: 'ac2', side: 'out', text: c.value.acmeAc2, time: AGO.day1.at },
    ],
  },
  {
    id: 'mina',
    name: people.value.mina,
    status: 'offline',
    role: c.value.roleCustomer,
    preview: c.value.minaPreview,
    time: AGO.days2.at,
    messages: [
      { id: 'mi1', side: 'in', text: c.value.minaMi1, time: AGO.days2.at },
      { id: 'mi2', side: 'out', text: c.value.minaMi2, time: AGO.days2.at },
    ],
  },
]);
</script>

<template>
  <NxChat
    class="adm-chat"
    height="38rem"
    :conversations="conversations"
    :search-placeholder="c.search"
    :labels="{ empty: c.emptyTitle }"
  />
</template>
