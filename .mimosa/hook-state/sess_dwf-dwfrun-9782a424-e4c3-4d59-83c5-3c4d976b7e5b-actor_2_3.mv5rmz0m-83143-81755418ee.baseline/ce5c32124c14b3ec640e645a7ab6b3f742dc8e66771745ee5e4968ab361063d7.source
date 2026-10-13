/** Showcase demos: Text & hero motion. */
import type { CSSProperties } from 'react';
import {
  HeroBackdrop,
  Badge,
  Button,
  DitherBackdrop,
  HoverReveal,
  PulseButton,
  RollText,
  ScrollScramble,
  ScrollTextReveal,
  TextHero,
} from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';


const LANGUAGES = ['English', 'Español', 'Français', 'Deutsch', '日本語', '中文', 'العربية', 'فارسی', 'हिन्दी', 'Português', '한국어', 'Türkçe'];

const panel: CSSProperties = {
  position: 'relative',
  isolation: 'isolate',
  display: 'grid',
  placeItems: 'center',
  alignContent: 'center',
  gap: 'var(--nx-space-6)',
  minBlockSize: '30rem',
  padding: 'var(--nx-space-16) var(--nx-space-6)',
  overflow: 'clip',
  textAlign: 'center',
};

const display: CSSProperties = {
  margin: 0,
  maxInlineSize: '18ch',
  font: '800 var(--nx-text-5xl) / 1.05 var(--nx-font-display)',
  letterSpacing: 'var(--nx-tracking-tighter)',
};

const lead: CSSProperties = { margin: 0, maxInlineSize: '36rem', color: 'var(--nx-text-muted)', fontSize: 'var(--nx-text-lg)' };

export function TextBlocks() {
  const tr = useTr();

  return (
    <Section
      id="text-blocks"
      eyebrow={tr('بلوک‌ها · متن و هیرو', 'Blocks · Text & hero')}
      title={tr('حروفی که جای خودشان را پیدا می‌کنند', 'Letters that find their place')}
      description={tr(
        'هیروی متنی با استیکر، متنی که با اسکرول جمع می‌شود، لینک‌های درخشان، رمزگشایی همراه اسکرول، منوی چرخان و پس‌زمینه‌های شیدری. فارسی و عربی کلمه‌به‌کلمه حرکت می‌کنند، بقیه حرف‌به‌حرف.',
        'A sticker text hero, text that gathers as you scroll, glowing links, a scroll-driven decode, rolling menu text and shader backdrops. Persian and Arabic move word by word, everything else letter by letter.',
      )}
      code={{
        react: `<TextHero
  title="Write in every language"
  highlight="every language"
  stickers={[{ shape: 'star', position: 'top-start' }, { shape: 'heart', position: 'bottom-end' }]}
  actions={<Button variant="primary">Start writing</Button>}
/>
<ScrollTextReveal text="Every word finds its place" eyebrow="Scroll" height="240vh" />
<HoverReveal items={[{ label: 'Design · Diseño · デザイン', href: '/design', description: '…' }]} />
<ScrollScramble title="Decoding every script" items={['English', '日本語', 'فارسی']} />
<RollText href="/work">Work</RollText>
<PulseButton icon="play">Play the reel</PulseButton>
<HeroBackdrop variant="mesh" />   <HeroBackdrop variant="stripes" />   <DitherBackdrop />`,
        blade: `<x-nx::text-hero title="Write in every language" highlight="every language"
    :stickers="[['shape' => 'star', 'position' => 'top-start'], ['shape' => 'heart', 'position' => 'bottom-end']]">
    <x-slot:actions><x-nx::button variant="primary">Start writing</x-nx::button></x-slot:actions>
</x-nx::text-hero>
<x-nx::scroll-text-reveal text="Every word finds its place" eyebrow="Scroll" height="240vh" />
<x-nx::hover-reveal :items="[['label' => 'Design · Diseño · デザイン', 'href' => '/design', 'description' => '…']]" />
<x-nx::scroll-scramble title="Decoding every script" :items="['English', '日本語', 'فارسی']" />
<x-nx::roll-text href="/work">Work</x-nx::roll-text>
<x-nx::pulse-button icon="play">Play the reel</x-nx::pulse-button>
<x-nx::backdrop variant="mesh" />   <x-nx::backdrop variant="stripes" />   <x-nx::dither-backdrop />`,
      }}
    >
      <div className="sc-demos">
        <Demo wide bare>
          <TextHero
            as="h2"
            title="Write in every language"
            highlight="every language"
            subtitle="Hola · Bonjour · こんにちは · سلام · नमस्ते — one headline, and every letter springs into place."
            stickers={[
              { shape: 'star', position: 'top-start' },
              { shape: 'sparkle', position: 'top-end' },
              { shape: 'smiley', position: 'bottom-start' },
              { shape: 'heart', position: 'bottom-end' },
              { shape: 'arrow', position: 'start' },
            ]}
            actions={
              <>
                <Button variant="primary" shape="pill" iconEnd="arrow-right">
                  Start writing
                </Button>
                <Button variant="ghost" shape="pill">
                  See the scripts
                </Button>
              </>
            }
          />
        </Demo>

        <Demo bare>
          <div dir="rtl" lang="fa">
            <TextHero
              as="h2"
              title="با هر زبانی بنویس"
              highlight="هر زبانی"
              subtitle="Persian and Arabic rise word by word, so their letters stay joined."
              stickers={[
                { shape: 'bolt', position: 'top-end' },
                { shape: 'heart', position: 'bottom-start', tone: 'violet' },
              ]}
              style={{ paddingBlock: 'var(--nx-space-16)' }}
            />
          </div>
        </Demo>

        <Demo bare>
          <div style={{ ...panel, minBlockSize: '26rem' }}>
            <DitherBackdrop />
            <p className="sc-demo-title">{tr('پس‌زمینهٔ دیتر', 'Dither backdrop')}</p>
            <Badge tone="accent" dot>
              Now in 12 languages
            </Badge>
            <h3 style={{ ...display, fontSize: 'var(--nx-text-4xl)' }}>Support that speaks your customer’s language</h3>
            <p style={lead}>Agents that answer in Español, Français, 日本語 and فارسی — in seconds.</p>
            <div className="sc-row" style={{ justifyContent: 'center' }}>
              <Button variant="primary" shape="pill">
                Start free
              </Button>
              <Button shape="pill">Book a demo</Button>
            </div>
          </div>
        </Demo>

        <Demo wide bare>
          <ScrollTextReveal text="Every word finds its place" eyebrow="Scroll · Desplázate · スクロール" height="220vh" />
        </Demo>

        <Demo title={tr('لینک‌های درخشان', 'Hover reveal')} wide>
          <HoverReveal
            items={[
              { label: 'Design · Diseño · デザイン', href: '#text-blocks', description: 'Interfaces with a sense of motion' },
              { label: 'Motion · Mouvement · Bewegung', href: '#text-blocks', description: 'Springs, not durations' },
              { label: 'Language · لغة · زبان', href: '#text-blocks', description: 'Right to left from the first line' },
              { label: 'Build · Construir · 만들기', href: '#text-blocks', description: 'Livewire, Inertia and React' },
            ]}
          />
        </Demo>

        <Demo wide bare>
          <ScrollScramble title="Decoding every script" items={LANGUAGES} />
        </Demo>

        <Demo title={tr('متن چرخان منو', 'Roll text')}>
          <nav aria-label={tr('منوی نمونه', 'Demo menu')} style={{ display: 'flex', flexWrap: 'wrap', gap: 'var(--nx-space-2) var(--nx-space-6)', fontSize: 'var(--nx-text-2xl)', fontWeight: 700 }}>
            <RollText href="#text-blocks">Work</RollText>
            <RollText href="#text-blocks">Studio</RollText>
            <RollText href="#text-blocks">Journal</RollText>
            <RollText href="#text-blocks">Kontakt</RollText>
            <RollText href="#text-blocks">お問い合わせ</RollText>
            <RollText href="#text-blocks">تماس با ما</RollText>
          </nav>
          <div className="sc-row">
            <Button variant="primary" shape="pill" iconEnd="arrow-right">
              <RollText>Get in touch</RollText>
            </Button>
            <RollText as="button" style={{ fontWeight: 600 }}>
              Menu · Menú · メニュー
            </RollText>
          </div>
        </Demo>

        <Demo title={tr('دکمهٔ تپنده', 'Pulse button')} center>
          <div className="sc-row" style={{ justifyContent: 'center', gap: 'var(--nx-space-10)', alignItems: 'start' }}>
            <PulseButton size="sm" aria-label="Next" />
            <PulseButton icon="play" tone="gold">
              Écouter
            </PulseButton>
            <PulseButton icon="sparkles" tone="inverse" size="lg">
              Try it · 試す
            </PulseButton>
          </div>
        </Demo>

        <Demo wide bare>
          <div style={panel}>
            <HeroBackdrop variant="mesh" />
            <p className="sc-demo-title">{tr('پس‌زمینهٔ مش و دکمهٔ تپنده', 'Mesh backdrop and pulse button')}</p>
            <h3 style={display}>Light, in every colour of the language</h3>
            <p style={lead}>Luz · Lumière · Licht · 光 · نور — a mesh of lights over a wireframe floor.</p>
            <PulseButton icon="play" size="lg" href="#text-blocks">
              Play the reel
            </PulseButton>
          </div>
        </Demo>

        <Demo wide bare>
          <div style={{ ...panel, minBlockSize: '24rem' }}>
            <HeroBackdrop variant="stripes" />
            <p className="sc-demo-title">{tr('پس‌زمینهٔ نوارها', 'Stripes backdrop')}</p>
            <h3 style={display}>Signals on every line</h3>
            <p style={lead}>Señales · Signaux · Signale · 信号 — pulses of light, each at its own speed.</p>
            <div className="sc-row" style={{ justifyContent: 'center', fontSize: 'var(--nx-text-lg)', fontWeight: 600 }}>
              <RollText href="#text-blocks">Changelog</RollText>
              <RollText href="#text-blocks">Docs</RollText>
              <RollText href="#text-blocks">Status</RollText>
            </div>
          </div>
        </Demo>
      </div>
    </Section>
  );
}
