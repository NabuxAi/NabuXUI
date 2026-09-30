{{--
    The input as it lives in real forms: a profile card that saves with live
    values, then a member search with a debounced live binding and locale
    digits in the result count.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $name = (string) ($state['name'] ?? '');
    $subdomain = (string) ($state['subdomain'] ?? '');
    $free = $subdomain !== '' && $subdomain !== 'nabu';

    $q = trim((string) ($state['memberQuery'] ?? ''));
    $members = collect([
        ['name' => 'مریم صادقی', 'role' => $say('Product designer', 'طراح محصول')],
        ['name' => 'سامان دهقان', 'role' => $say('Backend engineer', 'مهندس بک‌اند')],
        ['name' => 'Nguyen Linh', 'role' => $say('Support lead', 'سرپرست پشتیبانی')],
        ['name' => 'هانیه کاظمی', 'role' => $say('Data analyst', 'تحلیلگر داده')],
        ['name' => 'Marcus Reid', 'role' => $say('Growth marketer', 'بازاریاب رشد')],
    ])->filter(fn ($member) => $q === '' || str_contains(mb_strtolower($member['name'].' '.$member['role']), mb_strtolower($q)))->values();
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Store profile, with help where it counts', 'پروفایل فروشگاه، با راهنما دقیقاً جایی که لازم است') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Label, hint and icon on the display name; a glued prefix/suffix on the address; the billing email carries a hard error — nothing clears until it is fixed.', 'برچسب، راهنما و آیکون روی نام نمایشی؛ پیشوند/پسوند چسبیده روی آدرس؛ ایمیل صورت‌حساب خطای صریح دارد — تا درست نشود پاک نمی‌شود.') }}
        </p>
    </div>
    <div class="pg-grid">
        <x-nx::input label="{{ $say('Display name', 'نام نمایشی') }}" :hint="$say('Visible to every teammate.', 'برای همهٔ هم‌تیمی‌ها دیده می‌شود.')" icon="user" wire:model.blur="state.name" required />
        <x-nx::input label="{{ $say('Store address', 'آدرس فروشگاه') }}" prefix="https://" suffix=".nabu.shop" wire:model.live.debounce.400ms="state.subdomain" />
        <x-nx::input label="{{ $say('Billing email', 'ایمیل صورت‌حساب') }}" type="email" icon="mail" :error="$free ? null : $say('Enter a valid email, or billing stops.', 'یک ایمیل معتبر وارد کنید وگرنه صورت‌حساب می‌ایستد.')" wire:model.blur="state.billingEmail" />
    </div>
    <div class="pg-row" style="justify-content: space-between">
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Address preview:', 'پیش‌نمای آدرس:') }}
            <code dir="ltr" style="font: 500 var(--nx-text-sm) var(--nx-font-mono); color: var(--nx-text)">https://{{ $subdomain !== '' ? $subdomain : 'your-store' }}.nabu.shop</code>
        </p>
        <x-nx::button variant="primary" icon="check" wire:click="save('{{ $say('Profile saved', 'پروفایل ذخیره شد') }}')">{{ $say('Save profile', 'ذخیرهٔ پروفایل') }}</x-nx::button>
    </div>
</section>

<section class="pg-box" style="gap: 1rem">
    <div class="pg-row" style="justify-content: space-between">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A live member search', 'جست‌وجوی زندهٔ اعضا') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('Debounced by 300ms — one request per pause, not per keystroke. Try Persian or Latin names.', 'با تأخیر ۳۰۰ میلی‌ثانیه — یک درخواست برای هر مکث، نه هر کلید. نام فارسی یا لاتین امتحان کنید.') }}
            </p>
        </div>
        <span class="nx-badge" data-tone="accent">{{ NabuXUI::formatNumber($members->count()).' '.$say('of 5 members', 'از ۵ عضو') }}</span>
    </div>
    <x-nx::input :label="$say('Find a teammate', 'پیدا کردن هم‌تیمی')" icon="search" :placeholder="$say('Type a name or role…', 'نام یا نقش…')" wire:model.live.debounce.300ms="state.memberQuery" />
    <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .5rem">
        @forelse ($members as $member)
            <li class="pg-row" style="justify-content: space-between; padding: .6rem .9rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
                <span class="pg-row" style="gap: .6rem">
                    <x-nx::avatar :name="$member['name']" size="sm" />
                    <strong style="font-weight: 600">{{ $member['name'] }}</strong>
                </span>
                <span style="color: var(--nx-text-muted)">{{ $member['role'] }}</span>
            </li>
        @empty
            <li><x-nx::empty-state icon="search" size="sm" :title="$say('No one by that name', 'کسی با این نام نیست')" :description="$say('Check the spelling, or invite them first.', 'املای نام را بررسی کنید یا اول دعوتش کنید.')" /></li>
        @endforelse
    </ul>
</section>
