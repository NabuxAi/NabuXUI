import { useState } from 'react';
import { FlowAuthCode, FlowCheckout, type FlowCheckoutItem, FlowOnboarding } from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useTr } from '../lang';

/** The cart the checkout starts from — a desk, a keyboard, a stand. */
const CART: FlowCheckoutItem[] = [
  { id: 'lamp', name: 'Aster desk lamp', meta: 'Warm dim · graphite', price: 89, qty: 1 },
  { id: 'keys', name: 'Nimbus K2 keyboard', meta: 'Tactile · wireless', price: 149, qty: 1 },
  { id: 'stand', name: 'Vela laptop stand', meta: 'Aluminium · folding', price: 59, qty: 2 },
];

export function FlowsBlocks() {
  const tr = useTr();
  const [paid, setPaid] = useState('');

  return (
    <Section
      id="flows-blocks"
      eyebrow={tr('جریان‌های واقعی', 'Real flows')}
      title={tr('سه سفر که تا آخرش جدی است', 'Three journeys that mean it')}
      description={tr(
        'راه‌اندازی چهارقدمی با نوار پیشرفت و چیپ‌های انتخاب، پرداختِ سبد→آدرس→کارت→تأیید که هر قدمش خودش را می‌سنجد، و ورود با کد شش‌رقمی که خانه‌هایش خودشان فوکوس می‌کنند و تایمر ارسال دوباره واقعی است. وضعیت همه داخل کامپوننت است؛ قدم‌ها با ترنسلیت از جهت خواندن میآیند و در فارسی آینه میشوند.',
        'A four-step onboarding with a segmented progress bar and pick chips, a cart→address→card→confirmation checkout where every step guards itself, and a six-digit sign-in whose boxes focus themselves behind a real resend timer. All state lives inside the component; the steps slide in from the reading direction and mirror in Persian.',
      )}
      code={{
        react: `<FlowOnboarding brand="Meridian" :roles="roles" :topics="topics"
  welcome-points={['Dashboards tuned to your role', …]}
  :words="{ next: 'Continue', finish: 'Create workspace' }" @finish="create" />
<FlowCheckout :items="cart" :should-decline="(d) => d === '4000000000000002'" @paid="track" />
<FlowAuthCode hint-code="284913" :verify="(code) => code === '284913'" />`,
        blade: `{{-- ui-core ships the CSS; the markup is the same classes --}}
<section class="nx-flow nx-onboarding">
  <header class="nx-flow-top">
    <div class="nx-flow-bar" role="progressbar"><i data-on></i><i data-on></i><i></i><i></i></div>
    <p class="nx-flow-meta">…</p>
  </header>
  <div class="nx-flow-stage">
    <section class="nx-flow-step" data-pos="current">…</section>
    <section class="nx-flow-step" data-pos="after">…</section>
  </div>
  <footer class="nx-flow-nav">…back / continue…</footer>
</section>`,
      }}
    >
      <div className="sc-demos">
        <Demo wide title={tr('از «سلام» تا ورک‌اسپیس آماده', 'From hello to a ready workspace')} bare center>
          <FlowOnboarding
            brand="Meridian"
            welcomeTitle={tr('به خانهٔ جدید تیم‌تان خوش آمدید', 'Welcome to your team’s new home')}
            welcomeText={tr('چهار قدم کوتاه و ورک‌اسپیس با شیوهٔ کار شما جور می‌شود — نه کارت بانکی، نه تیکت برای ادمین.', 'Four short steps and the workspace fits how you work — no credit card, no admin tickets.')}
            welcomePoints={[
              tr('داشبورد هماهنگ با نقش شما', 'Dashboards tuned to your role'),
              tr('کیت‌های آغازین از پروژه‌های واقعی', 'Starter kits picked from real projects'),
              tr('هر وقت خواستید از تنظیمات تغییرش دهید', 'Editable any time from settings'),
            ]}
            rolesTitle={tr('اینجا کارِ شما چیست؟', 'What do you do here?')}
            rolesText={tr('فقط یک بار می‌پرسیم — تا بدانیم اولین دیدن کدام داشبورد باشد.', 'We ask once — to choose which dashboard greets you first.')}
            roles={[
              { id: 'designer', label: tr('طراح محصول', 'Product designer'), note: tr('شکل رابط را می‌سازد', 'Shapes the interface') },
              { id: 'engineer', label: tr('مهندس', 'Engineer'), note: tr('کد را می‌رساند', 'Ships the code') },
              { id: 'manager', label: tr('مدیر محصول', 'Product manager'), note: tr('مسیر را نگه می‌دارد', 'Owns the roadmap') },
              { id: 'marketer', label: tr('بازاریاب', 'Marketer'), note: tr('داستان را روایت می‌کند', 'Tells the story') },
            ]}
            topicsTitle={tr('فید خود را کوک کنید', 'Tune your feed')}
            topicsText={tr('انتخاب کنید هر دوشنبه چه چیزی روی میزتان بنشیند.', 'Pick what you want landing on your desk every Monday.')}
            topics={[
              { id: 'systems', label: tr('سیستم‌های طراحی', 'Design systems') },
              { id: 'motion', label: tr('موشن', 'Motion') },
              { id: 'a11y', label: tr('دسترس‌پذیری', 'Accessibility') },
              { id: 'speed', label: tr('کارایی', 'Performance') },
              { id: 'tokens', label: tr('توکن‌های طراحی', 'Design tokens') },
              { id: 'handoff', label: tr('تحویل طراحی', 'Handoff') },
            ]}
            summaryTitle={tr('نگاه آخر پیش از ساخت', 'Last look before we build it')}
            summaryText={tr('همهٔ این‌ها بعداً هم قابل تغییر است — این فقط خلاصهٔ روراست است.', 'Everything below is editable later — this is just the honest summary.')}
            words={{
              next: tr('ادامه', 'Continue'),
              finish: tr('ساخت ورک‌اسپیس', 'Create workspace'),
              stepOf: tr('قدم {current} از {total}', 'Step {current} of {total}'),
              pickHint: tr('یکی را بردار', 'Pick one'),
              someHint: tr('یکی یا چندتا', 'One or more'),
              lastLookHint: tr('نگاه آخر', 'Last look'),
              summaryRole: tr('نقش', 'Role'),
              summaryTopics: tr('علاقه‌ها', 'Interests'),
              doneTitle: tr('ورک‌اسپیس شما آماده شد', 'Your workspace is ready'),
              doneText: tr('برای {role} کوک شد — نخستین چکیده دوشنبه ساعت ۹:۰۰ می‌رسد.', 'Tuned for {role} — the first digest lands Monday 9:00.'),
              doneAction: tr('ورود به داشبورد', 'Enter the dashboard'),
            }}
          />
        </Demo>

        <Demo wide title={tr('پرداختی که واقعاً مثل پرداخت رفتار می‌کند', 'A checkout that behaves like one')} bare center>
          <FlowCheckout
            items={CART}
            shouldDecline={(digits) => digits === '4000000000000002'}
            onPaid={(order) => setPaid(order)}
            words={{
              cartLabel: tr('سبد', 'Cart'),
              addressLabel: tr('آدرس', 'Address'),
              paymentLabel: tr('پرداخت', 'Payment'),
              doneLabel: tr('تأیید', 'Done'),
              cartTitle: tr('سبد شما', 'Your cart'),
              emptyText: tr('سبد خالی است — هرچه حذف کردید یک کلیک با شما فاصله دارد.', 'The cart is empty — everything you removed is one click away.'),
              restoreLabel: tr('بازگرداندن سبد دمو', 'Restore the demo cart'),
              freeShippingAt: tr('افزودن {amount} تا ارسال رایگان شود', 'Add {amount} for free shipping'),
              freeShipping: tr('ارسال مهمان ما', 'Shipping is on us'),
              itemCount: tr('{count} قلم', '{count} items'),
              toAddress: tr('ادامه به آدرس', 'Continue to address'),
              addressTitle: tr('آدرس ارسال', 'Shipping address'),
              addressText: tr('دوبلین، فرانکفورت، سنگاپور — میز هرجا باشد، این فرم پیش از رفتن خودش را می‌سنجد.', 'Dublin, Frankfurt, Singapore — wherever the desk is, this form checks itself before moving on.'),
              nameLabel: tr('نام کامل', 'Full name'),
              streetLabel: tr('خیابان و پلاک', 'Street and number'),
              cityLabel: tr('شهر', 'City'),
              postcodeLabel: tr('کدپستی', 'Postcode'),
              countryLabel: tr('کشور', 'Country'),
              errName: tr('نام کامل لازم است', 'Full name is required'),
              errStreet: tr('خیابان و پلاک، لطفاً', 'Street and number, please'),
              errCity: tr('به کدام شهر بفرستیم؟', 'Which city ships it to?'),
              errPostcode: tr('کدپستی درست به نظر نمی‌رسد (۳ تا ۱۰ نویسه)', 'Postcode looks off (3–10 characters)'),
              backToCart: tr('بازگشت به سبد', 'Back to cart'),
              toPayment: tr('ادامه به پرداخت', 'Continue to payment'),
              paymentTitle: tr('پرداخت', 'Payment'),
              paymentText: tr('شماره چهارتایی گروه می‌شود و تاریخش خودش اسلش می‌خورد؛ برای دیدن ردشدنِ صادقانه 4000 0000 0000 0002 را امتحان کنید.', 'The number groups in fours and the expiry slashes itself. Try 4000 0000 0000 0002 to see a decline handled honestly.'),
              cardNumberLabel: tr('شمارهٔ کارت', 'Card number'),
              expiryLabel: tr('تاریخ انقضا', 'Expiry'),
              errCardNumber: tr('۱۶ رقم، چهارتایی گروه‌شده', '16 digits, grouped in fours'),
              errExpiry: tr('ماه/سال', 'MM/YY'),
              errCvc: tr('۳ تا ۴ رقم پشت کارت', '3–4 digits on the back'),
              declineText: tr('کارت رد شد. بانک نه گفت — هیچ مبلغی کسر نشد. شماره را عوض کنید و دوباره امتحان کنید.', 'Card declined. The bank said no — nothing was charged. Edit the number and try again.'),
              backToAddress: tr('بازگشت به آدرس', 'Back to address'),
              pay: tr('پرداخت {total}', 'Pay {total}'),
              processing: tr('در حال پردازش…', 'Processing…'),
              tryAgain: tr('تلاش دوباره', 'Try again'),
              doneTitle: tr('سفارش تأیید شد', 'Order confirmed'),
              doneText: tr('رسید در راه صندوق ایمیل شماست.', 'Receipt is on its way to your inbox.'),
              cardEnding: tr('کارت پایان‌یافته به {digits}', 'Card ending {digits}'),
              backToStore: tr('بازگشت به فروشگاه', 'Back to the store'),
              asideTitle: tr('خلاصهٔ سفارش', 'Order summary'),
              shippingLabel: tr('ارسال', 'Shipping'),
              freeLabel: tr('رایگان', 'Free'),
              vatLabel: tr('مالیات ({rate}٪)', 'VAT ({rate}%)'),
              totalLabel: tr('جمع', 'Total'),
              asideHint: tr('ارسال از {amount} رایگان است — پله‌ها همه‌چیز را همان لحظه دوباره می‌شمارند.', 'Shipping is free from {amount} — the steppers recompute everything instantly.'),
              decreaseLabel: tr('کاهش تعداد', 'Decrease quantity'),
              increaseLabel: tr('افزایش تعداد', 'Increase quantity'),
              removeLabel: tr('حذف قلم', 'Remove item'),
            }}
          />
          {paid && (
            <p className="sc-demo-title" style={{ fontWeight: 500 }}>
              {tr(`سفارش ${paid} ثبت شد (کالبک onPaid).`, `Order ${paid} captured (the onPaid callback).`)}
            </p>
          )}
        </Demo>

        <Demo wide title={tr('دو قدم، شش خانه، یک تایمر روراست', 'Two steps, six boxes, one honest timer')} bare center>
          <FlowAuthCode
            defaultEmail=""
            hintCode="284913"
            verify={(code) => code === '284913'}
            words={{
              continueLabel: tr('ادامه با کد', 'Continue with code'),
              errEmail: tr('این ایمیل خوانده نمی‌شود', 'This email does not parse'),
              codeTitle: tr('کد را وارد کنید', 'Enter your code'),
              codeLead: tr('فرستاده شده به {email} — تایپش کنید یا هر شش رقم را یک‌جا بچسبانید.', 'Sent to {email} — type it, or paste all six at once.'),
              hint: tr('کد دمو:', 'demo code:'),
              verifyLabel: tr('بررسی', 'Verify'),
              wrongCode: tr('کد درست نیست — کدها ۱۰ دقیقه زنده‌اند', 'That code is not right — codes live for 10 minutes'),
              resendIn: tr('ارسال دوباره تا {s} ثانیه', 'Resend in {s}s'),
              resendLabel: tr('ارسال دوبارهٔ کد', 'Resend code'),
              resentLabel: tr('دوباره فرستاده شد ✓', 'Sent again ✓'),
              backLabel: tr('بازگشت به ورود', 'Back to sign in'),
              doneTitle: tr('خوش بازگشتی، مایا', 'Welcome back, Maya'),
              doneText: tr('این دستگاه امن شناخته شد — دفعهٔ بعد شاید کد را لازم نداشته باشید.', 'Device trusted — next time we may skip the code.'),
              signOutLabel: tr('خروج', 'Sign out'),
            }}
          />
        </Demo>
      </div>
    </Section>
  );
}
