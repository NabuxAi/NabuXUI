import { useEffect, useRef, useState } from 'react';
import { type LoaderState, GlassPanel, LoaderLiquid, LoaderOrbit, LoaderRing } from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

type Job = { state: LoaderState; value: number };

/* ---- The orbit button in its real fields --------------------------------------- */

/** Save: two seconds of real work, a tick, then the button lets go back to idle. */
function SaveCard() {
  const tr = useTr();
  const [phase, setPhase] = useState<LoaderState>('idle');
  const [savedNow, setSavedNow] = useState(false);

  useEffect(() => {
    if (phase !== 'running') return;
    const done = window.setTimeout(() => {
      setPhase('success');
      setSavedNow(true);
    }, 2000);
    return () => window.clearTimeout(done);
  }, [phase]);

  useEffect(() => {
    if (phase !== 'success') return;
    const back = window.setTimeout(() => setPhase('idle'), 1800);
    return () => window.clearTimeout(back);
  }, [phase]);

  return (
    <GlassPanel className="sc-glass-card">
      <h3>Atlas Design · {tr('تنظیمات ورک‌اسپیس', 'workspace settings')}</h3>
      <p>Frankfurt · eu-central-1 · {tr('پلن تیمی · ۱۲ صندلی', 'Team plan · 12 seats')}</p>
      <p>
        <strong>Atlas Design</strong> · {tr('نام ورک‌اسپیس', 'workspace name')}
      </p>
      <div className="sc-row">
        <LoaderOrbit
          state={phase}
          label={tr('ذخیرهٔ تغییرات', 'Save changes')}
          runningLabel={tr('در حال ذخیره…', 'Saving…')}
          successLabel={tr('ذخیره شد', 'Saved')}
          onClick={() => setPhase('running')}
        />
        <span className="sc-demo-title">
          {savedNow ? tr('همین حالا ذخیره شد', 'Saved just now') : tr('آخرین ذخیره · ۲ دقیقه پیش', 'Last save · 2 minutes ago')}
        </span>
      </div>
    </GlassPanel>
  );
}

/** Sync: the first attempt dies with a 504 — the marks halt on red until the retry goes through. */
function SyncCard() {
  const tr = useTr();
  const [phase, setPhase] = useState<LoaderState>('idle');
  const [tries, setTries] = useState(0);

  useEffect(() => {
    if (phase !== 'running') return;
    const fails = tries === 1;
    const done = window.setTimeout(() => setPhase(fails ? 'error' : 'success'), fails ? 2400 : 1700);
    return () => window.clearTimeout(done);
  }, [phase, tries]);

  return (
    <GlassPanel className="sc-glass-card">
      <h3>Vector Calendar · {tr('همگام‌سازی', 'sync')}</h3>
      <div className="sc-row">
        <LoaderOrbit
          size="sm"
          state={phase}
          label={tr('همگام‌سازی با Google', 'Sync with Google')}
          runningLabel={tr('در حال همگام‌سازی…', 'Syncing…')}
          successLabel={tr('همگام شد · ۴۸ رویداد', 'Synced · 48 events')}
          errorLabel={tr('اتصال برقرار نشد', 'Connection failed')}
          onClick={() => {
            setTries((count) => count + 1);
            setPhase('running');
          }}
        />
        {phase === 'error' && (
          <span className="sc-demo-title">{tr('سرور پاسخ نداد (504) — دوباره بزنید.', 'Server did not answer (504) — press again to retry.')}</span>
        )}
      </div>
    </GlassPanel>
  );
}

/* ---- The morph ring in its real fields ----------------------------------------- */

/** A stepped climb with jitter, like a real transfer; optionally a cliff part-way. */
function useRingJob(failFirst: boolean) {
  const [run, setRun] = useState(0);
  const [tries, setTries] = useState(0);
  const [job, setJob] = useState<Job>({ state: 'idle', value: 0 });

  useEffect(() => {
    if (!run) return;
    const fails = failFirst && tries === 1;
    let p = 0;
    setJob({ state: 'running', value: 0 });
    const id = window.setInterval(() => {
      p = Math.min(100, p + (100 / 76) * (0.7 + Math.random() * 0.6));
      if (fails && p >= 58) {
        window.clearInterval(id);
        setJob({ state: 'error', value: 58 });
        return;
      }
      if (p >= 100) {
        window.clearInterval(id);
        setJob({ state: 'success', value: 100 });
        return;
      }
      setJob({ state: 'running', value: p });
    }, 55);
    return () => window.clearInterval(id);
  }, [run, tries, failFirst]);

  return {
    job,
    start: () => {
      setTries((count) => count + 1);
      setRun((count) => count + 1);
    },
  };
}

function RefundCard() {
  const tr = useTr();
  const { job, start } = useRingJob(false);

  return (
    <GlassPanel className="sc-glass-card">
      <div className="sc-row">
        <LoaderRing
          state={job.state}
          value={job.value}
          aria-label={tr('پیشرفت بازپرداخت', 'Refund progress')}
          caption={
            job.state === 'running'
              ? tr('در حال پردازش…', 'Processing…')
              : job.state === 'success'
                ? tr('۳ تا ۵ روز کاری', '3–5 business days')
                : undefined
          }
        />
        <div className="sc-stack">
          <h3>{tr('بازپرداخت · سفارش #NB-2841', 'Refund · order #NB-2841')}</h3>
          <p>€124.50 · Visa ··4242 · Stripe Frankfurt</p>
          <LoaderOrbit
            size="sm"
            state={job.state}
            label={tr('پردازش بازپرداخت', 'Process refund')}
            runningLabel={tr('در حال پردازش…', 'Processing…')}
            successLabel={tr('پردازش دوباره', 'Process another')}
            onClick={start}
          />
        </div>
      </div>
    </GlassPanel>
  );
}

function TopUpCard() {
  const tr = useTr();
  const { job, start } = useRingJob(true);

  return (
    <GlassPanel className="sc-glass-card">
      <div className="sc-row">
        <LoaderRing
          state={job.state}
          value={job.value}
          aria-label={tr('پیشرفت شارژ', 'Top-up progress')}
          caption={
            job.state === 'running'
              ? tr('در حال شارژ…', 'Charging…')
              : job.state === 'success'
                ? tr('موجودی به‌روز شد.', 'Balance updated.')
                : job.state === 'error'
                  ? tr('درگاه پاسخ نداد (502).', 'Gateway did not answer (502).')
                  : undefined
          }
        />
        <div className="sc-stack">
          <h3>Nomad wallet · {tr('شارژ', 'top-up')}</h3>
          <p>€50.00 · Mastercard ··7017 · Dublin</p>
          <LoaderOrbit
            size="sm"
            state={job.state}
            label={tr('شارژ کیف پول', 'Top up wallet')}
            runningLabel={tr('در حال شارژ…', 'Charging…')}
            successLabel={tr('شارژ دوباره', 'Top up again')}
            errorLabel={tr('تلاش دوباره', 'Retry')}
            onClick={start}
          />
        </div>
      </div>
    </GlassPanel>
  );
}

/* ---- The liquid tank in its real fields ---------------------------------------- */

/** A smooth cubic climb on rAF; resuming from a level takes the shorter run. */
function useTankJob(initial: Job) {
  const [run, setRun] = useState(initial.state === 'running' ? 1 : 0);
  const [job, setJob] = useState<Job>(initial);
  const fromRef = useRef(initial.value);

  useEffect(() => {
    if (!run) return;
    const p0 = fromRef.current;
    const t0 = performance.now();
    const duration = p0 > 0 ? 2600 : 5200;
    let raf = 0;
    const tick = (now: number) => {
      const t = Math.min(1, (now - t0) / duration);
      setJob({ state: 'running', value: p0 + (100 - p0) * (1 - Math.pow(1 - t, 3)) });
      if (t < 1) raf = requestAnimationFrame(tick);
      else setJob({ state: 'success', value: 100 });
    };
    raf = requestAnimationFrame(tick);
    return () => cancelAnimationFrame(raf);
  }, [run]);

  return {
    job,
    start: (from: number) => {
      fromRef.current = from;
      setJob({ state: 'running', value: from });
      setRun((count) => count + 1);
    },
  };
}

function MigrationTank() {
  const tr = useTr();
  const { job, start } = useTankJob({ state: 'running', value: 0 });

  return (
    <GlassPanel className="sc-glass-card">
      <div className="sc-row">
        <LoaderLiquid
          state={job.state}
          value={job.value}
          aria-label={tr('پیشرفت انتقال', 'Migration progress')}
          caption={job.state === 'success' ? tr('آماده شد', 'Ready') : tr('در حال انتقال…', 'Migrating…')}
        />
        <div className="sc-stack">
          <h3>Atlas · {tr('انتقال به فضای ابری', 'cloud migration')}</h3>
          <p>
            {tr('منبع', 'Source')} — {tr('میزبان مشترک · فرانکفورت', 'shared host · Frankfurt')}
          </p>
          <p>
            {tr('مقصد', 'Destination')} — S3 · Singapore
          </p>
          <p>
            {(3.8 * (job.value / 100)).toFixed(2)} GB / 3.80 GB
          </p>
          <LoaderOrbit
            size="sm"
            state={job.state}
            label={tr('انتقال دوباره', 'Migrate again')}
            runningLabel={tr('در حال انتقال…', 'Migrating…')}
            successLabel={tr('انتقال دوباره', 'Migrate again')}
            onClick={() => start(0)}
          />
        </div>
      </div>
    </GlassPanel>
  );
}

function BackupTank() {
  const tr = useTr();
  const { job, start } = useTankJob({ state: 'error', value: 34 });

  return (
    <GlassPanel className="sc-glass-card">
      <div className="sc-row">
        <LoaderLiquid
          size="sm"
          state={job.state}
          value={job.value}
          aria-label={tr('پیشرفت پشتیبان‌گیری', 'Backup progress')}
          caption={
            job.state === 'error'
              ? tr('خطا', 'Failed')
              : job.state === 'success'
                ? tr('آماده شد', 'Ready')
                : tr('در حال ادامه…', 'Resuming…')
          }
        />
        <div className="sc-stack">
          <h3>{tr('پشتیبان شبانه · خزانهٔ دوبلین', 'Nightly backup · Dublin vault')}</h3>
          <p>
            {job.state === 'error'
              ? tr('دیسک مقصد پر است (28) — فضا را آزاد کنید و دوباره تلاش کنید.', 'Destination disk full (28) — free space and retry.')
              : job.state === 'success'
                ? tr('فضا آزاد شد · پشتیبان‌گیری کامل شد.', 'Space freed · backup complete.')
                : tr('از ۳۴٪ ادامه می‌دهد — هیچ‌چیز از صفر شروع نمی‌شود.', 'Resuming from 34% — nothing restarts from zero.')}
          </p>
          <LoaderOrbit
            size="sm"
            state={job.state}
            label={tr('آزادسازی فضا و تلاش دوباره', 'Free space & retry')}
            runningLabel={tr('در حال ادامه…', 'Resuming…')}
            successLabel={tr('پشتیبان‌گیری دوباره', 'Back up again')}
            errorLabel={tr('آزادسازی فضا و تلاش دوباره', 'Free space & retry')}
            onClick={() => start(34)}
          />
        </div>
      </div>
    </GlassPanel>
  );
}

/* ---- The section ----------------------------------------------------------------- */

export function LoadersBlocks() {
  const tr = useTr();

  return (
    <Section
      id="loaders-blocks"
      eyebrow={tr('بارگذارها', 'Loaders')}
      title={tr('سه حالت انتظار که راست می‌گویند', 'Three waits that tell the truth')}
      description={tr(
        'نقطه‌های مداری درون دکمه‌ای که واقعاً کار می‌کند، حلقه‌ای که ضخامتش را با شکست‌هایش معامله می‌کند و درصد درست را نگه می‌دارد، و مخزنی که با دو موج ناهم‌فاز پُر می‌شود. همه حالت‌های idle/running/success/error را دارند و در خطا می‌ایستند — رنگ و نشانه و متن با هم عوض می‌شوند.',
        'Orbiting dots inside a button that really works, a ring that trades stroke weight for dashes while a thin arc keeps the honest percent, and a tank filling with two out-of-phase waves. All four states — idle, running, success, error — stop dead on failure, and every change of state moves colour, mark and label together.',
      )}
      code={{
        react: `<LoaderOrbit state={phase} label="Save changes" runningLabel="Saving…"
  successLabel="Saved" errorLabel="Connection failed" onClick={save} />
<LoaderRing state={job.state} value={job.value} aria-label="Refund progress"
  caption={job.state === 'running' ? 'Processing…' : '3–5 business days'} />
<LoaderLiquid state={tank.state} value={tank.value} aria-label="Migration progress"
  caption="Migrating…" />`,
        blade: `<button class="nx-loader-orbit" data-state="running" aria-busy>
  <span class="nx-loader-orbit-orbit" aria-hidden="true"><i></i><i></i><i></i><i></i></span>
  <span class="nx-loader-orbit-label"><span>Saving…</span></span>
</button>
<div class="nx-loader-ring" role="progressbar" aria-valuenow="58" aria-valuemin="0"
  aria-valuemax="100" data-state="running" style="--_p: 0.58">
  <svg viewBox="0 0 120 120" aria-hidden="true">
    <circle class="nx-loader-ring-track" pathLength="100"/>
    <circle class="nx-loader-ring-morph" pathLength="100"/>
    <circle class="nx-loader-ring-fill" pathLength="100"/>
  </svg>
  <span class="nx-loader-ring-read"><b class="nx-loader-ring-value">58%</b></span>
</div>
<div class="nx-loader-liquid" role="progressbar" aria-valuenow="34" data-state="error" style="--_p: 0.34">
  <svg viewBox="0 0 200 200" aria-hidden="true"><!-- bowl, waves, glass --></svg>
  <span class="nx-loader-liquid-read" data-wet><b class="nx-loader-liquid-value">34%</b></span>
</div>`,
      }}
    >
      <div className="sc-demos">
        <Demo wide title={tr('دکمه‌ای که کارش را نشان می‌دهد', 'A button that shows its work')} bare>
          <div className="sc-stack">
            <SaveCard />
            <SyncCard />
          </div>
        </Demo>

        <Demo wide title={tr('وقتی سرور پاسخ نمی‌دهد', 'When the server goes quiet')} bare>
          <div className="sc-row">
            <RefundCard />
            <TopUpCard />
          </div>
        </Demo>

        <Demo wide title={tr('مخزنی که وسط پُرشدن یخ می‌زند', 'The tank that froze mid-fill')} bare>
          <div className="sc-row">
            <MigrationTank />
            <BackupTank />
          </div>
        </Demo>
      </div>
    </Section>
  );
}
