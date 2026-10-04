// @vitest-environment happy-dom
import { describe, expect, it, vi } from 'vitest';
import {
  createOdometer,
  fillMessage,
  moveItem,
  odometerDiff,
  odometerSteps,
  passwordStrength,
  ropeStep,
  rubberBand,
  sortShifts,
  sortTargetIndex,
  sortable,
} from '../src/js/blocks/interact';
import { localeDigits, numberParts } from '../src/js/text';

describe('passwordStrength', () => {
  it('scores nothing as zero with every rule unmet', () => {
    const result = passwordStrength('');
    expect(result.score).toBe(0);
    expect(result.entropy).toBe(0);
    expect(result.rules.every((rule) => !rule.met)).toBe(true);
  });

  it('ticks the rules off one by one', () => {
    const met = (pw: string) => Object.fromEntries(passwordStrength(pw).rules.map((r) => [r.id, r.met]));
    expect(met('abc')).toEqual({ length: false, lower: true, upper: false, number: false, symbol: false });
    expect(met('Abcdefg1!')).toEqual({ length: true, lower: true, upper: true, number: true, symbol: true });
    expect(met('۱۲۳۴')).toMatchObject({ number: true });
  });

  it('treats common passwords, repeats and runs as weak', () => {
    expect(passwordStrength('password').score).toBe(0);
    expect(passwordStrength('12345678').score).toBe(0);
    expect(passwordStrength('aaaaaaaaaaaaaaaa').score).toBe(0);
    expect(passwordStrength('Password1').score).toBeLessThanOrEqual(1);
  });

  it('rewards length and variety', () => {
    expect(passwordStrength('Tr0ub4dor&3x').score).toBeGreaterThanOrEqual(3);
    expect(passwordStrength('correct horse battery staple').score).toBe(4);
    const short = passwordStrength('mK8#q');
    expect(short.score).toBeLessThanOrEqual(1);
  });

  it('caps passwords shorter than minLength at weak', () => {
    expect(passwordStrength('x7#Kq!vR2', { minLength: 12 }).score).toBeLessThanOrEqual(1);
  });

  it('penalises words taken from the user’s own details', () => {
    const plain = passwordStrength('hosseinrahimi!');
    const personal = passwordStrength('hosseinrahimi!', { userInputs: ['Hossein Rahimi', 'hossein@example.com'] });
    expect(personal.entropy).toBeLessThan(plain.entropy);
    expect(personal.score).toBeLessThan(plain.score);
  });

  it('counts Persian letters toward the pool', () => {
    expect(passwordStrength('سلام-دنیا-۱۴۰۳-بهار').score).toBeGreaterThanOrEqual(3);
  });
});

describe('odometer', () => {
  it('walks the wheel up or down, wrapping past 9', () => {
    expect(odometerSteps(7, 2, true)).toEqual([7, 8, 9, 0, 1, 2]);
    expect(odometerSteps(2, 7, false)).toEqual([2, 1, 0, 9, 8, 7]);
    expect(odometerSteps(4, 4, true)).toEqual([4]);
  });

  it('aligns columns on the units and leaves unchanged digits still', () => {
    const cols = odometerDiff(numberParts(1204, 'en'), numberParts(1209, 'en'), true);
    expect(cols.map((c) => c.steps.length)).toEqual([1, 1, 1, 1, 6]);
    expect(cols[4]!.steps).toEqual(['4', '5', '6', '7', '8', '9']);
    expect(cols[1]!).toEqual({ kind: 'static', steps: [','] });
  });

  it('rolls new columns in from blank and old ones out to blank', () => {
    const grow = odometerDiff(numberParts(999, 'en'), numberParts(1000, 'en'), true);
    expect(grow.map((c) => c.kind)).toEqual(['digit', 'static', 'digit', 'digit', 'digit']);
    expect(grow[0]!.steps).toEqual(['', '1']);
    expect(grow[1]!.steps).toEqual(['', ',']);
    expect(grow[4]!.steps).toEqual(['9', '0']);

    const shrink = odometerDiff(numberParts(102, 'en'), numberParts(98, 'en'), false);
    expect(shrink[0]!.steps).toEqual(['1', '']);
    expect(shrink[2]!.steps).toEqual(['2', '1', '0', '9', '8']);
  });

  it('speaks the locale’s digits', () => {
    const digits = localeDigits('fa');
    const cols = odometerDiff(numberParts(18, 'fa'), numberParts(21, 'fa'), true, digits);
    expect(cols[0]!.steps).toEqual(['۱', '۲']);
    expect(cols[1]!.steps).toEqual(['۸', '۹', '۰', '۱']);
  });

  it('renders the formatted value once for assistive tech and the columns hidden', () => {
    const el = document.createElement('span');
    document.body.appendChild(el);
    const odo = createOdometer(el, { value: 1284, locale: 'en' });
    expect(el.querySelector('.nx-odometer-sr')!.textContent).toBe('1,284');
    const roll = el.querySelector('.nx-odometer-roll')!;
    expect(roll.getAttribute('aria-hidden')).toBe('true');
    expect(Array.from(roll.querySelectorAll('.nx-odometer-cell'), (c) => c.textContent).join('')).toBe('1,284');
    odo.update(1300);
    expect(el.querySelector('.nx-odometer-sr')!.textContent).toBe('1,300');
    odo.destroy();
  });
});

describe('reorder logic', () => {
  it('moves an item without touching the original', () => {
    const list = ['a', 'b', 'c', 'd'];
    expect(moveItem(list, 0, 2)).toEqual(['b', 'c', 'a', 'd']);
    expect(moveItem(list, 3, 0)).toEqual(['d', 'a', 'b', 'c']);
    expect(moveItem(list, 1, 9)).toEqual(['a', 'c', 'd', 'b']);
    expect(list).toEqual(['a', 'b', 'c', 'd']);
  });

  it('finds the landing index from the dragged centre', () => {
    const centers = [20, 70, 120, 170];
    expect(sortTargetIndex(centers, 0, 20)).toBe(0);
    expect(sortTargetIndex(centers, 0, 95)).toBe(1);
    expect(sortTargetIndex(centers, 0, 200)).toBe(3);
    expect(sortTargetIndex(centers, 3, 10)).toBe(0);
    expect(sortTargetIndex(centers, 2, 100)).toBe(2);
  });

  it('shifts only the items between origin and target', () => {
    expect(sortShifts(4, 0, 2, 50)).toEqual([0, -50, -50, 0]);
    expect(sortShifts(4, 3, 1, 50)).toEqual([0, 50, 50, 0]);
    expect(sortShifts(3, 1, 1, 50)).toEqual([0, 0, 0]);
  });

  it('damps a drag past the bounds and fills message templates', () => {
    expect(rubberBand(0)).toBe(0);
    expect(rubberBand(1000, 48)).toBeLessThan(48);
    expect(rubberBand(20, 48)).toBeLessThan(20);
    expect(fillMessage('{name} at {position} of {total}', { name: 'Tea', position: 2, total: 5 })).toBe('Tea at 2 of 5');
  });

  it('reorders from the keyboard and reports the new order', async () => {
    const root = document.createElement('div');
    root.innerHTML = `<ol>${['a', 'b', 'c']
      .map((k) => `<li data-nx-sort-key="${k}" data-nx-sort-label="Item ${k}"><button data-nx-sort-handle>${k}</button></li>`)
      .join('')}</ol><span data-nx-sort-live></span>`;
    document.body.appendChild(root);
    const onChange = vi.fn();
    const stop = sortable(root, { onChange });
    const handle = root.querySelector<HTMLButtonElement>('[data-nx-sort-key="a"] button')!;
    const press = (key: string) => root.querySelector<HTMLElement>('[data-nx-sort-key="a"] button')!.dispatchEvent(new KeyboardEvent('keydown', { key, bubbles: true }));
    press(' ');
    expect(handle.getAttribute('aria-pressed')).toBe('true');
    press('ArrowDown');
    press('ArrowDown');
    expect(Array.from(root.querySelectorAll('li'), (li) => li.getAttribute('data-nx-sort-key'))).toEqual(['b', 'c', 'a']);
    press(' ');
    expect(onChange).toHaveBeenCalledWith(['b', 'c', 'a']);
    stop();
    root.remove();
  });
});

describe('ropeStep', () => {
  const hanging = (count: number, gap: number) =>
    Array.from({ length: count }, (_, i) => ({ x: 0, y: i * gap, px: 0, py: i * gap }));

  it('keeps the pin nailed to the ceiling', () => {
    const points = hanging(6, 10);
    for (let i = 0; i < 30; i++) ropeStep(points, { segmentLength: 10 });
    expect(points[0]).toEqual({ x: 0, y: 0, px: 0, py: 0 });
  });

  it('settles into a chain of near-equal segments', () => {
    const points = hanging(8, 10);
    // Kick the middle so the rope has real motion to settle from.
    points[4]!.x += 24;
    for (let i = 0; i < 240; i++) ropeStep(points, { segmentLength: 10 });
    const gaps = points.slice(1).map((p, i) => Math.hypot(p.x - points[i]!.x, p.y - points[i]!.y));
    for (const gap of gaps) expect(Math.abs(gap - 10)).toBeLessThan(0.5);
  });

  it('drops a pushed rope and catches it at the pinned end', () => {
    const points = hanging(5, 10);
    points[3]!.x += 30; // a sideways shove
    for (let i = 0; i < 120; i++) ropeStep(points, { segmentLength: 10 });
    // The tail hangs no higher than where the chain can reach: pin + 4 segments.
    const tail = points[points.length - 1]!;
    expect(tail.y).toBeLessThanOrEqual(40.5);
    expect(tail.y).toBeGreaterThan(0);
  });
});
