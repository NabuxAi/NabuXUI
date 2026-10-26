{{--
    /ai — the NabuGate studio. The user describes what they are building; the
    request goes to the NabuGate gateway when one is configured, and the local
    intent engine (keyword scoring over UseCases + twelve hand-built recipes)
    answers otherwise. Either way the draft renders live in the stage below,
    next to the real demos that serve the same purpose.
--}}
@php
    use App\Support\UseCases;
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $examples = $fa
        ? ['یک فرم ثبت‌نام با کد تأیید شش‌رقمی', 'داشبورد فروش با آمار و نمودار', 'صفحهٔ فرود برای اپ آرایشی', 'چت پشتیبانی با پاسخ سریع']
        : ['A sign-up form with a six-digit code', 'A sales dashboard with stats', 'A landing page for a skincare app', 'A support chat with quick replies'];
@endphp
<div>
    {{--
        The <style> lives INSIDE the root div on purpose: Livewire stamps its
        wire:id on the template's first root-level element, and a style-first
        template makes the style tag the morph root — every later update then
        patches the style and the real DOM never changes.
    --}}
    <style>
    .aist-root { display: grid; gap: 1.5rem; }
    .aist-chips { display: flex; flex-wrap: wrap; gap: .5rem; }
    .aist-chip { display: inline-flex; align-items: center; gap: .45rem; padding: .45rem .9rem; border: 1px solid var(--nx-border);
                 border-radius: 999px; background: var(--nx-surface); color: var(--nx-text-muted); font: 600 var(--nx-text-sm) / 1 var(--nx-font-sans);
                 cursor: pointer; transition: color .15s var(--nx-ease-out), border-color .15s var(--nx-ease-out), background-color .15s var(--nx-ease-out); }
    .aist-chip:hover { color: var(--nx-text); border-color: var(--nx-border-strong); }
    .aist-chip[aria-pressed="true"] { background: var(--nx-accent-soft); border-color: var(--nx-accent-border); color: var(--nx-accent-text); }
    .aist-chip svg { inline-size: 1rem; block-size: 1rem; }
    .aist-compose { display: grid; gap: .9rem; }
    .aist-actions { display: flex; flex-wrap: wrap; gap: .6rem; align-items: center; justify-content: space-between; }
    .aist-examples { display: flex; flex-wrap: wrap; gap: .4rem; align-items: center; }
    .aist-example { border: none; background: none; padding: .2rem .55rem; border-radius: 999px; cursor: pointer;
                    font: 500 var(--nx-text-xs) / 1.6 var(--nx-font-sans); color: var(--nx-text-muted);
                    border: 1px dashed var(--nx-border); transition: color .15s var(--nx-ease-out), border-color .15s var(--nx-ease-out); }
    .aist-example:hover { color: var(--nx-accent-text); border-color: var(--nx-accent-border); }
    .aist-stage { position: relative; overflow: clip; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl);
                  background:
                    radial-gradient(60% 50% at 15% 0%, var(--nx-accent-soft), transparent 70%),
                    var(--nx-surface-2);
                  padding: clamp(1rem, 3vw, 2rem); }
    .aist-stage-frame { border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface);
                        padding: clamp(1.1rem, 2.5vw, 1.75rem); box-shadow: var(--nx-shadow-lg); }
    .aist-frame-html iframe { display: block; inline-size: 100%; block-size: clamp(18rem, 40vw, 26rem); border: 0; border-radius: var(--nx-radius-lg); background: var(--nx-surface); }
    .aist-demos { display: grid; gap: .75rem; }
    .aist-demo { display: flex; align-items: center; gap: .8rem; padding: .7rem .9rem; border: 1px solid var(--nx-border);
                 border-radius: var(--nx-radius-md); background: var(--nx-surface); text-decoration: none; color: inherit;
                 transition: border-color .15s var(--nx-ease-out), translate .15s var(--nx-ease-out); }
    .aist-demo:hover { border-color: var(--nx-accent-border); translate: 0 -1px; }
    .aist-demo svg { inline-size: 1.1rem; block-size: 1.1rem; color: var(--nx-accent-text); flex: none; }
    .aist-demo-name { font: 700 var(--nx-text-sm) / 1.3 var(--nx-font-sans); color: var(--nx-text); }
    .aist-demo-group { font: 500 var(--nx-text-xs) / 1.4 var(--nx-font-sans); color: var(--nx-text-muted); }
    .aist-recipe { display: grid; gap: 1rem; color: var(--nx-text); }
    .aist-recipe-row { display: flex; flex-wrap: wrap; gap: .75rem; align-items: center; }
    .aist-recipe-grid { display: grid; gap: .75rem; grid-template-columns: repeat(auto-fit, minmax(min(100%, 10rem), 1fr)); }
    .aist-recipe-stats { display: grid; gap: .75rem; grid-template-columns: repeat(auto-fit, minmax(min(100%, 9.5rem), 1fr)); }
    .aist-recipe-bubbles { display: grid; gap: .6rem; }
    .aist-bubble { max-inline-size: 78%; padding: .6rem .85rem; border-radius: var(--nx-radius-lg); font-size: var(--nx-text-sm); line-height: 1.6; }
    .aist-bubble-them { background: var(--nx-surface-2); border: 1px solid var(--nx-border); justify-self: start; border-start-start-radius: var(--nx-radius-sm); }
    .aist-bubble-me { background: var(--nx-accent); color: #fff; justify-self: end; border-start-end-radius: var(--nx-radius-sm); }
    .aist-week { display: grid; grid-template-columns: repeat(7, 1fr); gap: .4rem; }
    .aist-day { aspect-ratio: 1; display: grid; place-items: center; border-radius: var(--nx-radius-sm); border: 1px solid var(--nx-border);
                background: var(--nx-surface); font-size: var(--nx-text-xs); font-weight: 600; color: var(--nx-text-muted); }
    .aist-day[data-on] { background: var(--nx-accent-soft); border-color: var(--nx-accent-border); color: var(--nx-accent-text); }
    .aist-file { display: flex; align-items: center; gap: .7rem; padding: .55rem .75rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-md); background: var(--nx-surface); }
    .aist-file svg { inline-size: 1.15rem; block-size: 1.15rem; color: var(--nx-accent-text); flex: none; }
    .aist-meta { display: grid; gap: .2rem; min-inline-size: 0; flex: 1; }
    .aist-meta b { font: 600 var(--nx-text-sm) / 1.3 var(--nx-font-sans); }
    .aist-meta span { font-size: var(--nx-text-xs); color: var(--nx-text-muted); }
    @media (prefers-reduced-motion: reduce) {
        .aist-chip, .aist-demo, .aist-example { transition: none; }
    }
    </style>
    <main class="nx-page" wire:transition.navigate="nx-page">
        <div class="pg aist-root">
            <nav class="pg-row" style="justify-content: space-between" aria-label="{{ $say('Playground', 'پلی‌گراوند') }}">
                <div class="pg-row">
                    <x-nx::button size="sm" variant="ghost" icon="arrow-left" href="/components" wire:navigate>{{ $say('All demos', 'همهٔ دموها') }}</x-nx::button>
                </div>
                <x-nx::language-menu :value="$locale" wire:model.live="locale" :label="$say('Language', 'زبان')" :languages="\App\Support\Locales::forMenu()" />
            </nav>

            <header style="display: grid; gap: .6rem">
                <div class="pg-row" style="gap: .75rem">
                    <h1 style="margin: 0; font: 700 var(--nx-text-3xl) / 1.2 var(--nx-font-display); letter-spacing: var(--nx-tracking-tight)">
                        {{ $say('NabuGate studio', 'استودیوی نابوگیت') }}
                    </h1>
                    @if ($gatewayOn)
                        <x-nx::badge tone="success">{{ $say('Gateway connected', 'درگاه وصل است') }}</x-nx::badge>
                    @else
                        <x-nx::badge tone="neutral">{{ $say('Local drafts', 'پیش‌نویس محلی') }}</x-nx::badge>
                    @endif
                </div>
                <p style="margin: 0; max-inline-size: 52rem; color: var(--nx-text-muted)">
                    {{ $say(
                        'Say what you are building — NabuGate drafts it live from the NabuXUI components, and the real demos that serve the same purpose line up beside it. Pick a use case to steer the model, or let the prompt speak.',
                        'بگویید چه می‌سازید — نابوگیت همان لحظه از کامپوننت‌های نابواِکس‌یو‌آی پیش‌نویس می‌زند و دموهای واقعیِ هم‌کاربرد کنارش صف می‌کشند. برای هدایت مدل کاربرد را انتخاب کنید یا به متنِ پرامپت اجازهٔ حرف‌زدن بدهید.',
                    ) }}
                </p>
            </header>

            <section class="pg-box aist-compose" aria-labelledby="aist-compose-title">
                <h2 class="pg-title" id="aist-compose-title" style="margin: 0">{{ $say('What are you building?', 'چه می‌سازید؟') }}</h2>

                <div>
                    <p style="margin: 0 0 .55rem; font: 600 var(--nx-text-xs) / 1.4 var(--nx-font-sans); color: var(--nx-text-muted)">
                        {{ $say('Steer with a use case (optional)', 'با کاربرد هدایت کنید (اختیاری)') }}
                    </p>
                    <div class="aist-chips" role="group" aria-label="{{ $say('Use case', 'کاربرد') }}">
                        <button type="button" class="aist-chip" aria-pressed="{{ $usecase === '' ? 'true' : 'false' }}" wire:click="$set('usecase', '')">
                            {{ $say('Let the prompt decide', 'پرامپت خودش تشخیص دهد') }}
                        </button>
                        @foreach ($usecases as $usecase)
                            <button type="button" class="aist-chip" aria-pressed="{{ $usecase === $usecase['id'] ? 'true' : 'false' }}" wire:click="$set('usecase', $usecase['id'])">
                                {{ \NabuXUI\NabuXUI::icon($usecase['icon']) }}{{ \App\Support\DemoCatalog::pick($usecase['label']) }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <x-nx::textarea :label="$say('Describe it', 'توصیفش کنید')"
                    :placeholder="$say('e.g. a sign-up card with an email field, a six-digit code and a submit button…', 'مثلاً: کارت ثبت‌نام با فیلد ایمیل، کد شش‌رقمی و دکمهٔ ثبت…')"
                    wire:model.live.debounce.300ms="prompt" :rows="3" />

                <div class="aist-actions">
                    <div class="aist-examples" aria-label="{{ $say('Example prompts', 'نمونه‌پرامپت‌ها') }}">
                        @foreach ($examples as $example)
                            <button type="button" class="aist-example" wire:click="$set('prompt', @js($example))">{{ $example }}</button>
                        @endforeach
                    </div>
                    <div class="pg-row" style="gap: .6rem">
                        <span wire:loading wire:target="generate" class="pg-row" style="gap: .45rem; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
                            {{ \NabuXUI\NabuXUI::icon('sparkles') }}{{ $say('NabuGate is drafting…', 'نابوگیت در حال پیش‌نویس…') }}
                        </span>
                        <x-nx::button variant="primary" icon="sparkles" wire:click="generate" wire:loading.attr="disabled" wire:target="generate">
                            {{ $say('Draft it with NabuGate', 'پیش‌نویس با نابوگیت') }}
                        </x-nx::button>
                    </div>
                </div>
                @unless ($errors->any())
                    <p wire:loading.remove wire:target="generate" style="margin: 0; font-size: var(--nx-text-xs); color: var(--nx-text-muted)">
                        {{ $say('The draft is a starting point rendered from real components — open the lined-up demos for props and copyable snippets.', 'پیش‌نویس نقطهٔ شروعی است از کامپوننت‌های واقعی — برای پراپ‌ها و تکه‌کد، دموهای هم‌ردیف را باز کنید.') }}
                    </p>
                @endunless
                @if ($errors->has('prompt'))
                    <x-nx::alert tone="danger" :title="$say('A few more words, please', 'چند کلمه بیشتر')">
                        {{ $errors->first('prompt') }}
                    </x-nx::alert>
                @endif
            </section>

            @if ($result)
                <section class="pg-box" style="gap: 1.1rem" aria-labelledby="aist-result-title" wire:key="aist-result-{{ $asked }}">
                    <div class="pg-row" style="justify-content: space-between; align-items: center">
                        <div>
                            <h2 class="pg-title" id="aist-result-title" style="margin: 0">{{ $result['name'] }}</h2>
                            <p style="margin: .2rem 0 0; color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $result['description'] }}</p>
                        </div>
                        <x-nx::badge tone="{{ $result['mode'] === 'nabugate' ? 'success' : 'info' }}">
                            {{ $result['mode'] === 'nabugate' ? 'NabuGate' : $say('Local draft', 'پیش‌نویس محلی') }}
                        </x-nx::badge>
                    </div>

                    <div class="aist-stage">
                        <div class="aist-stage-frame">
                            @if ($result['mode'] === 'nabugate')
                                <div class="aist-frame-html">
                                    <iframe title="{{ $result['name'] }}" sandbox="" srcdoc="{{ $result['html'] }}" loading="lazy"></iframe>
                                </div>
                            @else
                                <div class="aist-recipe">
                                    @include($result['recipe'])
                                </div>
                            @endif
                        </div>
                    </div>

                    <p style="margin: 0; font-size: var(--nx-text-xs); color: var(--nx-text-muted)">{{ $result['provider'] }}</p>

                    @if (count($result['demos']) > 0)
                        <div class="aist-demos">
                            <p style="margin: 0; font: 700 var(--nx-text-sm) / 1.4 var(--nx-font-sans)">
                                {{ $say('Real demos for the same job', 'دموهای واقعی همین کار') }}
                            </p>
                            @foreach ($result['demos'] as $demo)
                                <a class="aist-demo" href="/components/{{ $demo['group'] }}/{{ $demo['slug'] }}" wire:navigate>
                                    {{ \NabuXUI\NabuXUI::icon('arrow-left') }}
                                    <span class="aist-meta">
                                        <b class="aist-demo-name">{{ $demo['title'] }}</b>
                                        <span class="aist-demo-group">/components/{{ $demo['group'] }}/{{ $demo['slug'] }}</span>
                                    </span>
                                    <x-nx::badge tone="neutral" size="sm">{{ $say('Open', 'بازکردن') }}</x-nx::badge>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </section>
            @endif
        </div>
    </main>
</div>
