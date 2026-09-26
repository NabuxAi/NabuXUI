import { useRef, useState } from 'react';
import {
  Badge,
  GlassButton,
  GlassDock,
  GlassPanel,
  GlassSegmented,
  GlassSlider,
  GlassSwitch,
  GlassTabBar,
  LiquidRipple,
  ReadingGlass,
  toast,
} from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

/** Colour and type for the glass to bend: drifting blobs and a line of many scripts. */
function Stage({ children, words = true, tone }: { children: React.ReactNode; words?: boolean; tone?: 'night' | 'day' }) {
  return (
    <div className="sc-glass-stage" data-tone={tone}>
      <div className="sc-glass-blobs" aria-hidden="true">
        <i />
        <i />
        <i />
        <i />
      </div>
      {words && (
        <p className="sc-glass-words" aria-hidden="true">
          Liquid · 液体 · سائل · Flüssig · líquido · 액체 · तरल · مایع
        </p>
      )}
      <div className="sc-glass-content">{children}</div>
    </div>
  );
}

export function GlassBlocks() {
  const tr = useTr();
  const [period, setPeriod] = useState('week');
  const [volume, setVolume] = useState(64);
  const phone = useRef<HTMLDivElement>(null);

  return (
    <Section
      id="glass"
      eyebrow={tr('شیشه‌ی مایع', 'Liquid glass')}
      title={tr('شیشه‌ای که پشتش را خم می‌کند', 'Glass that bends what is behind it')}
      description={tr(
        'عدسی واقعی روی DOM زنده: متن زیرش قابل انتخاب می‌ماند و دکمه‌ها کلیک می‌خورند. عدسی در کروم، سافاری و فایرفاکس کار می‌کند؛ لبه‌ی پنل‌ها در کرومیوم پس‌زمینه را خم می‌کند و جاهای دیگر مات می‌ماند.',
        'A real lens over live DOM: text underneath stays selectable and buttons stay clickable. Lenses work in Chrome, Safari and Firefox; pane rims bend their backdrop in Chromium and stay frosted elsewhere.',
      )}
      code={{
        react: `<GlassPanel iridescent>…</GlassPanel>
<GlassButton icon="sparkles" shimmer>Continue</GlassButton>
<GlassSegmented options={[{ value: 'day', label: 'Day' }, …]} />
<GlassDock items={[{ id: 'mail', label: 'Mail', icon: 'mail', tone: 'lapis' }, …]} />
<GlassSwitch label="Refraction" defaultChecked />
<GlassSlider value={volume} onValueChange={setVolume} />
<GlassTabBar items={tabs} minimizeOnScroll />
<ReadingGlass>…any live content…</ReadingGlass>
<LiquidRipple>…</LiquidRipple>`,
        blade: `<x-nx::glass-panel iridescent>…</x-nx::glass-panel>
<x-nx::glass-button icon="sparkles" shimmer>Continue</x-nx::glass-button>
<x-nx::glass-segmented wire:model.live="period" :options="['day' => 'Day', 'week' => 'Week']" />
<x-nx::glass-dock :items="$apps" />
<x-nx::glass-switch wire:model.live="refraction" label="Refraction" />
<x-nx::glass-slider wire:model.live="volume" />
<x-nx::glass-tab-bar :items="$tabs" minimize-on-scroll />
<x-nx::reading-glass>…</x-nx::reading-glass>
<x-nx::liquid-ripple>…</x-nx::liquid-ripple>`,
      }}
    >
      <div className="sc-demos">
        <Demo wide bare>
          <Stage>
            <div className="sc-glass-hero">
              <GlassPanel className="sc-glass-card" iridescent followLight>
                <Badge tone="accent" dot>
                  Live material
                </Badge>
                <h3>Clarity over blur · 透明</h3>
                <p>
                  {tr(
                    'پس‌زمینه دیده می‌ماند. فقط لبه‌ی نوری، رنگ و کنتراست این لایه را می‌سازند.',
                    'The backdrop stays visible. Only the optical edge, the tint and the contrast define this layer.',
                  )}
                </p>
                <div className="sc-row">
                  <GlassButton icon="sparkles" shimmer onClick={() => toast.success('¡Listo!', { description: 'Glass shimmer sent around every rim.' })}>
                    Continue
                  </GlassButton>
                  <GlassButton icon="heart" iridescent>
                    Save · 保存
                  </GlassButton>
                  <GlassButton icon="settings" aria-label="Settings" preset="thick" />
                </div>
              </GlassPanel>
            </div>
          </Stage>
        </Demo>

        <Demo wide bare>
          <Stage words>
            <div className="sc-glass-presets">
              {(['regular', 'clear', 'frost', 'thick'] as const).map((look) => (
                <GlassPanel key={look} preset={look} className="sc-glass-chip">
                  <strong>{look}</strong>
                  <span>{{ regular: 'Regular', clear: 'Claro', frost: 'Givré', thick: 'Dick' }[look]}</span>
                </GlassPanel>
              ))}
            </div>
          </Stage>
        </Demo>

        <Demo title={tr('انتخاب با عدسی', 'Selection as a lens')} bare>
          <Stage words={false}>
            <div className="sc-glass-center">
              <GlassSegmented
                aria-label="Period"
                value={period}
                onValueChange={setPeriod}
                options={[
                  { value: 'day', label: 'Day' },
                  { value: 'week', label: 'Semana' },
                  { value: 'month', label: '月' },
                  { value: 'year', label: 'سنة' },
                ]}
              />
              <p className="sc-glass-note">{tr('بکشید، نگه دارید، رها کنید.', 'Drag it, hold it, let go.')}</p>
            </div>
          </Stage>
        </Demo>

        <Demo title={tr('داک با ذره‌بین', 'Dock magnifier')} bare>
          <Stage words={false} tone="night">
            <div className="sc-glass-center">
              <GlassDock
                aria-label="Apps"
                items={[
                  { id: 'finder', label: 'Files · ファイル', icon: 'folder', tone: 'lapis', current: true },
                  { id: 'mail', label: 'Mail · Correo', icon: 'mail', tone: 'cyan' },
                  { id: 'music', label: 'Music · موسیقی', icon: 'music', tone: 'rose' },
                  { id: 'photos', label: 'Photos · 사진', icon: 'image', tone: 'gold' },
                  { id: 'chat', label: 'Chat · चैट', icon: 'message', tone: 'green' },
                  { id: 'ai', label: 'Nabu AI', icon: 'sparkles', tone: 'violet' },
                ]}
              />
            </div>
          </Stage>
        </Demo>

        <Demo title={tr('کلید و اسلایدر', 'Switch and slider')} bare>
          <Stage words={false}>
            <GlassPanel className="sc-glass-controls" preset="frost">
              <GlassSwitch label="Refraction · 屈折" defaultChecked />
              <GlassSwitch label="Iridescence · Irisation" />
              <div className="sc-glass-slider-row">
                <span>Volume · Lautstärke</span>
                <GlassSlider aria-label="Volume" value={volume} onValueChange={setVolume} format={(v) => `${v}%`} />
              </div>
            </GlassPanel>
          </Stage>
        </Demo>

        <Demo title={tr('نوار تب شناور', 'Floating tab bar')} bare>
          <div className="sc-glass-phone">
            <div ref={phone} className="sc-glass-phone-scroll">
              <div className="sc-glass-feed">
                {['Kyoto · 京都', 'Lisboa', 'Marrakech · مراكش', 'Reykjavík', 'Tehran · تهران', 'Seoul · 서울', 'Oaxaca', 'Istanbul'].map((city, i) => (
                  <div key={city} className="sc-glass-post" style={{ '--i': i } as React.CSSProperties}>
                    <span>{city}</span>
                  </div>
                ))}
              </div>
            </div>
            <div className="sc-glass-phone-bar">
              <GlassTabBar
                aria-label="Main"
                minimizeOnScroll={phone}
                items={[
                  { value: 'home', label: 'Home', icon: 'home' },
                  { value: 'explore', label: 'Explorar', icon: 'globe' },
                  { value: 'saved', label: 'Gespeichert', icon: 'heart' },
                  { value: 'me', label: 'من', icon: 'user' },
                ]}
              />
            </div>
          </div>
        </Demo>

        <Demo wide title={tr('ذره‌بین روی محتوای زنده', 'A reading glass over live content')} bare>
          <ReadingGlass className="sc-glass-reading" defaultPosition={{ x: 0.62, y: 0.3 }}>
            <div className="sc-glass-article">
              <h3>Glass that reads with you · 与你同读的玻璃</h3>
              <p>
                The lens bends the page under it, and the page stays a page: select this sentence, or press the button below while the lens sits on
                top of it.
                <span lang="fa" dir="rtl"> متن فارسی زیر عدسی هم درست خم می‌شود. </span>
                <span lang="ja">レンズの下の文字も選択できます。</span>
                <span lang="es"> Y el texto sigue siendo texto.</span>
              </p>
              <div className="sc-row">
                <GlassButton size="sm" onClick={() => toast({ title: 'Clicked through the glass', description: 'Kenji · María · Amara' })}>
                  Click me · クリック
                </GlassButton>
                <Badge tone="success">Selectable</Badge>
                <Badge tone="info">Clickable</Badge>
              </div>
              <div className="sc-glass-bars" aria-hidden="true">
                {[38, 64, 52, 88, 71, 96, 58, 77, 45, 83, 62, 91].map((h, i) => (
                  <i key={i} style={{ blockSize: `${h}%` }} />
                ))}
              </div>
            </div>
          </ReadingGlass>
        </Demo>

        <Demo wide title={tr('موج روی رابط', 'Ripples through the interface')} bare>
          <LiquidRipple className="sc-glass-ripple">
            <div className="sc-glass-ripple-grid">
              {['Kenji Sato', 'María López', 'Amara Okafor', 'Omar Haddad', 'Lena Fischer', 'Priya Nair', '김지우', 'Zhang Wei'].map((name) => (
                <div key={name} className="sc-glass-person">
                  <span className="sc-glass-avatar" aria-hidden="true">
                    {name.slice(0, 1)}
                  </span>
                  <span>{name}</span>
                </div>
              ))}
            </div>
            <p className="sc-glass-note">{tr('هر جا را لمس کنید.', 'Tap anywhere · Touchez n’importe où')}</p>
          </LiquidRipple>
        </Demo>
      </div>
    </Section>
  );
}
