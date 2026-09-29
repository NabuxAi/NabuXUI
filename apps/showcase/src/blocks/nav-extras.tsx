/** Showcase demos: the quick nav extras — chip filter, stat strip, sort pill. */
import { useState } from 'react';
import { ChipFilter, SortPill, StatStrip } from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

export function NavExtrasBlocks() {
  const tr = useTr();
  const [tag, setTag] = useState('all');
  const [sort, setSort] = useState('featured');

  return (
    <Section
      id="nav-extras-blocks"
      eyebrow={tr('بلوک‌ها · ناوبریِ تکمیلی', 'Blocks · Navigation extras')}
      title={tr('کنترل‌های کوچک، حرکت‌های دقیق', 'Small controls, exact motion')}
      description={tr(
        'ردیف چیپ‌های فیلتر با فنرِ زیرِ انتخاب، نوار آماری که اعدادش از صفر می‌غلتند و قرص مرتب‌سازی که برچسبش داخل تریگر می‌چرخد — هر سه با اعداد فارسی و آینه‌شدن راست‌به‌چپ.',
        'A row of filter chips with a spring under the pick, a stat strip whose figures roll up from zero, and a sort pill whose label rolls inside the trigger — all locale-aware and mirrored for right-to-left.',
      )}
      code={{
        react: `<ChipFilter items={[{ value: 'all', label: 'All' }, { value: 'landing', label: 'Landing', count: 320 }]} value={tag} onValueChange={setTag} />
<StatStrip stats={[{ label: 'Designs', value: 2400, icon: 'layers' }, { label: 'Platforms', value: 5 }]} />
<SortPill options={[{ value: 'featured', label: 'Featured', icon: 'star' }]} value={sort} onValueChange={setSort} />`,
        blade: `<x-nx::chip-filter :options="['all' => 'همه', 'landing' => ['label' => 'لندینگ', 'icon' => 'globe']]" :counts="['landing' => 320]" x-on:nx-change="…" />
<x-nx::stat-strip :stats="[['label' => 'طرح‌ها', 'value' => 2400, 'icon' => 'layers']]" />
<x-nx::sort-pill :options="['featured' => ['label' => 'منتخب', 'icon' => 'star']]" wire:model.live="sort" />`,
      }}
    >
      <div className="sc-demos">
        <Demo title={tr('فیلتر چیپی — تگ‌های ترند لندینگ', 'Chip filter — trending landing tags')} center>
          <ChipFilter
            aria-label={tr('فیلتر تگ‌ها', 'Tag filter')}
            value={tag}
            onValueChange={setTag}
            items={[
              { value: 'all', label: tr('همه', 'All') },
              { value: 'landing', label: tr('لندینگ', 'Landing'), icon: 'globe', count: 320 },
              { value: 'dashboard', label: tr('داشبورد', 'Dashboard'), icon: 'grid', count: 214 },
              { value: 'shop', label: tr('فروشگاه', 'Shop'), icon: 'layers', count: 96 },
              { value: 'portfolio', label: tr('پورتفولیو', 'Portfolio'), icon: 'image', count: 58 },
              { value: 'blog', label: tr('بلاگ', 'Blog'), icon: 'edit', count: 41 },
            ]}
          />
          <p className="sc-demo-title">
            {tr('برچسب فعال:', 'Active tag:')} <strong>{tag}</strong>
          </p>
        </Demo>

        <Demo title={tr('نوار آمار', 'Stat strip')} center>
          <StatStrip
            aria-label={tr('آمار پلتفرم', 'Platform stats')}
            stats={[
              { label: tr('طرح‌ها', 'Designs'), value: 2400, icon: 'layers', caption: tr('+۱۲۰ این ماه', '+120 this month') },
              { label: tr('طراحان', 'Designers'), value: 1400, icon: 'users' },
              { label: tr('دسته‌ها', 'Categories'), value: 40, icon: 'grid' },
              { label: tr('پلتفرم‌ها', 'Platforms'), value: 5, icon: 'globe' },
            ]}
          />
          <p className="sc-demo-title">{tr('اعداد تا رسیدن به دید، صفر می‌مانند و یک‌بار می‌غلتند.', 'Figures hold at zero until seen, then roll up once.')}</p>
        </Demo>

        <Demo title={tr('قرص مرتب‌سازی', 'Sort pill')} center>
          <SortPill
            label={tr('مرتب‌سازی', 'Sort')}
            value={sort}
            onValueChange={setSort}
            options={[
              { value: 'featured', label: tr('منتخب', 'Featured'), icon: 'star' },
              { value: 'recent', label: tr('تازه‌ها', 'Recent'), icon: 'sparkles' },
              { value: 'top', label: tr('بالاترین امتیاز', 'Top rated'), icon: 'trend-up' },
            ]}
          />
          <p className="sc-demo-title">
            {tr('مرتب بر اساس:', 'Sorted by:')} <strong>{sort}</strong>
          </p>
        </Demo>
      </div>
    </Section>
  );
}
