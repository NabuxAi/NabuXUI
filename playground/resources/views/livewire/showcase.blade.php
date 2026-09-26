<div>
    <x-nx::header variant="floating" hide-on-scroll navigate label="Main" :items="[
        ['label' => 'Components', 'columns' => 2, 'children' => [
            ['label' => 'Buttons', 'href' => '#buttons', 'icon' => 'zap', 'description' => 'States, effects, magnetic'],
            ['label' => 'Inputs', 'href' => '#inputs', 'icon' => 'sliders', 'description' => 'wire:model everywhere'],
            ['label' => 'Cards', 'href' => '#cards', 'icon' => 'layers', 'description' => 'Spotlight, tilt, stacks'],
            ['label' => 'Charts', 'href' => '#charts', 'icon' => 'chart', 'description' => 'Animated, accessible'],
        ]],
        ['label' => 'Motion', 'columns' => 1, 'children' => [
            ['label' => 'Page transitions', 'href' => '/livewire/second', 'icon' => 'arrow-right'],
            ['label' => 'Loading', 'href' => '#loading', 'icon' => 'cpu'],
        ]],
        ['label' => 'Pricing', 'href' => '#pricing'],
    ]">
        <x-slot:brand><strong>Nabu<span style="color: var(--nx-gold-text)">X</span>UI</strong></x-slot:brand>
        <x-slot:actions>
            <x-nx::button variant="ghost" size="sm" icon="search" x-on:click="$dispatch('nx-command')"><x-nx::kbd>⌘K</x-nx::kbd></x-nx::button>
            <x-nx::theme-toggle />
        </x-slot:actions>
    </x-nx::header>

    <main class="nx-page" wire:transition.navigate="nx-page">
        <x-nx::hero backdrop="grid" eyebrow="NabuXUI for Livewire" eyebrow-badge="New" eyebrow-href="#buttons"
            title="One motion language · 一つの動き · حركة واحدة"
            subtitle="Blade components with the same animations as the React package: Alpine drives them, Livewire binds them.">
            <x-slot:actions>
                <x-nx::button variant="primary" size="lg" shape="pill" effect="shine" icon-end="arrow-right" href="#buttons">Browse components</x-nx::button>
                <x-nx::button variant="glow" size="lg" shape="pill" icon="command" magnetic x-on:click="$dispatch('nx-command')">Quick search</x-nx::button>
            </x-slot:actions>
        </x-nx::hero>

        <div class="pg">
            <section id="text" class="pg-box">
                <h2 class="pg-title">Text</h2>
                <x-nx::text-reveal as="p" style="margin:0;font-size:var(--nx-text-2xl);font-weight:700">Hola, Bonjour, 你好, سلام — Nabu writes in every language.</x-nx::text-reveal>
                <x-nx::shimmer variant="wave" style="font-size:var(--nx-text-xl);font-weight:600">Generating your answer</x-nx::shimmer>
                <x-nx::scramble trigger="hover" style="font-family:var(--nx-font-mono);font-size:var(--nx-text-xl);font-weight:700">NABUXAI · SCRIBE</x-nx::scramble>
                <p style="margin:0;font-size:var(--nx-text-xl);font-weight:700">Built for <x-nx::gradient-text><x-nx::word-rotate :words="['agents', 'soporte', 'サポート', 'الدعم']" /></x-nx::gradient-text></p>
                <div class="pg-row">
                    <span style="font-size:var(--nx-text-4xl);font-weight:800"><x-nx::number :value="$count" /></span>
                    <x-nx::button size="sm" icon="plus" wire:click="bump">Add</x-nx::button>
                </div>
                <p style="margin:0;font-size:var(--nx-text-lg)">Answers that are <x-nx::highlight>precise</x-nx::highlight>, <x-nx::highlight variant="circle">fast</x-nx::highlight> and <x-nx::highlight variant="marker">yours</x-nx::highlight>.</p>
            </section>

            <section id="buttons" class="pg-box">
                <h2 class="pg-title">Buttons</h2>
                <div class="pg-row">
                    <x-nx::button variant="primary">Primary</x-nx::button>
                    <x-nx::button>Secondary</x-nx::button>
                    <x-nx::button variant="outline">Outline</x-nx::button>
                    <x-nx::button variant="ghost">Ghost</x-nx::button>
                    <x-nx::button variant="danger">Danger</x-nx::button>
                    <x-nx::button variant="gold">Gold</x-nx::button>
                    <x-nx::button variant="glow" magnetic ripple icon="sparkles">Glow</x-nx::button>
                    <x-nx::button effect="slide">Slide label</x-nx::button>
                    <x-nx::icon-button icon="bell" label="Notifications" />
                </div>
                <div class="pg-row">
                    <x-nx::button variant="primary" size="lg" icon="lock" wire:click="pay">Pay $49 (wire:loading)</x-nx::button>
                    <x-nx::copy-button value="composer require nabuxai/nabuxui" variant="secondary">composer require nabuxai/nabuxui</x-nx::copy-button>
                    <x-nx::like-button :count="128" />
                </div>
            </section>

            <section id="inputs" class="pg-grid">
                <form class="pg-box" wire:submit="save">
                    <h2 class="pg-title">Inputs</h2>
                    <x-nx::input label="Work email" type="email" icon="mail" hint="Receipts go here." wire:model.blur="email" dir="ltr" required />
                    <x-nx::textarea label="Message · پیام · メッセージ" wire:model="message" placeholder="Type — the box grows…" />
                    <x-nx::select label="Model" :options="['lite' => 'Nabu Lite', 'pro' => 'Nabu Pro', 'max' => 'Nabu Max']" />
                    <x-nx::button type="submit" variant="primary">Save</x-nx::button>
                </form>
                <div class="pg-box">
                    <x-nx::checkbox label="Monthly newsletter" description="One email a month, no ads." checked />
                    <x-nx::checkbox label="All projects" indeterminate />
                    <x-nx::radio-group legend="Billing" orientation="horizontal" :options="['monthly' => 'Monthly', 'yearly' => 'Yearly']" wire:model.live="plan" :value="$plan" />
                    <x-nx::switch label="Auto-reply on Telegram" wire:model.live="notify" />
                    <x-nx::otp length="6" separator-after="3" wire:model.live="code" />
                    <p style="margin:0">Code: <code>{{ $code }}</code></p>
                    <x-nx::slider min="0" max="2" step="0.1" decimals="1" label="Temperature" start-label="Precise" end-label="Creative" wire:model.live="temperature" />
                    <p style="margin:0">Temperature: {{ $temperature }}</p>
                </div>
                <div class="pg-box">
                    <x-nx::file-drop wire:model="attachments" multiple hint="PDF, images or audio" />
                    <x-nx::prompt wire:submit="ask" wire:model="message" streaming-property="streaming" placeholder="Ask Nabu anything…" x-on:nx-stop="$wire.stop()">
                        <x-slot:toolbar><x-nx::button size="sm" variant="ghost" icon="globe">Web search</x-nx::button></x-slot:toolbar>
                    </x-nx::prompt>
                </div>
            </section>

            <section id="cards" class="pg-grid">
                <x-nx::card spotlight interactive icon="sparkles" title="Pointer spotlight" description="The surface and the edge light up under the pointer.">
                    <x-slot:footer><x-nx::badge tone="success" pulse>Live</x-nx::badge></x-slot:footer>
                </x-nx::card>
                <x-nx::card tilt variant="gradient" icon="layers" title="3D tilt" description="Leans toward the pointer, springs back." />
                <x-nx::stat-card label="Monthly revenue" :value="48260" prefix="$" :delta="12.4" :trend="[12, 18, 14, 22, 26, 24, 31, 35, 33, 41, 44, 48]" caption="vs last month" />
                <div class="pg-box" style="justify-items:center">
                    <x-nx::flip-card trigger="click">
                        <x-slot:front><x-nx::card title="NabuAuth" icon="lock" description="Single sign-on for every Nabu service." style="min-block-size:12rem;inline-size:16rem" /></x-slot:front>
                        <x-slot:back><x-nx::card variant="inverse" title="On the back" description="OAuth2, OpenID Connect, PKCE." style="min-block-size:12rem;inline-size:16rem" /></x-slot:back>
                    </x-nx::flip-card>
                </div>
            </section>

            <section id="charts" class="pg-grid">
                <div class="pg-box" style="grid-column:1/-1">
                    <x-nx::chart.area title="Conversations answered" subtitle="Last 9 months" :labels="['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep']" :series="[
                        ['name' => 'Telegram', 'values' => [1200, 1900, 1700, 2600, 3100, 2900, 3800, 4200, 4700]],
                        ['name' => 'WhatsApp', 'values' => [800, 1100, 1500, 1400, 1900, 2400, 2300, 2900, 3300]],
                    ]" />
                </div>
                <div class="pg-box"><x-nx::chart.bar title="Tickets closed" :data="['Mon' => 42, 'Tue' => 58, 'Wed' => 51, 'Thu' => 67, 'Fri' => 73, 'Sat' => 38, 'Sun' => 21]" /></div>
                <div class="pg-box"><x-nx::chart.donut title="Channels" center-label="messages" :data="['Telegram' => 4700, 'WhatsApp' => 3300, 'Web' => 1800, 'Email' => 900]" /></div>
                <div class="pg-box" style="justify-items:center"><x-nx::progress-ring :value="72" label="Quota used" /></div>
            </section>

            <section id="pricing">
                <x-nx::pricing yearly-note="2 months free" :plans="[
                    ['id' => 'starter', 'name' => 'Starter', 'description' => 'Try AI support.', 'price' => ['monthly' => 19, 'yearly' => 15], 'currency' => '$', 'period' => ['monthly' => '/month', 'yearly' => '/month'], 'features' => [['label' => '1 channel'], ['label' => 'Custom agent', 'included' => false]], 'cta' => ['label' => 'Start free', 'href' => '#']],
                    ['id' => 'pro', 'name' => 'Pro', 'description' => 'Every channel in one place.', 'price' => ['monthly' => 49, 'yearly' => 41], 'currency' => '$', 'period' => ['monthly' => '/month', 'yearly' => '/month'], 'featured' => true, 'flag' => 'Most popular', 'features' => [['label' => 'Every channel'], ['label' => 'Custom agent']], 'cta' => ['label' => 'Choose Pro', 'href' => '#']],
                ]" />
            </section>

            <section id="loading" class="pg-box">
                <h2 class="pg-title">Feedback & loading</h2>
                <div class="pg-row">
                    <x-nx::button variant="primary" x-on:click="$nxToast.success('Saved', { description: 'Guardado · 保存しました · تم الحفظ' })">Toast</x-nx::button>
                    <x-nx::button variant="danger" x-on:click="$nxToast.error('Payment failed')">Error toast</x-nx::button>
                    <x-nx::tooltip text="Copy the API URL"><x-nx::button size="sm" icon="copy">Tooltip</x-nx::button></x-nx::tooltip>
                    <x-nx::spinner tone="accent" size="lg" />
                    <x-nx::loader.orbit />
                    <x-nx::loader.dots />
                    <x-nx::loader.cuneiform />
                </div>
                <x-nx::alert tone="info" title="Scheduled maintenance" dismissible>Friday 2 AM · 10 minutes.</x-nx::alert>
                <x-nx::progress :value="64" label="Upload" />
                <x-nx::progress label="Processing" tone="brand" />
                <x-nx::skeleton lines="3" />
                <div class="pg-row">
                    <x-nx::badge>Draft</x-nx::badge><x-nx::badge tone="accent">New</x-nx::badge><x-nx::badge tone="success" pulse>Online</x-nx::badge><x-nx::badge tone="gold" variant="solid">PRO</x-nx::badge>
                    <x-nx::avatar-group label="Team" :people="[['name' => 'Haniyeh Rezaei'], ['name' => 'Kenji Sato'], ['name' => 'María López'], ['name' => 'Amara Okafor'], ['name' => 'Omar Haddad'], ['name' => 'Lena Fischer']]" max="4" />
                    <x-nx::rating wire:model.live="rating" :value="$rating" />
                </div>
            </section>

            <section class="pg-box">
                <h2 class="pg-title">Navigation</h2>
                <x-nx::tabs :items="['chat' => ['label' => 'Chat', 'icon' => 'message'], 'files' => ['label' => 'Files', 'icon' => 'file', 'badge' => '12'], 'settings' => ['label' => 'Settings', 'icon' => 'sliders']]" wire:model.live="tab">
                    <x-slot:chat><p style="margin:0">Today's messages live here. (tab = {{ $tab }})</p></x-slot:chat>
                    <x-slot:files><p style="margin:0">12 documents feed this agent.</p></x-slot:files>
                    <x-slot:settings><p style="margin:0">Tone, language and working hours.</p></x-slot:settings>
                </x-nx::tabs>
                <x-nx::segmented :options="['monthly' => 'Monthly', 'yearly' => 'Yearly']" wire:model.live="billing" :value="$billing" />
                <x-nx::nav-menu label="Sample" :items="[['href' => '#', 'label' => 'Products', 'current' => true], ['href' => '#pricing', 'label' => 'Pricing'], ['href' => '#cards', 'label' => 'Customers']]" />
                <x-nx::dock label="Apps" :items="[['label' => 'Home', 'icon' => 'home', 'href' => '#', 'current' => true], ['label' => 'Chat', 'icon' => 'message', 'href' => '#'], ['label' => 'Reports', 'icon' => 'chart', 'href' => '#'], ['label' => 'Team', 'icon' => 'users', 'href' => '#']]" />
                <x-nx::breadcrumbs :items="[['label' => 'Home', 'href' => '#'], ['label' => 'Agents', 'href' => '#'], ['label' => 'Sales support']]" />
                <x-nx::pagination :page="4" :pages="20" :url="fn ($p) => '?page='.$p" />
                <x-nx::steps :current="1" :steps="[['title' => 'Connect', 'description' => 'Telegram'], ['title' => 'Train'], ['title' => 'Launch']]" />
                <x-nx::accordion>
                    <x-nx::accordion-item title="Where is my data stored?" open>On your servers or ours — your choice.</x-nx::accordion-item>
                    <x-nx::accordion-item title="Which models are supported?">Any model NabuGate connects to.</x-nx::accordion-item>
                </x-nx::accordion>
                <div class="pg-row">
                    <x-nx::menu :items="[['type' => 'label', 'label' => 'Agent'], ['label' => 'Rename', 'icon' => 'edit', 'shortcut' => 'R', 'click' => '$nxToast(\'Rename\')'], ['label' => 'Voice replies', 'icon' => 'mic', 'checked' => true], ['type' => 'separator'], ['label' => 'Delete agent', 'icon' => 'trash', 'tone' => 'danger']]">
                        <x-slot:trigger><x-nx::button icon-end="chevron-down">Actions</x-nx::button></x-slot:trigger>
                    </x-nx::menu>
                    <x-nx::button variant="primary" wire:click="$set('showInvite', true)">Dialog (wire:model)</x-nx::button>
                    <x-nx::popover label="Details"><x-slot:trigger><x-nx::button variant="ghost" icon="info">Popover</x-nx::button></x-slot:trigger><p style="margin:0">Opens in the top layer.</p></x-nx::popover>
                    <x-nx::button variant="link" href="/livewire/second" wire:navigate>wire:navigate →</x-nx::button>
                </div>
            </section>

            <x-nx::marquee>
                @foreach (['NabuDesk', 'NabuCRM', 'NabuGate', 'NabuAuth', 'NabuHub', 'NabuWrite', 'NabuChat', 'NabuVoice'] as $product)
                    <li><x-nx::badge size="lg">{{ $product }}</x-nx::badge></li>
                @endforeach
            </x-nx::marquee>
        </div>
    </main>

    <x-nx::dialog wire:model="showInvite" title="Invite a teammate" description="The invite is valid for 7 days.">
        <x-nx::input label="Email" type="email" dir="ltr" />
        <x-slot:footer>
            <x-nx::button variant="ghost" x-on:click="open = false">Cancel</x-nx::button>
            <x-nx::button variant="primary" wire:click="invite">Send invite</x-nx::button>
        </x-slot:footer>
    </x-nx::dialog>

    <x-nx::command :groups="[
        ['label' => 'Sections', 'items' => [
            ['id' => 'buttons', 'label' => 'Buttons', 'icon' => 'zap', 'href' => '#buttons'],
            ['id' => 'charts', 'label' => 'Charts', 'icon' => 'chart', 'href' => '#charts'],
            ['id' => 'second', 'label' => 'Second page (wire:navigate)', 'icon' => 'arrow-right', 'href' => '/livewire/second'],
        ]],
    ]" />
</div>
