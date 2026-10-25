import { useState } from 'react';
import { AppWindow, type AppMetric, type AppNavItem, SegmentedControl } from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

/** The four shells, in switcher order. */
const SHELLS = ['windows', 'macos', 'android', 'ios'] as const;
type Shell = (typeof SHELLS)[number];

const SHELL_NAMES: Record<Shell, { fa: string; en: string }> = {
  windows: { fa: 'ویندوز', en: 'Windows' },
  macos: { fa: 'مک', en: 'macOS' },
  android: { fa: 'اندروید', en: 'Android' },
  ios: { fa: 'iOS', en: 'iOS' },
};

/** One sample app, shared by every shell: the same props and content ride all four windows. */
function useSampleApp() {
  const tr = useTr();
  const nav: AppNavItem[] = [
    { id: 'home', label: tr('خانه', 'Home'), icon: 'home' },
    { id: 'files', label: tr('فایل‌ها', 'Files'), icon: 'folder', badge: 3 },
    { id: 'reports', label: tr('گزارش‌ها', 'Reports'), icon: 'chart' },
    { id: 'settings', label: tr('تنظیمات', 'Settings'), icon: 'settings' },
  ];
  const metrics: AppMetric[] = [
    { label: tr('بازدید امروز', 'Visits today'), value: tr('۱۲٬۴۸۰', '12,480'), delta: tr('+۴٫۲٪', '+4.2%'), trend: 'up' },
    { label: tr('سفارش‌ها', 'Orders'), value: tr('۱٬۱۳۲', '1,132'), delta: tr('−۰٫۸٪', '−0.8%'), trend: 'down' },
    { label: tr('درآمد', 'Revenue'), value: tr('۸۴٫۶م', '84.6M') },
  ];
  const rows = [
    { name: tr('سفارش #۸۴۱۲ — سارا رضایی', 'Order #8412 — Sara Rezaei'), note: tr('در حال بسته‌بندی', 'Packing'), tone: 'up' },
    { name: tr('سفارش #۸۴۱۱ — امیر کاظمی', 'Order #8411 — Amir Kazemi'), note: tr('ارسال شد', 'Shipped'), tone: 'up' },
    { name: tr('سفارش #۸۴۱۰ — نگار موسوی', 'Order #8410 — Negar Mousavi'), note: tr('در انتظار پرداخت', 'Awaiting payment'), tone: 'down' },
  ];
  return { nav, metrics, rows, brand: 'Nabu Desk' };
}

/** The window's inner app: a light static list — deliberately not another nx block. */
function AppRows({ rows }: { rows: Array<{ name: string; note: string; tone: string }> }) {
  return (
    <ul style={{ display: 'grid', gap: 'var(--nx-space-2)', margin: 0, padding: 0, listStyle: 'none' }}>
      {rows.map((row) => (
        <li
          key={row.name}
          style={{
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
            gap: 'var(--nx-space-2)',
            padding: 'var(--nx-space-2) var(--nx-space-3)',
            border: '1px solid var(--nx-border)',
            borderRadius: 'var(--nx-radius-md)',
            background: 'var(--nx-surface)',
          }}
        >
          <span style={{ fontSize: 'var(--nx-text-sm)', overflow: 'hidden', textOverflow: 'ellipsis', whiteSpace: 'nowrap' }}>{row.name}</span>
          <span style={{ fontSize: 'var(--nx-text-xs)', color: row.tone === 'up' ? 'var(--nx-success)' : 'var(--nx-warning)', flexShrink: 0 }}>{row.note}</span>
        </li>
      ))}
    </ul>
  );
}

/** All four shells side by side over one DOM — the block's whole promise in one glance. */
function AllShellsDemo() {
  const tr = useTr();
  const app = useSampleApp();
  return (
    <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(min(100%, 22rem), 1fr))', gap: 'var(--nx-space-4)' }}>
      {SHELLS.map((shell) => (
        <figure key={shell} style={{ display: 'grid', gap: 'var(--nx-space-2)', margin: 0 }}>
          <AppWindow
            platform={shell}
            brand={app.brand}
            title={tr('میز کار', 'Desk')}
            subtitle={tr('امروز', 'Today')}
            icon="grid"
            nav={app.nav}
            metrics={app.metrics}
            height="24rem"
            navLabel={tr('بخش‌های میز کار', 'Desk sections')}
            statusbar={tr('۳ سفارش در جریان', '3 orders in progress')}
            fab={shell === 'android' ? true : undefined}
            fabLabel={tr('سفارش تازه', 'New order')}
          >
            <AppRows rows={app.rows} />
          </AppWindow>
          <figcaption style={{ fontSize: 'var(--nx-text-xs)', color: 'var(--nx-text-muted)', textAlign: 'center' }}>
            data-platform=&quot;{shell}&quot;
          </figcaption>
        </figure>
      ))}
    </div>
  );
}

/** One window whose shell is swapped live from outside — the controlled `platform` prop. */
function SwitcherDemo() {
  const tr = useTr();
  const app = useSampleApp();
  const [shell, setShell] = useState<Shell>('windows');
  return (
    <div style={{ display: 'grid', gap: 'var(--nx-space-3)', justifyItems: 'stretch' }}>
      <div style={{ display: 'flex', justifyContent: 'center' }}>
        <SegmentedControl
          size="sm"
          aria-label={tr('پوستهٔ پنجره', 'Window shell')}
          value={shell}
          onValueChange={(value) => setShell(value as Shell)}
          options={SHELLS.map((option) => ({ value: option, label: SHELL_NAMES[option].en }))}
        />
      </div>
      <AppWindow
        platform={shell}
        brand={app.brand}
        title={tr('میز کار', 'Desk')}
        subtitle={tr('امروز', 'Today')}
        icon="grid"
        nav={app.nav}
        metrics={app.metrics}
        height="26rem"
        navLabel={tr('بخش‌های میز کار', 'Desk sections')}
        statusbar={tr('۳ سفارش در جریان', '3 orders in progress')}
        fab={shell === 'android' ? true : undefined}
        fabLabel={tr('سفارش تازه', 'New order')}
      >
        <AppRows rows={app.rows} />
      </AppWindow>
    </div>
  );
}

export function PlatformBlocks() {
  const tr = useTr();
  return (
    <Section
      id="platform-blocks"
      eyebrow={tr('بلوک‌ها', 'Blocks')}
      title={tr('پنجرهٔ اپ پلتفرمی', 'Platform app window')}
      description={tr(
        'یک فریم اپ، چهار پوستهٔ بومی: ویندوز (کپشن + منوی اسنپ با نگه‌داشتنِ بزرگ‌کردن)، مک (چراغ‌های دایره‌ای و سایدبار vibrant)، اندروید (تب‌بار و FAB) و iOS (تیتر بزرگ و تب‌بار شیشه‌ای). data-platform روی خودِ پنجره می‌نشیند، پس هر چهار پوسته کنار هم در یک صفحه live سوییچ می‌شوند؛ کمینه/بیشینه واقعی است و بستن رویداد می‌دهد.',
        'One app frame, four native shells: Windows (caption + the snap-layouts flyout behind a held maximize), macOS (round traffic lights, vibrant sidebar), Android (tab bar + FAB) and iOS (large title, glass tab bar). data-platform lives on the window itself, so all four shells sit side by side on one page and swap live; minimize/maximize are real and close fires an event.',
      )}
      code={{
        react: `<AppWindow
  platform="windows"
  brand="Nabu Desk" title="Desk" icon="grid"
  nav={[
    { id: 'home', label: 'Home', icon: 'home' },
    { id: 'files', label: 'Files', icon: 'folder', badge: 3 },
  ]}
  metrics={[{ label: 'Visits', value: '12,480', delta: '+4.2%', trend: 'up' }]}
  statusbar="3 orders in progress"
  onPlatformChange={setShell}   // controlled: swap the shell from outside
>
  {/* the app's content */}
</AppWindow>`,
        blade: `<x-nx::app-window
    platform="windows" brand="Nabu Desk" title="Desk" icon="grid"
    :nav="[
        ['id' => 'home', 'label' => 'Home', 'icon' => 'home'],
        ['id' => 'files', 'label' => 'Files', 'icon' => 'folder', 'badge' => 3],
    ]"
    :metrics="[['label' => 'Visits', 'value' => '12,480', 'delta' => '+4.2%', 'trend' => 'up']]"
    statusbar="3 orders in progress"
>
    {{-- the app's content --}}
</x-nx::app-window>`,
      }}
    >
      <div className="sc-demos">
        <Demo title={tr('چهار پوسته، یک DOM', 'Four shells, one DOM')} wide bare>
          <AllShellsDemo />
        </Demo>
        <Demo title={tr('تعویض زندهٔ پوسته', 'Live shell switch')} wide>
          <SwitcherDemo />
        </Demo>
      </div>
    </Section>
  );
}
