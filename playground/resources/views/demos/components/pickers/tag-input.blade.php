{{--
    Tag inputs in context: a job post's skills (capped at six, with
    suggestions, bound to the server and echoed back) and an email "To" field
    where pasting a comma-separated list adds every address at once.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $skills = $state['skills'] ?? ['Laravel', 'Livewire', $say('Accessibility', 'دسترس‌پذیری')];
    $suggest = ['Laravel', 'Livewire', 'Alpine.js', 'Tailwind CSS', 'PHP', 'MySQL', 'Redis', 'Vue', 'React', 'TypeScript', $say('Accessibility', 'دسترس‌پذیری'), $say('Motion design', 'طراحی حرکت'), $say('Testing', 'تست‌نویسی')];
@endphp
<section class="pg-box" style="gap: 1rem; max-inline-size: 36rem" aria-labelledby="ti-job-title">
    <div>
        <h3 class="pg-title" id="ti-job-title" style="margin: 0">{{ $say('Senior Laravel developer', 'توسعه‌دهندهٔ ارشد لاراول') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('Skills candidates are filtered on — up to six.', 'مهارت‌هایی که داوطلبان با آن‌ها فیلتر می‌شوند — حداکثر شش تا.') }}</p>
    </div>
    <x-nx::tag-input name="skills" :value="$skills" :max="6" :suggestions="$suggest"
        :label="$say('Required skills', 'مهارت‌های لازم')" :placeholder="$say('Add a skill…', 'یک مهارت اضافه کنید…')"
        wire:model.live="state.skills" />
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('Saved on the server:', 'ذخیره‌شده روی سرور:') }}
        <strong style="color: var(--nx-text)">{{ count($skills) ? implode($fa ? '، ' : ', ', $skills) : '—' }}</strong>
    </p>
</section>

<section class="pg-box" style="gap: .75rem; max-inline-size: 36rem" aria-labelledby="ti-mail-title">
    <h3 class="pg-title" id="ti-mail-title" style="margin: 0">{{ $say('Share the report', 'اشتراک گزارش') }}</h3>
    <x-nx::tag-input :value="['sara@nabux.ir']" :label="$say('Recipients', 'گیرندگان')" :placeholder="$say('name@company.com, …', 'name@company.com، …')" dir="ltr" />
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('Paste “reza@nabux.ir, nika@nabux.ir” — each address becomes a chip. Add sara@nabux.ir again and the chip that is already there flashes.', '«reza@nabux.ir, nika@nabux.ir» را paste کنید — هر نشانی یک برچسب می‌شود. sara@nabux.ir را دوباره بزنید تا برچسب موجود چشمک بزند.') }}
    </p>
    <div class="pg-row" style="justify-content: flex-end">
        <x-nx::button variant="primary" icon="mail" wire:click="save(@js($say('Report shared', 'گزارش به اشتراک گذاشته شد')))">{{ $say('Send', 'ارسال') }}</x-nx::button>
    </div>
</section>
