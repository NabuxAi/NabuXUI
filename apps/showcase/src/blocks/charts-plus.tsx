import { Section } from '../Section';
import { useTr } from '../lang';

export function ChartsPlusBlocks() {
  const tr = useTr();
  return (
    <Section id="charts-plus-blocks" eyebrow={tr('بلوک‌ها', 'Blocks')} title={tr('ChartsPlus', 'ChartsPlus')} description="">
      {null}
    </Section>
  );
}
