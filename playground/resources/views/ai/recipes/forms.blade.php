{{-- forms: a compact intake form — inputs, a choice, a toggle, chips. --}}
@php $fa = app()->getLocale() === 'fa'; $say = fn (string $e, string $f) => $fa ? $f : $e; @endphp
<div style="max-inline-size: 30rem; margin-inline: auto; display: grid; gap: 1rem">
    <div class="aist-recipe-grid">
        <x-nx::field :label="$say('Project name', 'نام پروژه')" name="ai-form-name">
            <x-nx::input name="ai-form-name" :placeholder="$say('Aurora redesign', 'بازطراحی اورورا')" />
        </x-nx::field>
        <x-nx::field :label="$say('Due date', 'مهلت')" name="ai-form-date">
            <x-nx::input type="date" name="ai-form-date" />
        </x-nx::field>
    </div>
    <x-nx::field :label="$say('Brief', 'توضیح کوتاه')" name="ai-form-brief">
        <x-nx::textarea name="ai-form-brief" :rows="3" :placeholder="$say('One paragraph the whole team can act on…', 'یک بند که همهٔ تیم بتواند رویش کار کند…')"></x-nx::textarea>
    </x-nx::field>
    <div class="aist-recipe-row" style="justify-content: space-between">
        <x-nx::checkbox name="ai-form-notify" :label="$say('Notify the channel', 'اطلاع به کانال')" />
        <x-nx::switch name="ai-form-private" :label="$say('Private draft', 'پیش‌نویس خصوصی')" />
    </div>
    <div class="aist-recipe-row">
        <x-nx::chip>{{ $say('design', 'طراحی') }}</x-nx::chip>
        <x-nx::chip>{{ $say('web', 'وب') }}</x-nx::chip>
        <x-nx::chip removable>{{ $say('q3', 'فصل۳') }}</x-nx::chip>
        <x-nx::button variant="primary" style="margin-inline-start: auto" icon-end="arrow-right">{{ $say('Save draft', 'ذخیرهٔ پیش‌نویس') }}</x-nx::button>
    </div>
</div>
