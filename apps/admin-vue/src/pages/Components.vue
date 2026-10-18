<script setup lang="ts">
/**
 * گالری کامپوننت‌ها — صندلی نمایشی پکیج @nabuxai/ui-vue در این پنل. هر دوازده
 * خانوادهٔ پکیج (متن، دکمه، فرم، کارت، نمودار، نمایش، بازخورد، ناوبری،
 * لایه‌ها، هدر، هیرو و قیمت‌گذاری) در یک سناریوی واقعی و فشردهٔ همین پنل
 * می‌نشیند: کار روی سفارش #۱۲۴۸، ساخت کوپن تخفیف، آمار و نمودارهای فروشگاه.
 * همهٔ واژه‌ها از فرهنگ اپ (lang.ts) می‌آیند و با تغییر زبان، کل صفحه — از
 * برچسب نمودارها تا آیتم‌های تب‌ها — همان لحظه دوزبانه می‌شود.
 */
import { computed, h, ref } from 'vue';
import {
  NxAccordion,
  NxAlert,
  NxAvatar,
  NxAvatarGroup,
  NxBadge,
  NxBarChart,
  NxBreadcrumbs,
  NxButton,
  NxCard,
  NxCheckbox,
  NxChip,
  NxCommandPalette,
  NxCopyButton,
  NxDialog,
  NxDivider,
  NxDonutChart,
  NxField,
  NxFlipCard,
  NxGradientText,
  NxHeader,
  NxHero,
  NxHighlight,
  NxIconButton,
  NxInput,
  NxKbd,
  NxLikeButton,
  NxMenu,
  NxNumberTicker,
  NxOtpInput,
  NxPagination,
  NxPopover,
  NxPricingTable,
  NxProgress,
  NxProgressRing,
  NxRadioGroup,
  NxRating,
  NxReveal,
  NxSegmentedControl,
  NxSelect,
  NxShimmerText,
  NxSkeleton,
  NxSlider,
  NxSpinner,
  NxStatCard,
  NxSteps,
  NxSwitch,
  NxTabs,
  NxThemeToggle,
  NxTooltip,
  NxWordRotate,
  toast,
  type NxCommandGroup,
  type NxPricingPlan,
  type NxTabItem,
  type NxNavItem,
} from '@nabuxai/ui-vue';
import { STORAGE, monthLabels } from '../data';
import { localeId } from '../lang';
import { go, href as hrefOf } from '../router';
import { intlLocale, lang, s } from '../store';

const g = computed(() => s.value.pages.components);

/* ---- دکمه‌ها: چرخهٔ وضعیت برچسب ارسال ------------------------------------ */

type ShipStatus = 'idle' | 'loading' | 'success';
const TRACKING = 'NBU-1248-TRK';
const shipState = ref<ShipStatus>('idle');

function makeShippingLabel(): void {
  if (shipState.value !== 'idle') return;
  shipState.value = 'loading';
  window.setTimeout(() => {
    shipState.value = 'success';
    toast.success(g.value.shipDone);
    window.setTimeout(() => (shipState.value = 'idle'), 1600);
  }, 900);
}

/* ---- فرم: ساخت کوپن تخفیف ------------------------------------------------ */

const couponCode = ref('NABU-20');
const discount = ref(20);
const scope = ref('all');
const audience = ref('all');
const couponActive = ref(true);
const excludeSoldOut = ref(false);
const otp = ref('');
const couponProducts = ref<Array<'bag' | 'scarf'>>(['bag', 'scarf']);

const scopeOptions = computed(() => [
  { value: 'all', label: g.value.scopeAll },
  { value: 'leather', label: g.value.scopeLeather },
  { value: 'prints', label: g.value.scopePrints },
]);
const audienceOptions = computed(() => [
  { value: 'all', label: g.value.audienceAll },
  { value: 'club', label: g.value.audienceClub },
  { value: 'new', label: g.value.audienceNew },
]);
const productLabel = (id: 'bag' | 'scarf') => (id === 'bag' ? g.value.chipBag : g.value.chipScarf);
const percentOf = (value: number) => `${value}${g.value.percentSign}`;

function removeProduct(id: 'bag' | 'scarf'): void {
  couponProducts.value = couponProducts.value.filter((item) => item !== id);
  toast.info(`${productLabel(id)} ${g.value.chipRemoved}`);
}

function createCoupon(): void {
  toast.success(g.value.couponCreated);
}

/* ---- متن/کارت/نمودار/نمایش ------------------------------------------------ */

const orderSteps = computed(() => g.value.steps.map((title) => ({ title })));
const teamPeople = computed(() => [
  { name: s.value.people.maryam },
  { name: s.value.people.ali },
  { name: s.value.people.sara },
  { name: s.value.people.mina },
]);

const salesBars = computed(() =>
  monthLabels(intlLocale.value)
    .slice(0, 6)
    .map((label, i) => ({ label, value: [38, 52, 44, 61, 58, 73][i]! })),
);
const storageSplit = computed(() => [
  { label: g.value.legendDocs, value: STORAGE.docs },
  { label: g.value.legendMedia, value: STORAGE.media },
  { label: g.value.legendVoice, value: STORAGE.voice },
  { label: g.value.legendEmbed, value: STORAGE.embeddings },
]);
const storagePct = Math.round(((STORAGE.docs + STORAGE.media + STORAGE.voice + STORAGE.embeddings) / STORAGE.limit) * 100);

const stars = ref(4);

/* ---- ناوبری ---------------------------------------------------------------- */

const range = ref('30');
const rangeOptions = computed(() => g.value.ranges.map((label, i) => ({ value: ['7', '30', '90'][i]!, label })));
const invoicePage = ref(3);

const crumbs = computed(() => [
  ...g.value.crumbs.map((label, i) => ({ label, href: [hrefOf('dash'), hrefOf('invoices'), hrefOf('invoices')][i] })),
  { label: localeId('#1248', lang.value) },
]);

const orderTabs = computed<NxTabItem[]>(() => [
  { value: 'summary', label: g.value.tabSummary, content: h('p', { class: 'adm-gallery-note' }, g.value.tabSummaryBody) },
  {
    value: 'items',
    label: g.value.tabItems,
    content: h('ul', { class: 'adm-gallery-lines' }, [h('li', null, g.value.tabItemBag), h('li', null, g.value.tabItemScarf)]),
  },
  { value: 'payment', label: g.value.tabPayment, content: h('p', { class: 'adm-gallery-note' }, g.value.tabPaymentBody) },
]);

const faqItems = computed(() => [
  { title: g.value.faqShippingQ, content: g.value.faqShippingA },
  { title: g.value.faqGiftQ, content: g.value.faqGiftA },
]);

const orderMenuItems = computed(() => [
  { label: g.value.menuPrint, icon: 'file' as const, shortcut: '⌘P', onSelect: () => toast.info(g.value.printedToast) },
  { label: g.value.menuCopyId, icon: 'copy' as const, onSelect: () => toast.info(g.value.copiedToast) },
  { type: 'separator' as const },
  { type: 'label' as const, label: g.value.menuDangerZone },
  { label: g.value.menuDelete, icon: 'trash' as const, tone: 'danger' as const, onSelect: () => toast.warning(g.value.deletedToast) },
]);

/* ---- لایه‌ها ---------------------------------------------------------------- */

const couponDeleteOpen = ref(false);
const cmdOpen = ref(false);

function confirmCouponDelete(): void {
  couponDeleteOpen.value = false;
  toast.warning(g.value.couponDeleted);
}

const cmdGroups = computed<NxCommandGroup[]>(() => [
  {
    label: g.value.cmdGroup,
    items: [
      { id: 'dash', label: s.value.pages.dash.title, icon: 'grid', onSelect: () => go('dash') },
      { id: 'analytics', label: s.value.pages.analytics.title, icon: 'chart', onSelect: () => go('analytics') },
      { id: 'users', label: s.value.pages.users.title, icon: 'users', onSelect: () => go('users') },
      { id: 'settings', label: s.value.pages.settings.title, icon: 'settings', onSelect: () => go('settings') },
    ],
  },
]);

/* ---- هدر و قیمت‌گذاری -------------------------------------------------------- */

const headerItems = computed<NxNavItem[]>(() => [
  { label: s.value.pages.dash.title, href: hrefOf('dash'), current: true },
  { label: s.value.pages.analytics.title, href: hrefOf('analytics') },
  { label: s.value.pages.users.title, href: hrefOf('users') },
]);

const plans = computed<NxPricingPlan[]>(() => {
  const seat = (name: string): void => {
    toast.success(g.value.planChosen.replace('{name}', name));
  };
  return [
    {
      id: 'basic',
      name: g.value.planBasic,
      description: g.value.planBasicDesc,
      price: { monthly: 190, yearly: 1900 },
      period: { monthly: g.value.perMonth, yearly: g.value.perYear },
      features: [
        { label: g.value.featStorage, included: true },
        { label: g.value.featReports, included: true },
        { label: g.value.featLiveChat, included: false },
      ],
      cta: { label: g.value.planCta, onClick: () => seat(g.value.planBasic) },
    },
    {
      id: 'growth',
      name: g.value.planGrowth,
      description: g.value.planGrowthDesc,
      price: { monthly: 490, yearly: 4900 },
      period: { monthly: g.value.perMonth, yearly: g.value.perYear },
      featured: true,
      flag: g.value.planFlag,
      features: [
        { label: g.value.featStorage, included: true },
        { label: g.value.featReports, included: true },
        { label: g.value.featTeam, included: true },
      ],
      cta: { label: g.value.planCta, onClick: () => seat(g.value.planGrowth) },
    },
    {
      id: 'pro',
      name: g.value.planPro,
      description: g.value.planProDesc,
      price: { monthly: 990, yearly: 9900 },
      period: { monthly: g.value.perMonth, yearly: g.value.perYear },
      features: [
        { label: g.value.featTeam, included: true },
        { label: g.value.featLiveChat, included: true },
        { label: g.value.featManager, included: true },
      ],
      cta: { label: g.value.planCta, onClick: () => seat(g.value.planPro) },
    },
  ];
});

/* ---- لنگرهای هیرو ------------------------------------------------------------- */

const buttonsSeat = ref<HTMLElement | null>(null);
const pricingSeat = ref<HTMLElement | null>(null);
const scrollTo = (el: HTMLElement | null) => el?.scrollIntoView({ behavior: 'smooth', block: 'start' });
</script>

<template>
  <NxReveal stagger class="adm-gallery">
    <!-- ۱. هیرو — خانوادهٔ hero -->
    <NxHero
      class="adm-gallery-hero"
      backdrop="grid"
      align="start"
      :eyebrow="{ label: g.heroEyebrow, badge: g.heroBadge }"
      :title="g.heroTitle"
      :subtitle="g.heroSubtitle"
    >
      <template #actions>
        <NxButton variant="primary" icon="arrow-right" @click="scrollTo(pricingSeat)">{{ g.heroCta }}</NxButton>
        <NxButton variant="ghost" @click="scrollTo(buttonsSeat)">{{ g.heroSecondary }}</NxButton>
      </template>
    </NxHero>

    <!-- ۲. متن و اعداد — خانوادهٔ text -->
    <NxCard icon="wand" :title="g.textTitle" :description="g.textDesc">
      <div class="adm-gallery-text-demo">
        <h3><NxGradientText variant="brand" animate>{{ g.textGradient }}</NxGradientText></h3>
        <p class="adm-gallery-note"><NxShimmerText>{{ g.textShimmer }}</NxShimmerText></p>
        <p class="adm-gallery-note">
          {{ g.textRotatePrefix }}
          <NxWordRotate :words="g.rotateWords" />
        </p>
        <p class="adm-gallery-stat-line">
          <NxNumberTicker :value="987654" :locale="intlLocale" />
          <span class="adm-gallery-note">{{ g.textStatLabel }}</span>
        </p>
        <p class="adm-gallery-note"><NxHighlight>{{ g.highlightMark }}</NxHighlight> {{ g.highlightRest }}</p>
      </div>
    </NxCard>

    <!-- ۳. دکمه‌ها — خانوادهٔ button -->
    <div ref="buttonsSeat">
      <NxCard icon="zap" :title="g.buttonTitle" :description="g.buttonDesc">
        <div class="adm-gallery-stack">
          <div class="adm-gallery-row">
            <NxButton variant="primary" icon="check">{{ g.btnPrimary }}</NxButton>
            <NxButton variant="outline">{{ g.btnDraft }}</NxButton>
            <NxButton variant="ghost">{{ g.btnGhost }}</NxButton>
            <NxButton variant="danger" icon="trash">{{ g.btnDanger }}</NxButton>
            <NxButton variant="gold">{{ g.btnGold }}</NxButton>
            <NxButton variant="glow" effect="shine" magnetic ripple icon="zap">{{ g.btnFun }}</NxButton>
          </div>
          <div class="adm-gallery-row">
            <NxButton variant="secondary" icon="file" :status="shipState" @click="makeShippingLabel">{{ g.shipLabel }}</NxButton>
            <span class="adm-gallery-mono">{{ TRACKING }}</span>
            <NxCopyButton variant="outline" :value="TRACKING" @copied="toast.success(g.copiedTrack)" />
            <NxLikeButton :count="24" :label="g.likeLabel" />
            <NxIconButton icon="trash" :label="g.iconDelete" @click="toast.info(g.deletedToast)" />
          </div>
        </div>
      </NxCard>
    </div>

    <!-- ۴. فرم — خانوادهٔ form -->
    <NxCard icon="edit" :title="g.formTitle" :description="g.formDesc">
      <form class="adm-form" @submit.prevent="createCoupon">
        <div class="adm-form-grid">
          <NxField :label="g.fieldCode" class="adm-form-span">
            <NxInput v-model="couponCode" dir="ltr" :placeholder="g.codePlaceholder" />
          </NxField>
          <NxField :label="g.fieldPercent">
            <NxSlider v-model="discount" :min="5" :max="50" :step="5" show-value :format-value="percentOf" />
          </NxField>
          <NxField :label="g.fieldScope">
            <NxSelect v-model="scope" :options="scopeOptions" />
          </NxField>
        </div>
        <NxRadioGroup v-model="audience" name="coupon-audience" :legend="g.fieldAudience" :options="audienceOptions" />
        <div class="adm-gallery-row">
          <NxSwitch v-model="couponActive" :label="g.switchActive" :description="g.switchHint" />
          <NxCheckbox v-model="excludeSoldOut" :label="g.checkExclude" />
        </div>
        <div class="adm-gallery-row">
          <span class="adm-gallery-note">{{ g.chipsTitle }}</span>
          <NxChip v-for="id in couponProducts" :key="id" @remove="removeProduct(id)">{{ productLabel(id) }}</NxChip>
        </div>
        <NxField :label="g.otpLabel" :hint="g.otpHint">
          <NxOtpInput v-model="otp" :length="4" />
        </NxField>
        <div class="adm-form-actions">
          <NxButton type="submit" variant="primary" icon="check">{{ g.submitCoupon }}</NxButton>
        </div>
      </form>
    </NxCard>

    <!-- ۵. کارت‌ها — خانوادهٔ card -->
    <NxCard icon="grid" :title="g.cardTitle" :description="g.cardDesc">
      <div class="adm-gallery-stack">
        <div class="adm-gallery-stats">
          <NxStatCard :label="g.statRevenue" :value="4820" :delta="12.4" :trend="[31, 38, 35, 44, 41, 48]" :format="{ maximumFractionDigits: 0 }" :locale="intlLocale" />
          <NxStatCard :label="g.statOrders" :value="42" :delta="8" :trend="[12, 15, 11, 18, 16, 21]" :locale="intlLocale" />
          <NxStatCard :label="g.statScore" :value="96" :delta="-0.5" :trend="[97, 96, 96, 95, 96, 96]" :format="{ maximumFractionDigits: 0 }" :locale="intlLocale" />
        </div>
        <div class="adm-gallery-split">
          <NxFlipCard trigger="click" :flip-label="g.flipAria" class="adm-gallery-flip">
            <template #front>
              <div class="adm-gallery-flip-face">
                <strong>{{ g.flipFront }}</strong>
                <span class="adm-gallery-note">{{ g.flipFrontHint }}</span>
              </div>
            </template>
            <template #back>
              <div class="adm-gallery-flip-face">
                <span class="adm-gallery-note"><NxKbd>⌘K</NxKbd> {{ g.shortcutSearch }}</span>
                <span class="adm-gallery-note"><NxKbd>⌘P</NxKbd> {{ g.shortcutPrint }}</span>
              </div>
            </template>
          </NxFlipCard>
          <NxCard interactive spotlight href="#/analytics" icon="chart" :title="g.linkCardTitle" :description="g.linkCardDesc">
            <template #footer>
              <NxButton variant="ghost" size="sm" icon="arrow-right" href="#/analytics">{{ g.linkCardCta }}</NxButton>
            </template>
          </NxCard>
        </div>
      </div>
    </NxCard>

    <!-- ۶. نمودارها — خانوادهٔ chart -->
    <NxCard icon="chart" :title="g.chartTitle" :description="g.chartDesc">
      <div class="adm-gallery-split">
        <NxBarChart :title="g.chartSales" :data="salesBars" :locale="intlLocale" />
        <div class="adm-gallery-side">
          <NxDonutChart :title="g.chartStorage" :data="storageSplit" :locale="intlLocale" />
          <NxProgressRing :value="storagePct" :label="g.chartRing" tone="accent" />
        </div>
      </div>
    </NxCard>

    <!-- ۷. نمایش — خانوادهٔ display -->
    <NxCard icon="users" :title="g.displayTitle" :description="g.displayDesc">
      <div class="adm-gallery-stack">
        <div class="adm-gallery-row">
          <NxAvatar :name="s.app.user.name" status="online" />
          <NxAvatarGroup :people="teamPeople" :max="3" />
          <span class="adm-gallery-note">{{ g.teamLabel }}</span>
        </div>
        <NxDivider>{{ g.dividerText }}</NxDivider>
        <NxSteps :steps="orderSteps" :current="2" :aria-label="g.stepsAria" />
        <div class="adm-gallery-row">
          <NxRating v-model:value="stars" :label="g.ratingLabel" />
          <span class="adm-gallery-note">{{ g.kbdHint }} <NxKbd>⌘K</NxKbd></span>
        </div>
      </div>
    </NxCard>

    <!-- ۸. بازخورد — خانوادهٔ feedback -->
    <NxCard icon="bell" :title="g.feedbackTitle" :description="g.feedbackDesc">
      <div class="adm-gallery-stack">
        <NxAlert tone="warning" :title="g.alertTitle" dismissible>{{ g.alertBody }}</NxAlert>
        <div class="adm-gallery-row">
          <NxBadge tone="accent" variant="solid" dot pulse>{{ g.badgeLive }}</NxBadge>
          <NxBadge tone="success">{{ g.badgePaid }}</NxBadge>
          <NxBadge tone="warning">{{ g.badgePending }}</NxBadge>
          <NxBadge tone="danger">{{ g.badgeCanceled }}</NxBadge>
        </div>
        <div class="adm-gallery-row">
          <NxButton size="sm" variant="outline" @click="toast.success(g.tSuccess)">{{ g.toastSuccess }}</NxButton>
          <NxButton size="sm" variant="outline" @click="toast.info(g.tInfo)">{{ g.toastInfo }}</NxButton>
          <NxButton size="sm" variant="outline" @click="toast.warning(g.tWarning)">{{ g.toastWarning }}</NxButton>
          <NxButton size="sm" variant="outline" @click="toast.error(g.tDanger)">{{ g.toastDanger }}</NxButton>
        </div>
        <div class="adm-gallery-row">
          <NxTooltip :content="g.tooltipSync">
            <NxIconButton icon="info" :label="g.tooltipSync" />
          </NxTooltip>
          <NxProgress :value="68" :label="g.progressLabel" class="adm-gallery-progress" />
          <NxSpinner :label="g.progressLabel" size="sm" />
          <NxSkeleton :lines="2" class="adm-gallery-skeleton" />
        </div>
      </div>
    </NxCard>

    <!-- ۹. ناوبری — خانوادهٔ navigation -->
    <NxCard icon="layers" :title="g.navTitle" :description="g.navDesc">
      <div class="adm-gallery-stack">
        <NxBreadcrumbs :items="crumbs" />
        <NxTabs :items="orderTabs" variant="pill" :aria-label="g.tabsAria" />
        <div class="adm-gallery-row">
          <NxSegmentedControl v-model:value="range" :options="rangeOptions" size="sm" :aria-label="g.rangeAria" />
          <NxPagination v-model:page="invoicePage" :page-count="12" />
        </div>
        <div class="adm-gallery-two">
          <NxAccordion :items="faqItems" :default-open="[0]" />
          <NxMenu :items="orderMenuItems" align="end">
            <template #trigger>
              <NxButton variant="outline" size="sm" icon="sliders">{{ g.menuLabel }}</NxButton>
            </template>
          </NxMenu>
        </div>
      </div>
    </NxCard>

    <!-- ۱۰. دیالوگ و لایه‌ها — خانوادهٔ overlay -->
    <NxCard icon="message" :title="g.overlayTitle" :description="g.overlayDesc">
      <div class="adm-gallery-row">
        <NxDialog v-model:open="couponDeleteOpen" size="sm" :title="g.dialogTitle">
          <template #trigger>
            <NxButton variant="outline" icon="trash">{{ g.dialogTrigger }}</NxButton>
          </template>
          <p class="adm-gallery-note">{{ g.dialogBody }}</p>
          <template #footer>
            <NxButton variant="ghost" @click="couponDeleteOpen = false">{{ s.common.cancel }}</NxButton>
            <NxButton variant="danger" @click="confirmCouponDelete">{{ s.common.delete }}</NxButton>
          </template>
        </NxDialog>
        <NxPopover :label="g.popoverTitle">
          <template #trigger>
            <NxButton variant="ghost" icon="chevron-down">{{ g.popoverTrigger }}</NxButton>
          </template>
          <ul class="adm-gallery-lines">
            <li>{{ g.popCarrier }}</li>
            <li>{{ g.popCost }}</li>
            <li>{{ g.popTime }}</li>
          </ul>
        </NxPopover>
        <NxButton variant="secondary" icon="command" @click="cmdOpen = true">{{ g.cmdTrigger }}</NxButton>
        <NxCommandPalette v-model:open="cmdOpen" :hotkey="null" :groups="cmdGroups" :placeholder="g.cmdPlaceholder" :empty-text="g.cmdEmpty" />
      </div>
    </NxCard>

    <!-- ۱۱. هدر و تم — خانوادهٔ header -->
    <NxCard icon="home" :title="g.headerTitle" :description="g.headerDesc">
      <div class="adm-gallery-stack">
        <div class="adm-gallery-header-demo">
          <NxHeader :brand="s.app.brand" :items="headerItems" :aria-label="g.headerAria">
            <template #actions>
              <NxThemeToggle />
              <NxButton size="sm" variant="primary" @click="go('dash')">{{ g.headerCta }}</NxButton>
            </template>
          </NxHeader>
        </div>
        <div class="adm-gallery-row">
          <NxThemeToggle />
          <span class="adm-gallery-note">{{ g.themeHint }}</span>
        </div>
      </div>
    </NxCard>

    <!-- ۱۲. قیمت‌گذاری — خانوادهٔ pricing -->
    <div ref="pricingSeat">
      <NxCard icon="sparkles" :title="g.pricingTitle" :description="g.pricingDesc">
        <NxPricingTable
          :plans="plans"
          :billing-labels="{ monthly: g.billingMonthly, yearly: g.billingYearly }"
          :yearly-note="g.yearlyNote"
          :locale="intlLocale"
        />
      </NxCard>
    </div>
  </NxReveal>
</template>
