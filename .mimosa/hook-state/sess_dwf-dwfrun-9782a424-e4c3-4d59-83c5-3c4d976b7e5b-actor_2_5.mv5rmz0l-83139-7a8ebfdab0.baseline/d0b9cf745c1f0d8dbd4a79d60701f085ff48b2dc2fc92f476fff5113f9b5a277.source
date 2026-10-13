{{--
    The member selector as a project-assignment picker: a searchable people
    list where every picked row also chooses a role. The trigger’s avatar
    stack rebuilds on the fly and the “+n” rolls in Persian digits; the
    summary under it renders what the server now holds.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $people = [
        ['id' => 'ava', 'name' => $say('Ava Karimi', 'آوا کریمی'), 'email' => 'ava@nabu.example'],
        ['id' => 'soheil', 'name' => $say('Soheil Nouri', 'سهیل نوری'), 'email' => 'soheil@nabu.example'],
        ['id' => 'mona', 'name' => $say('Mia Novak', 'مونا احمدی'), 'email' => 'mona@nabu.example'],
        ['id' => 'kian', 'name' => $say('Kian Rajaee', 'کیان رجایی'), 'email' => 'kian@nabu.example'],
        ['id' => 'sara', 'name' => $say('Sara Mohammadi', 'سارا محمدی'), 'email' => 'sara@nabu.example'],
        ['id' => 'nima', 'name' => $say('Nima Farhadi', 'نیما فرهادی'), 'email' => 'nima@nabu.example'],
        ['id' => 'hana', 'name' => $say('Hana Qomi', 'حنا قمی'), 'email' => 'hana@nabu.example'],
        ['id' => 'parham', 'name' => $say('Parham Sabeti', 'پرهام ثابتی'), 'email' => 'parham@nabu.example'],
    ];

    $team = $state['team'] ?? ['ava', 'soheil'];
    $roles = $state['teamRoles'] ?? [];
    $roleNames = ['viewer' => $say('Viewer', 'بیننده'), 'editor' => $say('Editor', 'ویرایشگر'), 'admin' => $say('Admin', 'مدیر')];
@endphp

<section class="pg-box" style="gap: 1rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Assign the shop redesign', 'محول‌کردن بازطراحی فروشگاه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Open the stack and search (try “sara”); ticking someone adds them and unlocks their role select. Roles post as name_roles[id] and land in state.teamRoles.', 'استک را باز کنید و جست‌وجو کنید («سارا» را امتحان کنید)؛ تیک‌زدن نفر او را می‌افزاید و select نقشش را باز می‌کند. نقش‌ها به‌صورت name_roles[id] پست و در state.teamRoles می‌نشینند.') }}
        </p>
    </div>
    <div class="pg-row">
        <x-nx::member-selector name="team" :members="$people" :value="$team" max="5"
            wire:model.live="state.team" roles-model="state.teamRoles" :roles="$roles"
            :role-options="[
                ['value' => 'viewer', 'label' => $roleNames['viewer']],
                ['value' => 'editor', 'label' => $roleNames['editor']],
                ['value' => 'admin', 'label' => $roleNames['admin']],
            ]" default-role="editor" />
    </div>
    <ul style="list-style: none; margin: 0; padding: 0; display: grid; gap: .375rem">
        @forelse (array_filter(array_map(fn ($id) => collect($people)->firstWhere('id', $id), $team)) as $member)
            @php $role = $roleNames[$roles[$member['id']] ?? 'editor'] ?? $roleNames['editor']; @endphp
            <li class="pg-row" style="gap: .75rem; font-size: var(--nx-text-sm)">
                <x-nx::avatar :name="$member['name']" size="xs" />
                <strong>{{ $member['name'] }}</strong>
                <x-nx::badge :tone="($roles[$member['id']] ?? 'editor') === 'admin' ? 'accent' : 'neutral'">{{ $role }}</x-nx:badge>
                <span dir="ltr" style="color: var(--nx-text-muted)">{{ $member['email'] }}</span>
            </li>
        @empty
            <li style="color: var(--nx-text-muted); font-size: var(--nx-text-sm)">{{ $say('Nobody assigned yet — pick someone above.', 'هنوز کسی محول نشده — از بالا یکی را انتخاب کنید.') }}</li>
        @endforelse
    </ul>
</section>
