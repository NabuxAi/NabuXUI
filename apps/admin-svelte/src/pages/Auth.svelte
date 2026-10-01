<script lang="ts">
  /**
   * #/login — the sign-in view, outside the admin shell (`.adm-auth` in app.css
   * centres it). A hand-built nx-card over the same markup contract the AuthCard
   * block styles; the field labels and the submit word come from the core i18n
   * table (authEmail / authPassword / authRemember / authLogin). Submitting runs
   * a short loading beat, then lands in the panel with a toast.
   */
  import { toast, translate } from '@nabuxai/ui-core';
  import { app, strings } from '../store.svelte';
  import { go, href } from '../router.svelte';
  import NxIcon from '../lib/NxIcon.svelte';

  const s = $derived(strings());
  const a = $derived(s.pages.auth);

  let email = $state('');
  let password = $state('');
  let remember = $state(true);
  let busy = $state(false);

  const word = (key: 'authEmail' | 'authPassword' | 'authRemember' | 'authLogin') => translate(app.lang, key);

  const submit = (event: SubmitEvent) => {
    event.preventDefault();
    if (busy) return;
    busy = true;
    setTimeout(() => {
      busy = false;
      toast.success(a.welcomeBack);
      go('dash');
    }, 900);
  };

  const perks = $derived([a.perkOrders, a.perkReports, a.perkBilingual]);
</script>

<div class="adm-auth">
  <section class="nx-card adm-auth-card">
    <div class="nx-card-header">
      <span class="nx-card-icon"><NxIcon name="lock" /></span>
      <h1 class="nx-card-title">{a.loginTitle}</h1>
      <p class="nx-card-description">{s.app.tagline}</p>
    </div>
    <div class="nx-card-body adm-form">
      <form class="adm-form" onsubmit={submit}>
        <div class="nx-field">
          <label class="nx-label" for="login-email">{word('authEmail')}</label>
          <input id="login-email" class="nx-input" type="email" dir="ltr" autocomplete="username" bind:value={email} required />
        </div>
        <div class="nx-field">
          <label class="nx-label" for="login-password">{word('authPassword')}</label>
          <input id="login-password" class="nx-input" type="password" dir="ltr" autocomplete="current-password" bind:value={password} required minlength="8" />
        </div>
        <label class="nx-choice">
          <input class="nx-checkbox" type="checkbox" bind:checked={remember} />
          <span class="nx-choice-text"><span class="nx-choice-label">{word('authRemember')}</span></span>
        </label>
        <div class="adm-form-actions">
          <button type="submit" class="nx-button" data-variant="primary" data-status={busy ? 'loading' : undefined} disabled={busy}>
            <span class="nx-button-label">
              <span class="nx-button-text">{word('authLogin')}</span>
              {#if busy}
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
      <ul class="adm-auth-perks">
        {#each perks as perk (perk)}
          <li class="adm-auth-perk">
            <NxIcon name="check" />
            <span>{perk}</span>
          </li>
        {/each}
      </ul>
    </div>
  </section>
  <a class="nx-button" data-variant="ghost" data-size="sm" href={href('dash')}>
    <span class="nx-button-label">
      <NxIcon name="grid" />
      <span class="nx-button-text">{a.backToPanel}</span>
    </span>
  </a>
</div>
