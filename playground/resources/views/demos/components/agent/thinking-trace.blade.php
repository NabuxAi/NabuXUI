{{--
    A coding assistant answering a migration question: the trace runs live (steps
    arrive, the clock ticks, then it folds into "Thought for …"), and an earlier
    answer in the same thread carries a finished trace from history — one step failed.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $labels = ['thinking' => $say('Thinking…', 'در حال فکر…'), 'thought' => $say('Thought for :time', ':time فکر کرد')];

    $plan = [
        $say('Reading the orders schema', 'خواندن ساختار جدول سفارش‌ها'),
        $say('Finding queries that filter by status', 'یافتن کوئری‌هایی که بر اساس وضعیت فیلتر می‌کنند'),
        $say('Comparing a composite index with two single ones', 'مقایسهٔ ایندکس ترکیبی با دو ایندکس تکی'),
        $say('Drafting a reversible migration', 'نوشتن مایگریشن برگشت‌پذیر'),
    ];
    $history = [
        ['id' => 'h1', 'label' => $say('Opened the failing test', 'باز کردن تست ناموفق'), 'status' => 'done'],
        ['id' => 'h2', 'label' => $say('Ran the suite with --filter=Checkout', 'اجرای تست‌ها با --filter=Checkout'), 'status' => 'error', 'detail' => $say('Timed out after 30s — skipped', 'پس از ۳۰ ثانیه زمانش تمام شد — رد شد')],
        ['id' => 'h3', 'label' => $say('Traced the null to the coupon cast', 'ردیابی null تا تبدیل نوع کوپن'), 'status' => 'done'],
    ];
@endphp
<style>
    .agd-thread { display: grid; gap: 1rem; max-inline-size: 44rem; }
    .agd-msg { display: grid; gap: .625rem; padding: 1rem 1.125rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-bg-subtle); }
    .agd-msg[data-me] { justify-self: end; max-inline-size: 30rem; background: var(--nx-accent-soft); border-color: var(--nx-accent-border); }
    .agd-who { display: flex; align-items: center; gap: .5rem; margin: 0; font: 650 var(--nx-text-sm) / 1 var(--nx-font-sans); color: var(--nx-text-muted); }
    .agd-msg p { margin: 0; line-height: 1.7; }
</style>

<div class="agd-thread"
    x-data="{
        demoActive: false,
        demoStart: 0,
        demoSteps: [],
        done: false,
        timers: [],
        run() {
            this.timers.forEach(clearTimeout);
            this.timers = [];
            this.done = false;
            this.demoSteps = [];
            this.demoStart = Date.now();
            this.demoActive = true;
            const plan = @js($plan);
            plan.forEach((label, i) => this.timers.push(setTimeout(() => {
                this.demoSteps = [
                    ...this.demoSteps.map((step) => ({ ...step, status: 'done' })),
                    { id: 's' + i, label, status: 'running' },
                ];
            }, 700 + i * 1300)));
            this.timers.push(setTimeout(() => {
                this.demoSteps = this.demoSteps.map((step) => ({ ...step, status: 'done' }));
                this.demoActive = false;
                this.done = true;
            }, 700 + plan.length * 1300 + 500));
        },
    }" x-init="run()">
    <div class="agd-msg" data-me>
        <p>{{ $say('Orders by status are slow in the admin panel. Should I add an index?', 'لیست سفارش‌ها بر اساس وضعیت در پنل ادمین کند است. ایندکس اضافه کنم؟') }}</p>
    </div>
    <div class="agd-msg">
        <p class="agd-who"><x-nx::thinking-orbs size="sm" x-bind:data-state="demoActive ? 'thinking' : 'idle'" :label="$say('Assistant', 'دستیار')" /> {{ $say('Assistant', 'دستیار') }}</p>
        <x-nx::thinking-trace :labels="$labels" :active="false"
            x-bind:data-active="demoActive ? '1' : '0'" x-bind:data-steps="JSON.stringify(demoSteps)" x-bind:data-started-at="demoStart" />
        <p x-show="done" x-cloak x-transition.opacity.duration.250ms>
            {{ $say('Yes — a composite index on (status, created_at) covers both the filter and the sort. The migration is reversible and safe to run online.', 'بله — یک ایندکس ترکیبی روی (status, created_at) هم فیلتر و هم مرتب‌سازی را پوشش می‌دهد. مایگریشن برگشت‌پذیر است و بدون توقف اجرا می‌شود.') }}
        </p>
        <div class="pg-row">
            <x-nx::button size="sm" variant="ghost" icon="sparkles" x-on:click="run()">{{ $say('Ask again', 'دوباره بپرس') }}</x-nx::button>
        </div>
    </div>

    <div class="agd-msg">
        <p class="agd-who">{{ $say('Earlier today', 'امروز صبح') }}</p>
        <x-nx::thinking-trace :steps="$history" :duration="14" :labels="$labels" />
        <p>{{ $say('The checkout bug came from casting an empty coupon to null; a guard fixes it.', 'باگ پرداخت از تبدیل کوپن خالی به null بود؛ یک شرط محافظ درستش می‌کند.') }}</p>
    </div>
</div>
