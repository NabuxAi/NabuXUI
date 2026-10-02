{{--
    The gauge's real scenarios: the support team's queue load (alive through
    Livewire state, so every morph re-runs the sweep and the needle from
    wherever they stopped), the Growth plan's token cap with hand-drawn zones,
    and a compact health board where "bad" points a different way in each dial.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The support queue, live', 'صف پشتیبانی، زنده') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The value lives in Livewire state and the shift buttons move it — the sweep and the needle ride custom properties with CSS transitions, so every morph re-runs them from exactly where they stopped and the digits roll. The zone the reading sits in recolours the sweep, the hub dot and the caption (Safe · Caution · Critical, from the core i18n table), and the same data ships as a hidden table for screen readers.', 'مقدار در state لایووایر زندگی می‌کند و دکمه‌های شیفت جابه‌جایش می‌کنند — جاروب و عقربه روی پراپرتی‌های سفارشی با ترنزیشن CSS سوارند، پس هر morph آن‌ها را از همان‌جایی که بودند دوباره می‌راند و ارقام می‌غلتند. ناحیه‌ای که عقربه در آن می‌نشیند رنگ جاروب، نقطهٔ محور و برچسب را عوض می‌کند (ایمن · احتیاط · بحرانی، از i18n هسته) و همان داده برای صفحه‌خوان‌ها به‌صورت جدول پنهان هم می‌رود.') }}
        </p>
    </div>
    <x-nx::gauge
        :value="$state['load'] ?? 72"
        unit="{{ $say('%', '٪') }}"
        :title="$say('Support queue load', 'بار صف پشتیبانی')"
        :subtitle="$say('All channels · this shift', 'همهٔ کانال‌ها · شیفت جاری')" />
    <div class="pg-row">
        <span style="font-size: var(--nx-text-sm); color: var(--nx-text-muted)">{{ $say('Shift:', 'شیفت:') }}</span>
        <x-nx::button size="xs" variant="ghost" wire:click="$set('state.load', 42)">{{ $say('Morning · 42%', 'صبح · ۴۲٪') }}</x-nx::button>
        <x-nx::button size="xs" variant="ghost" wire:click="$set('state.load', 72)">{{ $say('Evening · 72%', 'عصر · ۷۲٪') }}</x-nx::button>
        <x-nx::button size="xs" variant="ghost" wire:click="$set('state.load', 91)">{{ $say('Night rush · 91%', 'شلوغی شب · ۹۱٪') }}</x-nx::button>
    </div>
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('Without zones the dial takes its default thirds — safe to 60%, caution to 85%, critical beyond — and the reading formats in the page’s own digits (۷۲ on a Persian page, 72 on an English one).', 'بدون zones صفحه به سه‌قسمت پیش‌فرضش می‌نشیند — ایمن تا ۶۰٪، احتیاط تا ۸۵٪ و بحرانی بعد از آن — و عددِ خوانده‌شده با ارقام خودِ صفحه قالب می‌خورد (۷۲ روی صفحهٔ فارسی، 72 روی انگلیسی).') }}
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The Growth plan’s token cap', 'سقف توکن پلن رشد') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Zones need not be the default thirds: each takes its own upTo, tone and label, and the last one — upTo omitted — runs to the cap. A fractional value gains one decimal on its own and decimals="2" pins it down; 4.53 lands in “Near the cap” and wears its colour.', 'ناحیه‌ها الزاماً سه‌قسمتِ پیش‌فرض نیستند: هر کدام upTo و tone و label خودش را می‌گیرد و آخرین ناحیه — با upTo حذف‌شده — تا سقف می‌رود. عدد اعشاری بی‌هیچ کاری یک رقم اعشار می‌گیرد و decimals="2" آن را دقیق قفل می‌کند؛ ۴٫۵۳ روی «نزدیک سقف» می‌نشیند و رنگش را می‌پوشد.') }}
        </p>
    </div>
    <x-nx::gauge
        :value="4.53"
        :max="5"
        :decimals="2"
        unit="M"
        :title="$say('Model-token spend', 'مصرف ماهانهٔ توکن مدل')"
        :subtitle="$say('Growth plan · resets on the 1st', 'پلن رشد · اول ماه بازنشانی می‌شود')"
        :zones="[
            ['upTo' => 2.5, 'tone' => 'success', 'label' => $say('Calm', 'آرام')],
            ['upTo' => 4.2, 'tone' => 'warning', 'label' => $say('Busy', 'پرترافیک')],
            ['tone' => 'danger', 'label' => $say('Near the cap', 'نزدیک سقف')],
        ]" />
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('Leave a zone’s label off and the tone’s own word arrives from the core i18n table; zone-label="" removes the caption under the figure altogether.', 'برچسبِ ناحیه را ندهید تا واژهٔ خودِ tone از i18n هسته بیاید؛ zone-label="" هم برچسب زیر عدد را یک‌راست حذف می‌کند.') }}
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The agent’s health board', 'تختهٔ سلامت ایجنت') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Two compact dials (size rides --nx-gauge-size) — and “bad” points wherever you say: in the reply-time gauge less is better, so the success zone sits first; satisfaction is the other way round. The dial mirrors in RTL, min parking at the inline-start end.', 'دو گیج جمع‌وجور (size می‌رود روی --nx-gauge-size) — و «بد» به هر سمتی که بگویید اشاره می‌کند: در گیج زمان پاسخ، کمتر بهتر است پس ناحیهٔ موفق اول می‌نشیند؛ رضایت برعکس است. صفحه در RTL آینه می‌شود و min سرِ ابتدای جهت خواندن می‌نشیند.') }}
        </p>
    </div>
    <div class="pg-grid">
        <div class="pg-box" style="padding: 0; border: 0">
            <x-nx::gauge
                size="15rem"
                :value="412"
                :max="800"
                unit="ms"
                :title="$say('Median first reply', 'میانهٔ نخستین پاسخ')"
                :subtitle="$say('All channels · last hour', 'همهٔ کانال‌ها · ساعت گذشته')"
                :zones="[
                    ['upTo' => 300, 'tone' => 'success', 'label' => $say('Snappy', 'روان')],
                    ['upTo' => 600, 'tone' => 'warning', 'label' => $say('Slowing', 'کمی سنگین')],
                    ['tone' => 'danger', 'label' => $say('Stuck', 'گیر کرده')],
                ]" />
        </div>
        <div class="pg-box" style="padding: 0; border: 0">
            <x-nx::gauge
                size="15rem"
                :value="93"
                unit="{{ $say('%', '٪') }}"
                :title="$say('Conversation satisfaction', 'رضایت گفت‌وگو')"
                :subtitle="$say('Ratings this week', 'امتیازهای این هفته')"
                :zones="[
                    ['upTo' => 70, 'tone' => 'danger', 'label' => $say('Needs care', 'نیازمند توجه')],
                    ['upTo' => 90, 'tone' => 'warning', 'label' => $say('Good', 'خوب')],
                    ['tone' => 'success', 'label' => $say('Excellent', 'عالی')],
                ]" />
        </div>
    </div>
</section>
