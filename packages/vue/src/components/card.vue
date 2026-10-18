<script lang="ts">
/**
 * NxCard — همتای Vue برای components/card.tsx در @nabuxai/ui-react.
 *
 * خانوادهٔ کارت: NxCard (صندلی پیش‌فرض)، NxFlipCard، NxStackCards،
 * NxSwipeStack و NxStatCard. قطعات با <script setup> در internal نوشته شده و
 * از این صندلی صادر می‌شوند تا index.ts (export *) به همه برسد.
 */
import { NxCard } from '../internal/cards';

export { NxCard, NxFlipCard, NxStackCards, NxSwipeStack, NxStatCard } from '../internal/cards';
export type { SwipeVerdict } from '../internal/cards';
export default NxCard;
</script>
