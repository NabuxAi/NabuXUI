<script setup lang="ts">
/**
 * The dashboard — the front page: the welcome card, the day's numbers (the
 * rolling StatStrip), the activity feed and the storage card. Data is seeded in
 * ../data and ../lang; digits follow the panel language.
 */
import { computed } from 'vue';
import { numberFmt, s, tr, intlLocale } from '../store';
import { href } from '../router';
import { AGO, STORAGE } from '../data';
import StatStrip, { type Stat } from '../components/StatStrip.vue';
import NxIcon from '../components/NxIcon.vue';

const d = computed(() => s.value.pages.dash);

const stats = computed<Stat[]>(() => [
  { label: d.value.statRevenue, value: 84_500_000, caption: d.value.statRevenueCaption },
  { label: d.value.statOrders, value: 42, caption: d.value.statOrdersCaption },
  { label: d.value.statUsers, value: 24_318, caption: d.value.statUsersCaption },
  { label: d.value.statSatisfaction, value: 96, caption: d.value.statSatisfactionCaption },
]);

const entries = computed(() => [
  { id: 'order', icon: 'check-circle' as const, text: tr('سفارش را تأیید کرد', 'confirmed order'), target: tr('مریم رضایی', 'Maryam Rezaei'), time: AGO.minutes3.at },
  { id: 'backup', icon: 'upload' as const, text: tr('پشتیبان‌گیری کامل شد', 'Backup finished'), target: tr('۱۲ گیگابایت', '12 gigabytes'), time: AGO.hours2.at },
  { id: 'card', icon: 'layers' as const, text: tr('کارت را به «بازبینی» برد', 'moved the card to Review'), target: tr('علی نیک‌پور', 'Ali Nikpour'), time: AGO.hour1.at },
  { id: 'stock', icon: 'bell' as const, text: tr('موجودی کم است:', 'Low stock:'), target: tr('کیف چرمی نابو', 'Nabu leather bag'), time: AGO.hours5.at },
  { id: 'invite', icon: 'mail' as const, text: tr('کاربر تازه را دعوت کرد', 'invited a new user'), target: 'mina@nabu.shop', time: AGO.day1.at },
]);

/** "۱۸ دقیقه پیش" / "18m ago" — the locale's digits, measured from the shared NOW. */
function agoWord(at: number): string {
  const minutes = Math.max(0, Math.round((Date.now() - at) / 60_000));
  const n = (value: number) => new Intl.NumberFormat(intlLocale.value).format(value);
  if (minutes < 1) return tr('همین حالا', 'just now');
  if (minutes < 60) return tr(`${n(minutes)} دقیقه پیش`, `${n(minutes)}m ago`);
  const hours = Math.round(minutes / 60);
  if (hours < 24) return tr(`${n(hours)} ساعت پیش`, `${n(hours)}h ago`);
  const days = Math.round(hours / 24);
  return tr(`${n(days)} روز پیش`, `${n(days)}d ago`);
}

const usage = computed(() => [
  { label: d.value.usageDocs, value: STORAGE.docs },
  { label: d.value.usageMedia, value: STORAGE.media },
  { label: d.value.usageVoice, value: STORAGE.voice },
  { label: d.value.usageEmbeddings, value: STORAGE.embeddings },
]);

const usedGb = computed(() => usage.value.reduce((sum, row) => sum + row.value, 0));
const usedPercent = Math.round((usedGb.value / STORAGE.limit) * 100);
const gbFmt = computed(() => new Intl.NumberFormat(intlLocale.value, { style: 'unit', unit: 'gigabyte', maximumFractionDigits: 1 }));
</script>

<template>
  <div class="adm-dashboard">
    <div class="adm-welcome">
      <h1>{{ d.welcomeHi }}</h1>
      <p>{{ d.welcomeText }}</p>
      <div class="adm-welcome-actions">
        <a class="nx-button" data-variant="primary" :href="href('analytics')">
          <span class="nx-button-label">
            <NxIcon name="chart" />
            <span class="nx-button-text">{{ d.welcomeCta }}</span>
          </span>
        </a>
        <a class="nx-button" data-variant="secondary" :href="href('users')">
          <span class="nx-button-label">
            <NxIcon name="users" />
            <span class="nx-button-text">{{ d.welcomeSecondary }}</span>
          </span>
        </a>
      </div>
    </div>

    <StatStrip :stats="stats" :label="d.statsLabel" />

    <div class="nx-card">
      <div class="nx-card-header">
        <span class="nx-card-icon"><NxIcon name="heart" /></span>
        <h2 class="nx-card-title">{{ d.feedTitle }}</h2>
      </div>
      <div class="nx-card-body">
        <ul class="adm-usage-list" style="gap: var(--nx-space-3)">
          <li v-for="entry in entries" :key="entry.id" class="adm-usage-row" style="justify-content: flex-start">
            <NxIcon :name="entry.icon" />
            <span style="color: var(--nx-text-muted)">{{ entry.text }}</span>
            <span style="font-weight: 600">{{ entry.target }}</span>
            <span style="margin-inline-start: auto; color: var(--nx-text-subtle); font-size: var(--nx-text-xs)">{{ agoWord(entry.time) }}</span>
          </li>
        </ul>
      </div>
    </div>

    <div class="nx-card adm-usage">
      <div class="nx-card-header">
        <span class="nx-card-icon"><NxIcon name="layers" /></span>
        <h2 class="nx-card-title">{{ d.usageTitle }}</h2>
        <p class="nx-card-description">{{ d.usagePlan }} · {{ d.usageNote }}</p>
      </div>
      <div class="nx-card-body adm-usage">
        <progress class="nx-progress" :value="usedPercent" max="100" :style="{ '--nx-value': usedPercent }" :aria-label="d.usageTitle" />
        <ul class="adm-usage-list">
          <li v-for="row in usage" :key="row.label" class="adm-usage-row">
            <span>{{ row.label }}</span>
            <span>{{ gbFmt.format(row.value) }}</span>
          </li>
        </ul>
        <p class="adm-usage-meta">
          <span>{{ gbFmt.format(usedGb) }} / {{ gbFmt.format(STORAGE.limit) }}</span>
          <span>{{ numberFmt.format(usedPercent) }}٪</span>
        </p>
      </div>
    </div>
  </div>
</template>
