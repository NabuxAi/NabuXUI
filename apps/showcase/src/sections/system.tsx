import { useState } from 'react';
import {
  Accordion,
  Alert,
  AvatarGroup,
  Badge,
  Breadcrumbs,
  Button,
  CuneiformLoader,
  Dialog,
  Dock,
  DotsLoader,
  Drawer,
  Kbd,
  LikeButton,
  Menu,
  NavMenu,
  OrbitLoader,
  Pagination,
  Popover,
  Progress,
  Rating,
  RouteProgress,
  SegmentedControl,
  Skeleton,
  Spinner,
  Steps,
  Switch,
  Tabs,
  ThemeToggle,
  Tooltip,
  toast,
  transition,
} from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

export function NotificationsSection() {
  const tr = useTr();

  return (
    <Section
      id="notifications"
      eyebrow={tr('اعلان', 'Notification')}
      title={tr('پیام‌هایی که مزاحم نمی‌شوند', 'Messages that never get in the way')}
      description={tr(
        'اعلان‌ها روی هم چیده می‌شوند، با بردن نشانگر باز می‌شوند و با کشیدن کنار می‌روند. تا وقتی نشانگر روی آن‌هاست، زمان‌سنجشان می‌ایستد.',
        'Toasts stack, fan out when hovered and swipe away. Their timers pause while you are reading them.',
      )}
      code={{
        react: `<Toaster />  // once, near the root

toast.success('Saved', { description: 'Your agent is live.' });
toast.error('Payment failed', { action: { label: 'Retry', onClick: retry } });`,
        blade: `{{-- once, in the layout --}}
<x-nx::toaster />

// In a Livewire component (use NabuXUI\\Livewire\\WithToasts):
$this->toast('ذخیره شد', 'ایجنت شما فعال است.', tone: 'success');

// Or after a redirect:
NabuXUI::flashToast('خوش آمدید', tone: 'accent');`,
        inertia: `// Server: Inertia::flash('toast', ['title' => 'Saved', 'tone' => 'success']);
// Client: flash toasts appear by themselves once <InertiaToaster /> is mounted.
<InertiaToaster position="bottom-end" />`,
      }}
    >
      <div className="sc-demos">
        <Demo title={tr('اعلان‌ها', 'Toasts')}>
          <div className="sc-row">
            <Button variant="primary" icon="check-circle" onClick={() => toast.success(tr('ذخیره شد', 'Saved'), { description: tr('ایجنت پشتیبانی شما فعال است.', 'Your support agent is live.') })}>
              {tr('موفقیت', 'Success')}
            </Button>
            <Button icon="info" onClick={() => toast.info(tr('نسخهٔ تازه آماده است', 'A new version is ready'), { action: { label: tr('بارگذاری دوباره', 'Reload'), onClick: () => undefined } })}>
              {tr('اطلاع', 'Info')}
            </Button>
            <Button icon="alert-triangle" onClick={() => toast.warning(tr('۸۰٪ سهمیه مصرف شده', '80% of quota used'))}>
              {tr('هشدار', 'Warning')}
            </Button>
            <Button variant="danger" icon="alert-circle" onClick={() => toast.error(tr('پرداخت ناموفق بود', 'Payment failed'), { description: tr('کارت شما رد شد. دوباره امتحان کنید.', 'Your card was declined. Try again.') })}>
              {tr('خطا', 'Error')}
            </Button>
          </div>
          <p className="sc-demo-title">{tr('چند بار بزنید، بعد نشانگر را روی دسته ببرید.', 'Press a few times, then hover the stack.')}</p>
        </Demo>
        <Demo title={tr('هشدار درون صفحه', 'Inline alerts')}>
          <Alert tone="info" title={tr('به‌روزرسانی برنامه‌ریزی‌شده', 'Scheduled maintenance')} dismissible>
            {tr('جمعه ساعت ۲ بامداد، سرویس ۱۰ دقیقه در دسترس نیست.', 'Friday 2 AM, the service is down for 10 minutes.')}
          </Alert>
          <Alert tone="success" variant="accent-bar" title={tr('دامنه متصل شد', 'Domain connected')} />
          <Alert tone="danger" variant="outline" title={tr('کلید API منقضی شده است', 'API key expired')} actions={<Button size="xs" variant="danger">{tr('ساخت کلید تازه', 'Create a new key')}</Button>} dismissible />
        </Demo>
        <Demo title={tr('نشان‌ها و راهنما', 'Badges and tooltips')}>
          <div className="sc-row">
            <Badge>{tr('پیش‌نویس', 'Draft')}</Badge>
            <Badge tone="accent">{tr('تازه', 'New')}</Badge>
            <Badge tone="success" pulse>
              {tr('آنلاین', 'Online')}
            </Badge>
            <Badge tone="warning" variant="outline">
              {tr('در انتظار', 'Pending')}
            </Badge>
            <Badge tone="danger" variant="solid">
              {tr('قطع', 'Down')}
            </Badge>
            <Badge tone="gold" variant="solid" size="lg">
              PRO
            </Badge>
          </div>
          <div className="sc-row">
            <Tooltip content={tr('کپی نشانی API', 'Copy the API URL')}>
              <Button size="sm" icon="copy">
                {tr('بالا', 'Top')}
              </Button>
            </Tooltip>
            <Tooltip content={tr('اینجا هم کار می‌کند', 'Works down here too')} side="bottom">
              <Button size="sm">{tr('پایین', 'Bottom')}</Button>
            </Tooltip>
            <Tooltip content={tr('ابتدای خط', 'Toward the start')} side="start">
              <Button size="sm">{tr('ابتدا', 'Start')}</Button>
            </Tooltip>
          </div>
        </Demo>
      </div>
    </Section>
  );
}

export function MicroSection() {
  const tr = useTr();
  const [rating, setRating] = useState(4);

  return (
    <Section
      id="micro"
      eyebrow={tr('ریزتعامل', 'Micro-interaction')}
      title={tr('جزئیات کوچک، حس بزرگ', 'Small details, big feel')}
      description={tr('پسندیدن با پاشش ذرات، تغییر تم با طلوع ماه، امتیازدهی با پیش‌نمایش و آواتارهایی که کنار هم می‌نشینند.', 'Likes that burst, a theme switch where the moon rises, ratings that preview and avatars that sit together.')}
      code={{
        react: `<ThemeToggle />
<Rating value={rating} onValueChange={setRating} />
<AvatarGroup people={team} max={4} />
<Kbd>⌘</Kbd><Kbd>K</Kbd>`,
        blade: `<x-nx::theme-toggle />
<x-nx::rating wire:model.live="rating" />
<x-nx::avatar-group :people="$team" max="4" />
<x-nx::kbd>⌘</x-nx::kbd><x-nx::kbd>K</x-nx::kbd>`,
      }}
    >
      <div className="sc-demos">
        <Demo title={tr('تم و پسندیدن', 'Theme and like')} center>
          <div className="sc-row">
            <ThemeToggle />
            <LikeButton count={12} />
            <Switch label={tr('اعلان صوتی', 'Sound')} defaultChecked />
          </div>
        </Demo>
        <Demo title={tr('امتیاز', 'Rating')} center>
          <Rating value={rating} onValueChange={setRating} label={tr('امتیاز شما به پاسخ', 'Rate this answer')} size="2rem" />
          <p className="sc-demo-title">{tr(`امتیاز فعلی: ${rating.toLocaleString('fa-IR')} از ۵`, `Current: ${rating} of 5`)}</p>
        </Demo>
        <Demo title={tr('آواتارها و کلیدها', 'Avatars and keys')} center>
          <AvatarGroup
            label={tr('تیم محصول', 'Product team')}
            max={4}
            people={[
              { name: tr('هانیه رضایی', 'Haniyeh Rezaei') },
              { name: tr('آرش کریمی', 'Arash Karimi') },
              { name: tr('نیلوفر احمدی', 'Niloufar Ahmadi') },
              { name: tr('کاوه مرادی', 'Kaveh Moradi') },
              { name: tr('سارا نوری', 'Sara Nouri') },
              { name: tr('پویا شریفی', 'Pouya Sharifi') },
            ]}
          />
          <div className="sc-row" dir="ltr">
            <Kbd>⌘</Kbd>
            <Kbd>K</Kbd>
            <span className="sc-demo-title">{tr('جست‌وجو', 'Search')}</span>
          </div>
        </Demo>
      </div>
    </Section>
  );
}

const PAGES = [
  { fa: 'داشبورد', en: 'Dashboard', icon: 'grid' },
  { fa: 'گفت‌وگوها', en: 'Conversations', icon: 'message' },
  { fa: 'گزارش‌ها', en: 'Reports', icon: 'chart' },
] as const;

export function TransitionsSection() {
  const tr = useTr();
  const [page, setPage] = useState(0);
  const [preset, setPreset] = useState('slide');
  const [progress, setProgress] = useState<'idle' | 'loading' | 'done'>('idle');

  const go = (next: number) => {
    if (next === page) return;
    document.documentElement.dataset.scPreset = preset;
    setProgress('loading');
    setTimeout(() => {
      transition(() => setPage(next), { types: [next > page ? 'forward' : 'back'] });
      setProgress('done');
      setTimeout(() => setProgress('idle'), 700);
    }, 450);
  };

  return (
    <Section
      id="transitions"
      eyebrow={tr('ترنزیشن صفحه', 'Page transition')}
      title={tr('جابه‌جایی نرم بین صفحه‌ها', 'Smooth moves between pages')}
      description={tr(
        'با View Transitions خود مرورگر؛ در Livewire با wire:navigate و در Inertia با router. جهت حرکت با راست‌چین بودن صفحه هماهنگ است و کسی که «حرکت کمتر» خواسته، فقط محوشدن می‌بیند.',
        'Built on the browser’s own View Transitions: wire:navigate in Livewire, the router in Inertia. Direction follows the page’s writing direction, and reduced-motion readers get a plain fade.',
      )}
      code={{
        react: `import { transition } from '@nabuxai/ui-react';

transition(() => setPage(next), { types: ['forward'] });
<main className="nx-page">…</main>   // html[data-nx-transition="slide"]`,
        blade: `{{-- Livewire 4 --}}
<main class="nx-page" wire:transition.navigate="nx-page">{{ $slot }}</main>
<a href="/reports" wire:navigate>گزارش‌ها</a>

{{-- Livewire 3: the same markup; NabuXUI animates the new page in. --}}
<x-nx::route-progress />`,
        inertia: `createInertiaApp({
  defaults: { visitOptions: nabuxVisitOptions() }, // view transitions on every visit
  progress: false,
  setup({ el, App, props }) {
    createRoot(el).render(<InertiaNabuXUI><App {...props} /></InertiaNabuXUI>);
  },
});`,
      }}
    >
      <RouteProgress state={progress} />
      <div className="sc-demos">
        <Demo wide>
          <div className="sc-row" style={{ justifyContent: 'space-between' }}>
            <SegmentedControl
              aria-label={tr('صفحه', 'Page')}
              value={String(page)}
              onValueChange={(value) => go(Number(value))}
              options={PAGES.map((p, i) => ({ value: String(i), label: tr(p.fa, p.en), icon: p.icon }))}
            />
            <SegmentedControl
              size="sm"
              tone="accent"
              aria-label={tr('نوع حرکت', 'Preset')}
              value={preset}
              onValueChange={setPreset}
              options={[
                { value: 'fade', label: tr('محو', 'Fade') },
                { value: 'rise', label: tr('بالا آمدن', 'Rise') },
                { value: 'slide', label: tr('سُر خوردن', 'Slide') },
                { value: 'zoom', label: tr('بزرگ‌نمایی', 'Zoom') },
              ]}
            />
          </div>
          <div className="sc-page-demo" aria-live="polite">
            <Badge tone="accent">{tr(`صفحهٔ ${(page + 1).toLocaleString('fa-IR')}`, `Page ${page + 1}`)}</Badge>
            <h3 style={{ margin: 0, fontSize: 'var(--nx-text-2xl)' }}>{tr(PAGES[page]!.fa, PAGES[page]!.en)}</h3>
            <Skeleton lines={3} />
          </div>
        </Demo>
      </div>
    </Section>
  );
}

export function LoadingSection() {
  const tr = useTr();
  const [value, setValue] = useState(64);

  return (
    <Section
      id="loading"
      eyebrow={tr('بارگذاری', 'Loading')}
      title={tr('انتظار، به زبان Nabux', 'Waiting, the Nabux way')}
      description={tr('چرخنده، مدار، سه‌نقطهٔ «در حال فکر» و میخ‌هایی که یکی‌یکی روی گل نقش می‌بندند؛ همه با برچسب برای صفحه‌خوان.', 'A spinner, an orbit, the “thinking” dots and cuneiform wedges pressed into clay one by one, all labelled for screen readers.')}
      code={{
        react: `<Spinner />  <OrbitLoader />  <DotsLoader />  <CuneiformLoader />
<Skeleton lines={3} />
<Progress value={64} />`,
        blade: `<x-nx::spinner />  <x-nx::loader.orbit />  <x-nx::loader.dots />  <x-nx::loader.cuneiform />
<x-nx::skeleton lines="3" />
<x-nx::progress :value="$percent" />`,
      }}
    >
      <div className="sc-demos">
        <Demo title={tr('نشانگرهای انتظار', 'Loaders')} center>
          <div className="sc-row" style={{ gap: 'var(--nx-space-8)' }}>
            <Spinner tone="accent" size="lg" />
            <OrbitLoader />
            <DotsLoader />
            <CuneiformLoader />
          </div>
        </Demo>
        <Demo title={tr('اسکلت محتوا', 'Skeleton')}>
          <div className="sc-row" style={{ flexWrap: 'nowrap' }}>
            <Skeleton shape="circle" width="3rem" />
            <div style={{ flex: 1 }}>
              <Skeleton lines={2} />
            </div>
          </div>
          <Skeleton shape="rect" height="7rem" />
        </Demo>
        <Demo title={tr('نوار پیشرفت', 'Progress')}>
          <Progress value={value} label={tr('پیشرفت آپلود', 'Upload progress')} />
          <Progress label={tr('در حال پردازش', 'Processing')} tone="brand" />
          <div className="sc-row">
            <Button size="sm" onClick={() => setValue((v) => Math.min(100, v + 12))}>
              +12%
            </Button>
            <Button size="sm" variant="ghost" onClick={() => setValue(8)}>
              {tr('از نو', 'Reset')}
            </Button>
          </div>
        </Demo>
      </div>
    </Section>
  );
}

export function NavigationSection() {
  const tr = useTr();
  const [page, setPage] = useState(4);
  const [dialog, setDialog] = useState(false);
  const [drawer, setDrawer] = useState(false);

  return (
    <Section
      id="navigation"
      eyebrow={tr('ناوبری', 'Navigation')}
      title={tr('نشانگری که دنبالتان می‌آید', 'A highlight that follows you')}
      description={tr(
        'تب‌ها، کنترل بخش‌بندی، منو با قرص متحرک، داک، صفحه‌بندی، آکاردئون، منوی کشویی و پنجره‌ها. کلیدهای جهت‌نما در راست‌چین هم درست کار می‌کنند.',
        'Tabs, segmented controls, a menu with a sliding pill, a dock, pagination, accordions, dropdowns and dialogs. Arrow keys behave correctly in right-to-left pages too.',
      )}
      code={{
        react: `<Tabs items={[{ value: 'chat', label: 'Chat', content: … }]} />
<NavMenu items={links} />
<Dock items={apps} variant="metal" />
<Menu trigger={<Button>Actions</Button>} items={[{ label: 'Rename', icon: 'edit' }]} />
<Dialog trigger={<Button>Open</Button>} title="…">…</Dialog>`,
        blade: `<x-nx::tabs :items="['chat' => 'گفت‌وگو', 'files' => 'فایل‌ها']">
  <x-slot:chat>…</x-slot:chat>
  <x-slot:files>…</x-slot:files>
</x-nx::tabs>
<x-nx::dock :items="$apps" variant="metal" />
<x-nx::menu :items="$actions"><x-slot:trigger>…</x-slot:trigger></x-nx::menu>
<x-nx::dialog name="invite" title="دعوت هم‌تیمی">…</x-nx::dialog>
<x-nx::button x-on:click="$dispatch('nx-open', 'invite')">دعوت</x-nx::button>`,
      }}
    >
      <div className="sc-demos">
        <Demo title={tr('تب‌ها', 'Tabs')} wide>
          <Tabs
            aria-label={tr('بخش‌های ایجنت', 'Agent sections')}
            items={[
              { value: 'chat', label: tr('گفت‌وگو', 'Chat'), icon: 'message', content: <p style={{ margin: 0 }}>{tr('همهٔ پیام‌های امروز این‌جاست.', 'Today’s messages live here.')}</p> },
              { value: 'files', label: tr('فایل‌ها', 'Files'), icon: 'file', badge: <Badge tone="accent">۱۲</Badge>, content: <p style={{ margin: 0 }}>{tr('۱۲ سند به دانش ایجنت اضافه شده است.', '12 documents feed this agent.')}</p> },
              { value: 'settings', label: tr('تنظیمات', 'Settings'), icon: 'sliders', content: <p style={{ margin: 0 }}>{tr('لحن، زبان و ساعات پاسخ‌گویی.', 'Tone, language and working hours.')}</p> },
            ]}
          />
          <Tabs
            variant="underline"
            aria-label={tr('بازه', 'Range')}
            items={[
              { value: '7', label: tr('۷ روز', '7 days'), content: null },
              { value: '30', label: tr('۳۰ روز', '30 days'), content: null },
              { value: '90', label: tr('۹۰ روز', '90 days'), content: null },
            ]}
          />
        </Demo>
        <Demo title={tr('منوی متحرک و مسیر', 'Nav menu and breadcrumbs')}>
          <NavMenu
            aria-label={tr('نمونهٔ منو', 'Sample menu')}
            items={[
              { href: '#navigation', label: tr('محصولات', 'Products'), current: true },
              { href: '#pricing', label: tr('قیمت‌ها', 'Pricing') },
              { href: '#cards', label: tr('مشتریان', 'Customers') },
              { href: '#text', label: tr('بلاگ', 'Blog') },
            ]}
          />
          <Breadcrumbs items={[{ label: tr('خانه', 'Home'), href: '#' }, { label: tr('ایجنت‌ها', 'Agents'), href: '#' }, { label: tr('پشتیبانی فروش', 'Sales support') }]} />
        </Demo>
        <Demo title={tr('داک', 'Dock')} center>
          <Dock
            aria-label={tr('برنامه‌ها', 'Apps')}
            items={[
              { label: tr('خانه', 'Home'), icon: 'home', href: '#', current: true },
              { label: tr('گفت‌وگو', 'Chat'), icon: 'message', href: '#' },
              { label: tr('گزارش', 'Reports'), icon: 'chart', href: '#' },
              { label: tr('تیم', 'Team'), icon: 'users', href: '#' },
              { label: tr('تنظیمات', 'Settings'), icon: 'sliders', href: '#' },
            ]}
          />
          <Dock
            variant="metal"
            aria-label={tr('ابزارها', 'Tools')}
            items={[
              { label: tr('نوشتن', 'Write'), icon: 'edit', onClick: () => toast(tr('نوشتن', 'Write')) },
              { label: tr('جادو', 'Magic'), icon: 'wand', onClick: () => toast(tr('جادو', 'Magic')) },
              { label: tr('امنیت', 'Security'), icon: 'shield', onClick: () => toast(tr('امنیت', 'Security')) },
            ]}
          />
        </Demo>
        <Demo title={tr('صفحه‌بندی و گام‌ها', 'Pagination and steps')}>
          <Pagination page={page} pageCount={20} onPageChange={setPage} />
          <Steps
            current={1}
            aria-label={tr('راه‌اندازی', 'Setup')}
            steps={[
              { title: tr('اتصال کانال', 'Connect'), description: tr('تلگرام', 'Telegram') },
              { title: tr('آموزش ایجنت', 'Train'), description: tr('اسناد شما', 'Your docs') },
              { title: tr('انتشار', 'Launch') },
            ]}
          />
        </Demo>
        <Demo title={tr('آکاردئون', 'Accordion')}>
          <Accordion
            defaultOpen={[0]}
            items={[
              { title: tr('داده‌های من کجا ذخیره می‌شود؟', 'Where is my data stored?'), content: tr('روی سرورهای خودتان یا دیتاسنتر Nabux در ایران؛ انتخاب با شماست.', 'On your own servers or in the Nabux data centre, your choice.') },
              { title: tr('کدام مدل‌ها پشتیبانی می‌شوند؟', 'Which models are supported?'), content: tr('هر مدلی که NabuGate به آن وصل است، از جمله مدل‌های متن‌باز.', 'Any model NabuGate connects to, open-weight ones included.') },
              { title: tr('می‌توانم ایجنت را روی سایتم بگذارم؟', 'Can I put the agent on my site?'), content: tr('بله، با یک خط کد یا افزونهٔ وردپرس.', 'Yes, with one line of code or the WordPress plugin.') },
            ]}
          />
        </Demo>
        <Demo title={tr('منو، پنجره و کشو', 'Menu, dialog and drawer')}>
          <div className="sc-row">
            <Menu
              trigger={<Button iconEnd="chevron-down">{tr('عملیات', 'Actions')}</Button>}
              items={[
                { type: 'label', label: tr('ایجنت', 'Agent') },
                { label: tr('تغییر نام', 'Rename'), icon: 'edit', shortcut: 'R', onSelect: () => toast(tr('تغییر نام', 'Rename')) },
                { label: tr('کپی تنظیمات', 'Duplicate'), icon: 'copy', shortcut: 'D' },
                { label: tr('صدای پاسخ', 'Voice replies'), icon: 'mic', checked: true },
                { type: 'separator' },
                { label: tr('حذف ایجنت', 'Delete agent'), icon: 'trash', tone: 'danger' },
              ]}
            />
            <Button variant="primary" onClick={() => setDialog(true)}>
              {tr('پنجره', 'Dialog')}
            </Button>
            <Button onClick={() => setDrawer(true)}>{tr('کشو', 'Drawer')}</Button>
            <Popover trigger={<Button variant="ghost" icon="info">{tr('توضیح', 'Details')}</Button>} label={tr('توضیح', 'Details')}>
              <p style={{ margin: 0, maxInlineSize: '16rem' }}>{tr('پاپ‌اور در لایهٔ بالای صفحه باز می‌شود و با کلیک بیرون بسته می‌شود.', 'The popover opens in the top layer and closes when you click outside.')}</p>
            </Popover>
          </div>
          <Dialog
            open={dialog}
            onOpenChange={setDialog}
            title={tr('دعوت هم‌تیمی', 'Invite a teammate')}
            description={tr('دعوت‌نامه با ایمیل ارسال می‌شود و ۷ روز اعتبار دارد.', 'The invite goes out by email and is valid for 7 days.')}
            footer={
              <>
                <Button variant="ghost" onClick={() => setDialog(false)}>
                  {tr('انصراف', 'Cancel')}
                </Button>
                <Button
                  variant="primary"
                  onClick={() => {
                    setDialog(false);
                    toast.success(tr('دعوت‌نامه ارسال شد', 'Invite sent'));
                  }}
                >
                  {tr('ارسال دعوت', 'Send invite')}
                </Button>
              </>
            }
          >
            <p style={{ margin: 0, color: 'var(--nx-text-muted)' }}>{tr('هم‌تیمی‌ها می‌توانند گفت‌وگوها را ببینند و به آن‌ها پاسخ دهند.', 'Teammates can see and answer conversations.')}</p>
          </Dialog>
          <Drawer open={drawer} onOpenChange={setDrawer} title={tr('جزئیات گفت‌وگو', 'Conversation details')}>
            <Steps
              orientation="vertical"
              current={2}
              steps={[
                { title: tr('پیام کاربر', 'User message'), description: '۱۰:۴۲' },
                { title: tr('پاسخ ایجنت', 'Agent reply'), description: '۱۰:۴۲' },
                { title: tr('ارجاع به کارشناس', 'Handed to a human'), description: '۱۰:۴۵' },
              ]}
            />
          </Drawer>
        </Demo>
      </div>
    </Section>
  );
}
