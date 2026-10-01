<script lang="ts">
  /**
   * #/chat — the conversations page: the chat block over seeded data (times
   * hang off the AGO anchors in ../data, so the day separators and the relative
   * column stamps stay honest on every load). Two conversations carry the
   * unread messages the sidebar badge counts — opening one clears its share of
   * the live total in the store — and one thread is typing, so the indicator is
   * one click away. Sending echoes into the thread locally; there is no backend
   * in the demo panel.
   */
  import { chat, strings } from '../store.svelte';
  import { AGO } from '../data';
  import Chat, { type ChatConversation } from '../lib/Chat.svelte';

  const s = $derived(strings());
  const c = $derived(s.pages.chat);
  const people = $derived(s.people);

  /** The thread open on arrival — its unread clears as soon as it is read. */
  let current = $state('maryam');

  const conversations = $derived<ChatConversation[]>([
    {
      id: 'maryam',
      name: people.maryam,
      status: 'online',
      role: c.roleMaryam,
      preview: c.maryamPreview,
      time: AGO.minutes18.at,
      unread: chat.unread.maryam || undefined,
      messages: [
        { id: 'm1', side: 'in', text: c.maryamM1, time: AGO.hours2.at },
        { id: 'm2', side: 'in', text: c.maryamM2, time: AGO.hours2.at },
        { id: 'm3', side: 'out', text: c.maryamM3, time: AGO.hour1.at },
        { id: 'm4', side: 'in', text: c.maryamM4, time: AGO.minutes18.at },
      ],
    },
    {
      id: 'ali',
      name: people.ali,
      status: 'busy',
      role: c.roleAli,
      preview: c.aliPreview,
      time: AGO.minutes3.at,
      unread: chat.unread.ali || undefined,
      typing: true,
      messages: [
        { id: 'a1', side: 'out', text: c.aliA1, time: AGO.hour1.at },
        { id: 'a2', side: 'in', text: c.aliA2, time: AGO.minutes3.at },
      ],
    },
    {
      id: 'sara',
      name: people.sara,
      status: 'away',
      role: c.roleSara,
      preview: c.saraPreview,
      time: AGO.hours5.at,
      messages: [
        { id: 's1', side: 'in', text: c.saraS1, time: AGO.day1.at },
        { id: 's2', side: 'out', text: c.saraS2, time: AGO.day1.at },
        { id: 's3', side: 'in', text: c.saraS3, time: AGO.hours5.at },
      ],
    },
    {
      id: 'acme',
      name: c.acmeAccounts,
      status: 'offline',
      role: c.roleAcme,
      preview: c.acmePreview,
      time: AGO.day1.at,
      messages: [
        { id: 'ac1', side: 'in', text: c.acmeAc1, time: AGO.day1.at },
        { id: 'ac2', side: 'out', text: c.acmeAc2, time: AGO.day1.at },
      ],
    },
    {
      id: 'mina',
      name: people.mina,
      status: 'offline',
      role: c.roleCustomer,
      preview: c.minaPreview,
      time: AGO.days2.at,
      messages: [
        { id: 'mi1', side: 'in', text: c.minaMi1, time: AGO.days2.at },
        { id: 'mi2', side: 'out', text: c.minaMi2, time: AGO.days2.at },
      ],
    },
  ]);

  // Opening a conversation is reading it: its share of the live total clears.
  $effect(() => {
    if (current && chat.unread[current]) chat.unread = { ...chat.unread, [current]: 0 };
  });
</script>

<Chat conversations={conversations} bind:value={current} height="38rem" searchPlaceholder={c.search} ariaLabel={c.title} />
