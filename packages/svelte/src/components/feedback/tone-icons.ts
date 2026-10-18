import type { IconName } from '@nabuxai/ui-core';

/** The icon each feedback tone carries — shared by the toaster and the alert. */
export const TONE_ICON: Record<string, IconName> = {
  neutral: 'bell',
  accent: 'sparkles',
  success: 'check-circle',
  warning: 'alert-triangle',
  danger: 'alert-circle',
  info: 'info',
};
