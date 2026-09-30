{{--
    The registration card signing a real person up for a workshop: three
    steps, ticket steppers, a rolling total in tomans, then the server sets
    `success` and the card draws its check and throws confetti. The reset
    button clears the demo so the whole flow can be walked again.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;
@endphp

<section class="pg-box" style="gap: 1rem; justify-items: center">
    <div style="justify-self: start; display: grid; gap: .25rem">
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Registering for the design-systems workshop', 'ثبت‌نام کارگاه سیستم طراحی') }}</h3>
        <p style="margin: 0; color: var(--nx-text-muted)">
            {{ $say('Steps before the last never submit; the ticket steps recompute the total live and the figures roll in Persian digits. Fill the name, walk to the end, register.', 'مرحله‌های قبل از آخر هرگز ارسال نمی‌کنند؛ شمارنده‌های بلیت جمع را زنده حساب می‌کنند و عددها با ارقام فارسی می‌غلتند. نام را بنویسید، تا انتها بروید و ثبت‌نام کنید.') }}
        </p>
    </div>
    <x-nx::registration-card model="state.reg" wire:submit="$set('state.registered', true)"
        :success="! empty($state['registered'])" currency="IRR"
        :event="[
            'title' => $say('Design Systems in Practice', 'سیستم طراحی در عمل'),
            'date' => $say('24 Aban, 9:30', '۲۴ آبان، ۹:۳۰'),
            'location' => $say('Tehran + online', 'تهران + برخط'),
            'badge' => $say('Limited seats', 'ظرفیت محدود'),
        ]"
        :tickets="[
            ['id' => 'inperson', 'label' => $say('In person', 'حضوری'), 'price' => 2800000, 'description' => $say('Lunch and the hands-on lab', 'با ناهار و آزمایشگاه عملی'), 'max' => 2],
            ['id' => 'online', 'label' => $say('Online', 'برخط'), 'price' => 1900000, 'description' => $say('Live, with recordings', 'زنده، همراه با ضبط'), 'max' => 5],
            ['id' => 'student', 'label' => $say('Student', 'دانشجویی'), 'price' => 0, 'description' => $say('With a valid ID', 'با معرفی‌نامهٔ معتبر'), 'max' => 1],
        ]" />

    @if (! empty($state['registered']))
        <x-nx::button variant="ghost" icon="arrow-left" wire:click="$set('state.registered', false); $set('state.reg', [])">
            {{ $say('Reset the demo and walk it again', 'بازنشانی دمو و اجرای دوباره') }}
        </x-nx::button>
    @endif
</section>
