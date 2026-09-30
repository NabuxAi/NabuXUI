/**
 * /#/chat — the conversations page: the chat block over seeded data (times
 * hang off the AGO anchors in ../data, so the day separators and the relative
 * column stamps stay honest on every load). Two unread conversations carry
 * the three unread messages the sidebar badge counts; one thread is typing so
 * the indicator is one click away. Sending echoes into the thread locally —
 * there is no backend in the demo panel.
 */
import { Chat, type ChatConversation } from '@nabuxai/ui-react';
import { useStrings, useTr } from '../lang';
import { AGO } from '../data';

export function ChatPage() {
  const c = useStrings().pages.chat;
  const tr = useTr();

  const conversations: ChatConversation[] = [
    {
      id: 'maryam',
      name: tr('مریم رضایی', 'Maryam Rezaei'),
      status: 'online',
      role: tr('مشتری · سفارش ۱۲۴۸', 'Customer · order #1248'),
      preview: tr('سلام! سفارشم کی به دستم می‌رسد؟', 'Hi! When will my order arrive?'),
      time: AGO.minutes18.at,
      unread: 2,
      messages: [
        { id: 'm1', side: 'in', text: tr('سلام! سفارش شمارهٔ ۱۲۴۸ را ثبت کردم.', 'Hi! I placed order #1248.'), time: AGO.hours2.at },
        { id: 'm2', side: 'in', text: tr('بسته‌بندی هدیه هم امکان‌پذیر است؟', 'Is gift wrapping possible?'), time: AGO.hours2.at },
        { id: 'm3', side: 'out', text: tr('سلام مریم‌جان، بله! همراه با یک کارت تبریک می‌فرستیم.', 'Hi Maryam, yes! We will include a greeting card.'), time: AGO.hour1.at },
        { id: 'm4', side: 'in', text: tr('عالی‌ست. کی به دستم می‌رسد؟', 'Perfect. When will it arrive?'), time: AGO.minutes18.at },
      ],
    },
    {
      id: 'ali',
      name: tr('علی نیک‌پور', 'Ali Nikpour'),
      status: 'busy',
      role: tr('تیم پشتیبانی', 'Support team'),
      preview: tr('گزارش این هفته را می‌فرستم…', 'Sending this week’s report…'),
      time: AGO.minutes3.at,
      unread: 1,
      typing: true,
      messages: [
        { id: 'a1', side: 'out', text: tr('علی، گزارش گفت‌وگوهای این هفته آماده است؟', 'Ali, is this week’s conversation report ready?'), time: AGO.hour1.at },
        { id: 'a2', side: 'in', text: tr('تقریباً؛ فقط نمودارها مانده.', 'Almost; only the charts are left.'), time: AGO.minutes3.at },
      ],
    },
    {
      id: 'sara',
      name: tr('سارا احمدی', 'Sara Ahmadi'),
      status: 'away',
      role: tr('مدیر محصول', 'Product manager'),
      preview: tr('جلسهٔ بازبینی ساعت ۱۶ لغو شد.', 'The 4 o’clock review is cancelled.'),
      time: AGO.hours5.at,
      messages: [
        { id: 's1', side: 'in', text: tr('یادآوری: بازبینی طراحی فردا ساعت ۱۰.', 'Reminder: the design review tomorrow at 10.'), time: AGO.day1.at },
        { id: 's2', side: 'out', text: tr('هستم 👌', 'I’ll be there 👌'), time: AGO.day1.at },
        { id: 's3', side: 'in', text: tr('جلسهٔ امروز ساعت ۱۶ لغو شد؛ فردا جبرانی می‌گیریم.', 'Today’s 4 o’clock meeting is cancelled; we will make up for it tomorrow.'), time: AGO.hours5.at },
      ],
    },
    {
      id: 'acme',
      name: tr('حسابداری آدم', 'Acme accounts'),
      status: 'offline',
      role: tr('حسابداری', 'Accounts'),
      preview: tr('فاکتور INV-2026-118 پرداخت شد.', 'Invoice INV-2026-118 was paid.'),
      time: AGO.day1.at,
      messages: [
        { id: 'ac1', side: 'in', text: tr('فاکتور این ماه واریز شد.', 'This month’s invoice was transferred.'), time: AGO.day1.at },
        { id: 'ac2', side: 'out', text: tr('واریزی را دریافت کردیم، ممنون! رسید را فرستادیم.', 'We received the transfer, thank you! The receipt is on its way.'), time: AGO.day1.at },
      ],
    },
    {
      id: 'mina',
      name: tr('مینا کریمی', 'Mina Karimi'),
      status: 'offline',
      role: tr('مشتری', 'Customer'),
      preview: tr('کیف چرمی نابو عالی بود!', 'The Nabu leather bag was perfect!'),
      time: AGO.days2.at,
      messages: [
        { id: 'mi1', side: 'in', text: tr('بسته رسید — کیف چرمی نابو واقعاً عالی بود!', 'The parcel arrived — the Nabu leather bag is really perfect!'), time: AGO.days2.at },
        { id: 'mi2', side: 'out', text: tr('خوشحالیم که پسندیدید 🌟', 'So glad you love it 🌟'), time: AGO.days2.at },
      ],
    },
  ];

  return (
    <Chat
      className="adm-chat"
      height="38rem"
      conversations={conversations}
      searchPlaceholder={c.search}
      placeholder={c.placeholder}
      labels={{
        conversations: c.conversations,
        messages: c.messages,
        messagePlaceholder: c.placeholder,
        typing: tr('{name} در حال نوشتن…', '{name} is typing…'),
        unread: c.unread,
        empty: c.emptyTitle,
        noMessages: tr('هنوز پیامی نیست؛ اولین را بنویسید.', 'No messages yet; write the first one.'),
      }}
      aria-label={c.title}
    />
  );
}
