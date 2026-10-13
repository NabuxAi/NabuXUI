{{--
    Inline edit's real scenarios: a project header whose title renames in
    place (entangled to Livewire state), a settings list with a required
    field and a server-style failure, and a Persian row.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
    $this->state['project'] ??= $say('Q3 growth roadmap', 'نقشهٔ راه رشد فصل سوم');
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Rename a project where it stands', 'تغییر نام پروژه همان‌جا که هست') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Click the title (or Tab to it and press Enter): it becomes an input of exactly its own width, and the width springs as it changes. Enter saves into the Livewire state below, Escape puts the old title back.', 'روی عنوان کلیک کنید (یا با Tab بروید و Enter بزنید): به ورودی‌ای با همان عرض خودش تبدیل می‌شود و عرض با تغییرش فنری می‌شود. Enter در وضعیت Livewire پایین ذخیره می‌کند و Escape عنوان قبلی را برمی‌گرداند.') }}
        </p>
    </div>
    <div style="display: grid; gap: .5rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); padding: 1.25rem">
        <span style="color: var(--nx-text-subtle); font-size: var(--nx-text-sm)">{{ $say('Workspace / Projects', 'فضای کار / پروژه‌ها') }}</span>
        <x-nx::inline-edit size="lg" :label="$say('Project name', 'نام پروژه')" wire:model.live="state.project" required
            :edit-label="$say('Edit {label}', 'ویرایش {label}')" :required-label="$say('A project needs a name', 'پروژه نام لازم دارد')" />
        <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('Saved on the server as:', 'در سرور ذخیره شده به‌صورت:') }} <code>{{ $this->state['project'] }}</code></span>
    </div>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Settings rows', 'سطرهای تنظیمات') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('The store handle refuses anything with a space (the save shows its error and keeps the field open); the support email is plain.', 'شناسهٔ فروشگاه هر چیزی با فاصله را رد می‌کند (ذخیره خطا نشان می‌دهد و فیلد را باز نگه می‌دارد)؛ ایمیل پشتیبانی ساده است.') }}
        </p>
        <dl style="display: grid; grid-template-columns: auto 1fr; gap: .75rem 1.25rem; margin: 0; align-items: center">
            <dt style="color: var(--nx-text-muted)">{{ $say('Store handle', 'شناسهٔ فروشگاه') }}</dt>
            <dd style="margin: 0">
                <x-nx::inline-edit :label="$say('Store handle', 'شناسهٔ فروشگاه')" value="nabu-shop" dir="ltr"
                    :saving-label="$say('Saving…', 'در حال ذخیره…')" :edit-label="$say('Edit {label}', 'ویرایش {label}')"
                    x-on:nx-change="$wire.ping({{ \Illuminate\Support\Js::from($say('Handle saved', 'شناسه ذخیره شد')) }})"
                    x-on:nx-validate="if (/\s/.test($event.detail.value)) $event.detail.error = {{ \Illuminate\Support\Js::from($say('No spaces in a handle', 'شناسه نباید فاصله داشته باشد')) }}" />
            </dd>
            <dt style="color: var(--nx-text-muted)">{{ $say('Support email', 'ایمیل پشتیبانی') }}</dt>
            <dd style="margin: 0">
                <x-nx::inline-edit :label="$say('Support email', 'ایمیل پشتیبانی')" value="" placeholder="help@nabu.example" dir="ltr"
                    :edit-label="$say('Edit {label}', 'ویرایش {label}')" />
            </dd>
        </dl>
    </section>

    <section class="pg-box" dir="rtl" lang="fa" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Right to left', 'راست‌به‌چپ') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('A Persian list title: the input grows toward the left as you type.', 'عنوان یک فهرست فارسی: ورودی با تایپ به سمت چپ بزرگ می‌شود.') }}
        </p>
        <x-nx::inline-edit label="عنوان فهرست" value="خریدهای نوروز" edit-label="ویرایش {label}" saving-label="در حال ذخیره…" />
    </section>
</div>
