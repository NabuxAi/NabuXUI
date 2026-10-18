<script module lang="ts">
  import type { Snippet } from 'svelte';

  export type Billing = 'monthly' | 'yearly';

  export interface PricingPlan {
    id: string;
    name: string | Snippet;
    description?: string | Snippet;
    /** Amount shown for each billing period (e.g. per month on both). */
    price: Record<Billing, number>;
    /** A currency sign before the amount ("$", "€"); omit for units after it. */
    currency?: string;
    /** Text after the amount ("/month", "تومان در ماه"). */
    period?: Partial<Record<Billing, string | Snippet>>;
    featured?: boolean;
    /** Ribbon on a featured plan ("Most popular"). */
    flag?: string | Snippet;
    features: Array<{ label: string | Snippet; included?: boolean }>;
    cta: { label: string | Snippet; href?: string; onClick?: () => void };
  }

  export interface PricingTableProps {
    plans: PricingPlan[];
    billing?: Billing;
    defaultBilling?: Billing;
    onBillingChange?: (billing: Billing) => void;
    /** Labels for the monthly / yearly switch. */
    billingLabels?: Record<Billing, string | Snippet>;
    /** A note beside the yearly option ("-20%"). */
    yearlyNote?: string | Snippet;
    locale?: string;
    class?: string;
  }
</script>

<script lang="ts">
  import Icon from '../../internal/icon.svelte';
  import Content from '../../internal/content.svelte';
  import { cx } from '../../internal/cx';
  import { nxIntlLocale } from '../../internal/locale';
  import { Badge } from '../feedback.svelte';
  import { Button } from '../button.svelte';
  import { NumberTicker } from '../text.svelte';
  import { SegmentedControl } from '../navigation.svelte';

  let {
    plans,
    billing,
    defaultBilling = 'monthly',
    onBillingChange,
    billingLabels = { monthly: 'Monthly', yearly: 'Yearly' },
    yearlyNote,
    locale,
    class: className,
  }: PricingTableProps = $props();

  // svelte-ignore state_referenced_locally
  // defaultBilling seeds the internal state once, exactly like the React seat's useState.
  let internal = $state(defaultBilling);
  /** Controlled while `billing` is passed, internal otherwise. */
  let period = $derived(billing !== undefined ? billing : internal);

  const setPeriod = (next: Billing) => {
    if (billing === undefined) internal = next;
    onBillingChange?.(next);
  };

  const intl = $derived(locale ?? nxIntlLocale());
</script>

<section class={cx('nx-pricing', className)}>
  <div class="nx-pricing-toggle">
    <SegmentedControl
      options={[
        { value: 'monthly', label: billingLabels.monthly },
        { value: 'yearly', label: billingLabels.yearly },
      ]}
      value={period}
      onValueChange={(value) => setPeriod(value as Billing)}
      aria-label={typeof billingLabels.monthly === 'string' ? `${billingLabels.monthly} / ${billingLabels.yearly}` : undefined}
    />
    {#if yearlyNote}
      <Badge tone="success" dot>
        <Content of={yearlyNote} />
      </Badge>
    {/if}
  </div>
  <div class="nx-pricing-grid">
    {#each plans as plan (plan.id)}
      <article class="nx-plan" data-featured={plan.featured ? '' : undefined}>
        {#if plan.featured && plan.flag}
          <span class="nx-plan-flag">
            <Content of={plan.flag} />
          </span>
        {/if}
        <h3 class="nx-plan-name">
          <Content of={plan.name} />
        </h3>
        {#if plan.description}
          <p class="nx-plan-description">
            <Content of={plan.description} />
          </p>
        {/if}
        <p class="nx-plan-price">
          {#if plan.currency}
            <span class="nx-plan-currency">{plan.currency}</span>
          {/if}
          <NumberTicker value={plan.price[period]} locale={intl} reveal={false} />
          {#if plan.period?.[period]}
            <span class="nx-plan-period">
              <Content of={plan.period[period]} />
            </span>
          {/if}
        </p>
        <ul class="nx-plan-features">
          {#each plan.features as feature, i}
            <li data-excluded={feature.included === false ? '' : undefined}>
              <Icon name={feature.included === false ? 'minus' : 'check'} />
              <span>
                <Content of={feature.label} />
              </span>
            </li>
          {/each}
        </ul>
        <div class="nx-plan-action">
          <Button
            block
            variant={plan.featured ? 'primary' : 'secondary'}
            size="lg"
            href={plan.cta.href}
            onclick={plan.cta.onClick}
            effect={plan.featured ? 'shine' : undefined}
          >
            <Content of={plan.cta.label} />
          </Button>
        </div>
      </article>
    {/each}
  </div>
</section>
