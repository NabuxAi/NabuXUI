<script setup lang="ts">
/**
 * The account page: personal details (with a working photo picker — a hidden
 * file input feeding an object-URL avatar, revoked on unmount), a password
 * form, the active devices with per-device sign-out, and the panel's own
 * appearance/language seats (the same ThemeSwitch/LangMenu the settings page
 * carries — the switch writes the shared core theme store, the menu calls
 * `setLang` and flips the whole panel on the spot). Everything is local
 * state; saving runs the button's own status marks (loading → success) and
 * lands a toast.
 */
import { computed, onBeforeUnmount, ref } from 'vue';
import { type IconName, toast } from '@nabuxai/ui-core';
import { s, tr } from '../store';
import NxIcon from '../components/NxIcon.vue';
import ThemeSwitch from '../components/ThemeSwitch.vue';
import LangMenu from '../components/LangMenu.vue';

const p = computed(() => s.value.pages.profile);
const set = computed(() => s.value.pages.settings);

type ButtonStatus = 'idle' | 'loading' | 'success';

interface Session {
  id: string;
  icon: IconName;
  device: string;
  lastActive: string;
  current?: boolean;
}

// Sessions seeded from the language layer, so a language switch re-titles them.
const seedSessions = (): Session[] => [
  { id: 'mac', icon: 'command', device: p.value.deviceMac, lastActive: s.value.common.online, current: true },
  { id: 'iphone', icon: 'globe', device: p.value.deviceIphone, lastActive: p.value.whenIphone },
  { id: 'windows', icon: 'grid', device: p.value.deviceWindows, lastActive: p.value.whenWindows },
];

const sessions = ref<Session[]>(seedSessions());

/* ---- The photo picker: a hidden native input behind the button; the preview is an object URL that dies with the page. ---- */

const avatar = ref<string | undefined>(undefined);
const file = ref<HTMLInputElement | null>(null);
let url: string | undefined;

const pickPhoto = (event: Event) => {
  const next = (event.target as HTMLInputElement).files?.[0];
  if (!next) return;
  if (url) URL.revokeObjectURL(url);
  url = URL.createObjectURL(next);
  avatar.value = url;
};

onBeforeUnmount(() => {
  if (url) URL.revokeObjectURL(url);
});

const revoke = (session: Session) => {
  sessions.value = sessions.value.filter((entry) => entry.id !== session.id);
  toast.success(tr(`از «${session.device}» خارج شدید`, `Signed out of “${session.device}”`));
};

/* ---- Saving: busy → check → toast, the button's own status marks. ---- */

const profileState = ref<ButtonStatus>('idle');
const passwordState = ref<ButtonStatus>('idle');

const saveProfile = (event: Event) => {
  event.preventDefault();
  if (profileState.value !== 'idle') return;
  profileState.value = 'loading';
  window.setTimeout(() => {
    profileState.value = 'success';
    toast.success(s.value.common.saved);
    window.setTimeout(() => (profileState.value = 'idle'), 1400);
  }, 700);
};

const savePassword = (event: Event) => {
  event.preventDefault();
  if (passwordState.value !== 'idle') return;
  passwordState.value = 'loading';
  window.setTimeout(() => {
    passwordState.value = 'success';
    toast.success(p.value.updatePassword);
    window.setTimeout(() => (passwordState.value = 'idle'), 1400);
  }, 700);
};

const languageOptions = computed(() => [
  { id: 'fa' as const, name: set.value.languageFa, short: 'FA' },
  { id: 'en' as const, name: set.value.languageEn, short: 'EN' },
]);
</script>

<template>
  <div class="adm-profile">
    <section class="nx-card adm-profile-main">
      <div class="nx-card-header">
        <span class="nx-card-icon"><NxIcon name="user" /></span>
        <h2 class="nx-card-title">{{ p.personal }}</h2>
      </div>
      <div class="nx-card-body">
        <form class="adm-form" @submit="saveProfile">
          <div class="adm-avatar-row">
            <span class="nx-avatar" data-size="xl" data-status="online" role="img" :aria-label="s.app.user.name">
              <img v-if="avatar" class="nx-avatar-image" :src="avatar" alt="" />
              <span v-else aria-hidden="true">{{ s.app.user.name.split(/\s+/).slice(0, 2).map((word) => Array.from(word)[0]).join('') }}</span>
            </span>
            <div class="adm-avatar-actions">
              <button type="button" class="nx-button" data-variant="secondary" data-size="sm" @click="file?.click()">
                <span class="nx-button-label">
                  <NxIcon name="image" />
                  <span class="nx-button-text">{{ p.changeAvatar }}</span>
                </span>
              </button>
              <input ref="file" type="file" accept="image/*" hidden @change="pickPhoto" />
              <span class="adm-avatar-hint">{{ p.avatarHint }}</span>
            </div>
          </div>
          <div class="adm-form-grid">
            <div class="nx-field">
              <label class="nx-label" for="profile-name">{{ p.name }}</label>
              <input id="profile-name" name="name" class="nx-input" :value="s.app.user.name" autocomplete="name" required />
            </div>
            <div class="nx-field">
              <label class="nx-label" for="profile-email">{{ p.email }}</label>
              <input id="profile-email" name="email" class="nx-input" type="email" dir="ltr" value="hossein@nabu.shop" autocomplete="email" readonly />
            </div>
            <div class="nx-field">
              <label class="nx-label" for="profile-role">{{ p.role }}</label>
              <input id="profile-role" name="role" class="nx-input" :value="s.app.user.role" readonly />
            </div>
            <div class="nx-field">
              <label class="nx-label" for="profile-phone">{{ p.phone }}</label>
              <input id="profile-phone" name="phone" class="nx-input" type="tel" dir="ltr" value="+98 912 000 0000" autocomplete="tel" />
            </div>
            <div class="nx-field adm-form-span">
              <label class="nx-label" for="profile-tz">{{ p.timezone }}</label>
              <select id="profile-tz" name="timezone" class="nx-select">
                <option value="Asia/Tehran">{{ s.common.tzTehran }}</option>
                <option value="Europe/Berlin">{{ s.common.tzBerlin }}</option>
                <option value="UTC">UTC</option>
              </select>
            </div>
            <div class="nx-field adm-form-span">
              <label class="nx-label" for="profile-bio">{{ p.bio }}</label>
              <textarea id="profile-bio" name="bio" class="nx-textarea" rows="3" :value="p.bioDefault" />
            </div>
          </div>
          <div class="adm-form-actions">
            <button type="submit" class="nx-button" data-variant="primary" :data-status="profileState === 'idle' ? undefined : profileState">
              <span class="nx-button-label">
                <NxIcon name="check" />
                <span class="nx-button-text">{{ s.common.save }}</span>
                <span v-if="profileState !== 'idle'" class="nx-button-status" aria-hidden="true">
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

    <div class="adm-profile-side">
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
          <span class="nx-card-icon"><NxIcon name="lock" /></span>
          <h2 class="nx-card-title">{{ p.security }}</h2>
        </div>
        <div class="nx-card-body">
          <form class="adm-form" @submit="savePassword">
            <div class="nx-field">
              <label class="nx-label" for="profile-current">{{ s.common.currentPassword }}</label>
              <input id="profile-current" class="nx-input" type="password" dir="ltr" autocomplete="current-password" required />
            </div>
            <div class="nx-field">
              <label class="nx-label" for="profile-next">{{ s.common.newPassword }}</label>
              <input id="profile-next" class="nx-input" type="password" dir="ltr" autocomplete="new-password" required minlength="8" />
              <p class="nx-hint">{{ p.newPasswordHint }}</p>
            </div>
            <div class="adm-form-actions">
              <button type="submit" class="nx-button" data-variant="primary" :data-status="passwordState === 'idle' ? undefined : passwordState">
                <span class="nx-button-label">
                  <NxIcon name="check" />
                  <span class="nx-button-text">{{ p.updatePassword }}</span>
                  <span v-if="passwordState !== 'idle'" class="nx-button-status" aria-hidden="true">
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
          <span class="nx-card-icon"><NxIcon name="shield" /></span>
          <h2 class="nx-card-title">{{ p.sessions }}</h2>
        </div>
        <div class="nx-card-body">
          <ul class="adm-sessions">
            <li v-for="session in sessions" :key="session.id" class="adm-session">
              <span class="adm-session-icon" aria-hidden="true">
                <NxIcon :name="session.icon" />
              </span>
              <span class="adm-session-meta">
                <span class="adm-session-device">
                  {{ session.device }}
                  <span v-if="session.current" class="adm-session-now">{{ p.thisDevice }}</span>
                </span>
                <span class="adm-session-when">{{ p.lastActive }}: {{ session.lastActive }}</span>
              </span>
              <button
                type="button"
                class="nx-button"
                data-variant="ghost"
                data-size="sm"
                :disabled="session.current"
                :title="session.current ? p.currentDeviceHint : undefined"
                @click="revoke(session)"
              >
                <span class="nx-button-label">
                  <NxIcon name="lock" />
                  <span class="nx-button-text">{{ p.revoke }}</span>
                </span>
              </button>
            </li>
          </ul>
        </div>
      </section>
    </div>
  </div>
</template>
