<script setup lang="ts">
/**
 * #/login — the sign-in view, outside the admin shell (`.adm-auth` in app.css
 * centres it). A hand-built nx-card over the same markup contract the AuthCard
 * block styles; the field labels and the submit word come from the core i18n
 * table (authEmail / authPassword / authRemember / authLogin). Submitting runs
 * a short loading beat, then lands in the panel with a toast.
 */
import { computed, ref } from 'vue';
import { toast, translate } from '@nabuxai/ui-core';
import { lang, s } from '../store';
import { go, href } from '../router';
import NxIcon from '../components/NxIcon.vue';

const a = computed(() => s.value.pages.auth);
const email = ref('');
const password = ref('');
const remember = ref(true);
const busy = ref(false);
const form = ref<HTMLFormElement | null>(null);

const word = (key: 'authEmail' | 'authPassword' | 'authRemember' | 'authLogin' | 'authLoginSubtitle') => translate(lang.value, key);

const submit = (event: Event) => {
  event.preventDefault();
  if (busy.value) return;
  busy.value = true;
  window.setTimeout(() => {
    busy.value = false;
    toast.success(a.value.welcomeBack);
    go('dash');
  }, 900);
};

const perks = computed(() => [a.value.perkOrders, a.value.perkReports, a.value.perkBilingual]);
</script>

<template>
  <div class="adm-auth">
    <section class="nx-card adm-auth-card">
      <div class="nx-card-header">
        <span class="nx-card-icon"><NxIcon name="lock" /></span>
        <h1 class="nx-card-title">{{ a.loginTitle }}</h1>
        <p class="nx-card-description">{{ s.app.tagline }}</p>
      </div>
      <div class="nx-card-body adm-form">
        <form class="adm-form" ref="form" @submit="submit">
          <div class="nx-field">
            <label class="nx-label" for="login-email">{{ word('authEmail') }}</label>
            <input id="login-email" v-model="email" class="nx-input" type="email" dir="ltr" autocomplete="username" required />
          </div>
          <div class="nx-field">
            <label class="nx-label" for="login-password">{{ word('authPassword') }}</label>
            <input id="login-password" v-model="password" class="nx-input" type="password" dir="ltr" autocomplete="current-password" required minlength="8" />
          </div>
          <label class="nx-choice">
            <input v-model="remember" class="nx-checkbox" type="checkbox" />
            <span class="nx-choice-text"><span class="nx-choice-label">{{ word('authRemember') }}</span></span>
          </label>
          <div class="adm-form-actions">
            <button type="submit" class="nx-button" data-variant="primary" :data-status="busy ? 'loading' : undefined" :disabled="busy">
              <span class="nx-button-label">
                <span class="nx-button-text">{{ word('authLogin') }}</span>
                <span v-if="busy" class="nx-button-status" aria-hidden="true">
                  <span class="nx-spinner" data-size="sm" />
                  <svg class="nx-icon nx-status-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12.5l4.5 4.5L19 7.5" pathLength="1" />
                  </svg>
                </span>
              </span>
            </button>
          </div>
        </form>
        <ul class="adm-auth-perks">
          <li v-for="perk in perks" :key="perk" class="adm-auth-perk">
            <NxIcon name="check" />
            <span>{{ perk }}</span>
          </li>
        </ul>
      </div>
    </section>
    <a class="nx-button" data-variant="ghost" data-size="sm" :href="href('dash')">
      <span class="nx-button-label">
        <NxIcon name="grid" />
        <span class="nx-button-text">{{ a.backToPanel }}</span>
      </span>
    </a>
  </div>
</template>
