<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AdminPanel;
use App\Support\Locales;
use Livewire\Attributes\Layout;
use Livewire\Component;
use NabuXUI\NabuXUI;

/**
 * /admin/users — the member directory: an avatar-group summary plus invite,
 * live search over name/role/email, role chips, server-side sorting driven
 * by the data-table block's headers, and row selection over checkboxes with
 * a remove-confirmation dialog. Panel words come from admin.* keys.
 */
#[Layout('layouts.admin')]
class Users extends Component
{
    use AdminPanel;

    /** Free-text search over name, role and email (live, debounced in the view). */
    public string $search = '';

    /** The role chips' value: 'all' | 'admin' | 'editor' | 'viewer'. */
    public string $role = 'all';

    /** The data-table's sort state; its headers call sortBy(key, direction). */
    public array $sort = ['key' => 'name', 'direction' => 'ascending'];

    /** The selected members' ids — the rows' checkboxes bind here as an array. */
    public array $selected = [];

    /** The remove dialog (x-nx::dialog wire:model). */
    public bool $confirming = false;

    /** The workspace's members (sample data; ids stay stable across removes). */
    public array $members = [];

    /**
     * The shared trait's updated() hook types its value ?string, which our
     * array props (selected) crash against — same behaviour for the locale
     * switch, but through a wider door. (Class methods win over the trait's.)
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

        $this->members = [
            ['id' => '1', 'name' => __('admin.user_name'), 'email' => 'hussein@nabu.studio', 'role' => 'admin', 'orders' => 486, 'active' => now()->subMinutes(4)],
            ['id' => '2', 'name' => __('admin.users_sample_1'), 'email' => 'maryam@nabu.studio', 'role' => 'editor', 'orders' => 312, 'active' => now()->subHours(2)],
            ['id' => '3', 'name' => __('admin.users_sample_2'), 'email' => 'saman@nabu.studio', 'role' => 'editor', 'orders' => 268, 'active' => now()->subHours(26)],
            ['id' => '4', 'name' => __('admin.users_sample_3'), 'email' => 'negar@nabu.studio', 'role' => 'viewer', 'orders' => 94, 'active' => now()->subDays(3)],
            ['id' => '5', 'name' => __('admin.users_sample_4'), 'email' => 'amir@nabu.studio', 'role' => 'viewer', 'orders' => 51, 'active' => now()->subDays(9)],
        ];
    }

    /** The data-table block's headers land here (server-side sorting, FLIP after the morph). */
    public function sortBy(string $key, string $direction): void
    {
        if (! in_array($key, ['name', 'role', 'orders', 'active'], true)) {
            return;
        }

        $this->sort = ['key' => $key, 'direction' => $direction === 'descending' ? 'descending' : 'ascending'];
    }

    /** The header checkbox: every visible row in, or everything out. */
    public function toggleAll(): void
    {
        $visible = array_map(fn (array $member) => (string) $member['id'], $this->visible());

        $this->selected = $visible !== [] && array_diff($visible, $this->selected) === [] ? [] : $visible;
    }

    /** The dialog's confirm: drop the selected members and their access. */
    public function removeSelected(): void
    {
        $gone = count($this->selected);

        $this->members = array_values(array_filter(
            $this->members,
            fn (array $member) => ! in_array((string) $member['id'], $this->selected, true),
        ));
        $this->selected = [];
        $this->confirming = false;

        if ($gone > 0) {
            $this->toast(__('admin.users_removed_toast'), tone: 'success');
        }
    }

    /** The header's invite button. */
    public function invite(): void
    {
        $this->toast(__('admin.welcome_invite_toast'), tone: 'success');
    }

    /** Members under the chips + the search box, before sorting. */
    private function visible(): array
    {
        $q = mb_strtolower(trim($this->search));
        $roles = $this->roleLabels();

        return array_values(array_filter($this->members, function (array $member) use ($q, $roles) {
            if ($this->role !== 'all' && $member['role'] !== $this->role) {
                return false;
            }
            if ($q === '') {
                return true;
            }

            return str_contains(mb_strtolower($member['name'].' '.$roles[$member['role']].' '.$member['email']), $q);
        }));
    }

    /** @return array<string, string> role id → label, in weight order. */
    private function roleLabels(): array
    {
        return [
            'admin' => __('admin.users_role_admin'),
            'editor' => __('admin.users_role_editor'),
            'viewer' => __('admin.users_role_viewer'),
        ];
    }

    public function render()
    {
        $locale = str_replace('_', '-', app()->getLocale());
        $fmt = fn (int $n) => NabuXUI::formatNumber($n, 0, $locale);
        // Carbon's diffForHumans() keeps its counter in latin digits; map them
        // into the locale's numbering system (identity for latin locales).
        $digits = array_combine(range(0, 9), NabuXUI::digits($locale));
        $roles = $this->roleLabels();
        $tones = ['admin' => 'accent', 'editor' => 'info', 'viewer' => null];
        $weight = ['admin' => 0, 'editor' => 1, 'viewer' => 2];

        // Filter, then apply the block's sort server-side so rows glide after the morph.
        $rows = $this->visible();
        $key = in_array($this->sort['key'], ['name', 'role', 'orders', 'active'], true) ? $this->sort['key'] : 'name';
        $down = $this->sort['direction'] === 'descending';
        usort($rows, function (array $a, array $b) use ($key, $down, $weight) {
            $value = match ($key) {
                'role' => fn (array $m) => $weight[$m['role']],
                'orders' => fn (array $m) => (int) $m['orders'],
                'active' => fn (array $m) => (int) $m['active']->getTimestamp(),
                default => fn (array $m) => mb_strtolower((string) $m['name']),
            };

            return ($value($a) <=> $value($b)) * ($down ? -1 : 1);
        });

        $display = [];
        foreach (array_values($rows) as $i => $member) {
            $display[] = [
                'id' => (string) $member['id'],
                'name' => (string) $member['name'],
                'email' => (string) $member['email'],
                'roleLabel' => $roles[$member['role']],
                'roleTone' => $tones[$member['role']],
                'roleSort' => (string) $weight[$member['role']],
                'orders' => $fmt((int) $member['orders']),
                'ordersSort' => (string) $member['orders'],
                'active' => strtr($member['active']->locale(substr($locale, 0, 2))->diffForHumans(), $digits),
                'activeSort' => $member['active']->format('U'),
                'index' => $i,
            ];
        }

        $people = array_map(fn (array $member) => ['name' => $member['name']], $this->members);
        $selectedPeople = array_values(array_map(
            fn (array $member) => ['name' => $member['name']],
            array_filter($this->members, fn (array $member) => in_array((string) $member['id'], $this->selected, true)),
        ));

        $picked = count($selectedPeople);
        $removeText = $picked === 1
            ? __('admin.users_remove_one', ['name' => $selectedPeople[0]['name']])
            : __('admin.users_remove_many', ['count' => $fmt($picked)]);

        $byRole = ['admin' => 0, 'editor' => 0, 'viewer' => 0];
        foreach ($this->members as $member) {
            $byRole[$member['role']]++;
        }

        $visibleIds = array_map(fn (array $member) => (string) $member['id'], $this->visible());

        return view('livewire.admin.users', [
            'rows' => $display,
            'columns' => [
                ['key' => 'name', 'label' => __('admin.users_column_member'), 'sortable' => true],
                ['key' => 'role', 'label' => __('admin.profile_field_role'), 'sortable' => true],
                ['key' => 'orders', 'label' => __('admin.users_column_orders'), 'sortable' => true, 'align' => 'end', 'numeric' => true],
                ['key' => 'active', 'label' => __('admin.users_column_active'), 'sortable' => true],
            ],
            'sort' => ['key' => $key, 'direction' => $down ? 'descending' : 'ascending'],
            'chips' => ['all' => __('admin.users_filter_all')] + $roles,
            'counts' => ['all' => $fmt(count($this->members)), 'admin' => $fmt($byRole['admin']), 'editor' => $fmt($byRole['editor']), 'viewer' => $fmt($byRole['viewer'])],
            'people' => $people,
            'selectedPeople' => $selectedPeople,
            'selectedCount' => $fmt($picked),
            'removeText' => $removeText,
            'memberCount' => $fmt(count($this->members)),
            'allVisibleSelected' => $visibleIds !== [] && array_diff($visibleIds, $this->selected) === [],
            'locale' => $locale,
        ]);
    }
}
