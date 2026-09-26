import type { SVGProps } from 'react';
import { type IconDefinition, type IconName, icons } from '@nabuxai/ui-core';

export interface IconProps extends Omit<SVGProps<SVGSVGElement>, 'name'> {
  name: IconName;
  /** Give the icon a name when it carries meaning on its own; otherwise it is hidden from assistive tech. */
  label?: string;
}

/** One of NabuXUI's built-in icons, drawn from the same paths the Blade components use. */
export function Icon({ name, label, className, ...rest }: IconProps) {
  const icon: IconDefinition = icons[name];
  const paint = icon.filled
    ? { fill: 'currentColor', stroke: 'none' }
    : { fill: 'none', stroke: 'currentColor', strokeLinecap: 'round' as const, strokeLinejoin: 'round' as const };

  return (
    <svg
      className={className ? `nx-icon ${className}` : 'nx-icon'}
      viewBox="0 0 24 24"
      {...paint}
      {...(label ? { role: 'img', 'aria-label': label } : { 'aria-hidden': true })}
      {...(icon.directional ? { 'data-directional': '' } : {})}
      {...rest}
    >
      <path d={icon.d} />
    </svg>
  );
}

export type { IconName };
