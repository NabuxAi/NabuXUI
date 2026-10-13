import { type CSSProperties } from 'react';
import { ElasticLetters, VelocityBand, WordCycle3d, WordFillScroll } from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

/* ---- Word fill scroll demo: the story of a release ------------------------------- */

function WordFillDemo() {
  const tr = useTr();
  const story = tr(
    'فیلدنوت نسخهٔ پنجمش را مثل یک داستان روایت می‌کند: ایده پشت یک بلیت کشتی در اسلو روی کاغذ آمد، نمونهٔ اول در یوکوهاما سرهم شد و بازخورد سی هزار نویسنده — از مونترال تا لیسبون — هر گوشه‌اش را سوهان زد. دفترچه‌های آفلاین اول رسیدند، بعد کتابخانه‌های اشتراکی، و هفتهٔ پیش ساخت عمومی به دست همهٔ تیم‌هایی رسید که در میدان می‌نویسند. برای خواندن ادامه اسکرول کنید؛ هر واژه دقیقاً وقتی روشن می‌شود که به آن می‌رسید.',
    'Fieldnote tells its fifth version like a story: the idea was sketched in Oslo on the back of a ferry ticket, the first prototype was assembled in Yokohama, and feedback from thirty thousand writers — Montréal to Lisbon — sanded every corner smooth. Offline notebooks arrived first, then the shared libraries, and last week the public build reached every team that writes in the field. Keep scrolling; every word lights exactly when you reach it.',
  );
  return (
    <WordFillScroll
      aria-label={tr('قاب خواندن فیلدنوت', 'The Fieldnote reading pane')}
      label={tr('داستان انتشار', 'The release story')}
      resetLabel={tr('از نو بخوان', 'Read it again')}
    >
      {story}
    </WordFillScroll>
  );
}

/* ---- Word cycle demo: the rotating keyword ---------------------------------------- */

function WordCycleDemo() {
  const tr = useTr();
  return (
    <WordCycle3d
      aria-label={tr('تیتر چرخان', 'The rotating headline')}
      kicker={tr('نبوکس‌یوآی · تایپوگرافی جنبشی', 'NABUXUI · KINETIC TYPE')}
      lead={tr('همه‌چیزِ برندت را', 'Everything your brand needs,')}
      words={tr(['سریع‌تر', 'باهم', 'همه‌جا', 'تمیزتر'], ['faster', 'together', 'anywhere', 'cleaner'])}
      tail={tr('زیر یک سقف برساز.', 'under one roof.')}
      hint={tr('هاور یا فوکوس چرخه را نگه می‌دارد · ۲٫۴ ثانیه هر واژه', 'Hover or focus holds the loop · 2.4s per word')}
      dotsLabel={tr('واژه', 'Word')}
      replayLabel={tr('چرخه را از نو بچرخان', 'Replay the cycle')}
    />
  );
}

/* ---- Velocity band demo: the changelog throttle ----------------------------------- */

const logTitle: CSSProperties = {
  margin: '0 0 var(--nx-space-3)',
  fontSize: 'var(--nx-text-md)',
  fontWeight: 700,
  color: 'var(--nx-text)',
};

const logBody: CSSProperties = {
  margin: 0,
  fontSize: 'var(--nx-text-sm)',
  lineHeight: 1.9,
  color: 'var(--nx-text)',
};

function VelocityDemo() {
  const tr = useTr();
  return (
    <VelocityBand
      aria-label={tr('نوارهای سرعت', 'The velocity bands')}
      label={tr('یادداشت‌های انتشار', 'Release notes')}
      meterLabel={tr('پیکسل بر فریم', 'px/frame')}
      bands={[
        { key: 'lead', text: tr('نبوکس‌یوآی · تایپوگرافی جنبشی · سرعت را حس کن', 'NABUXUI · KINETIC TYPE · FEEL THE VELOCITY') },
        { key: 'gold', text: tr('تندتر اسکرول کن · تندتر بچرخ', 'SCROLL HARDER · BAND FASTER'), reverse: true },
      ]}
    >
      <h4 style={logTitle}>{tr('فیلدنوت — یادداشت‌های انتشار ۵٫۰', 'Fieldnote — release notes 5.0')}</h4>
      <p style={logBody}>
        {tr(
          'این اسپرینت دور رفت: نمای خط‌زمانی به همهٔ فضاهای کاری رسید، همگام‌سازی آفلاین روی موبایل فرود آمد و موتور صادرات جدید زمان PDF را نصف کرد. تیم‌های ورشو، کازابلانکا و بوسان پیش از عرضه هر نقطهٔ ورودی را زیر فشار آزمودند. تندتر اسکرول کنید — نوار جواب انگشتتان را می‌دهد؛ مکث کنید تا میرایی‌اش به رانِ آرام برگردد و به بالا بروید تا هر دو نوار خلاف شما بروند.',
          'This sprint went far: the timeline view shipped to every workspace, offline sync landed on mobile, and the new export engine cut PDF times in half. Teams in Warsaw, Casablanca and Busan stress-tested every entry point before rollout. Scroll harder — the band answers your thumb; pause, and the damping eases it back to a calm drift, scroll up, and both bands reverse against you.',
        )}
      </p>
    </VelocityBand>
  );
}

/* ---- Elastic letters demo: the wordmark -------------------------------------------- */

function ElasticDemo() {
  const tr = useTr();
  return (
    <ElasticLetters
      aria-label={tr('واژه‌نگار فنری', 'The elastic wordmark')}
      kicker={tr('حروف‌نگار تایپ‌فورج · قلم‌های متغیر', 'Typeforge Foundry · variable fonts')}
      sub={tr('قلم‌های متغیر با جهش — نشانگر را روی واژه‌نگار بکشید.', 'Variable fonts with a bounce — drag your pointer across the wordmark.')}
    >
      {tr('قلم فنری تایپ‌فورج', 'TYPEFORGE')}
    </ElasticLetters>
  );
}

/* ---- The section -------------------------------------------------------------------- */

export function KineticBlocks() {
  const tr = useTr();
  return (
    <Section
      id="kinetic-blocks"
      eyebrow={tr('تایپوگرافی جنبشی', 'Kinetic type')}
      title={tr('متنی که سرعت خواندن‌ات را حس می‌کند', 'Type that feels how fast you read')}
      description={tr(
        'چهار حرکت که تایپوگرافی را به خواننده گره می‌زنند: واژه‌هایی که با پیشرفت خواندن رنگ می‌گیرند؛ واژهٔ کلیدی تیتر که در حلقهٔ rotateX می‌چرخد و جایگاهش به عرض واژهٔ بعدی نرم می‌رسد؛ نوارهای متن که سرعتشان را مستقیم از جهشت اسکرول می‌گیرند — یکی با جهت، یکی خلافش؛ و واژه‌نگاری که حرف‌هایش مثل لاستیک فنری برمی‌گردند. فارسی همیشه واژهٔ کامل می‌ماند و حروف هرگز نمی‌شکنند.',
        'Four motions that tie typography to the reader: words that take colour as the reading progresses; the headline keyword flipping through a rotateX loop while its slot eases to the next word; text bands whose speed is the scroll velocity itself — one with the flick, one against it; and a wordmark whose letters snap back like rubber. Persian stays whole words; the letters never break.',
      )}
      code={{
        react: `<WordFillScroll label="The release story" resetLabel="Read it again">{story}</WordFillScroll>
<WordCycle3d
  lead="Everything your brand needs,"
  words={['faster', 'together', 'anywhere', 'cleaner']}
  tail="under one roof."
/>
<VelocityBand
  label="Release notes"
  meterLabel="px/frame"
  bands={[{ text: 'NABUXUI · KINETIC TYPE' }, { text: 'SCROLL HARDER · BAND FASTER', reverse: true }]}
>
  <p>…the changelog the reader scrolls…</p>
</VelocityBand>
<ElasticLetters stiffness={170} damping={10}>TYPEFORGE</ElasticLetters>`,
        blade: `{{-- shared core CSS + the framework-agnostic behaviours --}}
<div class="nx-wordfill">{{ wordFillScroll($el) }}
  <div class="nx-wordfill-layout">
    <aside class="nx-wordfill-rail" aria-hidden="true"><i class="nx-wordfill-track"><b class="nx-wordfill-fill"></b></i></aside>
    <div class="nx-wordfill-pane" tabindex="0" role="region" aria-label="The release story">
      <p class="nx-wordfill-text">@foreach (\\NabuXUI\\NabuXUI::split($story) as $piece)<span class="nx-wordfill-word">{{ $piece }}</span>@endforeach</p>
    </div>
  </div>
</div>
<section class="nx-wordcycle">{{ -- the loop swaps .nx-wordcycle-word under the fold -- }}</section>
<section class="nx-velocity">{{ velocityBands($el) }}
  <div class="nx-velocity-pane" tabindex="0" role="region" aria-label="Release notes">…</div>
  <div class="nx-velocity-band" aria-hidden="true"><div class="nx-velocity-track"><span>…</span><span>…</span></div></div>
</section>
<section class="nx-elastic">{{ elasticLetters($el) }}
  <h3 class="nx-elastic-title">@foreach (\\NabuXUI\\NabuXUI::split('TYPEFORGE', 'char') as $piece)<span class="nx-elastic-unit" aria-hidden="true">{{ $piece }}</span>@endforeach</h3>
</section>`,
      }}
    >
      <Demo title={tr('خواندنی که خودش را روشن می‌کند', 'Reading that lights its own way')} wide>
        <WordFillDemo />
      </Demo>

      <Demo title={tr('واژهٔ تیتر که هرگز نمی‌ایستد', 'The headline word that never stands still')} wide>
        <WordCycleDemo />
      </Demo>

      <Demo title={tr('تایپی که سرعتش را از انگشت شما می‌گیرد', 'Type that takes its speed from your thumb')} wide>
        <VelocityDemo />
      </Demo>

      <Demo title={tr('حروفی که مثل لاستیک برمی‌گردند', 'Letters that snap back like rubber')} wide>
        <ElasticDemo />
      </Demo>
    </Section>
  );
}
