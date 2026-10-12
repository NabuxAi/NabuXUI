import { useState } from 'react';
import { Button, CtaGradient, CtaImage, CtaTerminal } from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

/** The GitHub star toggle that sits under the terminal pitch. */
function StarAction() {
  const tr = useTr();
  const [on, setOn] = useState(false);
  return (
    <Button variant="outline" icon="star" aria-pressed={on} onClick={() => setOn((value) => !value)}>
      {(12841 + (on ? 1 : 0)).toLocaleString('en')} · {tr('ستاره در گیت‌هاب', 'Star on GitHub')}
    </Button>
  );
}

export function CtaBlocks() {
  const tr = useTr();

  return (
    <Section
      id="cta-blocks"
      eyebrow={tr('دعوت به کنش', 'Call to action')}
      title={tr('سه بانگ که کاربر را حرکت می‌دهند', 'Three banners that move the user')}
      description={tr(
        'بنر گرادیانی با جاروی نور، ترمینالی که دستور نصب را تایپ می‌کند و دکمهٔ کپی‌اش تیک می‌زند، و شمای غروب با پارالاکس نشانگر و آمار اعتماد. همه‌چیز از توکن‌ها ساخته شده و در هر دو تم و هر دو جهت خوانا می‌ماند.',
        'A gradient banner with a passing light sweep, a terminal that types the install command while its copy button ticks, and a dusk scene with pointer parallax and trust stats. Everything is built from tokens and reads the same in both themes and both directions.',
      )}
      code={{
        react: `<CtaGradient badge="No credit card · 14-day trial" title="Every release, measured."
  subtitle="Adoption from the first deploy — Frankfurt to Singapore."
  primaryLabel="Start free" primaryHref="/signup" secondaryLabel="Talk to sales" secondaryHref="mailto:sales@example.com" />
<CtaGradient size="slim" title="Still reading?" primaryLabel="Start free" primaryHref="/signup" />
<CtaTerminal command="npm i -g @meridian/cli" title="Terminal to deploy, thirty seconds."
  output={['✓ meridian 2.4.1 — linked to the Frankfurt edge', '› run "meridian deploy" to ship your first release']}
  actions={<StarAction />} />
<CtaImage title="Launch. Watch. Learn."
  stats={[{ value: '99.99%', label: 'relay uptime' }, { value: '38ms', label: 'median latency · Frankfurt' }, { value: '4,200', label: 'active teams' }]}
  actionLabel="Start free" actionHref="/signup" />`,
        blade: `{{-- ui-core ships the CSS; the markup is the same classes --}}
<section class="nx-cta-gradient">
  <span class="nx-cta-gradient-sweep" aria-hidden="true"></span>
  <div class="nx-cta-gradient-body">
    <span class="nx-cta-gradient-chip">…</span>
    <h3 class="nx-cta-gradient-title">…</h3>
    <p class="nx-cta-gradient-subtitle">…</p>
    <div class="nx-cta-gradient-actions">
      <a class="nx-cta-gradient-button" href="/signup">Start free</a>
      <a class="nx-cta-gradient-button" data-variant="ghost" href="mailto:sales@example.com">Talk to sales</a>
    </div>
  </div>
</section>`,
      }}
    >
      <div className="sc-demos">
        <Demo wide title={tr('بنر میانی صفحه', 'The mid-page banner')} bare>
          <CtaGradient
            badge={tr('بدون کارت بانکی · ۱۴ روز آزمایشی', 'No credit card · 14-day trial')}
            title={tr('هر انتشار، با عدد.', 'Every release, measured.')}
            subtitle={tr(
              'پذیرش فیچرها را از همان اولین استقرار دنبال کنید — از فرانکفورت تا سنگاپور، ببینید کدام فیچر واقعاً استفاده می‌شود.',
              'Trace adoption from the first deploy — from Frankfurt to Singapore, see which features actually get used.',
            )}
            primaryLabel={tr('شروع رایگان', 'Start free')}
            primaryHref="/components"
            secondaryLabel={tr('گفتگو با فروش', 'Talk to sales')}
            secondaryHref="mailto:sales@meridian.dev"
          />
        </Demo>

        <Demo title={tr('نسخهٔ یک‌خطی', 'The slim variant')} bare>
          <CtaGradient
            size="slim"
            title={tr('هنوز همین‌هایی؟ معیارهایتان منتظرند.', 'Still reading? Your metrics are waiting.')}
            subtitle={tr('مریدین را قبل از انتشار بعدی راه بیندازید.', 'Set up Meridian before your next release.')}
            primaryLabel={tr('شروع رایگان', 'Start free')}
            primaryHref="/components"
          />
        </Demo>

        <Demo wide title={tr('ترمینال دولوپری', 'The developer terminal')} bare>
          <CtaTerminal
            badge={tr('برای دولوپرها', 'For developers')}
            title={tr('از ترمینال تا دِپلوی، ۳۰ ثانیه.', 'Terminal to deploy, thirty seconds.')}
            subtitle={tr(
              '‏CLI سبک ما با یک دستور جا می‌شود — اولین رِلیز را قبل از نوشتن اولین خط کانفیگ می‌بینید.',
              'The CLI settles in with one command — your first rollout lands before you write a line of config.',
            )}
            command="npm i -g @meridian/cli"
            terminalLabel="meridian — zsh"
            output={[
              '✓ meridian 2.4.1 — linked to the Frankfurt edge',
              '› run "meridian deploy" to ship your first release',
            ]}
            actions={
              <>
                <StarAction />
                <Button variant="link" iconEnd="external-link" href="https://cta.gallery" target="_blank" rel="noopener">
                  {tr('مستندات CLI', 'CLI docs')}
                </Button>
              </>
            }
          />
        </Demo>

        <Demo wide title={tr('شمای صحنه‌ای با آمار', 'The scenic banner with stats')} bare>
          <CtaImage
            title={tr('منتشر کن. تماشا کن. یاد بگیر.', 'Launch. Watch. Learn.')}
            subtitle={tr(
              'گرگ‌ومیشِ میان انتشار و دانستن — هر رِلیز همان‌طور که از ناوگان شما می‌گذرد روشن می‌شود.',
              'The dusk between shipping and knowing — every rollout lights up as it crosses your fleet.',
            )}
            stats={[
              { value: tr('۹۹٫۹۹٪', '99.99%'), label: tr('آپ‌تایم شبکهٔ رله', 'relay uptime') },
              { value: tr('۳۸ms', '38ms'), label: tr('میانهٔ تأخیر · فرانکفورت', 'median latency · Frankfurt') },
              { value: tr('۴٬۲۰۰', '4,200'), label: tr('تیم فعال', 'active teams') },
            ]}
            actionLabel={tr('شروع رایگان', 'Start free')}
            actionHref="/components"
          />
          <p className="sc-demo-title" style={{ marginTop: '0.75rem', fontWeight: 500 }}>
            {tr(
              'نشانگر را روی صحنه بچرخانید — ستاره‌ها، خورشید و تپه‌ها هر کدام ضریب خودشان را دارند و مخالف نشانگر می‌روند؛ با prefers-reduced-motion همه ساکن می‌مانند.',
              'Move the pointer over the scene — the stars, the sun and the hills each carry their own factor and drift against it; under prefers-reduced-motion everything holds still.',
            )}
          </p>
        </Demo>
      </div>
    </Section>
  );
}
