{{-- Livewire demos: animated backdrops that carry their content (pure CSS). --}}
<section class="pg-grid">
    <div class="pg-box">
        <h2 class="pg-title">Aurora · شفق</h2>
        <p style="margin:0;color:var(--nx-text-muted)">Three blurred curtains of lapis, violet and cyan drifting behind the slot — no Alpine, no JS.</p>
        <x-nx::backdrop-aurora style="min-block-size: 16rem; display: grid; place-items: center; align-content: center; gap: var(--nx-space-4); padding: var(--nx-space-8) var(--nx-space-6); border-radius: var(--nx-radius-lg); text-align: center">
            <h3 style="margin:0;font:800 var(--nx-text-2xl)/1.1 var(--nx-font-display)">Ship the next release · رهاشدن نسخهٔ تازه</h3>
            <p style="margin:0;color:var(--nx-text-muted)">The section is the backdrop — content paints above the layers.</p>
            <x-nx::button variant="primary" icon="sparkles">Get started · شروع کنید</x-nx::button>
        </x-nx::backdrop-aurora>
    </div>

    <div class="pg-box">
        <h2 class="pg-title">Starfield · میدان ستاره</h2>
        <p style="margin:0;color:var(--nx-text-muted)">Three box-shadow star fields falling at their own speeds; the near field also breathes. <code>--_tile</code> is the loop length.</p>
        <x-nx::backdrop-starfield style="min-block-size: 16rem; display: grid; place-items: center; align-content: center; gap: var(--nx-space-4); padding: var(--nx-space-8) var(--nx-space-6); border-radius: var(--nx-radius-lg); text-align: center">
            <h3 style="margin:0;font:800 var(--nx-text-2xl)/1.1 var(--nx-font-display)">A parallax night sky · شب‌تاب پارالاکس</h3>
            <p style="margin:0;color:var(--nx-text-muted)">Near stars overtake the far ones, so the sky gains depth.</p>
            <x-nx::button variant="glow" icon="star">Count the stars · ستاره‌ها را بشمار</x-nx::button>
        </x-nx::backdrop-starfield>
    </div>

    <div class="pg-box">
        <h2 class="pg-title">Mesh · مش رنگین</h2>
        <p style="margin:0;color:var(--nx-text-muted)">Five blurred colour fields blending into one woven surface — soft-light in light themes, screen in dark. Mesh paints five layers, not three.</p>
        <x-nx::backdrop-mesh style="min-block-size: 16rem; display: grid; place-items: center; align-content: center; gap: var(--nx-space-4); padding: var(--nx-space-8) var(--nx-space-6); border-radius: var(--nx-radius-lg); text-align: center">
            <h3 style="margin:0;font:800 var(--nx-text-2xl)/1.1 var(--nx-font-display)">A living mesh · مش زنده</h3>
            <p style="margin:0;color:var(--nx-text-muted)">Lapis, violet, cyan, gold and a violet-cyan weave, each on its own clock.</p>
            <x-nx::button icon="layers">See the weave · بافته را ببین</x-nx::button>
        </x-nx::backdrop-mesh>
    </div>

    <div class="pg-box">
        <h2 class="pg-title">Dither · موج موآره</h2>
        <p style="margin:0;color:var(--nx-text-muted)">A wave rolling through a field of tiny lapis and violet dots. Dot pitch: <code>style="--nx-dither-cell: 12px"</code> on the container (12px here). For the canvas dither keep <code>&lt;x-nx::dither-backdrop /&gt;</code>.</p>
        <x-nx::backdrop-dither style="--nx-dither-cell: 12px; min-block-size: 16rem; display: grid; place-items: center; align-content: center; gap: var(--nx-space-4); padding: var(--nx-space-8) var(--nx-space-6); border-radius: var(--nx-radius-lg); text-align: center">
            <h3 style="margin:0;font:800 var(--nx-text-2xl)/1.1 var(--nx-font-display)">The halftone wave · موج نیم‌تن</h3>
            <p style="margin:0;color:var(--nx-text-muted)">Two dot screens crossed at an angle read as one wave.</p>
            <x-nx::button variant="ghost" icon="cpu">Change the pitch · گام را عوض کن</x-nx::button>
        </x-nx::backdrop-dither>
    </div>
</section>
