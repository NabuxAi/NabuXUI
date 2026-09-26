import { type ComponentType, type ReactNode, createContext, forwardRef, useContext, useMemo } from 'react';
import { type Locale, type MessageKey, translate } from '@nabuxai/ui-core';

/**
 * Anything that renders an <a>: next/link, Inertia's Link, React Router's Link.
 * It receives `href` plus the usual anchor props.
 */
export type LinkComponent = ComponentType<Record<string, unknown> & { href: string; children?: ReactNode }>;

interface Settings {
  locale: Locale;
  link: LinkComponent | 'a';
}

const SettingsContext = createContext<Settings>({ locale: 'en', link: 'a' });

export interface NabuXUIProviderProps {
  /** Language for the components' own words (close buttons, empty states). Defaults to English. */
  locale?: Locale;
  /** The router's link, used for every internal href NabuXUI renders. */
  linkComponent?: LinkComponent;
  children: ReactNode;
}

export function NabuXUIProvider({ locale = 'en', linkComponent, children }: NabuXUIProviderProps) {
  const value = useMemo<Settings>(() => ({ locale, link: linkComponent ?? 'a' }), [locale, linkComponent]);
  return <SettingsContext.Provider value={value}>{children}</SettingsContext.Provider>;
}

export function useLocale(): Locale {
  return useContext(SettingsContext).locale;
}

/** `t('close')`, `t('page', { page: 2 })` in the provider's locale. */
export function useT(): (key: MessageKey, params?: Record<string, string | number>) => string {
  const locale = useLocale();
  return useMemo(() => (key: MessageKey, params?: Record<string, string | number>) => translate(locale, key, params), [locale]);
}

const isExternal = (href: string) => /^(https?:)?\/\//.test(href) || href.startsWith('mailto:') || href.startsWith('tel:') || href.startsWith('#');

type AnchorProps = React.AnchorHTMLAttributes<HTMLAnchorElement> & { href: string };

/**
 * An anchor that routes through the app's link component for internal paths and
 * stays a plain <a> for external, mail and in-page links.
 */
export const SmartLink = forwardRef<HTMLAnchorElement, AnchorProps>(function SmartLink({ href, ...rest }, ref) {
  const { link: Link } = useContext(SettingsContext);
  if (Link === 'a' || isExternal(href)) return <a ref={ref} href={href} {...rest} />;
  return <Link ref={ref} href={href} {...rest} />;
});
