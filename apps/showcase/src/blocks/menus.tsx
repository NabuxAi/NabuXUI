/** Showcase demos: Menus, navigation & morphing panels. */
import { type CSSProperties, useState } from 'react';
import {
  type ActivityItem,
  ActivityDropdown,
  AudioRoom,
  AvatarGroup,
  Button,
  DockPanels,
  FoldMenu,
  MemberSelector,
  MorphMenu,
  MorphTabs,
  RegistrationCard,
  StackMenu,
  StackedAccordion,
  VoiceRecorder,
  toast,
} from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

const PEOPLE = [
  { id: 'kenji', name: 'Kenji Sato', email: 'kenji@nabux.jp' },
  { id: 'maria', name: 'María López', email: 'maria@nabux.es' },
  { id: 'amara', name: 'Amara Okafor', email: 'amara@nabux.ng' },
  { id: 'omar', name: 'Omar Haddad', email: 'omar@nabux.ae' },
  { id: 'lena', name: 'Lena Fischer', email: 'lena@nabux.de' },
  { id: 'priya', name: 'Priya Nair', email: 'priya@nabux.in' },
  { id: 'jiwoo', name: '김지우', email: 'jiwoo@nabux.kr' },
  { id: 'wei', name: 'Zhang Wei', email: 'wei@nabux.cn' },
];

const minutes = (n: number) => Date.now() - n * 60_000;

const ACTIVITY: ActivityItem[] = [
  { id: 'a1', actor: { name: 'Amara Okafor' }, text: 'commented on', target: 'Q3 roadmap', time: minutes(3), unread: true },
  { id: 'a2', actor: { name: 'Kenji Sato' }, text: 'mentioned you in', target: '設計レビュー', time: minutes(42), unread: true },
  { id: 'a3', actor: { name: 'María López' }, text: 'shared', target: 'Informe de ventas', time: minutes(300), unread: true },
  { id: 'a4', actor: { name: 'Omar Haddad' }, text: 'approved', target: 'خطة الإطلاق', time: minutes(60 * 26) },
  { id: 'a5', actor: { name: 'Lena Fischer' }, text: 'joined the workspace', time: 'Last week' },
];

const ARRIVALS: Array<Pick<ActivityItem, 'actor' | 'text' | 'target'>> = [
  { actor: { name: 'Priya Nair' }, text: 'replied to', target: 'लॉन्च योजना' },
  { actor: { name: '김지우' }, text: 'reacted 🎉 to', target: 'Release notes' },
  { actor: { name: 'Zhang Wei' }, text: 'assigned you', target: '支持工单 #482' },
];

const tall: CSSProperties = { minBlockSize: '22rem', alignContent: 'start' };

export function MenusBlocks() {
  const tr = useTr();
  const [filters, setFilters] = useState<string[]>(['design']);
  const [tab, setTab] = useState('home');
  const [members, setMembers] = useState(['kenji', 'maria', 'amara']);
  const [roles, setRoles] = useState<Record<string, string>>({ kenji: 'admin', maria: 'editor', amara: 'viewer' });
  const [activity, setActivity] = useState(ACTIVITY);
  const [open, setOpen] = useState<string[]>(['shipping']);
  const [listeners, setListeners] = useState(1284);
  const [speaking, setSpeaking] = useState(0);

  const arrive = () => {
    const next = ARRIVALS[activity.length % ARRIVALS.length]!;
    setActivity((items) => [{ ...next, id: `n${items.length}-${Date.now()}`, time: Date.now(), unread: true }, ...items]);
  };

  const speakers = ['Kenji Sato', 'María López', 'Amara Okafor', 'Omar Haddad', 'Priya Nair', 'Zhang Wei'];

  return (
    <Section
      id="menus-blocks"
      eyebrow={tr('بلوک‌ها · منو و پنل‌های شکل‌پذیر', 'Blocks · Menus & morphing panels')}
      title={tr('سطح‌هایی که به پنل تبدیل می‌شوند', 'Surfaces that turn into panels')}
      description={tr(
        'منویی که مثل کاغذ تا باز می‌شود، داکی که خودش پنل می‌شود، دکمه‌ای که به فهرستش بدل می‌شود، منوی لایه‌لایه با جست‌وجو، اتاق صوتی شبیه جزیرهٔ پویا، اعلان‌ها، انتخاب اعضا، فرم ثبت‌نام و ضبط صدا — همه با فنر، در فارسی و عربی راست‌به‌چپ.',
        'A menu that unfolds like paper, a dock that becomes its panel, a button that grows into its list, nested menus with search, a dynamic-island audio room, activity, a member picker, a registration flow and a voice recorder — all on springs, and mirrored for right-to-left.',
      )}
      code={{
        react: `<FoldMenu sections={[{ title: 'Explore', links: [{ label: 'Home', href: '/', icon: 'home' }] }]} />
<DockPanels items={[{ id: 'music', label: 'Now playing', icon: 'play', content: <NowPlaying /> }]} />
<MorphMenu options={[{ value: 'design', label: 'Design' }]} value={filters} onValueChange={setFilters} />
<StackedAccordion type="single" items={[{ id: 'shipping', title: 'Shipping', content: '…' }]} />
<StackMenu title="Settings" items={tree} trigger={<Button icon="sliders">Settings</Button>} />
<MorphTabs items={[{ value: 'home', label: 'Home', icon: 'home' }]} value={tab} onValueChange={setTab} />
<AudioRoom title="Design crit" members={[{ name: 'Kenji Sato', speaking: true }]} listeners={1284} />
<ActivityDropdown items={activity} onMarkAllRead={markAllRead} />
<MemberSelector members={people} value={ids} onValueChange={setIds} roles={roles} onRolesChange={setRoles} />
<RegistrationCard event={{ title: 'Nabu Summit' }} tickets={tickets} currency="EUR" onSubmit={register} />
<VoiceRecorder level={micLevel} onStop={({ duration }) => save(duration)} />`,
        blade: `<x-nx::fold-menu :sections="[['title' => 'Explore', 'links' => [['label' => 'Home', 'href' => '/', 'icon' => 'home']]]]" />
<x-nx::dock-panels :items="[['id' => 'music', 'label' => 'Now playing', 'icon' => 'play']]"><x-slot:music>…</x-slot:music></x-nx::dock-panels>
<x-nx::morph-menu name="filters" :options="$options" wire:model.live="filters" />
<x-nx::stacked-accordion type="single" :items="$faq" wire:model.live="open" />
<x-nx::stack-menu title="Settings" :items="$tree" x-on:nx-select="$wire.choose($event.detail.id)">
    <x-slot:trigger><x-nx::button icon="sliders">Settings</x-nx::button></x-slot:trigger>
</x-nx::stack-menu>
<x-nx::morph-tabs :items="$tabs" wire:model.live="tab" />
<x-nx::audio-room title="Design crit" :members="$speakers" :listeners="$listeners" x-on:nx-room-leave="$wire.leave()" />
<x-nx::activity-dropdown :items="$activity" x-on:nx-mark-all-read="$wire.markAllRead()" />
<x-nx::member-selector :members="$people" wire:model.live="members" roles-model="roles" />
<x-nx::registration-card model="registration" wire:submit="register" :success="$registered" :event="$event" :tickets="$tickets" />
<x-nx::voice-recorder x-on:nx-record-stop="$wire.saveNote($event.detail.duration)" />`,
      }}
    >
      <div className="sc-demos">
        <Demo title={tr('منوی تاشو', 'Page fold menu')} center>
          <FoldMenu
            label="Menu · Menú · メニュー"
            sections={[
              {
                title: 'Explore · Explorar',
                links: [
                  { label: 'Home', href: '#menus-blocks', icon: 'home', current: true },
                  { label: 'Agents · エージェント', href: '#menus-blocks', icon: 'sparkles', description: 'Support that speaks every language' },
                  { label: 'Pricing · Tarifs', href: '#menus-blocks', icon: 'star' },
                ],
              },
              {
                title: 'Resources · Ressourcen',
                links: [
                  { label: 'Docs · 文档', href: '#menus-blocks', icon: 'file' },
                  { label: 'Changelog · سجل التغييرات', href: '#menus-blocks', icon: 'layers' },
                ],
              },
              {
                title: 'Company · कंपनी',
                links: [{ label: 'Say hi · 안녕하세요', icon: 'message', onSelect: () => toast.success('Hello · Hola · مرحبا') }],
              },
            ]}
            footer={
              <Button variant="primary" size="sm" block onClick={() => toast.success('Trial started · Prueba iniciada')}>
                Start free · Comenzar gratis
              </Button>
            }
          />
        </Demo>

        <Demo title={tr('فیلتر شکل‌پذیر', 'Morphing filter')}>
          <div style={tall}>
            <MorphMenu
              label="Filter · Filtro"
              name="filters"
              value={filters}
              onValueChange={setFilters}
              options={[
                { value: 'design', label: 'Design', icon: 'edit' },
                { value: 'engineering', label: 'Engineering · Ingeniería', icon: 'cpu' },
                { value: 'support', label: 'Support · Support client', icon: 'message' },
                { value: 'sales', label: 'Sales · Vertrieb', icon: 'chart' },
                { value: 'research', label: 'Research · 研究', icon: 'globe' },
              ]}
            />
            <p className="sc-demo-title" style={{ marginBlockStart: 'var(--nx-space-4)' }}>
              {filters.length ? filters.join(', ') : tr('بدون فیلتر', 'No filters')}
            </p>
          </div>
        </Demo>

        <Demo title={tr('زبانه‌های شکل‌پذیر', 'Morph tabs')}>
          <MorphTabs
            aria-label="Workspace"
            value={tab}
            onValueChange={setTab}
            items={[
              { value: 'home', label: 'Home', icon: 'home', content: <p style={{ margin: 0 }}>Welcome back — Bienvenido de nuevo.</p> },
              { value: 'inbox', label: 'Inbox · Bandeja', icon: 'mail', content: <p style={{ margin: 0 }}>3 new conversations · 3 nuevas conversaciones.</p> },
              { value: 'reports', label: 'Reports · 报告', icon: 'chart', content: <p style={{ margin: 0 }}>Resolution rate 94% · 解决率 94%</p> },
              { value: 'team', label: 'Team · فريق', icon: 'users', content: <p style={{ margin: 0 }}>8 teammates across 6 time zones.</p> },
            ]}
          />
        </Demo>

        <Demo title={tr('منوی لایه‌ای', 'Stack menu')} center>
          <StackMenu
            title="Settings"
            trigger={<Button icon="sliders">Settings · Ajustes</Button>}
            items={[
              {
                id: 'appearance',
                label: 'Appearance',
                icon: 'sun',
                children: [
                  {
                    id: 'theme',
                    label: 'Theme',
                    icon: 'moon',
                    children: [
                      { id: 'light', label: 'Light', onSelect: () => toast('Light') },
                      { id: 'dark', label: 'Dark', onSelect: () => toast('Dark') },
                      { id: 'system', label: 'System', onSelect: () => toast('System') },
                    ],
                  },
                  { id: 'density', label: 'Density', icon: 'sliders', shortcut: '⌘D', onSelect: () => toast('Compact') },
                ],
              },
              {
                id: 'language',
                label: 'Language · Idioma · 言語',
                icon: 'globe',
                children: ['English', 'Español', 'Français', 'Deutsch', '日本語', '中文', 'العربية', 'فارسی', 'हिन्दी', 'Português', '한국어', 'Türkçe'].map((name) => ({
                  id: name,
                  label: name,
                  onSelect: () => toast.success(name),
                })),
              },
              { id: 'notifications', label: 'Notifications', icon: 'bell', description: 'Email, push, digests', onSelect: () => toast('Notifications') },
              { id: 'signout', label: 'Sign out', icon: 'lock', tone: 'danger', onSelect: () => toast('Signed out · Sesión cerrada') },
            ]}
          />
        </Demo>

        <Demo title={tr('فعالیت‌ها', 'Activity')} center>
          <div className="sc-row">
            <ActivityDropdown items={activity} onMarkAllRead={() => setActivity((items) => items.map((item) => ({ ...item, unread: false })))} />
            <ActivityDropdown items={[]} title="Empty inbox" />
            <Button size="sm" icon="plus" onClick={arrive}>
              {tr('اعلان تازه', 'New activity')}
            </Button>
          </div>
        </Demo>

        <Demo title={tr('انتخاب اعضا', 'Member selector')} center>
          <MemberSelector members={PEOPLE} value={members} onValueChange={setMembers} roles={roles} onRolesChange={setRoles} max={4} />
          <p className="sc-demo-title">
            {members.map((id) => `${PEOPLE.find((p) => p.id === id)?.name} (${roles[id] ?? 'viewer'})`).join(' · ')}
          </p>
        </Demo>

        <Demo title={tr('داک با پنل', 'Dock with expanding panels')} wide>
          <div style={{ display: 'grid', placeItems: 'end center', minBlockSize: '22rem' }}>
            <DockPanels
              aria-label="Quick panels"
              items={[
                {
                  id: 'music',
                  label: 'Now playing',
                  icon: 'play',
                  content: (
                    <>
                      <h3 className="nx-dock-panels-heading">Now playing · Ahora suena</h3>
                      <p style={{ margin: 0, color: 'var(--nx-text-muted)' }}>Lo-fi beats for deep focus — 集中</p>
                    </>
                  ),
                },
                {
                  id: 'inbox',
                  label: 'Inbox',
                  icon: 'mail',
                  content: (
                    <>
                      <h3 className="nx-dock-panels-heading">Inbox</h3>
                      <p style={{ margin: '0 0 var(--nx-space-3)', color: 'var(--nx-text-muted)' }}>3 unread from Amara, Kenji and María.</p>
                      <Button size="sm" variant="primary" onClick={() => toast('Inbox · Bandeja')}>
                        Open inbox
                      </Button>
                    </>
                  ),
                },
                {
                  id: 'team',
                  label: 'Team',
                  icon: 'users',
                  content: (
                    <>
                      <h3 className="nx-dock-panels-heading">Team · Équipe</h3>
                      <AvatarGroup people={PEOPLE.map((person) => ({ name: person.name }))} max={5} />
                    </>
                  ),
                },
                {
                  id: 'ideas',
                  label: 'Ideas',
                  icon: 'sparkles',
                  content: (
                    <>
                      <h3 className="nx-dock-panels-heading">Ideas · Ideen · 아이디어</h3>
                      <p style={{ margin: 0, color: 'var(--nx-text-muted)' }}>Ship the Persian keyboard shortcuts next.</p>
                    </>
                  ),
                },
              ]}
            />
          </div>
        </Demo>

        <Demo title={tr('اتاق صوتی', 'Audio room')}>
          <div style={{ display: 'grid', justifyItems: 'center', gap: 'var(--nx-space-4)', minBlockSize: '24rem', alignContent: 'start' }}>
            <div className="sc-row">
              <Button size="xs" onClick={() => setListeners((n) => n + Math.ceil(Math.random() * 40))}>
                {tr('شنوندهٔ تازه', 'More listeners')}
              </Button>
              <Button size="xs" onClick={() => setSpeaking((n) => (n + 1) % speakers.length)}>
                {tr('نفر بعدی', 'Next speaker')}
              </Button>
            </div>
            <AudioRoom
              title="Design crit · 設計レビュー · مراجعة"
              listeners={listeners}
              onLeave={() => toast('Left the room · Salió de la sala')}
              members={speakers.map((name, i) => ({ name, role: i === 0 ? 'Host' : undefined, speaking: i === speaking || i === (speaking + 1) % speakers.length, muted: i === 2 || i === 4 }))}
            />
          </div>
        </Demo>

        <Demo title={tr('آکاردئون دسته‌ای', 'Stacked accordion')}>
          <StackedAccordion
            value={open}
            onValueChange={setOpen}
            items={[
              { id: 'shipping', title: 'Shipping · Envío', subtitle: 'Worldwide in 3–5 days', icon: 'globe', content: 'We ship to 140 countries — de Lisboa a Tokio.' },
              { id: 'returns', title: 'Returns · Retours', subtitle: '30 days, no questions', icon: 'arrow-left', content: 'Send it back within 30 days for a full refund.' },
              { id: 'payments', title: 'Payments · Zahlungen', subtitle: 'Cards, wallets, bank transfer', icon: 'lock', content: 'Every payment is encrypted end to end.' },
              { id: 'support', title: 'Support · サポート', subtitle: 'Humans and agents, 24/7', icon: 'message', content: 'Reply in any language — English, Español, العربية, 中文…' },
            ]}
          />
        </Demo>

        <Demo title={tr('ضبط صدا', 'Voice recorder')} center>
          <VoiceRecorder maxDuration={120} onStop={({ duration }) => toast.success(`Voice note · ${duration.toFixed(1)}s`)} />
          <p className="sc-demo-title">{tr('میکروفون درخواست نمی‌شود؛ موج‌ها شبیه‌سازی شده‌اند.', 'It never asks for the microphone: these bars are simulated.')}</p>
        </Demo>

        <Demo title={tr('کارت ثبت‌نام', 'Registration card')} center>
          <RegistrationCard
            currency="EUR"
            event={{
              title: 'Nabu Summit 2026 · Cumbre · Sommet · 峰会 · قمة',
              date: '12 Oct 2026',
              location: 'Lisboa',
              badge: (
                <span className="nx-badge" data-tone="gold">
                  Hybrid
                </span>
              ),
            }}
            tickets={[
              { id: 'general', label: 'General', price: 49, description: 'Talks, workshops, lunch' },
              { id: 'vip', label: 'VIP · 贵宾', price: 149, description: 'Front row and the speakers’ dinner', max: 2 },
              { id: 'student', label: 'Student · Estudiante', price: 0, max: 1 },
            ]}
            onSubmit={() => new Promise((resolve) => setTimeout(resolve, 900))}
          />
        </Demo>
      </div>
    </Section>
  );
}
