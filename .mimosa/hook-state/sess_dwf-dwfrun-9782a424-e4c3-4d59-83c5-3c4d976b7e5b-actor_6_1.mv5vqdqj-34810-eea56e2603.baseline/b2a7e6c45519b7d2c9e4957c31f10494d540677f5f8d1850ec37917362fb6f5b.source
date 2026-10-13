/**
 * Maps (React): hand-drawn SVG city blocks — no tiles, no SDK, nameless and
 * global.
 *
 *   MarkerMap      points of interest: pins that swell and find their label,
 *                 one details popover at a time, districts that light up under
 *                 the pointer or their legend chip.
 *   RouteMap      a transit line that inks itself through a wiping SVG mask
 *                 while the numbered stops settle in behind the pen; the panel
 *                 counts every leg and a button swaps the line.
 *   StoreLocator  a list beside the city: search, city chips, and a pick that
 *                 glides its marker to the centre of the frame.
 *
 * All state is React's (value / defaultValue / onValueChange everywhere); the
 * family needs no core behaviour. The same data ships as a visually-hidden
 * table under each map.
 */
import {
  type CSSProperties,
  type HTMLAttributes,
  type ReactNode,
  forwardRef,
  useEffect,
  useId,
  useMemo,
  useState,
} from 'react';
import { cx, useControllable, useEvent } from '../internal/hooks';
import { Icon } from '../internal/icon';
import { useT } from '../internal/provider';

const vars = (style: Record<string, string | number | undefined>) => style as CSSProperties;

/** Building footprints of the nameless city: [x, y, width, height] in the 640×400 map space. */
const CITY_BLOCKS: ReadonlyArray<readonly [number, number, number, number]> = [
  [36, 40, 112, 60],
  [168, 52, 76, 48],
  [300, 28, 124, 54],
  [452, 44, 96, 50],
  [560, 116, 56, 52],
  [36, 148, 88, 48],
  [168, 142, 76, 44],
  [286, 150, 104, 48],
  [410, 142, 72, 56],
  [56, 300, 100, 48],
  [170, 306, 64, 44],
  [312, 300, 98, 44],
  [60, 356, 140, 36],
  [232, 352, 140, 36],
  [492, 318, 108, 54],
  [560, 240, 56, 110],
];

/**
 * The city every map block draws: land, a river, two arteries, a dozen blocks
 * and a waterfront park. It carries the map's accessible name; overlays ride
 * in as children of the same SVG space.
 */
function CityArt({ label, children }: { label: string; children?: ReactNode }) {
  return (
    <svg className="nx-map-art" viewBox="0 0 640 400" preserveAspectRatio="xMidYMid slice" role="img" aria-label={label} focusable="false">
      <rect className="nx-map-land" x="0" y="0" width="640" height="400" />
      <path className="nx-map-water" d="M-10 226 C120 202 220 258 330 236 S540 196 650 226" />
      <path className="nx-map-street" d="M0 122 C160 114 320 128 640 102" />
      <path className="nx-map-street" d="M262 0 C254 120 246 260 240 400" />
      {CITY_BLOCKS.map(([x, y, w, h]) => (
        <rect key={`${x}-${y}`} className="nx-map-block" x={x} y={y} width={w} height={h} rx="10" />
      ))}
      <rect className="nx-map-park" x="486" y="226" width="78" height="86" rx="10" />
      <circle className="nx-map-leaf" cx="508" cy="252" r="7" />
      <circle className="nx-map-leaf" cx="536" cy="280" r="8" />
      <circle className="nx-map-leaf" cx="520" cy="296" r="6" />
      {children}
    </svg>
  );
}

/** Close on Escape while `active` — the listener lives only while it can fire. */
function useEscape(active: boolean, onClose: () => void) {
  useEffect(() => {
    if (!active) return;
    const onKey = (event: KeyboardEvent) => {
      if (event.key === 'Escape') onClose();
    };
    window.addEventListener('keydown', onKey);
    return () => window.removeEventListener('keydown', onKey);
  }, [active, onClose]);
}

/* ---- Marker map ---------------------------------------------------------------- */

export interface MarkerMapPoi {
  id: string;
  /** Position on the map, in percent of the frame. */
  x: number;
  y: number;
  name: string;
  /** A short classification shown beside the name ("Café", "Gallery"). */
  kind?: string;
  /** The district the pin lights up (an id from `districts`). */
  district?: string;
  /** A rating, shown as the gold pill. */
  rating?: string;
  /** Small facts (hours, distance) shown as pills. */
  meta?: string[];
  /** Any CSS background for the popover's thumbnail swatch. */
  thumb?: string;
}

export interface MarkerMapDistrict {
  id: string;
  label: string;
  /** The district's outline: polygon points in the 640×400 map space. */
  points: string;
  /** Where the district's name sits, in the same space. */
  lx: number;
  ly: number;
}

export interface MarkerMapProps extends HTMLAttributes<HTMLDivElement> {
  pois: MarkerMapPoi[];
  districts?: MarkerMapDistrict[];
  /** The map's accessible name (it is one picture to a screen reader). */
  label: string;
  /** The pin whose popover is open, controlled. */
  open?: string | null;
  defaultOpen?: string | null;
  onOpenChange?: (id: string | null) => void;
  /** Accessible name for the district legend. */
  legendLabel?: string;
}

/**
 * A city of points of interest: hover a pin and it swells with its label,
 * click and the details popover opens — one at a time, Escape folds it. The
 * districts light up under the pointer or from their legend chips.
 */
export const MarkerMap = forwardRef<HTMLDivElement, MarkerMapProps>(function MarkerMap(
  { pois, districts, label, open, defaultOpen, onOpenChange, legendLabel, className, ...rest },
  ref,
) {
  const t = useT();
  const [openId, setOpenId] = useControllable(open, defaultOpen ?? null, onOpenChange);
  const [hovered, setHovered] = useState<string | null>(null);
  const [locked, setLocked] = useState<string | null>(null);
  const activeDistrict = hovered ?? locked;
  const close = useEvent(() => setOpenId(null));
  useEscape(openId !== null, close);

  return (
    <div ref={ref} className={cx('nx-marker-map', className)} {...rest}>
      <div className="nx-map-stage" dir="ltr">
        <CityArt label={label}>
          {districts?.map((district) => (
            <polygon
              key={district.id}
              className="nx-map-district"
              points={district.points}
              data-active={activeDistrict === district.id ? '' : undefined}
            >
              <title>{district.label}</title>
            </polygon>
          ))}
          {districts?.map((district) => (
            <text key={`${district.id}-name`} className="nx-map-district-name" x={district.lx} y={district.ly}>
              {district.label}
            </text>
          ))}
        </CityArt>

        {pois.map((poi) => (
          <button
            key={poi.id}
            type="button"
            className="nx-map-pin"
            style={vars({ insetInlineStart: `${poi.x}%`, insetBlockStart: `${poi.y}%` })}
            aria-expanded={openId === poi.id}
            onPointerEnter={() => setHovered(poi.district ?? null)}
            onPointerLeave={() => setHovered(null)}
            onFocus={() => setHovered(poi.district ?? null)}
            onBlur={() => setHovered(null)}
            onClick={() => setOpenId(openId === poi.id ? null : poi.id)}
          >
            <span className="nx-map-bead" aria-hidden="true" />
            <span className="nx-map-label" aria-hidden="true" dir="auto">
              {poi.kind ? `${poi.name} · ${poi.kind}` : poi.name}
            </span>
          </button>
        ))}

        {pois.map((poi) => (
          <div
            key={`pop-${poi.id}`}
            className="nx-map-pop"
            data-open={openId === poi.id ? '' : undefined}
            data-below={poi.y < 25 ? '' : undefined}
            style={vars({ insetInlineStart: `${poi.x}%`, insetBlockStart: `${poi.y}%`, '--_thumb': poi.thumb })}
            role="group"
            aria-label={poi.name}
          >
            {poi.thumb && <span className="nx-map-thumb" aria-hidden="true" />}
            <p className="nx-map-pop-name">
              <b dir="auto">{poi.name}</b>
              {poi.kind && (
                <small dir="auto">{poi.kind}</small>
              )}
            </p>
            {(poi.rating || (poi.meta && poi.meta.length > 0)) && (
              <ul className="nx-map-pop-meta">
                {poi.rating && (
                  <li data-tone="star">
                    <Icon name="star" />
                    {poi.rating}
                  </li>
                )}
                {poi.meta?.map((fact, i) => (
                  <li key={`${fact}-${i}`}>{fact}</li>
                ))}
              </ul>
            )}
            <button type="button" className="nx-map-close" onClick={close} aria-label={t('close')}>
              <Icon name="x" />
            </button>
          </div>
        ))}
      </div>

      {districts && districts.length > 0 && (
        <ul className="nx-marker-map-legend" aria-label={legendLabel}>
          {districts.map((district) => (
            <li key={district.id}>
              <button
                type="button"
                className="nx-marker-map-chip"
                data-active={activeDistrict === district.id ? '' : undefined}
                aria-pressed={activeDistrict === district.id}
                onPointerEnter={() => setHovered(district.id)}
                onPointerLeave={() => setHovered(null)}
                onClick={() => setLocked(locked === district.id ? null : district.id)}
              >
                <i aria-hidden="true" />
                <span dir="auto">{district.label}</span>
              </button>
            </li>
          ))}
        </ul>
      )}

      <table className="nx-visually-hidden">
        <caption>{label}</caption>
        <tbody>
          {pois.map((poi) => (
            <tr key={poi.id}>
              <th scope="row">{poi.name}</th>
              <td>{[poi.kind, poi.rating, ...(poi.meta ?? [])].filter(Boolean).join(' · ')}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
});

/* ---- Route map -------------------------------------------------------------------- */

export interface RouteMapStop {
  /** Position in the 640×400 map space. */
  x: number;
  y: number;
  name: string;
  /** The leg into this stop ("7 min · 4.1 km"); the origin shows none. */
  leg?: string;
}

export interface RouteMapRoute {
  id: string;
  name: string;
  /** Travel time, shown as a badge. */
  eta?: string;
  /** Total distance, shown as a badge. */
  distance?: string;
  /** The line's path in the 640×400 map space. */
  d: string;
  stops: RouteMapStop[];
}

export interface RouteMapProps extends HTMLAttributes<HTMLDivElement> {
  routes: RouteMapRoute[];
  /** The map's accessible name. */
  label: string;
  /** The running route's id, controlled. */
  value?: string;
  defaultValue?: string;
  onValueChange?: (id: string) => void;
  /** A quiet note under the panel. */
  note?: ReactNode;
}

/**
 * A transit route that inks itself through a wiping SVG mask while its
 * numbered stops settle in behind the pen tip. The panel counts every leg —
 * ETA and distance — and the swap button trades lines and redraws the city.
 */
export const RouteMap = forwardRef<HTMLDivElement, RouteMapProps>(function RouteMap(
  { routes, label, value, defaultValue, onValueChange, note, className, ...rest },
  ref,
) {
  const [routeId, setRouteId] = useControllable(value, defaultValue ?? routes[0]?.id ?? '', onValueChange);
  const route = routes.find((item) => item.id === routeId) ?? routes[0];
  const other = routes.find((item) => item.id !== route?.id);
  const maskId = `nx-route-map-wipe${useId().replace(/:/g, '')}`;

  return (
    <div ref={ref} className={cx('nx-route-map', className)} {...rest}>
      <div className="nx-map-stage" dir="ltr">
        <CityArt label={label}>
          {routes.map((item) => (
            <path key={`ghost-${item.id}`} className="nx-route-map-ghost" d={item.d} />
          ))}
          {route && (
            // Keyed by route: swapping the line replays the whole draw.
            <g key={route.id}>
              <defs>
                <mask id={maskId} maskUnits="userSpaceOnUse" x="0" y="0" width="640" height="400">
                  <path className="nx-route-map-pen" pathLength={1} d={route.d} />
                </mask>
              </defs>
              <path className="nx-route-map-line" pathLength={1} mask={`url(#${maskId})`} d={route.d} />
              {route.stops.map((stop, i) => (
                <g
                  key={`${stop.name}-${i}`}
                  className="nx-route-map-stop"
                  style={vars({ '--nx-i': i })}
                  data-end={i === route.stops.length - 1 || undefined}
                >
                  <circle cx={stop.x} cy={stop.y} r={i === 0 || i === route.stops.length - 1 ? 8 : 7} />
                  <text x={stop.x} y={stop.y + 3.5}>
                    {i + 1}
                  </text>
                  <text className="nx-route-map-stop-name" x={stop.x} y={stop.y - 14}>
                    {stop.name}
                  </text>
                </g>
              ))}
            </g>
          )}
        </CityArt>
      </div>

      <aside className="nx-route-map-panel" aria-live="polite">
        {route && (
          <>
            <div>
              <p className="nx-route-map-title" dir="auto">
                {route.name}
              </p>
              {(route.eta || route.distance) && (
                <ul className="nx-route-map-badges">
                  {route.eta && <li className="nx-route-map-badge">{route.eta}</li>}
                  {route.distance && <li className="nx-route-map-badge">{route.distance}</li>}
                </ul>
              )}
            </div>
            <ol className="nx-route-map-steps">
              {route.stops.map((stop, i) => (
                <li key={`${stop.name}-${i}`} className="nx-route-map-step" data-end={i === route.stops.length - 1 || undefined}>
                  <i aria-hidden="true">{i + 1}</i>
                  <b dir="auto">{stop.name}</b>
                  {stop.leg && <small dir="auto">{stop.leg}</small>}
                </li>
              ))}
            </ol>
            {other && (
              <button type="button" className="nx-route-map-swap" onClick={() => setRouteId(other.id)}>
                <Icon name="arrow-right" />
                <span dir="auto">{other.name}</span>
              </button>
            )}
          </>
        )}
        {note && (
          <p className="nx-route-map-note" dir="auto">
            {note}
          </p>
        )}
      </aside>

      <table className="nx-visually-hidden">
        <caption>{label}</caption>
        <tbody>
          {routes.flatMap((item) =>
            item.stops.map((stop, i) => (
              <tr key={`${item.id}-${stop.name}-${i}`}>
                <th scope="row">{`${item.name} · ${i + 1}. ${stop.name}`}</th>
                <td>{stop.leg ?? ''}</td>
              </tr>
            )),
          )}
        </tbody>
      </table>
    </div>
  );
});

/* ---- Store locator ------------------------------------------------------------------ */

export interface StoreLocatorCity {
  id: string;
  label: string;
  /** The city's marker colour; any CSS color (a token reference reads best). */
  tint?: string;
}

export interface StoreLocatorStore {
  id: string;
  city: string;
  /** Position on the map, in percent of the frame. */
  x: number;
  y: number;
  name: string;
  street?: string;
  hours?: string;
  distance?: string;
}

export interface StoreLocatorProps extends HTMLAttributes<HTMLDivElement> {
  stores: StoreLocatorStore[];
  cities: StoreLocatorCity[];
  /** The map's accessible name. */
  label: string;
  /** Search text, controlled. */
  query?: string;
  defaultQuery?: string;
  onQueryChange?: (query: string) => void;
  /** The chip-selected city's id, controlled. */
  city?: string;
  defaultCity?: string;
  onCityChange?: (id: string) => void;
  /** The picked store's id, controlled. */
  selected?: string | null;
  defaultSelected?: string | null;
  onSelectedChange?: (id: string | null) => void;
  searchPlaceholder?: string;
  /** How the result count reads; defaults to the bare count. */
  resultsLabel?: (count: number) => ReactNode;
  /** The dead-search message. */
  emptyLabel?: ReactNode;
  resetLabel?: ReactNode;
  /** A quiet note under the map. */
  note?: ReactNode;
}

/**
 * A list beside the city: search branches by name or street, hop between
 * cities with the chips, and pick a result — the world glides until its
 * marker sits dead centre of the frame and the popover opens. The count
 * leads the list live; a dead search falls into the empty state.
 */
export const StoreLocator = forwardRef<HTMLDivElement, StoreLocatorProps>(function StoreLocator(
  {
    stores,
    cities,
    label,
    query: queryProp,
    defaultQuery,
    onQueryChange,
    city: cityProp,
    defaultCity,
    onCityChange,
    selected: selectedProp,
    defaultSelected,
    onSelectedChange,
    searchPlaceholder,
    resultsLabel,
    emptyLabel,
    resetLabel,
    note,
    className,
    ...rest
  },
  ref,
) {
  const t = useT();
  const [query, setQuery] = useControllable(queryProp, defaultQuery ?? '', onQueryChange);
  const [cityId, setCityId] = useControllable(cityProp, defaultCity ?? cities[0]?.id ?? '', onCityChange);
  const [selectedId, setSelectedId] = useControllable(selectedProp, defaultSelected ?? null, onSelectedChange);

  const cityOf = (id: string) => cities.find((city) => city.id === id);
  const model = useMemo(() => {
    const trimmed = query.trim().toLowerCase();
    const hasQuery = trimmed.length > 0;
    const results = hasQuery
      ? stores.filter((store) => `${store.name} ${store.street ?? ''}`.toLowerCase().includes(trimmed))
      : stores.filter((store) => store.city === cityId);
    return { hasQuery, results };
  }, [query, stores, cityId]);

  const selectedStore = stores.find((store) => store.id === selectedId) ?? null;
  const cityMeta = cityOf(cityId) ?? cities[0];
  const close = useEvent(() => setSelectedId(null));
  useEscape(selectedId !== null, close);

  // The world runs 55% larger than its frame: this translate puts the picked
  // marker at the centre (percentages are of the world's own size).
  const pan = selectedStore
    ? `${(50 / 1.55 - selectedStore.x).toFixed(2)}% ${(50 / 1.55 - selectedStore.y).toFixed(2)}%`
    : '0% 0%';

  const pick = (store: StoreLocatorStore) => {
    setCityId(store.city);
    setSelectedId(store.id);
  };

  return (
    <div ref={ref} className={cx('nx-store-locator', className)} data-has-query={model.hasQuery ? '' : undefined} {...rest}>
      <div className="nx-store-locator-side">
        <div className="nx-store-locator-searchbox">
          <Icon name="search" />
          <input
            className="nx-store-locator-search"
            type="search"
            value={query}
            onChange={(event) => setQuery(event.target.value)}
            placeholder={searchPlaceholder ?? t('search')}
            aria-label={t('search')}
          />
          <button type="button" className="nx-store-locator-clear" onClick={() => setQuery('')} aria-label={t('fmClearSearch')}>
            <Icon name="x" />
          </button>
        </div>

        <div className="nx-store-locator-chips">
          {cities.map((city) => (
            <button
              key={city.id}
              type="button"
              className="nx-store-locator-chip"
              style={vars({ '--_tint': city.tint })}
              aria-pressed={cityId === city.id}
              onClick={() => {
                setCityId(city.id);
                setSelectedId(null);
              }}
            >
              <i aria-hidden="true" />
              <span dir="auto">{city.label}</span>
            </button>
          ))}
        </div>

        <p className="nx-store-locator-count" role="status">
          {resultsLabel ? resultsLabel(model.results.length) : model.results.length}
        </p>

        {model.results.length > 0 && (
          <ul className="nx-store-locator-list">
            {model.results.map((store) => (
              <li key={store.id}>
                <button
                  type="button"
                  className="nx-store-locator-row"
                  style={vars({ '--_tint': cityOf(store.city)?.tint })}
                  aria-current={selectedId === store.id ? 'true' : undefined}
                  onClick={() => pick(store)}
                >
                  <span className="nx-store-locator-dot" aria-hidden="true" />
                  <span className="nx-store-locator-main">
                    <b dir="auto">{store.name}</b>
                    {store.street && (
                      <small dir="auto">{store.street}</small>
                    )}
                  </span>
                  {model.hasQuery && cityOf(store.city) && (
                    <span className="nx-store-locator-tag" dir="auto">
                      {cityOf(store.city)!.label}
                    </span>
                  )}
                </button>
              </li>
            ))}
          </ul>
        )}

        {model.results.length === 0 && (
          <div className="nx-store-locator-empty">
            <Icon name="search" />
            <p>{emptyLabel ?? t('noResults')}</p>
            {model.hasQuery && (
              <button
                type="button"
                className="nx-store-locator-reset"
                onClick={() => {
                  setQuery('');
                  setSelectedId(null);
                }}
              >
                {resetLabel ?? t('fmClearSearch')}
              </button>
            )}
          </div>
        )}
      </div>

      <div className="nx-store-locator-mapcol">
        <div className="nx-map-stage" dir="ltr">
          <span className="nx-store-locator-citybadge" style={vars({ '--_tint': cityMeta?.tint })}>
            <i aria-hidden="true" />
            <span dir="auto">{cityMeta?.label}</span>
          </span>

          <div className="nx-store-locator-world" style={vars({ translate: pan })}>
            <CityArt label={label} />

            {stores
              .filter((store) => store.city === cityId)
              .map((store, i) => (
                <button
                  key={store.id}
                  type="button"
                  className="nx-map-pin nx-store-locator-pin"
                  style={vars({
                    insetInlineStart: `${store.x}%`,
                    insetBlockStart: `${store.y}%`,
                    '--_tint': cityOf(store.city)?.tint,
                    '--nx-i': i,
                  })}
                  aria-pressed={selectedId === store.id}
                  onClick={() => pick(store)}
                >
                  <span className="nx-map-bead" aria-hidden="true" />
                  <span className="nx-map-label" aria-hidden="true" dir="auto">
                    {store.name}
                  </span>
                </button>
              ))}

            {stores
              .filter((store) => store.city === cityId)
              .map((store) => (
                <div
                  key={`pop-${store.id}`}
                  className="nx-map-pop nx-store-locator-pop"
                  data-open={selectedId === store.id ? '' : undefined}
                  data-flip={store.x > 70 ? '' : undefined}
                  style={vars({ insetInlineStart: `${store.x}%`, insetBlockStart: `${store.y}%` })}
                  role="group"
                  aria-label={store.name}
                >
                  <p className="nx-map-pop-name">
                    <b dir="auto">{store.name}</b>
                    {store.street && (
                      <small dir="auto">{store.street}</small>
                    )}
                  </p>
                  {(store.hours || store.distance) && (
                    <ul className="nx-map-pop-meta">
                      {store.hours && <li>{store.hours}</li>}
                      {store.distance && <li>{store.distance}</li>}
                    </ul>
                  )}
                  <button type="button" className="nx-map-close" onClick={close} aria-label={t('close')}>
                    <Icon name="x" />
                  </button>
                </div>
              ))}
          </div>
        </div>

        {note && (
          <p className="nx-store-locator-note" dir="auto">
            {note}
          </p>
        )}
      </div>

      <table className="nx-visually-hidden">
        <caption>{label}</caption>
        <tbody>
          {stores.map((store) => (
            <tr key={store.id}>
              <th scope="row">{`${store.name}${cityOf(store.city) ? ` (${cityOf(store.city)!.label})` : ''}`}</th>
              <td>{[store.street, store.hours, store.distance].filter(Boolean).join(' · ')}</td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
});
