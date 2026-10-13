{{--
    The wizard's real scenarios: a workspace setup whose review pane reads
    every answer back from the form's own controls, a workshop signup that
    drops the review pane and renames its own buttons, and a facilities
    ticket that resumes a half-filled draft straight from its second step.
    Each wizard sits inside wire:ignore: the panes and their answers are the
    browser's, so a Livewire round-trip (the toast after submit) never
    clobbers them — while wire:submit on the root still fires through.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $n = fn (int|float $value) => NabuXUI::formatNumber($value, 0, $fa ? 'fa' : 'en');

    // Scenario 2 — this instance's own words, fed through the labels array.
    $workshopLabels = [
        'next' => $say('Continue', 'ادامه'),
        'submit' => $say('Reserve my seat', 'رزرو جای من'),
        'back' => $say('Back', 'بازگشت'),
        'invalid' => $say('Name and email are needed to reserve.', 'برای رزرو، نام و ایمیل لازم است.'),
    ];
    $seatNote = fn (int $seats, int $price) => $fa
        ? $n($seats).' صندلی مانده · '.$n($price).' تومان'
        : $n($seats).' seats left · '.$n($price).' Toman';

    // Scenario 1 — the roles offered to invitees, checked answer included.
    $roles = [
        ['value' => 'admin', 'label' => $say('Admin', 'مدیر'), 'description' => $say('Manages billing and members', 'صورت‌حساب و اعضا را مدیریت می‌کند'), 'checked' => false],
        ['value' => 'member', 'label' => $say('Member', 'عضو'), 'description' => $say('Edits projects and files', 'پروژه‌ها و فایل‌ها را ویرایش می‌کند'), 'checked' => true],
        ['value' => 'viewer', 'label' => $say('Viewer', 'بیننده'), 'description' => $say('Read-only access', 'فقط می‌بیند'), 'checked' => false],
    ];
    $alerts = [
        ['value' => 'mentions', 'label' => $say('Mentions', 'ذکر نام'), 'description' => $say('When someone mentions you', 'وقتی کسی شما را ذکر کند')],
        ['value' => 'digest', 'label' => $say('Daily digest', 'خلاصهٔ روزانه'), 'description' => $say('One email each morning', 'هر صبح یک ایمیل')],
        ['value' => 'product', 'label' => $say('Product news', 'اخبار محصول'), 'description' => $say('Roughly once a month', 'تقریباً ماهی یک‌بار')],
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Setting up a workspace', 'راه‌اندازی فضای کاری') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('“Next” refuses to move until the step in view passes the form’s own validation — try it with an empty name: the pane shakes, the footer error lights up and the first bad field takes focus. Completed steps jump back from the bar above, and the review pane reads every answer back from the controls themselves (data-nx-summary names each question). The final submit re-enables all panes first, so nothing is dropped — and the wire:model inputs ride along in the demo’s state.', '«ادامه» تا وقتی گامِ جلو از اعتبارسنجیِ خودِ فرم نگذرد، جلو نمی‌رود — یک‌بار با نام خالی امتحان کنید: پن می‌لرزد، خطای پایین روشن می‌شود و نخستین فیلدِ ناقص فوکوس می‌گیرد. گام‌های تمام‌شده از نوار بالا قابل بازگشت‌اند و پن بازبینی پاسخ‌ها را از خودِ کنترل‌ها می‌خواند (data-nx-summary نام هر پرسش را می‌دهد). ثبت نهایی همهٔ پن‌ها را فعال می‌کند تا هیچ پاسخی گم نشود — ورودی‌های wire:model هم در state همین دمو همراه می‌آیند.') }}
        </p>
    </div>
    <div wire:ignore>
        <x-nx::wizard
            :heading="$say('Create your workspace', 'ساخت فضای کاری')"
            :subtitle="$say('Three steps and you are in — the last one lines up every answer for a final look.', 'سه گام و آماده — گام آخر همهٔ پاسخ‌ها را برای نگاه آخر کنار هم می‌گذارد.')"
            :steps="[
                ['id' => 'identity', 'title' => $say('Identity', 'هویت'), 'description' => $say('Name and field', 'نام و زمینهٔ کار')],
                ['id' => 'team', 'title' => $say('Teammates', 'همکاران'), 'description' => $say('Invites and roles', 'دعوت و نقش‌ها')],
                ['id' => 'prefs', 'title' => $say('Preferences', 'ترجیحات'), 'description' => $say('Language and alerts', 'زبان و اعلان‌ها')],
            ]"
            wire:submit="save('{{ $say('Workspace created', 'فضای کاری ساخته شد') }}')"
        >
            <x-slot:stepIdentity>
                <div class="pg-grid">
                    <x-nx::input name="wz-name" :label="$say('Workspace name', 'نام فضای کاری')" required
                        :placeholder="$say('e.g. Nabu Studio', 'مثلاً استودیو نابو')"
                        :data-nx-summary="$say('Workspace name', 'نام فضای کاری')"
                        wire:model="state.wzName" />
                    <x-nx::select name="wz-field" :label="$say('Field of work', 'حوزهٔ فعالیت')" required
                        :placeholder="$say('Pick one…', 'یکی را انتخاب کنید…')"
                        :options="[
                            'design' => $say('Design', 'طراحی'),
                            'engineering' => $say('Engineering', 'مهندسی'),
                            'content' => $say('Content', 'محتوا'),
                            'growth' => $say('Growth', 'رشد'),
                        ]"
                        :data-nx-summary="$say('Field of work', 'حوزهٔ فعالیت')"
                        wire:model="state.wzField" />
                </div>
                <x-nx::textarea name="wz-about" :label="$say('About the team (optional)', 'دربارهٔ تیم (اختیاری)')"
                    :placeholder="$say('Two lines are plenty.', 'دو خط کافی است.')"
                    :data-nx-summary="$say('About the team', 'دربارهٔ تیم')"></x-nx::textarea>
            </x-slot:stepIdentity>

            <x-slot:stepTeam>
                <x-nx::textarea name="wz-invite" :label="$say('Teammates to invite (optional)', 'همکاران برای دعوت (اختیاری)')"
                    :hint="$say('One email per line; you can also invite later.', 'هر خط یک ایمیل؛ می‌توانید بعداً هم دعوت کنید.')"
                    :placeholder="$say('lena@studio.example', 'lena@studio.example')"
                    :data-nx-summary="$say('Teammates to invite', 'همکاران برای دعوت')"></x-nx::textarea>
                <fieldset class="nx-field" style="border: 0; margin: 0; padding: 0">
                    <legend class="nx-label">{{ $say('Default role for invitees', 'نقش پیش‌فرض دعوت‌شدگان') }}</legend>
                    <div style="display: flex; flex-direction: column; gap: var(--nx-space-3)">
                        @foreach ($roles as $role)
                            <label class="nx-choice">
                                <input type="radio" class="nx-radio" name="wz-role" value="{{ $role['value'] }}"
                                    @if ($loop->first) data-nx-summary="{{ $say('Default role', 'نقش پیش‌فرض') }}" @endif
                                    @checked($role['checked'])>
                                <span class="nx-choice-text">
                                    <span class="nx-choice-label">{{ $role['label'] }}</span>
                                    <span class="nx-choice-description">{{ $role['description'] }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>
            </x-slot:stepTeam>

            <x-slot:stepPrefs>
                <fieldset class="nx-field" style="border: 0; margin: 0; padding: 0">
                    <legend class="nx-label">{{ $say('Interface language', 'زبان رابط') }}</legend>
                    <div style="display: flex; flex-direction: column; gap: var(--nx-space-3)">
                        <label class="nx-choice">
                            <input type="radio" class="nx-radio" name="wz-lang" value="fa"
                                data-nx-summary="{{ $say('Interface language', 'زبان رابط') }}" checked>
                            <span class="nx-choice-text"><span class="nx-choice-label">فارسی</span></span>
                        </label>
                        <label class="nx-choice">
                            <input type="radio" class="nx-radio" name="wz-lang" value="en">
                            <span class="nx-choice-text"><span class="nx-choice-label">English</span></span>
                        </label>
                    </div>
                </fieldset>
                <fieldset class="nx-field" style="border: 0; margin: 0; padding: 0">
                    <legend class="nx-label">{{ $say('Email alerts', 'هشدارهای ایمیلی') }}</legend>
                    <div style="display: flex; flex-direction: column; gap: var(--nx-space-3)">
                        @foreach ($alerts as $alert)
                            <label class="nx-choice">
                                <input type="checkbox" class="nx-checkbox" name="wz-alerts" value="{{ $alert['value'] }}"
                                    @if ($loop->first) data-nx-summary="{{ $say('Email alerts', 'هشدارهای ایمیلی') }}" @endif
                                    @checked($alert['value'] === 'mentions')>
                                <span class="nx-choice-text">
                                    <span class="nx-choice-label">{{ $alert['label'] }}</span>
                                    <span class="nx-choice-description">{{ $alert['description'] }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>
                <x-nx::checkbox name="wz-open-invite" :label="$say('Members may invite others', 'اعضا خودشان همکار تازه دعوت کنند')" />
            </x-slot:stepPrefs>
        </x-nx::wizard>
    </div>
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('The wizard is a plain <form>: attributes land on the root, so wire:submit (or a real action) works — the submit button knows the action through wire:target and spins for the length of the request.', 'ویزارد یک <form> ساده است: اتریبیوت‌ها روی ریشه می‌نشینند، پس wire:submit (یا action واقعی) کار می‌کند — دکمهٔ ثبت هم action را از wire:target می‌فهمد و تا پایان درخواست می‌چرخد.') }}
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A workshop signup without the review pane', 'ثبت‌نام کارگاه، بدون پنل بازبینی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('With :review="false" the last step is the last step — the footer button becomes the submit, here renamed to “رزرو جای من” through the labels array (invalid message included). These steps carry no ids, so their slots are the numbered ones: step1, step2, …', 'با :review="false" همان گام آخر، گام آخر است — دکمهٔ پایین تبدیل به ثبت می‌شود که این‌جا با آرایهٔ labels به «رزرو جای من» تغییر نام داده (پیام خطا هم همراهش). این گام‌ها id ندارند، پس اسلات‌هایشان شماره‌ای است: step1، step2 و…') }}
        </p>
    </div>
    <div wire:ignore>
        <x-nx::wizard
            :heading="$say('Motion & interface workshop', 'کارگاه حرکت و رابط')"
            :subtitle="$say('Two steps; your seat is held for '.$n(24).' hours.', 'دو گام؛ جای شما '.$n(24).' ساعت نگه داشته می‌شود.')"
            :steps="[
                ['title' => $say('Attendee', 'شرکت‌کننده')],
                ['title' => $say('Sessions', 'جلسه‌ها')],
            ]"
            :review="false"
            :labels="$workshopLabels"
            wire:submit="ping('{{ $say('Your seat is reserved', 'جای شما رزرو شد') }}')"
        >
            <x-slot:step1>
                <x-nx::input name="wk-name" :label="$say('Full name', 'نام و نام خانوادگی')" required
                    :data-nx-summary="$say('Full name', 'نام و نام خانوادگی')" />
                <x-nx::input name="wk-email" type="email" dir="ltr" :label="$say('Email', 'ایمیل')" required
                    :hint="$say('The receipt and the reminder land here.', 'رسید تأیید و یادآوری اینجا می‌آید.')"
                    :data-nx-summary="$say('Email', 'ایمیل')" />
                <x-nx::select name="wk-level" :label="$say('Experience with motion (optional)', 'تجربهٔ کار با حرکت (اختیاری)')"
                    :placeholder="$say('Self-rate…', 'خودتان ارزیابی کنید…')"
                    :options="[
                        'new' => $say('Just starting', 'تازه شروع کرده‌ام'),
                        'some' => $say('A few projects', 'چند پروژه انجام داده‌ام'),
                        'lots' => $say('Ship it daily', 'هر روز درگیرش هستم'),
                    ]"
                    :data-nx-summary="$say('Experience with motion', 'تجربهٔ کار با حرکت')" />
            </x-slot:step1>
            <x-slot:step2>
                <fieldset class="nx-field" style="border: 0; margin: 0; padding: 0">
                    <legend class="nx-label">{{ $say('Which sessions?', 'کدام جلسه‌ها؟') }}</legend>
                    <div style="display: flex; flex-direction: column; gap: var(--nx-space-3)">
                        @foreach ([
                            ['value' => 'rtl', 'label' => $say('Designing for RTL', 'طراحی برای راست‌به‌چپ'), 'note' => $seatNote(14, 450000), 'checked' => true],
                            ['value' => 'springs', 'label' => $say('Springs, not durations', 'فنر، نه مدت‌زمان'), 'note' => $seatNote(6, 600000), 'checked' => false],
                            ['value' => 'a11y', 'label' => $say('Accessibility in practice', 'دسترس‌پذیری در عمل'), 'note' => $seatNote(21, 350000), 'checked' => false],
                        ] as $session)
                            <label class="nx-choice">
                                <input type="checkbox" class="nx-checkbox" name="wk-sessions" value="{{ $session['value'] }}"
                                    @if ($loop->first) data-nx-summary="{{ $say('Sessions', 'جلسه‌ها') }}" @endif
                                    @checked($session['checked'])>
                                <span class="nx-choice-text">
                                    <span class="nx-choice-label">{{ $session['label'] }}</span>
                                    <span class="nx-choice-description">{{ $session['note'] }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>
                <x-nx::checkbox name="wk-lunch" :label="$say('Add lunch ('.$n(120000).' Toman)', 'ناهار اضافه شود ('.$n(120000).' تومان)')"
                    :data-nx-summary="$say('Lunch', 'ناهار')" />
            </x-slot:step2>
        </x-nx::wizard>
    </div>
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('The hop is still validated: try reserving with an empty email and the pane refuses in this instance’s own words.', 'پرش همچنان اعتبارسنجی می‌شود: با ایمیل خالی رزرو کنید تا پن با واژه‌های خودِ همین نمونه رد کند.') }}
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Resuming a half-filled draft', 'ادامهٔ فرم نیمه‌کاره') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('step="1" drops the user on the second pane: the first step renders as completed (its jump button is live), the answers are already in the controls, and the review pane reads them back — exactly what a “continue where you left off” needs.', 'step="1" کاربر را روی پن دوم می‌اندازد: گام نخست «تمام‌شده» رندر می‌شود (دکمهٔ بازگشتش فعال است)، پاسخ‌ها از قبل در کنترل‌ها هستند و پن بازبینی همان‌ها را برمی‌گرداند — دقیقاً چیزی که «از همان‌جا ادامه بده» لازم دارد.') }}
        </p>
    </div>
    <div wire:ignore>
        <x-nx::wizard
            :heading="$say('Facilities ticket', 'گزارش خرابی تدارکات')"
            :subtitle="$say('You had already told us where; the draft picks up from there.', 'محل را قبلاً گفته بودید؛ پیش‌نویس از همان‌جا ادامه می‌یابد.')"
            :steps="[
                ['id' => 'where', 'title' => $say('Where & how urgent', 'کجا و چقدر فوری؟')],
                ['id' => 'what', 'title' => $say('What happened', 'چه اتفاقی افتاده؟')],
            ]"
            :step="1"
            wire:submit="ping('{{ $say('Ticket '.$n(1403).' filed — facilities is on it', 'تیکت '.$n(1403).' ثبت شد — تدارکات در راه است') }}')"
        >
            <x-slot:stepWhere>
                <x-nx::select name="fc-place" :label="$say('Place', 'محل')" required
                    :options="[
                        'hall' => $say('Meeting hall', 'سالن جلسات'),
                        'kitchen' => $say('Kitchen', 'آبدارخانه'),
                        'studio' => $say('Studio', 'اتاق کار گروهی'),
                        'warehouse' => $say('Warehouse', 'انبار'),
                    ]"
                    :data-nx-summary="$say('Place', 'محل')" />
                <fieldset class="nx-field" style="border: 0; margin: 0; padding: 0">
                    <legend class="nx-label">{{ $say('Urgency', 'فوریت') }}</legend>
                    <div style="display: flex; flex-direction: column; gap: var(--nx-space-3)">
                        @foreach ([
                            ['value' => 'today', 'label' => $say('Today', 'امروز'), 'checked' => true],
                            ['value' => 'hour', 'label' => $say('Within the hour', 'تا یک ساعت دیگر'), 'checked' => false],
                            ['value' => 'whenever', 'label' => $say('Whenever possible', 'هر وقت شد، مهم نیست'), 'checked' => false],
                        ] as $urgency)
                            <label class="nx-choice">
                                <input type="radio" class="nx-radio" name="fc-urgent" value="{{ $urgency['value'] }}"
                                    @if ($loop->first) data-nx-summary="{{ $say('Urgency', 'فوریت') }}" @endif
                                    @checked($urgency['checked'])>
                                <span class="nx-choice-text"><span class="nx-choice-label">{{ $urgency['label'] }}</span></span>
                            </label>
                        @endforeach
                    </div>
                </fieldset>
            </x-slot:stepWhere>
            <x-slot:stepWhat>
                <x-nx::textarea name="fc-note" :label="$say('What happened', 'چه اتفاقی افتاده؟')" required rows="3"
                    :data-nx-summary="$say('What happened', 'چه اتفاقی افتاده؟')">پروژکتور سالن جلسات روشن نمی‌شود؛ چراغ آماده چشمک می‌زند.</x-nx::textarea>
                <x-nx::checkbox name="fc-now" :label="$say('Someone is there right now', 'همین حالا کسی آنجاست')"
                    :data-nx-summary="$say('Someone is there', 'کسی آنجاست')" checked />
            </x-slot:stepWhat>
        </x-nx::wizard>
    </div>
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('An nx-step-change event bubbles on every hop (carrying the step index) and nx-submit fires right before the form goes out — both are yours to listen to on the root.', 'رویداد nx-step-change با هر پرش (همراه با اندیس گام) و nx-submit درست پیش از ارسال فرم پخش می‌شود — هر دو را می‌توانید روی ریشه گوش کنید.') }}
    </p>
</section>
