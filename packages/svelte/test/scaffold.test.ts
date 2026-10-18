import { mount, unmount } from 'svelte';
import { describe, expect, it } from 'vitest';
import * as nx from '../src/index';
import NxButton from '../src/components/button.svelte';
import NxPricing from '../src/components/pricing.svelte';
import NxText from '../src/components/text.svelte';

describe('@nabuxai/ui-svelte scaffold', () => {
  it('exports the action and the fa/en binder', () => {
    expect(typeof nx.nxBehavior).toBe('function');
    expect(nx.NX_LANGS).toEqual(['fa', 'en']);
    expect(nx.nxTranslate('en', 'close')).toBe('Close');
    expect(nx.nxTranslate('fa', 'close')).toBe('بستن');
  });

  it('mounts the stub components', () => {
    for (const Component of [NxText, NxButton, NxPricing]) {
      const host = document.createElement('div');
      document.body.appendChild(host);
      const instance = mount(Component, { target: host });
      unmount(instance);
      host.remove();
    }
  });

  it('attaches, re-attaches and detaches a core behaviour', () => {
    const el = document.createElement('div');
    document.body.appendChild(el);
    let cleanups = 0;
    const behavior = (node: HTMLElement) => {
      expect(node).toBe(el);
      return () => {
        cleanups += 1;
      };
    };

    const action = nx.nxBehavior(el, { behavior, options: undefined, enabled: true });
    expect(cleanups).toBe(0);
    action.update({ behavior, options: undefined, enabled: true });
    expect(cleanups).toBe(1);
    action.destroy();
    expect(cleanups).toBe(2);
    el.remove();
  });
});
