import type { ReactNode } from 'react';
import { Badge, CopyButton, Reveal } from '@nabuxai/ui-react';
import { type Framework, useFramework, useTr } from './lang';

export interface SnippetCode {
  react: string;
  blade: string;
  inertia?: string;
  vue?: string;
  svelte?: string;
}

export function Section({ id, eyebrow, title, description, children, code }: { id: string; eyebrow: string; title: string; description: string; children: ReactNode; code?: SnippetCode }) {
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

/** The snippet's label per active framework; Livewire's code is Blade, like the old tab said. */
const SNIPPET_NAMES: Record<Framework, string> = {
  react: 'React',
  inertia: 'Inertia',
  livewire: 'Livewire · Blade',
  vue: 'Vue',
  svelte: 'Svelte',
};

/**
 * The code of the framework the header switcher has active — no tabs, all five
 * strings stay available and only the shown one changes. When a framework has
 * no snippet of its own the nearest one shows instead (react for Inertia,
 * blade for Vue/Svelte) and the note above the code says so; the note is its
 * own element, never woven into the copied string.
 */
export function Snippet({ react, blade, inertia, vue, svelte }: SnippetCode) {
  const tr = useTr();
  const { framework } = useFramework();

  const native: Record<Framework, string | undefined> = { react, inertia, livewire: blade, vue, svelte };
  const source = native[framework] ?? (framework === 'inertia' ? react : blade);
  const note = native[framework]
    ? null
    : framework === 'inertia'
      ? tr('Inertia: همان کامپوننت داخل صفحهٔ Inertia خودت.', 'Inertia: the same component, inside your own Inertia page.')
      : tr(
          'Vue/Svelte: هستهٔ NabuXUI یک CSS مشترک است؛ markup پایه همین خروجی Blade است و فقط CSS را import می‌کنی.',
          'Vue/Svelte: the NabuXUI core is one shared CSS file; the base markup is this Blade output and you only import the CSS.',
        );

  return (
    <div className="sc-snippet">
      <p className="sc-demo-title">
        {tr('نمونه کد', 'Code sample')} · {SNIPPET_NAMES[framework]}
      </p>
      {note && (
        <p className="sc-demo-title" style={{ fontWeight: 500, color: 'var(--nx-text-muted)' }}>
          {note}
        </p>
      )}
      <Code source={source} />
    </div>
  );
}
