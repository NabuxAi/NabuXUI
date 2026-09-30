{{--
    The theme switch in its natural habitat: an “Appearance” settings row. The
    thumb springs between light/system/dark on the core theme store — the same
    preference this page’s header toggle writes — so changes from either place
    stay in sync, even across tabs.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The appearance row', 'ردیف ظاهر') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Native radios under one springy thumb — keyboard and screen-reader friendly. Flip it, then flip the header toggle: both read the same store (watch another tab follow along).', 'رادیوهای بومی زیر یک thumb فنری — مناسب کیبورد و صفحه‌خوان. سوییچ را بزنید، بعد سوییچ سربرگ را: هر دو یک مخزن را می‌خوانند (تب دیگر را ببینید که دنبال می‌کند).') }}
            </p>
        </div>
        <div class="pg-row" style="justify-content: space-between; padding: 1rem 1.25rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface-2)">
            <span class="pg-row" style="gap: .75rem">
                {{ \NabuXUI\NabuXUI::icon('sun') }}
                <strong>{{ $say('Interface theme', 'پوستهٔ رابط') }}</strong>
            </span>
            <x-nx::theme-switch label="{{ $say('Interface theme', 'پوستهٔ رابط') }}" x-on:nx-change="ping(@js($say('Theme saved', 'پوسته ذخیره شد')))" />
        </div>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('The preference lives in localStorage under nabu.theme and applies before first paint through the inline theme script.', 'ترجیح در localStorage زیر کلید nabu.theme می‌ماند و با اسکریپت درون‌خطی تم پیش از نخستین فریم اعمال می‌شود.') }}
        </p>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Preset and named for forms', 'مقدار اولیه و نام برای فرم‌ها') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('value picks the starting radio (the store leads afterwards) and name lets a plain form post the choice.', 'با value رادیوی آغازین انتخاب می‌شود (بعد از آن مخزن تم مبناست) و name اجازه می‌دهد یک فرم ساده انتخاب را پست کند.') }}
        </p>
        <div class="pg-row" style="gap: 2rem; justify-content: center; padding: 1rem; border-radius: var(--nx-radius-xl); background: var(--nx-surface-2)">
            <div style="display: grid; gap: .5rem; justify-items: center">
                <span style="font-size: var(--nx-text-sm); color: var(--nx-text-muted)">value="dark"</span>
                <x-nx::theme-switch value="dark" name="demo-dark" label="{{ $say('Starts on dark', 'از تیره شروع می‌شود') }}" />
            </div>
            <div style="display: grid; gap: .5rem; justify-items: center">
                <span style="font-size: var(--nx-text-sm); color: var(--nx-text-muted)">value="light"</span>
                <x-nx::theme-switch value="light" name="demo-light" label="{{ $say('Starts on light', 'از روشن شروع می‌شود') }}" />
            </div>
        </div>
    </section>
</div>
