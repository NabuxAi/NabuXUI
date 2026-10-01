<script lang="ts">
  /**
   * Settings — store configuration and the user's own preferences. The
   * ThemeSwitch writes the shared core theme store; the language menu calls
   * `setLang`, which flips the whole panel — shell included — on the spot (no
   * reload needed in this flavour). The danger zone asks for the delete word
   * before its button wakes up, and "deleting" the demo store simply ends the
   * session at the sign-in view.
   */
  import { toast } from '@nabuxai/ui-core';
  import { strings } from '../store.svelte';
  import { go } from '../router.svelte';
  import NxIcon from '../lib/NxIcon.svelte';
  import ThemeSwitch from '../lib/ThemeSwitch.svelte';
  import LangMenu from '../lib/LangMenu.svelte';

  type ButtonStatus = 'idle' | 'loading' | 'success';

  const s = $derived(strings());
  const set = $derived(s.pages.settings);

  let saveState = $state<ButtonStatus>('idle');
  let twoFactor = $state(true);
  let digest = $state(true);
  let push = $state(false);
  let weekly = $state(true);
  let passwordOpen = $state(false);
  let confirm = $state('');
  let passwordDialog: HTMLDialogElement;

  const armed = $derived(confirm.trim().toLowerCase() === set.deleteWord);

  const saveGeneral = (event: SubmitEvent) => {
    event.preventDefault();
    if (saveState !== 'idle') return;
    saveState = 'loading';
    setTimeout(() => {
      saveState = 'success';
      toast.success(s.common.saved);
      setTimeout(() => (saveState = 'idle'), 1400);
    }, 700);
  };

  $effect(() => {
    if (passwordOpen) passwordDialog?.showModal();
    else passwordDialog?.close();
  });

  const updatePassword = () => {
    passwordOpen = false;
    toast.success(set.passwordUpdated);
  };

  const signOutEverywhere = () => toast.info(set.signedOutEverywhere);

  const deleteStore = () => {
    go('login');
    toast.warning(set.storeDeleted);
  };

  const languageOptions = $derived([
    { id: 'fa' as const, name: set.languageFa, short: 'FA' },
    { id: 'en' as const, name: set.languageEn, short: 'EN' },
  ]);

  const backdropClose = (event: MouseEvent) => {
    if (event.target === event.currentTarget) passwordOpen = false;
  };
</script>

<div class="adm-settings">
  <section class="nx-card adm-settings-general">
    <div class="nx-card-header">
      <span class="nx-card-icon"><NxIcon name="settings" /></span>
      <h2 class="nx-card-title">{set.general}</h2>
    </div>
    <div class="nx-card-body">
      <form class="adm-form" onsubmit={saveGeneral}>
        <div class="adm-form-grid">
          <div class="nx-field">
            <label class="nx-label" for="settings-name">{set.workspaceName}</label>
            <input id="settings-name" name="workspace" class="nx-input" value={s.app.storeName} required />
          </div>
          <div class="nx-field">
            <label class="nx-label" for="settings-url">{set.workspaceUrl}</label>
            <div class="nx-input-group">
              <span class="nx-input-addon">https://</span>
              <input id="settings-url" name="url" class="nx-input" dir="ltr" value="nabu.shop" />
            </div>
          </div>
          <div class="nx-field adm-form-span">
            <label class="nx-label" for="settings-tz">{set.timezoneField}</label>
            <select id="settings-tz" name="timezone" class="nx-select">
              <option value="Asia/Tehran">{s.common.tzTehran}</option>
              <option value="Europe/Berlin">{s.common.tzBerlin}</option>
              <option value="UTC">UTC</option>
            </select>
          </div>
        </div>
        <div class="adm-form-actions">
          <button type="submit" class="nx-button" data-variant="primary" data-status={saveState === 'idle' ? undefined : saveState}>
            <span class="nx-button-label">
              <NxIcon name="check" />
              <span class="nx-button-text">{s.common.save}</span>
              {#if saveState !== 'idle'}
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
      <span class="nx-card-icon"><NxIcon name="sun" /></span>
      <h2 class="nx-card-title">{set.appearance}</h2>
    </div>
    <div class="nx-card-body">
      <div class="adm-row">
        <span class="adm-row-text">
          <span class="adm-row-label">{set.theme}</span>
          <span class="adm-row-hint">{set.themeHint}</span>
        </span>
        <ThemeSwitch label={set.theme} />
      </div>
    </div>
  </section>

  <section class="nx-card">
    <div class="nx-card-header">
      <span class="nx-card-icon"><NxIcon name="globe" /></span>
      <h2 class="nx-card-title">{set.languageSection}</h2>
    </div>
    <div class="nx-card-body">
      <div class="adm-row">
        <span class="adm-row-text">
          <span class="adm-row-label">{set.languageFa} · {set.languageEn}</span>
          <span class="adm-row-hint">{set.languageHint}</span>
        </span>
        <LangMenu label={set.languageSection} options={languageOptions} />
      </div>
    </div>
  </section>

  <section class="nx-card">
    <div class="nx-card-header">
      <span class="nx-card-icon"><NxIcon name="bell" /></span>
      <h2 class="nx-card-title">{set.notifications}</h2>
    </div>
    <div class="nx-card-body">
      <div class="adm-choices">
        <label class="nx-choice">
          <input class="nx-switch" type="checkbox" role="switch" bind:checked={digest} />
          <span class="nx-choice-text"><span class="nx-choice-label">{set.emailDigest}</span></span>
        </label>
        <label class="nx-choice">
          <input class="nx-switch" type="checkbox" role="switch" bind:checked={push} />
          <span class="nx-choice-text"><span class="nx-choice-label">{set.pushAlerts}</span></span>
        </label>
        <label class="nx-choice">
          <input class="nx-switch" type="checkbox" role="switch" bind:checked={weekly} />
          <span class="nx-choice-text"><span class="nx-choice-label">{set.weeklyReport}</span></span>
        </label>
      </div>
    </div>
  </section>

  <section class="nx-card">
    <div class="nx-card-header">
      <span class="nx-card-icon"><NxIcon name="lock" /></span>
      <h2 class="nx-card-title">{set.security}</h2>
    </div>
    <div class="nx-card-body">
      <div class="adm-choices">
        <label class="nx-choice">
          <input class="nx-switch" type="checkbox" role="switch" bind:checked={twoFactor} />
          <span class="nx-choice-text"><span class="nx-choice-label">{set.twoFactor}</span></span>
        </label>
        <div class="adm-row">
          <span class="adm-row-text"><span class="adm-row-label">{set.password}</span></span>
          <button type="button" class="nx-button" data-variant="outline" data-size="sm" onclick={() => (passwordOpen = true)}>
            <span class="nx-button-label">
              <NxIcon name="lock" />
              <span class="nx-button-text">{s.common.edit}</span>
            </span>
          </button>
        </div>
        <div class="adm-row">
          <span class="adm-row-text"><span class="adm-row-label">{set.signOutDevices}</span></span>
          <button type="button" class="nx-button" data-variant="outline" data-size="sm" onclick={signOutEverywhere}>
            <span class="nx-button-label">
              <NxIcon name="lock" />
              <span class="nx-button-text">{s.common.confirm}</span>
            </span>
          </button>
        </div>
      </div>
    </div>
  </section>

  <section class="nx-card adm-settings-danger">
    <div class="nx-card-header">
      <span class="nx-card-icon"><NxIcon name="alert-triangle" /></span>
      <h2 class="nx-card-title">{set.danger}</h2>
      <p class="nx-card-description">{set.deleteBody}</p>
    </div>
    <div class="nx-card-body">
      <div class="adm-danger">
        <div class="nx-field">
          <label class="nx-label" for="settings-confirm">{set.typeToDelete}</label>
          <input id="settings-confirm" class="nx-input" dir="ltr" autocomplete="off" placeholder={set.deleteWord} bind:value={confirm} />
          <p class="nx-hint">{set.confirmWord}</p>
        </div>
        <button type="button" class="nx-button" data-variant="danger" disabled={!armed} onclick={deleteStore}>
          <span class="nx-button-label">
            <NxIcon name="trash" />
            <span class="nx-button-text">{set.deleteWorkspace}</span>
          </span>
        </button>
      </div>
    </div>
  </section>

  <dialog bind:this={passwordDialog} class="nx-dialog" data-size="sm" onclick={backdropClose} onclose={() => (passwordOpen = false)}>
    <header class="nx-dialog-header">
      <h2 class="nx-dialog-title">{set.password}</h2>
      <p class="nx-dialog-description">{set.passwordDescription}</p>
    </header>
    <div class="nx-dialog-body adm-form">
      <div class="nx-field">
        <label class="nx-label" for="password-current">{s.common.currentPassword}</label>
        <input id="password-current" class="nx-input" type="password" dir="ltr" autocomplete="current-password" required />
      </div>
      <div class="nx-field">
        <label class="nx-label" for="password-new">{s.common.newPassword}</label>
        <input id="password-new" class="nx-input" type="password" dir="ltr" autocomplete="new-password" required minlength="8" />
      </div>
    </div>
    <footer class="nx-dialog-footer">
      <button type="button" class="nx-button" data-variant="ghost" onclick={() => (passwordOpen = false)}>
        <span class="nx-button-label"><span class="nx-button-text">{s.common.cancel}</span></span>
      </button>
      <button type="button" class="nx-button" data-variant="primary" onclick={updatePassword}>
        <span class="nx-button-label">
          <NxIcon name="check" />
          <span class="nx-button-text">{s.common.confirm}</span>
        </span>
      </button>
    </footer>
    <button type="button" class="nx-dialog-close" aria-label={s.common.close} onclick={() => (passwordOpen = false)}>
      <NxIcon name="x" />
    </button>
  </dialog>
</div>
