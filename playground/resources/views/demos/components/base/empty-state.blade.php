{{--
    The empty state at both sizes: a first-run page with two ways forward, and
    a small no-results panel inside a real search flow — with its own action
    slot wired to the demo component.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $q = trim((string) ($state['projectQuery'] ?? ''));
    $projects = collect([
        $say('Landing rewrite', 'بازنویسی لندینگ'), $say('Gift cards', 'کارت هدیه'), $say('Onboarding v2', 'راه‌اندازی نسخهٔ ۲'),
    ]);
    $results = $q === '' ? $projects : $projects->filter(fn ($p) => str_contains(mb_strtolower($p), mb_strtolower($q)))->values();
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The first-run page', 'صفحهٔ نخستین اجرا') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The plate breathes, two sparks orbit it — pure CSS, resting under prefers-reduced-motion. Two doors out: loud and quiet.', 'بشقاب نفس می‌کشد و دو جرقه دورش می‌گردند — خالص CSS و آرام زیر prefers-reduced-motion. دو راه بیرون: بلند و آرام.') }}
        </p>
    </div>
    <x-nx::empty-state icon="folder" :title="$say('No projects yet', 'هنوز پروژه‌ای نیست')"
        :description="$say('A project holds one board, one inbox and its own members. Start from a blank page or let a sample do the talking.', 'هر پروژه یک برد، یک صندوق ورودی و اعضای خودش را دارد. از صفحهٔ خالی شروع کنید یا نمونه حرف بزند.')">
        <x-slot:actions>
            <x-nx::button variant="primary" icon="plus" wire:click="save('{{ $say('Blank project created', 'پروژهٔ خالی ساخته شد') }}')">{{ $say('New project', 'پروژهٔ تازه') }}</x-nx::button>
            <x-nx::button variant="secondary" icon="sparkles" wire:click="ping('{{ $say('Sample project added', 'پروژهٔ نمونه اضافه شد') }}')">{{ $say('Show me a sample', 'یک نمونه نشانم بده') }}</x-nx::button>
        </x-slot:actions>
    </x-nx::empty-state>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The small one, inside a search', 'کوچکش، داخل یک جست‌وجو') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('size="sm" fits inside a panel; typing a name that matches nothing lands here, and the ghost button clears the way back.', 'با size="sm" داخل پنل جا می‌شود؛ نامی که با هیچ چیز نخواند این‌جا می‌نشیند و دکمهٔ شبح راه برگشت را باز می‌کند.') }}
        </p>
    </div>
    <x-nx::input :label="$say('Your projects', 'پروژه‌های شما')" icon="search" :placeholder="$say('Type a project name…', 'نام پروژه…')" wire:model.live.debounce.300ms="state.projectQuery" />
    @if ($results->isNotEmpty())
        <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .5rem">
            @foreach ($results as $project)
                <li class="pg-row" style="gap: .6rem; padding: .6rem .9rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-lg)">
                    <x-nx::icon name="folder" />
                    <strong style="font-weight: 600">{{ $project }}</strong>
                </li>
            @endforeach
        </ul>
    @else
        <div style="padding: 1.25rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl)">
            <x-nx::empty-state icon="search" size="sm"
                :title="$say('Nothing matches “'.$q.'”', 'چیزی با «'.$q.'» نخواند')"
                :description="$say('Names search exactly; try fewer letters.', 'جست‌وجوی نام دقیق است؛ حروف کمتری را امتحان کنید.')">
                <x-slot:actions>
                    <x-nx::button size="sm" variant="ghost" icon="x" wire:click="$set('state.projectQuery', '')">{{ $say('Clear the search', 'پاک‌کردن جست‌وجو') }}</x-nx::button>
                </x-slot:actions>
            </x-nx::empty-state>
        </div>
    @endif
</section>
