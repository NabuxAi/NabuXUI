import { useState } from 'react';
import {
  MarkerMap,
  RouteMap,
  StoreLocator,
  type MarkerMapDistrict,
  type MarkerMapPoi,
  type RouteMapRoute,
  type StoreLocatorCity,
  type StoreLocatorStore,
} from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

/** Persian digits for the demo's counts (the components take formatted strings). */
const faDigits = (value: number | string) => String(value).replace(/\d/g, (digit) => '۰۱۲۳۴۵۶۷۸۹'[+digit]!);

/** Thumbnail swatches for the marker popovers, from the chart palette. */
const THUMB = {
  gold: 'linear-gradient(135deg, var(--nx-gold-300), var(--nx-chart-3))',
  rose: 'linear-gradient(135deg, var(--nx-chart-2), var(--nx-chart-3))',
  green: 'linear-gradient(135deg, var(--nx-chart-7), var(--nx-cyan-400))',
  cyan: 'linear-gradient(135deg, var(--nx-cyan-400), var(--nx-chart-1))',
  violet: 'linear-gradient(135deg, var(--nx-violet-400), var(--nx-chart-2))',
} as const;

export function MapsBlocks() {
  const tr = useTr();
  const [query, setQuery] = useState('');
  const [city, setCity] = useState('rotterdam');

  const districts: MarkerMapDistrict[] = [
    { id: 'north-ridge', label: tr('پشتهٔ شمالی', 'North Ridge'), points: '0,0 640,0 640,84 336,116 0,96', lx: 470, ly: 46 },
    { id: 'old-town', label: tr('شهر قدیمی', 'Old Town'), points: '0,96 336,116 476,224 428,298 190,328 0,290', lx: 168, ly: 212 },
    { id: 'museum-mile', label: tr('خیابان موزه‌ها', 'Museum Mile'), points: '336,116 640,84 640,202 476,224', lx: 512, ly: 160 },
    { id: 'botanic-ring', label: tr('حلقهٔ گیاه‌شناسی', 'Botanic Ring'), points: '476,224 640,202 640,400 428,400 428,298', lx: 544, ly: 340 },
    { id: 'old-harbor', label: tr('بندر قدیمی', 'Old Harbor'), points: '0,290 190,328 428,298 428,400 0,400', lx: 120, ly: 366 },
  ];

  const pois: MarkerMapPoi[] = [
    {
      id: 'lantern',
      x: 22,
      y: 78,
      district: 'old-harbor',
      name: tr('فانوس', 'The Lantern'),
      kind: tr('کافه', 'Café'),
      rating: tr('۴٫۸', '4.8'),
      meta: [tr('تا ۲۲:۰۰', 'until 22:00'), tr('۳۵۰ متر فاصله', '350 m away')],
      thumb: THUMB.gold,
    },
    {
      id: 'northlight',
      x: 58,
      y: 28,
      district: 'museum-mile',
      name: tr('گالری نور شمالی', 'Northlight Gallery'),
      kind: tr('گالری', 'Gallery'),
      rating: tr('۴٫۹', '4.9'),
      meta: [tr('تا ۱۸:۰۰', 'until 18:00'), tr('۱٫۲ کیلومتر فاصله', '1.2 km away')],
      thumb: THUMB.rose,
    },
    {
      id: 'kessel',
      x: 44,
      y: 52,
      district: 'old-town',
      name: tr('رسانه‌های کسل', 'Kessel Records'),
      kind: tr('پلاک‌فروشی', 'Vinyl shop'),
      rating: tr('۴٫۶', '4.6'),
      meta: [tr('تا ۲۱:۰۰', 'until 21:00'), tr('۶۴۰ متر فاصله', '640 m away')],
      thumb: THUMB.green,
    },
    {
      id: 'glasshouse',
      x: 76,
      y: 58,
      district: 'botanic-ring',
      name: tr('گرمابهٔ گیاه‌شناسی', 'Glasshouse Gardens'),
      kind: tr('باغ', 'Garden'),
      rating: tr('۴٫۷', '4.7'),
      meta: [tr('تا ۱۹:۰۰', 'until 19:00'), tr('۲٫۱ کیلومتر فاصله', '2.1 km away')],
      thumb: THUMB.cyan,
    },
    {
      id: 'wharf',
      x: 12,
      y: 74,
      district: 'old-harbor',
      name: tr('اسکلهٔ کشتی ۷', 'Ferry Wharf 7'),
      kind: tr('حمل‌ونقل', 'Transport'),
      rating: tr('۴٫۴', '4.4'),
      meta: [tr('۰۵:۰۰ تا ۲۴:۰۰', '05:00 to 24:00'), tr('۹۰۰ متر فاصله', '900 m away')],
      thumb: THUMB.cyan,
    },
    {
      id: 'aurora',
      x: 68,
      y: 12,
      district: 'north-ridge',
      name: tr('چشم‌انداز اورورا', 'Aurora Viewpoint'),
      kind: tr('بلندی', 'Viewpoint'),
      rating: tr('۴٫۹', '4.9'),
      meta: [tr('همیشه باز', 'always open'), tr('۱٫۶ کیلومتر فاصله', '1.6 km away')],
      thumb: THUMB.violet,
    },
  ];

  const routes: RouteMapRoute[] = [
    {
      id: 'harbor-express',
      name: tr('اکسپرس بندر', 'Harbor Express'),
      eta: tr('۳۸ دقیقه', '38 min'),
      distance: tr('۲۱٫۴ کیلومتر', '21.4 km'),
      d: 'M70 330 C128 308 142 278 172 254 C206 226 262 208 300 180 C338 152 398 170 448 152 C498 134 546 104 580 80',
      stops: [
        { x: 70, y: 330, name: tr('ایستگاه مرکزی', 'Central Station') },
        { x: 172, y: 254, name: tr('بندر قدیمی', 'Old Harbor'), leg: tr('۷ دقیقه · ۴٫۱ کیلومتر', '7 min · 4.1 km') },
        { x: 300, y: 180, name: tr('خیابان موزه‌ها', 'Museum Mile'), leg: tr('۹ دقیقه · ۵٫۲ کیلومتر', '9 min · 5.2 km') },
        { x: 448, y: 152, name: tr('حلقهٔ گیاه‌شناسی', 'Botanic Ring'), leg: tr('۱۱ دقیقه · ۶٫۳ کیلومتر', '11 min · 6.3 km') },
        { x: 580, y: 80, name: tr('فرودگاه T3', 'Airport T3'), leg: tr('۱۱ دقیقه · ۵٫۸ کیلومتر', '11 min · 5.8 km') },
      ],
    },
    {
      id: 'riverside-line',
      name: tr('خط رودخانه', 'Riverside Line'),
      eta: tr('۴۶ دقیقه', '46 min'),
      distance: tr('۲۴٫۸ کیلومتر', '24.8 km'),
      d: 'M70 330 C112 346 152 352 192 344 C242 334 286 314 330 294 C384 270 430 252 470 230 C520 202 556 134 580 80',
      stops: [
        { x: 70, y: 330, name: tr('ایستگاه مرکزی', 'Central Station') },
        { x: 192, y: 344, name: tr('پل کسل', 'Kessel Bridge'), leg: tr('۸ دقیقه · ۴٫۴ کیلومتر', '8 min · 4.4 km') },
        { x: 330, y: 294, name: tr('اسکلهٔ سیگنال', 'Signal Wharf'), leg: tr('۱۰ دقیقه · ۵٫۶ کیلومتر', '10 min · 5.6 km') },
        { x: 470, y: 230, name: tr('میان‌بر بار', 'Freight Bypass'), leg: tr('۱۳ دقیقه · ۶٫۷ کیلومتر', '13 min · 6.7 km') },
        { x: 580, y: 80, name: tr('فرودگاه T3', 'Airport T3'), leg: tr('۱۵ دقیقه · ۸٫۱ کیلومتر', '15 min · 8.1 km') },
      ],
    },
  ];

  const cities: StoreLocatorCity[] = [
    { id: 'rotterdam', label: tr('روتردام', 'Rotterdam'), tint: 'var(--nx-gold-300)' },
    { id: 'porto', label: tr('پورتو', 'Porto'), tint: 'var(--nx-chart-7)' },
    { id: 'singapore', label: tr('سنگاپور', 'Singapore'), tint: 'var(--nx-cyan-300)' },
  ];

  const stores: StoreLocatorStore[] = [
    { id: 'r1', city: 'rotterdam', x: 34, y: 36, name: 'Weena Corner', street: 'Weena 12', hours: tr('۰۷:۳۰ تا ۲۰:۰۰', '07:30 – 20:00'), distance: tr('۱٫۲ کیلومتر', '1.2 km') },
    { id: 'r2', city: 'rotterdam', x: 58, y: 54, name: 'Coolsingel Windows', street: 'Coolsingel 8', hours: tr('۰۹:۰۰ تا ۲۱:۰۰', '09:00 – 21:00'), distance: tr('۲٫۴ کیلومتر', '2.4 km') },
    { id: 'r3', city: 'rotterdam', x: 32, y: 66, name: 'Maas Side', street: 'Maasboulevard 41', hours: tr('۰۸:۰۰ تا ۱۹:۰۰', '08:00 – 19:00'), distance: tr('۳٫۱ کیلومتر', '3.1 km') },
    { id: 'p1', city: 'porto', x: 42, y: 34, name: 'Flores Arch', street: 'Rua das Flores 45', hours: tr('۰۸:۰۰ تا ۲۰:۰۰', '08:00 – 20:00'), distance: tr('۰٫۸ کیلومتر', '0.8 km') },
    { id: 'p2', city: 'porto', x: 66, y: 60, name: 'Doca Ferry', street: 'Rua da Devesa 8', hours: tr('۰۷:۰۰ تا ۱۸:۳۰', '07:00 – 18:30'), distance: tr('۲٫۶ کیلومتر', '2.6 km') },
    { id: 's1', city: 'singapore', x: 38, y: 42, name: 'Amoy Gate', street: 'Amoy Street 21', hours: tr('۱۰:۰۰ تا ۲۲:۰۰', '10:00 – 22:00'), distance: tr('۱٫۵ کیلومتر', '1.5 km') },
    { id: 's2', city: 'singapore', x: 64, y: 66, name: 'Tanjong Corner', street: 'Tanjong Pagar Road 5', hours: tr('۰۸:۳۰ تا ۲۰:۳۰', '08:30 – 20:30'), distance: tr('۳٫۸ کیلومتر', '3.8 km') },
  ];

  const cityLabel = cities.find((item) => item.id === city)?.label ?? '';

  return (
    <Section
      id="maps-blocks"
      eyebrow={tr('بلوک‌ها', 'Blocks')}
      title={tr('نقشه‌های شهر، دست‌کشیده', 'Hand-drawn city maps')}
      description={tr(
        'شهر بی‌نامی از SVG خالص — بدون تایل و بدون SDK. مارکرهایی که با لیبلشان متورم می‌شوند و پاپ‌اور جزئیات می‌گیرند، مسیر ترانزیت که با ماسک SVG خودش را مرکب می‌کند و ایستگاه‌ها پشت نوک قلم می‌نشینند، و یابندهٔ شعبه با جست‌وجو و فیلتر شهر و جهانی که برای هر انتخاب می‌لغزد.',
        'A nameless city in pure SVG — no tiles, no SDK. Markers that swell with their labels and open detail popovers, a transit route that inks itself through an SVG mask while the stops settle in behind the pen, and a store locator with search, city filters and a world that glides to every pick.',
      )}
      code={{
        react: `<MarkerMap label="City guide" pois={pois} districts={districts} />
<RouteMap label="Airport transfer" routes={[express, riverside]} />
<StoreLocator label="Branches" cities={cities} stores={stores} />`,
        blade: `<div class="nx-marker-map">{{-- pins are buttons; the CSS ships in @nabuxai/ui-core --}}
  <div class="nx-map-stage" dir="ltr">
    <svg class="nx-map-art" viewBox="0 0 640 400" role="img" aria-label="City guide">…</svg>
    <button class="nx-map-pin" style="inset-inline-start: 22%; inset-block-start: 78%">…</button>
  </div>
  <ul class="nx-marker-map-legend">…</ul>
</div>`,
      }}
    >
      <div className="sc-demos">
        <Demo title={tr('نقشهٔ مکان‌ها', 'Marker map')} center>
          <MarkerMap
            label={tr('نقشهٔ استیلیزهٔ شهری بندری با شش مکان دیدنی', 'Stylised city map of a harbour town with six points of interest')}
            pois={pois}
            districts={districts}
            legendLabel={tr('ناحیه‌ها', 'Districts')}
          />
        </Demo>

        <Demo title={tr('نقشهٔ مسیر', 'Route map')} wide>
          <RouteMap
            label={tr('نقشهٔ استیلیزهٔ شهر با مسیر حمل‌ونقل از ایستگاه مرکزی تا فرودگاه', 'Stylised city map with a transit route from Central Station to the airport')}
            routes={routes}
            note={tr('یک شهر، دو خط — شبح هر مسیر روی نقشه می‌ماند و دیگری ترسیم می‌شود.', 'One city, two lines — the ghost of each route stays on the map while the other draws.')}
          />
        </Demo>

        <Demo title={tr('یابندهٔ شعبه‌ها', 'Store locator')} wide>
          <StoreLocator
            label={tr('نقشهٔ شعبه‌های رسترز مریدین', 'Meridian Roasters branch map')}
            cities={cities}
            stores={stores}
            query={query}
            onQueryChange={setQuery}
            city={city}
            onCityChange={setCity}
            searchPlaceholder={tr('نام شعبه یا خیابان…', 'Branch or street…')}
            resultsLabel={(count) =>
              query.trim()
                ? tr(`${faDigits(count)} نتیجه برای «${query.trim()}»`, `${count} results for “${query.trim()}”`)
                : tr(`${faDigits(count)} شعبه در ${cityLabel}`, `${count} branches in ${cityLabel}`)
            }
            emptyLabel={tr('شعبه‌ای با این جست‌وجو پیدا نشد — برند هنوز به آن شهر نرسیده است.', 'No branch matched this search — the brand is not in that city yet.')}
            resetLabel={tr('پاک‌کردن جست‌وجو', 'Clear search')}
            note={tr('جهانِ نقشه ۵۵٪ از قابش بزرگ‌تر است — هر انتخاب، آن را جابه‌جا می‌کند تا مارکر دقیقاً وسط بنشیند.', 'The map’s world runs 55% larger than its frame — every pick translates it until the marker sits dead centre.')}
          />
        </Demo>
      </div>
    </Section>
  );
}
