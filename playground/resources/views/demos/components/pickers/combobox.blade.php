{{--
    Comboboxes doing real work: a flight search's destination (bound to the
    server, the chosen airport echoed back), and an assignee picker inside an
    issue form with descriptions and a disabled teammate.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $airports = [
        ['value' => 'IKA', 'label' => $say('Tehran — Imam Khomeini', 'تهران — امام خمینی'), 'description' => 'IKA · '.$say('Iran', 'ایران'), 'keywords' => ['Tehran', 'تهران', 'IKA']],
        ['value' => 'SYZ', 'label' => $say('Shiraz — Shahid Dastgheib', 'شیراز — شهید دستغیب'), 'description' => 'SYZ · '.$say('Iran', 'ایران'), 'keywords' => ['Shiraz', 'شیراز']],
        ['value' => 'IFN', 'label' => $say('Isfahan — Shahid Beheshti', 'اصفهان — شهید بهشتی'), 'description' => 'IFN · '.$say('Iran', 'ایران'), 'keywords' => ['Isfahan', 'اصفهان']],
        ['value' => 'IST', 'label' => $say('Istanbul Airport', 'فرودگاه استانبول'), 'description' => 'IST · '.$say('Türkiye', 'ترکیه'), 'keywords' => ['Istanbul', 'استانبول']],
        ['value' => 'DXB', 'label' => $say('Dubai International', 'فرودگاه بین‌المللی دبی'), 'description' => 'DXB · '.$say('UAE', 'امارات'), 'keywords' => ['Dubai', 'دبی']],
        ['value' => 'FRA', 'label' => $say('Frankfurt am Main', 'فرانکفورت'), 'description' => 'FRA · '.$say('Germany', 'آلمان'), 'keywords' => ['Frankfurt', 'فرانکفورت']],
        ['value' => 'CDG', 'label' => $say('Paris — Charles de Gaulle', 'پاریس — شارل دوگل'), 'description' => 'CDG · '.$say('France', 'فرانسه'), 'keywords' => ['Paris', 'پاریس']],
        ['value' => 'NRT', 'label' => $say('Tokyo — Narita', 'توکیو — ناریتا'), 'description' => 'NRT · '.$say('Japan', 'ژاپن'), 'keywords' => ['Tokyo', 'توکیو']],
    ];
    $to = $state['to'] ?? null;
    $picked = collect($airports)->firstWhere('value', $to);

    $people = [
        ['value' => 'sara', 'label' => $say('Sara Ahmadi', 'سارا احمدی'), 'description' => $say('Design lead', 'سرپرست طراحی'), 'icon' => 'user'],
        ['value' => 'reza', 'label' => $say('Reza Karimi', 'رضا کریمی'), 'description' => $say('Backend', 'بک‌اند'), 'icon' => 'user'],
        ['value' => 'nika', 'label' => $say('Nika Rahimi', 'نیکا رحیمی'), 'description' => $say('Frontend', 'فرانت‌اند'), 'icon' => 'user'],
        ['value' => 'omid', 'label' => $say('Omid Jafari', 'امید جعفری'), 'description' => $say('On leave until Mehr 20', 'در مرخصی تا ۲۰ مهر'), 'icon' => 'user', 'disabled' => true],
        ['value' => 'leila', 'label' => $say('Leila Moradi', 'لیلا مرادی'), 'description' => $say('QA', 'تضمین کیفیت'), 'icon' => 'user'],
    ];
@endphp
<style>
    .cb-flight { display: grid; gap: 1rem; padding: 1.25rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface); }
    .cb-flight-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(14rem, 1fr)); gap: 1rem; align-items: end; }
    .cb-flight-from { display: grid; gap: .375rem; }
    .cb-flight-from span { color: var(--nx-text-muted); font-size: var(--nx-text-sm); }
    .cb-flight-from strong { min-block-size: 2.75rem; display: flex; align-items: center; padding-inline: .75rem; border: 1px dashed var(--nx-border-strong); border-radius: var(--nx-radius-md); font-weight: 500; }
    .cb-issue { display: grid; gap: 1rem; max-inline-size: 30rem; }
</style>

<section class="cb-flight" aria-labelledby="cb-flight-title">
    <div class="pg-row" style="justify-content: space-between">
        <h3 class="pg-title" id="cb-flight-title" style="margin: 0">{{ $say('Book a flight', 'رزرو پرواز') }}</h3>
        <x-nx::badge tone="{{ $picked ? 'success' : 'neutral' }}">{{ $picked ? $picked['value'] : $say('No destination yet', 'مقصد انتخاب نشده') }}</x-nx::badge>
    </div>
    <div class="cb-flight-row">
        <div class="cb-flight-from">
            <span>{{ $say('From', 'مبدأ') }}</span>
            <strong>{{ $say('Tehran — IKA', 'تهران — IKA') }}</strong>
        </div>
        <div class="cb-flight-from">
            <label for="cb-to-input" style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('To', 'مقصد') }}</label>
            <x-nx::combobox id="cb-to" :options="$airports" :value="$to" :placeholder="$say('City, airport or code', 'شهر، فرودگاه یا کد')"
                name="to" wire:model.live="state.to" />
        </div>
    </div>
    <p style="margin: 0; color: var(--nx-text-muted); font-size: var(--nx-text-sm)">
        {{ $picked ? $say('Searching fares to ', 'جست‌وجوی بلیت به ').$picked['label'].'…' : $say('Try “par”, “IST” or “دبی” — keywords and codes match too.', '«پار»، «IST» یا «Dubai» را امتحان کنید — کلیدواژه و کد هم پیدا می‌شوند.') }}
    </p>
</section>

<section class="pg-box cb-issue" aria-labelledby="cb-issue-title">
    <h3 class="pg-title" id="cb-issue-title" style="margin: 0">{{ $say('New issue', 'مسئلهٔ تازه') }}</h3>
    <x-nx::field :label="$say('Title', 'عنوان')" for="cb-issue-name">
        <x-nx::input id="cb-issue-name" :value="$say('Checkout button jumps on hover', 'دکمهٔ پرداخت با هاور می‌پرد')" />
    </x-nx::field>
    <div style="display: grid; gap: .375rem">
        <label for="cb-assignee-input" style="font-size: var(--nx-text-sm); font-weight: 500">{{ $say('Assignee', 'مسئول') }}</label>
        <x-nx::combobox id="cb-assignee" :options="$people" value="nika" :placeholder="$say('Search teammates', 'جست‌وجوی هم‌تیمی‌ها')"
            :empty-text="$say('Nobody by that name on this team', 'کسی با این نام در تیم نیست')" />
    </div>
    <x-nx::button variant="primary" wire:click="save(@js($say('Issue created', 'مسئله ساخته شد')))">{{ $say('Create issue', 'ساخت مسئله') }}</x-nx::button>
</section>
