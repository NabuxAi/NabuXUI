{{--
    /admin — the dashboard: welcome hero over the aurora backdrop block, four
    stat cards with rolling numbers, the workspace metric chart, the activity
    stream, a dot-matrix chart of tickets and a usage card. Every label comes
    from playground/lang/{fa,en}/admin.php; numbers roll in the locale digits.
--}}
<x-admin.page active="dashboard" :title="__('admin.dashboard_title')" :subtitle="__('admin.dashboard_subtitle')">
    <div class="ap-grid">
        <x-nx::backdrop-aurora class="ap-hero" data-nx-reveal x-nx-reveal>
            <div class="ap-hero-body">
                <p class="ap-hero-hello">{{ __('admin.welcome_hello') }}</p>
                <p class="ap-hero-text">{{ __('admin.welcome_text') }}</p>
                <div class="ap-row">
                    <x-nx::button variant="primary" icon="chart" :href="route('admin.analytics')" wire:navigate>{{ __('admin.welcome_cta') }}</x-nx::button>
                    <x-nx::button variant="secondary" icon="users" wire:click="inviteTeammates">{{ __('admin.welcome_secondary') }}</x-nx::button>
                </div>
            </div>
        </x-nx::backdrop-aurora>

        <div class="ap-stats" data-nx-reveal="group" x-nx-reveal.group aria-label="{{ __('admin.dashboard_title') }}">
            @foreach ($stats as $stat)
                <x-nx::stat-card style="--nx-i: {{ $loop->index }}" :label="$stat['label']" :value="$stat['value']"
                    :delta="$stat['delta']" :trend="$stat['trend']" :caption="$stat['caption']" />
            @endforeach
        </div>

        <div class="ap-duo">
            <x-nx::metric-chart :title="__('admin.chart_title')" :caption="__('admin.chart_caption')"
                :labels="$monthLabels" :metrics="$metrics" />
            <section class="ap-box">
                <h3 class="ap-box-title">{{ __('admin.timeline_title') }}</h3>
                <x-nx::timeline-feed :items="$timeline" :label="__('admin.timeline_title')" />
                <p class="ap-box-more">
                    <x-nx::button size="sm" variant="ghost" icon="chevron-left" :href="route('admin.users')" wire:navigate>{{ __('admin.timeline_view_all') }}</x-nx::button>
                </p>
            </section>
        </div>

        <div class="ap-duo">
            <x-nx::dot-matrix-chart :title="__('admin.matrix_title')" :subtitle="__('admin.matrix_subtitle')"
                :labels="$matrix['labels']" :series="$matrix['series']" />
            <x-nx::usage-card :title="__('admin.usage_title')" :plan="__('admin.usage_plan')"
                :limit="$usage['limit']" :categories="$usage['categories']" :unit="__('admin.usage_unit')"
                :decimals="1" :note="__('admin.usage_note')"
                :action="['label' => __('admin.usage_upgrade'), 'href' => route('admin.settings')]" />
        </div>
    </div>
</x-admin.page>
