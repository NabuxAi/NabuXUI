/**
 * /#/profile — the account page: personal details (with a working photo
 * picker — a hidden file input feeding an object-URL avatar, revoked on
 * unmount), a password form, and the active devices with per-device sign-out.
 * Everything is local state; saving runs the button's own status marks
 * (loading → success) and lands a toast.
 */
import { useEffect, useRef, useState, type ChangeEvent, type FormEvent } from 'react';
import { Avatar, Button, Card, Field, Icon, Input, Select, Textarea, toast, type ButtonStatus, type IconName } from '@nabuxai/ui-react';
import { useStrings, useTr } from '../lang';

interface Session {
  id: string;
  icon: IconName;
  device: string;
  lastActive: string;
  current?: boolean;
}

/** The save button every form on this page shares: busy → check → toast. */
function SaveButton({ label, saved }: { label: string; saved: string }) {
  const [status, setStatus] = useState<ButtonStatus>('idle');
  const timers = useRef<number[]>([]);
  useEffect(() => () => timers.current.forEach((id) => window.clearTimeout(id)), []);

  const save = () => {
    if (status !== 'idle') return;
    setStatus('loading');
    timers.current.push(
      window.setTimeout(() => {
        setStatus('success');
        toast.success(saved);
        timers.current.push(window.setTimeout(() => setStatus('idle'), 1400));
      }, 700),
    );
  };

  return (
    <Button type="submit" variant="primary" icon="check" status={status} onClick={save}>
      {label}
    </Button>
  );
}

export function ProfilePage() {
  const s = useStrings();
  const p = s.pages.profile;
  const tr = useTr();

  const [avatar, setAvatar] = useState<string>();
  const [sessions, setSessions] = useState<Session[]>(() => [
    { id: 'mac', icon: 'command', device: tr('کروم · مک‌اواس', 'Chrome · macOS'), lastActive: s.common.online, current: true },
    { id: 'iphone', icon: 'globe', device: tr('سافاری · آیفون', 'Safari · iPhone'), lastActive: tr('۱۸ دقیقه پیش', '18 minutes ago') },
    { id: 'windows', icon: 'grid', device: tr('فایرفاکس · ویندوز', 'Firefox · Windows'), lastActive: tr('دو روز پیش', 'two days ago') },
  ]);

  // The photo picker: a hidden native input behind the button; the preview is
  // an object URL that dies with the page.
  const file = useRef<HTMLInputElement>(null);
  const url = useRef<string | undefined>(undefined);
  useEffect(() => () => { if (url.current) URL.revokeObjectURL(url.current); }, []);
  const pick = (event: ChangeEvent<HTMLInputElement>) => {
    const next = event.target.files?.[0];
    if (!next) return;
    if (url.current) URL.revokeObjectURL(url.current);
    url.current = URL.createObjectURL(next);
    setAvatar(url.current);
  };

  const revoke = (session: Session) => {
    setSessions((list) => list.filter((entry) => entry.id !== session.id));
    toast.success(tr(`از «${session.device}» خارج شدید`, `Signed out of “${session.device}”`));
  };

  const submit = (event: FormEvent<HTMLFormElement>) => event.preventDefault();

  return (
    <div className="adm-profile">
      <Card className="adm-profile-main" title={p.personal} titleAs="h2" icon="user">
        <form className="adm-form" onSubmit={submit}>
          <div className="adm-avatar-row">
            <Avatar name={s.app.user.name} src={avatar} size="xl" status="online" />
            <div className="adm-avatar-actions">
              <Button size="sm" icon="image" onClick={() => file.current?.click()}>{p.changeAvatar}</Button>
              <input ref={file} type="file" accept="image/*" hidden onChange={pick} />
              <span className="adm-avatar-hint">{tr('PNG یا JPG، حداکثر ۲ مگابایت', 'PNG or JPG, up to 2 MB')}</span>
            </div>
          </div>
          <div className="adm-form-grid">
            <Field label={p.name}>
              <Input name="name" defaultValue={s.app.user.name} autoComplete="name" required />
            </Field>
            <Field label={p.email}>
              <Input name="email" type="email" dir="ltr" defaultValue="hossein@nabu.shop" autoComplete="email" readOnly />
            </Field>
            <Field label={p.role}>
              <Input name="role" defaultValue={s.app.user.role} readOnly />
            </Field>
            <Field label={p.phone}>
              <Input name="phone" type="tel" dir="ltr" defaultValue="+98 912 000 0000" autoComplete="tel" />
            </Field>
            <Field label={p.timezone} className="adm-form-span">
              <Select
                name="timezone"
                defaultValue="Asia/Tehran"
                options={[
                  { value: 'Asia/Tehran', label: tr('تهران (GMT+3:30)', 'Tehran (GMT+3:30)') },
                  { value: 'Europe/Berlin', label: tr('برلین (GMT+2:00)', 'Berlin (GMT+2:00)') },
                  { value: 'UTC', label: 'UTC' },
                ]}
              />
            </Field>
            <Field label={p.bio} className="adm-form-span">
              <Textarea name="bio" rows={3} maxRows={6} defaultValue={tr('مدیر سیستم فروشگاه نابو؛ علاقه‌مند به ابزارهای خوب و تنظیمات ریز.', 'Nabu Store’s system admin; fond of good tooling and careful tuning.')} />
            </Field>
          </div>
          <div className="adm-form-actions">
            <SaveButton label={s.common.save} saved={s.common.saved} />
          </div>
        </form>
      </Card>

      <div className="adm-profile-side">
        <Card title={p.security} titleAs="h2" icon="lock">
          <form className="adm-form" onSubmit={submit}>
            <Field label={tr('رمز فعلی', 'Current password')}>
              <Input name="current" type="password" dir="ltr" autoComplete="current-password" required />
            </Field>
            <Field label={tr('رمز تازه', 'New password')} hint={tr('حداقل ۸ نویسه، با عدد و نویسهٔ بزرگ', 'At least 8 characters, with a digit and an uppercase')}>
              <Input name="next" type="password" dir="ltr" autoComplete="new-password" required minLength={8} />
            </Field>
            <div className="adm-form-actions">
              <SaveButton label={tr('به‌روزرسانی رمز', 'Update password')} saved={s.common.saved} />
            </div>
          </form>
        </Card>

        <Card title={p.sessions} titleAs="h2" icon="shield">
          <ul className="adm-sessions">
            {sessions.map((session) => (
              <li key={session.id} className="adm-session">
                <span className="adm-session-icon" aria-hidden="true">
                  <Icon name={session.icon} />
                </span>
                <span className="adm-session-meta">
                  <span className="adm-session-device">
                    {session.device}
                    {session.current && <span className="adm-session-now">{tr('این دستگاه', 'This device')}</span>}
                  </span>
                  <span className="adm-session-when">{p.lastActive}: {session.lastActive}</span>
                </span>
                <Button
                  size="sm"
                  variant="ghost"
                  icon="lock"
                  disabled={session.current}
                  title={session.current ? tr('برای دستگاه فعلی، از تنظیمات خارج شوید', 'For the current device, sign out from settings') : undefined}
                  onClick={() => revoke(session)}
                >
                  {p.revoke}
                </Button>
              </li>
            ))}
          </ul>
        </Card>
      </div>
    </div>
  );
}
