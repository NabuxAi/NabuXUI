<!--
  The seat-gate app: mounts the family components that take snippet props with
  REAL #snippet blocks (the way consumers write them), next to the tooltip's
  parameterized trigger snippet. test/families.test.ts mounts this and asserts
  on the rendered DOM.
-->
<script lang="ts">
  import Card from '../src/components/card/card.svelte';
  import FlipCard from '../src/components/card/flip-card.svelte';
  import StackCards from '../src/components/card/stack-cards.svelte';
  import SwipeStack from '../src/components/card/swipe-stack.svelte';
  import Badge from '../src/components/feedback/badge.svelte';
  import Tooltip from '../src/components/feedback/tooltip.svelte';
  import Grid from '../src/components/display/grid.svelte';
  import Bento from '../src/components/display/bento.svelte';
  import BentoItem from '../src/components/display/bento-item.svelte';
  import Kbd from '../src/components/display/kbd.svelte';

  const people = [
    { id: 1, name: 'Ann' },
    { id: 2, name: 'Bob' },
  ];
</script>

<Card title="With children" href="https://example.com">
  {#snippet children()}
    <p>the body</p>
  {/snippet}
</Card>

<FlipCard trigger="click" flipLabel="Turn">
  {#snippet front()}
    <span>front face</span>
  {/snippet}
  {#snippet back()}
    <span>back face</span>
  {/snippet}
</FlipCard>

{#snippet cardA()}
  <div class="stack-one">first</div>
{/snippet}
{#snippet cardB()}
  <div class="stack-two">second</div>
{/snippet}

<StackCards items={[cardA, cardB]} />

<SwipeStack items={people}>
  {#snippet renderItem(person)}
    <span class="deck-card">card of {person.name}</span>
  {/snippet}
</SwipeStack>

<Badge tone="gold">Gold badge</Badge>

<Kbd>⌘</Kbd>

<Grid min="12rem">
  {#snippet children()}
    <div>cell</div>
  {/snippet}
</Grid>

<Bento columns={4}>
  {#snippet children()}
    <BentoItem title="Tile" colSpan={2}>tile body</BentoItem>
  {/snippet}
</Bento>

<Tooltip content="Save the document">
  {#snippet children(anchor)}
    <button {...anchor} class="trigger">Save</button>
  {/snippet}
</Tooltip>
