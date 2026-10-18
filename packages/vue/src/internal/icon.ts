/**
 * One of NabuXUI's built-in icons — the Vue counterpart of ui-react's
 * `internal/icon.tsx`, drawn from the same core paths, with the same DOM.
 * Served both named and default so every family's parts can seat it.
 */
import { type FunctionalComponent, h, type PropType } from 'vue';
import { type IconDefinition, type IconName, icons } from '@nabuxai/ui-core';

export interface NxIconProps {
  name: IconName;
  /** Give the icon a name when it carries meaning on its own; otherwise hidden from assistive tech. */
  label?: string;
}

export const NxIcon: FunctionalComponent<NxIconProps> = (props) => {
  const icon: IconDefinition = icons[props.name];
  const paint = icon.filled
    ? { fill: 'currentColor', stroke: 'none' }
    : { fill: 'none', stroke: 'currentColor', 'stroke-linecap': 'round' as const, 'stroke-linejoin': 'round' as const };

  return h('svg', {
    class: 'nx-icon',
    viewBox: '0 0 24 24',
    ...(props.label ? { role: 'img', 'aria-label': props.label } : { 'aria-hidden': 'true' }),
    ...(icon.directional ? { 'data-directional': '' } : {}),
    ...paint,
  }, [h('path', { d: icon.d })]);
};

NxIcon.props = {
  name: { type: String as PropType<IconName>, required: true },
  label: { type: String, default: undefined },
};

export default NxIcon;
