# Nabux MCP Console

The shared web panel and sign-in of every Nabux MCP gateway (tm., wa., ig., fb.,
x., linkedin., threads., youtube., snap., social.nabuxai.com). One Python file, one
stylesheet, one small script and two fonts — no build step, no framework, no CDN.

The reference integration is **FaceBookMCP** (`app/panel.py`, `app/web.py`,
`tests/test_console_panel.py`). When in doubt, copy what it does.

| File | What it is |
| --- | --- |
| `nabux_console.py` | The kit: config object, page shell, HTML helpers, fa/en strings, NabuAuth sign-in (PKCE + client secret, silent SSO), `require_login`, static file route, owner discovery route. Stdlib + Starlette + httpx only. |
| `static/console/console.css` | NabuXUI tokens (`--nx-*`, light/dark, RTL) and every component style. |
| `static/console/console.js` | Theme (light/dark/system) and language cookies, copy buttons, toasts, popovers, mobile drawer, tabs, tool filter, confirm-before-submit. Everything works without it. |
| `static/console/fonts/` | Inter (Latin) and Vazirmatn (Persian) variable woff2, with their OFL licences. |
| `tests/test_nabux_console.py` | The kit's tests; also copied into each gateway. |
| `sync.sh` | Copies the kit into a gateway repo, or checks that the copy is current. |

The vendored copy in a gateway is never edited there. Change the kit here, then
re-run `sync.sh` into every repo.

---

## Vendoring into a gateway (the rollout checklist)

Do these in order. FaceBookMCP's diff on branch `claude/mcp-console` is the worked example.

### 1. Copy the kit

```bash
NabuXUI/packages/mcp-console/sync.sh /path/to/WhatsAppMCP          # writes into app/
NabuXUI/packages/mcp-console/sync.sh /path/to/Repo --check         # CI-style staleness check
```

It writes `app/nabux_console.py`, `app/static/console/**` and
`tests/test_nabux_console.py`. Check the repo's **Dockerfile** copies `app/`
whole (`COPY app ./app`); if it copies single files, add `app/static`.
Check `.dockerignore` does not exclude `app/static`.

### 2. Config (`app/config.py`)

```python
NABUAUTH_URL = os.environ.get("NABUAUTH_URL", "https://auth.nabuxai.com").strip().rstrip("/") or "https://auth.nabuxai.com"
NABUAUTH_CLIENT_ID = os.environ.get("NABUAUTH_CLIENT_ID", "").strip()           # e.g. whatsapp-mcp
NABUAUTH_CLIENT_SECRET = os.environ.get("NABUAUTH_CLIENT_SECRET", "").strip()
NABUAUTH_REDIRECT_URI = os.environ.get("NABUAUTH_REDIRECT_URI", "").strip() or f"{PUBLIC_BASE_URL}/auth/nabu/callback"
NABUAUTH_SCOPES = os.environ.get("NABUAUTH_SCOPES", "openid profile email").strip() or "openid profile email"
```

Add the five variables to `.env.example`, to every `docker-compose*.yml`
`environment:` list (Coolify only passes what the compose file names), and to
`README_COOLIFY.md`. Empty `NABUAUTH_CLIENT_ID` = NabuAuth off: the panel says so
on the sign-in page and keeps the app's old direct path working.

### 3. Owner identity in the database (`app/db.py`)

Additive migration on the owners table (WhatsAppMCP and FaceBookMCP do exactly this):

```python
have = {row[1] for row in conn.execute("PRAGMA table_info(owners)")}
for name, kind in (("nabu_sub", "TEXT"), ("email", "TEXT"), ("email_verified", "INTEGER NOT NULL DEFAULT 0"),
                   ("name", "TEXT"), ("avatar_url", "TEXT")):
    if name not in have:
        conn.execute(f"ALTER TABLE owners ADD COLUMN {name} {kind}")
conn.execute("CREATE UNIQUE INDEX IF NOT EXISTS owners_nabu_sub ON owners(nabu_sub) WHERE nabu_sub IS NOT NULL")
```

and three methods — copy FaceBookMCP's `ensure_nabu_owner`, `merge_owner`,
`owners_for_identity` and change the table list in `merge_owner` to every table
of that app with an `owner_id` column:

- `ensure_nabu_owner(sub, email, name, avatar, email_verified, claim_owner_id)` →
  `(owner_id, merged_owner_id)`. The browser's cookie owner (accounts connected
  before sign-in) is **adopted** if it has no Nabux login yet; if the Nabux
  account already has an owner, the browser owner is **merged** into it; another
  person's Nabux owner is never taken over.
- `merge_owner(target, source)` moves accounts, MCP tokens, auth codes, hooks…
- `owners_for_identity(sub, email)` → owners with that `nabu_sub`, or with that
  email **and** `email_verified=1` (compare lower-cased).

An app whose accounts live somewhere else too (WhatsApp Web sessions in a
sidecar) also moves those after a merge, as WhatsAppMCP's `move_owner` does.

### 4. The console object and the routes (`app/web.py` + `app/panel.py`)

```python
from . import nabux_console as nx

def load_user(request):                       # the app's own session → ConsoleUser
    oid = owner_id(request)                   # e.g. security.verify_cookie(request.cookies.get(COOKIE))
    owner = database.get_owner(oid) if oid else None
    if not owner:
        return None
    return nx.ConsoleUser(id=owner["id"], name=owner.get("name") or "", email=owner.get("email") or "",
                          avatar=owner.get("avatar_url") or "", nabu=bool(owner.get("nabu_sub")))

def on_login(request, profile, response):     # NabuAuth said who this is
    oid, _ = database.ensure_nabu_owner(sub=profile["sub"], email=profile["email"], name=profile["name"],
                                        avatar=profile["picture"], email_verified=profile["email_verified"],
                                        claim_owner_id=owner_id(request))
    set_cookie(response, oid)                 # the app's own session cookie

console = nx.Console(
    product="WhatsApp MCP", platform="whatsapp",          # platform = a key of nx.PRODUCTS
    base_url=config.PUBLIC_BASE_URL, secret_key=config.APP_SECRET_KEY,
    session_cookie=COOKIE, user_loader=load_user,
    nav=nx.standard_nav(accounts="/dashboard/numbers", accounts_label={"en": "Numbers", "fa": "شماره‌ها"},
                        webhooks="/dashboard/webhooks", activity="/dashboard/activity"),
    headline={"en": "…", "fa": "…"}, tagline={"en": "…", "fa": "…"},
    features=(nx.Feature({"en": "…", "fa": "…"}, {"en": "…", "fa": "…"}, "send"), ...),
    nabuauth=nx.NabuAuthSettings(client_id=config.NABUAUTH_CLIENT_ID, client_secret=config.NABUAUTH_CLIENT_SECRET,
                                 redirect_uri=config.NABUAUTH_REDIRECT_URI, url=config.NABUAUTH_URL,
                                 scopes=config.NABUAUTH_SCOPES),
    fallback_connect=({"en": "Connect WhatsApp", "fa": "اتصال واتس‌اپ"}, "/connect"),  # shown when NabuAuth is off
)
console.mount(mcp, on_login=on_login)        # /static/console/*, /console/prefs, /auth/nabu/login|callback, POST /auth/logout
```

`mount` takes the FastMCP server (`custom_route`) or a Starlette app. If the app
already has `/auth/nabu/*` or `/auth/logout` routes (WhatsAppMCP, InstagramMCP),
**delete them** — the kit's are the same flow, generalised — and keep its
`ensure_nabu_owner` merge logic inside `on_login`.

Build the console inside `register()` (not at import) so tests can monkeypatch
`config.NABUAUTH_*` before `server.build_app()`.

Static files are served by the kit route at `/static/console/<file>`; the shell
links them as `/static/console/console.css?v=<ASSET_VERSION>` — a hash of the
kit files, so a deploy busts browser caches by itself. No Starlette `Mount` needed.

### 5. Pages

```python
@mcp.custom_route("/", methods=["GET"])
async def landing(request):
    if console.current_user(request):
        return RedirectResponse(console.home_path, 302)
    return console.silent_sso(request) or console.render_signin(request)

@mcp.custom_route("/dashboard", methods=["GET"])
@console.require_login                       # signed out → NabuAuth (or / when NabuAuth is off)
async def dashboard(request):
    ctx = console.ctx(request)
    body = nx.stack(nx.grid(nx.stat("Numbers", 3, icon_name="accounts"), ..., cols=4), nx.card(...))
    return console.render_page(request, ctx.pick("نمای کلی", "Overview"), body, active="overview",
                               subtitle=..., actions=nx.button("Connect", "/connect", variant="platform"))
```

Standard sections and their `active` keys: `overview`, `accounts`, `connect`
(Connect to AI), `tools`, `activity`, `webhooks` (only if the app has webhooks or
API keys), `settings`. Keep **every existing route working**: rebuild their HTML on
`render_page` / `render_message`, never change their paths, methods or JSON.

Ready-made content:

- **Connect to AI** — `nx.connect_ai(ctx, server_name="whatsapp", token_html=...)`: endpoint + Claude,
  ChatGPT, Cursor and Claude Code tabs; pass the app's personal-token form as `token_html`
  (a `nx.post_form("/dashboard/token", ctx, nx.button(...))`).
- **Tools** — `nx.tools_list(ctx, await nx.mcp_tools(mcp))` lists what the server really exposes.
  Give every `@mcp.tool()` a one-line docstring; it is the description shown there and to AI clients.
- **Settings** — `nx.theme_switch(ctx)`, `nx.lang_switch(ctx)`, `nx.danger_zone(ctx, [...])`, sign-out form to `/auth/logout`.

Helpers (all escape their text; pass `nx.Html(...)` for markup you built safely):
`card`, `stat`, `grid`, `stack`, `badge`, `status_badge`, `table` (stacks into
labelled rows on phones), `empty_state`, `notice`, `endpoint_row` (monospace value +
copy), `code_block`, `copy_button`, `steps`, `kv_list`, `tabs`, `danger_zone`,
`button` (`primary`/`secondary`/`ghost`/`danger`/`danger-outline`/`platform`),
`post_form` (adds the console CSRF field; `confirm=` asks first), `icon`.

Forms: validate with `console.csrf_ok(request, form.get("csrf"))`; `ctx.csrf_field()` / `post_form` add it.

Language: `ctx.lang` is `fa` or `en` (`?lang=`, then the `nx_lang` cookie, then
Accept-Language, then fa). Use `ctx.pick(fa, en)` for page copy, `ctx.t(key)` for
kit strings, `ctx.num(n)` for numbers (Persian digits on fa pages). App strings can
override kit strings via `Console(strings={"en": {...}, "fa": {...}})`.

### 6. Owner discovery for Social MCP

Social MCP links every gateway to a person by their Nabux identity, with no manual linking:

```python
nx.register_owner_accounts_route(
    mcp, provider="whatsapp", token=lambda: config.INTERNAL_SERVICE_TOKEN,
    lookup=lambda sub, email: [
        {"account_key": a["account_key"], "label": a["verified_name"] or a["display_phone_number"],
         "kind": "number", "status": "connected" if a["connected"] else "disconnected",
         "connected_at": a["created_at"]}
        for oid in database.owners_for_identity(sub, email) for a in database.accounts_for_owner(oid)])
```

`GET /internal/v1/owners/accounts?sub=<sub>&email=<email>` with
`Authorization: Bearer <INTERNAL_SERVICE_TOKEN>`:

- 404 when `INTERNAL_SERVICE_TOKEN` is unset, 401 on a wrong token (constant-time compare),
  400 when neither `sub` nor a valid `email` is given.
- 200 `{"ok": true, "provider": "<name>", "accounts": [{"account_key", "label", "kind", "status", "connected_at"}]}`;
  `connected_at` is ISO 8601 UTC (unix seconds/ms are converted); duplicates are dropped.
- `account_key` **must** be the key the app's existing `/internal/v1/accounts/{account_key}` answers for —
  test that round trip (FaceBookMCP's `test_owner_accounts_route_finds_pages_by_sub_or_verified_email`).
- Match by `nabu_sub`, or by email only where NabuAuth said `email_verified: true` at sign-in.
  `nx.verified_email(profile)` gives the lower-cased email or "".
- `kind`: `page`, `profile`, `number`, `channel`, `account` … `status`: `connected`, `disconnected`, `expired` …

### 7. Tests and CI

- `tests/test_nabux_console.py` comes with the kit (imports `app.nabux_console`).
- Add app tests like FaceBookMCP's `tests/test_console_panel.py`: signed-out landing (with and
  without NabuAuth), every dashboard page signed in, NabuAuth callback with a faked NabuAuth
  (`web.console.http_transport = httpx.MockTransport(...)`), browser-owner adoption and merge,
  the discovery route.
- Add both files to the repo's CI test command.

### 8. Health checks

`GET /` answers 200 to non-browser clients (no `text/html` in Accept), so Docker /
Coolify health checks that fetch `/` keep passing. Silent SSO only redirects browsers.

---

## How sign-in behaves

- `/auth/nabu/login?next=/path` → NabuAuth `/oauth/authorize` with PKCE (S256). State,
  verifier, `next` and expiry ride in a signed, HttpOnly, 10-minute cookie (`nxc_nabu_flow`).
- `/auth/nabu/callback` checks the cookie signature, purpose, expiry and state (constant time),
  exchanges the code with the client secret and verifier, reads `/api/v1/user`, calls `on_login`,
  and redirects to `next` (same-site paths only). The code, verifier and tokens are never logged
  or echoed into a page.
- **Silent SSO**: a signed-out browser visit to `/` goes once to `/auth/nabu/login?prompt=none`.
  A NabuAuth session signs the person straight in; `login_required` / `consent_required` /
  `interaction_required` come back to `/?sso=0` and the sign-in page shows. A 30-minute guard
  cookie (`nxc_sso_tried`) plus `?sso=0` make a loop impossible even with cookies blocked.
- `POST /auth/logout` (CSRF) clears the session and goes to NabuAuth's `/oauth/logout`, which
  returns to `/`; the guard cookie stops an immediate silent sign-in.

## Design notes

Tokens are NabuXUI core's (`packages/core/src/css/tokens.css`) — lapis accent, ink/clay
neutrals, the same light/dark sets — so the panels sit in the family of NabuCRM and
NabuAuth. The platform colour appears only on the platform tile and the platform
button; `accessible_pair()` darkens it (or switches to dark text) until text on it
passes WCAG AA. Layout: 252px sidebar, sticky top bar with breadcrumb, 1120px
content; under 900px the sidebar becomes a drawer, under 720px tables stack and
popovers go full width. Focus rings on every control; `prefers-reduced-motion`
turns animation off. Pages carry a strict CSP (`script-src 'self'`), so do not add
inline `<script>` to a console page; a page that needs a third-party SDK (WhatsApp
Embedded Signup) passes `headers={"Content-Security-Policy": ...}` to `render_page`.

The product switcher list (`PRODUCTS`) lives only in `nabux_console.py`. Add a new
MCP there, run the tests, and sync to every repo.
