<script setup lang="ts">
/**
 * Settings — store configuration and the user's own preferences. The
 * ThemeSwitch writes the shared core theme store; the language menu calls
 * `setLang`, which flips the whole panel — shell included — on the spot (no
 * reload needed in this flavour). The danger zone asks for the delete word
 * before its button wakes up, and "deleting" the demo store simply ends the
 * session at the sign-in view.
 */
import { computed, nextTick, ref, watch } from 'vue';
import { toast } from '@nabuxai/ui-core';
import { s } from '../store';
import { go } from '../router';
import NxIcon from '../components/NxIcon.vue';
import ThemeSwitch from '../components/ThemeSwitch.vue';
import LangMenu from '../components/LangMenu.vue';

const set = computed(() => s.value.pages.settings);

type ButtonStatus = 'idle' | 'loading' | 'success';

const saveState = ref<ButtonStatus>('idle');
const twoFactor = ref(true);
const digest = ref(true);
const push = ref(false);
const weekly = ref(true);
const passwordOpen = ref(false);
const confirm = ref('');
const passwordDialog = ref<HTMLDialogElement | null>(null);

const armed = computed(() => confirm.value.trim().toLowerCase() === set.value.deleteWord);

const saveGeneral = (event: Event) => {
  event.preventDefault();
  if (saveState.value !== 'idle') return;
  saveState.value = 'loading';
  window.setTimeout(() => {
    saveState.value = 'success';
    toast.success(s.value.common.saved);
    window.setTimeout(() => (saveState.value = 'idle'), 1400);
  }, 700);
};

watch(passwordOpen, (open) => void nextTick(() => (open ? passwordDialog.value?.showModal() : passwordDialog.value?.close())));
const closePassword = () => (passwordOpen.value = false);

const updatePassword = () => {
  passwordOpen.value = false;
  toast.success(set.value.passwordUpdated);
};

const signOutEverywhere = () => toast.info(set.value.signedOutEverywhere);

const deleteStore = () => {
  go('login');
  toast.warning(set.value.storeDeleted);
};

const languageOptions = computed(() => [
  { id: 'fa' as const, name: set.value.languageFa, short: 'FA' },
  { id: 'en' as const, name: set.value.languageEn, short: 'EN' },
]);
</script>

<template>
  <div class="adm-settings">
    <section class="nx-card adm-settings-general">
      <div class="nx-card-header">
        <span class="nx-card-icon"><NxIcon name="settings" /></span>
        <h2 class="nx-card-title">{{ set.general }}</h2>
      </div>
      <div class="nx-card-body">
        <form class="adm-form" @submit="saveGeneral">
          <div class="adm-form-grid">
            <div class="nx-field">
              <label class="nx-label" for="settings-name">{{ set.workspaceName }}</label>
              <input id="settings-name" name="workspace" class="nx-input" :value="s.app.storeName" required />
            </div>
            <div class="nx-field">
              <label class="nx-label" for="settings-url">{{ set.workspaceUrl }}</label>
              <div class="nx-input-group">
                <span class="nx-input-addon">https://</span>
                <input id="settings-url" name="url" class="nx-input" dir="ltr" value="nabu.shop" />
              </div>
            </div>
            <div class="nx-field adm-form-span">
              <label class="nx-label" for="settings-tz">{{ set.timezoneField }}</label>
              <select id="settings-tz" name="timezone" class="nx-select">
                <option value="Asia/Tehran">{{ s.common.tzTehran }}</option>
                <option value="Europe/Berlin">{{ s.common.tzBerlin }}</option>
                <option value="UTC">UTC</option>
              </select>
            </div>
          </div>
          <div class="adm-form-actions">
            <button type="submit" class="nx-button" data-variant="primary" :data-status="saveState === 'idle' ? undefined : saveState">
              <span class="nx-button-label">
                <NxIcon name="check" />
                <span class="nx-button-text">{{ s.common.save }}</span>
                <span v-if="saveState !== 'idle'" class="nx-button-status" aria-hidden="true">
                  <span class="nx-spinner" data-size="sm" />
                  <svg class="nx-icon nx-status-check" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12.5l4.5 4.5L19 7.5" pathLength="1" />
                  </svg>
                </span>
              </span>
            </button>
          </div>
        </form>
      </div>
    </section>

    <section class="nx-card">
      <div class="nx-card-header">
        <span class="nx-card-icon"><NxIcon name="sun" /></span>
        <h2 class="nx-card-title">{{ set.appearance }}</h2>
      </div>
      <div class="nx-card-body">
        <div class="adm-row">
          <span class="adm-row-text">
            <span class="adm-row-label">{{ set.theme }}</span>
            <span class="adm-row-hint">{{ set.themeHint }}</span>
          </span>
          <ThemeSwitch :label="set.theme" />
        </div>
      </div>
    </section>

    <section class="nx-card">
      <div class="nx-card-header">
        <span class="nx-card-icon"><NxIcon name="globe" /></span>
        <h2 class="nx-card-title">{{ set.languageSection }}</h2>
      </div>
      <div class="nx-card-body">
        <div class="adm-row">
          <span class="adm-row-text">
            <span class="adm-row-label">{{ set.languageFa }} · {{ set.languageEn }}</span>
            <span class="adm-row-hint">{{ set.languageHint }}</span>
          </span>
          <LangMenu :label="set.languageSection" :options="languageOptions" />
        </div>
      </div>
    </section>

    <section class="nx-card">
      <div class="nx-card-header">
        <span class="nx-card-icon"><NxIcon name="bell" /></span>
        <h2 class="nx-card-title">{{ set.notifications }}</h2>
      </div>
      <div class="nx-card-body">
        <div class="adm-choices">
          <label class="nx-choice">
            <input v-model="digest" class="nx-switch" type="checkbox" role="switch" />
            <span class="nx-choice-text"><span class="nx-choice-label">{{ set.emailDigest }}</span></span>
          </label>
          <label class="nx-choice">
            <input v-model="push" class="nx-switch" type="checkbox" role="switch" />
            <span class="nx-choice-text"><span class="nx-choice-label">{{ set.pushAlerts }}</span></span>
          </label>
          <label class="nx-choice">
            <input v-model="weekly" class="nx-switch" type="checkbox" role="switch" />
            <span class="nx-choice-text"><span class="nx-choice-label">{{ set.weeklyReport }}</span></span>
          </label>
        </div>
      </div>
    </section>

    <section class="nx-card">
      <div class="nx-card-header">
        <span class="nx-card-icon"><NxIcon name="lock" /></span>
        <h2 class="nx-card-title">{{ set.security }}</h2>
      </div>
      <div class="nx-card-body">
        <div class="adm-choices">
          <label class="nx-choice">
            <input v-model="twoFactor" class="nx-switch" type="checkbox" role="switch" />
            <span class="nx-choice-text"><span class="nx-choice-label">{{ set.twoFactor }}</span></span>
          </label>
          <div class="adm-row">
            <span class="adm-row-text"><span class="adm-row-label">{{ set.password }}</span></span>
            <button type="button" class="nx-button" data-variant="outline" data-size="sm" @click="passwordOpen = true">
              <span class="nx-button-label">
                <NxIcon name="lock" />
                <span class="nx-button-text">{{ s.common.edit }}</span>
              </span>
            </button>
          </div>
          <div class="adm-row">
            <span class="adm-row-text"><span class="adm-row-label">{{ set.signOutDevices }}</span></span>
            <button type="button" class="nx-button" data-variant="outline" data-size="sm" @click="signOutEverywhere">
              <span class="nx-button-label">
                <NxIcon name="lock" />
                <span class="nx-button-text">{{ s.common.confirm }}</span>
              </span>
            </button>
          </div>
        </div>
      </div>
    </section>

    <section class="nx-card adm-settings-danger">
      <div class="nx-card-header">
        <span class="nx-card-icon"><NxIcon name="alert-triangle" /></span>
        <h2 class="nx-card-title">{{ set.danger }}</h2>
        <p class="nx-card-description">{{ set.deleteBody }}</p>
      </div>
      <div class="nx-card-body">
        <div class="adm-danger">
          <div class="nx-field">
            <label class="nx-label" for="settings-confirm">{{ set.typeToDelete }}</label>
            <input id="settings-confirm" v-model="confirm" class="nx-input" dir="ltr" autocomplete="off" :placeholder="set.deleteWord" />
            <p class="nx-hint">{{ set.confirmWord }}</p>
          </div>
          <button type="button" class="nx-button" data-variant="danger" :disabled="!armed" @click="deleteStore">
            <span class="nx-button-label">
              <NxIcon name="trash" />
              <span class="nx-button-text">{{ set.deleteWorkspace }}</span>
            </span>
          </button>
        </div>
      </div>
    </section>

    <dialog ref="passwordDialog" class="nx-dialog" data-size="sm" @close="closePassword" @click.self="closePassword">
      <header class="nx-dialog-header">
        <h2 class="nx-dialog-title">{{ set.password }}</h2>
        <p class="nx-dialog-description">{{ set.passwordDescription }}</p>
      </header>
      <div class="nx-dialog-body adm-form">
        <div class="nx-field">
          <label class="nx-label" for="password-current">{{ s.common.currentPassword }}</label>
          <input id="password-current" class="nx-input" type="password" dir="ltr" autocomplete="current-password" required />
        </div>
        <div class="nx-field">
          <label class="nx-label" for="password-new">{{ s.common.newPassword }}</label>
          <input id="password-new" class="nx-input" type="password" dir="ltr" autocomplete="new-password" required minlength="8" />
        </div>
      </div>
      <footer class="nx-dialog-footer">
        <button type="button" class="nx-button" data-variant="ghost" @click="closePassword">
          <span class="nx-button-label"><span class="nx-button-text">{{ s.common.cancel }}</span></span>
        </button>
        <button type="button" class="nx-button" data-variant="primary" @click="updatePassword">
          <span class="nx-button-label">
            <NxIcon name="check" />
            <span class="nx-button-text">{{ s.common.confirm }}</span>
          </span>
        </button>
      </footer>
      <button type="button" class="nx-dialog-close" :aria-label="s.common.close" @click="closePassword">
        <NxIcon name="x" />
      </button>
    </dialog>
  </div>
</template>
