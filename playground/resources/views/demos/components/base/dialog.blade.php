{{--
    The dialog where it earns its keep: a destructive action that demands you
    type the workspace name, and an invite dialog whose footer commits. Both
    open from Livewire state and close from the client.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $typed = trim((string) ($state['confirmName'] ?? ''));
    $armed = strtolower($typed) === 'nabu-store';
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Delete the workspace — prove you mean it', 'حذف ورک‌اسپیس — ثابت کنید منظورتان همین است') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Backdrop clicks are off here; the red button stays inert until the name matches exactly. Esc still works.', 'کلیک روی پس‌زمینه اینجا خاموش است؛ دکمهٔ سرخ تا وقتی نام دقیقاً مطابقت نکند کاری نمی‌کند. Esc همچنان کار می‌کند.') }}
        </p>
    </div>
    <div class="pg-row">
        <x-nx::button variant="danger" icon="trash" wire:click="$set('state.confirmOpen', true)">{{ $say('Delete workspace…', 'حذف ورک‌اسپیس…') }}</x-nx::button>
        <x-nx::button variant="ghost" wire:click="ping('{{ $say('Workspace is safe', 'ورک‌اسپیس سالم است') }}')">{{ $say('Keep it', 'نگهش دار') }}</x-nx::button>
    </div>
    <x-nx::dialog wire:model="state.confirmOpen" :title="$say('Delete nabu-store?', 'nabu-store حذف شود؟')" :description="$say('Every board, file and integration goes with it.', 'هر تختی، فایلی و اتصالی با آن می‌رود.')" size="sm" :close-on-backdrop="false">
        <div style="display: grid; gap: .75rem">
            <p style="margin: 0; color: var(--nx-text-muted)">
                {{ $say('Type the workspace name to arm the button:', 'برای مسلح‌شدن دکمه، نام ورک‌اسپیس را بنویسید:') }}
                <code dir="ltr" style="font: 500 var(--nx-text-sm) var(--nx-font-mono); color: var(--nx-danger-text)">nabu-store</code>
            </p>
            <x-nx::input wire:model.live="state.confirmName" :label="$say('Workspace name', 'نام ورک‌اسپیس')" dir="ltr" />
        </div>
        <x-slot:footer>
            <x-nx::button variant="ghost" wire:click="$set('state.confirmOpen', false)">{{ $say('Cancel', 'انصراف') }}</x-nx::button>
            <x-nx::button variant="danger" icon="trash" :disabled="$armed ? null : 'disabled'" wire:click="save('{{ $say('Workspace deleted', 'ورک‌اسپیس حذف شد') }}')">{{ $say('Delete forever', 'برای همیشه حذف کن') }}</x-nx::button>
        </x-slot:footer>
    </x-nx::dialog>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The everyday one: invite a teammate', 'یکی که هر روز است: دعوت هم‌تیمی') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Title and description feed the dialog’s accessible names; the footer carries the quiet and the loud action.', 'عنوان و توضیح نام‌های دسترس‌پذیر دیالوگ را می‌سازند؛ فوتر کنش آرام و بلند را با هم دارد.') }}
        </p>
    </div>
    <x-nx::button variant="secondary" icon="users" wire:click="$set('state.inviteOpen', true)">{{ $say('Invite teammate', 'دعوت هم‌تیمی') }}</x-nx::button>
    <x-nx::dialog wire:model="state.inviteOpen" :title="$say('Invite to nabu-store', 'دعوت به nabu-store')" :description="$say('They receive an email and join with the role you pick.', 'ایمیل می‌گیرند و با نقشی که انتخاب می‌کنید می‌آیند.')">
        <div style="display: grid; gap: 1rem">
            <x-nx::input label="{{ $say('Email', 'ایمیل') }}" type="email" icon="mail" wire:model.blur="state.inviteEmail" />
            <x-nx::select label="{{ $say('Role', 'نقش') }}" :options="[
                'admin' => $say('Admin — full control', 'مدیر — کنترل کامل'),
                'editor' => $say('Editor — no billing', 'ویراستار — بدون صورت‌حساب'),
                'viewer' => $say('Viewer — read only', 'بیننده — فقط خواندن'),
            ]" wire:model="state.inviteRole" />
        </div>
        <x-slot:footer>
            <x-nx::button variant="ghost" wire:click="$set('state.inviteOpen', false)">{{ $say('Not now', 'الان نه') }}</x-nx::button>
            <x-nx::button variant="primary" icon="mail" wire:click="save('{{ $say('Invitation sent', 'دعوت‌نامه ارسال شد') }}')">{{ $say('Send invitation', 'ارسال دعوت‌نامه') }}</x-nx::button>
        </x-slot:footer>
    </x-nx::dialog>
</section>
