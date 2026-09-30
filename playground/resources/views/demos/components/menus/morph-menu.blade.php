{{--
    The morph menu as a hiring-board filter: the trigger grows into its own
    department list, the selection count rolls, and the candidate pool below
    is actually filtered on the server. A second, upward-growing variant
    filters labels in a composer.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $candidates = [
        ['name' => $say('Ava Karimi', 'آوا کریمی'), 'team' => 'design'],
        ['name' => $say('Soheil Nouri', 'سهیل نوری'), 'team' => 'backend'],
        ['name' => $say('Mona Ahmadi', 'مونا احمدی'), 'team' => 'design'],
        ['name' => $say('Kian Rajaee', 'کیان رجایی'), 'team' => 'support'],
        ['name' => $say('Sara Mohammadi', 'سارا محمدی'), 'team' => 'backend'],
        ['name' => $say('Nima Farhadi', 'نیما فرهادی'), 'team' => 'research'],
    ];
    $teams = $state['teams'] ?? [];
    $matching = $teams === []
        ? $candidates
        : array_values(array_filter($candidates, fn ($candidate) => in_array($candidate['team'], $teams, true)));
@endphp

<div class="pg-grid">
    <section class="pg-box" style="gap: 1rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Filter the candidate pool', 'پالایش استخر نامزدها') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('The button’s own container becomes the checklist; the count rolls as you tick, “clear” empties it, and the pool below refilters on the server with every change.', 'ظرف خودِ دکمه به فهرست انتخاب بدل می‌شود؛ شمارنده با هر تیک می‌غلتد، «پاک کردن» خالی‌اش می‌کند و استخر پایین با هر تغییر روی سرور دوباره پالایش می‌شود.') }}
            </p>
        </div>
        <div class="pg-row" style="min-block-size: 15rem; align-items: start">
            <x-nx::morph-menu label="{{ $say('Department', 'دپارتمان') }}" name="teams" :value="$teams"
                wire:model.live="state.teams" :options="[
                    ['value' => 'design', 'label' => $say('Design', 'طراحی'), 'icon' => 'edit'],
                    ['value' => 'backend', 'label' => $say('Backend', 'بک‌اند'), 'icon' => 'cpu'],
                    ['value' => 'support', 'label' => $say('Support', 'پشتیبانی'), 'icon' => 'message'],
                    ['value' => 'research', 'label' => $say('Research', 'تحقیق'), 'icon' => 'globe', 'disabled' => true],
                ]" />
        </div>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ NabuXUI::formatNumber(count($matching)).' '.$say('of', 'از').' '.NabuXUI::formatNumber(count($candidates)).' '.$say('candidates match', 'نامزد مطابقت دارد') }}:
        </p>
        <div class="pg-row">
            @forelse ($matching as $candidate)
                <x-nx::badge tone="accent">{{ $candidate['name'] }}</x-nx::badge>
            @empty
                <x-nx::empty-state icon="users" size="sm" :title="$say('No one in these departments', 'در این دپارتمان‌ها کسی نیست')" style="padding-block: 1rem" />
            @endforelse
        </div>
    </section>

    <section class="pg-box" style="gap: 1rem">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Growing upward: labels on a note', 'رشد به بالا: برچسب‌های یک یادداشت') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('side="top" grows the list upward — handy at the bottom of a composer, above a toolbar.', 'با side="top" فهرست به بالا رشد می‌کند — مناسب تهِ یک جعبهٔ نوشتار، بالای نوار ابزار.') }}
            </p>
        </div>
        <div style="display: grid; gap: .75rem; padding: 1rem; border: 1px solid var(--nx-border); border-radius: var(--nx-radius-xl); background: var(--nx-surface-2)">
            <textarea class="nx-textarea" rows="3" placeholder="{{ $say('Write today’s note…', 'یادداشت امروز را بنویس…') }}" aria-label="{{ $say('Note body', 'متن یادداشت') }}"></textarea>
            <div class="pg-row" style="justify-content: space-between; min-block-size: 3.25rem; align-items: end">
                <x-nx::morph-menu label="{{ $say('Labels', 'برچسب‌ها') }}" name="note-labels" side="top" icon="star"
                    :value="$state['labels'] ?? []" wire:model.live="state.labels" :options="[
                        ['value' => 'focus', 'label' => $say('Deep focus', 'تمرکز عمیق'), 'icon' => 'zap'],
                        ['value' => 'meeting', 'label' => $say('Meeting', 'جلسه'), 'icon' => 'users'],
                        ['value' => 'idea', 'label' => $say('Idea', 'ایده'), 'icon' => 'sparkles'],
                        ['value' => 'later', 'label' => $say('Later', 'بعداً'), 'icon' => 'pause'],
                    ]" />
                <x-nx::button variant="secondary" size="sm" icon="check" wire:click="save(@js($say('Note saved', 'یادداشت ذخیره شد')))">{{ $say('Save note', 'ذخیرهٔ یادداشت') }}</x-nx::button>
            </div>
        </div>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Bound to Livewire:', 'به Livewire بسته شده:') }}
            <code>{{ json_encode($state['labels'] ?? []) }}</code>
        </p>
    </section>
</div>
