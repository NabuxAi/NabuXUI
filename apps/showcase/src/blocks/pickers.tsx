import { useEffect, useMemo, useState } from 'react';
import {
  Badge,
  BottomSheet,
  Button,
  Combobox,
  type ComboboxOption,
  ContextMenu,
  type DateRange,
  DateRangePicker,
  HoverCard,
  NumberField,
  TagInput,
  TimePicker,
  toast,
} from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

const CITIES: Array<{ value: string; fa: string; en: string; code: string }> = [
  { value: 'IKA', fa: 'تهران — امام خمینی', en: 'Tehran — Imam Khomeini', code: 'IKA' },
  { value: 'SYZ', fa: 'شیراز — شهید دستغیب', en: 'Shiraz — Shahid Dastgheib', code: 'SYZ' },
  { value: 'IFN', fa: 'اصفهان — شهید بهشتی', en: 'Isfahan — Shahid Beheshti', code: 'IFN' },
  { value: 'IST', fa: 'فرودگاه استانبول', en: 'Istanbul Airport', code: 'IST' },
  { value: 'DXB', fa: 'فرودگاه بین‌المللی دبی', en: 'Dubai International', code: 'DXB' },
  { value: 'FRA', fa: 'فرانکفورت', en: 'Frankfurt am Main', code: 'FRA' },
  { value: 'CDG', fa: 'پاریس — شارل دوگل', en: 'Paris — Charles de Gaulle', code: 'CDG' },
  { value: 'NRT', fa: 'توکیو — ناریتا', en: 'Tokyo — Narita', code: 'NRT' },
];

const TEAM = [
  { value: 'sara', fa: 'سارا احمدی', en: 'Sara Ahmadi' },
  { value: 'reza', fa: 'رضا کریمی', en: 'Reza Karimi' },
  { value: 'nika', fa: 'نیکا رحیمی', en: 'Nika Rahimi' },
  { value: 'omid', fa: 'امید جعفری', en: 'Omid Jafari' },
  { value: 'leila', fa: 'لیلا مرادی', en: 'Leila Moradi' },
  { value: 'arash', fa: 'آرش نوری', en: 'Arash Nouri' },
];

/** A pretend server search: answers after a short wait. */
function useFakeSearch(all: ComboboxOption[]) {
  const [query, setQuery] = useState('');
  const [results, setResults] = useState(all);
  const [loading, setLoading] = useState(false);
  useEffect(() => {
    setLoading(true);
    const timer = setTimeout(() => {
      const q = query.trim().toLocaleLowerCase();
      setResults(q ? all.filter((o) => `${o.label} ${o.description ?? ''}`.toLocaleLowerCase().includes(q)) : all);
      setLoading(false);
    }, 450);
    return () => clearTimeout(timer);
  }, [query, all]);
  return { results, loading, setQuery };
}

const iso = (d: Date) => new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate())).toISOString().slice(0, 10);
const plusDays = (n: number) => {
  const d = new Date();
  d.setDate(d.getDate() + n);
  return iso(d);
};

export function PickersBlocks() {
  const tr = useTr();
  const [to, setTo] = useState<string | null>(null);
  const [skills, setSkills] = useState<string[]>(['Laravel', 'React']);
  const [sheet, setSheet] = useState(false);
  const [stay, setStay] = useState<DateRange>({ start: plusDays(5), end: plusDays(8) });
  const [slot, setSlot] = useState<string | null>('19:30');
  const [qty, setQty] = useState<number | null>(2);

  const airports = useMemo<ComboboxOption[]>(
    () => CITIES.map((c) => ({ value: c.value, label: tr(c.fa, c.en), description: c.code, keywords: [c.fa, c.en, c.code] })),
    [tr],
  );
  const people = useMemo<ComboboxOption[]>(() => TEAM.map((p) => ({ value: p.value, label: tr(p.fa, p.en), icon: 'user' as const })), [tr]);
  const search = useFakeSearch(people);
  const nights = stay.start && stay.end ? Math.round((Date.parse(stay.end) - Date.parse(stay.start)) / 86_400_000) : 0;

  return (
    <Section
      id="pickers-blocks"
      eyebrow={tr('بلوک‌ها', 'Blocks')}
      title={tr('انتخابگرها و ورودی‌ها', 'Pickers & inputs')}
      description={tr(
        'کمبوباکس تایپی، برچسب‌ها، منوی راست‌کلیک، کارت پیش‌نمایش، برگهٔ کشیدنی، بازهٔ تاریخ شمسی و میلادی، چرخ ساعت و فیلد عددی — همه با کیبورد کامل و راست‌به‌چپ.',
        'A typeahead combobox, tags, a right-click menu, a hover card, a draggable sheet, Jalali and Gregorian date ranges, time drums and a number field — all keyboard-complete and right-to-left ready.',
      )}
      code={{
        react: `<Combobox options={airports} value={to} onValueChange={setTo} placeholder="City or code" />
<TagInput value={skills} onValueChange={setSkills} max={6} suggestions={all} />
<ContextMenu items={[{ id: 'open', label: 'Open' }, { separator: true }, …]} onSelect={run}>…</ContextMenu>
<HoverCard trigger={<a href="/u/sara">@sara</a>}>…profile…</HoverCard>
<BottomSheet open={open} onOpenChange={setOpen} snaps={[0.4, 0.9]} title="Choose a ride">…</BottomSheet>
<DateRangePicker value={stay} onValueChange={setStay} />          // Jalali in fa
<TimePicker value={slot} onValueChange={setSlot} minuteStep={5} />
<NumberField label="Quantity" value={qty} onValueChange={setQty} min={1} max={20} />`,
        blade: `<x-nx::combobox :options="$airports" wire:model.live="to" />
<x-nx::tag-input :max="6" :suggestions="$all" wire:model.live="skills" />
<x-nx::context-menu :items="$actions" x-on:nx-select="$wire.run($event.detail)">…</x-nx::context-menu>
<x-nx::hover-card><x-slot:trigger><a href="/u/sara">@sara</a></x-slot:trigger>…</x-nx::hover-card>
<x-nx::bottom-sheet id="rides" :snaps="[0.4, 0.9]" title="Choose a ride">…</x-nx::bottom-sheet>
<x-nx::date-range-picker wire:model.live="stay" />
<x-nx::time-picker wire:model.live="slot" :minute-step="5" />
<x-nx::number-field label="Quantity" wire:model.live="qty" :min="1" :max="20" />`,
      }}
    >
      <div className="sc-demos">
        <Demo title={tr('کمبوباکس — مقصد پرواز', 'Combobox — flight destination')}>
          <div style={{ display: 'grid', gap: '0.75rem', width: '100%' }}>
            <Combobox options={airports} value={to} onValueChange={setTo} label={tr('مقصد', 'Destination')} placeholder={tr('شهر، فرودگاه یا کد', 'City, airport or code')} />
            <Badge tone={to ? 'success' : 'neutral'}>{to ?? tr('هنوز انتخاب نشده', 'Nothing picked yet')}</Badge>
          </div>
        </Demo>

        <Demo title={tr('کمبوباکس — جست‌وجوی سرور', 'Combobox — server search')}>
          <Combobox
            options={search.results}
            filter={false}
            loading={search.loading}
            onQueryChange={search.setQuery}
            label={tr('مسئول', 'Assignee')}
            placeholder={tr('جست‌وجوی هم‌تیمی‌ها', 'Search teammates')}
            style={{ width: '100%' }}
          />
        </Demo>

        <Demo title={tr('برچسب‌ها — مهارت‌های آگهی', 'Tags — job post skills')}>
          <TagInput
            value={skills}
            onValueChange={setSkills}
            max={6}
            label={tr('مهارت‌ها', 'Skills')}
            suggestions={['Laravel', 'Livewire', 'Alpine.js', 'React', 'TypeScript', 'Tailwind CSS', 'Redis', tr('دسترس‌پذیری', 'Accessibility')]}
            style={{ width: '100%' }}
          />
        </Demo>

        <Demo title={tr('منوی راست‌کلیک — فایل', 'Context menu — a file')} center>
          <ContextMenu
            label={tr('کارهای فایل', 'File actions')}
            onSelect={(id) => toast({ title: id, tone: 'success' })}
            items={[
              { id: tr('باز شد', 'Opened'), label: tr('باز کردن', 'Open'), icon: 'external-link', shortcut: '↵' },
              { id: tr('تکثیر شد', 'Duplicated'), label: tr('تکثیر', 'Duplicate'), icon: 'copy', shortcut: '⌘D' },
              {
                label: tr('اشتراک', 'Share'),
                icon: 'users',
                items: [
                  { id: tr('پیوند کپی شد', 'Link copied'), label: tr('کپی پیوند', 'Copy link'), icon: 'copy' },
                  { id: tr('ایمیل آماده شد', 'Email drafted'), label: tr('ارسال با ایمیل', 'Send by email'), icon: 'mail' },
                ],
              },
              { id: 'lock', label: tr('قفل (فقط مالک)', 'Lock (owners only)'), icon: 'lock', disabled: true },
              { separator: true },
              { id: tr('به سطل رفت', 'Moved to trash'), label: tr('انتقال به سطل', 'Move to trash'), icon: 'trash', tone: 'danger' },
            ]}
            style={{ padding: '1.25rem 1.5rem', border: '1px dashed var(--nx-border-strong)', borderRadius: 'var(--nx-radius-lg)', textAlign: 'center' }}
          >
            <strong>{tr('گزارش فصل سوم.pdf', 'Q3 report.pdf')}</strong>
            <div style={{ color: 'var(--nx-text-muted)', fontSize: 'var(--nx-text-sm)' }}>{tr('راست‌کلیک یا انگشت را نگه دارید', 'Right-click or long-press')}</div>
          </ContextMenu>
        </Demo>

        <Demo title={tr('کارت پیش‌نمایش — منشن', 'Hover card — a mention')}>
          <p style={{ margin: 0, lineHeight: 1.9 }}>
            {tr('می‌شود', 'Can')}{' '}
            <HoverCard trigger={<a href="#sara" style={{ color: 'var(--nx-accent-text)', fontWeight: 600 }}>@{tr('سارا', 'sara')}</a>}>
              <span style={{ display: 'grid', gap: '0.5rem' }}>
                <strong>{tr('سارا احمدی', 'Sara Ahmadi')}</strong>
                <span style={{ color: 'var(--nx-text-muted)', fontSize: 'var(--nx-text-sm)' }}>{tr('سرپرست طراحی · تهران', 'Design lead · Tehran')}</span>
                <Button size="sm" icon="message" onClick={() => toast({ title: tr('پیام رفت', 'Message sent'), tone: 'success' })}>
                  {tr('پیام', 'Message')}
                </Button>
              </span>
            </HoverCard>{' '}
            {tr('فاصله‌ها را پیش از انتشار ببیند؟', 'check the spacing before we ship?')}
          </p>
        </Demo>

        <Demo title={tr('برگهٔ پایینی — انتخاب سرویس', 'Bottom sheet — choose a ride')} center>
          <Button variant="primary" icon="zap" onClick={() => setSheet(true)}>
            {tr('انتخاب سرویس', 'Choose a ride')}
          </Button>
          <BottomSheet open={sheet} onOpenChange={setSheet} snaps={[0.4, 0.9]} title={tr('انتخاب سرویس', 'Choose a ride')}>
            <div style={{ display: 'grid', gap: '0.625rem' }}>
              {[tr('اقتصادی', 'Economy'), tr('راحت', 'Comfort'), tr('ون', 'Van'), tr('پیک موتوری', 'Courier'), tr('رانندهٔ بانو', 'Women drivers')].map((name) => (
                <Button
                  key={name}
                  block
                  onClick={() => {
                    toast({ title: name, tone: 'success' });
                    setSheet(false);
                  }}
                >
                  {name}
                </Button>
              ))}
            </div>
          </BottomSheet>
        </Demo>

        <Demo title={tr('بازهٔ تاریخ — رزرو هتل', 'Date range — hotel stay')} wide>
          <div style={{ display: 'flex', flexWrap: 'wrap', gap: '1rem', alignItems: 'center' }}>
            <DateRangePicker value={stay} onValueChange={setStay} min={plusDays(0)} label={tr('ورود – خروج', 'Check-in – check-out')} />
            <Badge tone="accent">{nights ? tr(`${new Intl.NumberFormat('fa-IR').format(nights)} شب`, `${nights} nights`) : tr('تاریخ را بزنید', 'Pick dates')}</Badge>
            <DateRangePicker label={tr('بازهٔ گزارش', 'Report period')} max={plusDays(0)} calendar="gregory" />
          </div>
        </Demo>

        <Demo title={tr('ساعت — زمان تحویل', 'Time — delivery slot')} center>
          <TimePicker value={slot} onValueChange={setSlot} minuteStep={5} label={tr('ساعت تحویل', 'Delivery time')} />
        </Demo>

        <Demo title={tr('فیلد عددی — سبد خرید', 'Number field — cart')} center>
          <div style={{ display: 'flex', flexWrap: 'wrap', gap: '1.25rem' }}>
            <NumberField label={tr('تعداد', 'Quantity')} value={qty} onValueChange={setQty} min={1} max={20} />
            <NumberField label={tr('شفافیت', 'Opacity')} defaultValue={0.8} min={0} max={1} step={0.05} format={{ style: 'percent' }} />
          </div>
        </Demo>
      </div>
    </Section>
  );
}
