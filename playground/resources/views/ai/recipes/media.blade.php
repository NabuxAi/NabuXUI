{{-- media: the files moment — a drop zone, three rows, one uploading. --}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $e, string $f) => $fa ? $f : $e;
    $files = [
        ['name' => 'brand-kit.pdf', 'meta' => $say('4.2 MB · today', '۴٫۲ مگابایت · امروز'), 'icon' => 'file'],
        ['name' => 'hero-shoot-final.zip', 'meta' => $say('182 MB · yesterday', '۱۸۲ مگابایت · دیروز'), 'icon' => 'folder'],
    ];
@endphp
<div style="max-inline-size: 34rem; margin-inline: auto; display: grid; gap: .8rem">
    <div style="display: grid; place-items: center; gap: .3rem; padding: 1.4rem; border: 1.5px dashed var(--nx-border-strong);
                border-radius: var(--nx-radius-lg); background: var(--nx-surface-2); text-align: center">
        <b style="font-size: var(--nx-text-sm)">{{ $say('Drop files here', 'فایل‌ها را اینجا رها کنید') }}</b>
        <span style="font-size: var(--nx-text-xs); color: var(--nx-text-muted)">{{ $say('or browse — up to 250 MB each', 'یا انتخاب کنید — هرکدام تا ۲۵۰ مگابایت') }}</span>
    </div>
    <div style="display: grid; gap: .5rem">
        @foreach ($files as $file)
            <div class="aist-file">
                {{ \NabuXUI\NabuXUI::icon($file['icon']) }}
                <span class="aist-meta"><b>{{ $file['name'] }}</b><span>{{ $file['meta'] }}</span></span>
                <x-nx::copy-button :value="$file['name']" size="sm" variant="ghost" :label="$say('Copy name', 'کپی نام')" />
            </div>
        @endforeach
        <div class="aist-file">
            {{ \NabuXUI\NabuXUI::icon('upload') }}
            <span class="aist-meta"><b>q4-report.mp4</b><span>{{ $say('Uploading… 72%', 'در حال بارگذاری… ۷۲٪') }}</span></span>
        </div>
        <x-nx::progress :value="72" />
    </div>
</div>
