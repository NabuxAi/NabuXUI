import { useState } from 'react';
import { Button, GlassSegmented, Icon3d, Icon3dRow, type Icon3dName, type Icon3dTone } from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

/** The whole set, one language: body + accent gradients, white edge, ground shadow. */
const SET: Array<{ name: Icon3dName; fa: string; en: string }> = [
  { name: 'rocket', fa: 'موشک', en: 'Rocket' },
  { name: 'chart', fa: 'نمودار', en: 'Chart' },
  { name: 'shield', fa: 'سپر', en: 'Shield' },
  { name: 'cloud', fa: 'ابر', en: 'Cloud' },
  { name: 'message', fa: 'پیام', en: 'Message' },
  { name: 'gear', fa: 'چرخ‌دنده', en: 'Gear' },
  { name: 'lock', fa: 'قفل', en: 'Lock' },
  { name: 'camera', fa: 'دوربین', en: 'Camera' },
  { name: 'battery', fa: 'باتری', en: 'Battery' },
  { name: 'search', fa: 'جست‌وجو', en: 'Search' },
  { name: 'folder', fa: 'پوشه', en: 'Folder' },
  { name: 'star', fa: 'ستاره', en: 'Star' },
  { name: 'bolt', fa: 'رعد', en: 'Bolt' },
];

const sizeLabel = { margin: 0, color: 'var(--nx-text-muted)', fontSize: 'var(--nx-text-xs)' } as const;

export function Icons3dBlocks() {
  const tr = useTr();
  const [tone, setTone] = useState<Icon3dTone>('lapis');

  return (
    <Section
      id="icons-blocks"
      eyebrow={tr('آیکون‌های سه‌بعدی', '3D icons')}
      title={tr('سیزده آیکون، یک زبان', 'Thirteen icons, one language')}
      description={tr(
        'هر آیکون SVG درون‌خطی خالص روی شبکهٔ ۶۴×۶۴ است: گرادیان بدنه، گرادیان لهجه، هایلایت لبهٔ سفید و سایهٔ بیضی روی زمین. هر نمونه گرادیان‌هایش را با id یکتا می‌کشد، یک tone کل مجموعه را رنگ می‌کند و تیلت اشاره‌گر فقط روی اشاره‌گرهای دقیق بیدار می‌شود.',
        'Every icon is pure inline SVG on a 64×64 grid: a body gradient, an accent gradient, a white edge highlight and an elliptical ground shadow. Each instance draws its gradients under unique ids, one tone re-skins the whole set, and the pointer tilt only wakes on fine pointers.',
      )}
      code={{
        react: `<Icon3d name="rocket" size="display" tone="gold" tilt />
<Icon3d name="bolt" size={24} label="Deploys" />
<Icon3dRow items={[{ icon: 'bolt', title: 'Every commit is a release', text: '…' }]} />`,
        blade: `<x-nx::icon-3d name="rocket" size="display" tone="gold" tilt />
<x-nx::icon-3d name="bolt" size="24" label="Deploys" />
<x-nx::icon-3d-row :items="$features" />`,
      }}
    >
      <div className="sc-demos">
        <Demo wide title={tr('کل مجموعه با یک tone', 'The whole set, one tone')}>
          <GlassSegmented
            aria-label={tr('رنگ مجموعه', 'Set colour')}
            value={tone}
            onValueChange={(value) => setTone(value as Icon3dTone)}
            options={[
              { value: 'lapis', label: tr('لاژوردی', 'Lapis') },
              { value: 'violet', label: tr('بنفش', 'Violet') },
              { value: 'gold', label: tr('طلایی', 'Gold') },
            ]}
          />
          <ul className="nx-icon3d-grid">
            {SET.map((entry) => (
              <li key={entry.name} className="nx-icon3d-cell">
                <Icon3d name={entry.name} tone={tone} tilt label={tr(entry.fa, entry.en)} />
                <span className="nx-icon3d-name">{tr(entry.fa, entry.en)}</span>
              </li>
            ))}
          </ul>
        </Demo>

        <Demo wide title={tr('یک زبان، هر اندازه', 'One language, every size')}>
          <div style={{ display: 'flex', flexWrap: 'wrap', alignItems: 'end', justifyContent: 'center', gap: '1.25rem 2.25rem' }}>
            <div style={{ display: 'grid', gap: '0.6rem', justifyItems: 'center' }}>
              <Button variant="secondary" size="lg">
                <Icon3d name="gear" size="sm" />
                {tr('تنظیمات فضای کاری', 'Workspace settings')}
              </Button>
              <small style={sizeLabel}>{tr('۲۴ پیکسل، داخل دکمه', '24px, in a button')}</small>
            </div>
            <div style={{ display: 'grid', gap: '0.6rem', justifyItems: 'center' }}>
              <span
                style={{
                  display: 'inline-flex',
                  alignItems: 'center',
                  gap: '0.6rem',
                  inlineSize: 'min(15rem, 74vw)',
                  padding: '0.55rem 1rem',
                  borderRadius: 'var(--nx-radius-lg)',
                  background: 'var(--nx-surface-2)',
                  boxShadow: 'inset 0 0 0 1px var(--nx-border)',
                  color: 'var(--nx-text-muted)',
                  fontSize: 'var(--nx-text-sm)',
                }}
              >
                <Icon3d name="search" size="sm" />
                {tr('جست‌وجو در مستندات…', 'Search the docs…')}
              </span>
              <small style={sizeLabel}>{tr('۲۴ پیکسل، داخل فیلد', '24px, in a field')}</small>
            </div>
            <div style={{ display: 'grid', gap: '0.6rem', justifyItems: 'center' }}>
              <Icon3d name="folder" size="md" />
              <small style={sizeLabel}>48px</small>
            </div>
            <div style={{ display: 'grid', gap: '0.6rem', justifyItems: 'center' }}>
              <Icon3d name="lock" size="lg" />
              <small style={sizeLabel}>64px</small>
            </div>
            <div style={{ display: 'grid', gap: '0.6rem', justifyItems: 'center' }}>
              <Icon3d name="rocket" size="display" tone="gold" />
              <small style={sizeLabel}>{tr('۱۰۸ پیکسل، نمایشی', '108px, display')}</small>
            </div>
          </div>
        </Demo>

        <Demo wide title={tr('ردیف قابلیت با تیلت هاور', 'The feature row, tilting on hover')}>
          <Icon3dRow
            items={[
              {
                icon: 'bolt',
                title: tr('هر کامیت یک انتشار', 'Every commit is a release'),
                text: tr(
                  'ادغام شاخه یعنی ساختن و منتشر شدن خودکار؛ نه صفی براییدارید و نه خط لوله‌ای را پرورش دهید.',
                  'Merging a branch builds and ships it on its own — no queue to watch, no pipeline to nurse.',
                ),
                href: '#icons-blocks',
                link: tr('چطور کار می‌کند', 'How it works'),
              },
              {
                icon: 'chart',
                title: tr('پیش‌بینی هزینه', 'Spend forecasting'),
                text: tr(
                  'روند هزینهٔ هر سرویس را سی روز جلوتر ببینید و پیش از رسیدن فاکتور خبردار شوید، نه بعد از آن.',
                  'See each service’s trend thirty days ahead and hear about it before the invoice, not after.',
                ),
                tone: 'violet',
              },
              {
                icon: 'shield',
                title: tr('امنیت از روز اول', 'Secure from day one'),
                text: tr(
                  'رمزنگاری سرتاسری، ورود یکپارچه و گزارش ممیزی همیشه روشن است — نه افزودنی‌ای برای بعداً.',
                  'End-to-end encryption, single sign-on and audit trails are always on — not an add-on for later.',
                ),
                tone: 'gold',
              },
            ]}
          />
        </Demo>
      </div>
    </Section>
  );
}
