import { useEffect, useRef, useState } from 'react';
import {
  Button,
  type ButtonStatus,
  Checkbox,
  CopyButton,
  Field,
  FileDrop,
  type FileItem,
  IconButton,
  Input,
  LikeButton,
  OtpInput,
  PromptInput,
  RadioGroup,
  Select,
  Slider,
  Switch,
  Textarea,
  toast,
} from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

export function ButtonsSection() {
  const tr = useTr();
  const [status, setStatus] = useState<ButtonStatus>('idle');
  const [fail, setFail] = useState(false);

  const pay = () => {
    setStatus('loading');
    setTimeout(() => {
      setStatus(fail ? 'error' : 'success');
      setFail((f) => !f);
      setTimeout(() => setStatus('idle'), 1600);
    }, 1400);
  };

  return (
    <Section
      id="buttons"
      eyebrow={tr('دکمه', 'Button')}
      title={tr('دکمه‌هایی که جواب می‌دهند', 'Buttons that answer back')}
      description={tr(
        'وضعیت بارگذاری، موفقیت و خطا بدون تغییر عرض دکمه. درخشش، جابه‌جایی متن، حالت مغناطیسی و موج لمس هم آماده‌اند.',
        'Loading, success and error states that never change the width, plus shine, sliding labels, magnetic pull and a press ripple.',
      )}
      code={{
        react: `<Button variant="primary" status={status} onClick={pay}>Pay $49</Button>
<Button variant="glow" magnetic ripple>Talk to Nabu</Button>
<Button variant="secondary" effect="slide">Hover me</Button>
<CopyButton value="npm i @nabuxai/ui-react" />
<LikeButton count={128} />`,
        blade: `{{-- wire:loading turns the label into a spinner, width unchanged --}}
<x-nx::button variant="primary" wire:click="pay">پرداخت</x-nx::button>
<x-nx::button variant="glow" magnetic ripple>با نبو حرف بزنید</x-nx::button>
<x-nx::button effect="slide">نشانگر را بیاورید</x-nx::button>
<x-nx::copy-button value="composer require nabuxai/nabuxui" />
<x-nx::like-button :count="$post->likes" wire:click="like" />`,
      }}
    >
      <div className="sc-demos">
        <Demo title={tr('گونه‌ها', 'Variants')}>
          <div className="sc-row">
            <Button variant="primary">{tr('اصلی', 'Primary')}</Button>
            <Button variant="secondary">{tr('ثانویه', 'Secondary')}</Button>
            <Button variant="outline">{tr('حاشیه‌دار', 'Outline')}</Button>
            <Button variant="ghost">{tr('شبح', 'Ghost')}</Button>
            <Button variant="danger">{tr('حذف', 'Delete')}</Button>
            <Button variant="gold">{tr('طلایی', 'Gold')}</Button>
            <Button variant="inverse">{tr('معکوس', 'Inverse')}</Button>
            <Button variant="link">{tr('پیوند', 'Link')}</Button>
          </div>
        </Demo>
        <Demo title={tr('اندازه‌ها و آیکون', 'Sizes and icons')}>
          <div className="sc-row">
            <Button size="xs">XS</Button>
            <Button size="sm" icon="sparkles">
              {tr('کوچک', 'Small')}
            </Button>
            <Button icon="mail">{tr('متوسط', 'Medium')}</Button>
            <Button size="lg" shape="pill" iconEnd="arrow-right" variant="primary">
              {tr('بزرگ', 'Large')}
            </Button>
            <IconButton icon="bell" label={tr('اعلان‌ها', 'Notifications')} />
            <IconButton icon="edit" label={tr('ویرایش', 'Edit')} variant="secondary" />
          </div>
        </Demo>
        <Demo title={tr('وضعیت در یک دکمه', 'State in one button')}>
          <div className="sc-row">
            <Button variant="primary" size="lg" status={status} onClick={pay} icon="lock">
              {tr('پرداخت ۴۹۰ هزار تومان', 'Pay $49')}
            </Button>
          </div>
          <p className="sc-demo-title">{tr('هر بار یک‌بار موفق و یک‌بار ناموفق.', 'Alternates between success and failure.')}</p>
        </Demo>
        <Demo title={tr('جلوه‌ها', 'Effects')}>
          <div className="sc-row">
            <Button variant="glow" shape="pill" magnetic ripple icon="sparkles">
              {tr('مغناطیسی', 'Magnetic')}
            </Button>
            <Button variant="primary" effect="shine">
              {tr('درخشش', 'Shine')}
            </Button>
            <Button variant="secondary" effect="slide">
              {tr('جابه‌جایی متن', 'Slide label')}
            </Button>
            <Button variant="secondary" ripple>
              {tr('موج لمس', 'Ripple')}
            </Button>
          </div>
        </Demo>
        <Demo title={tr('کپی و پسندیدن', 'Copy and like')}>
          <div className="sc-row">
            <CopyButton value="pnpm add @nabuxai/ui-react" variant="secondary">
              pnpm add @nabuxai/ui-react
            </CopyButton>
            <CopyButton value="composer require nabuxai/nabuxui" variant="secondary" />
            <LikeButton count={128} />
            <LikeButton defaultLiked count={2048} />
          </div>
        </Demo>
      </div>
    </Section>
  );
}

export function InputsSection() {
  const tr = useTr();
  const [email, setEmail] = useState('nabu@');
  const [files, setFiles] = useState<FileItem[]>([]);
  const [streaming, setStreaming] = useState(false);
  const [attachments, setAttachments] = useState([{ id: 'a1', name: tr('گزارش-فصل-سوم.pdf', 'q3-report.pdf') }]);
  const timers = useRef<number[]>([]);
  useEffect(() => () => timers.current.forEach(clearInterval), []);

  const upload = (picked: File[]) => {
    const start = files.length;
    setFiles((current) => [...current, ...picked.map((f) => ({ name: f.name, size: f.size, progress: 0, status: 'uploading' as const }))]);
    picked.forEach((_, n) => {
      const index = start + n;
      const id = window.setInterval(() => {
        setFiles((current) =>
          current.map((file, i) => {
            if (i !== index || file.status !== 'uploading') return file;
            const progress = Math.min(100, (file.progress ?? 0) + 7 + Math.random() * 18);
            return progress >= 100 ? { ...file, progress: 100, status: 'done' } : { ...file, progress };
          }),
        );
      }, 260);
      timers.current.push(id);
      setTimeout(() => clearInterval(id), 6000);
    });
  };

  const invalid = email.length > 0 && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);

  return (
    <Section
      id="inputs"
      eyebrow={tr('ورودی', 'Input')}
      title={tr('فرم‌هایی که با کاربر حرف می‌زنند', 'Forms that talk back')}
      description={tr(
        'هر کنترل همان عنصر بومی مرورگر است؛ برای همین wire:model، ارسال فرم، اعتبارسنجی و پرکردن خودکار بدون ترفند کار می‌کنند.',
        'Every control is the browser’s own element, so wire:model, form posts, validation and autofill work without tricks.',
      )}
      code={{
        react: `<Field label="Email" error={errors.email}>
  <Input type="email" startAddon="mail" value={email} onChange={…} />
</Field>
<OtpInput length={6} onComplete={verify} />
<Slider value={temperature} onValueChange={setTemperature} />
<PromptInput onSubmit={ask} streaming={isStreaming} onStop={stop} />`,
        blade: `{{-- The error comes straight from $errors, by field name --}}
<x-nx::input label="ایمیل" name="email" type="email" icon="mail" wire:model.live.blur="email" />
<x-nx::otp length="6" wire:model.live="code" />
<x-nx::slider label="دما" min="0" max="2" step="0.1" wire:model.live="temperature" />
<x-nx::file-drop wire:model="attachments" multiple />
<x-nx::prompt wire:submit="ask" wire:model="message" />`,
        inertia: `const form = useForm({ email: '' });

<Field label="Email" error={form.errors.email}>
  <Input {...bind(form, 'email')} />
</Field>
<Button type="submit" status={submitStatus(form)}>Save</Button>`,
      }}
    >
      <div className="sc-demos">
        <Demo title={tr('فیلد با اعتبارسنجی', 'Field with validation')}>
          <Field label={tr('ایمیل کاری', 'Work email')} hint={tr('رسید و گزارش‌ها به این نشانی می‌رود.', 'Receipts and reports go here.')} error={invalid ? tr('نشانی ایمیل کامل نیست.', 'That email address is incomplete.') : undefined} required>
            <Input type="email" dir="ltr" startAddon="mail" value={email} onChange={(event) => setEmail(event.target.value)} autoComplete="email" />
          </Field>
          <Field label={tr('نشانی سایت', 'Website')}>
            <Input dir="ltr" startAddon="https://" endAddon="globe" placeholder="nabuxai.com" />
          </Field>
        </Demo>
        <Demo title={tr('متن، انتخاب و گزینه‌ها', 'Text, select and choices')}>
          <Field label={tr('پیام', 'Message')}>
            <Textarea placeholder={tr('بنویسید؛ کادر با متن بزرگ می‌شود…', 'Type — the box grows with you…')} maxRows={8} />
          </Field>
          <Field label={tr('مدل', 'Model')}>
            <Select
              defaultValue="nabu-pro"
              options={[
                { value: 'nabu-lite', label: 'Nabu Lite' },
                { value: 'nabu-pro', label: 'Nabu Pro' },
                { value: 'nabu-max', label: 'Nabu Max' },
              ]}
            />
          </Field>
        </Demo>
        <Demo title={tr('تیک، رادیو و سوئیچ', 'Checkbox, radio, switch')}>
          <Checkbox label={tr('خبرنامهٔ ماهانه را می‌خواهم', 'Send me the monthly newsletter')} description={tr('ماهی یک ایمیل، بدون تبلیغ.', 'One email a month, no ads.')} defaultChecked />
          <Checkbox label={tr('همهٔ پروژه‌ها', 'All projects')} indeterminate />
          <RadioGroup
            name="plan"
            legend={tr('صورت‌حساب', 'Billing')}
            defaultValue="monthly"
            orientation="horizontal"
            options={[
              { value: 'monthly', label: tr('ماهانه', 'Monthly') },
              { value: 'yearly', label: tr('سالانه', 'Yearly') },
            ]}
          />
          <Switch label={tr('پاسخ خودکار در تلگرام', 'Auto-reply on Telegram')} defaultChecked />
          <Switch label={tr('حالت تمرکز', 'Focus mode')} size="sm" />
        </Demo>
        <Demo title={tr('کد یک‌بارمصرف و اسلایدر', 'One-time code and slider')}>
          <OtpInput length={6} separatorAfter={3} onComplete={(code) => toast.success(tr('کد تأیید شد', 'Code verified'), { description: code })} />
          <Slider min={0} max={2} step={0.1} defaultValue={0.7} formatValue={(v) => v.toFixed(1)} startLabel={tr('دقیق', 'Precise')} endLabel={tr('خلاق', 'Creative')} aria-label={tr('دما', 'Temperature')} />
        </Demo>
        <Demo title={tr('بارگذاری فایل', 'File upload')}>
          <FileDrop multiple onFiles={upload} files={files} hint={tr('PDF، تصویر یا صوت تا ۲۰ مگابایت', 'PDF, image or audio up to 20 MB')} />
        </Demo>
        <Demo title={tr('ورودی گفت‌وگو با هوش مصنوعی', 'AI prompt input')} wide>
          <PromptInput
            placeholder={tr('از نبو بپرسید…', 'Ask Nabu anything…')}
            attachments={attachments}
            onRemoveAttachment={(id) => setAttachments((a) => a.filter((f) => f.id !== id))}
            onAttach={(picked) => setAttachments((a) => [...a, ...picked.map((f, i) => ({ id: `${Date.now()}-${i}`, name: f.name }))])}
            streaming={streaming}
            onStop={() => setStreaming(false)}
            onSubmit={() => {
              setStreaming(true);
              setTimeout(() => setStreaming(false), 2600);
            }}
            toolbar={
              <Button size="sm" variant="ghost" icon="globe">
                {tr('جست‌وجوی وب', 'Web search')}
              </Button>
            }
          />
        </Demo>
      </div>
    </Section>
  );
}
