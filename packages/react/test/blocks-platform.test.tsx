// @vitest-environment happy-dom
/**
 * The platform app window: one DOM, four shells. Each platform renders with
 * its own chrome (desktop caption + sidebar, mobile tab bar, the Android FAB),
 * the caption buttons say their words and swap maximize↔restore with the
 * state, and the Windows snap flyout opens through the no-popover fallback —
 * happy-dom has `popover` on the prototype but no `showPopover`, so the core
 * behaviour's data-open branch is the only one these tests can reach (the
 * native-popover branch stays uncovered on purpose, like every other popover
 * block in this repo).
 */
import { afterEach, describe, expect, it, vi } from 'vitest';
import { cleanup, fireEvent, render, screen, waitFor, within } from '@testing-library/react';
import { act } from 'react';
import { messages, platforms } from '@nabuxai/ui-core';
import { AppWindow } from '../src/blocks/platform';

afterEach(() => {
  cleanup();
  vi.useRealTimers();
});

const nav = [
  { id: 'home', label: 'Home', icon: 'home' as const },
  { id: 'files', label: 'Files', icon: 'folder' as const, badge: 3 },
  { id: 'stats', label: 'Stats', icon: 'chart' as const },
];

describe('AppWindow', () => {
  it('renders one window per platform side by side, each wearing its own shell', () => {
    const { container } = render(
      <>
        {platforms.map((platform) => (
          <AppWindow key={platform} platform={platform} title={platform} />
        ))}
      </>,
    );
    const windows = Array.from(container.querySelectorAll('.nx-app-window'));
    expect(windows).toHaveLength(4);
    expect(new Set(windows.map((el) => el.getAttribute('data-platform')))).toEqual(new Set(['windows', 'macos', 'android', 'ios']));
  });

  it('gives the desktop shells the sidebar and caption, the mobile ones the tab bar', () => {
    const { container } = render(
      <>
        {platforms.map((platform) => (
          <AppWindow key={platform} platform={platform} nav={nav} title="App" />
        ))}
      </>,
    );
    const window = (platform: string) => container.querySelector(`.nx-app-window[data-platform="${platform}"]`)!;
    for (const desktop of ['windows', 'macos']) {
      // within() wraps queries in getBy* helpers without querySelector — the
      // sidebar presence check runs on the raw element.
      expect(window(desktop).querySelector('.nx-app-sidebar')).toBeTruthy();
      expect(window(desktop).querySelector('.nx-app-caption')).toBeTruthy();
      expect(window(desktop).querySelector('.nx-app-tabbar')).toBeNull();
    }
    for (const mobile of ['android', 'ios']) {
      expect(window(mobile).querySelector('.nx-app-sidebar')).toBeNull();
      expect(window(mobile).querySelector('.nx-app-caption')).toBeNull();
      const tabbar = window(mobile).querySelector('.nx-app-tabbar')!;
      expect(tabbar).toBeTruthy();
      expect(tabbar.querySelectorAll('.nx-app-tab')).toHaveLength(nav.length);
    }
  });

  it('shows the FAB only on Android', () => {
    const { container } = render(
      <>
        {platforms.map((platform) => (
          <AppWindow key={platform} platform={platform} fab label="Compose" />
        ))}
      </>,
    );
    expect(container.querySelectorAll('.nx-app-fab')).toHaveLength(1);
    expect(container.querySelector('.nx-app-window[data-platform="android"] .nx-app-fab')).toBeTruthy();
  });

  it('reports the picked nav item and moves aria-current with it', () => {
    const onActiveChange = vi.fn();
    render(<AppWindow platform="windows" nav={nav} defaultActive="home" navLabel="Main" onActiveChange={onActiveChange} />);
    const sidebar = screen.getByRole('navigation', { name: 'Main' });
    const active = () => within(sidebar).getByText('Home').closest('button');
    expect(active()?.getAttribute('aria-current')).toBe('page');
    fireEvent.click(within(sidebar).getByText('Stats'));
    expect(onActiveChange).toHaveBeenCalledWith('stats');
    expect(active()?.getAttribute('aria-current')).toBeNull();
    expect(within(sidebar).getByText('Stats').closest('button')?.getAttribute('aria-current')).toBe('page');
  });

  it('labels the caption buttons and swaps maximize↔restore with the state', async () => {
    const { container } = render(<AppWindow platform="windows" title="Notes" />);
    const root = container.querySelector('.nx-app-window')!;
    const minimize = screen.getByRole('button', { name: messages.en.minimize });
    const close = screen.getByRole('button', { name: messages.en.close });
    expect(minimize).toBeTruthy();
    expect(close).toBeTruthy();

    fireEvent.click(minimize);
    expect(root.getAttribute('data-state')).toBe('minimized');
    // autoRestore brings a minimized window back — after 1.1s, so past the
    // waitFor default of 1s.
    await waitFor(() => expect(root.getAttribute('data-state')).not.toBe('minimized'), { timeout: 2500 });

    let maximize = screen.getByRole('button', { name: messages.en.maximize });
    expect(maximize).toBeTruthy();
    fireEvent.click(maximize);
    await waitFor(() => expect(root.getAttribute('data-state')).toBe('maximized'));
    maximize = await waitFor(() => screen.getByRole('button', { name: messages.en.restore }));
    expect(maximize.getAttribute('data-kind')).toBe('maximize');

    fireEvent.click(maximize);
    await waitFor(() => expect(root.getAttribute('data-state')).toBe('rest'));
    expect(screen.getByRole('button', { name: messages.en.maximize })).toBeTruthy();
  });

  it('announces the close through the window event', () => {
    const { container } = render(<AppWindow platform="windows" title="Notes" />);
    const root = container.querySelector('.nx-app-window')!;
    const heard = vi.fn();
    root.addEventListener('nx-window-close', heard);
    fireEvent.click(screen.getByRole('button', { name: messages.en.close }));
    expect(heard).toHaveBeenCalledOnce();
  });

  it('arms the snap flyout after the hold and opens it through the data-open fallback', () => {
    vi.useFakeTimers();
    const { container } = render(<AppWindow platform="windows" title="Notes" />);
    const menu = container.querySelector('.nx-app-snap-menu')!;
    expect(menu).toBeTruthy();
    expect(menu.getAttribute('aria-label')).toBe(messages.en.snapLayouts);
    expect(menu.hasAttribute('data-open')).toBe(false);

    const maximize = screen.getByRole('button', { name: messages.en.maximize });
    fireEvent.focus(maximize);
    act(() => vi.advanceTimersByTime(500));
    expect(menu.hasAttribute('data-open')).toBe(true);
  });

  it('follows a data-platform flip made outside React', async () => {
    const onPlatformChange = vi.fn();
    const { container } = render(<AppWindow defaultPlatform="windows" title="App" onPlatformChange={onPlatformChange} />);
    const root = container.querySelector('.nx-app-window')!;
    act(() => {
      root.setAttribute('data-platform', 'macos');
    });
    await waitFor(() => expect(onPlatformChange).toHaveBeenCalledWith('macos'));
    // The adopted shell re-renders the desktop chrome.
    await waitFor(() => expect(root.querySelector('.nx-app-caption')).toBeTruthy());
  });
});
