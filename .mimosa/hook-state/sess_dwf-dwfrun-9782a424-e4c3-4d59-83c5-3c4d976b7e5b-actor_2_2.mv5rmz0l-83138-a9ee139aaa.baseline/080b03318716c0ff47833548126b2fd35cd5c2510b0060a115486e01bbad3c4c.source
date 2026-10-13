{{--
    The product card's real scenarios: the storefront shelf of a Persian
    shop (discount star, ratings, the low-stock breath), a cart pair wired
    from both sides (the server-seeded in-cart state, then the nx-add /
    nx-remove events reporting through toasts), and the edge states — a
    card with no photo and one that is sold out.
--}}
@php
    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    // Product shots as inline SVG data URIs — no external assets, and the
    // aspect ratio (4 / 5) matches the card's media frame.
    $shot = fn (string $body) => 'data:image/svg+xml,' . rawurlencode(
        '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 500">' . $body . '</svg>'
    );
    $art = [
        'headphones' => $shot('<rect width="400" height="500" fill="#eef0ff"/><path d="M118 260 v-50 a82 82 0 0 1 164 0 v50" fill="none" stroke="#4f46e5" stroke-width="16" stroke-linecap="round"/><rect x="96" y="244" width="44" height="96" rx="20" fill="#4f46e5"/><rect x="260" y="244" width="44" height="96" rx="20" fill="#4f46e5"/>'),
        'speaker' => $shot('<rect width="400" height="500" fill="#e6fffb"/><rect x="130" y="90" width="140" height="320" rx="26" fill="none" stroke="#0d9488" stroke-width="14"/><circle cx="200" cy="180" r="32" fill="none" stroke="#0d9488" stroke-width="12"/><circle cx="200" cy="320" r="54" fill="#0d9488"/><circle cx="200" cy="320" r="18" fill="#e6fffb"/>'),
        'watch' => $shot('<rect width="400" height="500" fill="#fbf0d9"/><rect x="172" y="70" width="56" height="120" rx="26" fill="none" stroke="#b45309" stroke-width="12"/><rect x="172" y="310" width="56" height="120" rx="26" fill="none" stroke="#b45309" stroke-width="12"/><circle cx="200" cy="250" r="86" fill="none" stroke="#b45309" stroke-width="16"/><line x1="200" y1="250" x2="200" y2="200" stroke="#b45309" stroke-width="14" stroke-linecap="round"/><line x1="200" y1="250" x2="236" y2="276" stroke="#b45309" stroke-width="14" stroke-linecap="round"/>'),
        'charger' => $shot('<rect width="400" height="500" fill="#eff6ff"/><circle cx="200" cy="250" r="112" fill="none" stroke="#2563eb" stroke-width="14"/><circle cx="200" cy="250" r="52" fill="#2563eb"/><path d="M206 168 l-34 92 h38 l-16 74 66-104 h-40 l22-62 z" fill="#eff6ff" stroke="#2563eb" stroke-width="8" stroke-linejoin="round"/>'),
    ];
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The storefront shelf', 'قفسهٔ ویترین فروشگاه') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The shop as it ships: hover (or focus) a card and its photo zooms inside the frame, the pre-discount price is crossed out while the saving — a whole percent — is computed from the two prices, the stars fill to the fraction of the rating, and the stock badge speaks in states (the low one breathes and counts the units left). Every number, percent and tally arrives in the page’s own digits.', 'فروشگاه همان‌طور که واقعاً منتشر می‌شود: روی کارت هاور کنید (یا فوکوس کنید) تا عکسش در قاب خودش زوم کند، قیمتِ قبل از تخفیف خط می‌خورد در حالی که میزان پس‌انداز — یک درصدِ گرد — از خود همان دو قیمت حساب می‌شود، ستاره‌ها تا کسرِ امتیاز پُر می‌شوند و نشان موجودی به سه حالت حرف می‌زند (حالت «کم‌موجود» نفس می‌کشد و تعدادِ مانده را می‌گوید). همهٔ عددها، درصدها و شمارنده‌ها با ارقام خودِ صفحه می‌آیند.') }}
        </p>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 16rem), 1fr)); gap: var(--nx-space-4)">
        <x-nx::product-card
            :title="$say('Nabu B2 wireless headphones', 'هدفون بی‌سیم نابو B2')"
            :category="$say('Audio', 'صدا')"
            href="#"
            :image="['src' => $art['headphones'], 'alt' => $say('Nabu B2 headphones, front view', 'هدفون نابو B2، نمای روبه‌رو')]"
            :price="4850000" :compareAt="6900000" currency="{{ $say('toman', 'تومان') }}"
            :rating="4.5" :ratingCount="1284" stock="in" />
        <x-nx::product-card
            :title="$say('Nabu Mini bookshelf speaker', 'اسپیکر کتابی نابو Mini')"
            :category="$say('Audio', 'صدا')"
            href="#"
            :image="['src' => $art['speaker'], 'alt' => $say('Nabu Mini speaker, front view', 'اسپیکر نابو Mini، نمای روبه‌رو')]"
            :price="2190000" currency="{{ $say('toman', 'تومان') }}"
            :rating="4" :ratingCount="216" stock="low" :stockCount="2" />
        <x-nx::product-card
            :title="$say('Nabu Pulse smartwatch', 'ساعت هوشمند نابو Pulse')"
            :category="$say('Wearables', 'پوشیدنی')"
            href="#"
            :image="['src' => $art['watch'], 'alt' => $say('Nabu Pulse smartwatch, front view', 'ساعت هوشمند نابو Pulse، نمای روبه‌رو')]"
            :price="3980000" currency="{{ $say('toman', 'تومان') }}"
            :rating="4.8" :ratingCount="89" stock="in" />
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('The cart, from both sides', 'سبد خرید، از دو طرف') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('The button morphs on its spring — icon swap, label crossfade, recolour — and announces itself to screen readers. The left card arrives with in-cart already true (the server is the truth there); the right one starts empty. Without add-action everything stays client-side and the nx-add / nx-remove events bubble from the card — here they report through a toast; pass add-action="addToCart" and remove-action="removeFromCart" instead, and Livewire’s method is called after the optimistic flip while the button holds still for the round-trip.', 'دکمه با فنر خودش مورف می‌شود — تعویض آیکون، کراس‌فید برچسب، تغییر رنگ — و خودش را به صفحه‌خوان‌ها هم اعلام می‌کند. کارت سمت ابتدا با in-cart از قبل روشن می‌رسد (حقیقت همان‌جا سمت سرور است)؛ کارت دیگر خالی شروع می‌کند. بدون add-action همه‌چیز سمت مرورگر می‌ماند و رویدادهای nx-add و nx-remove از خود کارت حباب می‌کنند — اینجا با یک توست خبر می‌دهند؛ به‌جایش add-action="addToCart" و remove-action="removeFromCart" بدهید تا متد لایووایر شما پس از چرخش خوش‌بینانه صدا زده شود و دکمه تا پایان رفت‌وبرگشت درنگ کند.') }}
        </p>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 16rem), 1fr)); gap: var(--nx-space-4)">
        <x-nx::product-card
            :title="$say('Nabu wireless charging pad', 'پایهٔ شارژ بی‌سیم نابو')"
            :category="$say('Accessories', 'لوازم جانبی')"
            href="#"
            :image="['src' => $art['charger'], 'alt' => $say('Nabu charging pad, top view', 'پایهٔ شارژ نابو، نمای از بالا')]"
            :price="890000" currency="{{ $say('toman', 'تومان') }}"
            stock="in" in-cart
            x-on:nx-add="$wire.ping('{{ $say('Added', 'به سبد اضافه شد') }}: ' + $event.detail.title)"
            x-on:nx-remove="$wire.ping('{{ $say('Removed', 'از سبد حذف شد') }}: ' + $event.detail.title)" />
        <x-nx::product-card
            :title="$say('Nabu flat cable, 1.5 m', 'کابل تخت نابو، ۱٫۵ متر')"
            :category="$say('Accessories', 'لوازم جانبی')"
            href="#"
            :price="149000" currency="{{ $say('toman', 'تومان') }}"
            stock="in"
            x-on:nx-add="$wire.ping('{{ $say('Added', 'به سبد اضافه شد') }}: ' + $event.detail.title)"
            x-on:nx-remove="$wire.ping('{{ $say('Removed', 'از سبد حذف شد') }}: ' + $event.detail.title)" />
    </div>
    <p style="margin: 0; font-size: var(--nx-text-sm); color: var(--nx-text-muted)">
        {{ $say('Out-of-stock cards never get here: their button ships disabled, so the guard never has to fire.', 'کارت‌های ناموجود اصلاً به اینجا نمی‌رسند: دکمهٔ‌شان از همان رندر ازکار افتاده است، پس نگهبانِ داخلی هرگز فعال نمی‌شود.') }}
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('No photo, and sold out', 'بدون عکس، و ناموجود') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Without an image the tags ride along the body’s top edge instead of over a photo — the audiobook still carries its discount, rating and stock. And when stock is out, the badge turns danger and the button disables itself with the “out of stock” words from the core i18n table.', 'بدون تصویر، تگ‌ها به جای روی عکس، لبهٔ بالای بدنه سوار می‌شوند — کتاب صوتی تخفیف، امتیاز و موجودی‌اش را هنوز دارد. و وقتی موجودی تمام است، نشان به خطر می‌رود و دکمه با واژهٔ «ناموجود» از جدول i18n هسته خودش را ازکار می‌اندازد.') }}
        </p>
    </div>
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 16rem), 1fr)); gap: var(--nx-space-4)">
        <x-nx::product-card
            :title="$say('“The Nabu Path”, the audiobook', 'کتاب صوتی «مسیر نابو»')"
            :category="$say('Audiobook', 'کتاب صوتی')"
            href="#"
            :price="249000" :compareAt="320000" currency="{{ $say('toman', 'تومان') }}"
            :rating="4.9" :ratingCount="41" stock="in" />
        <x-nx::product-card
            :title="$say('Nabu Cast microphone', 'میکروفون نابو Cast')"
            :category="$say('Audio', 'صدا')"
            href="#"
            :price="3490000" currency="{{ $say('toman', 'تومان') }}"
            stock="out" />
    </div>
</section>
