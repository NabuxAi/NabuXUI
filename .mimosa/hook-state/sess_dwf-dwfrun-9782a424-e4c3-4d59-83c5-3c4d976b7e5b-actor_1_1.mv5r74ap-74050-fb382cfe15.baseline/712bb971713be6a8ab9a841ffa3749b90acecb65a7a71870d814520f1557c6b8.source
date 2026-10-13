{{--
    Hold to confirm's real scenarios: a project's danger zone (the bar, wired
    to the server through nx-confirm), revoking API keys with the ring
    variant, and the same thing right to left.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A project’s danger zone', 'منطقهٔ خطرِ یک پروژه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Deleting a project cannot be undone, so the button asks for a held press instead of a second dialog. Let go early and the fill drains back; hold for 1.2 seconds and the trash becomes a check while the server deletes (the toast is the round-trip). From the keyboard, hold Space or Enter.', 'حذف پروژه برگشت ندارد، پس دکمه به‌جای یک دیالوگ دوم، فشار نگه‌داشته می‌خواهد. زودتر رها کنید تا پرشدن خالی شود؛ ۱٫۲ ثانیه نگه دارید تا سطل به تیک تبدیل شود و سرور حذف کند (توست همان رفت‌وبرگشت است). با کیبورد Space یا Enter را نگه دارید.') }}
        </p>
    </div>
    <div style="display: grid; gap: 1rem; border: 1px solid var(--nx-danger-soft); border-radius: var(--nx-radius-xl); padding: 1.25rem; max-inline-size: 36rem">
        <div class="pg-row" style="justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap">
            <div style="display: grid; gap: .25rem; min-inline-size: 14rem; flex: 1">
                <strong>{{ $say('Delete “Nabu storefront”', 'حذف «ویترین نابو»') }}</strong>
                <span style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('All 1,284 orders, 3 environments and their domains go with it.', 'هر ۱٬۲۸۴ سفارش، ۳ محیط و دامنه‌هایشان هم با آن پاک می‌شوند.') }}</span>
            </div>
            <x-nx::hold-to-confirm :label="$say('Hold to delete', 'برای حذف نگه دارید')" :done-label="$say('Deleted', 'حذف شد')"
                :hint="$say('Press and hold for a second to delete the project', 'برای حذف پروژه، یک ثانیه نگه دارید')"
                x-on:nx-confirm="$wire.save({{ \Illuminate\Support\Js::from($say('Project deleted', 'پروژه حذف شد')) }})" />
        </div>
    </div>
</section>

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Revoke API keys', 'باطل‌کردن کلیدهای API') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('The ring variant runs the progress around the icon — tighter rows, same safety. The second one is a warning tone with a longer hold.', 'گونهٔ حلقه، پیشروی را دور آیکون می‌برد — سطرهای جمع‌وجورتر، همان ایمنی. دومی با رنگ هشدار و نگه‌داشتن طولانی‌تر است.') }}
        </p>
        @foreach ([['prod', $say('Production key', 'کلید تولید'), 'nbx_live_•••• 8f2c', 'danger', 1200], ['ci', $say('CI runner key', 'کلید اجراکنندهٔ CI'), 'nbx_test_•••• 41aa', 'warning', 2000]] as [$id, $name, $mask, $tone, $ms])
            <div class="pg-row" style="justify-content: space-between; gap: 1rem; padding-block: .5rem; border-block-start: 1px solid var(--nx-border)">
                <span style="display: grid">
                    <span style="font-weight: 600">{{ $name }}</span>
                    <code dir="ltr" style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $mask }}</code>
                </span>
                <x-nx::hold-to-confirm variant="ring" size="sm" :tone="$tone" :duration="$ms" icon="lock"
                    :label="$say('Revoke', 'ابطال')" :done-label="$say('Revoked', 'باطل شد')"
                    :hint="$say('Press and hold to revoke this key', 'برای ابطال این کلید نگه دارید')"
                    x-on:nx-confirm="$wire.ping({{ \Illuminate\Support\Js::from($say($name.' revoked', $name.' باطل شد')) }})" />
            </div>
        @endforeach
    </section>

    <section class="pg-box" dir="rtl" lang="fa" style="gap: 1rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Right to left', 'راست‌به‌چپ') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('The bar fills from the inline start — the right edge here.', 'نوار از ابتدای سطر پر می‌شود — این‌جا از لبهٔ راست.') }}
        </p>
        <div class="pg-row">
            <x-nx::hold-to-confirm label="برای خالی‌کردن سبد نگه دارید" done-label="سبد خالی شد" hint="برای خالی‌کردن سبد خرید نگه دارید" size="lg" />
        </div>
    </section>
</div>
