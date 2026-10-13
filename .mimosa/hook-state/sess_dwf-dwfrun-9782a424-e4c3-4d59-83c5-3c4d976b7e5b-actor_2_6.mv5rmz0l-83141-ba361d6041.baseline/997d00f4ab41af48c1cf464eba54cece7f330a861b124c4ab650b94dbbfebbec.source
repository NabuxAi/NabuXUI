{{--
    /admin/analytics — growth at a glance: four stat cards (each trend renders
    the sparkline block), the workspace metric chart over the last six months,
    a traffic analytics-card with 7/30/90-day periods, the plan comparison
    table and the usage card. Labels come from admin.* keys.
--}}
<x-admin.page active="analytics" :title="__('admin.analytics_title')" :subtitle="__('admin.analytics_subtitle')">
    <div class="ap-grid">
        <div class="ap-stats" data-nx-reveal="group" x-nx-reveal.group aria-label="{{ __('admin.analytics_title') }}">
            @foreach ($stats as $stat)
                <x-nx::stat-card style="--nx-i: {{ $loop->index }}" :label="$stat['label']" :value="$stat['value']"
                    :decimals="$stat['decimals']" :suffix="$stat['suffix']" :delta="$stat['delta']"
                    :trend="$stat['trend']" :caption="$stat['caption']" />
            @endforeach
        </div>

        <div class="ap-duo">
            <x-nx::metric-chart :title="__('admin.analytics_chart_title')"
                :caption="__('admin.chart_caption')" :labels="$months" :metrics="$metrics" />
            <x-nx::analytics-card :title="__('admin.analytics_label_traffic')" active="30d"
                :periods="$traffic" :format="['maximumFractionDigits' => 0]" />
        </div>

        <div class="ap-duo">
            <x-nx::comparison-table :caption="__('admin.analytics_compare_plans')" recommended="growth"
                :plans="$plans" :features="$features" />
            <x-nx::usage-card :title="__('admin.usage_title')" :plan="__('admin.usage_plan')"
                :limit="$usage['limit']" :categories="$usage['categories']" :unit="__('admin.usage_unit')"
                :decimals="1" :note="__('admin.usage_note')"
                :action="['label' => __('admin.usage_upgrade'), 'href' => route('admin.settings')]" />
        </div>
    </div>
</x-admin.page>
