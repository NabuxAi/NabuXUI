{{--
    <x-nx::pagination :paginator="$users" />             links (server pages)
    <x-nx::pagination :paginator="$users" livewire />    wire:click="gotoPage(n)" with Livewire's WithPagination
    or explicit :page / :pages with a :url callback.
--}}
@props(['paginator' => null, 'page' => 1, 'pages' => 1, 'url' => null, 'livewire' => false, 'siblings' => 1])
@php
    if ($paginator) {
        $page = $paginator->currentPage();
        $pages = $paginator->lastPage();
        $url ??= fn ($p) => $paginator->url($p);
        $pageName = method_exists($paginator, 'getPageName') ? $paginator->getPageName() : 'page';
    }
    $pageName ??= 'page';
    $set = collect([1, $pages])->merge(range($page - $siblings, $page + $siblings))->filter(fn ($p) => $p >= 1 && $p <= $pages)->unique()->sort()->values();
    $range = [];
    foreach ($set as $i => $p) {
        if ($i > 0 && $p - $set[$i - 1] > 1) $range[] = $p - $set[$i - 1] === 2 ? $p - 1 : null;
        $range[] = $p;
    }
    $link = function ($target) use ($url, $livewire, $pages, $pageName) {
        $disabled = $target < 1 || $target > $pages;
        if ($livewire) return ['tag' => 'button', 'attrs' => $disabled ? 'disabled aria-disabled="true"' : 'wire:click="gotoPage('.$target.", '".e($pageName)."')\"", 'disabled' => $disabled];
        return ['tag' => $disabled ? 'span' : 'a', 'attrs' => $disabled ? 'aria-disabled="true"' : 'href="'.e($url ? $url($target) : '?page='.$target).'"', 'disabled' => $disabled];
    };
@endphp
@if ($pages > 1)
<nav {{ $attributes->class('nx-pagination')->merge(['aria-label' => __('nabuxui::ui.pagination')]) }} x-data="nxNavIndicator(false)">
    <span class="nx-indicator" aria-hidden="true"></span>
    @php $prev = $link($page - 1); @endphp
    <{{ $prev['tag'] }} class="nx-page-link" {!! $prev['attrs'] !!} aria-label="{{ __('nabuxui::ui.previous') }}" @if ($prev['tag'] === 'button') type="button" @endif>{{ \NabuXUI\NabuXUI::icon('chevron-left') }}</{{ $prev['tag'] }}>
    @foreach ($range as $i => $p)
        @if ($p === null)
            <span class="nx-page-link" data-gap aria-hidden="true">…</span>
        @else
            @php $l = $link($p); @endphp
            <{{ $l['tag'] }} class="nx-page-link" {!! $l['attrs'] !!} aria-label="{{ __('nabuxui::ui.page', ['page' => $p]) }}" @if ($p === $page) aria-current="page" @endif @if ($l['tag'] === 'button') type="button" @endif>{{ \NabuXUI\NabuXUI::formatNumber($p) }}</{{ $l['tag'] }}>
        @endif
    @endforeach
    @php $next = $link($page + 1); @endphp
    <{{ $next['tag'] }} class="nx-page-link" {!! $next['attrs'] !!} aria-label="{{ __('nabuxui::ui.next') }}" @if ($next['tag'] === 'button') type="button" @endif>{{ \NabuXUI\NabuXUI::icon('chevron-right') }}</{{ $next['tag'] }}>
</nav>
@endif
