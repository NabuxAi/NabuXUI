/**
 * /#/settings — store configuration and the user's own preferences. The
 * ThemeSwitch writes the shared core theme store by itself; the LanguageMenu
 * persists the app's saved pick (`nabuxai.admin.lang`, the key lang.tsx
 * reads at mount) and reloads so the whole panel — shell included — lands in
 * the new language; the hash survives the reload, so this page stays put.
 * The danger zone asks for the delete word before its button wakes up, and
 * "deleting" the demo store simply ends the session at the sign-in view.
 */
import { useState, type FormEvent } from 'react';
import {
  Button,
  Card,
  Dialog,
  Field,
  Input,
  LanguageMenu,
  Select,
  Switch,
  ThemeSwitch,
  toast,
  type ButtonStatus,
} from '@nabuxai/ui-react';
import { useLang, useStrings } from '../lang';
import { useRoute } from '../router';

export function SettingsPage() {
  const s = useStrings();
  const set = s.pages.settings;
  const lang = useLang();
  const [, go] = useRoute();

  const [saveState, setSaveState] = useState<ButtonStatus>('idle');
  const [twoFactor, setTwoFactor] = useState(true);
  const [digest, setDigest] = useState(true);
  const [push, setPush] = useState(false);
  const [weekly, setWeekly] = useState(true);
  const [passwordOpen, setPasswordOpen] = useState(false);
  const [confirm, setConfirm] = useState('');
  const deleteWord = set.deleteWord;
  const armed = confirm.trim().toLowerCase() === deleteWord;

  const saveGeneral = (event: FormEvent<HTMLFormElement>) => {
    event.preventDefault();
    if (saveState !== 'idle') return;
    setSaveState('loading');
    window.setTimeout(() => {
      setSaveState('success');
      toast.success(s.common.saved);
      window.setTimeout(() => setSaveState('idle'), 1400);
    }, 700);
  };

  // The panel's language lives in App state; the one bridge a page has is the
  // saved pick in localStorage + a reload — the same key readLang() reads.
  const changeLang = (id: string) => {
    try {
      localStorage.setItem('nabuxai.admin.lang', id === 'en' ? 'en' : 'fa');
    } catch {
      /* storage closed: the menu rolls back below */
    }
    window.location.reload();
  };

  const signOutEverywhere = () => toast.info(set.signedOutEverywhere);

  const deleteStore = () => {
    go('login');
    toast.warning(set.storeDeleted);
  };

  return (
    <div className="adm-settings">
      <Card className="adm-settings-general" title={set.general} titleAs="h2" icon="settings">
        <form className="adm-form" onSubmit={saveGeneral}>
          <div className="adm-form-grid">
            <Field label={set.workspaceName}>
              <Input name="workspace" defaultValue={s.app.storeName} required />
            </Field>
            <Field label={set.workspaceUrl}>
              <Input name="url" dir="ltr" defaultValue="nabu.shop" startAddon="https://" />
            </Field>
            <Field label={set.timezoneField} className="adm-form-span">
              <Select
                name="timezone"
                defaultValue="Asia/Tehran"
                options={[
                  { value: 'Asia/Tehran', label: s.common.tzTehran },
                  { value: 'Europe/Berlin', label: s.common.tzBerlin },
                  { value: 'UTC', label: 'UTC' },
                ]}
              />
            </Field>
          </div>
          <div className="adm-form-actions">
            <Button type="submit" variant="primary" icon="check" status={saveState}>{s.common.save}</Button>
          </div>
        </form>
      </Card>

      <Card title={set.appearance} titleAs="h2" icon="sun">
        <div className="adm-row">
          <span className="adm-row-text">
            <span className="adm-row-label">{set.theme}</span>
            <span className="adm-row-hint">{set.themeHint}</span>
          </span>
          <ThemeSwitch label={set.theme} />
        </div>
      </Card>

      <Card title={set.languageSection} titleAs="h2" icon="globe">
        <div className="adm-row">
          <span className="adm-row-text">
            <span className="adm-row-label">{set.languageFa} · {set.languageEn}</span>
            <span className="adm-row-hint">{set.languageHint}</span>
          </span>
          <LanguageMenu
            label={set.languageSection}
            value={lang}
            onValueChange={changeLang}
            languages={[
              { id: 'fa', name: set.languageFa, short: 'FA' },
              { id: 'en', name: set.languageEn, short: 'EN' },
            ]}
          />
        </div>
      </Card>

      <Card title={set.notifications} titleAs="h2" icon="bell">
        <div className="adm-choices">
          <Switch label={set.emailDigest} checked={digest} onCheckedChange={setDigest} />
          <Switch label={set.pushAlerts} checked={push} onCheckedChange={setPush} />
          <Switch label={set.weeklyReport} checked={weekly} onCheckedChange={setWeekly} />
        </div>
      </Card>

      <Card title={set.security} titleAs="h2" icon="lock">
        <div className="adm-choices">
          <Switch label={set.twoFactor} checked={twoFactor} onCheckedChange={setTwoFactor} />
          <div className="adm-row">
            <span className="adm-row-text">
              <span className="adm-row-label">{set.password}</span>
            </span>
            <Dialog
              open={passwordOpen}
              onOpenChange={setPasswordOpen}
              trigger={<Button size="sm" variant="outline" icon="lock">{s.common.edit}</Button>}
              title={set.password}
              description={set.passwordDescription}
              footer={
                <>
                  <Button variant="ghost" onClick={() => setPasswordOpen(false)}>{s.common.cancel}</Button>
                  <Button
                    variant="primary"
                    icon="check"
                    onClick={() => {
                      setPasswordOpen(false);
                      toast.success(set.passwordUpdated);
                    }}
                  >
                    {s.common.confirm}
                  </Button>
                </>
              }
            >
              <Field label={s.common.currentPassword}>
                <Input type="password" dir="ltr" autoComplete="current-password" required />
              </Field>
              <Field label={s.common.newPassword}>
                <Input type="password" dir="ltr" autoComplete="new-password" required minLength={8} />
              </Field>
            </Dialog>
          </div>
          <div className="adm-row">
            <span className="adm-row-text">
              <span className="adm-row-label">{set.signOutDevices}</span>
            </span>
            <Button size="sm" variant="outline" icon="lock" onClick={signOutEverywhere}>{s.common.confirm}</Button>
          </div>
        </div>
      </Card>

      <Card className="adm-settings-danger" title={set.danger} titleAs="h2" icon="alert-triangle" description={set.deleteBody}>
        <div className="adm-danger">
          <Field label={set.typeToDelete} hint={set.confirmWord}>
            <Input value={confirm} onChange={(event) => setConfirm(event.target.value)} dir="ltr" autoComplete="off" placeholder={deleteWord} />
          </Field>
          <Button variant="danger" icon="trash" disabled={!armed} onClick={deleteStore}>
            {set.deleteWorkspace}
          </Button>
        </div>
      </Card>
    </div>
  );
}
