import { useMemo } from 'react';
import { router } from '@inertiajs/react';
import {
  BarChart,
  Button,
  Gauge,
  StatStrip,
  TimelineFeed,
  toast,
  type IconName,
  type StatStripStat,
  type TimelineEntry,
} from '@nabuxai/ui-react';
import { Shell } from '../shell';
import { STRINGS, useLang } from '../lang';

/** What DashboardController (Laravel) sends down with the page. */
export interface DashboardProps {
  stats: Array<{ id: 'visits' | 'uptime' | 'deploys' | 'incidents'; value: number; delta: number | null }>;
  traffic: { days: string[]; visits: number[] };
  latency: number;
  events: Array<{ id: string; actor: string; kind: 'deploy' | 'incident' | 'release' | 'backup'; target: string; minutesAgo: number }>;
}

/** The stat glyph per id — the server sends numbers, the client owns the words. */
const STAT_ICONS: Record<DashboardProps['stats'][number]['id'], IconName> = {
  visits: 'users',
  uptime: 'zap',
  deploys: 'layers',
  incidents: 'bell',
};

/** The feed tone per event kind. */
const EVENT_TONES: Record<DashboardProps['events'][number]['kind'], TimelineEntry['tone']> = {
  deploy: 'info',
  incident: 'danger',
  release: 'success',
  backup: 'neutral',
};

export default function Dashboard({ stats, traffic, latency, events }: DashboardProps) {
  const lang = useLang();
  const s = STRINGS[lang].dashboard;
  const intl = lang === 'fa' ? 'fa-IR' : 'en-US';

  const strip: StatStripStat[] = stats.map((stat) => ({
    icon: STAT_ICONS[stat.id],
    label: s.stats[stat.id].label,
    value: stat.value,
    caption:
      stat.delta == null
        ? s.stats[stat.id].caption
        : `${new Intl.NumberFormat(intl, { signDisplay: 'always', maximumFractionDigits: 1 }).format(stat.delta)}${lang === 'fa' ? '٪' : '%'} · ${s.stats[stat.id].caption}`,
  }));

  // The chart's x labels follow the language's calendar, from the server's ISO days.
  const chartData = useMemo(() => {
    const day = new Intl.DateTimeFormat(intl, { day: 'numeric' });
    return traffic.days.map((iso, i) => ({ label: day.format(new Date(`${iso}T00:00:00Z`)), value: traffic.visits[i] ?? 0 }));
  }, [intl, traffic]);

  const feed: TimelineEntry[] = events.map((event) => ({
    id: event.id,
    actor: event.actor,
    text: s.feed.sentences[event.kind],
    target: event.target,
    time: Date.now() - event.minutesAgo * 60_000,
    tone: EVENT_TONES[event.kind],
  }));

  const reload = () => {
    router.reload();
    toast(s.reloaded);
  };

  return (
    <Shell title={STRINGS[lang].dashboard.title}>
      <div className="inr-grid">
        <section className="inr-card inr-hero">
          <div>
            <h2>{s.welcomeTitle}</h2>
            <p>{s.welcomeText}</p>
          </div>
          <div className="inr-row">
            <Button variant="primary" icon="bell" onClick={() => toast(s.actionPrimary)}>
              {s.actionPrimary}
            </Button>
            <Button variant="secondary" icon="arrow-right" onClick={reload}>
              {s.actionSecondary}
            </Button>
          </div>
        </section>

        <StatStrip stats={strip} aria-label={s.statsLabel} />

        <section className="inr-card">
          <BarChart data={chartData} title={s.chart.title} subtitle={s.chart.subtitle} height={280} />
        </section>

        <div className="inr-duo">
          <section className="inr-card">
            <Gauge value={latency} max={300} unit="ms" title={s.gauge.title} subtitle={s.gauge.subtitle} size="16rem" />
          </section>
          <section className="inr-card">
            <h3 className="inr-title">{s.feed.title}</h3>
            <TimelineFeed entries={feed} label={s.feed.label} />
          </section>
        </div>
      </div>
    </Shell>
  );
}
