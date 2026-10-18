<script lang="ts">
/**
 * NxDisplay — همتای Vue برای components/display.tsx در @nabuxai/ui-react.
 *
 * خانوادهٔ نمایش: NxAvatar (صندلی پیش‌فرض)، NxAvatarGroup، NxKbd، NxRating،
 * NxSteps، NxMarquee، NxGrid، NxBento، NxBentoItem و NxDivider. قطعات با
 * <script setup> در internal نوشته شده و از این صندلی صادر می‌شوند تا
 * index.ts (export *) به همه برسد.
 */
import { NxAvatar } from '../internal/display';

export {
  NxAvatar,
  NxAvatarGroup,
  NxKbd,
  NxRating,
  NxSteps,
  NxMarquee,
  NxGrid,
  NxBento,
  NxBentoItem,
  NxDivider,
} from '../internal/display';
export default NxAvatar;
</script>
