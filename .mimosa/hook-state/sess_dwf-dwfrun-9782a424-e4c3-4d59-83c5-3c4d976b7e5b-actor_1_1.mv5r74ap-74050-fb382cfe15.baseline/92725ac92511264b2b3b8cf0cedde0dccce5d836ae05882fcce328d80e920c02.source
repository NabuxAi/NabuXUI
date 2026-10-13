{{--
    Interests picker's real scenarios: the personalisation step of an onboarding
    flow (bound to Livewire, the count carries into the toast), and a plain-form
    variant with a disabled option that posts natively.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $interests = [
        ['value' => 'design', 'label' => $say('Design', 'طراحی'), 'emoji' => '🎨'],
        ['value' => 'food', 'label' => $say('Food', 'غذا'), 'emoji' => '🍜'],
        ['value' => 'music', 'label' => $say('Music', 'موسیقی'), 'emoji' => '🎵'],
        ['value' => 'books', 'label' => $say('Books', 'کتاب'), 'emoji' => '📚'],
        ['value' => 'travel', 'label' => $say('Travel', 'سفر'), 'emoji' => '✈️'],
        ['value' => 'running', 'label' => $say('Running', 'دویدن'), 'emoji' => '🏃'],
        ['value' => 'games', 'label' => $say('Games', 'بازی'), 'emoji' => '🎮'],
        ['value' => 'photo', 'label' => $say('Photography', 'عکاسی'), 'emoji' => '📷'],
        ['value' => 'plants', 'label' => $say('Plants', 'گیاهان'), 'emoji' => '🌱'],
        ['value' => 'code', 'label' => $say('Code', 'کدنویسی'), 'emoji' => '💻'],
        ['value' => 'languages', 'label' => $say('Languages', 'زبان‌ها'), 'emoji' => '🌍'],
        ['value' => 'podcasts', 'label' => $say('Podcasts', 'پادکست'), 'emoji' => '🎧'],
    ];
    $picked = is_array($state['interests'] ?? null) ? $state['interests'] : ['design', 'food'];
@endphp

<section class="pg-box" style="gap: 1.25rem" x-data x-init="Array.isArray($wire.get('state.interests')) || $wire.set('state.interests', @js($picked), false)">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Personalising a new account', 'شخصی‌سازی حساب تازه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The onboarding step that asks what you love: chips drag sideways on touch, picking throws the emoji, Clear shakes the picks off. The count follows the server.', 'مرحلهٔ راه‌اندازی که می‌پرسد دوست داری چی: چیپ‌ها با انگشت می‌لغزند، تیک‌زدن ایموجی را به هوا می‌فرستد و «پاک‌کردن» آنها را می‌لرزاند. شمارش از سرور می‌آید.') }}
        </p>
    </div>
    <x-nx::interests-picker :label="$say('What are you into?', 'به چی علاقه داری؟')" wire:model.live="state.interests" :value="$picked" :options="$interests"
        :clear-label="$say('Clear', 'پاک‌کردن')" />
    <div class="pg-row" style="justify-content: space-between">
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say(NabuXUI::formatNumber(count($picked)).' picked on the server', NabuXUI::formatNumber(count($picked)).' انتخاب در سرور') }}
        </p>
        <x-nx::button variant="primary" icon-end="arrow-right" wire:click="save(@js($say('Following '.NabuXUI::formatNumber(count($picked)).' topics', NabuXUI::formatNumber(count($picked)).' موضوع دنبال می‌کنی')))">
            {{ $say('Continue', 'ادامه') }}
        </x-nx::button>
    </div>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A plain form, one row, a locked chip', 'فرم ساده، یک ردیف، یک چیپ قفل') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('No Livewire here: name makes the checkboxes post natively, rows=2 packs the chips tighter and one option ships disabled.', 'اینجا Livewire نیست: با name چک‌باکس‌ها بومی پست می‌شوند، rows=2 چیپ‌ها را جمع‌وجورتر می‌چیند و یک گزینه از قبل غیرفعال است.') }}
        </p>
        <form wire:submit.prevent="ping(@js($say('Preferences saved', 'ترجیحات ذخیره شد')))" style="display: grid; gap: 1rem">
            <x-nx::interests-picker name="topics[]" :rows="2" :options="[
                ['value' => 'news', 'label' => $say('News', 'اخبار'), 'emoji' => '📰'],
                ['value' => 'weather', 'label' => $say('Weather', 'آب‌وهوا'), 'emoji' => '🌤️'],
                ['value' => 'sport', 'label' => $say('Sport', 'ورزش'), 'emoji' => '⚽'],
                ['value' => 'markets', 'label' => $say('Markets', 'بازار'), 'emoji' => '📈', 'disabled' => true],
                ['value' => 'culture', 'label' => $say('Culture', 'فرهنگ'), 'emoji' => '🎭'],
                ['value' => 'tech', 'label' => $say('Tech', 'فناوری'), 'emoji' => '🛠️'],
            ]" :clear-label="$say('Clear', 'پاک‌کردن')" />
            <x-nx::button variant="secondary" size="sm" type="submit">{{ $say('Save topics', 'ذخیرهٔ موضوع‌ها') }}</x-nx::button>
        </form>
    </section>
</div>
