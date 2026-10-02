import { Section } from '../Section';
import { useTr } from '../lang';

export function PickersBlocks() {
  const tr = useTr();
  return (
    <Section id="pickers-blocks" eyebrow={tr('بلوک‌ها', 'Blocks')} title={tr('Pickers', 'Pickers')} description="">
      {null}
    </Section>
  );
}
