<script lang="ts">
/**
 * NxFeedback — همتای Vue برای components/feedback.tsx در @nabuxai/ui-react.
 *
 * خانوادهٔ بازخورد: NxToaster (صندلی پیش‌فرض) و تابع toast، NxAlert، NxBadge،
 * NxTooltip، NxProgress، NxSkeleton، NxSpinner، NxOrbitLoader، NxDotsLoader و
 * NxCuneiformLoader. قطعات با <script setup> در internal نوشته شده و از این
 * صندلی صادر می‌شوند تا index.ts (export *) به همه برسد.
 */
import { NxToaster } from '../internal/feedback';

export {
  toast,
  NxToaster,
  NxAlert,
  NxBadge,
  NxTooltip,
  NxProgress,
  NxSkeleton,
  NxSpinner,
  NxOrbitLoader,
  NxDotsLoader,
  NxCuneiformLoader,
} from '../internal/feedback';
export type { ToasterPosition, NxTooltipProps, ToastStore, ToastTone } from '../internal/feedback';
export default NxToaster;
</script>
