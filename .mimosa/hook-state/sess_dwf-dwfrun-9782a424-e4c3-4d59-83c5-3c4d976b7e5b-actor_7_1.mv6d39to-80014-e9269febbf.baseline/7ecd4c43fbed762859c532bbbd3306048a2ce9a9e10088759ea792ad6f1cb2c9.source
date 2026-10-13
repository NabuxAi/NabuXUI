{{--
    /filament/task-boards — the live kanban on the Task model, through the
    page App\Filament\Pages\TaskBoard. The board itself is <x-nx::kanban>:
    move-action / add-action name the page's own Livewire methods, so a drag
    (or a «انتقال به …» menu pick) flips the record's status in the database
    and the quick-add composer creates a Task — the component calls them
    after its optimistic move and the re-render confirms it. The columns
    come fresh from the database on every render ($this->boardColumns());
    the component keeps stable wire:keys from the card ids, so the morph
    glides instead of tearing the board down. Column counters are the
    component's own rolling digits, fed locale="fa" so the board speaks
    Persian with Persian digits (its own strings come from nabuxui::ui.fa).
    Priority rides each card's tone dot — بالا/متوسط/کم as
    danger/warning/info — and the meta line names it in words plus the due
    day (فردا، دو روز دیگر، …), which is as far as the nx card schema goes
    (title/meta/tone/assignee/actions — no badge slot).
--}}
<x-filament-panels::page>
    <section class="nx-taskboard">
        <header class="nx-taskboard-head">
            <div class="nx-taskboard-copy">
                <p class="nx-taskboard-title">کارها روی یک برد</p>
                <p class="nx-taskboard-hint">
                    کارت را بین ستون‌ها بکشید یا از منوی سه‌نقطهٔ آن «انتقال به …» را بزنید؛
                    وضعیت تسک همان لحظه در دیتابیس ثبت می‌شود. با «افزودن کارت» هم می‌توانید
                    تسک تازه بسازید. رنگ نقطهٔ هر کارت اولویت آن است.
                </p>
            </div>
            <div class="nx-taskboard-legend" aria-label="راهنمای اولویت">
                <x-nx::badge tone="danger" dot>اولویت بالا</x-nx::badge>
                <x-nx::badge tone="warning" dot>اولویت متوسط</x-nx::badge>
                <x-nx::badge tone="info" dot>اولویت کم</x-nx::badge>
            </div>
        </header>

        <x-nx::kanban
            :columns="$this->boardColumns()"
            move-action="moveCard"
            add-action="addCard"
            height="36rem"
            label="برد تسک‌ها"
            locale="fa"
        />
    </section>

    <style>
    /* The board band: a short header over the kanban, both on nx tokens. */
    .nx-taskboard { display: grid; gap: var(--nx-space-4); }
    .nx-taskboard-head {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        justify-content: space-between;
        gap: var(--nx-space-3);
    }
    .nx-taskboard-copy { display: grid; gap: var(--nx-space-1); }
    .nx-taskboard-title {
        margin: 0;
        font: 700 var(--nx-text-2xl) / 1.3 var(--nx-font-display);
        letter-spacing: var(--nx-tracking-tighter);
        color: var(--nx-text);
    }
    .nx-taskboard-hint { margin: 0; max-inline-size: 46rem; color: var(--nx-text-muted); }
    .nx-taskboard-legend { display: flex; flex-wrap: wrap; gap: var(--nx-space-2); }
    </style>
</x-filament-panels::page>
