<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AdminPanel;
use App\Support\Locales;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use NabuXUI\NabuXUI;

/**
 * /admin/files — the workspace's media library on the nx-file-manager block:
 * the folder tree with its breadcrumbs, live search and grid/list views,
 * type chips that filter server-side (the block re-applies after the morph),
 * and real uploads through Livewire — both the manager's upload button and
 * the file-drop below feed the same property, and the toast lands when the
 * round-trip finishes. The side rail carries the storage gauge (6.4 of the
 * plan's 10 GB) and the library's count. Words come from admin.files_*.
 */
#[Layout('layouts.admin')]
class Files extends Component
{
    use AdminPanel;
    use WithFileUploads;

    /** The type chips' value: 'all' | 'images' | 'docs' | 'videos' | 'archives'. */
    public string $type = 'all';

    /** The upload round-trip's landing spot (both the manager and the drop bind here). */
    public array $uploads = [];

    /**
     * The shared trait's updated() hook types its value ?string, which the
     * uploads array crashes against — same behaviour for the locale switch,
     * but through a wider door. (Class methods win over the trait's.)
     */
    public function updated(string $name, mixed $value): void
    {
        if ($name === 'locale' && is_string($value) && in_array($value, Locales::codes(), true)) {
            session(['locale' => $value]);
            $this->redirect($this->path);
        }
    }

    public function mount(): void
    {
        $this->rememberLocale();
    }

    /** The uploads' property hook: the files landed, the toast says so, the list resets. */
    public function updatedUploads(): void
    {
        if ($this->uploads !== []) {
            $this->toast(__('admin.files_uploaded_toast'), tone: 'success');
            $this->reset('uploads');
        }
    }

    public function render()
    {
        $locale = str_replace('_', '-', app()->getLocale());
        $fmt = fn ($value) => NabuXUI::formatNumber((float) $value, 0, $locale);

        $entries = $this->library();

        // The type chips: folders always stay (so the tree keeps walking);
        // files stay when their type matches. Counts come off the full shelf.
        $typeKeys = ['images', 'docs', 'videos', 'archives'];
        $counts = ['all' => count($entries)];
        foreach ($typeKeys as $key) {
            $counts[$key] = count(array_filter($entries, fn (array $entry) => ($entry['filter'] ?? null) === $key));
        }

        $items = array_values(array_filter($entries, function (array $entry) {
            return $entry['kind'] === 'folder' || $this->type === 'all' || ($entry['filter'] ?? null) === $this->type;
        }));

        return view('livewire.admin.files', [
            'items' => $items,
            'chips' => [
                'all' => __('admin.files_filter_all'),
                'images' => __('admin.files_type_images'),
                'docs' => __('admin.files_type_docs'),
                'videos' => __('admin.files_type_videos'),
                'archives' => __('admin.files_type_archives'),
            ],
            'counts' => array_map(fn ($count) => $fmt($count), $counts),
            'countText' => str_replace(':count', $fmt(count($entries)), __('admin.files_count')),
            'locale' => $locale,
        ]);
    }

    /**
     * The library: three folders from files_folder_* with the five files of
     * files_file_* nested where they belong, sizes in bytes (the block
     * formats them) and a 4/3 SVG preview for the two artwork pieces.
     *
     * @return array<int, array<string, mixed>>
     */
    private function library(): array
    {
        $shot = fn (string $body) => 'data:image/svg+xml,'.rawurlencode(
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 300">'.$body.'</svg>'
        );
        $poster = $shot('<rect width="400" height="300" fill="#eef0ff"/><circle cx="200" cy="128" r="72" fill="#4f46e5" opacity="0.85"/><path d="M96 236 h208" stroke="#4f46e5" stroke-width="14" stroke-linecap="round"/>');
        $logo = $shot('<rect width="400" height="300" fill="#fbf0d9"/><path d="M132 208 L200 96 L268 208 Z" fill="none" stroke="#b45309" stroke-width="16" stroke-linejoin="round"/><circle cx="200" cy="180" r="12" fill="#b45309"/>');

        return [
            ['id' => 'f1', 'name' => __('admin.files_folder_1'), 'kind' => 'folder', 'items' => 1, 'modified' => now()->subDays(2)],
            ['id' => 'f2', 'name' => __('admin.files_folder_2'), 'kind' => 'folder', 'items' => 2, 'modified' => now()->subDays(6)],
            ['id' => 'f3', 'name' => __('admin.files_folder_3'), 'kind' => 'folder', 'items' => 1, 'modified' => now()->subWeeks(2)],
            ['id' => 'logo', 'name' => __('admin.files_file_4'), 'parent' => null, 'kind' => 'image',
                'size' => 18432, 'modified' => now()->subDays(9), 'filter' => 'images', 'preview' => $logo],
            ['id' => 'poster', 'name' => __('admin.files_file_1'), 'parent' => 'f1', 'kind' => 'image',
                'size' => 2411724, 'modified' => now()->subDays(2), 'filter' => 'images', 'preview' => $poster],
            ['id' => 'catalogue', 'name' => __('admin.files_file_2'), 'parent' => 'f2', 'kind' => 'file',
                'size' => 3984588, 'modified' => now()->subDays(6), 'filter' => 'docs'],
            ['id' => 'archive', 'name' => __('admin.files_file_5'), 'parent' => 'f2', 'kind' => 'file',
                'size' => 100663296, 'modified' => now()->subDays(12), 'filter' => 'archives'],
            ['id' => 'demo', 'name' => __('admin.files_file_3'), 'parent' => 'f3', 'kind' => 'video',
                'size' => 154763264, 'modified' => now()->subWeeks(2), 'filter' => 'videos'],
        ];
    }
}
