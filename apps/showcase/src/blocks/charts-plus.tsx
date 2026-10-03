import { useState } from 'react';
import {
  BrushChart,
  Button,
  ChartLoading,
  ComposedChart,
  ForecastChart,
  RadarChart,
  RadialBarChart,
  SegmentedControl,
  StackedAreaChart,
  StackedBarChart,
} from '@nabuxai/ui-react';
import { Demo, Section } from '../Section';
import { useLang, useTr } from '../lang';

const MONTHS = {
  fa: ['فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر', 'آبان', 'آذر', 'دی', 'بهمن', 'اسفند'],
  en: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'],
};

const DAU = Array.from({ length: 90 }, (_, i) => {
  const weekly = [0.92, 1, 1.06, 1.08, 1.04, 0.86, 0.78][i % 7]!;
  const launch = i >= 61 && i <= 66 ? 1 + (6 - Math.abs(63 - i)) * 0.07 : 1;
  return Math.round((4200 + i * 38) * weekly * launch);
});

export function ChartsPlusBlocks() {
  const tr = useTr();
  const lang = useLang();
  const months = MONTHS[lang];
  const [mode, setMode] = useState<'stacked' | 'percent' | 'expanded'>('stacked');
  const [barMode, setBarMode] = useState<'stacked' | 'percent' | 'grouped'>('stacked');
  const [loading, setLoading] = useState(false);
  const days = Array.from({ length: 90 }, (_, i) => `${tr('روز', 'Day')} ${(i + 1).toLocaleString(lang === 'fa' ? 'fa-IR' : 'en-US')}`);

  const traffic = [
    { name: tr('جست‌وجو', 'Search'), values: [42, 46, 51, 49, 55, 61, 66, 64, 70, 74, 79, 86] },
    { name: tr('مستقیم', 'Direct'), values: [28, 27, 30, 33, 31, 34, 36, 39, 38, 41, 43, 44] },
    { name: tr('شبکه‌های اجتماعی', 'Social'), values: [12, 15, 19, 26, 31, 28, 25, 29, 34, 38, 36, 41] },
    { name: tr('ایمیل', 'Email'), values: [8, 9, 9, 10, 12, 11, 13, 14, 13, 15, 17, 18] },
  ];

  return (
    <Section
      id="charts-plus-blocks"
      eyebrow={tr('بلوک‌ها', 'Blocks')}
      title={tr('نمودارهای پیشرفته', 'Advanced charts')}
      description={tr(
        'انباشته، درصدی و جریانی؛ میله و خط با دو محور؛ زوم با انتخاب بازه؛ پیش‌بینی با نوار اطمینان؛ رادار، حلقه‌های پیشرفت و اسکلت بارگذاری — همه SVG، با نوار ابزار شیشه‌ای، کیبورد و جدول پنهانِ همان داده.',
        'Stacked, percent and stream; bars and a line on two axes; brush zoom; a forecast with its confidence band; radar, radial progress and a loading skeleton — all SVG, with a frosted tooltip, keyboard reading and a hidden table of the same data.',
      )}
      code={{
        react: `<StackedAreaChart labels={months} series={traffic} mode="percent" fill="hatched" markers />
<StackedBarChart labels={teams} series={tickets} orientation="horizontal" mode="percent" totals />
<ComposedChart labels={months} bars={[revenue]} lines={[conversion]} lineFormat={{ style: 'percent' }} />
<BrushChart labels={days} series={[dau]} defaultValue={[56, 89]} onValueChange={setRange} />
<ForecastChart labels={months} actual={sales} forecast={projection} lower={low} upper={high} animated />
<RadarChart axes={skills} series={releases} max={100} rings={5} />
<RadialBarChart data={[{ label: 'Revenue', value: 82 }, { label: 'Users', value: 1240, max: 2000 }]} />
<ChartLoading variant="area" />`,
        blade: `<x-nx::stacked-area :labels="$months" :series="$traffic" mode="percent" fill="hatched" markers />
<x-nx::stacked-bar :labels="$teams" :series="$tickets" orientation="horizontal" mode="percent" totals />
<x-nx::composed-chart :labels="$months" :bars="[$revenue]" :lines="[$conversion]" :line-format="['style' => 'percent']" />
<x-nx::brush-chart :labels="$days" :series="[$dau]" :range="[56, 89]" x-on:nx-range="…" />
<x-nx::forecast-line :labels="$months" :actual="$sales" :forecast="$projection" :lower="$low" :upper="$high" animated />
<x-nx::radar-chart :axes="$skills" :series="$releases" :max="100" rings="5" />
<x-nx::radial-bar :data="$goals" />
<x-nx::chart-loading variant="area" />`,
      }}
    >
      <div className="sc-demos">
        <Demo wide>
          <div className="sc-row">
            <SegmentedControl
              aria-label={tr('حالت انباشته', 'Stack mode')}
              size="sm"
              value={mode}
              onValueChange={(v) => setMode(v as typeof mode)}
              options={[
                { value: 'stacked', label: tr('انباشته', 'Stacked') },
                { value: 'percent', label: tr('درصدی', 'Percent') },
                { value: 'expanded', label: tr('جریانی', 'Stream') },
              ]}
            />
          </div>
          <StackedAreaChart title={tr('بازدید به تفکیک کانال', 'Visits by channel')} subtitle={tr('هزار بازدید', 'Thousands')} labels={months} series={traffic} mode={mode} markers={mode === 'stacked'} />
        </Demo>

        <Demo>
          <div className="sc-row">
            <SegmentedControl
              aria-label={tr('حالت میله', 'Bar mode')}
              size="sm"
              value={barMode}
              onValueChange={(v) => setBarMode(v as typeof barMode)}
              options={[
                { value: 'stacked', label: tr('انباشته', 'Stacked') },
                { value: 'percent', label: '۱۰۰٪' },
                { value: 'grouped', label: tr('گروهی', 'Grouped') },
              ]}
            />
          </div>
          <StackedBarChart
            title={tr('سفارش‌ها', 'Orders')}
            mode={barMode}
            totals={barMode !== 'grouped'}
            labels={tr('ش,ی,د,س,چ,پ,ج', 'Sat,Sun,Mon,Tue,Wed,Thu,Fri').split(',')}
            series={[
              { name: tr('تحویل‌شده', 'Delivered'), values: [182, 164, 198, 211, 236, 274, 158] },
              { name: tr('در راه', 'In transit'), values: [44, 52, 47, 58, 61, 72, 39] },
              { name: tr('مرجوعی', 'Returned'), values: [9, 7, 12, 8, 11, 14, 6] },
            ]}
          />
        </Demo>

        <Demo>
          <StackedBarChart
            title={tr('نتیجهٔ تیکت‌ها', 'Ticket outcomes')}
            orientation="horizontal"
            mode="percent"
            fill="hatched"
            labels={tr('فروش,فنی,مالی,حساب کاربری', 'Sales,Technical,Billing,Accounts').split(',')}
            series={[
              { name: tr('حل‌شده', 'Solved'), values: [412, 286, 158, 121] },
              { name: tr('در انتظار', 'Waiting'), values: [61, 94, 22, 18] },
              { name: tr('ارجاع‌شده', 'Escalated'), values: [12, 48, 9, 5] },
            ]}
          />
        </Demo>

        <Demo wide>
          <ComposedChart
            title={tr('درآمد و نرخ تبدیل', 'Revenue & conversion')}
            labels={months.slice(0, 8)}
            bars={[{ name: tr('درآمد', 'Revenue'), values: [412, 456, 498, 471, 532, 588, 610, 664] }]}
            lines={[{ name: tr('نرخ تبدیل', 'Conversion'), values: [0.021, 0.024, 0.026, 0.023, 0.029, 0.031, 0.03, 0.034] }]}
            barAxis={tr('میلیون تومان', 'M toman')}
            lineAxis={tr('نرخ', 'Rate')}
            lineFormat={{ style: 'percent', maximumFractionDigits: 1 }}
            markers
          />
        </Demo>

        <Demo wide>
          <BrushChart title={tr('کاربران فعال روزانه', 'Daily active users')} labels={days} series={[{ name: tr('کاربر فعال', 'Active users'), values: DAU }]} defaultValue={[56, 89]} minSpan={6} />
        </Demo>

        <Demo wide>
          <ForecastChart
            title={tr('فروش ماهانه', 'Monthly sales')}
            subtitle={tr('میلیون تومان · نوار اطمینان ۸۰٪', 'Million tomans · 80% band')}
            labels={months}
            actual={[318, 342, 336, 371, 398, 384]}
            forecast={[412, 431, 455, 472, 498, 521]}
            lower={[396, 404, 417, 421, 432, 441]}
            upper={[428, 458, 493, 523, 564, 601]}
            animated
          />
        </Demo>

        <Demo>
          <RadarChart
            title={tr('مدل پشتیبانی', 'Support model')}
            max={100}
            rings={5}
            axes={tr('سرعت,دقت,هزینه,فارسی,کدنویسی,ایمنی', 'Speed,Accuracy,Cost,Persian,Coding,Safety').split(',')}
            series={[
              { name: tr('نسخهٔ ۲', 'v2'), values: [88, 92, 64, 95, 81, 90] },
              { name: tr('نسخهٔ ۱', 'v1'), values: [72, 80, 78, 70, 66, 84] },
            ]}
          />
        </Demo>

        <Demo>
          <RadialBarChart
            title={tr('اهداف فصل', 'Quarter goals')}
            centerLabel={tr('در مسیر', 'On track')}
            data={[
              { label: tr('درآمد', 'Revenue'), value: 82 },
              { label: tr('کاربر تازه', 'New users'), value: 1240, max: 2000, hint: tr('هدف: ۲٬۰۰۰', 'Target 2,000') },
              { label: tr('رضایت', 'Satisfaction'), value: 4.6, max: 5 },
              { label: tr('پوشش مستندات', 'Docs coverage'), value: 57 },
            ]}
          />
        </Demo>

        <Demo wide title={tr('اسکلت بارگذاری', 'Loading skeleton')}>
          <div className="sc-row">
            <Button size="sm" variant="secondary" onClick={() => setLoading((v) => !v)}>
              {loading ? tr('داده رسید', 'Data arrived') : tr('بارگذاری دوباره', 'Reload')}
            </Button>
          </div>
          {loading ? (
            <ChartLoading variant="area" height="240px" title={tr('نشست‌ها به تفکیک دستگاه', 'Sessions by device')} />
          ) : (
            <StackedAreaChart
              title={tr('نشست‌ها به تفکیک دستگاه', 'Sessions by device')}
              height={240}
              labels={tr('ش,ی,د,س,چ,پ,ج', 'Sat,Sun,Mon,Tue,Wed,Thu,Fri').split(',')}
              series={[
                { name: tr('موبایل', 'Mobile'), values: [820, 760, 910, 880, 940, 1010, 690] },
                { name: tr('دسکتاپ', 'Desktop'), values: [410, 520, 560, 540, 500, 380, 260] },
              ]}
            />
          )}
          <div style={{ display: 'grid', gridTemplateColumns: 'repeat(auto-fit, minmax(12rem, 1fr))', gap: '1.25rem' }}>
            <ChartLoading variant="line" height="7rem" />
            <ChartLoading variant="bar" height="7rem" />
            <ChartLoading variant="donut" height="7rem" />
          </div>
        </Demo>
      </div>
    </Section>
  );
}
