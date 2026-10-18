<script lang="ts">
  /**
   * گالری کامپوننت‌ها — هر ۱۲ خانوادهٔ @nabuxai/ui-svelte، هرکدام در یک
   * سناریوی فشرده و واقعی: همین صفحه با خودِ آن کامپوننت‌ها ساخته شده
   * (پوستهٔ هر بخش یک Card خودِ پکیج است). استایل‌ها از همان css هسته‌اند که
   * main.ts از روز اول وارد کرده (`import '@nabuxai/ui-core/css'`)؛ این فایل
   * فقط چیدمان خودش را با اسکوپ خودش می‌چیند. متن‌ها از lang.ts اپ می‌آیند؛
   * ارقام و جهت، از خود سند پیروی می‌کنند.
   */
  import {
    GradientText, ShimmerText, WordRotate, NumberTicker, Highlight, ScrambleText, Reveal,
    Button, CopyButton, LikeButton, IconButton,
    Field, Input, Select, Checkbox, Switch, RadioGroup, Slider, OtpInput, Chip,
    Card, StatCard, FlipCard, StackCards,
    BarChart, AreaChart, DonutChart, Sparkline, ProgressRing,
    Avatar, AvatarGroup, Kbd, Rating, Steps, Marquee, Divider,
    toast, Alert, Badge, Tooltip, Progress, Skeleton, Spinner, OrbitLoader, DotsLoader, CuneiformLoader,
    Tabs, SegmentedControl, Breadcrumbs, Pagination, Accordion, Menu,
    Dialog, Drawer, Popover, CommandPalette,
    Header, RouteProgress, ThemeToggle,
    Hero,
    PricingTable,
  } from '@nabuxai/ui-svelte';
  import { strings } from '../store.svelte';
  import { go } from '../router.svelte';

  const s = $derived(strings());
  const c = $derived(s.pages.components);

  /* ---- داده‌های بی‌زبانِ نمودارها — برچسب‌ها از دیکشنری می‌آیند -------------------- */
  const SALES = [42, 55, 48, 61, 58, 72, 66];
  const VISITS = [310, 355, 320, 391, 372, 448, 410];
  const ORDERS = [28, 34, 31, 40, 37, 47, 43];
  const TREND_HAPPY = [82, 84, 83, 86, 88, 87, 91];
  const TREND_CHURN = [9, 8, 9, 7, 6, 6, 4];

  /* ---- وضعیت‌های کوچکِ تعاملی گالری ------------------------------------------------ */
  let productName = $state('');
  let category = $state('');
  let stock = $state(35);
  let giftWrap = $state(true);
  let lowStockAlert = $state(false);
  let layout = $state('grid');
  let otp = $state('');
  let tags = $state<string[]>([]);
  let ordersPage = $state(3);
  let routeState = $state<'idle' | 'loading' | 'done'>('loading');
  let paletteOpen = $state(false);

  const tagPool = $derived([c.formTagBestseller, c.formTagEco, c.formTagHandmade]);
  const toggleTag = (tag: string) => {
    tags = tags.includes(tag) ? tags.filter((t) => t !== tag) : [...tags, tag];
  };

  /* ---- فهرست‌هایی که به زبان صفحه وابسته‌اند ---------------------------------------- */
  const categoryOptions = $derived([
    { value: 'bags', label: c.formCatBags },
    { value: 'scarves', label: c.formCatScarves },
    { value: 'watches', label: c.formCatWatches },
  ]);
  const layoutOptions = $derived([
    { value: 'grid', label: c.formLayoutGrid, icon: 'grid' as const },
    { value: 'list', label: c.formLayoutList, icon: 'layers' as const },
  ]);
  const routeOptions = $derived([
    { value: 'idle', label: c.navRouteIdle },
    { value: 'loading', label: c.navRouteLoading },
    { value: 'done', label: c.navRouteDone },
  ]);
  const tabItems = $derived([
    { value: 'overview', label: c.navTabOverview, content: c.navTabOverviewBody },
    { value: 'usage', label: c.navTabUsage, content: c.navTabUsageBody },
    { value: 'a11y', label: c.navTabA11y, content: c.navTabA11yBody },
  ]);
  const breadcrumbItems = $derived([
    { label: c.navBreadHome, href: '#/dash' },
    { label: c.navBreadStore, href: '#/invoices' },
    { label: c.navBreadBags, href: '#/components' },
    { label: c.navBreadItem },
  ]);
  const accordionItems = $derived([
    { id: 'free', title: c.navAccQ1, content: c.navAccA1 },
    { id: 'rtl', title: c.navAccQ2, content: c.navAccA2 },
  ]);
  const menuEntries = $derived([
    { label: c.navMenuOpen, icon: 'external-link' as const },
    { label: c.navMenuDuplicate, icon: 'file' as const },
    { type: 'separator' as const },
    { type: 'label' as const, label: c.navMenuMore },
    { label: c.navMenuDelete, icon: 'x' as const, tone: 'danger' as const, shortcut: '⌫' },
  ]);
  const supportTeam = $derived([
    s.people.maryam, s.people.ali, s.people.sara, s.people.mina, s.app.user.name,
  ].map((name) => ({ name })));
  const stepItems = $derived([
    { title: c.displayStepCart },
    { title: c.displayStepAddress },
    { title: c.displayStepPay },
    { title: c.displayStepDone },
  ]);
  const barData = $derived(c.chartDays.map((day, i) => ({ label: day, value: SALES[i] ?? 0 })));
  const areaSeries = $derived([
    { name: c.chartSeriesVisits, values: VISITS },
    { name: c.chartSeriesOrders, values: ORDERS },
  ]);
  const donutData = $derived([
    { label: c.chartChannelStore, value: 52 },
    { label: c.chartChannelWeb, value: 28 },
    { label: c.chartChannelMarket, value: 20 },
  ]);
  const headerNav = $derived([
    { label: c.headerHome, href: '#/dash', current: true },
    {
      label: c.headerProducts,
      children: [
        { label: c.headerChildBags, href: '#/components', description: c.headerChildBagsDesc },
        { label: c.headerChildWatches, href: '#/components', description: c.headerChildWatchesDesc },
      ],
    },
  ]);
  const paletteGroups = $derived([
    {
      label: c.overlayPaletteGroupPages,
      items: [
        { id: 'dash', label: c.overlayPaletteGoDash, icon: 'grid' as const, hint: '#/dash', onSelect: () => { paletteOpen = false; go('dash'); } },
        { id: 'users', label: c.overlayPaletteGoUsers, icon: 'users' as const, hint: '#/users', onSelect: () => { paletteOpen = false; go('users'); } },
      ],
    },
    {
      label: c.overlayPaletteGroupActions,
      items: [
        { id: 'toast', label: c.overlayPaletteToast, icon: 'sparkles' as const, shortcut: 'T', onSelect: () => { paletteOpen = false; toast.success(c.feedbackToastSuccess); } },
      ],
    },
  ]);
  const plans = $derived([
    {
      id: 'starter',
      name: c.planStarter,
      description: c.planStarterDesc,
      price: { monthly: 9, yearly: 90 },
      period: { monthly: c.pricingPeriod, yearly: c.pricingPeriod },
      features: [
        { label: c.planStarterF1 },
        { label: c.planStarterF2 },
        { label: c.planStarterF3, included: false },
      ],
      cta: { label: c.pricingCta, href: '#/settings' },
    },
    {
      id: 'pro',
      name: c.planPro,
      description: c.planProDesc,
      featured: true,
      flag: c.pricingFlag,
      price: { monthly: 29, yearly: 290 },
      period: { monthly: c.pricingPeriod, yearly: c.pricingPeriod },
      features: [
        { label: c.planProF1 },
        { label: c.planProF2 },
        { label: c.planProF3 },
        { label: c.planProF4 },
      ],
      cta: { label: c.pricingCta, href: '#/settings' },
    },
    {
      id: 'business',
      name: c.planBusiness,
      description: c.planBusinessDesc,
      price: { monthly: 99, yearly: 990 },
      period: { monthly: c.pricingPeriod, yearly: c.pricingPeriod },
      features: [
        { label: c.planBusinessF1 },
        { label: c.planBusinessF2 },
        { label: c.planBusinessF3 },
        { label: c.planBusinessF4 },
      ],
      cta: { label: c.pricingCta, href: '#/settings' },
    },
  ]);
</script>

<section class="cmp-gallery">
  <header class="cmp-head">
    <h1>{c.title}</h1>
    <p>{c.intro}</p>
    <p class="cmp-note">{c.seatsNote}</p>
  </header>

  <!-- ۱ · متن ------------------------------------------------------------------------------ -->
  <Card variant="outline" title={c.textTitle} description={c.textCaption}>
    <div class="cmp-row cmp-wrap cmp-center">
      <GradientText as="strong" variant="brand" animate>{c.textGradient}</GradientText>
      <ShimmerText text={c.textShimmer} variant="wave" />
      <span class="cmp-inline">
        {c.textRotateLead}
        <WordRotate words={c.textRotateWords} />
      </span>
      <span class="cmp-inline">
        <NumberTicker value={12480} />
        <span class="cmp-muted">{c.textTickerLabel}</span>
      </span>
      <ScrambleText text={c.textScramble} />
    </div>
    <p class="cmp-line">
      {c.textHighlightLead}
      <Highlight variant="circle">{c.textHighlightMark}</Highlight>
      {c.textHighlightTail}
    </p>
    <Reveal stagger class="cmp-reveal">
      {#each c.textRevealLines as line (line)}
        <p class="cmp-muted">{line}</p>
      {/each}
    </Reveal>
  </Card>

  <!-- ۲ · دکمه ----------------------------------------------------------------------------- -->
  <Card variant="outline" title={c.buttonTitle} description={c.buttonCaption}>
    <div class="cmp-row cmp-wrap cmp-center">
      <Button text={s.common.save} variant="primary" magnetic ripple onclick={() => toast.success(c.feedbackToastSuccess)} />
      <Button text={c.buttonPreview} variant="secondary" />
      <Button text={c.buttonGuide} variant="outline" />
      <Button text={c.buttonGhost} variant="ghost" />
      <Button text={s.common.delete} variant="danger" />
      <Button text={c.buttonGold} variant="gold" effect="shine" />
      <Button text={c.buttonLoading} status="loading" />
    </div>
    <div class="cmp-row cmp-wrap cmp-center">
      <CopyButton value="NX-1248" text={c.buttonCopyId} onCopied={() => toast(c.buttonCopiedToast)} />
      <LikeButton count={128} label={c.buttonLikeLabel} variant="outline" />
      <IconButton icon="heart" label={c.buttonIconLabel} variant="outline" />
    </div>
  </Card>

  <!-- ۳ · فرم ------------------------------------------------------------------------------- -->
  <Card variant="outline" title={c.formTitle} description={c.formCaption}>
    <div class="cmp-form">
      <Field label={c.formNameLabel} hint={c.formNameHint}>
        <Input placeholder={c.formNamePlaceholder} value={productName} oninput={(e) => (productName = e.currentTarget.value)} />
      </Field>
      <Field label={c.formCategoryLabel}>
        <Select placeholder={c.formCategoryLabel} options={categoryOptions} onchange={(e) => (category = e.currentTarget.value)} />
      </Field>
      <div class="cmp-field">
        <span class="cmp-label">{c.formStockLabel}</span>
        <Slider min={0} max={100} bind:value={stock} showValue startLabel={c.formStockMin} endLabel={c.formStockMax} />
      </div>
      <div class="cmp-field">
        <span class="cmp-label">{c.formOtpLabel}</span>
        <OtpInput bind:value={otp} separatorAfter={3} onComplete={() => toast.success(c.formOtpDone)} />
        <span class="cmp-muted">{c.formOtpHint}</span>
      </div>
      <div class="cmp-choices cmp-wrap">
        <Checkbox label={c.formGiftLabel} description={c.formGiftHint} checked={giftWrap} onCheckedChange={(v) => (giftWrap = v)} />
        <Switch label={c.formNotifyLabel} description={c.formNotifyHint} size="sm" checked={lowStockAlert} onCheckedChange={(v) => (lowStockAlert = v)} />
      </div>
      <RadioGroup name="cmp-layout" legend={c.formLayoutLabel} orientation="horizontal" options={layoutOptions} value={layout} onValueChange={(v) => (layout = v)} />
      <div class="cmp-field">
        <span class="cmp-label">{c.formTagsLabel}</span>
        <div class="cmp-row cmp-wrap">
          {#each tagPool as tag (tag)}
          <span class="cmp-tag" class:selected={tags.includes(tag)}>
            <Chip text={tag} onRemove={() => toggleTag(tag)} />
          </span>
          {/each}
        </div>
        <span class="cmp-muted">{tags.map((t) => t).join(' · ') || c.formTagsLabel}</span>
      </div>
    </div>
  </Card>

  <!-- ۴ · کارت ------------------------------------------------------------------------------ -->
  <!-- snippetهای پشته در بلاکِ section اعلام می‌شوند تا در Card property حساب نشوند. -->
  {#snippet stackOne()}<span>{c.cardStackOne}</span>{/snippet}
  {#snippet stackTwo()}<span>{c.cardStackTwo}</span>{/snippet}
  {#snippet stackThree()}<span>{c.cardStackThree}</span>{/snippet}
  <Card variant="outline" title={c.cardTitle} description={c.cardCaption}>
    <div class="cmp-grid cmp-grid-3">
      <Card
        interactive
        spotlight
        tilt
        icon="image"
        title={c.cardDemoTitle}
        description={c.cardDemoDesc}
        footer={c.cardDemoFooter}
      >
        <Button text={c.cardDemoAction} variant="secondary" size="sm" />
      </Card>
      <StatCard label={c.cardStatLabel} value={4820} delta={12} trend={TREND_HAPPY} caption={c.cardStatCaption} />
      <FlipCard trigger="click" flipLabel={c.cardFlipLabel} class="cmp-flip">
        {#snippet front()}
          <strong>{c.cardFlipFrontTitle}</strong>
          <span class="cmp-muted">{c.cardFlipFrontHint}</span>
        {/snippet}
        {#snippet back()}
          <strong>{c.cardFlipBackTitle}</strong>
          <span class="cmp-muted">{c.cardFlipBackHint}</span>
        {/snippet}
      </FlipCard>
    </div>
    <StackCards items={[stackOne, stackTwo, stackThree]} aria-label={c.cardStackLabel} class="cmp-stack" />
  </Card>

  <!-- ۵ · نمودار ---------------------------------------------------------------------------- -->
  <Card variant="outline" title={c.chartTitle} description={c.chartCaption}>
    <div class="cmp-grid cmp-grid-2">
      <BarChart data={barData} title={c.chartBarTitle} height={180} />
      <AreaChart labels={c.chartDays} series={areaSeries} title={c.chartAreaTitle} height={180} />
      <DonutChart data={donutData} title={c.chartDonutTitle} centerLabel={c.chartCenterLabel} size="9rem" />
      <div class="cmp-rings">
        <ProgressRing value={72} label={c.chartRingLabel} />
        <div class="cmp-sparks">
          <span class="cmp-inline"><Sparkline data={TREND_HAPPY} trend="up" />{c.chartSparkHappy}</span>
          <span class="cmp-inline"><Sparkline data={TREND_CHURN} trend="down" />{c.chartSparkChurn}</span>
        </div>
      </div>
    </div>
  </Card>

  <!-- ۶ · نمایش ----------------------------------------------------------------------------- -->
  <Card variant="outline" title={c.displayTitle} description={c.displayCaption}>
    <div class="cmp-row cmp-wrap cmp-center">
      <span class="cmp-inline cmp-muted">{c.displayTeamLabel}</span>
      <Avatar name={s.people.maryam} status="online" />
      <Avatar name={s.people.ali} status="busy" />
      <Avatar name={s.people.sara} status="away" />
      <AvatarGroup people={supportTeam} max={4} label={c.displayGroupLabel} />
    </div>
    <div class="cmp-row cmp-wrap cmp-center">
      <Rating defaultValue={4} label={c.displayRatingLabel} />
      <span class="cmp-inline cmp-muted">
        {c.displayKbdHint}: <Kbd>⌘</Kbd><Kbd>K</Kbd>
      </span>
    </div>
    <Steps steps={stepItems} current={1} aria-label={c.displayStepsLabel} />
    <Marquee items={c.displayMarqueeItems} aria-label={c.displayMarqueeLabel} />
    <Divider>{c.displayDividerText}</Divider>
  </Card>

  <!-- ۷ · بازخورد --------------------------------------------------------------------------- -->
  <Card variant="outline" title={c.feedbackTitle} description={c.feedbackCaption}>
    <div class="cmp-stack-vert">
      <Alert tone="success" title={c.feedbackAlertTitle} dismissible>
        {c.feedbackAlertBody}
        {#snippet actions()}
          <Button variant="link" size="sm" text={c.feedbackAlertAction} href="#/analytics" />
        {/snippet}
      </Alert>
      <div class="cmp-row cmp-wrap cmp-center">
        <Badge tone="accent" dot>{c.feedbackBadgeNew}</Badge>
        <Badge tone="success" dot pulse>{c.feedbackBadgeLive}</Badge>
        <Badge tone="gold" variant="solid">{c.feedbackBadgeGold}</Badge>
        <Tooltip content={c.feedbackTooltipHint}>
          {#snippet children(anchor)}
            <Button {...anchor} variant="secondary" size="sm" text={s.common.save} />
          {/snippet}
        </Tooltip>
        <Button variant="outline" size="sm" text={c.feedbackShowToast} onclick={() => toast.success(c.feedbackToastSuccess)} />
        <Button variant="ghost" size="sm" text={c.feedbackShowError} onclick={() => toast.error(c.feedbackToastError)} />
      </div>
      <div class="cmp-row cmp-wrap">
        <div class="cmp-grow">
          <span class="cmp-label">{c.feedbackProgressLabel}</span>
          <Progress value={64} />
        </div>
        <div class="cmp-grow">
          <span class="cmp-label">{c.feedbackSkeletonLabel}</span>
          <Skeleton lines={2} />
        </div>
      </div>
      <div class="cmp-row cmp-wrap cmp-center">
        <Spinner size="sm" />
        <OrbitLoader size="sm" />
        <DotsLoader />
        <CuneiformLoader size="sm" />
      </div>
    </div>
  </Card>

  <!-- ۸ · ناوبری ---------------------------------------------------------------------------- -->
  <Card variant="outline" title={c.navigationTitle} description={c.navigationCaption}>
    <Tabs items={tabItems} aria-label={c.navigationTitle} />
    <div class="cmp-row cmp-wrap cmp-center">
      <SegmentedControl options={layoutOptions} value={layout} onValueChange={(v) => (layout = v)} aria-label={c.formLayoutLabel} />
      <Breadcrumbs items={breadcrumbItems} />
      <Menu items={menuEntries} aria-label={c.navMenuAriaLabel}>
        {#snippet trigger(attrs)}
          <Button {...attrs} variant="secondary" size="sm" text={c.navMenuAriaLabel} />
        {/snippet}
      </Menu>
    </div>
    <Pagination page={ordersPage} pageCount={9} onPageChange={(p) => (ordersPage = p)} />
    <Accordion items={accordionItems} defaultOpen={['free']} />
  </Card>

  <!-- ۹ · همپوشانی -------------------------------------------------------------------------- -->
  <Card variant="outline" title={c.overlayTitle} description={c.overlayCaption}>
    <div class="cmp-row cmp-wrap cmp-center">
      <Dialog title={c.overlayDialogTitle} description={c.overlayDialogDesc} size="sm">
        {#snippet trigger(attrs)}
          <Button {...attrs} variant="secondary" text={c.overlayDialogButton} />
        {/snippet}
        <p>{c.overlayDialogBody}</p>
        {#snippet footer()}
          <form method="dialog" class="cmp-dialog-actions">
            <!-- type="submit" لازم است: Button پیش‌فرض type="button" دارد و بدون آن
                 فرمِ method="dialog" سابمیت نمی‌شود و دیالوگ با «انصراف» بسته نمی‌شود. -->
            <Button variant="ghost" text={s.common.cancel} type="submit" />
            <Button text={c.overlayDialogPublish} type="submit" onclick={() => toast.success(c.feedbackToastSuccess)} />
          </form>
        {/snippet}
      </Dialog>
      <Drawer title={c.overlayDrawerTitle} description={c.overlayDrawerDesc} side="start">
        {#snippet trigger(attrs)}
          <Button {...attrs} variant="outline" text={c.overlayDrawerButton} />
        {/snippet}
        <p>{c.overlayDrawerBody}</p>
      </Drawer>
      <Popover label={c.overlayPopoverButton}>
        {#snippet trigger(attrs)}
          <Button {...attrs} variant="ghost" text={c.overlayPopoverButton} />
        {/snippet}
        <p>{c.overlayPopoverBody}</p>
      </Popover>
      <Button variant="primary" text={c.overlayPaletteButton} onclick={() => (paletteOpen = true)} />
    </div>
    <!-- ‌hotkey خالی می‌ماند تا ⌘K پنل (جست‌وجوی کاربران) دست‌نخورده بماند. -->
    <CommandPalette
      groups={paletteGroups}
      open={paletteOpen}
      onOpenChange={(open) => (paletteOpen = open)}
      hotkey={null}
      placeholder={c.overlayPalettePlaceholder}
      emptyText={c.overlayPaletteEmpty}
    />
  </Card>

  <!-- ۱۰ · سربرگ ---------------------------------------------------------------------------- -->
  <Card variant="outline" title={c.headerTitle} description={c.headerCaption}>
    <Header brand={c.headerBrand} brandHref="#/dash" items={headerNav}>
      {#snippet actions()}
        <Button variant="outline" size="sm" text={c.headerAction} data-desktop onclick={() => toast(c.headerAction)} />
      {/snippet}
    </Header>
    <div class="cmp-row cmp-wrap">
      <div class="cmp-grow">
        <span class="cmp-label">{c.navSegRouteLabel}</span>
        <SegmentedControl options={routeOptions} value={routeState} onValueChange={(v) => (routeState = v as typeof routeState)} aria-label={c.navSegRouteLabel} />
        <span class="cmp-muted">{c.headerRouteNote}</span>
      </div>
      <RouteProgress state={routeState} />
      <div class="cmp-grow">
        <span class="cmp-label">{c.headerThemeNote}</span>
        <ThemeToggle />
      </div>
    </div>
  </Card>

  <!-- ۱۱ · هیرو ----------------------------------------------------------------------------- -->
  <Card variant="outline" title={c.heroTitle} description={c.heroCaption}>
    <Hero
      eyebrow={{ label: c.heroEyebrow, badge: c.heroBadge }}
      title={c.heroTitleLine}
      subtitle={c.heroSubtitle}
      align="center"
    >
      {#snippet actions()}
        <Button text={c.heroPrimary} onclick={() => toast.success(c.feedbackToastSuccess)} />
        <Button variant="outline" text={c.heroSecondary} href="#/dash" />
      {/snippet}
    </Hero>
  </Card>

  <!-- ۱۲ · تعرفه ---------------------------------------------------------------------------- -->
  <Card variant="outline" title={c.pricingTitle} description={c.pricingCaption}>
    <PricingTable
      plans={plans}
      billingLabels={{ monthly: c.pricingMonthly, yearly: c.pricingYearly }}
      yearlyNote={c.pricingYearlyNote}
    />
  </Card>
</section>

<style>
  /* چیدمان گالری — با متغیرهای خود NabuXUI؛ همه‌چیز auto-fit تا در باریک‌ترین صفحه هم بشکند.
     ستون صریح minmax(0,1fr) مثل adm-gallery در طعم Vue لازم است: ستون ضمنی گرید به
     min-content محتوا (عنوان‌ها/ردیف‌ها) سایه می‌گیرد و در 375px از viewport بیرون می‌زند. */
  .cmp-gallery {
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    gap: var(--nx-space-5);
  }

  .cmp-gallery > * {
    min-inline-size: 0;
  }

  .cmp-head {
    display: grid;
    gap: var(--nx-space-2);
  }

  .cmp-head > h1 {
    margin: 0;
    font: 700 var(--nx-text-3xl) / 1.25 var(--nx-font-display);
    letter-spacing: var(--nx-tracking-tight);
  }

  .cmp-head > p {
    margin: 0;
    max-inline-size: 52rem;
    color: var(--nx-text-muted);
  }

  .cmp-note {
    font-size: var(--nx-text-sm);
    color: var(--nx-text-subtle);
  }

  .cmp-row {
    display: flex;
    align-items: center;
    gap: var(--nx-space-3);
  }

  .cmp-wrap {
    flex-wrap: wrap;
  }

  .cmp-center {
    justify-content: center;
  }

  .cmp-inline {
    display: inline-flex;
    align-items: center;
    gap: var(--nx-space-2);
  }

  .cmp-muted {
    color: var(--nx-text-muted);
    font-size: var(--nx-text-sm);
  }

  .cmp-line {
    margin: 0;
  }

  /* این چهار کلاس به کامپوننت‌ها به‌شکل class prop می‌روند؛ پس بی‌اسکوپ می‌مانند. */
  :global(.cmp-reveal) {
    display: grid;
    gap: var(--nx-space-1);
  }

  .cmp-form {
    display: grid;
    /* مثل خود گالری: ستون صریح تا ردیف OTP/اسلایدر min-content گرید راwide نکند. */
    grid-template-columns: minmax(0, 1fr);
    gap: var(--nx-space-4);
  }

  /* ردیف OTP پایهٔ intrinsic دارد (۶ × 3rem) و در اندازه‌گیری min-content گرید
     کل فرم را تا ۳۳۶px می‌کشد؛ مثل جریان auth (.nx-auth-code-boxes در flows.css)
     اندازهٔ مشخص می‌دهیم تا خانه‌ها طبق طراحی تا 2.25rem جمع شوند. */
  .cmp-field :global(.nx-otp) {
    inline-size: min(100%, 17.5rem);
  }

  .cmp-field {
    display: grid;
    gap: var(--nx-space-2);
    justify-items: start;
  }

  .cmp-label {
    font-size: var(--nx-text-sm);
    font-weight: 600;
  }

  .cmp-choices {
    display: flex;
    gap: var(--nx-space-6);
  }

  .cmp-tag {
    display: inline-flex;
  }

  .cmp-tag.selected :global(.nx-chip) {
    outline: 2px solid var(--nx-accent);
    outline-offset: 1px;
  }

  .cmp-grid {
    display: grid;
    gap: var(--nx-space-4);
  }

  @media (min-width: 48rem) {
    .cmp-grid-2 {
      grid-template-columns: repeat(2, minmax(0, 1fr));
    }

    .cmp-grid-3 {
      grid-template-columns: repeat(auto-fit, minmax(14rem, 1fr));
    }
  }

  :global(.cmp-flip) {
    min-block-size: 9rem;
  }

  :global(.cmp-flip .nx-flip-face) {
    display: grid;
    gap: var(--nx-space-2);
    align-content: center;
    padding: var(--nx-space-4);
  }

  :global(.cmp-stack) {
    margin-block-start: var(--nx-space-4);
  }

  .cmp-rings {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-evenly;
    gap: var(--nx-space-4);
  }

  .cmp-sparks {
    display: grid;
    gap: var(--nx-space-3);
  }

  .cmp-stack-vert {
    display: grid;
    gap: var(--nx-space-4);
  }

  .cmp-grow {
    display: grid;
    gap: var(--nx-space-2);
    flex: 1 1 16rem;
  }

  .cmp-dialog-actions {
    display: flex;
    gap: var(--nx-space-2);
    justify-content: flex-end;
  }
</style>
