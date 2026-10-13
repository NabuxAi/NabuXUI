{{-- Livewire demos: Buttons, loaders & micro-interactions. --}}
@php
    $interests = [
        ['value' => 'design', 'label' => 'Design · Diseño', 'emoji' => '🎨'],
        ['value' => 'food', 'label' => 'Food · 料理', 'emoji' => '🍜'],
        ['value' => 'music', 'label' => 'Music · موسيقى', 'emoji' => '🎵'],
        ['value' => 'books', 'label' => 'Books · Livres', 'emoji' => '📚'],
        ['value' => 'travel', 'label' => 'Travel · Viajes', 'emoji' => '✈️'],
        ['value' => 'running', 'label' => 'Running · Laufen', 'emoji' => '🏃'],
        ['value' => 'games', 'label' => 'Games · ゲーム', 'emoji' => '🎮'],
        ['value' => 'photo', 'label' => 'Photography · Fotografía', 'emoji' => '📷'],
        ['value' => 'plants', 'label' => 'Plants · 植物', 'emoji' => '🌱'],
        ['value' => 'yoga', 'label' => 'Yoga · योग', 'emoji' => '🧘'],
        ['value' => 'film', 'label' => 'Film · Cinéma', 'emoji' => '🎬'],
        ['value' => 'coffee', 'label' => 'Coffee · Kahve', 'emoji' => '☕'],
        ['value' => 'science', 'label' => 'Science · 과학', 'emoji' => '🧪'],
        ['value' => 'basketball', 'label' => 'Basketball · Basquete', 'emoji' => '🏀'],
        ['value' => 'theatre', 'label' => 'Theatre · Tiyatro', 'emoji' => '🎭'],
        ['value' => 'code', 'label' => 'Code · コード', 'emoji' => '💻'],
        ['value' => 'languages', 'label' => 'Languages · Idiomas', 'emoji' => '🌍'],
        ['value' => 'podcasts', 'label' => 'Podcasts · 播客', 'emoji' => '🎧'],
        ['value' => 'crafts', 'label' => 'Crafts · Handwerk', 'emoji' => '🧵'],
        ['value' => 'cycling', 'label' => 'Cycling · 骑行', 'emoji' => '🚲'],
        ['value' => 'history', 'label' => 'History · تاریخ', 'emoji' => '🏛️'],
    ];
@endphp

<section class="pg-box">
    <h2 class="pg-title">Flip button · perspective reveal</h2>
    <p style="margin:0;color:var(--nx-text-muted)">Hover, or Tab to it: each part rolls like the face of a cube.</p>
    <div class="pg-row">
        <x-nx::flip-button label="Get started" hover-label="Let's go" />
        <x-nx::flip-button variant="secondary" label="Book a demo" hover-label="Pick a time" icon="message" hover-icon="zap" wire:click="ping('Demo booked · デモを予約しました')" />
        <x-nx::flip-button variant="inverse" size="lg" label="Commencer" hover-label="C’est parti" href="#actions-transaction" />
    </div>
    <div class="pg-row" dir="rtl" lang="fa">
        <x-nx::flip-button label="شروع کنید" hover-label="بزن بریم" />
        <x-nx::flip-button variant="secondary" size="sm" label="ابدأ الآن" hover-label="هيا بنا" />
    </div>
</section>

<div class="pg-grid">
    <section class="pg-box">
        <h2 class="pg-title">Blob button</h2>
        <p style="margin:0;color:var(--nx-text-muted)">The blobs drift, then gather at the pointer.</p>
        <div class="pg-row">
            <x-nx::blob-button icon="sparkles" wire:click="ping('Hola · Bonjour · مرحبا')">Talk to Nabu</x-nx::blob-button>
            <x-nx::blob-button size="lg" icon-end="arrow-right" href="#actions-interests">Hablar con Nabu</x-nx::blob-button>
            <x-nx::blob-button size="sm">ナブと話す</x-nx::blob-button>
        </div>
    </section>

    <section class="pg-box">
        <h2 class="pg-title">Border button</h2>
        <p style="margin:0;color:var(--nx-text-muted)">wire:loading spins the dashes; the status closes them.</p>
        <div class="pg-row">
            <x-nx::border-button icon="file" wire:click="ping('Draft saved · Borrador guardado')">Save draft</x-nx::border-button>
            <div x-data="{ status: null, fail: false }">
                <x-nx::border-button success-label="Saved · Gespeichert" error-label="Try again · 다시 시도" wire:ignore.self
                    x-bind:data-status="status" x-bind:aria-busy="status === 'loading' ? 'true' : null"
                    x-on:click="if (status) return; status = 'loading'; setTimeout(() => { status = fail ? 'error' : 'success'; fail = ! fail }, 1400); setTimeout(() => status = null, 3200)">
                    Publish
                </x-nx::border-button>
            </div>
            <x-nx::border-button :status="$state['border'] ?? null" success-label="Synced · 同期済み">From the server</x-nx::border-button>
        </div>
        <div class="pg-row">
            <x-nx::button size="sm" variant="ghost" wire:click="$set('state.border', 'success')">Server: success</x-nx::button>
            <x-nx::button size="sm" variant="ghost" wire:click="$set('state.border', 'error')">Server: error</x-nx::button>
            <x-nx::button size="sm" variant="ghost" wire:click="$set('state.border', null)">Reset</x-nx::button>
        </div>
    </section>
</div>

<section class="pg-box" id="actions-transaction">
    <h2 class="pg-title">Transaction in one button</h2>
    <p style="margin:0;color:var(--nx-text-muted)">Pay → Processing (pixels ripple, the width follows the words) → Paid. Every other press is declined.</p>
    <div class="pg-row">
        <div x-data="{ fail: false }">
            <x-nx::transaction-button :labels="['idle' => 'Pay $49', 'loading' => 'Processing…', 'success' => 'Paid · 支払い済み', 'error' => 'Declined · Rechazado']" wire:ignore.self
                x-on:click="if (current !== 'idle') return; set('loading'); setTimeout(() => { set(fail ? 'error' : 'success'); fail = ! fail }, 1800); setTimeout(() => set('idle'), 4200)" />
        </div>
        <x-nx::transaction-button size="sm" wire:click="ping('Paid with wire:loading')" :labels="['idle' => 'Pay with Livewire', 'loading' => 'Talking to the server…']" />
    </div>
    <div class="pg-row">
        <x-nx::transaction-button :status="$state['pay'] ?? null" :labels="['idle' => 'Checkout · Finalizar', 'loading' => 'Verifying…', 'success' => 'Order placed', 'error' => 'Card declined']" />
        <x-nx::button size="sm" variant="ghost" wire:click="$set('state.pay', 'success')">Server: paid</x-nx::button>
        <x-nx::button size="sm" variant="ghost" wire:click="$set('state.pay', 'error')">Server: declined</x-nx::button>
        <x-nx::button size="sm" variant="ghost" wire:click="$set('state.pay', null)">Reset</x-nx::button>
    </div>
    <div class="pg-row" dir="rtl" lang="ar">
        <div x-data>
            <x-nx::transaction-button :labels="['idle' => 'ادفع ٤٩ دولارًا', 'loading' => 'جارٍ المعالجة…', 'success' => 'تم الدفع']" wire:ignore.self
                x-on:click="if (current !== 'idle') return; set('loading'); setTimeout(() => set('success'), 1800); setTimeout(() => set('idle'), 4000)" />
        </div>
    </div>
</section>

<div class="pg-grid">
    <section class="pg-box">
        <h2 class="pg-title">Fill button</h2>
        <p style="margin:0;color:var(--nx-text-muted)">Enter from any side: the fill grows from there and leaves the way you go.</p>
        <div class="pg-row">
            <x-nx::fill-button icon-end="arrow-right">Explore · Explorar</x-nx::fill-button>
            <x-nx::fill-button variant="inverse" hover-label="订阅 · Subscribe">Subscribe</x-nx::fill-button>
            <x-nx::fill-button variant="gold" shape="rounded" hover-label="Kostenlos starten" wire:click="ping('Welcome · Willkommen')">Start free</x-nx::fill-button>
        </div>
    </section>

    <section class="pg-box">
        <h2 class="pg-title">Metal button</h2>
        <p style="margin:0;color:var(--nx-text-muted)">A liquid-metal toggle. The first one is bound with wire:model.live.</p>
        <div class="pg-row">
            <x-nx::metal-button label="Voice input" wire:model.live="state.mic" :pressed="(bool) ($state['mic'] ?? false)" />
            <x-nx::metal-button label="Listen · Escuchar" icon="play" shape="pill" />
            <x-nx::metal-button label="Record" size="lg" :pressed="true" />
            <x-nx::metal-button label="Mute" icon="bell" active-icon="x" size="sm" />
        </div>
        <p style="margin:0">Mic (Livewire): <code>{{ ($state['mic'] ?? false) ? 'on' : 'off' }}</code></p>
    </section>
</div>

<section class="pg-box">
    <h2 class="pg-title">Ripples · toast and like</h2>
    <p style="margin:0;color:var(--nx-text-muted)">A toaster with <code>data-effect="ripple"</code> sends a soft ring out of each new toast's icon. The like button rings behind the heart.</p>
    <div class="pg-row" x-data="{ n: 0 }" style="align-items:flex-start">
        <ol class="nx-toast-list" data-effect="ripple" style="inline-size:min(24rem,100%)">
            <template x-for="i in [n]" x-bind:key="i">
                <li class="nx-toast" data-tone="success" data-state="open" data-front>
                    <span class="nx-toast-icon">{{ \NabuXUI\NabuXUI::icon('check-circle') }}</span>
                    <div class="nx-toast-body">
                        <p class="nx-toast-title">Saved · 保存しました</p>
                        <p class="nx-toast-description">Your draft is safe · Tu borrador está a salvo.</p>
                    </div>
                </li>
            </template>
        </ol>
        <x-nx::button size="sm" icon="bell" x-on:click="n++">Replay</x-nx::button>
        <x-nx::like-button :count="128" />
    </div>
</section>

<section class="pg-box" id="actions-interests" x-data x-init="Array.isArray($wire.get('state.interests')) || $wire.set('state.interests', ['design', 'food'], false)">
    <h2 class="pg-title">Interests picker</h2>
    <p style="margin:0;color:var(--nx-text-muted)">Drag the rows sideways. Picks throw their emoji; Clear shakes them off. Bound with wire:model.live.</p>
    <x-nx::interests-picker label="What are you into? · ¿Qué te gusta?" wire:model.live="state.interests" :value="$state['interests'] ?? ['design', 'food']" :options="$interests" />
    <p style="margin:0">Picked (Livewire): <code>{{ json_encode($state['interests'] ?? [], JSON_UNESCAPED_UNICODE) }}</code></p>
</section>

<div class="pg-grid">
    <section class="pg-box">
        <h2 class="pg-title">Label creator</h2>
        <p style="margin:0;color:var(--nx-text-muted)">Type a new name, pick a colour, press Enter. Bound with wire:model.live.</p>
        <x-nx::label-creator wire:model.live="state.labels" :labels="$state['labels'] ?? []" x-on:nx-label-created="$wire.ping('Label created · ' + $event.detail.name)" />
        <p style="margin:0">Labels (Livewire): <code>{{ json_encode($state['labels'] ?? [], JSON_UNESCAPED_UNICODE) }}</code></p>
    </section>

    <section class="pg-box">
        <h2 class="pg-title">Label creator · plain form</h2>
        <p style="margin:0;color:var(--nx-text-muted)">Without wire:model: the labels post as JSON under a name.</p>
        <x-nx::label-creator name="labels" placeholder="Etiqueta nueva…" create-text="Crear “:name”" color-label="Color"
            :labels="[['name' => 'Bug', 'color' => 'pink'], ['name' => 'Design · Diseño', 'color' => 'indigo'], ['name' => '緊急 · Urgent', 'color' => 'orange']]" />
    </section>
</div>

<div class="pg-grid">
    <section class="pg-box">
        <h2 class="pg-title">Pixel loaders</h2>
        <div class="pg-row" style="gap:2rem">
            <x-nx::pixel-loader label="Thinking · Pensando" />
            <x-nx::pixel-loader variant="center" rows="7" cols="7" size="sm" />
            <x-nx::pixel-loader variant="chaos" rows="4" cols="8" size="lg" />
        </div>
        <div style="display:grid;gap:.75rem;padding:1rem;border:1px solid var(--nx-border);border-radius:var(--nx-radius-lg)">
            <div style="display:flex;justify-content:space-between;font-size:var(--nx-text-sm);font-weight:600"><span>Orders · Pedidos</span><span style="color:var(--nx-text-subtle)">Loading…</span></div>
            <x-nx::pixel-loader variant="chaos" rows="3" cols="24" size="sm" label="Loading orders · Cargando pedidos" />
            <x-nx::pixel-loader variant="chaos" rows="3" cols="24" size="sm" label="Loading orders" style="opacity:.7" />
        </div>
    </section>

    <section class="pg-box">
        <h2 class="pg-title">Parametric loaders</h2>
        <div class="pg-row" style="gap:2rem">
            <x-nx::parametric-loader size="lg" label="Rose · Rosa" />
            <x-nx::parametric-loader kind="spiro" size="lg" label="Spirograph" />
            <x-nx::parametric-loader kind="lissajous" size="lg" label="Lissajous" />
        </div>
        <div class="pg-row" style="gap:2rem">
            <x-nx::parametric-loader kind="rose" :options="['n' => 7, 'd' => 3]" />
            <x-nx::parametric-loader kind="spiro" :options="['R' => 6, 'r' => 1, 'offset' => 3]" />
            <x-nx::parametric-loader kind="lissajous" :options="['a' => 5, 'b' => 4]" :duration="4200" />
            <x-nx::parametric-loader size="sm" />
        </div>
    </section>
</div>
