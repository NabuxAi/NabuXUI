{{--
    /admin/invoice — the sample invoices stack, the chosen document beside it
    and a print button. Selecting a row (wire:click="select") re-renders the
    document; the block's own @media print rules turn the document into the
    plain paper sheet and this page's style block hides the shell around it,
    so "print" gives you the invoice and nothing else.
--}}
<x-admin.page active="invoice" :title="__('admin.invoice_title')" :subtitle="__('admin.invoice_subtitle')">
    <style>
        /* The invoices stack: number + status on one line, client + total below. */
        .ap-invoice-index { gap: var(--nx-space-2); }
        .ap-invoice-row {
            display: grid; grid-template-columns: minmax(0, 1fr) auto; align-items: center; column-gap: var(--nx-space-3);
            padding: var(--nx-space-3) var(--nx-space-4); border: 1px solid var(--nx-border);
            border-radius: var(--nx-radius-xl); background: var(--nx-surface-2);
            font: inherit; color: inherit; text-align: start; cursor: pointer;
            transition-property: translate, scale, border-color, background-color;
            transition-duration: var(--nx-dur-fast); transition-timing-function: var(--nx-ease-out);
        }
        .ap-invoice-row > .nx-badge { justify-self: end; }
        .ap-invoice-row[aria-current="true"] { border-color: var(--nx-accent-border); background: var(--nx-accent-soft); }
        .ap-invoice-no { font: 500 var(--nx-text-sm) / 1.4 var(--nx-font-mono); letter-spacing: var(--nx-tracking-tight); color: var(--nx-text); }
        .ap-invoice-party { color: var(--nx-text-muted); }
        .ap-invoice-amount { justify-self: end; font-weight: 600; color: var(--nx-text); }
        .ap-invoice-row:focus-visible { outline: 2px solid var(--nx-ring); outline-offset: 2px; }
        .ap-invoice-row:active { scale: calc(1 - 0.04 * var(--nx-motion)); }
        @media (hover: hover) and (pointer: fine) {
            .ap-invoice-row:hover { translate: 0 calc(-2px * var(--nx-motion)); border-color: var(--nx-accent-border); }
        }

        /* Print: the invoice block is already the paper document — hide the shell. */
        @media print {
            body { background: none; }
            .ap-page { display: block; min-height: auto; padding: 0; }
            .nx-admin, .nx-admin-frame, .nx-admin-main { block-size: auto; min-block-size: 0; }
            .nx-admin-content { overflow: visible; block-size: auto; }
            .nx-admin-sidebar, .nx-admin-topbar, .nx-admin-drawer,
            .nx-toast-list, .ap-invoice-actions, .ap-invoice-index { display: none !important; }
            .ap-invoice-duo { display: block; }
            .nx-invoice { box-shadow: none; }
        }
    </style>

    <div class="ap-grid">
        <div class="ap-row ap-invoice-actions">
            <x-nx::button variant="primary" icon="plus" wire:click="createInvoice">{{ __('admin.invoice_new') }}</x-nx::button>
            <x-nx::button variant="secondary" icon="file" x-on:click="window.print()">{{ __('admin.invoice_print') }}</x-nx::button>
        </div>

        <div class="ap-duo ap-invoice-duo">
            <section class="ap-box ap-invoice-index" aria-label="{{ __('admin.invoice_title') }}">
                @foreach ($invoices as $invoice)
                    <button type="button" class="ap-invoice-row" wire:click="select('{{ $invoice['id'] }}')"
                        wire:key="ap-invoice-row-{{ $invoice['id'] }}"
                        aria-current="{{ $invoice['id'] === $current['id'] ? 'true' : 'false' }}">
                        <span class="ap-invoice-no">{{ $invoice['number'] }}</span>
                        <x-nx::badge :tone="$statusTones[$invoice['status']]">{{ $statusLabels[$invoice['status']] }}</x-nx::badge>
                        <span class="ap-invoice-party">{{ $invoice['to']['name'] }}</span>
                        <span class="ap-invoice-amount">{{ \NabuXUI\NabuXUI::formatNumber($invoice['total'], 0, app()->getLocale()) }}</span>
                    </button>
                @endforeach
            </section>

            <x-nx::invoice :number="$current['number']" :status="$current['status']"
                :brand="__('admin.invoice_from_name')" brand-tagline="{{ __('admin.panel') }}"
                :issue-date="$current['issueLabel']" :due-date="$current['dueLabel']"
                :from="$current['from']" :to="$current['to']" :lines="$current['lines']"
                :extra-totals="[['label' => __('admin.invoice_tax'), 'percent' => 0.09]]"
                :currency="__('admin.invoice_currency')" :note="__('admin.invoice_note')" />
        </div>
    </div>
</x-admin.page>
