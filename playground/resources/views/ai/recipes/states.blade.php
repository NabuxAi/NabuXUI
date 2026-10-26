{{-- states: the no-data moments — empty state, then the error variant. --}}
@php $fa = app()->getLocale() === 'fa'; $say = fn (string $e, string $f) => $fa ? $f : $e; @endphp
<div style="display: grid; gap: 1.2rem">
    <div style="border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface); padding: .5rem .75rem">
        <x-nx::empty-state icon="search" :title="$say('Nothing here yet', 'هنوز چیزی این‌جا نیست')"
            :description="$say('Create your first project and it will show up on this page.', 'نخستین پروژه را بسازید تا در همین صفحه ظاهر شود.')">
            <x-slot:actions>
                <x-nx::button variant="primary" size="sm" icon="plus">{{ $say('New project', 'پروژهٔ تازه') }}</x-nx::button>
            </x-slot:actions>
        </x-nx::empty-state>
    </div>
    <div style="border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg); background: var(--nx-surface); padding: .5rem .75rem">
        <x-nx::empty-state icon="alert-circle" :title="$say('503 — taking a breath', '۵۰۳ — یک نفس')" tone="danger"
            :description="$say('Our region had a hiccup. Everything is queued and nothing was lost.', 'ناحیهٔ ما سکته‌ای زد. همه چید و هیچ‌چیز از دست نرفت.')">
            <x-slot:actions>
                <x-nx::button size="sm" icon="arrow-right">{{ $say('Retry', 'تلاش دوباره') }}</x-nx::button>
                <x-nx::button variant="ghost" size="sm" icon="info">{{ $say('Status page', 'صفحهٔ وضعیت') }}</x-nx::button>
            </x-slot:actions>
        </x-nx::empty-state>
    </div>
</div>
