/*
 * Nabux MCP Console — small progressive enhancements. Every page works without it.
 * Theme (light/dark/system) and language live in cookies the server also reads,
 * so the first paint is already right. Source: NabuXUI packages/mcp-console.
 */
(function () {
  "use strict";
  var doc = document, root = doc.documentElement;
  root.classList.remove("no-js");
  root.classList.add("js");
  if (doc.readyState === "loading") doc.addEventListener("DOMContentLoaded", init); else init();
  function init() {

  var YEAR = 365 * 24 * 3600;
  function setCookie(name, value) {
    var secure = location.protocol === "https:" ? "; Secure" : "";
    doc.cookie = name + "=" + encodeURIComponent(value) + "; Max-Age=" + YEAR + "; Path=/; SameSite=Lax" + secure;
  }
  function $$(sel, el) { return Array.prototype.slice.call((el || doc).querySelectorAll(sel)); }

  /* ---- toasts ------------------------------------------------------------ */
  var ICON_OK = '<svg class="nx-i" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 6 9 17l-5-5"/></svg>';
  var ICON_ERR = '<svg class="nx-i" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><path d="M12 8v4"/><path d="M12 16h.01"/></svg>';
  function toast(message, tone) {
    var host = doc.querySelector(".nx-toasts");
    if (!host || !message) return;
    var el = doc.createElement("div");
    el.className = "nx-toast" + (tone === "danger" ? " nx-toast--danger" : "");
    el.setAttribute("role", tone === "danger" ? "alert" : "status");
    el.innerHTML = tone === "danger" ? ICON_ERR : ICON_OK;
    var span = doc.createElement("span");
    span.textContent = message;
    el.appendChild(span);
    host.appendChild(el);
    setTimeout(function () {
      el.classList.add("is-leaving");
      setTimeout(function () { el.remove(); }, 260);
    }, 2600);
  }
  window.nxToast = toast;

  /* ---- theme ------------------------------------------------------------- */
  var ORDER = ["light", "dark", "system"];
  function applyTheme(mode) {
    if (mode === "light" || mode === "dark") root.setAttribute("data-theme", mode);
    else root.removeAttribute("data-theme");
    root.setAttribute("data-theme-pref", mode);
    setCookie("nx_theme", mode);
    $$("[data-theme-set]").forEach(function (a) {
      if (a.getAttribute("data-theme-set") === mode) a.setAttribute("aria-current", "true"); else a.removeAttribute("aria-current");
    });
    $$("[data-theme-cycle]").forEach(function (a) {
      var labels = {};
      try { labels = JSON.parse(a.getAttribute("data-labels") || "{}"); } catch (e) { labels = {}; }
      var base = (a.getAttribute("aria-label") || "").split(":")[0];
      if (labels[mode]) { a.setAttribute("aria-label", base + ": " + labels[mode]); a.title = base + ": " + labels[mode]; }
    });
  }
  doc.addEventListener("click", function (ev) {
    var set = ev.target.closest && ev.target.closest("[data-theme-set]");
    if (set) { ev.preventDefault(); applyTheme(set.getAttribute("data-theme-set")); return; }
    var cyc = ev.target.closest && ev.target.closest("[data-theme-cycle]");
    if (cyc) {
      ev.preventDefault();
      var cur = root.getAttribute("data-theme-pref") || "system";
      applyTheme(ORDER[(ORDER.indexOf(cur) + 1) % ORDER.length]);
      return;
    }
    var lang = ev.target.closest && ev.target.closest("[data-lang-set]");
    if (lang) {
      ev.preventDefault();
      setCookie("nx_lang", lang.getAttribute("data-lang-set"));
      var url = new URL(location.href);
      url.searchParams.delete("lang");
      location.replace(url.toString());
    }
  });

  /* ---- copy -------------------------------------------------------------- */
  function fallbackCopy(text) {
    var ta = doc.createElement("textarea");
    ta.value = text; ta.setAttribute("readonly", ""); ta.style.position = "fixed"; ta.style.opacity = "0";
    doc.body.appendChild(ta); ta.select();
    var ok = false;
    try { ok = doc.execCommand("copy"); } catch (e) { ok = false; }
    ta.remove();
    return ok;
  }
  doc.addEventListener("click", function (ev) {
    var btn = ev.target.closest && ev.target.closest("[data-copy]");
    if (!btn) return;
    var text = btn.getAttribute("data-copy") || "";
    function done(ok) {
      if (ok) {
        btn.classList.add("is-copied");
        setTimeout(function () { btn.classList.remove("is-copied"); }, 1600);
        toast(btn.getAttribute("data-copied") || "Copied");
      } else {
        toast(btn.getAttribute("data-failed") || "Copy failed", "danger");
      }
    }
    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard.writeText(text).then(function () { done(true); }, function () { done(fallbackCopy(text)); });
    } else {
      done(fallbackCopy(text));
    }
  });

  /* ---- popovers (details): one open at a time, close outside / Escape ---- */
  var pops = $$("details[data-pop]");
  pops.forEach(function (d) {
    d.addEventListener("toggle", function () {
      if (d.open) pops.forEach(function (o) { if (o !== d) o.open = false; });
    });
  });
  doc.addEventListener("click", function (ev) {
    pops.forEach(function (d) { if (d.open && !d.contains(ev.target)) d.open = false; });
  });
  doc.addEventListener("keydown", function (ev) {
    if (ev.key !== "Escape") return;
    pops.forEach(function (d) {
      if (d.open) { d.open = false; var s = d.querySelector("summary"); if (s) s.focus(); }
    });
    if (root.classList.contains("nx-drawer-open")) closeDrawer(true);
  });

  /* ---- mobile drawer ----------------------------------------------------- */
  var opener = doc.querySelector("[data-drawer-open]");
  var side = doc.getElementById("nx-side");
  var scrim = doc.querySelector(".nx-scrim");
  function openDrawer() {
    root.classList.add("nx-drawer-open");
    if (scrim) scrim.hidden = false;
    if (opener) opener.setAttribute("aria-expanded", "true");
    var first = side && side.querySelector(".nx-nav__link.is-active, .nx-nav__link");
    if (first) setTimeout(function () { first.focus(); }, 50);
  }
  function closeDrawer(refocus) {
    root.classList.remove("nx-drawer-open");
    if (scrim) scrim.hidden = true;
    if (opener) { opener.setAttribute("aria-expanded", "false"); if (refocus) opener.focus(); }
  }
  if (opener) opener.addEventListener("click", openDrawer);
  $$("[data-drawer-close]").forEach(function (el) { el.addEventListener("click", function () { closeDrawer(true); }); });

  /* ---- tabs -------------------------------------------------------------- */
  $$("[data-tabs]").forEach(function (box) {
    var tabs = $$('[role="tab"]', box);
    function select(tab, focus) {
      tabs.forEach(function (t) {
        var on = t === tab;
        t.setAttribute("aria-selected", on ? "true" : "false");
        t.tabIndex = on ? 0 : -1;
        var panel = doc.getElementById(t.getAttribute("aria-controls"));
        if (panel) { if (on) panel.removeAttribute("data-inactive"); else panel.setAttribute("data-inactive", ""); }
      });
      if (focus) tab.focus();
    }
    tabs.forEach(function (t, i) {
      t.addEventListener("click", function () { select(t); });
      t.addEventListener("keydown", function (ev) {
        var rtl = root.dir === "rtl";
        var step = { ArrowRight: rtl ? -1 : 1, ArrowLeft: rtl ? 1 : -1 }[ev.key];
        if (ev.key === "Home") step = -i; else if (ev.key === "End") step = tabs.length - 1 - i;
        if (step === undefined) return;
        ev.preventDefault();
        select(tabs[(i + step + tabs.length) % tabs.length], true);
      });
    });
    var want = location.hash && box.querySelector('[aria-controls="panel-' + location.hash.slice(1) + '"]');
    if (want) select(want);
  });

  /* ---- tool filter ------------------------------------------------------- */
  $$("[data-tools]").forEach(function (box) {
    var input = box.querySelector("[data-tool-filter]");
    var none = box.querySelector(".nx-tools__none");
    if (!input) return;
    input.addEventListener("input", function () {
      var q = input.value.trim().toLowerCase(), shown = 0;
      $$(".nx-tool", box).forEach(function (li) {
        var hit = !q || (li.getAttribute("data-filter") || "").indexOf(q) !== -1;
        li.hidden = !hit; if (hit) shown++;
      });
      if (none) none.hidden = shown !== 0;
    });
  });

  /* ---- confirm before destructive forms ---------------------------------- */
  doc.addEventListener("submit", function (ev) {
    var f = ev.target;
    var msg = f && f.getAttribute && f.getAttribute("data-confirm");
    if (msg && !window.confirm(msg)) ev.preventDefault();
  });

  /* ---- server flash ------------------------------------------------------ */
  var flash = doc.body && doc.body.getAttribute("data-toast");
  if (flash) toast(flash);
  }
})();
