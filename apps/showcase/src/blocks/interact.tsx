import { Section } from '../Section';
import { useTr } from '../lang';

export function InteractBlocks() {
  const tr = useTr();
  return (
    <Section id="interact-blocks" eyebrow={tr('بلوک‌ها', 'Blocks')} title={tr('Interact', 'Interact')} description="">
      {null}
    </Section>
  );
}
