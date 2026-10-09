{{--
    Hover cards where they earn their keep: an @mention inside a comment
    thread that previews the profile, and a pull-request reference that
    previews its status. Rest the pointer on a link (or Tab to it); the card
    stays while the pointer is inside it, so its buttons are reachable.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $n = fn (int $v) => \NabuXUI\NabuXUI::formatNumber($v);
@endphp
<style>
    .hc-thread { display: grid; gap: 1rem; max-inline-size: 40rem; }
    .hc-comment { display: grid; grid-template-columns: auto 1fr; gap: .75rem; padding: 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface); }
    .hc-comment p { margin: .25rem 0 0; line-height: 1.8; }
    .hc-link { color: var(--nx-accent-text); font-weight: 600; text-decoration: none; border-radius: var(--nx-radius-xs); }
    .hc-link:hover { text-decoration: underline; }
    .hc-link:focus-visible { outline: 2px solid var(--nx-ring); outline-offset: 2px; }
    .hc-profile { display: grid; gap: .75rem; }
    .hc-profile-head { display: flex; align-items: center; gap: .75rem; }
    .hc-profile strong { display: block; }
    .hc-profile small { color: var(--nx-text-muted); }
    .hc-stats { display: flex; gap: 1rem; color: var(--nx-text-muted); font-size: var(--nx-text-sm); }
    .hc-stats b { color: var(--nx-text); }
    .hc-pr { display: grid; gap: .5rem; font-size: var(--nx-text-sm); }
</style>

<section class="hc-thread" aria-labelledby="hc-thread-title">
    <h3 class="pg-title" id="hc-thread-title" style="margin: 0">{{ $say('Design review thread', 'گفت‌وگوی بازبینی طراحی') }}</h3>
    <article class="hc-comment">
        <x-nx::avatar :name="$say('Reza Karimi', 'رضا کریمی')" size="sm" />
        <div>
            <strong>{{ $say('Reza Karimi', 'رضا کریمی') }}</strong>
            <p>
                {{ $say('Looks good. Can', 'خوب است. می‌شود') }}
                <x-nx::hover-card :open-delay="350">
                    <x-slot:trigger><a class="hc-link" href="#sara">{{ '@'.$say('emma', 'سارا') }}</a></x-slot:trigger>
                    <span class="hc-profile">
                        <span class="hc-profile-head">
                            <x-nx::avatar :name="$say('Emma Carter', 'سارا احمدی')" status="online" />
                            <span><strong>{{ $say('Emma Carter', 'سارا احمدی') }}</strong><small>{{ $say('Design lead · Istanbul', 'سرپرست طراحی · استانبول') }}</small></span>
                        </span>
                        <span>{{ $say('Owns the motion system and the RTL review checklist.', 'مسئول سیستم حرکت و چک‌لیست بازبینی راست‌به‌چپ.') }}</span>
                        <span class="hc-stats"><span><b>{{ $n(128) }}</b> {{ $say('reviews', 'بازبینی') }}</span><span><b>{{ $n(36) }}</b> {{ $say('components', 'کامپوننت') }}</span></span>
                        <x-nx::button size="sm" variant="secondary" icon="message" wire:click="ping(@js($say('Message sent to Emma', 'پیام برای سارا رفت')))">{{ $say('Message', 'پیام') }}</x-nx::button>
                    </span>
                </x-nx::hover-card>
                {{ $say('check the spacing in', 'فاصله‌ها را در') }}
                <x-nx::hover-card side="top" :open-delay="350">
                    <x-slot:trigger><a class="hc-link" href="#pr-482">#482</a></x-slot:trigger>
                    <span class="hc-pr">
                        <span class="pg-row" style="gap: .5rem"><x-nx::badge tone="success" dot>{{ $say('Ready to merge', 'آمادهٔ ادغام') }}</x-nx::badge><small style="color: var(--nx-text-muted)">#482</small></span>
                        <strong>{{ $say('Bottom sheet: snap points and flick to dismiss', 'برگهٔ پایینی: نقاط توقف و بستن با ضربه') }}</strong>
                        <span style="color: var(--nx-text-muted)">{{ $say('+412 −38 · 3 approvals · checks passing', '+۴۱۲ −۳۸ · ۳ تأیید · بررسی‌ها سبز') }}</span>
                    </span>
                </x-nx::hover-card>
                {{ $say('before we ship?', 'پیش از انتشار ببیند؟') }}
            </p>
        </div>
    </article>
    <p style="margin: 0; color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('On a phone the links just navigate — hover cards are an extra, never the only way in.', 'روی گوشی لینک‌ها فقط باز می‌شوند — کارت پیش‌نمایش یک امکان اضافه است، نه تنها راه.') }}</p>
</section>
