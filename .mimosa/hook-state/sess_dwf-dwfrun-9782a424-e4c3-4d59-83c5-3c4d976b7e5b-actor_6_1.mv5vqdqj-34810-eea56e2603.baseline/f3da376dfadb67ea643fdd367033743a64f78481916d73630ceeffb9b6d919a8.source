/** Showcase demos: Backdrops — animated backgrounds that carry their content. */
import type { CSSProperties } from 'react';
import { Backdrop, Badge, Button } from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

/** The Backdrop block is the section: it sizes itself to its stage, not to its layers. */
const stage: CSSProperties = {
  minBlockSize: '17rem',
  display: 'grid',
  placeItems: 'center',
  alignContent: 'center',
  gap: 'var(--nx-space-4)',
  padding: 'var(--nx-space-8) var(--nx-space-6)',
  borderRadius: 'var(--nx-radius-lg)',
  textAlign: 'center',
};

const heading: CSSProperties = { margin: 0, font: '800 var(--nx-text-3xl) / 1.1 var(--nx-font-display)', letterSpacing: 'var(--nx-tracking-tighter)' };

const lead: CSSProperties = { margin: 0, color: 'var(--nx-text-muted)', fontSize: 'var(--nx-text-sm)' };

export function BackdropsBlocks() {
  const tr = useTr();

  return (
    <Section
      id="backdrops"
      eyebrow={tr('پس‌زمینه‌های متحرک', 'Animated backdrops')}
      title={tr('بخشی که پس‌زمینه‌اش را خودش می‌آورد', 'A section that brings its own background')}
      description={tr(
        'چهار پس‌زمینهٔ تمام-CSS — شفق، میدان ستاره، مش رنگین و موج موآره — که محتوا رویشان می‌نشیند و چیزی منتظر جاوااسکریپت نمی‌ماند؛ زیر حرکت کاهشی هر چهارتایشان قاب ثابتی می‌شوند.',
        'Four pure-CSS backgrounds — aurora, a parallax starfield, a living mesh and a dither wave — that carry their content and wait for no JavaScript; under reduced motion each settles into a still frame.',
      )}
      code={{
        react: `<Backdrop variant="aurora">…content…</Backdrop>
<Backdrop variant="starfield">…content…</Backdrop>
<Backdrop variant="mesh">…content…</Backdrop>
<Backdrop variant="dither" style={{ '--nx-dither-cell': '12px' }}>…content…</Backdrop>`,
        blade: `<x-nx::backdrop-aurora>…content…</x-nx::backdrop-aurora>
<x-nx::backdrop-starfield>…content…</x-nx::backdrop-starfield>
<x-nx::backdrop-mesh>…content…</x-nx::backdrop-mesh>
<x-nx::backdrop-dither style="--nx-dither-cell: 12px">…content…</x-nx::backdrop-dither>`,
      }}
    >
      <div className="sc-demos">
        <Demo title={tr('شفق · پرده‌های نور', 'Aurora · curtains of light')} bare>
          <Backdrop variant="aurora" style={stage}>
            <Badge tone="accent" dot>
              Aurora · شفق
            </Badge>
            <h3 style={heading}>{tr('رهاشدن نسخهٔ تازه', 'Ship the next release')}</h3>
            <p style={lead}>{tr('لاپیس و بنفش از دو سو عبور می‌کنند و سیان میانشان می‌چرخد.', 'Lapis and violet drift past each other; cyan swims between.')}</p>
            <Button variant="primary" icon="sparkles">
              {tr('شروع کنید', 'Get started')}
            </Button>
          </Backdrop>
        </Demo>

        <Demo title={tr('میدان ستاره · سه عمق', 'Starfield · three depths')} bare>
          <Backdrop variant="starfield" style={stage}>
            <h3 style={heading}>{tr('شب‌تاب پارالاکس', 'A parallax night sky')}</h3>
            <p style={lead}>{tr('ستاره‌های نزدیک تندتر می‌افتند و تنانفس پارالاکس می‌سازند.', 'Near stars fall faster; the sky gains depth.')}</p>
            <Button variant="glow" icon="star">
              {tr('ستاره‌ها را بشمار', 'Count the stars')}
            </Button>
          </Backdrop>
        </Demo>

        <Demo title={tr('مش · پنج میدان رنگین', 'Mesh · five colour fields')} bare>
          <Backdrop variant="mesh" style={stage}>
            <h3 style={heading}>{tr('مش زنده', 'A living mesh')}</h3>
            <p style={lead}>{tr('پنج میدان روی هم بافت می‌شوند — در تم روشن نرم، در تیره درخشان.', 'Five fields weave one surface — soft-light in light, screen in dark.')}</p>
            <Button icon="layers">{tr('بافته را ببین', 'See the weave')}</Button>
          </Backdrop>
        </Demo>

        <Demo title={tr('موآره · موج در نقطه‌ها', 'Dither · a wave in the dots')} bare>
          <Backdrop variant="dither" style={stage}>
            <h3 style={heading}>{tr('موج نیم‌تن', 'The halftone wave')}</h3>
            <p style={lead}>{tr('دو صفحهٔ نقطه‌ای در زاویه‌های مختلف، یک موج می‌سازند.', 'Two dot screens crossed at an angle read as one wave.')}</p>
            <Button variant="ghost" icon="cpu">
              {tr('گام نمایش را عوض کن', 'Change the pitch')}
            </Button>
          </Backdrop>
        </Demo>
      </div>
    </Section>
  );
}
