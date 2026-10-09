<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

/**
 * The «فایل‌ها» board: a self-contained sample tree — a plain PHP array, no
 * database — inside one <x-nx::file-manager>. Stepping through folders and
 * the breadcrumb trail are the block's own Alpine; this page rides the four
 * events the block dispatches (nx-navigate, nx-open, nx-view, nx-upload) and
 * keeps each in Livewire state, so the status card proves the wiring and a
 * re-render always hands the board back the folder it is open in.
 */
class FileManager extends Page
{
    protected string $view = 'filament.pages.file-manager';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFolder;

    protected static string|UnitEnum|null $navigationGroup = 'ابزارها';

    protected static ?string $navigationLabel = 'فایل‌ها';

    protected static ?string $title = 'فایل‌ها';

    /** The folder the board sits in — kept from nx-navigate (null = the root). */
    public ?string $currentFolder = null;

    /** The entry whose details drawer last opened — kept from nx-open. */
    public ?string $selectedFile = null;

    /** grid|list — kept from nx-view. */
    public string $viewMode = 'grid';

    /** How many files the picker last handed over — kept from nx-upload. */
    public ?int $pickedFiles = null;

    /** nx-navigate: the block's Alpine stepped into a folder (null = the root). */
    public function trackFolder(?string $folder): void
    {
        $this->currentFolder = $folder;
    }

    /** nx-open: a file's details drawer slid in. */
    public function trackFile(?string $id): void
    {
        $this->selectedFile = $id;
    }

    /** nx-view: the toolbar's segmented switched between grid and list. */
    public function trackView(string $view): void
    {
        $this->viewMode = in_array($view, ['grid', 'list'], true) ? $view : 'grid';
    }

    /** nx-upload: the native picker handed over a FileList (no wire:model bound). */
    public function noteUpload(int $count): void
    {
        $this->pickedFiles = $count;
    }

    /**
     * The sample tree, built fresh on every render (the dates are relative).
     * Every entry: id, name, parent (null = root), kind (or let the extension
     * say), size in bytes, items for folders, modified, href, preview and
     * details — the exact shape the block's blade docblock asks for.
     *
     * @return list<array<string, mixed>>
     */
    public function tree(): array
    {
        // A self-contained thumbnail (data URI, no network) for the drawer previews.
        $thumb = fn (string $word) => 'data:image/svg+xml;charset=utf-8,'.rawurlencode(
            '<svg xmlns="http://www.w3.org/2000/svg" width="480" height="320" viewBox="0 0 480 320">'
            .'<rect width="480" height="320" fill="#e8eaf0"/><circle cx="240" cy="130" r="54" fill="#c7cede"/>'
            .'<path d="M158 250h164l-30-44H188z" fill="#b6c0d4"/>'
            .'<text x="240" y="302" text-anchor="middle" font-family="system-ui, sans-serif" font-size="22" fill="#5c667a">'.$word.'</text>'
            .'</svg>'
        );

        return [
            // The root's folders.
            ['id' => 'projects', 'name' => 'پروژه‌ها', 'kind' => 'folder', 'items' => 2, 'modified' => now()->subDays(2)],
            ['id' => 'sfx', 'name' => 'افکت‌های صوتی', 'kind' => 'folder', 'items' => 2, 'modified' => now()->subDays(8)],

            // The root's loose files.
            ['id' => 'brand-guide', 'name' => 'brand-guide.pdf', 'size' => 1218560, 'modified' => now()->subWeeks(2), 'href' => '#',
                'details' => [['label' => 'نسخه', 'value' => '۲٫۱']]],
            ['id' => 'readme', 'name' => 'readme.txt', 'size' => 2048, 'modified' => now()->subMonth()],

            // پروژه‌ها / two city projects.
            ['id' => 'berlin', 'name' => 'پروژهٔ برلین', 'parent' => 'projects', 'kind' => 'folder', 'items' => 3, 'modified' => now()->subDays(2)],
            ['id' => 'istanbul', 'name' => 'پروژهٔ استانبول', 'parent' => 'projects', 'kind' => 'folder', 'items' => 3, 'modified' => now()->subDay()],

            ['id' => 'berlin-hero', 'name' => 'berlin-hero.jpg', 'parent' => 'berlin', 'size' => 2411724, 'modified' => now()->subDays(2),
                'preview' => $thumb('برلین'),
                'details' => [
                    ['label' => 'ابعاد', 'value' => '۲۵۶۰×۱۴۴۰'],
                    ['label' => 'عکاس', 'value' => 'سارا مولر'],
                ]],
            ['id' => 'berlin-teaser', 'name' => 'berlin-teaser.mp4', 'parent' => 'berlin', 'size' => 48234496, 'modified' => now()->subDays(3),
                'details' => [['label' => 'مدت', 'value' => '۰:۴۵']]],
            ['id' => 'berlin-brand', 'name' => 'berlin-brand.pdf', 'parent' => 'berlin', 'size' => 921600, 'modified' => now()->subDays(5), 'href' => '#',
                'details' => [['label' => 'صفحات', 'value' => '۱۲']]],

            ['id' => 'moodboard', 'name' => 'مودبورد', 'parent' => 'istanbul', 'kind' => 'folder', 'items' => 2, 'modified' => now()->subDays(4)],
            ['id' => 'istanbul-spot', 'name' => 'istanbul-spot.mp3', 'parent' => 'istanbul', 'size' => 3145728, 'modified' => now()->subDay(),
                'details' => [['label' => 'مدت', 'value' => '۰:۳۰']]],
            ['id' => 'istanbul-cut', 'name' => 'istanbul-cut.mov', 'parent' => 'istanbul', 'size' => 131941395, 'modified' => now()->subDays(6),
                'details' => [['label' => 'مدت', 'value' => '۱:۲۰']]],

            // پروژه‌ها / پروژهٔ استانبول / مودبورد — the tree's deepest folder.
            ['id' => 'mood-bosphorus', 'name' => 'mood-bosphorus.jpg', 'parent' => 'moodboard', 'size' => 1843200, 'modified' => now()->subDays(4),
                'preview' => $thumb('بسفر'),
                'details' => [['label' => 'ابعاد', 'value' => '۱۶۰۰×۹۰۰']]],
            ['id' => 'mood-galata', 'name' => 'mood-galata.jpg', 'parent' => 'moodboard', 'size' => 1761280, 'modified' => now()->subDays(4),
                'preview' => $thumb('گالاتا'),
                'details' => [['label' => 'ابعاد', 'value' => '۱۶۰۰×۹۰۰']]],

            // افکت‌های صوتی.
            ['id' => 'whoosh', 'name' => 'whoosh.wav', 'parent' => 'sfx', 'size' => 1258291, 'modified' => now()->subDays(8),
                'details' => [['label' => 'مدت', 'value' => '۰:۰۲']]],
            ['id' => 'click', 'name' => 'click.mp3', 'parent' => 'sfx', 'size' => 96256, 'modified' => now()->subDays(9),
                'details' => [['label' => 'مدت', 'value' => '۰:۰۱']]],
        ];
    }

    /** The walk of crumbs to the current folder, for the status card. */
    public function folderTrail(): string
    {
        $byId = collect($this->tree())->keyBy('id');
        $names = [];
        $at = $this->currentFolder;
        $seen = [];

        while ($at !== null && ! in_array($at, $seen, true)) {
            $seen[] = $at;
            $entry = $byId->get($at);
            if ($entry === null) {
                break;
            }
            array_unshift($names, (string) $entry['name']);
            $at = $entry['parent'] ?? null;
        }

        return $names === [] ? 'ریشه (فضای کاری)' : implode(' / ', $names);
    }

    /** A folder's display name, for the status card. */
    public function folderName(?string $id): string
    {
        if ($id === null) {
            return 'ریشه (فضای کاری)';
        }

        foreach ($this->tree() as $entry) {
            if (($entry['kind'] ?? null) === 'folder' && $entry['id'] === $id) {
                return (string) $entry['name'];
            }
        }

        return $id;
    }

    /** A file's display name, for the status card. */
    public function fileName(?string $id): string
    {
        if ($id === null) {
            return '—';
        }

        foreach ($this->tree() as $entry) {
            if ($entry['id'] === $id) {
                return (string) $entry['name'];
            }
        }

        return $id;
    }
}
