/**
 * #/login · #/register · #/forgot-password — the auth view, outside the admin
 * shell (`.adm-auth` in admin.css centres it on the page). The AuthCard owns
 * its three panes; the route drives the mode, so the card's own pane
 * switching, the history and a shared link all agree, and going back works.
 * Submitting runs the card's loading state for a beat, then either lands in
 * the panel (login/register) or confirms the reset link (forgot).
 */
import { AuthCard, Button, toast, type AuthMode } from '@nabuxai/ui-react';
import { useStrings, useTr } from '../lang';
import { href, useRoute } from '../router';

export function AuthPage() {
  const s = useStrings();
  const a = s.pages.auth;
  const tr = useTr();
  const [route, go] = useRoute();
  const mode: AuthMode = route === 'register' ? 'register' : route === 'forgot' ? 'forgot' : 'login';

  // The card keeps its button busy until this settles; navigation unmounts it
  // first, which is fine — the promise's finally becomes a no-op.
  const submit = (pane: AuthMode) =>
    new Promise<void>((resolve) => {
      window.setTimeout(() => {
        if (pane === 'forgot') {
          toast.info(tr('پیوند بازنشانی به ایمیلتان فرستاده شد.', 'A reset link is on its way to your email.'));
        } else {
          toast.success(pane === 'login' ? tr('خوش آمدید!', 'Welcome back!') : tr('حسابتان آماده شد — خوش آمدید!', 'Your account is ready — welcome!'));
          go('dash');
        }
        resolve();
      }, 900);
    });

  return (
    <div className="adm-auth" style={{ rowGap: 'var(--nx-space-4)' }}>
      <AuthCard
        className="adm-auth-card"
        brand={s.app.brand}
        tagline={s.app.tagline}
        perks={[
          tr('سفارش‌ها و گفت‌وگوها در یک صفحه', 'Orders and conversations on one screen'),
          tr('گزارش‌های زندهٔ فروش', 'Live sales reports'),
          tr('دوزبانه و راست‌به‌چپ، روشن و تیره', 'Bilingual, RTL-native, light and dark'),
        ]}
        mode={mode}
        onModeChange={go}
        onSubmit={submit}
        labels={{
          register: a.registerTitle,
          forgot: a.forgotTitle,
          backToLogin: a.backToLogin,
        }}
      />
      <Button variant="ghost" size="sm" icon="grid" href={href('dash')}>
        {a.backToPanel}
      </Button>
    </div>
  );
}
