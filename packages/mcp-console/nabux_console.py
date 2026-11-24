"""Nabux MCP Console: the shared web panel every Nabux MCP gateway serves.

One file, vendored into each gateway as ``app/nabux_console.py`` together with
``app/static/console/`` (see README.md). It depends on nothing but the standard
library, Starlette and httpx, which every gateway already has.

What it gives a gateway:

- ``Console``: the app's config (product, platform, host, nav, NabuAuth client)
  and ``render_page`` / ``render_signin`` / ``render_message`` that draw the
  whole shell (top bar, product switcher, user menu, sidebar, theme and
  language switches) around the page body the app builds.
- Small HTML helpers (``card``, ``stat``, ``badge``, ``table``, ``endpoint_row``,
  ``steps``, ``danger_zone`` ...). Every value is escaped unless it is ``Html``.
- fa/en strings with ``t()``. Persian pages are RTL. The language comes from
  ``?lang=``, then the ``nx_lang`` cookie, then Accept-Language, then fa.
- NabuAuth sign-in (authorization code + PKCE + client secret), generalised
  from WhatsAppMCP: ``/auth/nabu/login``, ``/auth/nabu/callback``,
  ``/auth/logout``. The state and verifier ride in a signed cookie. The code,
  the verifier and the tokens are never logged.
- Silent SSO: the first signed-out visit tries ``prompt=none`` once; a guard
  cookie and a ``?sso=0`` marker make sure it never loops.
- ``current_user`` and ``require_login`` on top of the app's own session.
- The owner discovery route Social MCP uses to find every account a Nabux user
  owns: ``GET /internal/v1/owners/accounts?sub=&email=``.

Keep this file identical in every gateway. Change it in NabuXUI
(``packages/mcp-console``) and run ``sync.sh``.
"""
from __future__ import annotations

import base64
import functools
import hashlib
import hmac
import html as _html
import json
import os
import re
import secrets
import time
from dataclasses import dataclass, field
from datetime import datetime, timezone
from pathlib import Path
from typing import Any, Awaitable, Callable, Iterable, Mapping, Optional, Sequence, Union
from urllib.parse import quote, urlencode

import httpx
from starlette.requests import Request
from starlette.responses import HTMLResponse, JSONResponse, RedirectResponse, Response

KIT_VERSION = "1.1.0"
STATIC_DIR = Path(__file__).resolve().parent / "static" / "console"
STATIC_PREFIX = "/static/console"

LANG_COOKIE = "nx_lang"
THEME_COOKIE = "nx_theme"
FLOW_COOKIE = "nxc_nabu_flow"
SSO_GUARD_COOKIE = "nxc_sso_tried"
SSO_GUARD_TTL = 30 * 60
FLOW_TTL = 600
LANGS = ("fa", "en")
RTL_LANGS = {"fa"}
# NabuAuth answers a prompt=none request with one of these when it cannot sign
# the person in without showing them a screen.
SILENT_ERRORS = {"login_required", "consent_required", "interaction_required", "account_selection_required"}


# ---------------------------------------------------------------------------
# Escaping
# ---------------------------------------------------------------------------
class Html(str):
    """Markup that is already safe. Everything that is not ``Html`` is escaped.

    ``Html + Html`` is Html. A plain ``str`` on either side of ``+`` raises
    TypeError: Python cannot tell markup you wrote (``'<div>' + button(...)``,
    which would come out escaped as visible ``&lt;div&gt;``) from text that
    must be escaped (a user name). Say which one it is:

    - ``join('<b>', x)`` / ``tag('<div class="a">', x, close='</div>')`` treat
      a plain str as *text* (escaped) or the open/close strings as *markup*;
    - ``Html('<div>') + x`` marks markup you wrote yourself;
    - an f-string (``Html(f'<div>{h(x)}</div>')``) for anything longer.
    """

    def __add__(self, other: Any) -> "Html":
        if isinstance(other, Html):
            return Html(str.__add__(self, other))
        return _concat_error(other, "Html + str")

    def __radd__(self, other: Any) -> "Html":
        if isinstance(other, Html):
            return Html(str.__add__(other, self))
        return _concat_error(other, "str + Html")


def _concat_error(other: Any, shape: str) -> Any:
    if not isinstance(other, str):
        return NotImplemented
    raise TypeError(
        f"nabux_console: {shape} is ambiguous: is the str markup or text? Wrap markup you wrote in Html(...), "
        "or build it with join(...) / tag(open, *items, close=...), which escape text and keep Html as it is.")


def esc(value: Any) -> str:
    """Escape any value for HTML text and attribute context. None is ""."""
    return _html.escape(str(value if value is not None else ""), quote=True)


def h(value: Any) -> str:
    """``Html`` passes through; anything else is escaped. Lists are joined."""
    if isinstance(value, Html):
        return str(value)
    if isinstance(value, (list, tuple)):
        return "".join(h(v) for v in value)
    return esc(value)


def join(*parts: Any) -> Html:
    """Parts side by side: Html kept, everything else escaped as text; None and "" skipped."""
    return Html("".join(h(p) for p in parts if p is not None and p != ""))


def tag(open_: str, *items: Any, close: str = "") -> Html:
    """``open_`` + items + ``close``: the two ends are markup you wrote, each item is
    escaped unless it is Html. ``tag('<div class="nx-actions">', a, b, close='</div>')``."""
    return Html(str(open_) + "".join(h(i) for i in items if i is not None) + str(close))


# A separator between short facts ("3 in · 2 out"). Persian uses the Persian
# comma: a middle dot beside a Persian digit reads as a zero ("۱ ·" looks like "۱۰").
SEPARATORS = {"fa": "، ", "en": " · "}
_FA_DIGIT = "[\u06f0-\u06f9\u0660-\u0669]"
_FA_DOT = re.compile(f"[ \u00a0]*\u00b7[ \u00a0]*(?={_FA_DIGIT})|(?<={_FA_DIGIT})[ \u00a0]*\u00b7[ \u00a0]*")


def sep(lang: str) -> str:
    return SEPARATORS.get(lang, SEPARATORS["en"])


def sep_join(lang: str, *parts: Any) -> Html:
    """Non-empty parts joined with the reader's separator (escaped unless Html)."""
    return Html(esc(sep(lang)).join(h(p) for p in parts if p is not None and p != ""))


def fa_separators(text: str) -> str:
    """A middle dot next to a Persian digit becomes a Persian comma. Every fa page goes through this."""
    return _FA_DOT.sub(SEPARATORS["fa"], text)


def _attrs(attrs: Optional[Mapping[str, Any]]) -> str:
    out = []
    for key, value in (attrs or {}).items():
        if value is None or value is False:
            continue
        name = key.rstrip("_").replace("_", "-")
        out.append(f" {esc(name)}" if value is True else f' {esc(name)}="{esc(value)}"')
    return "".join(out)


# ---------------------------------------------------------------------------
# Icons (24px grid). UI icons are strokes in the Lucide style (ISC).
# ---------------------------------------------------------------------------
_UI_ICONS = {
    "overview": '<rect x="3" y="3" width="7" height="9" rx="1.5"/><rect x="14" y="3" width="7" height="5" rx="1.5"/><rect x="14" y="12" width="7" height="9" rx="1.5"/><rect x="3" y="16" width="7" height="5" rx="1.5"/>',
    "accounts": '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
    "connect": '<path d="M12 8V4H8"/><rect x="4" y="8" width="16" height="12" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/>',
    "tools": '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
    "webhooks": '<path d="M18 16.98h-5.99c-1.1 0-1.95.94-2.48 1.9A4 4 0 0 1 2 17c.01-.7.2-1.4.57-2"/><path d="m6 17 3.13-5.78c.53-.97.1-2.18-.5-3.1a4 4 0 1 1 6.89-4.06"/><path d="m12 6 3.13 5.73C15.66 12.7 16.9 13 18 13a4 4 0 0 1 0 8"/>',
    "activity": '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
    "settings": '<path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/>',
    "grid": '<circle cx="5" cy="5" r="1.6"/><circle cx="12" cy="5" r="1.6"/><circle cx="19" cy="5" r="1.6"/><circle cx="5" cy="12" r="1.6"/><circle cx="12" cy="12" r="1.6"/><circle cx="19" cy="12" r="1.6"/><circle cx="5" cy="19" r="1.6"/><circle cx="12" cy="19" r="1.6"/><circle cx="19" cy="19" r="1.6"/>',
    "sun": '<circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>',
    "moon": '<path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/>',
    "monitor": '<rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/>',
    "languages": '<path d="m5 8 6 6"/><path d="m4 14 6-6 2-3"/><path d="M2 5h12"/><path d="M7 2h1"/><path d="m22 22-5-10-5 10"/><path d="M14 18h6"/>',
    "logout": '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/>',
    "external": '<path d="M15 3h6v6"/><path d="M10 14 21 3"/><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>',
    "copy": '<rect x="8" y="8" width="14" height="14" rx="2"/><path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2"/>',
    "check": '<path d="M20 6 9 17l-5-5"/>',
    "menu": '<path d="M4 6h16"/><path d="M4 12h16"/><path d="M4 18h16"/>',
    "close": '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
    "chevron": '<path d="m6 9 6 6 6-6"/>',
    "chevron-right": '<path d="m9 18 6-6-6-6"/>',
    "cursor": '<path d="M4.04 4.24a.5.5 0 0 1 .65-.65l15.5 6.06a.5.5 0 0 1-.06.95l-6.29 1.66a2 2 0 0 0-1.43 1.43l-1.66 6.29a.5.5 0 0 1-.95.06z"/>',
    "terminal": '<path d="m4 17 6-6-6-6"/><path d="M12 19h8"/>',
    "arrow": '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
    "shield": '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
    "key": '<path d="m15.5 7.5 2.3 2.3a1 1 0 0 0 1.4 0l2.1-2.1a1 1 0 0 0 0-1.4L19 4"/><path d="m21 2-9.6 9.6"/><circle cx="7.5" cy="15.5" r="5.5"/>',
    "inbox": '<path d="M22 12h-6l-2 3h-4l-2-3H2"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>',
    "zap": '<path d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z"/>',
    "info": '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>',
    "alert": '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/>',
    "search": '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
    "plus": '<path d="M5 12h14"/><path d="M12 5v14"/>',
    "lock": '<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
    "sparkles": '<path d="M9.94 15.5A2 2 0 0 0 8.5 14.06l-6.14-1.58a.5.5 0 0 1 0-.96L8.5 9.94A2 2 0 0 0 9.94 8.5l1.58-6.14a.5.5 0 0 1 .96 0L14.06 8.5A2 2 0 0 0 15.5 9.94l6.14 1.58a.5.5 0 0 1 0 .96L15.5 14.06a2 2 0 0 0-1.44 1.44l-1.58 6.14a.5.5 0 0 1-.96 0z"/>',
    "message": '<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"/>',
    "send": '<path d="M14.54 21.69a.5.5 0 0 0 .94-.03l6.5-19a.5.5 0 0 0-.64-.64l-19 6.5a.5.5 0 0 0-.02.94l7.93 3.18a2 2 0 0 1 1.11 1.11z"/><path d="m21.85 2.15-10.94 10.94"/>',
    "chart": '<path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="M18 17V9"/><path d="M13 17V5"/><path d="M8 17v-3"/>',
    "user": '<circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/>',
    "plug": '<path d="M12 22v-5"/><path d="M9 8V2"/><path d="M15 8V2"/><path d="M18 8v5a4 4 0 0 1-4 4h-4a4 4 0 0 1-4-4V8Z"/>',
    "book": '<path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H19a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1H6.5a1 1 0 0 1 0-5H20"/>',
    "refresh": '<path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/>',
    "phone": '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
    "phone-incoming": '<path d="M16 2v6h6"/><path d="m22 2-6 6"/><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
    "qr": '<rect x="3" y="3" width="5" height="5" rx="1"/><rect x="16" y="3" width="5" height="5" rx="1"/><rect x="3" y="16" width="5" height="5" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/><path d="M12 7v3a2 2 0 0 1-2 2H7"/><path d="M3 12h.01"/><path d="M12 3h.01"/><path d="M12 16v.01"/><path d="M16 12h1"/><path d="M21 12v.01"/><path d="M12 21v-1"/>',
    "trash": '<path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>',
}

# Other names for the same drawing.
_UI_ICONS["calls"] = _UI_ICONS["phone-incoming"]
_UI_ICONS["qr-code"] = _UI_ICONS["qr"]

# Platform glyphs, filled, drawn on the brand tile. Simplified marks, not logos.
_PLATFORM_GLYPHS = {
    "telegram": '<path d="M21.4 4.3 2.95 11.43c-1.26.5-1.25 1.2-.23 1.52l4.74 1.48 1.83 5.6c.22.62.11.86.76.86.5 0 .72-.23 1-.5l2.37-2.3 4.92 3.63c.9.5 1.56.24 1.79-.84l3.23-15.24c.33-1.33-.5-1.93-1.96-1.34ZM9.12 14.46l8.73-5.51c.43-.27.83-.12.5.17l-7.48 6.75-.3 3.14Z"/>',
    "whatsapp": '<path fill-rule="evenodd" d="M12 2.6a9.4 9.4 0 0 0-8.1 14.15L2.6 21.4l4.77-1.25A9.4 9.4 0 1 0 12 2.6Zm0 1.75a7.65 7.65 0 1 1-3.9 14.23l-.28-.17-2.84.75.76-2.77-.18-.29A7.65 7.65 0 0 1 12 4.35Z"/><path d="M9.3 7.6c-.17-.38-.35-.39-.52-.4h-.44c-.15 0-.4.06-.61.29-.21.23-.8.78-.8 1.9s.82 2.2.93 2.35c.12.15 1.59 2.53 3.92 3.45 1.94.76 2.33.61 2.75.57.42-.04 1.36-.55 1.55-1.09.19-.53.19-.99.13-1.09-.06-.1-.21-.15-.44-.27l-1.55-.76c-.21-.08-.36-.11-.51.12-.15.23-.6.76-.73.91-.13.15-.27.17-.5.06-.23-.12-.97-.36-1.85-1.14-.68-.61-1.14-1.36-1.28-1.59-.13-.23-.01-.35.1-.47l.35-.4c.11-.14.15-.23.23-.38.08-.15.04-.29-.02-.4l-.71-1.86Z"/>',
    "instagram": '<path fill-rule="evenodd" d="M7.8 2.5h8.4a5.3 5.3 0 0 1 5.3 5.3v8.4a5.3 5.3 0 0 1-5.3 5.3H7.8a5.3 5.3 0 0 1-5.3-5.3V7.8a5.3 5.3 0 0 1 5.3-5.3Zm0 1.9a3.4 3.4 0 0 0-3.4 3.4v8.4a3.4 3.4 0 0 0 3.4 3.4h8.4a3.4 3.4 0 0 0 3.4-3.4V7.8a3.4 3.4 0 0 0-3.4-3.4Zm4.2 3.15a4.45 4.45 0 1 1 0 8.9 4.45 4.45 0 0 1 0-8.9Zm0 1.9a2.55 2.55 0 1 0 0 5.1 2.55 2.55 0 0 0 0-5.1Zm4.85-2.55a1.1 1.1 0 1 1 0 2.2 1.1 1.1 0 0 1 0-2.2Z"/>',
    "facebook": '<path d="M15.12 5.32H17V2.14A26.11 26.11 0 0 0 14.26 2c-2.72 0-4.58 1.66-4.58 4.7v2.62H6.61v3.56h3.07V22h3.68v-9.12h3.06l.46-3.56h-3.52V7.05c0-1.03.28-1.73 1.76-1.73Z"/>',
    "x": '<path d="M17.75 3h3.07l-6.7 7.66L22 21h-6.17l-4.83-6.32L5.47 21H2.4l7.17-8.2L2 3h6.33l4.37 5.78ZM16.67 19.18h1.7L7.4 4.73H5.58Z"/>',
    "linkedin": '<path d="M4.2 9.2h3.3V20H4.2Zm1.65-5.4a1.92 1.92 0 1 1 0 3.84 1.92 1.92 0 0 1 0-3.84ZM9.6 9.2h3.17v1.48h.05c.44-.84 1.52-1.72 3.13-1.72 3.35 0 3.97 2.2 3.97 5.07V20h-3.3v-5.24c0-1.25-.02-2.86-1.74-2.86-1.75 0-2.01 1.36-2.01 2.77V20H9.6Z"/>',
    "threads": '<path fill-rule="evenodd" d="M12.18 2.5c2.73.02 4.86.9 6.26 2.58.7.85 1.2 1.86 1.52 3.05l-1.83.49c-.26-.94-.65-1.73-1.17-2.37-1.04-1.27-2.62-1.92-4.79-1.93-2.15.02-3.76.72-4.8 2.08-.97 1.27-1.48 3.12-1.5 5.5.02 2.38.53 4.23 1.5 5.5 1.04 1.36 2.65 2.06 4.8 2.08 1.94-.02 3.21-.47 4.28-1.53 1.21-1.2 1.19-2.66.8-3.55-.23-.52-.65-.96-1.22-1.29-.15 1.03-.47 1.86-.97 2.5-.67.85-1.63 1.32-2.86 1.39-.93.05-1.83-.17-2.53-.63a2.98 2.98 0 0 1-1.36-2.37c-.08-1.68 1.25-2.95 3.4-3.08.76-.04 1.47-.01 2.13.1-.09-.53-.26-.94-.52-1.24-.36-.42-.92-.63-1.66-.64h-.02c-.6 0-1.4.17-1.92.94l-1.57-1.06c.7-1.03 1.83-1.6 3.49-1.6h.03c2.43.02 3.62 1.5 3.79 3.6.43.18.82.4 1.17.66 1.08.8 1.85 2.03 2.02 3.58.2 1.84-.43 3.65-1.84 5.04-1.43 1.41-3.2 2.04-5.6 2.06-2.73-.02-4.83-.92-6.25-2.68C4.84 17.1 4.18 14.86 4.16 12c.02-2.86.68-5.1 1.98-6.82C7.56 3.42 9.66 2.52 12.18 2.5Zm.72 9.17c-.25 0-.5 0-.77.02-1.5.09-1.67.84-1.65 1.2.04.74.81 1.17 1.85 1.11 1.02-.06 2.08-.47 2.27-2.15a7.2 7.2 0 0 0-1.7-.18Z"/>',
    "youtube": '<path fill-rule="evenodd" d="M21.58 7.19a2.5 2.5 0 0 0-1.77-1.77C18.25 5 12 5 12 5s-6.25 0-7.81.42a2.5 2.5 0 0 0-1.77 1.77C2 8.75 2 12 2 12s0 3.25.42 4.81a2.5 2.5 0 0 0 1.77 1.77C5.75 19 12 19 12 19s6.25 0 7.81-.42a2.5 2.5 0 0 0 1.77-1.77C22 15.25 22 12 22 12s0-3.25-.42-4.81ZM10 15V9l5.2 3Z"/>',
    "snapchat": '<path d="M12.07 2.75c1.7 0 3.25.73 4.09 2.15.55.93.48 2.5.4 3.82l-.04.62c.2.1.46.13.73.04.35-.12.74-.14 1 .03.4.25.33.76-.03.97-.2.12-.5.22-.82.32-.48.15-1.01.33-.9.77.37 1.47 1.62 2.7 3.22 3.29.27.1.33.36.2.58-.36.6-1.58.73-2.1.86-.13.24-.1.75-.4.86-.43.15-1.06-.09-1.86.04-1.06.18-1.82 1.45-3.53 1.45s-2.43-1.27-3.49-1.45c-.8-.13-1.43.11-1.86-.04-.3-.11-.27-.62-.4-.86-.52-.13-1.74-.26-2.1-.86-.13-.22-.07-.48.2-.58 1.6-.59 2.85-1.82 3.22-3.29.11-.44-.42-.62-.9-.77-.32-.1-.62-.2-.82-.32-.36-.21-.43-.72-.03-.97.26-.17.65-.15 1-.03.27.09.53.06.73-.04l-.04-.62c-.08-1.32-.15-2.89.4-3.82.84-1.42 2.39-2.15 4.09-2.15h.15Z"/>',
    "social": '<circle cx="18" cy="5.5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="18.5" r="3"/><path d="m8.6 13.5 6.8 3.9M15.4 7l-6.8 3.9" stroke="currentColor" stroke-width="1.8" fill="none"/>',
    "nabux": '<path d="M6.5 18V6h2.6l6.3 7.7V6H18v12h-2.6L9.1 10.3V18Z"/>',
}


def icon(name: str, size: int = 16, *, label: str | None = None, cls: str = "") -> Html:
    """A UI icon. Decorative (aria-hidden) unless a label is given."""
    inner = _UI_ICONS.get(name, _UI_ICONS["info"])
    a11y = f' role="img" aria-label="{esc(label)}"' if label else ' aria-hidden="true" focusable="false"'
    return Html(
        f'<svg class="nx-i {esc(cls)}" width="{int(size)}" height="{int(size)}" viewBox="0 0 24 24" fill="none" '
        f'stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"{a11y}>{inner}</svg>'
    )


def platform_glyph(key: str, size: int = 18, svg: str | None = None) -> Html:
    inner = svg or _PLATFORM_GLYPHS.get(key) or _PLATFORM_GLYPHS["nabux"]
    return Html(f'<svg width="{int(size)}" height="{int(size)}" viewBox="0 0 24 24" fill="currentColor" '
                f'aria-hidden="true" focusable="false">{inner}</svg>')


# ---------------------------------------------------------------------------
# The Nabux MCP family. The product switcher reads this list; keep it the one
# place where products and hosts are written down.
# ---------------------------------------------------------------------------
@dataclass(frozen=True)
class Product:
    key: str
    name: str
    host: str
    color: str
    fa: str = ""
    gradient: str = ""

    @property
    def url(self) -> str:
        return f"https://{self.host}/"


PRODUCTS: tuple[Product, ...] = (
    Product("telegram", "Telegram MCP", "tm.nabuxai.com", "#229ED9", "تلگرام"),
    Product("whatsapp", "WhatsApp MCP", "wa.nabuxai.com", "#25D366", "واتس‌اپ"),
    Product("instagram", "Instagram MCP", "ig.nabuxai.com", "#D62976", "اینستاگرام",
            "linear-gradient(45deg,#F58529 0%,#DD2A7B 55%,#8134AF 100%)"),
    Product("facebook", "Facebook MCP", "fb.nabuxai.com", "#1877F2", "فیسبوک"),
    Product("x", "X MCP", "x.nabuxai.com", "#000000", "ایکس"),
    Product("linkedin", "LinkedIn MCP", "li.nabuxai.com", "#0A66C2", "لینکدین"),
    Product("threads", "Threads MCP", "th.nabuxai.com", "#000000", "تردز"),
    Product("youtube", "YouTube MCP", "yt.nabuxai.com", "#FF0000", "یوتیوب"),
    Product("snapchat", "Snapchat MCP", "snap.nabuxai.com", "#FFFC00", "اسنپ‌چت"),
    Product("social", "Social MCP", "social.nabuxai.com", "#5647E6", "سوشال",
            "linear-gradient(135deg,#6a63f4,#7c3aed)"),
)
PRODUCTS_BY_KEY = {p.key: p for p in PRODUCTS}
NABUX_ACCOUNT_URL = "https://auth.nabuxai.com/"
NABUX_HOME_URL = "https://nabuxai.com/"


# ---- colour maths: the platform colour must carry text at WCAG AA ---------
def _rgb(hex_color: str) -> tuple[float, float, float]:
    v = hex_color.strip().lstrip("#")
    if len(v) == 3:
        v = "".join(c * 2 for c in v)
    if len(v) != 6:
        raise ValueError(f"not a #rrggbb colour: {hex_color!r}")
    return tuple(int(v[i:i + 2], 16) / 255 for i in (0, 2, 4))  # type: ignore[return-value]


def _lum(rgb: tuple[float, float, float]) -> float:
    def ch(c: float) -> float:
        return c / 12.92 if c <= 0.03928 else ((c + 0.055) / 1.055) ** 2.4
    r, g, b = (ch(c) for c in rgb)
    return 0.2126 * r + 0.7152 * g + 0.0722 * b


def contrast(a: str, b: str) -> float:
    la, lb = _lum(_rgb(a)), _lum(_rgb(b))
    hi, lo = max(la, lb), min(la, lb)
    return (hi + 0.05) / (lo + 0.05)


def _hex(rgb: Sequence[float]) -> str:
    return "#" + "".join(f"{max(0, min(255, round(c * 255))):02x}" for c in rgb)


def accessible_pair(color: str) -> tuple[str, str]:
    """(background, text) for a button in this colour, at least 4.5:1.

    Light colours keep their hue and take near-black text; others darken until
    white text passes.
    """
    rgb = _rgb(color)
    if _lum(rgb) > 0.4 and contrast(color, "#111111") >= 4.5:  # yellow and other light brand colours
        return _hex(rgb), "#111111"
    for _ in range(40):
        if contrast(_hex(rgb), "#ffffff") >= 4.5:
            break
        rgb = tuple(c * 0.94 for c in rgb)  # type: ignore[assignment]
    return _hex(rgb), "#ffffff"


# ---------------------------------------------------------------------------
# Strings
# ---------------------------------------------------------------------------
STRINGS: dict[str, dict[str, str]] = {
    "en": {
        "nav.overview": "Overview", "nav.accounts": "Accounts", "nav.connect": "Connect to AI",
        "nav.tools": "Tools", "nav.webhooks": "Webhooks & API", "nav.activity": "Activity",
        "nav.settings": "Settings", "nav.section.workspace": "Workspace", "nav.section.developers": "Developers",
        "skip": "Skip to content", "menu.open": "Open menu", "menu.close": "Close menu",
        "switcher.label": "Nabux MCP services", "switcher.title": "Nabux MCP services",
        "switcher.here": "You are here", "switcher.all": "One Nabux account signs you in to all of them.",
        "user.menu": "Account menu", "user.manage": "Manage Nabux account", "user.signout": "Sign out",
        "user.local": "Signed in on this browser", "user.link": "Link to your Nabux account",
        "theme.label": "Theme", "theme.light": "Light", "theme.dark": "Dark", "theme.system": "System",
        "lang.label": "Language", "lang.other": "فارسی", "lang.other.code": "fa",
        "copy": "Copy", "copied": "Copied to clipboard", "copy.failed": "Copy failed — select the text instead",
        "endpoint": "MCP endpoint", "endpoint.hint": "Paste this into any MCP client.",
        "signin.cta": "Continue with Nabux", "signin.eyebrow": "Nabux MCP gateway",
        "signin.secondary": "How it works", "signin.connects": "What it connects",
        "signin.mcp.title": "What is MCP?",
        "signin.mcp.text": "The Model Context Protocol is an open standard that lets AI assistants use tools safely. "
                           "This gateway is an MCP server: you sign in once, choose what it may reach, and your assistant "
                           "can read and act only on what you allowed.",
        "signin.mcp.s1": "Sign in with your Nabux account", "signin.mcp.s2": "Connect the accounts you own",
        "signin.mcp.s3": "Add the endpoint to Claude, ChatGPT or Cursor",
        "signin.trust.official": "Official platform APIs", "signin.trust.encrypted": "Tokens encrypted at rest",
        "signin.trust.mixed": "Official APIs + opt-in web sessions",
        "signin.trust.revoke": "Revoke access any time",
        "signin.privacy": "Nabux never sees your passwords. Access tokens stay encrypted on this server and are used only "
                          "for the actions you or your AI assistant ask for.",
        "signin.disabled.title": "Nabux sign-in is not set up on this server",
        "signin.disabled.text": "You can still connect directly; your connection is kept on this browser.",
        "signin.one": "One account for every Nabux MCP", "signin.ai": "Your AI assistant",
        "footer.privacy": "Privacy", "footer.terms": "Terms", "footer.account": "Nabux account",
        "footer.all": "All Nabux services", "footer.by": "An MCP gateway by Nabux",
        "auth.expired.title": "This sign-in link has expired",
        "auth.expired.text": "Sign-in links are good for ten minutes and work once. Start again and it will go through.",
        "auth.cancelled.title": "Sign-in was cancelled", "auth.failed.title": "We could not sign you in",
        "auth.retry": "Try again", "auth.home": "Back to start",
        "link.banner.title": "Link this browser to your Nabux account",
        "link.banner.text": "Your connected accounts will follow you to every device and AI client you sign in to.",
        "status.connected": "Connected", "status.disconnected": "Disconnected", "status.expired": "Expired",
        "status.active": "Active", "status.off": "Not set",
        "danger.title": "Danger zone", "confirm": "Are you sure?",
        "tools.search": "Filter tools", "tools.count": "{n} tools", "tools.none": "No tools match this filter.",
        "tools.args": "Arguments", "tools.noargs": "No arguments", "tools.required": "required",
        "ai.claude": "Claude", "ai.chatgpt": "ChatGPT", "ai.cursor": "Cursor", "ai.claude_code": "Claude Code",
        "ai.token": "Personal token",
        "ai.claude.s1": "Open claude.ai → Settings → Connectors.",
        "ai.claude.s2": "Choose “Add custom connector” and paste the endpoint below.",
        "ai.claude.s3": "Press Connect and sign in with Nabux when asked.",
        "ai.chatgpt.s1": "Open ChatGPT → Settings → Apps & Connectors → Advanced, and turn on Developer mode.",
        "ai.chatgpt.s2": "Back in Apps & Connectors choose Create, name it and paste the endpoint below.",
        "ai.chatgpt.s3": "Pick OAuth as the authentication and sign in with Nabux.",
        "ai.cursor.s1": "Open Cursor → Settings → MCP → Add new global MCP server.",
        "ai.cursor.s2": "Add this entry to mcp.json and save. Cursor opens the sign-in page the first time.",
        "ai.claude_code.s1": "Run this in a terminal, then type /mcp in Claude Code to sign in.",
        "ai.token.text": "For clients without OAuth: a personal token works as a Bearer header or inside the URL. "
                         "It does not expire, so treat it like a password.",
        "ai.token.create": "Create personal token",
        "ai.token.once": "Copy it now: this token is shown only once.",
        "ai.token.url": "URL with the token inside", "ai.token.header": "Authorization header",
        "error.title": "Something went wrong",
    },
    "fa": {
        "nav.overview": "نمای کلی", "nav.accounts": "حساب‌ها", "nav.connect": "اتصال به هوش مصنوعی",
        "nav.tools": "ابزارها", "nav.webhooks": "وب‌هوک و API", "nav.activity": "فعالیت",
        "nav.settings": "تنظیمات", "nav.section.workspace": "فضای کار", "nav.section.developers": "توسعه‌دهندگان",
        "skip": "رفتن به محتوا", "menu.open": "باز کردن منو", "menu.close": "بستن منو",
        "switcher.label": "سرویس‌های MCP نابکس", "switcher.title": "سرویس‌های MCP نابکس",
        "switcher.here": "همین‌جا هستید", "switcher.all": "با یک حساب Nabux وارد همهٔ این سرویس‌ها می‌شوید.",
        "user.menu": "منوی حساب", "user.manage": "مدیریت حساب Nabux", "user.signout": "خروج",
        "user.local": "واردشده روی همین مرورگر", "user.link": "اتصال به حساب Nabux",
        "theme.label": "پوسته", "theme.light": "روشن", "theme.dark": "تیره", "theme.system": "خودکار",
        "lang.label": "زبان", "lang.other": "English", "lang.other.code": "en",
        "copy": "کپی", "copied": "کپی شد", "copy.failed": "کپی نشد؛ متن را انتخاب و کپی کنید",
        "endpoint": "آدرس MCP", "endpoint.hint": "این آدرس را در هر کلاینت MCP وارد کنید.",
        "signin.cta": "ادامه با Nabux", "signin.eyebrow": "درگاه MCP نابکس",
        "signin.secondary": "چطور کار می‌کند", "signin.connects": "چه چیزهایی را وصل می‌کند",
        "signin.mcp.title": "MCP چیست؟",
        "signin.mcp.text": "پروتکل MCP استانداردی باز است که به دستیارهای هوش مصنوعی اجازه می‌دهد با خیال راحت از ابزارها استفاده کنند. "
                           "این درگاه یک سرور MCP است: یک بار وارد می‌شوید، مشخص می‌کنید به چه چیزی دسترسی داشته باشد، "
                           "و دستیار شما فقط روی همان‌ها کار می‌کند.",
        "signin.mcp.s1": "با حساب Nabux وارد شوید", "signin.mcp.s2": "حساب‌هایی را که مال شماست وصل کنید",
        "signin.mcp.s3": "آدرس را به Claude، ChatGPT یا Cursor بدهید",
        "signin.trust.official": "API رسمی پلتفرم", "signin.trust.encrypted": "توکن‌ها رمزنگاری‌شده",
        "signin.trust.mixed": "API رسمی + نشست وب اختیاری",
        "signin.trust.revoke": "قطع دسترسی در هر زمان",
        "signin.privacy": "Nabux هیچ‌وقت رمز عبور شما را نمی‌بیند. توکن‌های دسترسی رمزنگاری‌شده روی همین سرور می‌مانند "
                          "و فقط برای کارهایی به کار می‌روند که شما یا دستیارتان درخواست می‌کنید.",
        "signin.disabled.title": "ورود با Nabux روی این سرور هنوز راه‌اندازی نشده است",
        "signin.disabled.text": "همچنان می‌توانید مستقیم وصل شوید؛ اتصال روی همین مرورگر نگه داشته می‌شود.",
        "signin.one": "یک حساب برای همهٔ MCPهای Nabux", "signin.ai": "دستیار هوش مصنوعی شما",
        "footer.privacy": "حریم خصوصی", "footer.terms": "شرایط استفاده", "footer.account": "حساب Nabux",
        "footer.all": "همهٔ سرویس‌های Nabux", "footer.by": "درگاه MCP از Nabux",
        "auth.expired.title": "این لینک ورود منقضی شده است",
        "auth.expired.text": "لینک ورود ده دقیقه اعتبار دارد و فقط یک بار کار می‌کند. دوباره شروع کنید.",
        "auth.cancelled.title": "ورود لغو شد", "auth.failed.title": "ورود انجام نشد",
        "auth.retry": "تلاش دوباره", "auth.home": "بازگشت به صفحهٔ اول",
        "link.banner.title": "این مرورگر را به حساب Nabux خود وصل کنید",
        "link.banner.text": "حساب‌های وصل‌شده در هر دستگاه و هر کلاینت هوش مصنوعی که با آن وارد شوید همراه شما خواهند بود.",
        "status.connected": "وصل", "status.disconnected": "قطع", "status.expired": "منقضی",
        "status.active": "فعال", "status.off": "تنظیم نشده",
        "danger.title": "منطقهٔ خطر", "confirm": "مطمئن هستید؟",
        "tools.search": "جست‌وجوی ابزار", "tools.count": "{n} ابزار", "tools.none": "ابزاری با این عبارت پیدا نشد.",
        "tools.args": "ورودی‌ها", "tools.noargs": "بدون ورودی", "tools.required": "الزامی",
        "ai.claude": "Claude", "ai.chatgpt": "ChatGPT", "ai.cursor": "Cursor", "ai.claude_code": "Claude Code",
        "ai.token": "توکن شخصی",
        "ai.claude.s1": "در claude.ai به Settings ← Connectors بروید.",
        "ai.claude.s2": "گزینهٔ «Add custom connector» را بزنید و آدرس زیر را وارد کنید.",
        "ai.claude.s3": "Connect را بزنید و در صورت درخواست با Nabux وارد شوید.",
        "ai.chatgpt.s1": "در ChatGPT به Settings ← Apps & Connectors ← Advanced بروید و Developer mode را روشن کنید.",
        "ai.chatgpt.s2": "در Apps & Connectors گزینهٔ Create را بزنید، نامی بدهید و آدرس زیر را وارد کنید.",
        "ai.chatgpt.s3": "نوع احراز هویت را OAuth بگذارید و با Nabux وارد شوید.",
        "ai.cursor.s1": "در Cursor به Settings ← MCP ← Add new global MCP server بروید.",
        "ai.cursor.s2": "این بخش را به mcp.json اضافه و ذخیره کنید. بار اول صفحهٔ ورود باز می‌شود.",
        "ai.claude_code.s1": "این دستور را در ترمینال اجرا کنید و سپس در Claude Code عبارت /mcp را بزنید تا وارد شوید.",
        "ai.token.text": "برای کلاینت‌هایی که OAuth ندارند: توکن شخصی را به‌صورت هدر Bearer یا داخل آدرس بدهید. "
                         "این توکن منقضی نمی‌شود؛ مثل رمز عبور از آن محافظت کنید.",
        "ai.token.create": "ساخت توکن شخصی",
        "ai.token.once": "همین حالا کپی کنید: این توکن فقط یک بار نمایش داده می‌شود.",
        "ai.token.url": "آدرس همراه با توکن", "ai.token.header": "هدر Authorization",
        "error.title": "مشکلی پیش آمد",
    },
}


def pick_lang(request: Optional[Request]) -> str:
    """?lang= wins, then the nx_lang cookie, then Accept-Language, then fa."""
    if request is None:
        return "fa"
    q = (request.query_params.get("lang") or "").strip().lower()
    if q in LANGS:
        return q
    c = (request.cookies.get(LANG_COOKIE) or "").strip().lower()
    if c in LANGS:
        return c
    best, best_q = None, -1.0
    for i, part in enumerate((request.headers.get("accept-language") or "").split(",")):
        bits = part.strip().split(";")
        tag = bits[0].strip().lower().split("-")[0]
        weight = 1.0
        for b in bits[1:]:
            b = b.strip()
            if b.startswith("q="):
                try:
                    weight = float(b[2:])
                except ValueError:
                    weight = 0.0
        if tag in LANGS and weight > 0 and weight - i * 1e-6 > best_q:
            best, best_q = tag, weight - i * 1e-6
    return best or "fa"


_FA_DIGITS = str.maketrans("0123456789", "۰۱۲۳۴۵۶۷۸۹")


def digits(value: Any, lang: str) -> str:
    """Numbers in the reader's digits: Persian digits on fa pages."""
    text = str(value)
    return text.translate(_FA_DIGITS) if lang == "fa" else text


def direction(lang: str) -> str:
    return "rtl" if lang in RTL_LANGS else "ltr"


def pick_theme(request: Optional[Request]) -> str:
    v = (request.cookies.get(THEME_COOKIE) if request is not None else "") or ""
    return v if v in ("light", "dark") else "system"


# ---------------------------------------------------------------------------
# Config
# ---------------------------------------------------------------------------
@dataclass
class NavItem:
    """One sidebar link. ``label`` is a string-table key or {"fa": ..., "en": ...}."""
    key: str
    href: str
    label: Union[str, Mapping[str, str]]
    icon: str = "overview"
    section: str = "workspace"
    badge: Optional[str] = None


def standard_nav(*, overview: str = "/dashboard", accounts: Optional[str] = "/dashboard/accounts",
                 accounts_label: Union[str, Mapping[str, str], None] = None,
                 connect: Optional[str] = "/dashboard/connect", tools: Optional[str] = "/dashboard/tools",
                 webhooks: Optional[str] = None, activity: Optional[str] = None,
                 settings: Optional[str] = "/dashboard/settings") -> list[NavItem]:
    """The standard sections in the standard order. Pass None to leave one out."""
    items = [NavItem("overview", overview, "nav.overview", "overview")]
    if accounts:
        items.append(NavItem("accounts", accounts, accounts_label or "nav.accounts", "accounts"))
    if connect:
        items.append(NavItem("connect", connect, "nav.connect", "connect"))
    if tools:
        items.append(NavItem("tools", tools, "nav.tools", "tools"))
    if activity:
        items.append(NavItem("activity", activity, "nav.activity", "activity"))
    if webhooks:
        items.append(NavItem("webhooks", webhooks, "nav.webhooks", "webhooks", section="developers"))
    if settings:
        items.append(NavItem("settings", settings, "nav.settings", "settings", section="developers"))
    return items


@dataclass
class NabuAuthSettings:
    client_id: str = ""
    client_secret: str = ""
    redirect_uri: str = ""
    url: str = "https://auth.nabuxai.com"
    scopes: str = "openid profile email"

    @property
    def enabled(self) -> bool:
        return bool(self.client_id and self.client_secret)

    @classmethod
    def from_env(cls, public_base_url: str, environ: Optional[Mapping[str, str]] = None) -> "NabuAuthSettings":
        env = os.environ if environ is None else environ
        base = public_base_url.rstrip("/")
        return cls(
            client_id=(env.get("NABUAUTH_CLIENT_ID") or "").strip(),
            client_secret=(env.get("NABUAUTH_CLIENT_SECRET") or "").strip(),
            redirect_uri=(env.get("NABUAUTH_REDIRECT_URI") or "").strip() or f"{base}/auth/nabu/callback",
            url=((env.get("NABUAUTH_URL") or "").strip().rstrip("/") or "https://auth.nabuxai.com"),
            scopes=(env.get("NABUAUTH_SCOPES") or "").strip() or "openid profile email",
        )


@dataclass
class ConsoleUser:
    """The person the page is drawn for. ``nabu`` is False for a browser-only owner."""
    id: Any
    name: str = ""
    email: str = ""
    avatar: str = ""
    nabu: bool = False

    @property
    def display(self) -> str:
        return self.name or self.email or ""

    @property
    def initials(self) -> str:
        src = (self.name or self.email.split("@")[0] or "").strip()
        words = [w for w in src.replace(".", " ").replace("_", " ").split() if w]
        if len(words) >= 2:
            return (words[0][0] + words[1][0]).upper()
        return src[:2].upper()


@dataclass
class Feature:
    """One line of the sign-in page's "what it connects" list."""
    title: Union[str, Mapping[str, str]]
    text: Union[str, Mapping[str, str]]
    icon: str = "check"


LoginHook = Callable[[Request, dict, Response], Union[None, Response, Awaitable[Union[None, Response]]]]
UserLoader = Callable[[Request], Optional[ConsoleUser]]


@dataclass
class Console:
    """Everything the shell needs to know about one gateway."""
    product: str
    platform: str
    base_url: str
    secret_key: str
    session_cookie: str
    user_loader: UserLoader
    nav: list[NavItem] = field(default_factory=standard_nav)
    tagline: Mapping[str, str] = field(default_factory=dict)
    headline: Mapping[str, str] = field(default_factory=dict)
    features: Sequence[Feature] = ()
    nabuauth: NabuAuthSettings = field(default_factory=NabuAuthSettings)
    brand_color: Optional[str] = None
    brand_gradient: Optional[str] = None
    icon_svg: Optional[str] = None
    strings: Mapping[str, Mapping[str, str]] = field(default_factory=dict)
    home_path: str = "/dashboard"
    signin_path: str = "/"
    fallback_connect: Optional[tuple[Union[str, Mapping[str, str]], str]] = None
    privacy_url: str = "https://nabuxai.com/privacy"
    terms_url: str = "https://nabuxai.com/terms"
    http_transport: Any = None
    http_timeout: float = 30.0
    # The sign-in page's first trust line. "signin.trust.official" fits a gateway
    # that only uses the platform's official API; one with unofficial paths (web
    # sessions, QR-linked devices, user sessions) says so: "signin.trust.mixed" or
    # its own {"fa": ..., "en": ...}.
    trust_line: Union[str, Mapping[str, str]] = "signin.trust.official"
    # The privacy sentence under the sign-in page. Override it when the gateway
    # does take a password (a web-session or two-step login form).
    privacy: Union[str, Mapping[str, str]] = "signin.privacy"

    def __post_init__(self) -> None:
        self.base_url = self.base_url.rstrip("/")
        prod = PRODUCTS_BY_KEY.get(self.platform)
        self.brand_color = self.brand_color or (prod.color if prod else "#5647E6")
        self.brand_gradient = self.brand_gradient or (prod.gradient if prod else "")
        if len(self.secret_key or "") < 16:
            raise ValueError("Console.secret_key must be the app's long random secret")

    # ---- derived -------------------------------------------------------
    @property
    def host(self) -> str:
        return self.base_url.split("://", 1)[-1].split("/", 1)[0]

    @property
    def secure(self) -> bool:
        return self.base_url.startswith("https://")

    @property
    def mcp_url(self) -> str:
        return f"{self.base_url}/mcp"

    def t(self, lang: str, key: str, **fmt: Any) -> str:
        """App strings first, then the kit's; English, then the key itself."""
        for table in (self.strings.get(lang), STRINGS.get(lang), self.strings.get("en"), STRINGS.get("en")):
            if table and key in table:
                text = table[key]
                if fmt and lang == "fa":
                    fmt = {k: digits(v, lang) if isinstance(v, (int, float)) else v for k, v in fmt.items()}
                return text.format(**fmt) if fmt else text
        return key.format(**fmt) if fmt else key

    def text(self, lang: str, value: Union[str, Mapping[str, str], None]) -> str:
        """A {"fa","en"} pair, a string-table key, or a literal."""
        if value is None:
            return ""
        if isinstance(value, Mapping):
            return str(value.get(lang) or value.get("en") or next(iter(value.values()), ""))
        return self.t(lang, value)

    def ctx(self, request: Request, user: Optional[ConsoleUser] = None) -> "Ctx":
        return Ctx(self, request, user if user is not None else self.current_user(request))

    # ---- session ---------------------------------------------------------
    def current_user(self, request: Request) -> Optional[ConsoleUser]:
        cached = getattr(request.state, "nx_user", _MISSING)
        if cached is not _MISSING:
            return cached
        try:
            user = self.user_loader(request)
        except Exception:  # a broken loader must not take the page down
            user = None
        request.state.nx_user = user
        return user

    def csrf(self, request: Request) -> str:
        """Bound to the app's session cookie, so it changes with the session."""
        raw = request.cookies.get(self.session_cookie, "")
        return hmac.new(self._key("csrf"), raw.encode(), hashlib.sha256).hexdigest()[:32]

    def csrf_ok(self, request: Request, value: Optional[str]) -> bool:
        if not request.cookies.get(self.session_cookie) or not value:
            return False
        return hmac.compare_digest(self.csrf(request), str(value))

    def login_url(self, next_path: str = "") -> str:
        return "/auth/nabu/login?next=" + quote(safe_next(next_path or self.home_path, self.home_path), safe="")

    # ---- signing ---------------------------------------------------------
    def _key(self, purpose: str) -> bytes:
        return hashlib.sha256(f"nabux-console:{purpose}:{self.secret_key}".encode()).digest()

    def sign(self, payload: dict, purpose: str = "flow") -> str:
        raw = json.dumps(payload, separators=(",", ":"), sort_keys=True).encode()
        body = base64.urlsafe_b64encode(raw).decode().rstrip("=")
        sig = hmac.new(self._key(purpose), body.encode(), hashlib.sha256).hexdigest()
        return f"v1.{body}.{sig}"

    def unsign(self, value: Optional[str], purpose: str = "flow") -> Optional[dict]:
        if not value or value.count(".") != 2:
            return None
        version, body, sig = value.split(".", 2)
        if version != "v1" or not body or not sig:
            return None
        good = hmac.new(self._key(purpose), body.encode(), hashlib.sha256).hexdigest()
        if not hmac.compare_digest(good, sig):
            return None
        try:
            payload = json.loads(base64.urlsafe_b64decode(body + "=" * (-len(body) % 4)))
        except (ValueError, json.JSONDecodeError):
            return None
        return payload if isinstance(payload, dict) else None

    # ---- pages -----------------------------------------------------------
    def render_page(self, request: Request, title: str, body_html: Any, active: Optional[str] = None,
                    user: Optional[ConsoleUser] = None, *, subtitle: Any = None, actions: Any = None,
                    status: int = 200, toast: Optional[str] = None, headers: Optional[dict] = None,
                    banner: Any = None) -> HTMLResponse:
        """The full signed-in shell around ``body_html``."""
        c = self.ctx(request, user)
        return _shell_response(c, title, _app_shell(c, title, body_html, active, subtitle, actions, toast, banner),
                               status=status, headers=headers)

    def render_signin(self, request: Request, *, notice: Any = None, status: int = 200,
                      next_path: Optional[str] = None) -> HTMLResponse:
        """The signed-out landing page."""
        c = self.ctx(request, None)
        title = self.text(c.lang, self.headline) or self.product
        return _shell_response(c, self.product, _signin_page(c, notice, next_path), status=status, title_override=title)

    def render_message(self, request: Request, title: str, message: Any = "", *, tone: str = "danger",
                       action: Optional[tuple[str, str]] = None, status: int = 200) -> HTMLResponse:
        """A small standalone page for errors and confirmations (expired links, failures)."""
        c = self.ctx(request)
        body = _message_page(c, title, message, tone, action)
        return _shell_response(c, title, body, status=status)

    # ---- routes ----------------------------------------------------------
    def mount(self, target: Any, *, on_login: LoginHook,
              on_logout: Optional[Callable[[Request, Response], None]] = None) -> None:
        """Add /static/console, /console/prefs and the NabuAuth routes to the app.

        ``target`` is a FastMCP server (``custom_route``) or a Starlette app.
        ``on_login(request, profile, response)`` maps the NabuAuth profile to
        the app's owner and sets the app's session cookie on ``response`` (a
        redirect to where the person was going). It may return another
        Response instead, or raise ValueError with a message to show.
        """
        route = registrar(target)
        console = self

        @route(STATIC_PREFIX + "/{path:path}", ["GET"])
        async def nx_static(request: Request):
            return serve_static(request.path_params.get("path", ""), request.query_params.get("v"))

        @route("/console/prefs", ["GET"])
        async def nx_prefs(request: Request):
            resp = RedirectResponse(safe_next(request.query_params.get("next"), console.signin_path), status_code=303)
            lang = (request.query_params.get("lang") or "").lower()
            theme = (request.query_params.get("theme") or "").lower()
            if lang in LANGS:
                resp.set_cookie(LANG_COOKIE, lang, max_age=365 * 86400, path="/", samesite="lax", secure=console.secure)
            if theme in ("light", "dark", "system"):
                resp.set_cookie(THEME_COOKIE, theme, max_age=365 * 86400, path="/", samesite="lax", secure=console.secure)
            return resp

        @route("/auth/nabu/login", ["GET"])
        async def nx_login(request: Request):
            if not console.nabuauth.enabled:
                return RedirectResponse(console.signin_path, status_code=302)
            silent = request.query_params.get("prompt") == "none"
            state, verifier, challenge = pkce()
            flow = console.sign({
                "purpose": "nabu", "state": state, "verifier": verifier, "silent": silent,
                "exp": int(time.time()) + FLOW_TTL,
                "next": safe_next(request.query_params.get("next"), console.home_path),
            })
            resp = RedirectResponse(console.authorize_url(state, challenge, prompt="none" if silent else None),
                                    status_code=302)
            resp.set_cookie(FLOW_COOKIE, flow, max_age=FLOW_TTL, httponly=True, path="/",
                            secure=console.secure, samesite="lax")
            if silent:
                console._set_guard(resp)
            return resp

        @route("/auth/nabu/callback", ["GET"])
        async def nx_callback(request: Request):
            if not console.nabuauth.enabled:
                return RedirectResponse(console.signin_path, status_code=302)
            lang = pick_lang(request)
            flow = console.unsign(request.cookies.get(FLOW_COOKIE))
            error = (request.query_params.get("error") or "").strip()
            if error:
                if error in SILENT_ERRORS or (flow and flow.get("silent")):
                    # The silent attempt found no NabuAuth session: show the sign-in page, once.
                    resp = RedirectResponse(_with_query(console.signin_path, "sso=0"), status_code=303)
                    console._set_guard(resp)
                    resp.delete_cookie(FLOW_COOKIE, path="/")
                    return resp
                resp = console.render_message(request, console.t(lang, "auth.cancelled.title"), error,
                                              action=(console.t(lang, "auth.retry"), "/auth/nabu/login"))
                resp.delete_cookie(FLOW_COOKIE, path="/")
                return resp
            state = request.query_params.get("state") or ""
            code = (request.query_params.get("code") or "").strip()
            expected = str((flow or {}).get("state") or "")
            state_ok = bool(expected) and len(expected) == len(state) and hmac.compare_digest(expected, state)
            if not flow or flow.get("purpose") != "nabu" or not state_ok \
                    or int(flow.get("exp") or 0) < time.time() or not code:
                return console.render_message(request, console.t(lang, "auth.expired.title"),
                                              console.t(lang, "auth.expired.text"), tone="warning",
                                              action=(console.t(lang, "auth.retry"), "/auth/nabu/login"))
            target = safe_next(str(flow.get("next") or ""), console.home_path)
            resp: Response = RedirectResponse(target, status_code=303)
            try:
                profile = await console.exchange(code, str(flow.get("verifier") or ""))
                result = on_login(request, profile, resp)
                if hasattr(result, "__await__"):
                    result = await result  # type: ignore[assignment]
                if isinstance(result, Response):
                    resp = result
            except (NabuAuthError, ValueError) as exc:
                failed = console.render_message(request, console.t(lang, "auth.failed.title"), str(exc),
                                                action=(console.t(lang, "auth.retry"), "/auth/nabu/login"))
                failed.delete_cookie(FLOW_COOKIE, path="/")
                return failed
            resp.delete_cookie(FLOW_COOKIE, path="/")
            resp.delete_cookie(SSO_GUARD_COOKIE, path="/")
            return resp

        @route("/auth/logout", ["POST"])
        async def nx_logout(request: Request):
            form = await request.form()
            if not console.csrf_ok(request, form.get("csrf")):
                return RedirectResponse(console.home_path, status_code=303)
            dest = (console.end_session_url(f"{console.base_url}{console.signin_path}")
                    if console.nabuauth.enabled else console.signin_path)
            resp = RedirectResponse(dest, status_code=303)
            resp.delete_cookie(console.session_cookie, path="/")
            resp.delete_cookie(FLOW_COOKIE, path="/")
            console._set_guard(resp)  # no silent sign-in straight back in
            if on_logout:
                on_logout(request, resp)
            return resp

    def _set_guard(self, resp: Response) -> None:
        resp.set_cookie(SSO_GUARD_COOKIE, "1", max_age=SSO_GUARD_TTL, httponly=True, path="/",
                        secure=self.secure, samesite="lax")

    # ---- silent SSO ------------------------------------------------------
    def silent_sso(self, request: Request) -> Optional[Response]:
        """On a signed-out browser visit, try NabuAuth with prompt=none once.

        Returns the redirect to take, or None to render the page. It never
        fires twice: a guard cookie is set on the way out, ``?sso=0`` comes
        back on the way in, and non-browser requests (health checks, curl)
        are left alone.
        """
        if not self.nabuauth.enabled or request.method != "GET":
            return None
        if request.cookies.get(SSO_GUARD_COOKIE) or request.query_params.get("sso") == "0":
            return None
        if "text/html" not in (request.headers.get("accept") or ""):
            return None
        if self.current_user(request) is not None:
            return None
        nxt = request.query_params.get("next") or self.home_path
        resp = RedirectResponse("/auth/nabu/login?prompt=none&next=" + quote(safe_next(nxt, self.home_path), safe=""),
                                status_code=302)
        self._set_guard(resp)
        return resp

    # ---- NabuAuth client -------------------------------------------------
    def authorize_url(self, state: str, challenge: str, prompt: Optional[str] = None) -> str:
        params = {
            "client_id": self.nabuauth.client_id, "redirect_uri": self.nabuauth.redirect_uri,
            "response_type": "code", "scope": self.nabuauth.scopes, "state": state,
            "code_challenge": challenge, "code_challenge_method": "S256",
        }
        if prompt:
            params["prompt"] = prompt
        return f"{self.nabuauth.url}/oauth/authorize?{urlencode(params)}"

    def end_session_url(self, return_to: str) -> str:
        """NabuAuth's end-session endpoint; it returns to ``return_to`` (same origin as a redirect URI)."""
        return f"{self.nabuauth.url}/oauth/logout?{urlencode({'post_logout_redirect_uri': return_to})}"

    def _client(self) -> httpx.AsyncClient:
        kwargs: dict[str, Any] = {"timeout": self.http_timeout}
        if self.http_transport is not None:
            kwargs["transport"] = self.http_transport
        return httpx.AsyncClient(**kwargs)

    async def exchange(self, code: str, verifier: str) -> dict:
        """Code → token → profile. Errors never carry the code, verifier or token."""
        auth = self.nabuauth
        if not auth.enabled:
            raise NabuAuthError("NabuAuth is not configured")
        form = {"grant_type": "authorization_code", "client_id": auth.client_id, "client_secret": auth.client_secret,
                "redirect_uri": auth.redirect_uri, "code": code, "code_verifier": verifier}
        try:
            async with self._client() as client:
                tr = await client.post(f"{auth.url}/oauth/token", data=form,
                                       headers={"Content-Type": "application/x-www-form-urlencoded"})
        except httpx.HTTPError:
            raise NabuAuthError("NabuAuth could not be reached") from None
        try:
            body = tr.json()
        except ValueError:
            body = {}
        access = str((body if isinstance(body, dict) else {}).get("access_token") or "")
        if tr.status_code >= 400 or not access:
            raise NabuAuthError(f"NabuAuth token exchange failed (HTTP {tr.status_code})")
        try:
            async with self._client() as client:
                pr = await client.get(f"{auth.url}/api/v1/user", headers={"Authorization": f"Bearer {access}"})
        except httpx.HTTPError:
            raise NabuAuthError("NabuAuth profile could not be read") from None
        try:
            profile = pr.json()
        except ValueError:
            profile = {}
        if pr.status_code >= 400 or not isinstance(profile, dict):
            raise NabuAuthError(f"NabuAuth profile failed (HTTP {pr.status_code})")
        subject = str(profile.get("sub") or "").strip()
        if not subject:
            raise NabuAuthError("NabuAuth did not return an account id")
        email = str(profile.get("email") or "").strip()
        picture = str(profile.get("picture") or profile.get("avatar_url") or "")
        return {
            "sub": subject,
            "email": email,
            "email_verified": profile.get("email_verified") is True,
            "name": str(profile.get("name") or profile.get("preferred_username") or ""),
            "picture": picture if picture.startswith("https://") and len(picture) <= 500 else "",
        }

    # ---- decorators ------------------------------------------------------
    def require_login(self, handler: Callable[[Request], Awaitable[Response]]) -> Callable[[Request], Awaitable[Response]]:
        """Run the handler only for a signed-in person; ``request.state.user`` is set.

        Signed out: to NabuAuth when it is configured, else to the sign-in page.
        """
        @functools.wraps(handler)
        async def wrapper(request: Request) -> Response:
            user = self.current_user(request)
            if user is None:
                nxt = request.url.path + (f"?{request.url.query}" if request.url.query else "")
                if self.nabuauth.enabled:
                    return RedirectResponse(self.login_url(nxt), status_code=302)
                return RedirectResponse(self.signin_path, status_code=302)
            request.state.user = user
            return await handler(request)
        return wrapper


_MISSING = object()


class NabuAuthError(RuntimeError):
    pass


def pkce() -> tuple[str, str, str]:
    """(state, verifier, S256 challenge)."""
    verifier = secrets.token_urlsafe(48)
    challenge = base64.urlsafe_b64encode(hashlib.sha256(verifier.encode()).digest()).decode().rstrip("=")
    return secrets.token_urlsafe(24), verifier, challenge


def verified_email(profile: Mapping[str, Any]) -> str:
    """The profile's email, lower-cased, only when NabuAuth says it is verified."""
    email = str(profile.get("email") or "").strip().lower()
    return email if email and profile.get("email_verified") is True and "@" in email else ""


def safe_next(value: Optional[str], default: str = "/dashboard") -> str:
    """A path on this site to go to after sign-in; anything else becomes ``default``."""
    nxt = str(value or "")
    if not nxt.startswith("/") or nxt.startswith("//") or "\\" in nxt or len(nxt) > 500 \
            or any(ord(ch) < 32 for ch in nxt):
        return default
    return nxt


def _with_query(path: str, query: str) -> str:
    return path + ("&" if "?" in path else "?") + query


def registrar(target: Any) -> Callable[[str, list[str]], Callable]:
    """A ``route(path, methods)`` decorator factory for FastMCP or Starlette."""
    if hasattr(target, "custom_route"):
        return lambda path, methods: target.custom_route(path, methods=methods)
    routes = getattr(getattr(target, "router", None), "routes", None)
    if routes is not None:
        from starlette.routing import Route

        def factory(path: str, methods: list[str]) -> Callable:
            def deco(fn: Callable) -> Callable:
                routes.append(Route(path, fn, methods=methods))
                return fn
            return deco
        return factory
    if callable(target):
        return target
    raise TypeError("mount() needs a FastMCP server, a Starlette app, or a route(path, methods) factory")


# ---------------------------------------------------------------------------
# Static files (CSS, JS, fonts) — served from the vendored folder, nothing else.
# ---------------------------------------------------------------------------
_TYPES = {".css": "text/css; charset=utf-8", ".js": "text/javascript; charset=utf-8", ".woff2": "font/woff2",
          ".svg": "image/svg+xml", ".txt": "text/plain; charset=utf-8"}


def _static_index() -> dict[str, Path]:
    out: dict[str, Path] = {}
    if STATIC_DIR.is_dir():
        for p in STATIC_DIR.rglob("*"):
            if p.is_file() and p.suffix in _TYPES:
                out[p.relative_to(STATIC_DIR).as_posix()] = p
    return out


_STATIC = _static_index()


def _asset_version() -> str:
    digest = hashlib.sha256(KIT_VERSION.encode())
    for name in sorted(_STATIC):
        digest.update(name.encode())
        digest.update(_STATIC[name].read_bytes())
    return digest.hexdigest()[:10]


ASSET_VERSION = _asset_version()


def asset(path: str) -> str:
    """URL of a kit file with the cache-busting version."""
    return f"{STATIC_PREFIX}/{path}?v={ASSET_VERSION}"


def serve_static(path: str, version: Optional[str] = None) -> Response:
    file = _STATIC.get(path)
    if file is None:
        return Response("not found", status_code=404, media_type="text/plain")
    # Fonts never change under the same name; CSS/JS are pinned by ?v=ASSET_VERSION.
    long = version == ASSET_VERSION or file.suffix == ".woff2"
    cache = "public, max-age=31536000, immutable" if long else "public, max-age=300"
    return Response(file.read_bytes(), media_type=_TYPES[file.suffix],
                    headers={"Cache-Control": cache, "X-Content-Type-Options": "nosniff"})


# ---------------------------------------------------------------------------
# Owner discovery for Social MCP
# ---------------------------------------------------------------------------
def iso_time(value: Any) -> Optional[str]:
    """Unix seconds/milliseconds, a datetime or an ISO string → ISO 8601 UTC."""
    if value in (None, ""):
        return None
    if isinstance(value, datetime):
        dt = value if value.tzinfo else value.replace(tzinfo=timezone.utc)
        return dt.astimezone(timezone.utc).isoformat().replace("+00:00", "Z")
    if isinstance(value, (int, float)) or (isinstance(value, str) and value.strip().isdigit()):
        n = float(value)
        if n > 1e12:
            n /= 1000.0
        return datetime.fromtimestamp(n, tz=timezone.utc).isoformat().replace("+00:00", "Z")
    return str(value)


ACCOUNT_MODES = ("official", "web")
_STANDARD_ACCOUNT_FIELDS = ("account_key", "label", "kind", "status", "connected_at", "mode")
_EXTRA_FIELD_MAX = 200


def _extra_field_name(name: Any) -> bool:
    return isinstance(name, str) and 0 < len(name) <= 40 and name[0].isalpha() and name.isascii() \
        and all(ch.isalnum() or ch == "_" for ch in name) and name == name.lower()


def owner_accounts_payload(provider: str, accounts: Iterable[Mapping[str, Any]]) -> dict:
    """The JSON shape every gateway answers with, de-duplicated by account_key.

    Each account has account_key, label, kind, status and connected_at.
    ``mode`` ("official" for the platform's API, "web" for an unofficial web /
    QR / user session) is kept when the lookup gives one of those two values.
    Any other snake_case field with a plain value (str, int, float, bool, None),
    such as Instagram's ``"session": "browser"``, is passed through as it is,
    strings cut to 200 characters. Lists, dicts and other objects are dropped.
    """
    seen: set[str] = set()
    out = []
    for a in accounts:
        key = str(a.get("account_key") or "").strip()
        if not key or key in seen:
            continue
        seen.add(key)
        item: dict[str, Any] = {
            "account_key": key,
            "label": str(a.get("label") or key),
            "kind": str(a.get("kind") or "account"),
            "status": str(a.get("status") or "connected"),
            "connected_at": iso_time(a.get("connected_at")),
        }
        if a.get("mode") in ACCOUNT_MODES:
            item["mode"] = str(a["mode"])
        for name, value in a.items():
            if name in _STANDARD_ACCOUNT_FIELDS or not _extra_field_name(name):
                continue
            if isinstance(value, str):
                item[name] = value[:_EXTRA_FIELD_MAX]
            elif value is None or isinstance(value, (bool, int, float)):
                item[name] = value
        out.append(item)
    return {"ok": True, "provider": provider, "accounts": out}


def register_owner_accounts_route(target: Any, *, provider: str, token: Union[str, Callable[[], str]],
                                  lookup: Callable[[str, str], Iterable[Mapping[str, Any]]],
                                  path: str = "/internal/v1/owners/accounts") -> None:
    """``GET /internal/v1/owners/accounts?sub=&email=`` for trusted Nabux services.

    ``lookup(sub, email)`` returns the accounts owned by that NabuAuth subject
    or by that (verified, lower-cased) email: dicts with account_key, label,
    kind, status, connected_at, and ``mode`` ("official" | "web") plus any
    extra plain fields (see ``owner_accounts_payload``). The account_key is the
    one the gateway's ``/internal/v1/accounts/{account_key}`` already answers for.
    The sub and email are never logged: the query string is blanked before the
    access log sees it.
    """
    route = registrar(target)

    @route(path, ["GET"])
    async def nx_owner_accounts(request: Request):
        expected = token() if callable(token) else token
        if not expected:
            return JSONResponse({"ok": False, "detail": "not_found"}, status_code=404)
        given = request.headers.get("authorization") or ""
        if not hmac.compare_digest(given.encode(), f"Bearer {expected}".encode()):
            return JSONResponse({"ok": False, "detail": "unauthorized"}, status_code=401)
        sub = (request.query_params.get("sub") or "").strip()[:200]
        email = (request.query_params.get("email") or "").strip().lower()[:320]
        if email and ("@" not in email or any(ch.isspace() for ch in email)):
            email = ""
        # The access log prints the query string once the response starts: keep the sub and email out of it.
        request.scope["query_string"] = b"redacted"
        if not sub and not email:
            return JSONResponse({"ok": False, "detail": "sub_or_email_required"}, status_code=400)
        return JSONResponse(owner_accounts_payload(provider, lookup(sub, email)))


# ---------------------------------------------------------------------------
# Per-request context
# ---------------------------------------------------------------------------
class Ctx:
    """The console, the request, the language and the user, for building a page."""

    def __init__(self, console: Console, request: Request, user: Optional[ConsoleUser]):
        self.console = console
        self.request = request
        self.user = user
        self.lang = pick_lang(request)
        self.dir = direction(self.lang)
        self.theme = pick_theme(request)

    def t(self, key: str, **fmt: Any) -> str:
        return self.console.t(self.lang, key, **fmt)

    def text(self, value: Union[str, Mapping[str, str], None]) -> str:
        return self.console.text(self.lang, value)

    def pick(self, fa: str, en: str) -> str:
        return fa if self.lang == "fa" else en

    def num(self, value: Any) -> str:
        return digits(value, self.lang)

    @property
    def sep(self) -> str:
        """" · " in English, "، " in Persian (see ``sep_join``)."""
        return sep(self.lang)

    def sep_join(self, *parts: Any) -> Html:
        return sep_join(self.lang, *parts)

    @property
    def csrf(self) -> str:
        return self.console.csrf(self.request)

    def csrf_field(self) -> Html:
        return Html(f'<input type="hidden" name="csrf" value="{esc(self.csrf)}">')

    @property
    def path(self) -> str:
        """Where this page lives, for "come back here" links. Sign-in URLs (which carry
        the one-time code and state) are never echoed back into the page."""
        p = self.request.url.path
        if p.startswith("/auth/"):
            return self.console.signin_path
        q = self.request.url.query
        return p + (f"?{q}" if q else "")


# ---------------------------------------------------------------------------
# HTML helpers. Text arguments are escaped; pass Html(...) for markup.
# ---------------------------------------------------------------------------
TONES = ("neutral", "accent", "success", "warning", "danger", "info")


def _tone(tone: str) -> str:
    return tone if tone in TONES else "neutral"


def button(label: Any, href: Optional[str] = None, *, variant: str = "primary", icon_name: Optional[str] = None,
           size: str = "md", type: str = "submit", attrs: Optional[Mapping[str, Any]] = None,
           trailing_icon: Optional[str] = None) -> Html:
    cls = f"nx-btn nx-btn--{esc(variant)}" + (f" nx-btn--{esc(size)}" if size != "md" else "")
    inner = join(icon(icon_name) if icon_name else "", Html(f"<span>{h(label)}</span>"),
                 icon(trailing_icon, cls="nx-flip") if trailing_icon else "")
    extra = dict(attrs or {})
    if href is not None:
        return Html(f'<a class="{cls}" href="{esc(href)}"{_attrs(extra)}>{inner}</a>')
    return Html(f'<button class="{cls}" type="{esc(type)}"{_attrs(extra)}>{inner}</button>')


def post_form(action: str, ctx: Ctx, inner: Any, *, confirm: Optional[str] = None, cls: str = "nx-inline") -> Html:
    """A POST form with the console CSRF field. ``confirm`` asks before submitting (JS)."""
    data = f' data-confirm="{esc(confirm)}"' if confirm else ""
    return Html(f'<form method="post" action="{esc(action)}" class="{esc(cls)}"{data}>{ctx.csrf_field()}{h(inner)}</form>')


def badge(text: Any, tone: str = "neutral", *, dot: bool = True) -> Html:
    d = '<span class="nx-badge__dot" aria-hidden="true"></span>' if dot else ""
    return Html(f'<span class="nx-badge nx-badge--{_tone(tone)}">{d}{h(text)}</span>')


def status_badge(ctx: Ctx, status: str) -> Html:
    tone = {"connected": "success", "active": "success", "disconnected": "neutral", "expired": "warning",
            "error": "danger", "off": "neutral"}.get(status, "neutral")
    return badge(ctx.t(f"status.{status}") if f"status.{status}" in STRINGS["en"] else status, tone)


def card(body: Any = "", title: Any = None, *, subtitle: Any = None, actions: Any = None, flush: bool = False,
         id: Optional[str] = None, cls: str = "") -> Html:
    head = ""
    if title is not None or actions is not None:
        sub = f'<p class="nx-card__sub">{h(subtitle)}</p>' if subtitle is not None else ""
        act = f'<div class="nx-card__actions">{h(actions)}</div>' if actions is not None else ""
        ttl = f'<h2 class="nx-card__title">{h(title)}</h2>' if title is not None else ""
        head = f'<div class="nx-card__head"><div class="nx-card__heading">{ttl}{sub}</div>{act}</div>'
    body_cls = "nx-card__body nx-card__body--flush" if flush else "nx-card__body"
    ident = f' id="{esc(id)}"' if id else ""
    return Html(f'<section class="nx-card {esc(cls)}"{ident}>{head}<div class="{body_cls}">{h(body)}</div></section>')


def stat(label: Any, value: Any, *, hint: Any = None, icon_name: Optional[str] = None, tone: str = "accent",
         href: Optional[str] = None) -> Html:
    ic = f'<span class="nx-stat__icon nx-tone-{_tone(tone)}">{icon(icon_name, 16)}</span>' if icon_name else ""
    hint_html = f'<p class="nx-stat__hint">{h(hint)}</p>' if hint is not None else ""
    inner = (f'<div class="nx-stat__top"><p class="nx-stat__label">{h(label)}</p>{ic}</div>'
             f'<p class="nx-stat__value">{h(value)}</p>{hint_html}')
    if href:
        return Html(f'<a class="nx-stat nx-stat--link" href="{esc(href)}">{inner}</a>')
    return Html(f'<div class="nx-stat">{inner}</div>')


def grid(*items: Any, cols: int = 2, cls: str = "") -> Html:
    return Html(f'<div class="nx-grid nx-grid--{int(cols)} {esc(cls)}">{"".join(h(i) for i in items)}</div>')


def stack(*items: Any, gap: str = "md") -> Html:
    return Html(f'<div class="nx-stack nx-stack--{esc(gap)}">{"".join(h(i) for i in items)}</div>')


def empty_state(title: Any, text: Any = "", *, icon_name: str = "inbox", action: Any = None) -> Html:
    act = f'<div class="nx-empty__action">{h(action)}</div>' if action is not None else ""
    return Html(f'<div class="nx-empty"><span class="nx-empty__icon">{icon(icon_name, 20)}</span>'
                f'<p class="nx-empty__title">{h(title)}</p><p class="nx-empty__text">{h(text)}</p>{act}</div>')


def table(headers: Sequence[Any], rows: Sequence[Sequence[Any]], *, empty: Any = None,
          align: Optional[Sequence[str]] = None, cls: str = "", stack: bool = True) -> Html:
    """A table that turns into labelled stacked rows on phones."""
    if not rows and empty is not None:
        return Html(h(empty))
    al = list(align or [])
    th = "".join(f'<th scope="col" class="nx-al-{esc(al[i]) if i < len(al) else "start"}">{h(x)}</th>'
                 for i, x in enumerate(headers))
    body = []
    for row in rows:
        tds = []
        for i, cell in enumerate(row):
            lab = headers[i] if i < len(headers) else ""
            a = al[i] if i < len(al) else "start"
            tds.append(f'<td data-label="{esc(lab)}" class="nx-al-{esc(a)}">{h(cell)}</td>')
        body.append("<tr>" + "".join(tds) + "</tr>")
    return Html(f'<div class="nx-table-wrap"><table class="nx-table{" nx-table--stack" if stack else ""} {esc(cls)}"><thead><tr>{th}</tr></thead>'
                f'<tbody>{"".join(body)}</tbody></table></div>')


def copy_button(value: str, ctx: Ctx, *, label: Optional[str] = None) -> Html:
    lab = label or ctx.t("copy")
    return Html(f'<button type="button" class="nx-copy" data-copy="{esc(value)}" data-copied="{esc(ctx.t("copied"))}" '
                f'data-failed="{esc(ctx.t("copy.failed"))}" aria-label="{esc(lab)}" title="{esc(lab)}">'
                f'{icon("copy", 15, cls="nx-copy__a")}{icon("check", 15, cls="nx-copy__b")}</button>')


def endpoint_row(label: Any, value: str, ctx: Ctx, *, hint: Any = None, copy: bool = True,
                 icon_name: Optional[str] = None) -> Html:
    """A labelled monospace value with a copy button (URLs, keys, commands)."""
    ic = icon(icon_name, 14) if icon_name else ""
    lab = f'<p class="nx-field__label">{ic}{h(label)}</p>' if label is not None else ""
    hint_html = f'<p class="nx-field__hint">{h(hint)}</p>' if hint is not None else ""
    cp = copy_button(value, ctx) if copy else ""
    shown = esc(value).replace("/", "/<wbr>").replace("/<wbr>/<wbr>", "//")  # break long URLs after a slash
    return Html(f'<div class="nx-field">{lab}<div class="nx-endpoint"><code dir="ltr">{shown}</code>{cp}</div>'
                f'{hint_html}</div>')


# ---- forms (classes in console.css "Forms") ---------------------------------
def _described(fid: str, hint: Any, error: Any) -> tuple[str, str, dict[str, Any]]:
    hint_id, err_id = f"{fid}-hint", f"{fid}-err"
    described = " ".join(x for x, on in ((hint_id, hint is not None), (err_id, error is not None)) if on)
    hint_html = f'<p class="nx-field__hint" id="{esc(hint_id)}">{h(hint)}</p>' if hint is not None else ""
    err_html = (f'<p class="nx-field__error" id="{esc(err_id)}">{icon("alert", 14)}<span>{h(error)}</span></p>'
                if error is not None else "")
    return hint_html, err_html, {"aria-describedby": described or None,
                                 "aria-invalid": "true" if error is not None else None}


def _field(fid: str, label: Any, control: str, hint_html: str, err_html: str, kind: str) -> Html:
    return Html(f'<div class="nx-field nx-field--{kind}"><label class="nx-field__label" for="{esc(fid)}">{h(label)}</label>'
                f'{control}{err_html}{hint_html}</div>')


def text_field(name: str, label: Any, *, type: str = "text", value: Any = "", placeholder: str = "",
               hint: Any = None, error: Any = None, autocomplete: Optional[str] = None,
               inputmode: Optional[str] = None, required: bool = False, autofocus: bool = False,
               ltr: bool = False, cls: str = "", id: Optional[str] = None,
               attrs: Optional[Mapping[str, Any]] = None) -> Html:
    """A labelled native input (phone numbers, codes, passwords, keys). ``ltr`` for numbers/latin.

    A password's value is never written back into the page. ``cls`` adds
    ``nx-input--lg`` (a big phone field) or ``nx-input--code`` (a one-time code).
    """
    fid = id or f"f-{name}"
    hint_html, err_html, aria = _described(fid, hint, error)
    extra: dict[str, Any] = {"autocomplete": autocomplete, "inputmode": inputmode, "required": required,
                             "autofocus": autofocus, "dir": "ltr" if ltr else None, **aria}
    extra.update(attrs or {})
    ph = f' placeholder="{esc(placeholder)}"' if placeholder else ""
    val = f' value="{esc(value)}"' if value not in (None, "") and type != "password" else ""
    control = (f'<input class="nx-input {esc(cls)}" id="{esc(fid)}" name="{esc(name)}" type="{esc(type)}"{val}{ph}'
               f'{_attrs(extra)}>')
    return _field(fid, label, control, hint_html, err_html, "input")


def select_field(name: str, label: Any, options: Iterable[Union[tuple[Any, Any], Any]], *, value: Any = "",
                 hint: Any = None, error: Any = None, required: bool = False, cls: str = "",
                 id: Optional[str] = None, attrs: Optional[Mapping[str, Any]] = None) -> Html:
    """A labelled <select>. ``options`` are (value, label) pairs or plain values; ``value`` is selected."""
    fid = id or f"f-{name}"
    hint_html, err_html, aria = _described(fid, hint, error)
    extra: dict[str, Any] = {"required": required, **aria}
    extra.update(attrs or {})
    opts = []
    for opt in options:
        v, lab = opt if isinstance(opt, tuple) else (opt, opt)
        sel = " selected" if str(v) == str(value) else ""
        opts.append(f'<option value="{esc(v)}"{sel}>{h(lab)}</option>')
    control = (f'<select class="nx-select {esc(cls)}" id="{esc(fid)}" name="{esc(name)}"{_attrs(extra)}>'
               f'{"".join(opts)}</select>')
    return _field(fid, label, control, hint_html, err_html, "select")


def textarea_field(name: str, label: Any, *, value: Any = "", placeholder: str = "", hint: Any = None,
                   error: Any = None, required: bool = False, ltr: bool = False, rows: Optional[int] = None,
                   mono: bool = False, cls: str = "", id: Optional[str] = None,
                   attrs: Optional[Mapping[str, Any]] = None) -> Html:
    """A labelled <textarea>. ``mono`` for pasted cookies, JSON or keys."""
    fid = id or f"f-{name}"
    hint_html, err_html, aria = _described(fid, hint, error)
    extra: dict[str, Any] = {"required": required, "dir": "ltr" if ltr else None, "rows": rows,
                             "placeholder": placeholder or None, **aria}
    extra.update(attrs or {})
    klass = "nx-textarea" + (" nx-textarea--mono" if mono else "") + (f" {cls}" if cls else "")
    control = (f'<textarea class="{esc(klass)}" id="{esc(fid)}" name="{esc(name)}"{_attrs(extra)}>'
               f'{esc(value)}</textarea>')
    return _field(fid, label, control, hint_html, err_html, "textarea")


def checkbox(name: str, label: Any, *, checked: bool = False, value: Any = "1", cls: str = "",
             attrs: Optional[Mapping[str, Any]] = None) -> Html:
    """A checkbox with its label beside it (wraps onto more lines cleanly)."""
    extra: dict[str, Any] = {"checked": checked}
    extra.update(attrs or {})
    return Html(f'<label class="nx-check {esc(cls)}"><input type="checkbox" name="{esc(name)}" value="{esc(value)}"'
                f'{_attrs(extra)}><span>{h(label)}</span></label>')


def hidden_field(name: str, value: Any) -> Html:
    return Html(f'<input type="hidden" name="{esc(name)}" value="{esc(value)}">')


def details(summary: Any, body: Any, *, open: bool = False, subtle: bool = False, cls: str = "") -> Html:
    """A native disclosure: "Advanced", "Edit", "Replace". ``subtle`` is the quieter summary style."""
    base = "nx-disclosure" if subtle else "nx-details"
    return Html(f'<details class="{base} {esc(cls)}"{" open" if open else ""}><summary>{h(summary)}</summary>'
                f'<div class="{base}__body">{h(body)}</div></details>')


def code_block(text: str, ctx: Ctx, *, label: Any = None, copy: bool = True) -> Html:
    head = ""
    if label is not None or copy:
        head = (f'<div class="nx-code__head"><span>{h(label or "")}</span>'
                f'{copy_button(text, ctx) if copy else ""}</div>')
    return Html(f'<div class="nx-code">{head}<pre dir="ltr" tabindex="0"><code>{esc(text)}</code></pre></div>')


def steps(items: Sequence[Union[tuple, Mapping[str, Any]]], *, numbered: bool = True, lang: str = "en") -> Html:
    """[(title, body)] or [(title, body, done)] → an ordered step list."""
    out = []
    for i, it in enumerate(items, 1):
        if isinstance(it, Mapping):
            title, body, done = it.get("title"), it.get("body"), bool(it.get("done"))
        else:
            title, body, done = it[0], (it[1] if len(it) > 1 else ""), bool(it[2]) if len(it) > 2 else False
        mark = icon("check", 14) if done else (digits(i, lang) if numbered else "")
        b = f'<div class="nx-steps__body">{h(body)}</div>' if body not in (None, "") else ""
        out.append(f'<li class="nx-steps__item{" is-done" if done else ""}"><span class="nx-steps__mark" '
                   f'aria-hidden="true">{h(mark) if isinstance(mark, Html) else esc(mark)}</span>'
                   f'<div class="nx-steps__content"><p class="nx-steps__title">{h(title)}</p>{b}</div></li>')
    return Html(f'<ol class="nx-steps">{"".join(out)}</ol>')


def notice(text: Any, *, tone: str = "info", title: Any = None, action: Any = None,
           icon_name: Optional[str] = None) -> Html:
    ic = icon_name or {"success": "check", "warning": "alert", "danger": "alert"}.get(tone, "info")
    ttl = f'<p class="nx-notice__title">{h(title)}</p>' if title is not None else ""
    act = f'<div class="nx-notice__action">{h(action)}</div>' if action is not None else ""
    role = ' role="alert"' if tone == "danger" else ""
    return Html(f'<div class="nx-notice nx-notice--{_tone(tone)}"{role}><span class="nx-notice__icon">{icon(ic, 16)}</span>'
                f'<div class="nx-notice__main">{ttl}<div class="nx-notice__text">{h(text)}</div></div>{act}</div>')


def danger_zone(ctx: Ctx, items: Sequence[tuple[Any, Any, Any]], *, title: Any = None) -> Html:
    """[(title, text, action_html)] in a red-edged card."""
    rows = "".join(f'<div class="nx-danger__row"><div><p class="nx-danger__title">{h(t)}</p>'
                   f'<p class="nx-danger__text">{h(x)}</p></div><div class="nx-danger__action">{h(a)}</div></div>'
                   for t, x, a in items)
    return card(Html(rows), title if title is not None else ctx.t("danger.title"), cls="nx-danger", flush=True)


def kv_list(pairs: Sequence[tuple[Any, Any]]) -> Html:
    rows = "".join(f'<div class="nx-kv__row"><dt>{h(k)}</dt><dd>{h(v)}</dd></div>' for k, v in pairs)
    return Html(f'<dl class="nx-kv">{rows}</dl>')


def tabs(ctx: Ctx, items: Sequence[tuple[str, Any, Any]], *, label: str = "") -> Html:
    """[(id, label, content)]. Without JS every panel shows, one after another."""
    tl = "".join(f'<button type="button" role="tab" class="nx-tabs__tab" id="tab-{esc(i)}" aria-controls="panel-{esc(i)}" '
                 f'aria-selected="{"true" if n == 0 else "false"}" tabindex="{0 if n == 0 else -1}">{h(lab)}</button>'
                 for n, (i, lab, _) in enumerate(items))
    panels = "".join(f'<div class="nx-tabs__panel" role="tabpanel" id="panel-{esc(i)}" aria-labelledby="tab-{esc(i)}"'
                     f'{"" if n == 0 else " data-inactive"}><h3 class="nx-tabs__fallback">{h(lab)}</h3>{h(c)}</div>'
                     for n, (i, lab, c) in enumerate(items))
    return Html(f'<div class="nx-tabs" data-tabs><div class="nx-tabs__list" role="tablist" aria-label="{esc(label)}">{tl}</div>'
                f'{panels}</div>')


def avatar(user: Optional[ConsoleUser], size: int = 32) -> Html:
    if user and user.avatar.startswith("https://"):
        return Html(f'<span class="nx-avatar" style="--s:{int(size)}px"><img src="{esc(user.avatar)}" alt="" '
                    f'width="{int(size)}" height="{int(size)}" referrerpolicy="no-referrer"></span>')
    inner = esc(user.initials) if user and user.initials else icon("user", max(12, int(size * 0.5)))
    return Html(f'<span class="nx-avatar" style="--s:{int(size)}px" aria-hidden="true">{inner}</span>')


def platform_tile(console_or_key: Union[Console, str], size: int = 32) -> Html:
    if isinstance(console_or_key, Console):
        key, color, grad, svg = (console_or_key.platform, console_or_key.brand_color or "#5647E6",
                                 console_or_key.brand_gradient or "", console_or_key.icon_svg)
    else:
        prod = PRODUCTS_BY_KEY.get(console_or_key)
        key, color, grad, svg = console_or_key, (prod.color if prod else "#5647E6"), (prod.gradient if prod else ""), None
    bg, fg = accessible_pair(color)
    bg_css = grad or bg
    return Html(f'<span class="nx-ptile" style="--tile:{esc(bg_css)};--tile-fg:{esc(fg)};--s:{int(size)}px">'
                f'{platform_glyph(key, max(12, int(size * 0.58)), svg)}</span>')


def nabux_mark(size: int = 28) -> Html:
    return Html(f'<span class="nx-mark" style="--s:{int(size)}px">{platform_glyph("nabux", max(12, int(size * 0.62)))}</span>')


def tools_list(ctx: Ctx, tools: Sequence[Mapping[str, Any]], *, groups: Optional[Mapping[str, str]] = None) -> Html:
    """The MCP tools, with a filter box. ``tools`` comes from ``mcp_tools()``."""
    items = []
    for tl in tools:
        name = str(tl.get("name") or "")
        desc = str(tl.get("description") or "").strip().split("\n\n")[0]
        props = (tl.get("input_schema") or {}).get("properties") or {}
        required = set((tl.get("input_schema") or {}).get("required") or [])
        chips = "".join(
            f'<span class="nx-chip{" is-req" if k in required else ""}" title="{esc((v or {}).get("type", "") if isinstance(v, dict) else "")}">'
            f'{esc(k)}{"" if k not in required else "<span aria-hidden=true>*</span>"}</span>'
            for k, v in props.items()) or f'<span class="nx-muted">{esc(ctx.t("tools.noargs"))}</span>'
        items.append(f'<li class="nx-tool" data-filter="{esc((name + " " + desc).lower())}">'
                     f'<div class="nx-tool__head"><code dir="ltr" class="nx-tool__name">{esc(name)}</code></div>'
                     f'<p class="nx-tool__desc">{esc(desc) or "&nbsp;"}</p>'
                     f'<div class="nx-tool__args" dir="ltr">{chips}</div></li>')
    search = (f'<label class="nx-search">{icon("search", 15)}<span class="nx-sr">{esc(ctx.t("tools.search"))}</span>'
              f'<input type="search" data-tool-filter placeholder="{esc(ctx.t("tools.search"))}" autocomplete="off"></label>')
    return Html(f'<div class="nx-tools" data-tools><div class="nx-tools__bar">{search}'
                f'<span class="nx-muted nx-tools__count">{esc(ctx.t("tools.count", n=len(tools)))}</span></div>'
                f'<ul class="nx-tools__list">{"".join(items)}</ul>'
                f'<p class="nx-tools__none" hidden>{esc(ctx.t("tools.none"))}</p></div>')


async def mcp_tools(mcp: Any) -> list[dict]:
    """The tools a FastMCP server exposes: name, description, input_schema."""
    try:
        listed = await mcp.list_tools()
    except Exception:
        return []
    out = []
    for tl in listed:
        out.append({"name": getattr(tl, "name", ""), "description": getattr(tl, "description", "") or "",
                    "input_schema": getattr(tl, "inputSchema", None) or getattr(tl, "parameters", None) or {}})
    return sorted(out, key=lambda x: x["name"])


def connect_ai(ctx: Ctx, *, server_name: str, token_html: Any = None) -> Html:
    """The standard "Connect to AI" content: endpoint + Claude/ChatGPT/Cursor/Claude Code (+ token)."""
    url = ctx.console.mcp_url
    cursor_json = json.dumps({"mcpServers": {server_name: {"url": url}}}, indent=2)
    items: list[tuple[str, Any, Any]] = [
        ("claude", ctx.t("ai.claude"), stack(steps([(ctx.t("ai.claude.s1"), ""), (ctx.t("ai.claude.s2"), ""),
                                                     (ctx.t("ai.claude.s3"), "")], lang=ctx.lang),
                                              endpoint_row(ctx.t("endpoint"), url, ctx))),
        ("chatgpt", ctx.t("ai.chatgpt"), stack(steps([(ctx.t("ai.chatgpt.s1"), ""), (ctx.t("ai.chatgpt.s2"), ""),
                                                       (ctx.t("ai.chatgpt.s3"), "")], lang=ctx.lang),
                                                endpoint_row(ctx.t("endpoint"), url, ctx))),
        ("cursor", ctx.t("ai.cursor"), stack(steps([(ctx.t("ai.cursor.s1"), ""), (ctx.t("ai.cursor.s2"), "")], lang=ctx.lang),
                                              code_block(cursor_json, ctx, label="mcp.json"))),
        ("claude-code", ctx.t("ai.claude_code"), stack(steps([(ctx.t("ai.claude_code.s1"), "")], lang=ctx.lang),
                                                        code_block(f"claude mcp add --transport http {server_name} {url}",
                                                                   ctx, label="Terminal"))),
    ]
    if token_html is not None:
        items.append(("token", ctx.t("ai.token"), token_html))
    return tabs(ctx, items, label=ctx.t("nav.connect"))


# ---------------------------------------------------------------------------
# Page assembly
# ---------------------------------------------------------------------------
def _shell_response(c: Ctx, title: str, body: str, *, status: int = 200, headers: Optional[dict] = None,
                    title_override: Optional[str] = None) -> HTMLResponse:
    con = c.console
    page_title = f"{title_override or title} · {con.product}" if title != con.product else (title_override or title)
    theme_attr = (f' data-theme="{c.theme}"' if c.theme in ("light", "dark") else "") + f' data-theme-pref="{c.theme}"'
    bg, fg = accessible_pair(con.brand_color or "#5647E6")
    favicon = ("data:image/svg+xml," + quote(
        f'<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><rect width="24" height="24" rx="6" fill="{bg}"/>'
        f'<g transform="translate(3.6 3.6) scale(.7)" fill="{fg}">{_PLATFORM_GLYPHS.get(con.platform, _PLATFORM_GLYPHS["nabux"])}</g></svg>',
        safe=""))
    doc = (
        f'<!doctype html><html lang="{esc(c.lang)}" dir="{esc(c.dir)}"{theme_attr} class="no-js">'
        f'<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">'
        f'<title>{esc(page_title)}</title>'
        f'<meta name="color-scheme" content="light dark">'
        f'<meta name="theme-color" content="#fcfbf8" media="(prefers-color-scheme: light)">'
        f'<meta name="theme-color" content="#070812" media="(prefers-color-scheme: dark)">'
        f'<link rel="icon" href="{favicon}">'
        f'<link rel="stylesheet" href="{asset("console.css")}">'
        f'<script src="{asset("console.js")}"></script>'
        f'<style>:root{{--nx-platform:{esc(bg)};--nx-platform-fg:{esc(fg)}}}</style>'
        f'</head>{body}</html>'
    )
    if c.lang == "fa":
        doc = fa_separators(doc)
    base = {
        "Cache-Control": "no-store",
        "X-Frame-Options": "DENY",
        "X-Content-Type-Options": "nosniff",
        "Referrer-Policy": "strict-origin-when-cross-origin",
        "Content-Security-Policy": ("default-src 'self'; img-src 'self' https: data:; style-src 'self' 'unsafe-inline'; "
                                    "script-src 'self'; font-src 'self'; connect-src 'self'; frame-ancestors 'none'; "
                                    "base-uri 'self'; object-src 'none'"),
    }
    base.update(headers or {})
    return HTMLResponse(doc, status_code=status, headers=base)


def _theme_switch(c: Ctx) -> Html:
    opts = []
    for mode, ic in (("light", "sun"), ("dark", "moon"), ("system", "monitor")):
        cur = c.theme == mode
        href = "/console/prefs?theme=" + mode + "&next=" + quote(c.path, safe="")
        current = ' aria-current="true"' if cur else ""
        opts.append(f'<a class="nx-seg__opt" href="{esc(href)}" data-theme-set="{mode}"{current} '
                    f'title="{esc(c.t("theme." + mode))}">{icon(ic, 15)}<span class="nx-sr">{esc(c.t("theme." + mode))}</span></a>')
    return Html(f'<div class="nx-seg" role="group" aria-label="{esc(c.t("theme.label"))}">{"".join(opts)}</div>')


def _lang_link(c: Ctx, cls: str = "nx-iconbtn nx-langbtn") -> Html:
    other = c.t("lang.other.code")
    href = "/console/prefs?lang=" + other + "&next=" + quote(_strip_lang(c.path), safe="")
    return Html(f'<a class="{cls}" href="{esc(href)}" data-lang-set="{esc(other)}" hreflang="{esc(other)}" '
                f'lang="{esc(other)}" title="{esc(c.t("lang.label"))}: {esc(c.t("lang.other"))}">'
                f'{icon("languages", 16)}<span class="nx-langbtn__txt">{esc(c.t("lang.other"))}</span></a>')


def _strip_lang(path: str) -> str:
    if "?" not in path:
        return path
    base, q = path.split("?", 1)
    keep = [p for p in q.split("&") if p and not p.startswith("lang=")]
    return base + ("?" + "&".join(keep) if keep else "")


def _theme_toggle(c: Ctx) -> Html:
    nxt = {"light": "dark", "dark": "system", "system": "light"}[c.theme]
    href = "/console/prefs?theme=" + nxt + "&next=" + quote(c.path, safe="")
    labels = {m: c.t("theme." + m) for m in ("light", "dark", "system")}
    return Html(f'<a class="nx-iconbtn" href="{esc(href)}" data-theme-cycle data-labels="{esc(json.dumps(labels))}" '
                f'title="{esc(c.t("theme.label"))}: {esc(labels[c.theme])}" aria-label="{esc(c.t("theme.label"))}: {esc(labels[c.theme])}">'
                f'{icon("sun", 16, cls="nx-th-light")}{icon("moon", 16, cls="nx-th-dark")}{icon("monitor", 16, cls="nx-th-system")}</a>')


def _switcher(c: Ctx) -> Html:
    tiles = []
    for p in PRODUCTS:
        here = p.key == c.console.platform
        name = p.name if c.lang == "en" or not p.fa else f"{p.fa} MCP"
        extra = f'<span class="nx-switch__here">{esc(c.t("switcher.here"))}</span>' if here else \
            f'<span class="nx-switch__host" dir="ltr">{esc(p.host)}</span>'
        tiles.append(f'<li><a class="nx-switch__item{" is-here" if here else ""}" href="{esc(p.url)}"'
                     f'{" aria-current=page" if here else ""}>{platform_tile(p.key, 30)}'
                     f'<span class="nx-switch__txt"><span class="nx-switch__name">{esc(name)}</span>{extra}</span></a></li>')
    return Html(
        f'<details class="nx-pop nx-switch" data-pop><summary class="nx-iconbtn" aria-label="{esc(c.t("switcher.label"))}" '
        f'title="{esc(c.t("switcher.label"))}">{icon("grid", 16)}</summary>'
        f'<div class="nx-pop__panel nx-switch__panel"><p class="nx-pop__title">{esc(c.t("switcher.title"))}</p>'
        f'<ul class="nx-switch__grid">{"".join(tiles)}</ul>'
        f'<p class="nx-pop__foot">{nabux_mark(18)}<span>{esc(c.t("switcher.all"))}</span></p></div></details>')


def _user_menu(c: Ctx) -> Html:
    u = c.user
    if u is None:
        return Html("")
    who = esc(u.display or c.t("user.local"))
    email = f'<p class="nx-user__email" dir="ltr">{esc(u.email)}</p>' if u.email else \
        f'<p class="nx-user__email">{esc(c.t("user.local"))}</p>'
    con = c.console
    manage = ""
    if u.nabu:
        manage = (f'<a class="nx-menu__item" href="{esc(con.nabuauth.url)}/" target="_blank" rel="noopener">'
                  f'{icon("user", 15)}<span>{esc(c.t("user.manage"))}</span>{icon("external", 13, cls="nx-menu__ext")}</a>')
    elif con.nabuauth.enabled:
        manage = (f'<a class="nx-menu__item" href="{esc(con.login_url(c.path))}">{icon("shield", 15)}'
                  f'<span>{esc(c.t("user.link"))}</span></a>')
    signout = (f'<form method="post" action="/auth/logout">{c.csrf_field()}<button type="submit" class="nx-menu__item">'
               f'{icon("logout", 15)}<span>{esc(c.t("user.signout"))}</span></button></form>')
    return Html(
        f'<details class="nx-pop nx-user" data-pop><summary class="nx-user__btn" aria-label="{esc(c.t("user.menu"))}">'
        f'{avatar(u, 30)}</summary><div class="nx-pop__panel nx-user__panel">'
        f'<div class="nx-user__head">{avatar(u, 36)}<div class="nx-user__id"><p class="nx-user__name">{who}</p>{email}</div></div>'
        f'<div class="nx-menu">{manage}'
        f'<div class="nx-menu__row"><span>{esc(c.t("theme.label"))}</span>{_theme_switch(c)}</div>'
        f'<div class="nx-menu__row"><span>{esc(c.t("lang.label"))}</span>{_lang_link(c, "nx-chipbtn")}</div>'
        f'<div class="nx-menu__sep"></div>{signout}</div></div></details>')


def _brand(c: Ctx, href: str) -> Html:
    con = c.console
    return Html(f'<a class="nx-brand" href="{esc(href)}">{nabux_mark(26)}<span class="nx-brand__slash" aria-hidden="true">/</span>'
                f'{platform_tile(con, 26)}<span class="nx-brand__name">{esc(con.product)}</span></a>')


def _sidebar(c: Ctx, active: Optional[str]) -> Html:
    con = c.console
    sections: dict[str, list[str]] = {}
    for item in con.nav:
        cur = item.key == active
        b = f'<span class="nx-nav__badge">{esc(item.badge)}</span>' if item.badge else ""
        sections.setdefault(item.section, []).append(
            f'<li><a class="nx-nav__link{" is-active" if cur else ""}" href="{esc(item.href)}"'
            f'{" aria-current=page" if cur else ""}>{icon(item.icon, 16)}<span>{esc(con.text(c.lang, item.label))}</span>{b}</a></li>')
    groups = []
    for sec, links in sections.items():
        label = c.t(f"nav.section.{sec}")
        groups.append(f'<p class="nx-nav__section">{esc(label)}</p><ul class="nx-nav__list">{"".join(links)}</ul>')
    mini = (f'<div class="nx-side__endpoint"><p class="nx-side__label">{icon("plug", 13)}{esc(c.t("endpoint"))}</p>'
            f'<div class="nx-endpoint nx-endpoint--sm"><code dir="ltr">{esc(con.host)}/mcp</code>{copy_button(con.mcp_url, c)}</div></div>')
    return Html(
        f'<aside class="nx-side" id="nx-side" aria-label="{esc(con.product)}"><div class="nx-side__inner"><div class="nx-side__head">'
        f'{_brand(c, con.home_path)}<button type="button" class="nx-iconbtn nx-side__close" data-drawer-close '
        f'aria-label="{esc(c.t("menu.close"))}">{icon("close", 16)}</button></div>'
        f'<nav class="nx-nav">{"".join(groups)}</nav>{mini}</div></aside>')


def _footer(c: Ctx, compact: bool = False) -> Html:
    con = c.console
    links = (f'<a href="{esc(con.privacy_url)}">{esc(c.t("footer.privacy"))}</a>'
             f'<a href="{esc(con.terms_url)}">{esc(c.t("footer.terms"))}</a>'
             f'<a href="{esc(con.nabuauth.url)}/">{esc(c.t("footer.account"))}</a>'
             f'<a href="{esc(NABUX_HOME_URL)}">{esc(c.t("footer.all"))}</a>')
    return Html(f'<footer class="nx-foot{" nx-foot--compact" if compact else ""}"><p>{nabux_mark(16)}'
                f'<span>{esc(c.t("footer.by"))}</span></p><nav class="nx-foot__links">{links}</nav></footer>')


def theme_switch(c: Ctx) -> Html:
    """Light / dark / system segmented control (works without JS)."""
    return _theme_switch(c)


def lang_switch(c: Ctx, cls: str = "nx-chipbtn") -> Html:
    """A link to the other language (works without JS)."""
    return _lang_link(c, cls)


def _app_shell(c: Ctx, title: str, body: Any, active: Optional[str], subtitle: Any, actions: Any,
               toast: Optional[str], banner: Any) -> str:
    con = c.console
    sub = f'<p class="nx-page__sub">{h(subtitle)}</p>' if subtitle is not None else ""
    act = f'<div class="nx-page__actions">{h(actions)}</div>' if actions is not None else ""
    link_banner = ""
    if banner is None and c.user is not None and not c.user.nabu and con.nabuauth.enabled:
        banner = notice(c.t("link.banner.text"), tone="accent", title=c.t("link.banner.title"), icon_name="shield",
                        action=button(c.t("signin.cta"), con.login_url(c.path), size="sm"))
    if banner is not None:
        link_banner = f'<div class="nx-page__banner">{h(banner)}</div>'
    toast_attr = f' data-toast="{esc(toast)}"' if toast else ""
    return (
        f'<body class="nx-app"{toast_attr}><a class="nx-skip" href="#nx-main">{esc(c.t("skip"))}</a>'
        f'<div class="nx-layout">{_sidebar(c, active)}<div class="nx-scrim" data-drawer-close hidden></div>'
        f'<div class="nx-main-col"><header class="nx-top"><button type="button" class="nx-iconbtn nx-top__menu" '
        f'data-drawer-open aria-controls="nx-side" aria-expanded="false" aria-label="{esc(c.t("menu.open"))}">{icon("menu", 18)}</button>'
        f'<div class="nx-top__brand">{_brand(c, con.home_path)}</div>'
        f'<div class="nx-top__crumb"><span>{esc(con.product)}</span>{icon("chevron-right", 14, cls="nx-crumb-sep")}<b>{esc(title)}</b></div>'
        f'<div class="nx-top__spacer"></div><span class="nx-top__host" dir="ltr">{esc(con.host)}</span>'
        f'<div class="nx-top__tools">{_lang_link(c)}{_theme_toggle(c)}{_switcher(c)}{_user_menu(c)}</div></header>'
        f'<main class="nx-main" id="nx-main" tabindex="-1"><div class="nx-page">{link_banner}'
        f'<div class="nx-page__head"><div class="nx-page__titles"><h1 class="nx-page__title">{esc(title)}</h1>{sub}</div>{act}</div>'
        f'<div class="nx-page__body">{h(body)}</div></div>{_footer(c, compact=True)}</main></div></div>'
        f'<div class="nx-toasts" aria-live="polite" aria-atomic="true"></div></body>'
    )


def _signin_page(c: Ctx, notice_html: Any, next_path: Optional[str]) -> str:
    con = c.console
    nxt = safe_next(next_path or c.request.query_params.get("next"), con.home_path)
    if con.nabuauth.enabled:
        primary = button(c.t("signin.cta"), con.login_url(nxt), size="lg", icon_name=None, trailing_icon="arrow",
                         attrs={"data-primary": True})
        fallback_note = ""
    else:
        label, href = con.fallback_connect or (c.t("auth.home"), con.home_path)
        primary = button(con.text(c.lang, label), href, size="lg", variant="platform", trailing_icon="arrow")
        fallback_note = notice(c.t("signin.disabled.text"), tone="warning", title=c.t("signin.disabled.title"))
    headline = con.text(c.lang, con.headline) or con.product
    tagline = con.text(c.lang, con.tagline)
    trust = "".join(f'<li>{icon(ic, 15)}<span>{esc(con.text(c.lang, k))}</span></li>' for ic, k in (
        ("shield", con.trust_line), ("lock", "signin.trust.encrypted"), ("refresh", "signin.trust.revoke")))
    features = "".join(
        f'<li class="nx-feat"><span class="nx-feat__icon">{icon(f.icon, 16)}</span><div><p class="nx-feat__title">'
        f'{esc(con.text(c.lang, f.title))}</p><p class="nx-feat__text">{esc(con.text(c.lang, f.text))}</p></div></li>'
        for f in con.features)
    clients = "".join(f'<li class="nx-diag__client"><span class="nx-diag__mono" aria-hidden="true">{icon(ic, 12)}</span>{esc(n)}</li>'
                      for n, ic in (("Claude", "sparkles"), ("ChatGPT", "message"), ("Cursor", "cursor")))
    prod = PRODUCTS_BY_KEY.get(con.platform)
    pname = (prod.fa if (prod and c.lang == "fa" and prod.fa) else (prod.name.replace(" MCP", "") if prod else con.product))
    diagram = (
        f'<div class="nx-diag" aria-hidden="true"><div class="nx-diag__glow"></div>'
        f'<div class="nx-diag__node">{platform_tile(con, 52)}<span class="nx-diag__cap">{esc(pname)}</span></div>'
        f'<div class="nx-diag__wire"><span></span></div>'
        f'<div class="nx-diag__node nx-diag__node--hub">{nabux_mark(60)}<span class="nx-diag__cap">Nabux MCP</span>'
        f'<span class="nx-diag__sub">{esc(c.t("signin.one"))}</span></div>'
        f'<div class="nx-diag__wire nx-diag__wire--b"><span></span></div>'
        f'<div class="nx-diag__node"><ul class="nx-diag__clients">{clients}</ul><span class="nx-diag__cap">{esc(c.t("signin.ai"))}</span></div>'
        f'</div>')
    connects = ""
    if features:
        connects = (f'<section class="nx-land__sec" id="connects"><h2 class="nx-land__h2">{esc(c.t("signin.connects"))}</h2>'
                    f'<ul class="nx-feats">{features}</ul></section>')
    mcp = (f'<section class="nx-land__sec nx-land__mcp" id="how"><div><h2 class="nx-land__h2">{esc(c.t("signin.mcp.title"))}</h2>'
           f'<p class="nx-land__p">{esc(c.t("signin.mcp.text"))}</p></div>'
           f'{steps([(c.t("signin.mcp.s1"), ""), (c.t("signin.mcp.s2"), ""), (c.t("signin.mcp.s3"), "")], lang=c.lang)}</section>')
    extra = f'<div class="nx-land__notice">{h(notice_html)}</div>' if notice_html is not None else ""
    fb = f'<div class="nx-land__notice">{h(fallback_note)}</div>' if fallback_note else ""
    return (
        f'<body class="nx-land"><a class="nx-skip" href="#nx-main">{esc(c.t("skip"))}</a>'
        f'<header class="nx-land__top">{_brand(c, con.signin_path)}<div class="nx-top__spacer"></div>'
        f'<div class="nx-top__tools">{_lang_link(c)}{_theme_toggle(c)}{_switcher(c)}</div></header>'
        f'<main id="nx-main" class="nx-land__main" tabindex="-1">'
        f'<section class="nx-hero"><div class="nx-hero__copy">{extra}{fb}'
        f'<p class="nx-eyebrow">{platform_tile(con, 20)}<span>{esc(c.t("signin.eyebrow"))}</span></p>'
        f'<h1 class="nx-hero__title">{esc(headline)}</h1>'
        f'<p class="nx-hero__lead">{esc(tagline)}</p>'
        f'<div class="nx-hero__cta">{primary}<a class="nx-btn nx-btn--ghost nx-btn--lg" href="#how"><span>{esc(c.t("signin.secondary"))}</span></a></div>'
        f'<ul class="nx-trust">{trust}</ul></div>'
        f'<div class="nx-hero__art">{diagram}</div></section>'
        f'{connects}{mcp}'
        f'<p class="nx-land__privacy">{icon("lock", 14)}<span>{esc(con.text(c.lang, con.privacy))}</span></p>'
        f'</main>{_footer(c)}<div class="nx-toasts" aria-live="polite" aria-atomic="true"></div></body>'
    )


def _message_page(c: Ctx, title: str, message: Any, tone: str, action: Optional[tuple[str, str]]) -> str:
    con = c.console
    act = button(action[0], action[1]) if action else ""
    home = (f'<a class="nx-btn nx-btn--ghost" href="{esc(con.signin_path)}"><span>{esc(c.t("auth.home"))}</span></a>')
    ic = {"danger": "alert", "warning": "alert", "success": "check"}.get(tone, "info")
    return (
        f'<body class="nx-land nx-msgpage"><header class="nx-land__top">{_brand(c, con.signin_path)}'
        f'<div class="nx-top__spacer"></div><div class="nx-top__tools">{_lang_link(c)}{_theme_toggle(c)}</div></header>'
        f'<main id="nx-main" class="nx-msg"><div class="nx-msg__card"><span class="nx-msg__icon nx-tone-{_tone(tone)}">{icon(ic, 22)}</span>'
        f'<h1 class="nx-msg__title">{esc(title)}</h1><div class="nx-msg__text">{h(message)}</div>'
        f'<div class="nx-msg__actions">{act}{home}</div></div></main>{_footer(c)}</body>'
    )


__all__ = [
    "Console", "ConsoleUser", "NabuAuthSettings", "NavItem", "Feature", "Ctx", "Html", "Product", "PRODUCTS",
    "STRINGS", "NabuAuthError", "standard_nav", "esc", "h", "join", "icon", "button", "post_form", "badge",
    "status_badge", "card", "stat", "grid", "stack", "empty_state", "table", "copy_button", "endpoint_row",
    "code_block", "steps", "notice", "danger_zone", "kv_list", "tabs", "avatar", "platform_tile", "nabux_mark",
    "tools_list", "mcp_tools", "connect_ai", "theme_switch", "lang_switch", "pick_lang", "direction", "safe_next", "pkce", "verified_email",
    "registrar", "serve_static", "asset", "ASSET_VERSION", "register_owner_accounts_route", "owner_accounts_payload",
    "iso_time", "accessible_pair", "contrast", "digits", "text_field", "hidden_field", "select_field",
    "textarea_field", "checkbox", "details", "tag", "sep", "sep_join", "fa_separators", "ACCOUNT_MODES",
]
