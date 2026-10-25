// @vitest-environment happy-dom
import { afterEach, describe, expect, it, vi } from 'vitest';
import { PLATFORM_EVENT, WINDOW_CLOSE_EVENT, isPlatform, platformWindow, platforms, snapShapes, snapZones } from '../src/js/blocks/platform';

/**
 * The blade markup, distilled: an empty caption cluster (the CSS draws the
 * glyphs), the maximize button inside the snap wrap with its two labels, and
 * the snap menu the fallback branch opens through data-open. happy-dom 20.14.5
 * keeps `popover` on the prototype but no `showPopover`, so the popover-native
 * branch is unreachable here — by design, these tests walk the fallback.
 */
const makeWindow = (): HTMLElement => {
  const el = document.createElement('div');
  el.className = 'nx-app-window';
  el.innerHTML = `
    <div class="nx-app-frame">
      <div class="nx-app-main">
        <header class="nx-app-titlebar">
          <div class="nx-app-caption">
            <button type="button" class="nx-app-caption-btn" data-kind="minimize" aria-label="Minimize"></button>
            <div class="nx-app-snap">
              <button type="button" class="nx-app-caption-btn" data-kind="maximize" aria-label="Maximize"
                data-label-maximize="Maximize" data-label-restore="Restore"></button>
              <div class="nx-app-snap-menu" popover="auto" role="menu" aria-label="Snap layouts">
                <div class="nx-app-snap-layout" data-zones="halves" role="group">
                  <button type="button" role="menuitem" data-shape="half"></button>
                  <button type="button" role="menuitem" data-shape="half"></button>
                </div>
                <div class="nx-app-snap-layout" data-zones="thirds" role="group">
                  <button type="button" role="menuitem" data-shape="third"></button>
                </div>
              </div>
            </div>
            <button type="button" class="nx-app-caption-btn" data-kind="close" aria-label="Close"></button>
          </div>
        </header>
        <div class="nx-app-content"></div>
      </div>
    </div>`;
  document.body.appendChild(el);
  return el;
};

const button = (el: HTMLElement, kind: string): HTMLButtonElement =>
  el.querySelector<HTMLButtonElement>(`.nx-app-caption-btn[data-kind="${kind}"]`)!;
const menu = (el: HTMLElement): HTMLElement => el.querySelector<HTMLElement>('.nx-app-snap-menu')!;
const zone = (el: HTMLElement, shape: string): HTMLButtonElement =>
  el.querySelector<HTMLButtonElement>(`.nx-app-snap-layout button[data-shape="${shape}"]`)!;

afterEach(() => {
  vi.useRealTimers();
  document.body.innerHTML = '';
});

describe('platform vocabulary', () => {
  it('names the four shells and keeps them narrow', () => {
    expect(platforms).toEqual(['windows', 'macos', 'android', 'ios']);
    expect(isPlatform('macos')).toBe(true);
    expect(isPlatform('beos')).toBe(false);
    expect(isPlatform(undefined)).toBe(false);
  });

  it('lists the snap shapes and zone groups the flyout offers', () => {
    expect(snapShapes).toEqual(['half', 'third', 'big', 'quarter', 'wide', 'mid', 'full']);
    expect(snapZones).toEqual(['halves', 'thirds', 'big-start', 'quads', 'big-top', 'mixed']);
  });
});

describe('app window state machine', () => {
  it('settles on rest and the attribute platform before anything is pressed', () => {
    const el = makeWindow();
    platformWindow(el, { platform: 'macos' });
    expect(el.dataset.state).toBe('rest');
    expect(el.dataset.platform).toBe('macos');
    expect(button(el, 'maximize').getAttribute('aria-label')).toBe('Maximize');
    expect(button(el, 'maximize').getAttribute('aria-expanded')).toBe('false');
  });

  it('falls back to windows for a platform it does not know', () => {
    const el = makeWindow();
    el.setAttribute('data-platform', 'beos');
    platformWindow(el);
    expect(el.dataset.platform).toBe('windows');
  });

  it('parks the window minimized and brings it back on its own', () => {
    vi.useFakeTimers();
    const el = makeWindow();
    const onMinimize = vi.fn();
    platformWindow(el, { onMinimize });
    button(el, 'minimize').click();
    expect(el.dataset.state).toBe('minimized');
    expect(onMinimize).toHaveBeenCalledTimes(1);
    vi.advanceTimersByTime(1_100);
    expect(el.dataset.state).toBe('rest');
  });

  it('keeps a window minimized without autoRestore, until minimize is pressed again', () => {
    vi.useFakeTimers();
    const el = makeWindow();
    platformWindow(el, { autoRestore: false });
    button(el, 'minimize').click();
    vi.advanceTimersByTime(5_000);
    expect(el.dataset.state).toBe('minimized');
    button(el, 'minimize').click();
    expect(el.dataset.state).toBe('rest');
  });

  it('toggles rest and maximized, swapping the maximize button’s label', () => {
    const el = makeWindow();
    const onMaximize = vi.fn();
    const onRestore = vi.fn();
    platformWindow(el, { onMaximize, onRestore });
    const max = button(el, 'maximize');
    max.click();
    expect(el.dataset.state).toBe('maximized');
    expect(max.getAttribute('aria-label')).toBe('Restore');
    expect(onMaximize).toHaveBeenCalledTimes(1);
    max.click();
    expect(el.dataset.state).toBe('rest');
    expect(max.getAttribute('aria-label')).toBe('Maximize');
    expect(onRestore).toHaveBeenCalledTimes(1);
  });

  it('announces close, hides the window, and revives it when autoRestore is on', () => {
    vi.useFakeTimers();
    const el = makeWindow();
    const onClose = vi.fn();
    const heard: string[] = [];
    el.addEventListener(WINDOW_CLOSE_EVENT, () => heard.push(WINDOW_CLOSE_EVENT));
    platformWindow(el, { onClose });
    button(el, 'close').click();
    expect(heard).toEqual([WINDOW_CLOSE_EVENT]);
    expect(onClose).toHaveBeenCalledTimes(1);
    expect(el.hasAttribute('data-closed')).toBe(true);
    vi.advanceTimersByTime(800);
    expect(el.hasAttribute('data-closed')).toBe(false);
    expect(el.dataset.state).toBe('rest');
  });

  it('leaves a closed window gone without autoRestore', () => {
    vi.useFakeTimers();
    const el = makeWindow();
    platformWindow(el, { autoRestore: false });
    button(el, 'close').click();
    vi.advanceTimersByTime(5_000);
    expect(el.hasAttribute('data-closed')).toBe(true);
  });
});

describe('snap layouts flyout', () => {
  it('arms after the hold and opens through data-open (the fallback path)', () => {
    vi.useFakeTimers();
    const el = makeWindow();
    platformWindow(el, { holdMs: 400 });
    const max = button(el, 'maximize');
    max.dispatchEvent(new Event('pointerdown'));
    vi.advanceTimersByTime(399);
    expect(menu(el).hasAttribute('data-open')).toBe(false);
    vi.advanceTimersByTime(1);
    expect(menu(el).hasAttribute('data-open')).toBe(true);
    expect(max.getAttribute('aria-expanded')).toBe('true');
  });

  it('disarms when the button is released before the hold elapses', () => {
    vi.useFakeTimers();
    const el = makeWindow();
    platformWindow(el, { holdMs: 400 });
    const max = button(el, 'maximize');
    max.dispatchEvent(new Event('pointerenter'));
    vi.advanceTimersByTime(200);
    max.dispatchEvent(new Event('pointerup'));
    vi.advanceTimersByTime(400);
    expect(menu(el).hasAttribute('data-open')).toBe(false);
  });

  it('closes on Escape, on leaving the snap wrap, and on a maximize click', () => {
    vi.useFakeTimers();
    const el = makeWindow();
    platformWindow(el, { holdMs: 100 });
    const max = button(el, 'maximize');
    const open = () => {
      max.dispatchEvent(new Event('pointerdown'));
      vi.advanceTimersByTime(100);
    };

    open();
    document.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape' }));
    expect(menu(el).hasAttribute('data-open')).toBe(false);

    open();
    el.querySelector<HTMLElement>('.nx-app-snap')!.dispatchEvent(new Event('mouseleave'));
    expect(menu(el).hasAttribute('data-open')).toBe(false);

    open();
    max.click();
    expect(menu(el).hasAttribute('data-open')).toBe(false);
    // The click after a hold only folds the flyout away — the state stands.
    expect(el.dataset.state).toBe('rest');
  });

  it('arms from the keyboard too: focus holds, blur cancels', () => {
    vi.useFakeTimers();
    const el = makeWindow();
    platformWindow(el, { holdMs: 100 });
    const max = button(el, 'maximize');
    max.dispatchEvent(new Event('focus'));
    vi.advanceTimersByTime(100);
    expect(menu(el).hasAttribute('data-open')).toBe(true);
    document.dispatchEvent(new KeyboardEvent('keydown', { key: 'Escape' }));

    max.dispatchEvent(new Event('focus'));
    vi.advanceTimersByTime(40);
    max.dispatchEvent(new Event('blur'));
    vi.advanceTimersByTime(200);
    expect(menu(el).hasAttribute('data-open')).toBe(false);
  });

  it('never opens when snap is off', () => {
    vi.useFakeTimers();
    const el = makeWindow();
    platformWindow(el, { snap: false, holdMs: 100 });
    button(el, 'maximize').dispatchEvent(new Event('pointerdown'));
    vi.advanceTimersByTime(300);
    expect(menu(el).hasAttribute('data-open')).toBe(false);
  });

  it('flashes the picked shape, then settles back', () => {
    vi.useFakeTimers();
    const el = makeWindow();
    platformWindow(el, { holdMs: 100 });
    button(el, 'maximize').dispatchEvent(new Event('pointerdown'));
    vi.advanceTimersByTime(100);
    zone(el, 'third').click();
    expect(el.dataset.shape).toBe('third');
    expect(menu(el).hasAttribute('data-open')).toBe(false);
    vi.advanceTimersByTime(950);
    expect(el.dataset.shape).toBeUndefined();
  });

  it('ignores zone shapes outside the vocabulary', () => {
    vi.useFakeTimers();
    const el = makeWindow();
    platformWindow(el, { holdMs: 100 });
    button(el, 'maximize').dispatchEvent(new Event('pointerdown'));
    vi.advanceTimersByTime(100);
    const stranger = document.createElement('button');
    stranger.setAttribute('data-shape', 'diagonal');
    menu(el).querySelector('.nx-app-snap-layout')!.appendChild(stranger);
    stranger.click();
    expect(el.dataset.shape).toBeUndefined();
  });
});

describe('platform switching', () => {
  it('reports outside data-platform changes through the platform event', async () => {
    const el = makeWindow();
    platformWindow(el);
    const seen: string[] = [];
    el.addEventListener(PLATFORM_EVENT, (event) => seen.push((event as CustomEvent<{ platform: string }>).detail.platform));

    el.setAttribute('data-platform', 'android');
    await vi.waitFor(() => expect(seen).toEqual(['android']));

    // The window's own platform and unknown shells stay quiet.
    el.setAttribute('data-platform', 'beos');
    el.setAttribute('data-platform', 'ios');
    await vi.waitFor(() => expect(seen).toEqual(['android', 'ios']));
  });

  it('does not re-announce the platform it already wears', async () => {
    const el = makeWindow();
    platformWindow(el, { platform: 'macos' });
    const heard = vi.fn();
    el.addEventListener(PLATFORM_EVENT, heard);
    el.setAttribute('data-platform', 'macos');
    await new Promise((resolve) => setTimeout(resolve, 20));
    expect(heard).not.toHaveBeenCalled();
  });
});

describe('cleanup', () => {
  it('stops the timers, the listeners and the fallback flyout', () => {
    vi.useFakeTimers();
    const el = makeWindow();
    const stop = platformWindow(el, { holdMs: 100 });
    const max = button(el, 'maximize');

    button(el, 'minimize').click();
    max.dispatchEvent(new Event('pointerdown'));
    vi.advanceTimersByTime(100);
    expect(menu(el).hasAttribute('data-open')).toBe(true);

    stop();

    // Timers were dropped: no restore, and a stray open flyout is tidied away.
    vi.advanceTimersByTime(5_000);
    expect(el.dataset.state).toBe('minimized');
    expect(menu(el).hasAttribute('data-open')).toBe(false);

    // Clicks no longer reach the state machine.
    button(el, 'maximize').click();
    expect(el.dataset.state).toBe('minimized');
  });
});
