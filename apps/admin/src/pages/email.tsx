/**
 * #/email — the mail client: the full-page nx-email block over seeded
 * bilingual messages (times hang off the AGO anchors in ../data, so the list's
 * "today" column stays honest). The folder rail, unread badges, stars, the
 * keyboard-walked list and the reply composer are all the block's own; the
 * page adds the live search above it and a compose dialog that really appends
 * to the Sent folder. Message ids carry the language, so switching fa/en
 * rebuilds the list in the new words (read/star marks reset with the words —
 * the honest price of a stateful block fed new copy). Deterministic seeds: no
 * Math.random, no fetch.
 */
import { useState } from 'react';
import { Button, Dialog, Email, Field, Input, Textarea, toast, type EmailMessage } from '@nabuxai/ui-react';
import { AGO, NOW } from '../data';
import { useLang, useStrings, useTr } from '../lang';

const INTL = { fa: 'fa-IR', en: 'en-US' } as const;

/** A seeded message: both languages kept, built into the block's shape per render. */
type Seed = {
  id: string;
  fromFa: string;
  fromEn: string;
  email?: string;
  to?: string;
  subjectFa: string;
  subjectEn: string;
  bodyFa: string;
  bodyEn: string;
  time: number;
  unread?: boolean;
  starred?: boolean;
  /** 'inbox' by default; the block's 'starred' folder is virtual (starred). */
  folder?: string;
};

const SEED: Seed[] = [
  {
    id: 'm1',
    fromFa: 'مریم رضایی',
    fromEn: 'Maryam Rezaei',
    email: 'maryam@nabu.shop',
    subjectFa: 'سفارش ۱۲۴۸ کی می‌رسد؟',
    subjectEn: 'When will order 1248 arrive?',
    bodyFa: 'سلام!\nسفارش شمارهٔ ۱۲۴۸ را فردا صبح ثبت کردم.\nامکان بسته‌بندی هدیه هم هست؟\nممنون از پاسخ سریعتان.',
    bodyEn: 'Hi!\nI placed order #1248 yesterday morning.\nIs gift wrapping possible too?\nThanks for the quick reply.',
    time: AGO.minutes18.at,
    unread: true,
    starred: true,
  },
  {
    id: 'm2',
    fromFa: 'گالری رنگین',
    fromEn: 'Rangin Gallery',
    email: 'hello@rangin.gallery',
    subjectFa: 'فاکتور INV-2026-118 پرداخت شد',
    subjectEn: 'Invoice INV-2026-118 was paid',
    bodyFa: 'با سلام,\nفاکتور این ماه را واریز کردیم.\nلطفاً رسید را برای حسابداری ارسال کنید.\nگالری رنگین، اصفهان.',
    bodyEn: 'Hello,\nWe transferred this month’s invoice.\nPlease send the receipt to our accounts team.\nRangin Gallery, Isfahan.',
    time: AGO.hour1.at,
    unread: true,
  },
  {
    id: 'm3',
    fromFa: 'علی نیک‌پور',
    fromEn: 'Ali Nikpour',
    email: 'ali@nabu.shop',
    subjectFa: 'گزارش گفت‌وگوهای این هفته',
    subjectEn: 'This week’s conversation report',
    bodyFa: 'سلام حسین،\nگزارش هفتگی آماده است؛ فقط نمودارها مانده.\nتا فردا ظهر می‌فرستم.\nیک نکته: زمان پاسخ‌دهی ۱۸٪ بهتر شده.',
    bodyEn: 'Hi Hossein,\nThe weekly report is almost done; only the charts are left.\nI’ll send it by noon tomorrow.\nOne note: response time improved 18%.',
    time: AGO.hours2.at,
    starred: true,
  },
  {
    id: 'm4',
    fromFa: 'درگاه پرداخت نابو',
    fromEn: 'Nabu Pay',
    email: 'no-reply@nabupay.ir',
    subjectFa: 'تراکنش موفق — ۲٬۸۵۰٬۰۰۰ تومان',
    subjectEn: 'Successful payment — 2,850,000 Toman',
    bodyFa: 'تراکنش با شمارهٔ پیگیری ۸۴۱۲۷۳۶ با موفقیت انجام شد.\nاین پیام خودکار است؛ پاسخ ندهید.',
    bodyEn: 'Transaction 8412736 completed successfully.\nThis is an automatic message; do not reply.',
    time: AGO.hours5.at,
  },
  {
    id: 'm5',
    fromFa: 'سارا احمدی',
    fromEn: 'Sara Ahmadi',
    email: 'sara@nabu.shop',
    subjectFa: 'جلسهٔ بازبینی طراحی — فردا ۱۰',
    subjectEn: 'Design review — tomorrow at 10',
    bodyFa: 'یادآوری: بازبینی طراحی صفحهٔ محصول فردا ساعت ۱۰.\nفایل‌های جدید را در پوشهٔ «طراحی» گذاشته‌ام.',
    bodyEn: 'Reminder: the product page design review is tomorrow at 10.\nI’ve put the new files in the “Design” folder.',
    time: AGO.day1.at,
  },
  {
    id: 'm6',
    fromFa: 'مینا کریمی',
    fromEn: 'Mina Karimi',
    email: 'mina@nabu.shop',
    subjectFa: 'کیف چرمی نابو عالی بود!',
    subjectEn: 'The Nabu leather bag is perfect!',
    bodyFa: 'بسته رسید — واقعاً عالی بود!\nدوستم هم می‌خواهد؛ لینک محصول را می‌فرستید؟',
    bodyEn: 'The parcel arrived — it is really perfect!\nA friend wants one too; could you send the product link?',
    time: AGO.days2.at,
    folder: 'archive',
  },
  {
    id: 'm7',
    fromFa: 'خبرنامهٔ لاجورد',
    fromEn: 'Lapis newsletter',
    email: 'news@lapis.design',
    subjectFa: 'ده الگوی تازهٔ داشبورد',
    subjectEn: 'Ten fresh dashboard patterns',
    bodyFa: 'این هفته ده الگوی تازهٔ داشبورد را مرور کردیم.\nبرای لغو اشتراک، اینجا کلیک کنید.',
    bodyEn: 'This week we reviewed ten fresh dashboard patterns.\nClick here to unsubscribe.',
    time: AGO.days2.at,
    folder: 'trash',
  },
  {
    id: 'm8',
    fromFa: 'حسین مرادی',
    fromEn: 'Hossein Moradi',
    email: 'hossein@nabu.shop',
    to: 'علی نیک‌پور',
    subjectFa: 'پاسخ: گزارش گفت‌وگوهای این هفته',
    subjectEn: 'Re: This week’s conversation report',
    bodyFa: 'علی جان، عالیه.\nنمودارها را همان فردا بفرست تا در جلسهٔ ساعت ۱۰ استفاده کنیم.',
    bodyEn: 'Ali, great.\nSend the charts tomorrow so we can use them in the 10 o’clock meeting.',
    time: AGO.hours2.at,
    folder: 'sent',
  },
  {
    id: 'm9',
    fromFa: 'حسین مرادی',
    fromEn: 'Hossein Moradi',
    email: 'hossein@nabu.shop',
    to: 'گالری رنگین',
    subjectFa: 'پاسخ: فاکتور INV-2026-118 پرداخت شد',
    subjectEn: 'Re: Invoice INV-2026-118 was paid',
    bodyFa: 'واریزی را دریافت کردیم، ممنون!\nرسید را پیوست کردیم؛ چیزی لازم بود در خدمتیم.',
    bodyEn: 'We received the transfer, thank you!\nThe receipt is attached; let us know if you need anything.',
    time: AGO.day1.at,
    folder: 'sent',
  },
  {
    id: 'm10',
    fromFa: 'حسین مرادی',
    fromEn: 'Hossein Moradi',
    email: 'hossein@nabu.shop',
    to: 'سارا احمدی',
    subjectFa: '',
    subjectEn: '',
    bodyFa: 'دربارهٔ رنگ دکمهٔ «افزودن به سبد» هنوز مطمئن نیستم…',
    bodyEn: 'I’m still not sure about the “Add to cart” button colour…',
    time: AGO.hours5.at,
    folder: 'drafts',
  },
];

export function EmailPage() {
  const lang = useLang();
  const tr = useTr();
  const s = useStrings();
  const e = s.pages.email;
  const fa = lang === 'fa';
  const number = new Intl.NumberFormat(INTL[lang]);

  const [sent, setSent] = useState<Seed[]>([]);
  const [query, setQuery] = useState('');
  const [composing, setComposing] = useState(false);
  const [draft, setDraft] = useState({ to: '', subject: '', body: '' });

  const needle = query.trim().toLowerCase();
  const seeds = [...sent, ...SEED].filter(
    (item) => !needle || item.subjectFa.toLowerCase().includes(needle) || item.subjectEn.toLowerCase().includes(needle) || item.fromFa.toLowerCase().includes(needle) || item.fromEn.toLowerCase().includes(needle),
  );

  // ids carry the language: the block keeps its own copy of `messages` keyed by
  // id, so a language switch must look like a new list for the words to follow.
  const messages: EmailMessage[] = seeds.map((item) => ({
    id: `${item.id}-${lang}`,
    from: { name: item.folder === 'sent' || item.folder === 'drafts' ? e.you : fa ? item.fromFa : item.fromEn, email: item.email },
    to: item.folder === 'sent' || item.folder === 'drafts' ? item.to : e.you,
    subject: (fa ? item.subjectFa : item.subjectEn) || e.subjectNone,
    body: fa ? item.bodyFa : item.bodyEn,
    time: item.time,
    unread: item.unread,
    starred: item.starred,
    folder: item.folder,
  }));

  const unread = messages.filter((item) => item.unread).length;

  const send = () => {
    const to = draft.to.trim();
    const body = draft.body.trim();
    if (!to || !body) return;
    setSent((prev) => [
      { id: `s-${Date.now().toString(36)}`, fromFa: s.app.user.name, fromEn: s.app.user.name, email: 'hossein@nabu.shop', to, subjectFa: draft.subject.trim(), subjectEn: draft.subject.trim(), bodyFa: body, bodyEn: body, time: NOW, folder: 'sent' },
      ...prev,
    ]);
    setComposing(false);
    setDraft({ to: '', subject: '', body: '' });
    toast(tr('پیام ارسال شد', 'The message was sent'));
  };

  const canSend = draft.to.trim().length > 0 && draft.body.trim().length > 0;

  return (
    <div className="adm-dashboard">
      <div className="adm-wide" style={{ display: 'flex', flexWrap: 'wrap', gap: 'var(--nx-space-3)', alignItems: 'center', justifyContent: 'space-between' }}>
        <div style={{ display: 'flex', flexWrap: 'wrap', gap: 'var(--nx-space-2)', alignItems: 'center' }}>
          <Input
            type="search"
            value={query}
            onChange={(event) => setQuery(event.target.value)}
            placeholder={e.search}
            aria-label={s.common.search}
            startAddon="search"
            style={{ inlineSize: 'min(17rem, 60vw)' }}
          />
          {unread > 0 && (
            <span className="nx-badge" data-tone="accent" data-dot="">
              {number.format(unread)} {e.unread}
            </span>
          )}
        </div>
        <Button variant="primary" icon="edit" onClick={() => setComposing(true)}>
          {e.action}
        </Button>
      </div>

      <Email
        className="adm-wide"
        height="36rem"
        messages={messages}
        label={e.title}
        labels={{
          mail: e.title,
          inbox: e.inbox,
          starred: e.starred,
          sent: e.sent,
          drafts: e.drafts,
          archive: e.archive,
          trash: e.trash,
          noMessage: e.selectMessage,
          empty: e.emptyFolder,
          from: e.from,
          to: e.to,
          unread: e.unread,
        }}
        onReply={() => toast(tr('پاسخ ارسال شد', 'The reply was sent'))}
      />

      <Dialog
        open={composing}
        onOpenChange={setComposing}
        size="md"
        title={e.compose}
        footer={
          <>
            <Button
              variant="secondary"
              icon="trash"
              onClick={() => {
                setComposing(false);
                setDraft({ to: '', subject: '', body: '' });
              }}
            >
              {e.discard}
            </Button>
            <Button variant="primary" icon="arrow-right" disabled={!canSend} onClick={send}>
              {e.send}
            </Button>
          </>
        }
      >
        <div style={{ display: 'grid', gap: 'var(--nx-space-3)' }}>
          <Field label={e.to} required>
            <Input value={draft.to} onChange={(event) => setDraft({ ...draft, to: event.target.value })} autoComplete="off" />
          </Field>
          <Field label={e.subject} hint={e.subjectNone}>
            <Input value={draft.subject} onChange={(event) => setDraft({ ...draft, subject: event.target.value })} autoComplete="off" />
          </Field>
          <Field label={e.compose} required>
            <Textarea rows={5} value={draft.body} onChange={(event) => setDraft({ ...draft, body: event.target.value })} />
          </Field>
          <p style={{ margin: 0, color: 'var(--nx-text-subtle)', fontSize: 'var(--nx-text-sm)' }}>
            {e.sentAt}: {new Intl.DateTimeFormat(INTL[lang], { dateStyle: 'medium', timeStyle: 'short' }).format(NOW)}
          </p>
        </div>
      </Dialog>
    </div>
  );
}
