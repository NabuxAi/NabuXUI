import { Link, router } from '@inertiajs/react';
import { Button, toast } from '@nabuxai/ui-react';
import { Shell } from '../shell';
import { STRINGS, useLang } from '../lang';

/**
 * What VisitsController (Laravel) sends down with the page. `pulse` is behind
 * `Inertia::optional` on the server (v2's lazy): it is absent on the first full
 * load and only evaluated when a partial reload asks for it — which is exactly
 * what the button below does.
 */
export interface VisitsProps {
  pulse?: string;
}

export default function Visits({ pulse }: VisitsProps) {
  const lang = useLang();
  const s = STRINGS[lang].visits;
  const intl = lang === 'fa' ? 'fa-IR' : 'en-US';

  // Format the server's timestamp in the page language, once it exists.
  const pulseText = pulse
    ? new Intl.DateTimeFormat(intl, { dateStyle: 'medium', timeStyle: 'medium' }).format(new Date(pulse))
    : null;

  return (
    <Shell title={STRINGS[lang].visits.title}>
      <div className="inr-grid">
        <section className="inr-card">
          <h3 className="inr-title">{s.linksTitle}</h3>
          <p className="inr-text">{s.linksText}</p>
          <div className="inr-row">
            {/* Buttons with an href go through SmartLink → the provider's
                linkComponent, which here is Inertia's Link. */}
            <Button variant="primary" href="/inertia" icon="grid">
              {s.links.dashboard}
            </Button>
            <Button variant="secondary" href="/inertia/gallery" icon="layers">
              {s.links.gallery}
            </Button>
            {/* A Link asking for a single prop: the server re-evaluates only
                `pulse`; the rest of the page never re-renders. */}
            <Link className="inr-link" href="/inertia/visits" only={['pulse']}>
              {s.links.self}
            </Link>
          </div>
        </section>

        <section className="inr-card">
          <h3 className="inr-title">{s.partialTitle}</h3>
          <p className="inr-text">{s.partialText}</p>
          <p className="inr-pulse" data-empty={pulseText ? undefined : ''}>
            {pulseText ?? s.pulseEmpty}
          </p>
          <div className="inr-row">
            <Button variant="primary" icon="zap" onClick={() => router.reload({ only: ['pulse'] })}>
              {s.reloadPartial}
            </Button>
            <Button
              variant="secondary"
              icon="arrow-right"
              onClick={() => {
                router.reload();
                toast(s.reloadFull);
              }}
            >
              {s.reloadFull}
            </Button>
          </div>
        </section>

        <section className="inr-card">
          <h3 className="inr-title">{s.leftTitle}</h3>
          <p className="inr-text">{s.leftText}</p>
          <div className="inr-row">
            {/* A plain anchor on purpose — an Inertia visit to a non-Inertia
                page cannot work; this one is a full document request. */}
            <a className="inr-link" href="/">
              {s.leftAction}
            </a>
          </div>
        </section>
      </div>
    </Shell>
  );
}
