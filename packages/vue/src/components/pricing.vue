<script lang="ts">
/**
 * NxPricing — the pricing seat of @nabuxai/ui-vue: the pricing table (the
 * default export) — the Vue counterpart of ui-react's components/pricing.tsx.
 */
import NxPricingTable from '../internal/pricing/pricing.vue';

// The seat's face for a default import; every part is a named export below.
export default NxPricingTable;

export { default as NxPricingTable, type NxBilling, type NxPricingPlan, type NxPricingTableProps } from '../internal/pricing/pricing.vue';
</script>
