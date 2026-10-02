import { Section } from '../Section';
import { useTr } from '../lang';

export function EffectsBlocks() {
  const tr = useTr();
  return (
    <Section id="effects-blocks" eyebrow={tr('بلوک‌ها', 'Blocks')} title={tr('Effects', 'Effects')} description="">
      {null}
    </Section>
  );
}
