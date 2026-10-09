<?php

namespace App\Livewire\Admin;

use App\Livewire\Concerns\AdminPanel;
use App\Support\Locales;
use Livewire\Attributes\Layout;
use Livewire\Component;
use NabuXUI\NabuXUI;

/**
 * /admin/roles — the permission matrix: one row per permission (grouped by
 * section), one column per role, a switch at every crossing. The switches are
 * live (roles_note: a grant lands on every member of the role at once) and
 * the toolbar's Save acknowledges the pending set with a toast — it only
 * wakes once a crossing leaves its default. Words come from admin.roles_* +
 * the existing users_role_* role names; digits roll with the locale.
 */
#[Layout('layouts.admin')]
class Roles extends Component
{
    use AdminPanel;

    /** role id → permission id → granted. The switches bind matrix.<role>.<perm> (live). */
    public array $matrix = [];

    public function mount(): void
    {
        $this->rememberLocale();
        $this->matrix = $this->defaults();
    }

    /**
     * The shared trait's updated() hook types its value ?string, which our
     * bool matrix entries crash against — same behaviour for the locale
     * switch, but through a wider door. (Class methods win over the trait's.)
     */
    public function updated(string $name, mixed $value): void
    {
        if ($name === 'locale' && is_string($value) && in_array($value, Locales::codes(), true)) {
            session(['locale' => $value]);
            $this->redirect($this->path);
        }
    }

    /** The toolbar's button: the live matrix already holds the grants; this acknowledges them. */
    public function save(): void
    {
        $this->toast(__('admin.roles_saved_toast'), tone: 'success');
    }

    public function render()
    {
        $locale = str_replace('_', '-', app()->getLocale());
        $fmt = fn (int $n) => NabuXUI::formatNumber($n, 0, $locale);

        $roles = [
            ['id' => 'admin', 'label' => __('admin.users_role_admin'), 'tone' => 'accent'],
            ['id' => 'editor', 'label' => __('admin.users_role_editor'), 'tone' => 'info'],
            ['id' => 'viewer', 'label' => __('admin.users_role_viewer'), 'tone' => null],
        ];

        // The untouched set, rebuilt deterministically every request, drives the dirty flag.
        $defaults = $this->defaults();
        $changed = 0;
        foreach ($defaults as $role => $grants) {
            foreach ($grants as $perm => $on) {
                $changed += (bool) ($this->matrix[$role][$perm] ?? false) !== (bool) $on ? 1 : 0;
            }
        }

        // Sections → permissions, in nav order; each crossing carries its own
        // switch label ("grant :permission to :role") for screen readers.
        $groups = [];
        foreach ($this->groups() as $group) {
            $perms = [];
            foreach ($group['perms'] as $perm) {
                $label = __('admin.roles_perm_'.$perm);
                $perms[] = [
                    'id' => $group['id'].'_'.$perm,
                    'label' => $label,
                    'grants' => array_combine(
                        array_column($roles, 'id'),
                        array_map(
                            fn (array $role) => __('admin.roles_grant', [
                                'permission' => $group['label'].' · '.$label,
                                'role' => $role['label'],
                            ]),
                            $roles,
                        ),
                    ),
                ];
            }
            $groups[] = ['id' => $group['id'], 'label' => $group['label'], 'perms' => $perms];
        }

        return view('livewire.admin.roles', [
            'roles' => $roles,
            'groups' => $groups,
            'roleCount' => $fmt(count($roles)),
            'dirty' => $changed > 0,
            'locale' => $locale,
        ]);
    }

    /** @return array<int, array{id: string, label: string, perms: array<int, string>}> */
    private function groups(): array
    {
        return [
            ['id' => 'products', 'label' => __('admin.roles_group_products'), 'perms' => ['view', 'create', 'edit', 'delete']],
            ['id' => 'orders', 'label' => __('admin.roles_group_orders'), 'perms' => ['view', 'create', 'edit', 'delete']],
            ['id' => 'users', 'label' => __('admin.roles_group_users'), 'perms' => ['view', 'create', 'edit', 'delete']],
            ['id' => 'settings', 'label' => __('admin.roles_group_settings'), 'perms' => ['view', 'edit']],
            ['id' => 'reports', 'label' => __('admin.roles_group_reports'), 'perms' => ['view']],
        ];
    }

    /** @return array<string, array<string, bool>> role id → permission id → default grant. */
    private function defaults(): array
    {
        $matrix = ['admin' => [], 'editor' => [], 'viewer' => []];

        foreach ($this->groups() as $group) {
            foreach ($group['perms'] as $perm) {
                $id = $group['id'].'_'.$perm;
                // Admin holds everything; editors shape the catalogue (never
                // delete, never settings or people); viewers only read.
                $matrix['admin'][$id] = true;
                $matrix['editor'][$id] = in_array($group['id'], ['products', 'orders'], true) && $perm !== 'delete';
                $matrix['viewer'][$id] = $perm === 'view';
            }
        }

        return $matrix;
    }
}
