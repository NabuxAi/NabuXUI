import { useState } from 'react';
import {
  CompareSlider,
  HoldToConfirm,
  InlineEdit,
  Lightbox,
  Odometer,
  PasswordStrength,
  SlideToConfirm,
  SortableList,
  SwipeActions,
  UndoSnackbars,
  toast,
  useUndoSnackbar,
} from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

/** Inline SVG scenes, so the demos need no image files. */
const scene = (body: string, w = 1200, h = 800) => `data:image/svg+xml,${encodeURIComponent(`<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ${w} ${h}">${body}</svg>`)}`;

const landscape = (sky: string, sun: string, hill: string, field: string, front: string) =>
  scene(
    `<rect width="1200" height="800" fill="${sky}"/><circle cx="900" cy="200" r="80" fill="${sun}"/>` +
      `<path d="M0 540 L240 300 L430 490 L640 250 L900 540 L1200 380 V800 H0Z" fill="${hill}"/>` +
      `<path d="M0 620 Q600 540 1200 630 V800 H0Z" fill="${field}"/><path d="M0 700 Q500 660 1200 710 V800 H0Z" fill="${front}"/>`,
  );

const RAW = landscape('#9aa3ad', '#d8d8d8', '#6f757c', '#7b8077', '#5c605a');
const GRADED = landscape('#ffcf8f', '#fff2c4', '#5b5ea6', '#2f9e6e', '#1f6f50');

const PHOTOS = [
  { src: landscape('#ffb37a', '#ffe2a8', '#5b4b8a', '#2b3a67', '#1d2a52'), en: 'Sunset over the gulf', fa: 'غروب روی خلیج' },
  { src: landscape('#cfe8ff', '#fff6c9', '#6b7fa8', '#3f7d4e', '#2d5e39'), en: 'Snow line on Damavand', fa: 'خط برف دماوند' },
  { src: landscape('#1e2a4a', '#f4d35e', '#2ec4b6', '#0b7a75', '#13213f'), en: 'Isfahan after dark', fa: 'اصفهان پس از تاریکی' },
  { src: landscape('#f6e7c8', '#edae49', '#d1495b', '#c9a66b', '#8f2d56'), en: 'Spice bazaar', fa: 'بازار ادویه' },
  { src: landscape('#0f1b2d', '#ffffff', '#d9a066', '#b5793f', '#8a5a2b'), en: 'Desert at 2 a.m.', fa: 'کویر، ساعت ۲ بامداد' },
  { src: landscape('#e8f1e4', '#fff8d6', '#7aa95c', '#3e7d3a', '#2f5e2c'), en: 'Tea terraces', fa: 'پلکان‌های چای' },
];

interface Step {
  id: string;
  fa: string;
  en: string;
}

const STEPS: Step[] = [
  { id: 'freeze', fa: 'توقف کد', en: 'Code freeze' },
  { id: 'qa', fa: 'آزمون کیفیت', en: 'QA pass' },
  { id: 'notes', fa: 'یادداشت انتشار', en: 'Release notes' },
  { id: 'ship', fa: 'انتشار روی تولید', en: 'Ship to production' },
];

export function InteractBlocks() {
  const tr = useTr();
  const [title, setTitle] = useState('');
  const [orders, setOrders] = useState(1284);
  const [steps, setSteps] = useState(STEPS);
  const [split, setSplit] = useState(50);
  const [mail, setMail] = useState(['a', 'b']);
  const snack = useUndoSnackbar();

  return (
    <Section
      id="interact-blocks"
      eyebrow={tr('بلوک‌ها', 'Blocks')}
      title={tr('تعامل‌هایی که محصول به آن تکیه می‌کند', 'Interactions a product leans on')}
      description={tr(
        'نگه‌دار یا بکش تا تأیید شود، ویرایش درجا، عددهایی که می‌غلتند، بازگردانی، مرتب‌سازی، لایت‌باکس، مقایسه، کشیدن برای کنش و سنجش گذرواژه — همه با کیبورد و RTL.',
        'Hold or slide to confirm, edit in place, numbers that roll, undo, reorder, a lightbox, compare, swipe for actions and a password meter — all keyboard-ready and RTL-aware.',
      )}
      code={{
        react: `<HoldToConfirm label="Hold to delete" doneLabel="Deleted" onConfirm={remove} />
<SlideToConfirm label="Slide to pay $49" doneLabel="Paid" onConfirm={pay} />
<InlineEdit label="Project name" value={name} onValueChange={setName} onSave={rename} />
<Odometer value={orders} />
<UndoSnackbars {...snack.bind} onUndo={restore} />
<SortableList items={steps} getKey={(s) => s.id} renderItem={(s) => s.title} onReorder={setSteps} />
<Lightbox images={photos} columns={3} />
<CompareSlider before={{ src: raw, alt: 'Raw' }} after={{ src: graded, alt: 'Graded' }} />
<SwipeActions end={[{ id: 'delete', label: 'Delete', icon: 'trash', tone: 'danger', primary: true }]}>…</SwipeActions>
<PasswordStrength label="Password" userInputs={[name, email]} />`,
        blade: `<x-nx::hold-to-confirm label="Hold to delete" done-label="Deleted" action="delete" />
<x-nx::slide-to-confirm label="Slide to pay $49" done-label="Paid" action="pay" />
<x-nx::inline-edit label="Project name" wire:model="name" action="rename" />
<x-nx::odometer :value="$orders" />
<x-nx::undo-snackbar />  {{-- $this->dispatch('nx-undo', message: …, undo: 'restore', params: [$id]) --}}
<x-nx::sortable-list :items="$steps" action="reorder" />
<x-nx::lightbox :images="$photos" :columns="3" />
<x-nx::compare-slider :before="$raw" :after="$graded" wire:model.live="split" />
<x-nx::swipe-actions :end="$actions">…</x-nx::swipe-actions>
<x-nx::password-strength wire:model="password" :user-inputs="[$name, $email]" />`,
      }}
    >
      <div className="sc-demos">
        <Demo title={tr('نگه‌دار تا تأیید شود', 'Hold to confirm')} center>
          <div style={{ display: 'flex', gap: '0.75rem', flexWrap: 'wrap', justifyContent: 'center' }}>
            <HoldToConfirm label={tr('برای حذف نگه دارید', 'Hold to delete')} doneLabel={tr('حذف شد', 'Deleted')} hint={tr('برای حذف نگه دارید', 'Press and hold to delete')} onConfirm={() => toast.success(tr('پروژه حذف شد', 'Project deleted'))} />
            <HoldToConfirm variant="ring" size="sm" icon="lock" label={tr('ابطال', 'Revoke')} doneLabel={tr('باطل شد', 'Revoked')} />
          </div>
        </Demo>

        <Demo title={tr('بکش تا تأیید شود', 'Slide to confirm')} center>
          <SlideToConfirm label={tr('بکشید تا ۴۹ دلار پرداخت شود', 'Slide to pay $49')} doneLabel={tr('پرداخت شد', 'Paid')} resetAfter={3000} onConfirm={() => toast.success(tr('رسید ارسال شد', 'Receipt sent'))} />
        </Demo>

        <Demo title={tr('ویرایش درجا', 'Inline edit')}>
          <div style={{ display: 'grid', gap: '0.5rem' }}>
            <span style={{ color: 'var(--nx-text-subtle)', fontSize: 'var(--nx-text-sm)' }}>{tr('پروژه‌ها /', 'Projects /')}</span>
            <InlineEdit
              size="lg"
              label={tr('نام پروژه', 'Project name')}
              value={title || tr('نقشهٔ راه فصل سوم', 'Q3 roadmap')}
              onValueChange={setTitle}
              required
              requiredLabel={tr('نام لازم است', 'A name is required')}
              savingLabel={tr('در حال ذخیره…', 'Saving…')}
              onSave={(next) => new Promise<string | void>((resolve) => setTimeout(() => resolve(next.toLowerCase().includes('error') ? tr('این نام گرفته شده', 'That name is taken') : undefined), 700))}
            />
          </div>
        </Demo>

        <Demo title={tr('کیلومترشمار', 'Odometer')} center>
          <div style={{ display: 'grid', gap: '0.75rem', justifyItems: 'center' }}>
            <Odometer value={orders} style={{ font: '700 var(--nx-text-4xl) / 1 var(--nx-font-display)' }} />
            <div style={{ display: 'flex', gap: '0.5rem' }}>
              <button type="button" className="nx-button" data-variant="secondary" data-size="sm" onClick={() => setOrders((n) => n + Math.ceil(Math.random() * 120))}>
                <span className="nx-button-label">+</span>
              </button>
              <button type="button" className="nx-button" data-variant="secondary" data-size="sm" onClick={() => setOrders((n) => Math.max(0, n - Math.ceil(Math.random() * 120)))}>
                <span className="nx-button-label">−</span>
              </button>
            </div>
          </div>
        </Demo>

        <Demo title={tr('اسنک‌بار بازگردانی', 'Undo snackbar')}>
          <ul style={{ display: 'grid', gap: '0.5rem', margin: 0, padding: 0, listStyle: 'none' }}>
            {[
              { id: 'a', text: tr('قرارداد شعبهٔ تبریز', 'Contract for the Tabriz branch') },
              { id: 'b', text: tr('فاکتور ۱۰۴۳', 'Invoice #1043') },
            ]
              .filter((row) => mail.includes(row.id))
              .map((row) => (
                <li key={row.id} style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', gap: '0.5rem', padding: '0.6rem 0.9rem', border: '1px solid var(--nx-border)', borderRadius: 'var(--nx-radius-lg)' }}>
                  <span>{row.text}</span>
                  <button
                    type="button"
                    className="nx-button"
                    data-variant="ghost"
                    data-size="sm"
                    onClick={() => {
                      setMail((list) => list.filter((id) => id !== row.id));
                      snack.push({ id: row.id, message: tr(`بایگانی شد: ${row.text}`, `Archived: ${row.text}`), icon: 'folder' });
                    }}
                  >
                    <span className="nx-button-label">{tr('بایگانی', 'Archive')}</span>
                  </button>
                </li>
              ))}
          </ul>
          <UndoSnackbars {...snack.bind} undoLabel={tr('بازگردانی', 'Undo')} onUndo={(id) => setMail((list) => (list.includes(String(id)) ? list : [...list, String(id)].sort()))} />
        </Demo>

        <Demo title={tr('فهرست مرتب‌شدنی', 'Sortable list')}>
          <SortableList
            items={steps}
            getKey={(step) => step.id}
            getLabel={(step) => tr(step.fa, step.en)}
            onReorder={setSteps}
            label={tr('چک‌لیست انتشار', 'Release checklist')}
            handleLabel={tr('جابه‌جایی {name}', 'Reorder {name}')}
            renderItem={(step, i) => (
              <span style={{ display: 'flex', justifyContent: 'space-between', gap: '0.5rem' }}>
                <strong>{tr(step.fa, step.en)}</strong>
                <span style={{ color: 'var(--nx-text-subtle)' }}>{i + 1}</span>
              </span>
            )}
          />
        </Demo>

        <Demo title={tr('لایت‌باکس', 'Lightbox')} wide>
          <Lightbox
            columns={3}
            label={tr('سفرنامه', 'Travel journal')}
            images={PHOTOS.map((photo) => ({ src: photo.src, alt: tr(photo.fa, photo.en), caption: tr(photo.fa, photo.en) }))}
          />
        </Demo>

        <Demo title={tr('اسلایدر مقایسه', 'Compare slider')}>
          <CompareSlider value={split} onValueChange={setSplit} before={{ src: RAW, alt: tr('عکس خام', 'Raw photo') }} after={{ src: GRADED, alt: tr('عکس اصلاح‌شده', 'Graded photo') }} beforeLabel={tr('خام', 'Raw')} afterLabel={tr('اصلاح‌شده', 'Graded')} />
        </Demo>

        <Demo title={tr('کنش‌های کشیدنی', 'Swipe actions')}>
          <SwipeActions
            start={[{ id: 'read', label: tr('خوانده', 'Read'), icon: 'check', tone: 'accent', primary: true, onSelect: () => toast(tr('خوانده شد', 'Marked as read')) }]}
            end={[
              { id: 'archive', label: tr('بایگانی', 'Archive'), icon: 'folder', tone: 'warning' },
              { id: 'delete', label: tr('حذف', 'Delete'), icon: 'trash', tone: 'danger', primary: true, onSelect: () => toast.success(tr('حذف شد', 'Deleted')) },
            ]}
          >
            <div style={{ padding: '0.85rem 1rem' }}>
              <strong>{tr('سارا احمدی', 'Sara Ahmadi')}</strong>
              <div style={{ color: 'var(--nx-text-muted)', fontSize: 'var(--nx-text-sm)' }}>{tr('قرارداد شعبهٔ تبریز', 'Contract for the Tabriz branch')}</div>
            </div>
          </SwipeActions>
        </Demo>

        <Demo title={tr('قدرت گذرواژه', 'Password strength')}>
          <PasswordStrength
            label={tr('گذرواژه', 'Password')}
            userInputs={['Hossein Rahimi']}
            labels={tr('fa', 'en') === 'fa'
              ? {
                  scores: ['خیلی ضعیف', 'ضعیف', 'متوسط', 'خوب', 'قوی'],
                  rules: { length: 'دست‌کم {min} نویسه', lower: 'یک حرف کوچک', upper: 'یک حرف بزرگ', number: 'یک رقم', symbol: 'یک نماد' },
                  show: 'نمایش گذرواژه',
                  hide: 'پنهان‌کردن گذرواژه',
                  strength: 'قدرت',
                  met: 'انجام شد',
                  unmet: 'هنوز نه',
                }
              : undefined}
          />
        </Demo>
      </div>
    </Section>
  );
}
