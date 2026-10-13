{{--
    The textarea as it lives in real products: a checkout order note that
    grows with the customer's words (until maxRows), a support reply that is
    validated while typing and sent from a spinning button, and a team bio
    capped by the browser's own maxlength with a Persian countdown.
--}}
@php
    use NabuXUI\NabuXUI;

    $fa = app()->getLocale() === 'fa';
    $say = fn (string $en, string $faText) => $fa ? $faText : $en;

    $note = (string) ($state['orderNote'] ?? '');

    $reply = trim((string) ($state['supportReply'] ?? ''));
    $minReply = 30;
    $replyError = $reply !== '' && mb_strlen($reply) < $minReply
        ? $say('Say a little more — at least '.NabuXUI::formatNumber($minReply).' characters.', 'کمی بیشتر بنویسید — دست‌کم '.NabuXUI::formatNumber($minReply).' نویسه.')
        : null;

    $bio = (string) ($state['teamBio'] ?? '');
    $bioLeft = max(140 - mb_strlen($bio), 0);
@endphp

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('Order note at checkout', 'یادداشت سفارش در پرداخت') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Two rows to start, then the field grows line by line with what the customer writes; past five rows it stops growing and scrolls inside, so the receipt stays compact. Whatever is typed lands on the picking list below, exactly as it will be printed.', 'با ۲ ردیف شروع می‌شود و خط به خط با نوشتهٔ مشتری بالا می‌رود؛ بعد از ۵ ردیف رشد را کنار می‌گذارد و درون خودش اسکرول می‌گیرد تا رسید جمع بماند. هر چه تایپ می‌شود، همان‌طور که چاپ خواهد شد پایین روی لیست برداشت می‌نشیند.') }}
        </p>
    </div>
    <x-nx::textarea
        :label="$say('Note for the warehouse', 'یادداشت برای انبار‌دار')"
        :hint="$say('Delivery hour, exact address or packaging notes — printed on the picking list.', 'ساعت تحویل، نشانی دقیق یا نکتهٔ بسته‌بندی — روی لیست برداشت چاپ می‌شود.')"
        rows="2"
        :maxRows="5"
        :placeholder="$say('Ring the bell twice, the dog is in the yard…', 'زنگ را دو بار بزنید؛ سگ در حیاط است…')"
        wire:model.live.debounce.300ms="state.orderNote" />
    <p style="margin: 0; color: var(--nx-text-muted)">
        {{ $say('Picking list:', 'لیست برداشت:') }}
        <strong dir="auto" style="font-weight: 600; color: var(--nx-text)">{{ $note !== '' ? $note : $say('no note yet', 'هنوز یادداشتی نیست') }}</strong>
    </p>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div>
        <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A support reply worth sending', 'پاسخ پشتیبانی که ارزش ارسال داشته باشد') }}</h3>
        <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
            {{ $say('Required and validated while typing: anything shorter than 30 characters gets the red frame, and nothing is sent until it is fixed. The send is slow on purpose, so the button can spin.', 'الزامی است و هنگام تایپ بررسی می‌شود: هر چه کوتاه‌تر از ۳۰ نویسه باشد قاب قرمز و پیام خطا می‌گیرد و تا درست نشود چیزی ارسال نمی‌شود. ارسال عمداً کند است تا دکمه بچرخد.') }}
        </p>
    </div>
    <x-nx::textarea
        :label="$say('Reply to ticket #981', 'پاسخ به تیکت ۹۸۱')"
        :hint="$say('The customer reads this verbatim.', 'مشتری همین را مو‌به‌مو می‌خواند.')"
        required
        :maxRows="8"
        :error="$replyError"
        wire:model.live.debounce.300ms="state.supportReply" />
    <div class="pg-row" style="justify-content: flex-end">
        <x-nx::button variant="primary" icon="arrow-right" wire:click="save('{{ $say('Reply sent to the customer', 'پاسخ برای مشتری ارسال شد') }}')">
            {{ $say('Send reply', 'ارسال پاسخ') }}
        </x-nx::button>
    </div>
</section>

<section class="pg-box" style="gap: 1.25rem">
    <div class="pg-row" style="justify-content: space-between">
        <div>
            <h3 class="pg-title" style="font-size: var(--nx-text-xl)">{{ $say('A team bio with a hard ceiling', 'معرفی تیم با سقف سخت') }}</h3>
            <p style="margin: .25rem 0 0; color: var(--nx-text-muted)">
                {{ $say('maxlength reaches the native control untouched, so the browser itself stops the typing — the countdown only reports it, in locale digits, and turns warning with 20 left.', 'maxlength بی‌واسطه به کنترل بومی می‌رسد و خود مرورگر تایپ را می‌بندد — شمارش معکوس فقط خبر می‌دهد، با ارقام فارسی، و با ۲۰ نویسهٔ باقی‌مانده به هشدار می‌رود.') }}
            </p>
        </div>
        <span class="nx-badge" data-tone="{{ $bioLeft <= 20 ? 'warning' : 'neutral' }}">{{ NabuXUI::formatNumber($bioLeft).' '.$say('left', 'باقی‌مانده') }}</span>
    </div>
    <x-nx::textarea
        :label="$say('Team introduction', 'معرفی تیم')"
        rows="3"
        :maxRows="3"
        maxlength="140"
        :placeholder="$say('What your team builds, in one breath…', 'آنچه تیم‌تان می‌سازد، در یک نفس…')"
        wire:model.live.debounce.300ms="state.teamBio" />
</section>
