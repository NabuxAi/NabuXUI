{{-- schedule: the week picker — a live 7-day grid (Alpine) above the plan steps. --}}
@php $fa = app()->getLocale() === 'fa'; $say = fn (string $e, string $f) => $fa ? $f : $e; @endphp
<div style="max-inline-size: 32rem; margin-inline: auto; display: grid; gap: 1rem"
    x-data="{ on: $el.dataset.picked ? JSON.parse($el.dataset.picked) : [false, true, false, true, false, false, false] }">
    <div class="aist-recipe-row" style="justify-content: space-between">
        <b style="font-size: var(--nx-text-sm)">{{ $say('This week', 'این هفته') }}</b>
        <span style="font-size: var(--nx-text-xs); color: var(--nx-text-muted)" x-text="on.filter(Boolean).length + ({{ $fa ? "' روز فعال'" : "' active days'" }})"></span>
    </div>
    <div class="aist-week" role="group" :aria-label="{{ $say('Pick days', 'انتخاب روزها') }}">
        @foreach ([[$fa, 'د', 'Mon'], [$fa, 'ی', 'Tue'], [$fa, 'د', 'Wed'], [$fa, 'س', 'Thu'], [$fa, 'چ', 'Fri'], [$fa, 'پ', 'Sat'], [$fa, 'ش', 'Sun']] as $i => $day)
            <button type="button" class="aist-day" x-on:click="on[{{ $i }}] = !on[{{ $i }}]" :aria-pressed="on[{{ $i }}] ? 'true' : 'false'"
                :data-on="on[{{ $i }}] ? '' : null" :aria-label="{{ $day[2] }}">{{ $day[1] }}</button>
        @endforeach
    </div>
    <x-nx::steps :steps="[
        $say('Pick days', 'انتخاب روزها'),
        $say('Set hours', 'تعیین ساعات'),
        $say('Invite the team', 'دعوت تیم'),
    ]" :current="1" />
</div>
