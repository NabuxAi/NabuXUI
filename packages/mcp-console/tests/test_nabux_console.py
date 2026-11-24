"""Tests for the Nabux MCP Console kit (nabux_console.py).

They run in NabuXUI (packages/mcp-console) and, copied by sync.sh, in every
gateway (tests/test_nabux_console.py, importing app.nabux_console). NabuAuth is
faked with httpx.MockTransport; nothing leaves the machine.
"""
from __future__ import annotations

import base64
import hashlib
import json
import sys
import time
from pathlib import Path
from urllib.parse import parse_qs, urlparse

import httpx
import pytest
from starlette.applications import Starlette
from starlette.testclient import TestClient

try:  # vendored into a gateway
    from app import nabux_console as nx
except ImportError:  # the kit itself
    sys.path.insert(0, str(Path(__file__).resolve().parents[1]))
    import nabux_console as nx

HOST = "mcp.example.test"
BASE = f"https://{HOST}"
AUTH = "https://auth.example.test"


class FakeNabuAuth:
    """Token + profile endpoints, recording what was sent."""

    def __init__(self, *, profile=None, token_status=200):
        self.profile = profile or {"sub": "42", "email": "Hussein@NabuxAI.com", "email_verified": True,
                                   "name": "Hussein Ahmadi", "picture": "https://cdn.example.test/a.png"}
        self.token_status = token_status
        self.token_forms: list[dict] = []

    def handler(self, request: httpx.Request) -> httpx.Response:
        if request.url.path == "/oauth/token":
            self.token_forms.append({k: v[0] for k, v in parse_qs(request.content.decode()).items()})
            if self.token_status != 200:
                return httpx.Response(self.token_status, json={"error": "invalid_grant"})
            return httpx.Response(200, json={"access_token": "tok-SECRET", "token_type": "Bearer"})
        if request.url.path == "/api/v1/user":
            assert request.headers["authorization"] == "Bearer tok-SECRET"
            return httpx.Response(200, json=self.profile)
        return httpx.Response(404)


def make(enabled: bool = True, fake: FakeNabuAuth | None = None):
    app = Starlette()
    logins: list[dict] = []

    def loader(request):
        uid = request.cookies.get("demo_session")
        return nx.ConsoleUser(id=uid, name="Hussein Ahmadi", email="hussein@nabuxai.com", nabu=True) if uid else None

    auth = nx.NabuAuthSettings(client_id="demo-mcp", client_secret="s3cret",
                               redirect_uri=f"{BASE}/auth/nabu/callback", url=AUTH) if enabled else nx.NabuAuthSettings()
    con = nx.Console(product="Demo MCP", platform="facebook", base_url=BASE, secret_key="k" * 48,
                     session_cookie="demo_session", user_loader=loader, nabuauth=auth,
                     nav=nx.standard_nav(accounts="/dashboard/pages", accounts_label={"en": "Pages", "fa": "صفحه‌ها"},
                                         webhooks="/dashboard/webhooks", activity=None),
                     http_transport=httpx.MockTransport((fake or FakeNabuAuth()).handler),
                     fallback_connect=({"en": "Connect directly", "fa": "اتصال مستقیم"}, "/connect"))

    def on_login(request, profile, response):
        logins.append(profile)
        response.set_cookie("demo_session", "u-" + profile["sub"], path="/")

    con.mount(app, on_login=on_login)
    route = nx.registrar(app)

    @route("/", ["GET"])
    async def home(request):
        return con.silent_sso(request) or con.render_signin(request)

    @route("/dashboard", ["GET"])
    @con.require_login
    async def dash(request):
        return con.render_page(request, "Overview", nx.card("<b>hi</b>", "Pages <i>"), active="tools",
                               subtitle="Sub & title")

    @route("/dashboard/pages", ["GET"])
    @con.require_login
    async def pages(request):
        return con.render_page(request, "Pages", "", active="accounts")

    nx.register_owner_accounts_route(app, provider="demo", token=lambda: app.state.token,
                                     lookup=lambda sub, email: app.state.lookup(sub, email))
    app.state.token = "internal-token"
    app.state.lookup = lambda sub, email: []
    client = TestClient(app, base_url=BASE, follow_redirects=False)
    return con, client, logins, app


HTML = {"accept": "text/html,application/xhtml+xml"}


# ---- escaping ---------------------------------------------------------------
def test_everything_is_escaped_unless_marked_html():
    evil = '<script>alert("x")</script>'
    assert "<script>" not in nx.card(evil, evil, subtitle=evil, actions=evil)
    assert "&lt;script&gt;" in nx.card(evil)
    assert nx.card(nx.Html("<b>ok</b>")).count("<b>ok</b>") == 1
    tbl = nx.table([evil], [[evil], [nx.Html("<em>x</em>")]])
    assert "<script>" not in tbl and "<em>x</em>" in tbl and 'data-label="&lt;script&gt;' in tbl
    assert "<script>" not in nx.badge(evil) + nx.stat(evil, evil, hint=evil) + nx.notice(evil, title=evil)
    assert "<script>" not in nx.empty_state(evil, evil) + nx.steps([(evil, evil)]) + nx.kv_list([(evil, evil)])
    assert "&quot;" in nx.button("x", href='/a"onmouseover="y')
    assert nx.esc(None) == "" and nx.esc("'") == "&#x27;"
    assert isinstance(nx.join("<", nx.Html("<i>")), nx.Html) and nx.join("<", nx.Html("<i>")) == "&lt;<i>"


def test_page_shell_escapes_title_subtitle_and_user():
    con, client, *_ = make()
    client.cookies.set("demo_session", "<u>")
    r = client.get("/dashboard?lang=en")
    assert r.status_code == 200
    assert "Pages &lt;i&gt;" in r.text and "Sub &amp; title" in r.text and "&lt;b&gt;hi&lt;/b&gt;" in r.text
    assert r.headers["x-frame-options"] == "DENY" and "frame-ancestors 'none'" in r.headers["content-security-policy"]
    assert r.headers["cache-control"] == "no-store"


# ---- language / direction ---------------------------------------------------
def _lang(headers=None, cookies=None, query=""):
    con, client, *_ = make(enabled=False)
    for k, v in (cookies or {}).items():
        client.cookies.set(k, v)
    r = client.get("/" + query, headers=headers or {})
    return r.text.split('lang="', 1)[1].split('"', 1)[0], r.text.split('dir="', 1)[1].split('"', 1)[0]


def test_language_and_direction_choice():
    assert _lang() == ("fa", "rtl")  # nothing known: Persian
    assert _lang({"accept-language": "en-US,en;q=0.9"}) == ("en", "ltr")
    assert _lang({"accept-language": "de-DE,fa;q=0.4"}) == ("fa", "rtl")
    assert _lang({"accept-language": "fa;q=0.3,en;q=0.9"}) == ("en", "ltr")
    assert _lang({"accept-language": "fr"}) == ("fa", "rtl")
    assert _lang({"accept-language": "en"}, {"nx_lang": "fa"}) == ("fa", "rtl")  # the cookie beats the browser
    assert _lang({"accept-language": "fa"}, {"nx_lang": "fa"}, "?lang=en") == ("en", "ltr")  # ?lang= beats both
    assert _lang(cookies={"nx_lang": "xx"}) == ("fa", "rtl")


def test_strings_tables_match_and_app_strings_win():
    assert set(nx.STRINGS["en"]) == set(nx.STRINGS["fa"])
    con, *_ = make()
    con.strings = {"en": {"nav.tools": "Graph tools"}}
    assert con.t("en", "nav.tools") == "Graph tools" and con.t("fa", "nav.tools") == "ابزارها"
    assert con.t("en", "tools.count", n=3) == "3 tools" and con.t("en", "no.such.key") == "no.such.key"


def test_prefs_route_sets_cookies_and_never_redirects_off_site():
    con, client, *_ = make()
    r = client.get("/console/prefs?lang=en&theme=dark&next=/dashboard")
    assert r.status_code == 303 and r.headers["location"] == "/dashboard"
    assert client.cookies.get("nx_lang") == "en" and client.cookies.get("nx_theme") == "dark"
    assert client.get("/console/prefs?next=//evil.example").headers["location"] == "/"
    assert client.get("/console/prefs?next=https://evil.example").headers["location"] == "/"
    client.cookies.set("demo_session", "u")
    assert 'data-theme="dark"' in client.get("/dashboard").text


# ---- nav / shell ------------------------------------------------------------
def test_nav_renders_sections_active_item_and_switcher():
    con, client, *_ = make()
    client.cookies.set("demo_session", "u-1")
    html = client.get("/dashboard?lang=en").text
    assert 'class="nx-nav__link is-active" href="/dashboard/tools" aria-current=page' in html
    assert ">Pages<" in html and ">Webhooks &amp; API<" in html and ">Activity<" not in html
    for p in nx.PRODUCTS:  # the switcher lists every product once, the current one marked
        assert f'href="https://{p.host}/"' in html
    assert html.count("aria-current=page>") >= 2 and "You are here" in html
    assert "Manage Nabux account" in html and 'action="/auth/logout"' in html
    fa = client.get("/dashboard/pages?lang=fa").text
    assert 'dir="rtl"' in fa and ">صفحه‌ها<" in fa and "is-active\" href=\"/dashboard/pages\"" in fa


def test_standard_nav_skips_missing_sections():
    keys = [i.key for i in nx.standard_nav(webhooks=None, activity="/a", tools=None)]
    assert keys == ["overview", "accounts", "connect", "activity", "settings"]


def test_require_login_sends_signed_out_people_to_sign_in():
    con, client, *_ = make()
    r = client.get("/dashboard?x=1")
    assert r.status_code == 302 and r.headers["location"] == "/auth/nabu/login?next=%2Fdashboard%3Fx%3D1"
    _, off, *_ = make(enabled=False)
    assert off.get("/dashboard").headers["location"] == "/"


def test_signin_page_and_disabled_notice():
    con, client, *_ = make()
    html = client.get("/?sso=0&lang=en", headers=HTML).text
    assert "Continue with Nabux" in html and "/auth/nabu/login?next=%2Fdashboard" in html and "What is MCP?" in html
    _, off, *_ = make(enabled=False)
    html = off.get("/?lang=en", headers=HTML).text
    assert "Nabux sign-in is not set up on this server" in html and 'href="/connect"' in html
    assert "Continue with Nabux" not in html


# ---- silent SSO -------------------------------------------------------------
def test_silent_sso_is_tried_once_and_never_loops():
    con, client, *_ = make()
    first = client.get("/", headers=HTML)
    assert first.status_code == 302 and first.headers["location"].startswith("/auth/nabu/login?prompt=none")
    assert client.cookies.get(nx.SSO_GUARD_COOKIE) == "1"
    start = client.get(first.headers["location"])
    q = parse_qs(urlparse(start.headers["location"]).query)
    assert q["prompt"] == ["none"] and q["client_id"] == ["demo-mcp"]
    # NabuAuth has no session: back to the sign-in page, which now renders instead of redirecting again.
    back = client.get(f"/auth/nabu/callback?error=login_required&state={q['state'][0]}")
    assert back.status_code == 303 and back.headers["location"] == "/?sso=0"
    assert client.get(back.headers["location"], headers=HTML).status_code == 200
    assert client.get("/", headers=HTML).status_code == 200  # the guard cookie holds

    # Cookies blocked: ?sso=0 alone still stops it.
    _, fresh, *_ = make()
    assert fresh.get("/?sso=0", headers=HTML).status_code == 200
    # Health checks and API clients are never redirected.
    assert fresh.get("/").status_code == 200 and fresh.get("/", headers={"accept": "*/*"}).status_code == 200
    # Without NabuAuth there is nothing to try.
    _, off, *_ = make(enabled=False)
    assert off.get("/", headers=HTML).status_code == 200


def test_consent_required_on_a_silent_attempt_also_lands_on_sign_in():
    con, client, *_ = make()
    client.get("/auth/nabu/login?prompt=none")
    r = client.get("/auth/nabu/callback?error=consent_required&state=x")
    assert r.status_code == 303 and r.headers["location"] == "/?sso=0"


# ---- sign-in round trip -----------------------------------------------------
def test_login_callback_validates_state_and_exchanges_with_pkce():
    fake = FakeNabuAuth()
    con, client, logins, _ = make(fake=fake)
    start = client.get("/auth/nabu/login?next=/dashboard/pages")
    assert start.status_code == 302
    url = urlparse(start.headers["location"])
    q = parse_qs(url.query)
    assert f"{url.scheme}://{url.netloc}{url.path}" == f"{AUTH}/oauth/authorize"
    assert q["code_challenge_method"] == ["S256"] and q["redirect_uri"] == [f"{BASE}/auth/nabu/callback"]
    assert "prompt" not in q and q["scope"] == ["openid profile email"]
    flow_cookie = client.cookies.get(nx.FLOW_COOKIE)
    assert flow_cookie and "verifier" not in flow_cookie  # signed and encoded, not a readable field

    # Forged state, missing code, and a tampered cookie are all refused without calling NabuAuth.
    assert "expired" in client.get("/auth/nabu/callback?code=c&state=forged&lang=en").text
    assert "expired" in client.get(f"/auth/nabu/callback?state={q['state'][0]}&lang=en").text
    good = client.cookies.get(nx.FLOW_COOKIE)
    client.cookies.set(nx.FLOW_COOKIE, good[:-2] + ("aa" if not good.endswith("aa") else "bb"), domain=HOST)
    assert "expired" in client.get(f"/auth/nabu/callback?code=c&state={q['state'][0]}&lang=en").text
    client.cookies.set(nx.FLOW_COOKIE, good, domain=HOST)
    assert fake.token_forms == [] and logins == []

    done = client.get(f"/auth/nabu/callback?code=the-code&state={q['state'][0]}")
    assert done.status_code == 303 and done.headers["location"] == "/dashboard/pages"
    form = fake.token_forms[0]
    assert form["client_secret"] == "s3cret" and form["code"] == "the-code" and form["grant_type"] == "authorization_code"
    challenge = base64.urlsafe_b64encode(hashlib.sha256(form["code_verifier"].encode()).digest()).decode().rstrip("=")
    assert challenge == q["code_challenge"][0]
    assert logins == [{"sub": "42", "email": "Hussein@NabuxAI.com", "email_verified": True, "name": "Hussein Ahmadi",
                       "picture": "https://cdn.example.test/a.png"}]
    assert client.cookies.get("demo_session") == "u-42" and client.cookies.get(nx.FLOW_COOKIE) is None
    # The flow cookie is single use.
    assert "expired" in client.get(f"/auth/nabu/callback?code=the-code&state={q['state'][0]}&lang=en").text


def test_expired_flow_and_offsite_next_are_refused():
    con, client, *_ = make()
    state, verifier, _ = nx.pkce()
    client.cookies.set(nx.FLOW_COOKIE, con.sign({"purpose": "nabu", "state": state, "verifier": verifier,
                                                 "exp": int(time.time()) - 1, "next": "/dashboard"}), domain=HOST)
    assert "expired" in client.get(f"/auth/nabu/callback?code=c&state={state}&lang=en").text
    start = client.get("/auth/nabu/login?next=//evil.example/x")
    flow = con.unsign(client.cookies.get(nx.FLOW_COOKIE))
    assert flow["next"] == "/dashboard" and start.status_code == 302
    for bad in ("https://evil", "//evil", "/\\evil", "", None, "/ok\r\nx"):
        assert nx.safe_next(bad, "/home") == "/home"
    assert nx.safe_next("/connect?txn=1") == "/connect?txn=1"


def test_failed_token_exchange_shows_an_error_without_secrets():
    fake = FakeNabuAuth(token_status=400)
    con, client, logins, _ = make(fake=fake)
    q = parse_qs(urlparse(client.get("/auth/nabu/login").headers["location"]).query)
    r = client.get(f"/auth/nabu/callback?code=the-code&state={q['state'][0]}")
    assert r.status_code == 200 and "HTTP 400" in r.text and logins == []
    assert "the-code" not in r.text and fake.token_forms[0]["code_verifier"] not in r.text


def test_profile_without_subject_is_refused_and_unverified_email_is_not_trusted():
    con, client, logins, _ = make(fake=FakeNabuAuth(profile={"email": "x@y.z"}))
    q = parse_qs(urlparse(client.get("/auth/nabu/login").headers["location"]).query)
    assert "did not return an account id" in client.get(f"/auth/nabu/callback?code=c&state={q['state'][0]}").text
    assert nx.verified_email({"email": " A@B.co ", "email_verified": True}) == "a@b.co"
    assert nx.verified_email({"email": "a@b.co", "email_verified": "true"}) == ""
    assert nx.verified_email({"email": "a@b.co"}) == ""


def test_cancelled_interactive_sign_in_explains_itself():
    con, client, *_ = make()
    client.get("/auth/nabu/login")
    r = client.get("/auth/nabu/callback?error=access_denied&state=x&lang=en")
    assert r.status_code == 200 and "Sign-in was cancelled" in r.text and "access_denied" in r.text


def test_logout_needs_csrf_and_ends_the_nabuauth_session():
    con, client, *_ = make()
    client.cookies.set("demo_session", "u-42", domain=HOST)
    assert client.post("/auth/logout", data={"csrf": "nope"}).headers["location"] == "/dashboard"
    assert client.cookies.get("demo_session") == "u-42"
    html = client.get("/dashboard").text
    token = html.split('name="csrf" value="', 1)[1].split('"', 1)[0]
    r = client.post("/auth/logout", data={"csrf": token})
    assert r.status_code == 303 and r.headers["location"].startswith(f"{AUTH}/oauth/logout?post_logout_redirect_uri=")
    assert client.cookies.get("demo_session") is None and client.cookies.get(nx.SSO_GUARD_COOKIE) == "1"


# ---- owner discovery --------------------------------------------------------
def test_owner_accounts_route_auth_and_shape():
    con, client, _, app = make()
    seen = []
    app.state.lookup = lambda sub, email: seen.append((sub, email)) or [
        {"account_key": "fb_1", "label": "Bright Stars", "kind": "page", "status": "connected", "connected_at": 1700000000},
        {"account_key": "fb_1", "label": "dup"},
        {"account_key": "", "label": "no key"},
        {"account_key": "fb_2", "connected_at": None},
    ]
    url = "/internal/v1/owners/accounts?sub=42&email=Hussein@NabuxAI.com"
    assert client.get(url).status_code == 401
    assert client.get(url, headers={"authorization": "Bearer wrong"}).status_code == 401
    ok = client.get(url, headers={"authorization": "Bearer internal-token"})
    assert ok.status_code == 200 and seen == [("42", "hussein@nabuxai.com")]
    assert ok.json() == {"ok": True, "provider": "demo", "accounts": [
        {"account_key": "fb_1", "label": "Bright Stars", "kind": "page", "status": "connected",
         "connected_at": "2023-11-14T22:13:20Z"},
        {"account_key": "fb_2", "label": "fb_2", "kind": "account", "status": "connected", "connected_at": None}]}
    bad = client.get("/internal/v1/owners/accounts?email=not-an-email", headers={"authorization": "Bearer internal-token"})
    assert bad.status_code == 400
    app.state.token = ""
    assert client.get(url, headers={"authorization": "Bearer "}).status_code == 404


def test_iso_time_accepts_seconds_millis_and_datetimes():
    from datetime import datetime, timezone
    assert nx.iso_time(0) is not None and nx.iso_time(1700000000000) == "2023-11-14T22:13:20Z"
    assert nx.iso_time(datetime(2026, 1, 2, tzinfo=timezone.utc)) == "2026-01-02T00:00:00Z"
    assert nx.iso_time("") is None and nx.iso_time("2026-01-02T00:00:00Z") == "2026-01-02T00:00:00Z"


# ---- static, colours --------------------------------------------------------
def test_static_files_are_served_and_nothing_else():
    con, client, *_ = make()
    css = client.get(nx.asset("console.css"))
    assert css.status_code == 200 and css.headers["content-type"].startswith("text/css")
    assert "immutable" in css.headers["cache-control"]
    assert "max-age=300" in client.get("/static/console/console.js").headers["cache-control"]
    assert client.get("/static/console/fonts/vazirmatn-arabic.woff2").headers["content-type"] == "font/woff2"
    for bad in ("../nabux_console.py", "..%2Fnabux_console.py", "nope.css", "/etc/passwd"):
        assert client.get("/static/console/" + bad).status_code == 404
    page = client.get("/?sso=0", headers=HTML).text
    assert f"console.css?v={nx.ASSET_VERSION}" in page and "fonts.googleapis" not in page


def test_platform_colours_carry_text_at_aa():
    for p in nx.PRODUCTS:
        bg, fg = nx.accessible_pair(p.color)
        assert nx.contrast(bg, fg) >= 4.5, p.key
    assert nx.accessible_pair("#FFFC00")[1] == "#111111"
    assert nx.accessible_pair("#1877F2")[1] == "#ffffff"


def test_secret_key_is_required():
    with pytest.raises(ValueError):
        nx.Console(product="x", platform="x", base_url=BASE, secret_key="short", session_cookie="s",
                   user_loader=lambda r: None)


def test_json_payload_is_plain_json():
    payload = nx.owner_accounts_payload("x", [{"account_key": "k", "connected_at": 1}])
    assert json.loads(json.dumps(payload))["accounts"][0]["connected_at"] == "1970-01-01T00:00:01Z"
