import { createContext, useContext } from 'react';

/**
 * The Inertia app's language layer — the same pattern as apps/showcase and
 * apps/admin: a `Lang` union, a context the whole tree reads, `useTr` for
 * one-off pairs, a saved pick in localStorage, and one typed dictionary
 * (`STRINGS`) with every key of every page, fa and en.
 *
 * The first paint follows the server: HandleInertiaRequests shares the app
 * locale, and the shell starts from it unless the visitor already picked a
 * language here (readLang below).
 */

export type Lang = 'fa' | 'en';

export const LangContext = createContext<Lang>('fa');

export function useLang() {
  return useContext(LangContext);
}

/** `tr('ذخیره', 'Save')` — the app speaks Persian first, English second. */
export function useTr() {
  const lang = useContext(LangContext);
  return (fa: string, en: string) => (lang === 'fa' ? fa : en);
}

/** Saved pick — localStorage first; `fallback` (the server's locale) when storage is closed or empty. */
export function readLang(fallback: Lang = 'fa'): Lang {
  try {
    const saved = localStorage.getItem('nabuxui.inertia.lang');
    return saved === 'fa' || saved === 'en' ? saved : fallback;
  } catch {
    return fallback;
  }
}

export function writeLang(lang: Lang) {
  try {
    localStorage.setItem('nabuxui.inertia.lang', lang);
  } catch {
    /* not persisted */
  }
}

/* ============================================================================
 * The dictionary. `fa` defines the shape; `en` fills the same shape.
 * ========================================================================== */

const fa = {
  app: {
    name: 'نابو اینرسیا',
    brand: 'Nabu · Inertia',
    tagline: 'طعم Inertia از NabuXUI — همان کامپوننت‌ها، روتر لاراول',
    home: 'بازگشت به Livewire',
    homeTitle: 'صفحهٔ اصلی (Livewire)',
  },
  nav: {
    dashboard: 'داشبورد',
    gallery: 'گالری بلوک‌ها',
    visits: 'ناوبری اینرسیا',
    ariaLabel: 'ناوبری اپ اینرسیا',
  },
  dashboard: {
    title: 'داشبورد عملیات',
    subtitle: 'آمار از کنترلر لاراول می‌آید؛ رندر با کامپوننت‌های React',
    welcomeTitle: 'سلام، تازهٔ صفحه اولی؟',
    welcomeText:
      'این صفحه با یک درخواست معمولی لاراول ساخته شده و بعد از آن، هر ناوبری فقط JSON می‌گیرد و در همان DOM می‌ماند. زبان و تم را از نوار بالا عوض کنید تا همهٔ کامپوننت‌ها هم‌زمان جابه‌جا شوند.',
    statsLabel: 'شاخص‌های هفته',
    stats: {
      visits: { label: 'بازدید هفته', caption: 'رو به رشد' },
      uptime: { label: 'پایداری (٪)', caption: 'سی‌روز اخیر' },
      deploys: { label: 'استقرار', caption: 'این ماه' },
      incidents: { label: 'رخداد باز', caption: 'برطرف‌شده‌ها حساب نشده' },
    },
    chart: { title: 'بازدید روزانه', subtitle: 'چهارده‌روز اخیر' },
    gauge: { title: 'تأخیر میانگین', subtitle: 'پاسخ سرور در ۵ دقیقهٔ اخیر' },
    feed: {
      title: 'جریان فعالیت',
      label: 'رخدادهای اخیر تیم',
      /** The verb between the actor and the target, per event kind. */
      sentences: {
        deploy: 'یک استقرار را برای',
        incident: 'یک رخداد باز کرد روی',
        release: 'این نسخه را منتشر کرد:',
        backup: 'پشتیبان‌گیری کرد از',
      },
    },
    actionPrimary: 'اعلان آزمایشی',
    actionSecondary: 'بارگذاری دوباره',
    reloaded: 'صفحه از سرور تازه شد',
  },
  gallery: {
    title: 'گالری بلوک‌ها',
    subtitle: 'بلوک‌های آمادهٔ @nabuxai/ui-react، همین‌جا در دل اپ اینرسیا',
    filterLabel: 'پالایش گالری',
    filters: { all: 'همه', commerce: 'فروش', people: 'افراد', states: 'حالت‌ها' },
    product: {
      title: 'مجموعهٔ ستارهٔ شمال',
      altTitle: 'مجموعهٔ صلیب جنوبی',
      category: 'قالب رابط کاربری',
      add: 'افزودن',
      inCart: 'در سبد',
    },
    profile: {
      name: 'مایا لیندبرگ',
      role: 'طراح ارشد محصول',
      follow: 'دنبال‌کردن',
      following: 'دنبال‌شده',
      message: 'پیام',
      stats: { projects: 'پروژه', followers: 'دنبال‌کننده', following: 'دنبال‌شده' },
    },
    sortLabel: 'مرتب‌سازی',
    sort: { featured: 'ویژه', newest: 'تازه‌ترین', price: 'ارزان‌ترین' },
    empty: { title: 'چیزی اینجا نیست', text: 'با پالایش دیگری دوباره امتحان کنید.', action: 'پاک‌کردن پالایش' },
    count: (n: number) => `${n} بلوک در این صفحه`,
  },
  visits: {
    title: 'ناوبری اینرسیا',
    subtitle: 'لینک، بارگذاری دوبارهٔ جزئی و خروج از اپ — همین صفحه آزمایشگاه هر سه است',
    linksTitle: 'لینک‌ها',
    linksText: 'هر لینک داخلی از Link اینرسیا رد می‌شود؛ NabuXUI هم همان را از linkComponent می‌گیرد.',
    links: {
      dashboard: 'برو به داشبورد',
      gallery: 'برو به گالری',
      self: 'همین صفحه (فقط pulse)',
    },
    partialTitle: 'بارگذاری دوبارهٔ جزئی',
    partialText:
      'کنترلر pulse را با Inertia::optional می‌دهد: در بار اول نمی‌آید، ولی دکمهٔ زیر فقط همان کلید را از سرور می‌گیرد و هیچ چیز دیگری دوباره رندر نمی‌شود.',
    pulseEmpty: 'هنوز از سرور نگرفته — دکمه را بزنید',
    reloadFull: 'بارگذاری کامل صفحه',
    reloadPartial: 'فقط pulse را تازه کن',
    leftTitle: 'خروج از اپ اینرسیا',
    leftText: 'برای برگشت به طعم Livewire باید <a> ساده زد — لینک اینرسیا فقط صفحه‌های اینرسیا را می‌فهمد.',
    leftAction: 'بازگشت به Livewire',
  },
  footer: 'ساخته‌شده با @nabuxai/ui-react و @inertiajs/react',
};

const en: typeof fa = {
  app: {
    name: 'Nabu Inertia',
    brand: 'Nabu · Inertia',
    tagline: 'The NabuXUI Inertia flavor — same components, Laravel routing',
    home: 'Back to Livewire',
    homeTitle: 'Home (Livewire)',
  },
  nav: {
    dashboard: 'Dashboard',
    gallery: 'Block gallery',
    visits: 'Inertia routing',
    ariaLabel: 'Inertia app navigation',
  },
  dashboard: {
    title: 'Operations dashboard',
    subtitle: 'The numbers come from a Laravel controller; the rendering is pure React',
    welcomeTitle: 'Hello, first time here?',
    welcomeText:
      'This page was built by a plain Laravel request; every navigation after it only fetches JSON and stays in the same DOM. Switch the language or theme from the top bar and every component follows at once.',
    statsLabel: 'This week at a glance',
    stats: {
      visits: { label: 'Weekly visits', caption: 'trending up' },
      uptime: { label: 'Uptime (%)', caption: 'last 30 days' },
      deploys: { label: 'Deploys', caption: 'this month' },
      incidents: { label: 'Open incidents', caption: 'resolved not counted' },
    },
    chart: { title: 'Daily visits', subtitle: 'last 14 days' },
    gauge: { title: 'Average latency', subtitle: 'server response, last 5 minutes' },
    feed: {
      title: 'Activity feed',
      label: 'recent team events',
      /** The verb between the actor and the target, per event kind. */
      sentences: {
        deploy: 'shipped a deploy to',
        incident: 'opened an incident on',
        release: 'cut the release',
        backup: 'took a backup of',
      },
    },
    actionPrimary: 'Test a toast',
    actionSecondary: 'Reload',
    reloaded: 'Page refreshed from the server',
  },
  gallery: {
    title: 'Block gallery',
    subtitle: 'Ready-made @nabuxai/ui-react blocks, live inside the Inertia app',
    filterLabel: 'Filter the gallery',
    filters: { all: 'All', commerce: 'Commerce', people: 'People', states: 'States' },
    product: {
      title: 'Northstar Kit',
      altTitle: 'Southcross Kit',
      category: 'UI template',
      add: 'Add to cart',
      inCart: 'In cart',
    },
    profile: {
      name: 'Maja Lindberg',
      role: 'Senior product designer',
      follow: 'Follow',
      following: 'Following',
      message: 'Message',
      stats: { projects: 'Projects', followers: 'Followers', following: 'Following' },
    },
    sortLabel: 'Sort',
    sort: { featured: 'Featured', newest: 'Newest', price: 'Cheapest' },
    empty: { title: 'Nothing here', text: 'Try another filter above.', action: 'Clear the filter' },
    count: (n: number) => `${n} blocks on this page`,
  },
  visits: {
    title: 'Inertia routing',
    subtitle: 'Links, a partial reload and leaving the app — this page is the lab for all three',
    linksTitle: 'Links',
    linksText: 'Every internal href goes through Inertia’s Link; NabuXUI gets the very same one from linkComponent.',
    links: {
      dashboard: 'Go to the dashboard',
      gallery: 'Go to the gallery',
      self: 'This page (only pulse)',
    },
    partialTitle: 'Partial reload',
    partialText:
      'The controller serves pulse through Inertia::optional: it is absent on the first load, and the button below asks for that one key alone — nothing else re-renders.',
    pulseEmpty: 'Not fetched yet — press the button',
    reloadFull: 'Full page reload',
    reloadPartial: 'Refresh only pulse',
    leftTitle: 'Leaving the Inertia app',
    leftText: 'Going back to the Livewire flavor needs a plain <a> — an Inertia link only understands Inertia pages.',
    leftAction: 'Back to Livewire',
  },
  footer: 'Built with @nabuxai/ui-react and @inertiajs/react',
};

export const STRINGS: Record<Lang, typeof fa> = { fa, en };
