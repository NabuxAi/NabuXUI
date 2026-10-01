import { useEffect, useState } from 'react';
import { Button, CommandPalette, Header, Kbd, NabuXUIProvider, type NavItem, SegmentedControl, ThemeSwitch, ThemeToggle, theme, Toaster } from '@nabuxai/ui-react';
import { HeroSection, TextSection } from './sections/intro';
import { FrameworksSection } from './sections/frameworks';
import { ButtonsSection, InputsSection } from './sections/actions';
import { CardsSection, ChartsSection, GridSection, PricingSection } from './sections/content';
import { LoadingSection, MicroSection, NavigationSection, NotificationsSection, TransitionsSection } from './sections/system';
import { type Framework, FRAMEWORKS, FrameworkContext, type Lang, LangContext, readFramework } from './lang';
import { TextBlocks } from './blocks/text';
import { ActionsBlocks } from './blocks/actions';
import { CardsBlocks } from './blocks/cards';
import { DataBlocks } from './blocks/data';
import { BackdropsBlocks } from './blocks/backdrops';
import { MenusBlocks } from './blocks/menus';
import { NavExtrasBlocks } from './blocks/nav-extras';
import { GlassBlocks } from './blocks/glass';

const SECTIONS: Array<{ id: string; fa: string; en: string; icon: NonNullable<NavItem['children']>[number]['icon'] }> = [
  { id: 'text', fa: 'متن', en: 'Text', icon: 'edit' },
  { id: 'buttons', fa: 'دکمه', en: 'Buttons', icon: 'zap' },
  { id: 'inputs', fa: 'ورودی', en: 'Inputs', icon: 'sliders' },
  { id: 'cards', fa: 'کارت', en: 'Cards', icon: 'layers' },
  { id: 'charts', fa: 'نمودار', en: 'Charts', icon: 'chart' },
  { id: 'pricing', fa: 'قیمت‌گذاری', en: 'Pricing', icon: 'star' },
  { id: 'notifications', fa: 'اعلان', en: 'Notifications', icon: 'bell' },
  { id: 'micro', fa: 'ریزتعامل', en: 'Micro-interactions', icon: 'heart' },
  { id: 'transitions', fa: 'ترنزیشن صفحه', en: 'Page transitions', icon: 'arrow-right' },
  { id: 'loading', fa: 'بارگذاری', en: 'Loading', icon: 'cpu' },
  { id: 'grid', fa: 'گرید و بنتو', en: 'Grid & bento', icon: 'grid' },
  { id: 'navigation', fa: 'ناوبری', en: 'Navigation', icon: 'globe' },
  { id: 'frameworks', fa: 'فریم‌ورک‌ها', en: 'Frameworks', icon: 'command' },
];

/** The morphin-style block sections: listed in the menu and the palette so every part of the page is reachable from them. */
const BLOCK_GROUPS: Array<{ id: string; fa: string; en: string; icon: NonNullable<NavItem['children']>[number]['icon'] }> = [
  { id: 'glass', fa: 'بلوک‌های شیشه‌ای', en: 'Glass blocks', icon: 'sparkles' },
  { id: 'text-blocks', fa: 'بلوک‌های متن', en: 'Text blocks', icon: 'edit' },
  { id: 'actions-blocks', fa: 'بلوک‌های کنش', en: 'Action blocks', icon: 'zap' },
  { id: 'cards-blocks', fa: 'بلوک‌های کارت', en: 'Card blocks', icon: 'layers' },
  { id: 'data-blocks', fa: 'بلوک‌های داده', en: 'Data blocks', icon: 'chart' },
  { id: 'backdrops', fa: 'پس‌زمینه‌های متحرک', en: 'Animated backdrops', icon: 'image' },
  { id: 'menus-blocks', fa: 'منو و پنل‌های شکل‌پذیر', en: 'Menus & morphing panels', icon: 'sliders' },
  { id: 'nav-extras-blocks', fa: 'فیلتر، آمار و مرتب‌سازی', en: 'Filter, stats & sort', icon: 'grid' },
];

function readLang(): Lang {
  try {
    return localStorage.getItem('nabuxui.showcase.lang') === 'en' ? 'en' : 'fa';
  } catch {
    return 'fa';
  }
}

export function App() {
  const [lang, setLang] = useState<Lang>(readLang);
  const [framework, setFramework] = useState<Framework>(readFramework);
  const [palette, setPalette] = useState(false);
  const fa = lang === 'fa';
  const tr = (a: string, b: string) => (fa ? a : b);

  useEffect(() => {
    document.documentElement.lang = lang;
    document.documentElement.dir = fa ? 'rtl' : 'ltr';
    document.title = fa ? 'NabuXUI · سیستم طراحی Nabux' : 'NabuXUI · the Nabux design system';
    try {
      localStorage.setItem('nabuxui.showcase.lang', lang);
    } catch {
      /* not persisted */
    }
  }, [lang, fa]);

  useEffect(() => {
    try {
      localStorage.setItem('nabuxui.showcase.framework', framework);
    } catch {
      /* not persisted */
    }
  }, [framework]);

  useEffect(() => {
    const open = () => setPalette(true);
    window.addEventListener('sc-command', open);
    return () => window.removeEventListener('sc-command', open);
  }, []);

  const nav: NavItem[] = [
    {
      label: tr('کامپوننت‌ها', 'Components'),
      columns: 2,
      children: SECTIONS.slice(0, 8).map((s) => ({ label: tr(s.fa, s.en), href: `#${s.id}`, icon: s.icon, description: tr('نمایش و نمونه کد', 'Demo and code') })),
    },
    {
      label: tr('بلوک‌ها', 'Blocks'),
      columns: 2,
      children: BLOCK_GROUPS.map((s) => ({ label: tr(s.fa, s.en), href: `#${s.id}`, icon: s.icon })),
    },
    {
      label: tr('حرکت', 'Motion'),
      columns: 1,
      children: SECTIONS.slice(8).map((s) => ({ label: tr(s.fa, s.en), href: `#${s.id}`, icon: s.icon })),
    },
    { label: tr('قیمت‌گذاری', 'Pricing'), href: '#pricing' },
    { label: 'GitHub', href: 'https://github.com/NabuxAi' },
  ];

  const go = (id: string) => () => document.getElementById(id)?.scrollIntoView({ behavior: 'smooth', block: 'start' });

  // Snippet-framework switch. SegmentedControl takes no extra DOM props, so the
  // data-desktop hide-below-56rem rule rides on the desktop wrapper; the same
  // control also fills the mobile menu footer, keeping the switch usable at any width.
  const frameworkSwitch = (
    <SegmentedControl
      size="sm"
      aria-label={tr('فریم‌ورک نمونه‌کدها', 'Snippet framework')}
      value={framework}
      onValueChange={(value) => setFramework(value as Framework)}
      options={FRAMEWORKS.map((option) => ({ value: option.value, label: option.label }))}
    />
  );

  // Language and theme live in the header bar, but they must also be reachable
  // from the menu itself: on a phone the drawer is the only comfortable place.
  const mobileSettings = (
    <div style={{ display: 'grid', gap: 'var(--nx-space-3)', justifyItems: 'stretch' }}>
      {frameworkSwitch}
      <div style={{ display: 'flex', gap: 'var(--nx-space-2)', alignItems: 'center', justifyContent: 'space-between' }}>
        <Button variant="ghost" size="sm" onClick={() => setLang(fa ? 'en' : 'fa')} lang={fa ? 'en' : 'fa'}>
          {fa ? 'English' : 'فارسی'}
        </Button>
        <ThemeSwitch label={tr('پوسته', 'Theme')} />
      </div>
    </div>
  );

  return (
    <LangContext.Provider value={lang}>
      <FrameworkContext.Provider value={{ framework, setFramework }}>
        <NabuXUIProvider locale={lang}>
          <a className="nx-skip-link nx-visually-hidden" href="#main">
            {tr('رفتن به محتوا', 'Skip to content')}
          </a>
          <Header
            variant="floating"
            hideOnScroll
            aria-label={tr('ناوبری اصلی', 'Main navigation')}
            brand={
              <>
                <span className="sc-logo" aria-hidden="true">
                  N
                </span>
                <span>
                  Nabu<span className="sc-brand-x">X</span>UI
                </span>
              </>
            }
            items={nav}
            mobileActions={mobileSettings}
            actions={
              <>
                <Button variant="ghost" size="sm" data-desktop="" onClick={() => setPalette(true)} icon="search" aria-keyshortcuts="Meta+K Control+K">
                  <span dir="ltr">
                    <Kbd>⌘K</Kbd>
                  </span>
                </Button>
                <div data-desktop="">{frameworkSwitch}</div>
                <Button variant="ghost" size="sm" onClick={() => setLang(fa ? 'en' : 'fa')} lang={fa ? 'en' : 'fa'}>
                  {fa ? 'English' : 'فارسی'}
                </Button>
                <ThemeToggle />
              </>
            }
          />
          <main id="main" className="sc-main" tabIndex={-1}>
            <HeroSection />
            <FrameworksSection />
            <GlassBlocks />
            <TextSection />
            <TextBlocks />
            <ButtonsSection />
            <ActionsBlocks />
            <InputsSection />
            <CardsSection />
            <CardsBlocks />
            <ChartsSection />
            <DataBlocks />
            <PricingSection />
            <NotificationsSection />
            <MicroSection />
            <TransitionsSection />
            <LoadingSection />
            <GridSection />
            <NavigationSection />
            <BackdropsBlocks />
            <MenusBlocks />
            <NavExtrasBlocks />
          </main>
          <footer className="sc-footer">
            <div className="sc-container">
              {tr('NabuXUI · سیستم طراحی Nabux برای Livewire، Inertia و React', 'NabuXUI · the Nabux design system for Livewire, Inertia and React')}
            </div>
          </footer>
          <Toaster />
          <CommandPalette
            open={palette}
            onOpenChange={setPalette}
            groups={[
              {
                label: tr('بخش‌ها', 'Sections'),
                items: SECTIONS.map((s) => ({ id: s.id, label: tr(s.fa, s.en), icon: s.icon, keywords: [s.en, s.fa], onSelect: go(s.id) })),
              },
              {
                label: tr('بلوک‌ها', 'Blocks'),
                items: BLOCK_GROUPS.map((s) => ({ id: s.id, label: tr(s.fa, s.en), icon: s.icon, keywords: [s.en, s.fa], onSelect: go(s.id) })),
              },
              {
                label: tr('فرمان‌ها', 'Commands'),
                items: [
                  { id: 'lang', label: fa ? 'Switch to English' : 'تغییر به فارسی', icon: 'globe', onSelect: () => setLang(fa ? 'en' : 'fa') },
                  { id: 'theme', label: tr('رفتن به حالت تیره', 'Switch to dark mode'), icon: 'moon', onSelect: () => theme.set('dark') },
                  { id: 'theme-light', label: tr('رفتن به حالت روشن', 'Switch to light mode'), icon: 'sun', onSelect: () => theme.set('light') },
                  { id: 'theme-system', label: tr('پیروی از سیستم', 'Follow the system theme'), icon: 'settings', onSelect: () => theme.set('system') },
                  { id: 'top', label: tr('برگشت به بالا', 'Back to top'), icon: 'arrow-up', onSelect: () => window.scrollTo({ top: 0, behavior: 'smooth' }) },
                ],
              },
            ]}
          />
        </NabuXUIProvider>
      </FrameworkContext.Provider>
    </LangContext.Provider>
  );
}
