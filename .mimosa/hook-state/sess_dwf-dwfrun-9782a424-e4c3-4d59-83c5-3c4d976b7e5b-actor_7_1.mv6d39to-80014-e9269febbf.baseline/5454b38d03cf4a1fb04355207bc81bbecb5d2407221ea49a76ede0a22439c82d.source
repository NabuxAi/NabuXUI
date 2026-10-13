/** Showcase demos: Buttons, loaders & micro-interactions. */
import { useEffect, useRef, useState } from 'react';
import {
  type ActionStatus,
  BlobButton,
  BorderButton,
  Button,
  type FileUploadProps,
  FileUpload,
  FillButton,
  FlipButton,
  Icon,
  type InterestOption,
  InterestsPicker,
  LabelCreator,
  type LabelItem,
  LikeButton,
  MetalButton,
  ParametricLoader,
  PixelLoader,
  type UploadItem,
  TransactionButton,
  toast,
} from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';

const INTERESTS: InterestOption[] = [
  { value: 'design', label: 'Design · Diseño', emoji: '🎨' },
  { value: 'food', label: 'Food · 料理', emoji: '🍜' },
  { value: 'music', label: 'Music · موسيقى', emoji: '🎵' },
  { value: 'books', label: 'Books · Livres', emoji: '📚' },
  { value: 'travel', label: 'Travel · Viajes', emoji: '✈️' },
  { value: 'running', label: 'Running · Laufen', emoji: '🏃' },
  { value: 'games', label: 'Games · ゲーム', emoji: '🎮' },
  { value: 'photo', label: 'Photography · Fotografía', emoji: '📷' },
  { value: 'plants', label: 'Plants · 植物', emoji: '🌱' },
  { value: 'yoga', label: 'Yoga · योग', emoji: '🧘' },
  { value: 'film', label: 'Film · Cinéma', emoji: '🎬' },
  { value: 'coffee', label: 'Coffee · Kahve', emoji: '☕' },
  { value: 'science', label: 'Science · 과학', emoji: '🧪' },
  { value: 'basketball', label: 'Basketball · Basquete', emoji: '🏀' },
  { value: 'theatre', label: 'Theatre · Tiyatro', emoji: '🎭' },
  { value: 'code', label: 'Code · コード', emoji: '💻' },
  { value: 'languages', label: 'Languages · Idiomas', emoji: '🌍' },
  { value: 'podcasts', label: 'Podcasts · 播客', emoji: '🎧' },
  { value: 'crafts', label: 'Crafts · Handwerk', emoji: '🧵' },
  { value: 'cycling', label: 'Cycling · 骑行', emoji: '🚲' },
  { value: 'history', label: 'History · تاریخ', emoji: '🏛️' },
];

/** Runs a fake request: loading, then success and error by turns, then back to idle. */
function useFakeRequest(duration = 1500, settle = 1900) {
  const [status, setStatus] = useState<ActionStatus>('idle');
  const fail = useRef(false);
  const timers = useRef<number[]>([]);
  useEffect(() => () => timers.current.forEach(clearTimeout), []);

  const run = () => {
    if (status !== 'idle') return;
    setStatus('loading');
    timers.current.push(
      window.setTimeout(() => {
        setStatus(fail.current ? 'error' : 'success');
        fail.current = !fail.current;
        timers.current.push(window.setTimeout(() => setStatus('idle'), settle));
      }, duration),
    );
  };

  return [status, run] as const;
}

/** A stand-in toast in a list with data-effect="ripple", replayed on demand. */
function ToastRipple() {
  const [round, setRound] = useState(0);
  return (
    <div className="sc-stack">
      <ol className="nx-toast-list" data-effect="ripple">
        <li key={round} className="nx-toast" data-tone="success" data-state="open" data-front="">
          <span className="nx-toast-icon">
            <Icon name="check-circle" />
          </span>
          <div className="nx-toast-body">
            <p className="nx-toast-title">Saved · 保存しました</p>
            <p className="nx-toast-description">Your draft is safe · Tu borrador está a salvo.</p>
          </div>
        </li>
      </ol>
      <div className="sc-row">
        <Button size="sm" icon="bell" onClick={() => setRound((n) => n + 1)}>
          Replay the ripple
        </Button>
        <LikeButton count={128} />
        <LikeButton defaultLiked count={2048} />
      </div>
    </div>
  );
}

const MAX_UPLOAD_BYTES = 10 * 1024 * 1024;

/** A stand-in request: most files land, one in three "fails" so the error row shows. */
function fakeUpload(file: File) {
  return new Promise<void>((resolve, reject) => {
    window.setTimeout(file.size % 3 ? resolve : reject, 1400);
  });
}

const summarize = (items: UploadItem[]) => items.map((item) => `${item.file.name} (${item.state})`).join(', ') || 'nothing yet';

function UploadDemo() {
  const [items, setItems] = useState<FileUploadProps['value']>([]);
  return (
    <>
      <FileUpload
        multiple
        accept=".pdf,image/*"
        maxBytes={MAX_UPLOAD_BYTES}
        value={items}
        onValueChange={setItems}
        upload={fakeUpload}
        hint="PDF, PNG or JPG · up to 10 MB"
        tooLargeLabel="Too large · خیلی بزرگ"
        unsupportedLabel="Unsupported · پشتیبانی نمی‌شود"
      />
      <p className="sc-demo-title">Tried: {summarize(items ?? [])} — one upload in three fails, so the error row shows.</p>
    </>
  );
}

export function ActionsBlocks() {
  const [border, publish] = useFakeRequest(1400, 1800);
  const [payment, pay] = useFakeRequest(1800, 2400);
  const [listening, setListening] = useState(false);
  const [interests, setInterests] = useState<string[]>(['design', 'food']);
  const [labels, setLabels] = useState<LabelItem[]>([
    { name: 'Bug', color: 'pink' },
    { name: 'Design · Diseño', color: 'indigo' },
    { name: '緊急 · Urgent', color: 'orange' },
  ]);

  return (
    <Section
      id="actions-blocks"
      eyebrow="Blocks · Actions"
      title="Buttons, loaders & micro-interactions"
      description="Cube-flip labels, gathering colour blobs, borders that march and close into a status, a whole checkout in one button, fills that follow the pointer, liquid metal, emoji bursts and loaders drawn from equations. Hover them, Tab to them, try them in both themes and right to left."
      code={{
        react: `<FlipButton label="Get started" hoverLabel="Let's go" icon="arrow-right" hoverIcon="sparkles" />
<BlobButton icon="sparkles">Talk to Nabu</BlobButton>
<BorderButton status={status} successLabel="Saved" onClick={save}>Save draft</BorderButton>
<TransactionButton status={status} onClick={pay}
  labels={{ idle: 'Pay $49', loading: 'Processing…', success: 'Paid', error: 'Declined' }} />
<FillButton variant="inverse" iconEnd="arrow-right">Explore</FillButton>
<MetalButton label="Voice input" pressed={listening} onPressedChange={setListening} />
<InterestsPicker options={options} value={picked} onValueChange={setPicked} />
<FileUpload multiple accept=".pdf,image/*" maxBytes={maxBytes} upload={upload} onValueChange={setFiles} />
<LabelCreator labels={labels} onLabelsChange={setLabels} />
<PixelLoader variant="chaos" rows={3} cols={24} label="Loading orders" />
<ParametricLoader kind="spiro" size="lg" />`,
        blade: `<x-nx::flip-button label="Get started" hover-label="Let's go" />
<x-nx::blob-button icon="sparkles" wire:click="start">Talk to Nabu</x-nx::blob-button>
{{-- wire:loading spins the dashes; status closes them into a ring --}}
<x-nx::border-button wire:click="save" :status="$saved ? 'success' : null" success-label="Saved">Save draft</x-nx::border-button>
<x-nx::transaction-button wire:click="pay" :status="$paymentStatus"
    :labels="['idle' => 'Pay $49', 'loading' => 'Processing…', 'success' => 'Paid']" />
<x-nx::fill-button variant="inverse" icon-end="arrow-right">Explore</x-nx::fill-button>
<x-nx::metal-button label="Voice input" wire:model.live="listening" />
<x-nx::interests-picker :options="$options" wire:model.live="interests" />
<x-nx::label-creator wire:model.live="labels" x-on:nx-label-created="$wire.saveLabel($event.detail)" />
<x-nx::pixel-loader variant="chaos" rows="3" cols="24" label="Loading orders" />
<x-nx::parametric-loader kind="spiro" size="lg" />`,
      }}
    >
      <div className="sc-demos">
        <Demo title="Perspective reveal: hover or Tab to roll the cube">
          <div className="sc-row">
            <FlipButton label="Get started" hoverLabel="Let's go" />
            <FlipButton variant="secondary" label="Book a demo" hoverLabel="Pick a time" icon="message" hoverIcon="zap" onClick={() => toast('Demo booked · デモを予約しました')} />
            <FlipButton variant="inverse" size="lg" label="Commencer" hoverLabel="C’est parti" />
          </div>
        </Demo>

        <Demo title="Gradient blobs that gather at the pointer" center>
          <div className="sc-row">
            <BlobButton icon="sparkles" onClick={() => toast.success('Hola · Bonjour · مرحبا')}>
              Talk to Nabu
            </BlobButton>
            <BlobButton size="lg" iconEnd="arrow-right">
              Hablar con Nabu
            </BlobButton>
            <BlobButton size="sm">ナブと話す</BlobButton>
          </div>
        </Demo>

        <Demo title="A border that marches, spins, then closes" center>
          <div className="sc-row">
            <BorderButton icon="file" status={border} successLabel="Saved · Gespeichert" errorLabel="Try again · 다시 시도" onClick={publish}>
              Save draft
            </BorderButton>
            <BorderButton size="sm" status="success" successLabel="Synced · 同期済み">
              Sync
            </BorderButton>
          </div>
          <p className="sc-demo-title">Every other save fails, so both endings show.</p>
        </Demo>

        <Demo title="A whole checkout in one button" center>
          <TransactionButton status={payment} onClick={pay} labels={{ idle: 'Pay $49', loading: 'Processing…', success: 'Paid · 支払い済み', error: 'Declined · Rechazado' }} />
          <p className="sc-demo-title">The width follows the words; every other payment is declined.</p>
        </Demo>

        <Demo title="Fill from where the pointer enters" center>
          <div className="sc-row">
            <FillButton iconEnd="arrow-right">Explore · Explorar</FillButton>
            <FillButton variant="inverse" hoverLabel="订阅 · Subscribe">
              Subscribe
            </FillButton>
            <FillButton variant="gold" shape="rounded" hoverLabel="Kostenlos starten">
              Start free
            </FillButton>
          </div>
        </Demo>

        <Demo title="Liquid-metal dock toggles" center>
          <div className="sc-row">
            <MetalButton label="Voice input" pressed={listening} onPressedChange={setListening} />
            <MetalButton label="Listen · Escuchar" icon="play" shape="pill" />
            <MetalButton label="Record" size="lg" defaultPressed />
            <MetalButton label="Notifications" icon="bell" activeIcon="x" size="sm" />
          </div>
          <p className="sc-demo-title">Voice input is {listening ? 'on' : 'off'}.</p>
        </Demo>

        <Demo title="Ripples: a toast's icon and a liked heart">
          <ToastRipple />
        </Demo>

        <Demo title="Interests picker: drag the rows, pick, then Clear" wide>
          <InterestsPicker label="What are you into? · ¿Qué te gusta?" options={INTERESTS} value={interests} onValueChange={setInterests} name="interests[]" />
          <p className="sc-demo-title">Picked: {interests.join(', ') || 'nothing yet'}</p>
        </Demo>

        <Demo title="Gradient upload: drag files over it, drop, or browse">
          <UploadDemo />
        </Demo>

        <Demo title="Label creator (Notion-style)">
          <LabelCreator labels={labels} onLabelsChange={setLabels} onCreate={(label) => toast.success(`Label created · ${label.name}`)} />
          <p className="sc-demo-title">Type a new name, pick a colour, press Enter.</p>
        </Demo>

        <Demo title="Pixel loaders: wave, centre, chaos">
          <div className="sc-row" style={{ gap: 'var(--nx-space-8)' }}>
            <PixelLoader label="Thinking · Pensando" />
            <PixelLoader variant="center" rows={7} cols={7} size="sm" />
            <PixelLoader variant="chaos" rows={4} cols={8} size="lg" />
          </div>
          <div className="sc-stack" style={{ gap: 'var(--nx-space-3)', padding: 'var(--nx-space-4)', border: '1px solid var(--nx-border)', borderRadius: 'var(--nx-radius-lg)' }}>
            <p className="sc-demo-title">Orders · Pedidos</p>
            <PixelLoader variant="chaos" rows={3} cols={24} size="sm" label="Loading orders · Cargando pedidos" />
            <PixelLoader variant="chaos" rows={3} cols={24} size="sm" label="Loading orders" style={{ opacity: 0.7 }} />
          </div>
        </Demo>

        <Demo title="Parametric orbit loaders">
          <div className="sc-row" style={{ gap: 'var(--nx-space-8)' }}>
            <ParametricLoader size="lg" label="Rose · Rosa" />
            <ParametricLoader kind="spiro" size="lg" label="Spirograph" />
            <ParametricLoader kind="lissajous" size="lg" label="Lissajous" />
          </div>
          <div className="sc-row" style={{ gap: 'var(--nx-space-8)' }}>
            <ParametricLoader options={{ n: 7, d: 3 }} />
            <ParametricLoader kind="spiro" options={{ R: 6, r: 1, offset: 3 }} />
            <ParametricLoader kind="lissajous" options={{ a: 5, b: 4 }} duration={4200} />
            <ParametricLoader size="sm" />
          </div>
        </Demo>
      </div>
    </Section>
  );
}
