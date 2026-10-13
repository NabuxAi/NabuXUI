/**
 * Calendar — the month grid with a controlled selection and view: picking a
 * day opens the block's own agenda panel, the "today" button brings both the
 * month and the selection home, and the "new event" dialog really appends to
 * the state (the chip lands on its day at once). Beside the grid sits the
 * upcoming list — the next six events as day chips in the locale's calendar.
 * Events are seeded on fixed offsets from the shared NOW, so the page looks
 * the same on every load; fa gets the Persian week (starting Saturday).
 */
import { useState } from 'react';
import { Button, Calendar, Card, Dialog, Field, Input, toast, type CalendarEvent, type CalendarTone } from '@nabuxai/ui-react';
import { useLang, useStrings, useTr } from '../lang';
import { NOW } from '../data';

const INTL = { fa: 'fa-IR', en: 'en-US' } as const;

/** A local-day ISO `offset` days from the shared NOW — how the block reads days too. */
function isoFromOffset(offset: number) {
  const day = new Date(NOW);
  day.setDate(day.getDate() + offset);
  return `${day.getFullYear()}-${String(day.getMonth() + 1).padStart(2, '0')}-${String(day.getDate()).padStart(2, '0')}`;
}

type PanelEvent = {
  id: string;
  date: string;
  fa: string;
  en: string;
  timeFa?: string;
  timeEn?: string;
  tone?: CalendarTone;
};

const SEED: PanelEvent[] = [
  { id: 'e1', date: isoFromOffset(-3), fa: 'پشتیبان‌گیری ماهانه', en: 'The monthly backup', tone: 'success' },
  { id: 'e2', date: isoFromOffset(-1), fa: 'بازگشت از مرخصی سارا', en: 'Sara is back from leave', tone: 'success' },
  { id: 'e3', date: isoFromOffset(0), fa: 'جلسهٔ تیم فروش', en: 'The sales team meets', timeFa: '۱۰:۰۰', timeEn: '10:00', tone: 'accent' },
  { id: 'e4', date: isoFromOffset(0), fa: 'ارسال خبرنامه', en: 'Send the newsletter', timeFa: '۱۶:۳۰', timeEn: '16:30' },
  { id: 'e5', date: isoFromOffset(1), fa: 'مهلت پرداخت فاکتور ۱۲۴۸', en: 'Invoice 1248 falls due', tone: 'danger' },
  { id: 'e6', date: isoFromOffset(3), fa: 'جلسهٔ طراحی صفحهٔ محصول', en: 'Product page design review', timeFa: '۱۴:۰۰', timeEn: '14:00', tone: 'info' },
  { id: 'e7', date: isoFromOffset(5), fa: 'مصاحبهٔ پشتیبانی — نامزد دوم', en: 'Support interview — second candidate', timeFa: '۱۱:۰۰', timeEn: '11:00', tone: 'accent' },
  { id: 'e8', date: isoFromOffset(8), fa: 'تحویل عکسهای محصول', en: 'Product photos delivered', tone: 'warning' },
  { id: 'e9', date: isoFromOffset(12), fa: 'گزارش مالی ماهانه', en: 'The monthly financial report', tone: 'gold' },
  { id: 'e10', date: isoFromOffset(16), fa: 'کارگاه تجربهٔ مشتری', en: 'The customer experience workshop', timeFa: '۱۳:۰۰', timeEn: '13:00', tone: 'info' },
  { id: 'e11', date: isoFromOffset(21), fa: 'بازبینی طرحهای نوروز', en: 'Review the Nowruz designs', tone: 'accent' },
];

export function CalendarPage() {
  const lang = useLang();
  const tr = useTr();
  const s = useStrings();
  const c = s.pages.calendar;
  const intl = INTL[lang];
  const today = isoFromOffset(0);

  const [events, setEvents] = useState<PanelEvent[]>(SEED);
  const [selected, setSelected] = useState<string | null>(today);
  const [viewing, setViewing] = useState<string>(() => today.slice(0, 7));
  const [composing, setComposing] = useState(false);
  const [draft, setDraft] = useState({ label: '', date: today, time: '' });

  const weekday = new Intl.DateTimeFormat(intl, { weekday: 'short', day: 'numeric', timeZone: 'UTC' });
  const dayChip = (iso: string) => weekday.format(new Date(`${iso}T00:00:00Z`));

  const calendarEvents: CalendarEvent[] = events.map((event) => ({
    date: event.date,
    label: tr(event.fa, event.en),
    time: event.timeFa ? tr(event.timeFa, event.timeEn ?? event.timeFa) : undefined,
    tone: event.tone,
  }));

  const upcoming = events
    .filter((event) => event.date >= today)
    .sort((a, b) => (a.date < b.date ? -1 : a.date > b.date ? 1 : 0))
    .slice(0, 6);

  const goToday = () => {
    setSelected(today);
    setViewing(today.slice(0, 7));
  };

  const addEvent = () => {
    const label = draft.label.trim();
    if (!label || !/^\d{4}-\d{2}-\d{2}$/.test(draft.date)) return;
    const time = draft.time.trim();
    setEvents((prev) => [...prev, { id: `e-${Date.now().toString(36)}`, date: draft.date, fa: label, en: label, timeFa: time || undefined, timeEn: time || undefined, tone: 'accent' }]);
    setSelected(draft.date);
    setViewing(draft.date.slice(0, 7));
    setComposing(false);
    setDraft({ label: '', date: draft.date, time: '' });
    toast(c.added);
  };

  const canAdd = draft.label.trim().length > 0 && /^\d{4}-\d{2}-\d{2}$/.test(draft.date);

  return (
    <div className="adm-dashboard">
      <div style={{ display: 'grid', gap: 'var(--nx-space-4)' }}>
        <div className="adm-welcome-actions">
          <Button variant="primary" icon="plus" onClick={() => setComposing(true)}>
            {c.action}
          </Button>
          <Button variant="secondary" icon="grid" onClick={goToday}>
            {c.today}
          </Button>
        </div>
        <Calendar
          events={calendarEvents}
          value={selected}
          onValueChange={setSelected}
          month={viewing}
          onMonthChange={setViewing}
          weekStart={lang === 'fa' ? 6 : 0}
          label={c.title}
          emptyText={c.noEvents}
        />
      </div>

      <div className="adm-side">
        <Card title={c.upcoming} icon="bell">
          {upcoming.length > 0 ? (
            <ul style={{ display: 'grid', gap: 'var(--nx-space-2)', margin: 0, padding: 0, listStyle: 'none' }}>
              {upcoming.map((event) => (
                <li key={event.id} style={{ display: 'flex', alignItems: 'center', gap: 'var(--nx-space-3)', fontSize: 'var(--nx-text-sm)' }}>
                  <span className="nx-badge" data-tone={event.tone}>
                    {dayChip(event.date)}
                  </span>
                  <span>{tr(event.fa, event.en)}</span>
                  {event.timeFa && (
                    <span dir="ltr" style={{ marginInlineStart: 'auto', color: 'var(--nx-text-subtle)', font: 'var(--nx-text-xs) var(--nx-font-mono)' }}>
                      {tr(event.timeFa, event.timeEn ?? event.timeFa)}
                    </span>
                  )}
                </li>
              ))}
            </ul>
          ) : (
            <p style={{ margin: 0, color: 'var(--nx-text-muted)' }}>{c.noEvents}</p>
          )}
        </Card>
      </div>

      <Dialog
        open={composing}
        onOpenChange={setComposing}
        size="sm"
        title={c.action}
        footer={
          <>
            <Button variant="secondary" onClick={() => setComposing(false)}>
              {s.common.cancel}
            </Button>
            <Button variant="primary" icon="plus" disabled={!canAdd} onClick={addEvent}>
              {s.common.add}
            </Button>
          </>
        }
      >
        <div style={{ display: 'grid', gap: 'var(--nx-space-3)' }}>
          <Field label={c.fieldTitle} required>
            <Input value={draft.label} onChange={(event) => setDraft({ ...draft, label: event.target.value })} autoComplete="off" />
          </Field>
          <Field label={c.fieldDay} required>
            <Input type="date" dir="ltr" value={draft.date} onChange={(event) => setDraft({ ...draft, date: event.target.value })} />
          </Field>
          <Field label={c.fieldTime}>
            <Input type="time" dir="ltr" value={draft.time} onChange={(event) => setDraft({ ...draft, time: event.target.value })} />
          </Field>
        </div>
      </Dialog>
    </div>
  );
}
