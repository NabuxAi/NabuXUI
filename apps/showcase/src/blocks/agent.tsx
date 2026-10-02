import { Section } from '../Section';
import { useTr } from '../lang';

export function AgentBlocks() {
  const tr = useTr();
  return (
    <Section id="agent-blocks" eyebrow={tr('بلوک‌ها', 'Blocks')} title={tr('Agent', 'Agent')} description="">
      {null}
    </Section>
  );
}
