import { createApp, defineComponent, h } from 'vue';
import { describe, expect, it } from 'vitest';
import * as nx from '../src/index';
import NxButton from '../src/components/button.vue';
import NxHero from '../src/components/hero.vue';
import NxText from '../src/components/text.vue';

describe('@nabuxai/ui-vue scaffold', () => {
  it('exports the composable and the fa/en binder', () => {
    expect(typeof nx.useNxBehavior).toBe('function');
    expect(nx.NX_LANGS).toEqual(['fa', 'en']);
    expect(nx.nxTranslate('en', 'close')).toBe('Close');
    expect(nx.nxTranslate('fa', 'close')).toBe('بستن');
  });

  it('mounts the stub SFCs', () => {
    for (const Component of [NxText, NxButton, NxHero]) {
      const host = document.createElement('div');
      document.body.appendChild(host);
      const app = createApp(Component);
      app.mount(host);
      app.unmount();
      host.remove();
    }
  });

  it('attaches a behaviour on mount and detaches it on unmount', () => {
    const attached: string[] = [];
    let cleanups = 0;
    const behavior = (el: HTMLElement) => {
      attached.push(el.tagName);
      return () => {
        cleanups += 1;
      };
    };

    const host = document.createElement('div');
    document.body.appendChild(host);
    const app = createApp(
      defineComponent({
        setup() {
          const seat = nx.useNxBehavior<HTMLElement, undefined>(behavior, undefined);
          return () => h('div', { ref: seat });
        },
      }),
    );
    app.mount(host);
    expect(attached).toEqual(['DIV']);
    expect(cleanups).toBe(0);
    app.unmount();
    expect(cleanups).toBe(1);
    host.remove();
  });
});
