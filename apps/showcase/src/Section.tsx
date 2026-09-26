import type { ReactNode } from 'react';
import { Badge, CopyButton, Reveal, Tabs } from '@nabuxai/ui-react';
import { useTr } from './lang';

export function Section({ id, eyebrow, title, description, children, code }: { id: string; eyebrow: string; title: string; description: string; children: ReactNode; code?: { react: string; blade: string; inertia?: string } }) {
  return (
    <section id={id} className="sc-section sc-container">
      <Reveal className="sc-section-head">
        <Badge tone="accent" dot>
          {eyebrow}
        </Badge>
        <h2>{title}</h2>
        <p>{description}</p>
      </Reveal>
      {children}
      {code && <Snippet {...code} />}
    </section>
  );
}

export function Demo({ title, children, wide, bare, center }: { title?: string; children: ReactNode; wide?: boolean; bare?: boolean; center?: boolean }) {
  return (
    <div className="sc-demo" data-wide={wide ? '' : undefined} data-bare={bare ? '' : undefined} data-center={center ? '' : undefined}>
      {title && <p className="sc-demo-title">{title}</p>}
      {children}
    </div>
  );
}

function Code({ source }: { source: string }) {
  return (
    <div className="sc-code-wrap" dir="ltr">
      <pre className="sc-code">
        <code>{source.trim()}</code>
      </pre>
      <CopyButton value={source.trim()} size="sm" />
    </div>
  );
}

export function Snippet({ react, blade, inertia }: { react: string; blade: string; inertia?: string }) {
  const tr = useTr();
  return (
    <Tabs
      variant="underline"
      aria-label={tr('نمونه کد', 'Code sample')}
      items={[
        { value: 'react', label: 'React', content: <Code source={react} /> },
        { value: 'blade', label: 'Livewire · Blade', content: <Code source={blade} /> },
        ...(inertia ? [{ value: 'inertia', label: 'Inertia', content: <Code source={inertia} /> }] : []),
      ]}
    />
  );
}
