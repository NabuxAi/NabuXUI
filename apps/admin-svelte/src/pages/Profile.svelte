<script lang="ts">
  /**
   * #/profile — the account page: personal details (with a working photo
   * picker — a hidden file input feeding an object-URL avatar, revoked when the
   * page goes), a password form, and the active devices with per-device
   * sign-out. Everything is local state; saving runs the button's own status
   * marks (loading → success) and lands a toast.
   */
  import { toast, type IconName } from '@nabuxai/ui-core';
  import { strings, tr } from '../store.svelte';
  import Avatar from '../lib/Avatar.svelte';
  import NxIcon from '../lib/NxIcon.svelte';

  type ButtonStatus = 'idle' | 'loading' | 'success';

  const s = $derived(strings());
  const p = $derived(s.pages.profile);

  let profileState = $state<ButtonStatus>('idle');
  let passwordState = $state<ButtonStatus>('idle');

  /** The seeded devices, by id — the labels follow the panel language. */
  type DeviceId = 'mac' | 'iphone' | 'windows';
  const DEVICE: Record<DeviceId, { icon: IconName; current?: boolean }> = {
    mac: { icon: 'command', current: true },
    iphone: { icon: 'globe' },
    windows: { icon: 'grid' },
  };
  const DEVICE_LABEL = $derived<Record<DeviceId, { device: string; lastActive: string }>>({
    mac: { device: p.deviceMac, lastActive: s.common.online },
    iphone: { device: p.deviceIphone, lastActive: p.whenIphone },
    windows: { device: p.deviceWindows, lastActive: p.whenWindows },
  });

  let signedIn = $state<DeviceId[]>(['mac', 'iphone', 'windows']);
  const sessions = $derived(signedIn.map((id) => ({ id, ...DEVICE[id], ...DEVICE_LABEL[id] })));

  // The photo picker: a hidden native input behind the button; the preview is
  // an object URL. The effect below revokes the previous URL on every change
  // (its cleanup runs first, on the value that run saw) and the last one when
  // the page goes.
  let fileInput: HTMLInputElement;
  let photoUrl: string | undefined = $state(undefined);

  $effect(() => {
    const url = photoUrl;
    return () => {
      if (url) URL.revokeObjectURL(url);
    };
  });

  const pickPhoto = () => fileInput?.click();

  const onPhoto = (event: Event) => {
    const next = (event.target as HTMLInputElement).files?.[0];
    if (!next) return;
    photoUrl = URL.createObjectURL(next);
  };

  /** The save flow every form here shares: busy → check → toast → idle. */
  function save(flow: 'profile' | 'password') {
    const status = flow === 'profile' ? profileState : passwordState;
    if (status !== 'idle') return;
    const set = (next: ButtonStatus) => (flow === 'profile' ? (profileState = next) : (passwordState = next));
    set('loading');
    setTimeout(() => {
      set('success');
      toast.success(s.common.saved);
      setTimeout(() => set('idle'), 1400);
    }, 700);
  }

  const revoke = (session: { id: DeviceId; device: string }) => {
    signedIn = signedIn.filter((id) => id !== session.id);
    toast.success(tr(`از «${session.device}» خارج شدید`, `Signed out of “${session.device}”`));
  };
</script>

<div class="adm-profile">
  <section class="nx-card adm-profile-main">
    <div class="nx-card-header">
      <span class="nx-card-icon"><NxIcon name="user" /></span>
      <h2 class="nx-card-title">{p.personal}</h2>
    </div>
    <div class="nx-card-body">
      <form class="adm-form" onsubmit={(event) => { event.preventDefault(); save('profile'); }}>
        <div class="adm-avatar-row">
          <Avatar name={s.app.user.name} src={photoUrl} size="xl" status="online" />
          <div class="adm-avatar-actions">
            <button type="button" class="nx-button" data-variant="outline" data-size="sm" onclick={pickPhoto}>
              <span class="nx-button-label">
                <NxIcon name="image" />
                <span class="nx-button-text">{p.changeAvatar}</span>
              </span>
            </button>
            <input type="file" accept="image/*" hidden bind:this={fileInput} onchange={onPhoto} />
            <span class="adm-avatar-hint">{p.avatarHint}</span>
          </div>
        </div>
        <div class="adm-form-grid">
          <div class="nx-field">
            <label class="nx-label" for="profile-name">{p.name}</label>
            <input id="profile-name" name="name" class="nx-input" value={s.app.user.name} autocomplete="name" required />
          </div>
          <div class="nx-field">
            <label class="nx-label" for="profile-email">{p.email}</label>
            <input id="profile-email" name="email" class="nx-input" type="email" dir="ltr" value="hossein@nabu.shop" autocomplete="email" readonly />
          </div>
          <div class="nx-field">
            <label class="nx-label" for="profile-role">{p.role}</label>
            <input id="profile-role" name="role" class="nx-input" value={s.app.user.role} readonly />
          </div>
          <div class="nx-field">
            <label class="nx-label" for="profile-phone">{p.phone}</label>
            <input id="profile-phone" name="phone" class="nx-input" type="tel" dir="ltr" value="+98 912 000 0000" autocomplete="tel" />
          </div>
          <div class="nx-field adm-form-span">
            <label class="nx-label" for="profile-tz">{p.timezone}</label>
            <select id="profile-tz" name="timezone" class="nx-select">
              <option value="Asia/Tehran">{s.common.tzTehran}</option>
              <option value="Europe/Berlin">{s.common.tzBerlin}</option>
              <option value="UTC">UTC</option>
            </select>
          </div>
          <div class="nx-field adm-form-span">
            <label class="nx-label" for="profile-bio">{p.bio}</label>
            <textarea id="profile-bio" name="bio" class="nx-textarea" rows="3">{p.bioDefault}</textarea>
          </div>
        </div>
        <div class="adm-form-actions">
          <button type="submit" class="nx-button" data-variant="primary" data-status={profileState === 'idle' ? undefined : profileState}>
            <span class="nx-button-label">
              <NxIcon name="check" />
              <span class="nx-button-text">{s.common.save}</span>
              {#if profileState !== 'idle'}
                <span class="nx-button-status" aria-hidden="true">
                  <span class="nx-spinner" data-size="sm"></span>
                  <svg class="nx-icon nx-status-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12.5l4.5 4.5L19 7.5" pathLength="1" />
                  </svg>
                </span>
              {/if}
            </span>
          </button>
        </div>
      </form>
    </div>
  </section>

  <div class="adm-profile-side">
    <section class="nx-card">
      <div class="nx-card-header">
        <span class="nx-card-icon"><NxIcon name="lock" /></span>
        <h2 class="nx-card-title">{p.security}</h2>
      </div>
      <div class="nx-card-body">
        <form class="adm-form" onsubmit={(event) => { event.preventDefault(); save('password'); }}>
          <div class="nx-field">
            <label class="nx-label" for="password-current">{s.common.currentPassword}</label>
            <input id="password-current" class="nx-input" type="password" dir="ltr" autocomplete="current-password" required />
          </div>
          <div class="nx-field">
            <label class="nx-label" for="password-next">{s.common.newPassword}</label>
            <input id="password-next" class="nx-input" type="password" dir="ltr" autocomplete="new-password" required minlength="8" />
            <p class="nx-hint">{p.newPasswordHint}</p>
          </div>
          <div class="adm-form-actions">
            <button type="submit" class="nx-button" data-variant="primary" data-status={passwordState === 'idle' ? undefined : passwordState}>
              <span class="nx-button-label">
                <NxIcon name="check" />
                <span class="nx-button-text">{p.updatePassword}</span>
                {#if passwordState !== 'idle'}
                  <span class="nx-button-status" aria-hidden="true">
                    <span class="nx-spinner" data-size="sm"></span>
                    <svg class="nx-icon nx-status-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <path d="M5 12.5l4.5 4.5L19 7.5" pathLength="1" />
                    </svg>
                  </span>
                {/if}
              </span>
            </button>
          </div>
        </form>
      </div>
    </section>

    <section class="nx-card">
      <div class="nx-card-header">
        <span class="nx-card-icon"><NxIcon name="shield" /></span>
        <h2 class="nx-card-title">{p.sessions}</h2>
      </div>
      <div class="nx-card-body">
        <ul class="adm-sessions">
          {#each sessions as session (session.id)}
            <li class="adm-session">
              <span class="adm-session-icon" aria-hidden="true">
                <NxIcon name={session.icon} />
              </span>
              <span class="adm-session-meta">
                <span class="adm-session-device">
                  {session.device}
                  {#if session.current}<span class="nx-badge" data-tone="accent">{p.thisDevice}</span>{/if}
                </span>
                <span class="adm-session-when">{p.lastActive}: {session.lastActive}</span>
              </span>
              <button
                type="button"
                class="nx-button"
                data-variant="ghost"
                data-size="sm"
                disabled={session.current}
                title={session.current ? p.currentDeviceHint : undefined}
                onclick={() => revoke(session)}
              >
                <span class="nx-button-label">
                  <NxIcon name="lock" />
                  <span class="nx-button-text">{p.revoke}</span>
                </span>
              </button>
            </li>
          {/each}
        </ul>
      </div>
    </section>
  </div>
</div>

<style>
  .adm-profile {
    display: grid;
    gap: var(--nx-space-5);
    align-items: start;
  }

  @media (min-width: 72rem) {
    .adm-profile {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .adm-profile-main {
      grid-column: 1 / -1;
    }
  }

  .adm-profile-side {
    display: grid;
    gap: var(--nx-space-5);
  }

  .adm-avatar-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: var(--nx-space-4);
  }

  .adm-avatar-actions {
    display: grid;
    gap: var(--nx-space-1);
    justify-items: start;
  }

  .adm-avatar-hint {
    color: var(--nx-text-subtle);
    font-size: var(--nx-text-xs);
  }

  .adm-sessions {
    display: grid;
    gap: var(--nx-space-3);
    margin: 0;
    padding: 0;
    list-style: none;
  }

  .adm-session {
    display: flex;
    align-items: center;
    gap: var(--nx-space-3);
  }

  .adm-session-icon {
    display: grid;
    place-items: center;
    inline-size: 2.25rem;
    block-size: 2.25rem;
    border: 1px solid var(--nx-border);
    border-radius: var(--nx-radius-md);
    background: var(--nx-surface-2);
    color: var(--nx-text-muted);
  }

  .adm-session-meta {
    display: grid;
    gap: 0.125rem;
    min-inline-size: 0;
  }

  .adm-session-device {
    display: flex;
    align-items: center;
    gap: var(--nx-space-2);
    font-weight: 600;
  }

  .adm-session-when {
    color: var(--nx-text-subtle);
    font-size: var(--nx-text-xs);
  }
</style>
