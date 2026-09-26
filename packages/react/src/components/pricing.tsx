import type { ReactNode } from 'react';
import { cx, useControllable } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { useLocale } from '../internal/provider';
import { Button } from './button';
import { Badge } from './feedback';
import { SegmentedControl } from './navigation';
import { NumberTicker } from './text';

export type Billing = 'monthly' | 'yearly';

export interface PricingPlan {
  id: string;
  name: ReactNode;
  description?: ReactNode;
  /** Amount shown for each billing period (e.g. per month on both). */
  price: Record<Billing, number>;
  /** A currency sign before the amount ("$", "€"); omit for units after it. */
  currency?: string;
  /** Text after the amount ("/month", "تومان در ماه"). */
  period?: Partial<Record<Billing, ReactNode>>;
  featured?: boolean;
  /** Ribbon on a featured plan ("Most popular"). */
  flag?: ReactNode;
  features: Array<{ label: ReactNode; included?: boolean }>;
  cta: { label: ReactNode; href?: string; onClick?: () => void };
}

export interface PricingTableProps {
  plans: PricingPlan[];
  billing?: Billing;
  defaultBilling?: Billing;
  onBillingChange?: (billing: Billing) => void;
  /** Labels for the monthly / yearly switch. */
  billingLabels?: Record<Billing, ReactNode>;
  /** A note beside the yearly option ("-20%"). */
  yearlyNote?: ReactNode;
  locale?: string;
  className?: string;
}

const INTL = { en: 'en-US', fa: 'fa-IR', ar: 'ar' } as const;

export function PricingTable({
  plans,
  billing,
  defaultBilling = 'monthly',
  onBillingChange,
  billingLabels = { monthly: 'Monthly', yearly: 'Yearly' },
  yearlyNote,
  locale,
  className,
}: PricingTableProps) {
  const language = useLocale();
  const [period, setPeriod] = useControllable(billing, defaultBilling, onBillingChange);
  const intl = locale ?? INTL[language];

  return (
    <section className={cx('nx-pricing', className)}>
      <div className="nx-pricing-toggle">
        <SegmentedControl
          options={[
            { value: 'monthly', label: billingLabels.monthly },
            { value: 'yearly', label: billingLabels.yearly },
          ]}
          value={period}
          onValueChange={(value) => setPeriod(value as Billing)}
          aria-label={typeof billingLabels.monthly === 'string' ? `${billingLabels.monthly} / ${billingLabels.yearly}` : undefined}
        />
        {yearlyNote && (
          <Badge tone="success" dot>
            {yearlyNote}
          </Badge>
        )}
      </div>
      <div className="nx-pricing-grid">
        {plans.map((plan) => (
          <article key={plan.id} className="nx-plan" data-featured={plan.featured ? '' : undefined}>
            {plan.featured && plan.flag && <span className="nx-plan-flag">{plan.flag}</span>}
            <h3 className="nx-plan-name">{plan.name}</h3>
            {plan.description && <p className="nx-plan-description">{plan.description}</p>}
            <p className="nx-plan-price">
              {plan.currency && <span className="nx-plan-currency">{plan.currency}</span>}
              <NumberTicker value={plan.price[period]} locale={intl} reveal={false} />
              {plan.period?.[period] && <span className="nx-plan-period">{plan.period[period]}</span>}
            </p>
            <ul className="nx-plan-features">
              {plan.features.map((feature, i) => (
                <li key={i} data-excluded={feature.included === false ? '' : undefined}>
                  <Icon name={feature.included === false ? 'minus' : 'check'} />
                  <span>{feature.label}</span>
                </li>
              ))}
            </ul>
            <div className="nx-plan-action">
              <Button block variant={plan.featured ? 'primary' : 'secondary'} size="lg" href={plan.cta.href} onClick={plan.cta.onClick} effect={plan.featured ? 'shine' : undefined}>
                {plan.cta.label}
              </Button>
            </div>
          </article>
        ))}
      </div>
    </section>
  );
}
