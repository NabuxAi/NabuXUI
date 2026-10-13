import { FlipCube, OpenBox, ProductRoom } from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

/** Localised digits for the status readouts — the library defaults stay English. */
const FA_DIGITS = '۰۱۲۳۴۵۶۷۸۹'.split('');

function faNum(value: number | string): string {
  return String(value).replace(/\d/g, (digit) => FA_DIGITS[Number(digit)] ?? digit);
}

/* ---- Product room demo: the hero shot of a global audio brand ---------------------- */

function ProductRoomDemo() {
  const tr = useTr();
  const layers = [
    {
      id: 'pedestal',
      tone: 'pedestal' as const,
      label: tr('سکوی نمایش', 'Display pedestal'),
      spec: tr('چوب گردوی روغن‌خورده', 'Oiled walnut'),
    },
    {
      id: 'mesh',
      tone: 'mesh' as const,
      label: tr('بدنهٔ مش', 'Mesh shell'),
      spec: tr('پارچهٔ صوتی تنفس‌پذیر', 'Breathable acoustic fabric'),
    },
    {
      id: 'driver',
      tone: 'driver' as const,
      label: tr('درایور ووفر', 'Woofer driver'),
      spec: tr('۴۰ وات · دهانهٔ ۱۰ سانتی', '40 W · 10 cm cone'),
    },
    {
      id: 'ring',
      tone: 'ring' as const,
      label: tr('حلقهٔ کنترل', 'Control ring'),
      spec: tr('لمس و چرخش، با بازخورد نور', 'Touch and turn, light feedback'),
    },
    {
      id: 'dome',
      tone: 'dome' as const,
      label: tr('گنبد نور', 'Light dome'),
      spec: tr('درخشش ۲۷۰۰ کلوین', '2700 K glow'),
    },
  ];
  return (
    <ProductRoom
      layers={layers}
      label={tr('اتاق محصول سه‌بعدی؛ برای چرخش و تمایل دادن بکشید', 'Three-dimensional product room; drag to spin and tilt')}
      caption={tr('بکشید و بچرخانید', 'Drag to orbit')}
      explodeLabel={tr('نمای انفجاری', 'Exploded view')}
      resetLabel={tr('بازنشانی نما', 'Reset view')}
      statusLabel={({ spin, tilt, exploded }) =>
        tr(
          `چرخش ${faNum(spin)}° · تمایل ${faNum(tilt)}° · ${exploded ? 'نمای انفجاری' : 'لایه‌ها چیده'}`,
          `spin ${spin}° · tilt ${tilt}° · ${exploded ? 'exploded' : 'stacked'}`,
        )
      }
    />
  );
}

/* ---- Flip cube demo: the feature rail of an outdoor watch -------------------------- */

function FlipCubeDemo() {
  const tr = useTr();
  const faces = [
    {
      id: 'display',
      icon: 'sun' as const,
      title: tr('نمایشگر آمولد', 'AMOLED display'),
      note: tr('۲۰۰۰ نیت · همیشه‌روشن', '2,000 nits · always-on'),
    },
    {
      id: 'battery',
      icon: 'zap' as const,
      title: tr('باتری ۲۱ روزه', '21-day battery'),
      note: tr('شارژ خورشیدی در کمپ', 'Solar top-up on the trail'),
    },
    {
      id: 'gps',
      icon: 'globe' as const,
      title: tr('جی‌پی‌اس دوباند', 'Dual-band GPS'),
      note: tr('نقشه‌های آفلاین قله‌ها', 'Offline summit maps'),
    },
    {
      id: 'titanium',
      icon: 'layers' as const,
      title: tr('تیتانیوم درجه ۵', 'Grade-5 titanium'),
      note: tr('وزن ۴۲ گرم روی مچ', '42 g on the wrist'),
    },
    {
      id: 'glass',
      icon: 'star' as const,
      title: tr('شیشهٔ یاقوت‌کبود', 'Sapphire glass'),
      note: tr('ضدخش، حتی روی سنگ', 'Scratch-proof on rock'),
    },
    {
      id: 'water',
      icon: 'shield' as const,
      title: tr('مقاومت آب ۱۰۰ متر', '100 m water rating'),
      note: tr('استاندارد ISO 6425', 'ISO 6425 diver test'),
    },
  ];
  return (
    <FlipCube
      faces={faces}
      label={tr('مکعب ویژگی‌ها؛ برای چرخش بکشید یا از کلیدهای جهت‌دار استفاده کنید', 'Feature cube; drag to spin, or use the arrow keys')}
      caption={tr('بکشید · کلیدهای جهت‌دار', 'Drag · arrow keys')}
      prevLabel={tr('وجه قبلی', 'Previous face')}
      nextLabel={tr('وجه بعدی', 'Next face')}
      faceLabel={(_, index) => tr(`وجه ${faNum(index + 1)}`, `Face ${index + 1}`)}
      statusLabel={({ face, total, title }) =>
        tr(
          `وجه ${faNum(face + 1)} از ${faNum(total)} · ${String(title)}`,
          `Face ${face + 1} of ${total} · ${String(title)}`,
        )
      }
    />
  );
}

/* ---- Open box demo: the box is the hero of the product page ------------------------- */

function OpenBoxDemo() {
  const tr = useTr();
  return (
    <OpenBox
      title="HALO"
      subtitle="BUDS PRO"
      price={tr('۱۴۹ دلار', '$149')}
      name="HALO BUDS PRO"
      label={tr('جعبهٔ هدیهٔ Halo Buds Pro؛ برای باز شدن هاور یا کلیک کنید', 'Halo Buds Pro gift box; hover or click to open')}
      caption={tr('هاور · کلیک', 'Hover · click')}
      openLabel={tr('باز کردن جعبه', 'Open the box')}
      closeLabel={tr('بستن جعبه', 'Close the box')}
      statusLabel={({ open, pinned }) =>
        !open
          ? tr('جعبه بسته · هاور یا کلیک کنید', 'box closed · hover or click')
          : tr(
              `درِ جعبه باز · ${faNum(110)} درجه · ${pinned ? 'سنجاق‌شده با کلیک' : 'باز روی هاور'}`,
              `lid open · 110° · ${pinned ? 'pinned by click' : 'open on hover'}`,
            )
      }
    />
  );
}

/* ---- The section ---------------------------------------------------------------------- */

export function Depth3dBlocks() {
  const tr = useTr();
  return (
    <Section
      id="depth-3d-blocks"
      eyebrow={tr('عمق سه‌بعدی', '3D depth')}
      title={tr('صحنه‌هایی که با کشیدن می‌چرخند', 'Scenes you take apart by dragging')}
      description={tr(
        'سه صحنهٔ CSS-3D خالص (perspective و preserve-3d، بدون WebGL): اتاق محصول چندلایه که با درگ می‌چرخد، با اینرسی سُر می‌خورد و نمای انفجاری‌اش لایه‌ها را با برچسب‌های روبه‌رو باز می‌کند؛ مکعب شش‌وجهی که رها که کنید روی نزدیک‌ترین وجه می‌نشیند و با نقطه‌ها و کلیدهای جهت‌دار وجه‌به‌وجه می‌چرخد؛ و جعبهٔ هدیه‌ای که هاور، درش را از لولا باز می‌کند و محصول فنری از آن می‌جهد.',
        'Three pure CSS-3D scenes (perspective + preserve-3d, no WebGL): a multi-layer product room you drag to orbit, with soft inertia and an exploded view that lifts every layer apart with a camera-facing label; a six-face cube that springs onto the nearest face on release and walks face by face with dots and arrow keys; and a gift box whose lid swings off its hinge on hover while the goods leap out on a spring.',
      )}
      code={{
        react: `<ProductRoom layers={stack} explodeLabel="Exploded view" resetLabel="Reset view" />
<FlipCube faces={features} prevLabel="Previous face" nextLabel="Next face" />
<OpenBox title="HALO" subtitle="BUDS PRO" price="$149" name="HALO BUDS PRO" />`,
        blade: `{{-- shared core CSS + the framework-agnostic behaviour --}}
<div class="nx-product-room" data-exploded>
  <div class="nx-3d-stage nx-product-room-stage" tabindex="0" role="img" aria-label="…">
    <div class="nx-product-room-world" style="--nx-n: 5">
      <div class="nx-product-room-slab" data-tone="pedestal" style="--nx-i: 0">
        <span class="nx-product-room-pin"><span class="nx-product-room-tag">…</span></span>
      </div>
    </div>
  </div>
</div>
{{ dragRotate($stage, ['inertia' => true, 'spin' => -28, 'tilt' => 62, 'minTilt' => 30, 'maxTilt' => 80]) }}`,
      }}
    >
      <Demo title={tr('اتاق محصول با نمای انفجاری', 'The product room, exploded')} wide>
        <ProductRoomDemo />
      </Demo>

      <Demo title={tr('مکعب شش‌وجهی ویژگی‌ها', 'The six-face feature cube')} wide>
        <FlipCubeDemo />
      </Demo>

      <Demo title={tr('جعبهٔ بازشو با محصول فنری', 'The open box with spring goods')} wide>
        <OpenBoxDemo />
      </Demo>
    </Section>
  );
}
