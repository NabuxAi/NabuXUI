{{--
    The multi-select's real scenarios: an agent's reply languages as a plain
    form field, then a channel allow-list capped at three with a live
    Livewire round trip.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $languages = [
        ['value' => 'fa', 'label' => 'فارسی', 'icon' => 'globe', 'description' => $say('Persian · default', 'فارسی · پیش‌فرض')],
        ['value' => 'en', 'label' => 'English', 'icon' => 'globe', 'description' => $say('English', 'انگلیسی')],
        ['value' => 'ar', 'label' => 'العربية', 'icon' => 'globe', 'description' => $say('Arabic · RTL', 'عربی · راست‌به‌چپ')],
        ['value' => 'ja', 'label' => '日本語', 'icon' => 'globe', 'description' => $say('Japanese', 'ژاپنی')],
        ['value' => 'zh', 'label' => '中文', 'icon' => 'globe', 'description' => $say('Chinese', 'چینی')],
        ['value' => 'hi', 'label' => 'हिन्दी', 'icon' => 'globe', 'description' => $say('Hindi', 'هندی')],
        ['value' => 'es', 'label' => 'Español', 'icon' => 'globe'],
        ['value' => 'fr', 'label' => 'Français', 'icon' => 'globe'],
        ['value' => 'de', 'label' => 'Deutsch', 'icon' => 'globe'],
        ['value' => 'tr', 'label' => 'Türkçe', 'icon' => 'globe'],
        ['value' => 'ko', 'label' => '한국어', 'icon' => 'globe'],
        ['value' => 'pt', 'label' => 'Português', 'icon' => 'globe'],
    ];

    $channels = [
        ['value' => 'whatsapp', 'label' => $say('WhatsApp', 'واتساپ'), 'icon' => 'message', 'description' => $say('Voice notes supported', 'یادداشت صوتی پشتیبانی می‌شود')],
        ['value' => 'telegram', 'label' => $say('Telegram', 'تلگرام'), 'icon' => 'message', 'description' => $say('Inline keyboards', 'کیبوردهای درون‌خطی')],
        ['value' => 'web', 'label' => $say('Web chat', 'گفت‌وگوی وب'), 'icon' => 'globe', 'description' => $say('The site widget', 'ویجت سایت')],
        ['value' => 'email', 'label' => $say('Email', 'ایمیل'), 'icon' => 'mail', 'description' => $say('Threads stay whole', 'رشته‌ها کامل می‌مانند')],
        ['value' => 'line', 'label' => 'LINE', 'icon' => 'message', 'description' => $say('Japan only', 'فقط ژاپن')],
        ['value' => 'sms', 'label' => 'SMS', 'icon' => 'message', 'disabled' => true, 'description' => $say('Coming next quarter', 'فصل بعد می‌آید')],
    ];
@endphp

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Which languages may the agent answer in?', 'ایجنت به چه زبان‌هایی حق پاسخ دارد؟') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('A plain form field: every pick posts as languages[]. Backspace removes the last chip; typing filters by label — in any script.', 'یک فیلد فرم ساده: هر انتخاب به‌صورت languages[] پست می‌شود. Backspace آخرین چیپ را برمی‌دارد؛ نوشتن فهرست را فیلتر می‌کند — با هر خطی.') }}
            </p>
        </div>
        <x-nx::multi-select name="languages"
            :label="$say('Reply languages', 'زبان‌های پاسخگویی')"
            :placeholder="$say('Add a language…', 'یک زبان اضافه کنید…')"
            :search-placeholder="$say('Search 12 languages…', 'جست‌وجو بین ۱۲ زبان…')"
            :empty-text="$say('No language matches', 'زبانی پیدا نشد')"
            :options="$languages"
            :value="['fa', 'en', 'ja', 'ar']" />
        <x-nx::button size="sm" variant="secondary" icon="check" wire:click="save(@js($say('Language policy saved', 'سیاست زبان ذخیره شد')))">{{ $say('Save policy', 'ذخیرهٔ سیاست') }}</x-nx::button>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('At most three channels per agent', 'حداکثر سه کانال برای هر ایجنت') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('The cap disables the rest; SMS is disabled by data. Every change goes live to the server.', 'سقف، بقیه را غیرفعال می‌کند؛ پیامک از پایه غیرفعال است. هر تغییر زنده به سرور می‌رود.') }}
            </p>
        </div>
        <x-nx::multi-select
            :label="$say('Channels', 'کانال‌ها')"
            :placeholder="$say('Pick up to three…', 'تا سه‌تا انتخاب کنید…')"
            :options="$channels"
            :max="3"
            wire:model.live="state.channels" />
        <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
            {{ $say('On the server (wire:model.live):', 'روی سرور (wire:model.live):') }}
            <code>{{ implode(' · ', (array) ($state['channels'] ?? [])) ?: '—' }}</code>
            ({{ NabuXUI::formatNumber(count((array) ($state['channels'] ?? []))) }}/{{ NabuXUI::formatNumber(3) }})
        </p>
    </section>
</div>
