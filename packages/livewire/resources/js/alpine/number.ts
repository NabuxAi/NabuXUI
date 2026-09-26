/**
 * Rolling numbers in the DOM: update each digit column's --d so it rolls, and
 * rebuild the columns only when the number's shape changes (a new digit, a
 * separator). The markup matches what the Blade component renders server-side.
 */
import { localeDigits, numberParts } from '@nabuxai/ui-core';

export function renderNumber(el: HTMLElement, value: number, locale?: string, format?: Intl.NumberFormatOptions): void {
  const parts = numberParts(value, locale, format);
  const text = parts.map((p) => p.char).join('');
  const label = el.querySelector('.nx-visually-hidden');
  if (label) label.textContent = text;

  const roll = el.querySelector<HTMLElement>('.nx-number-roll');
  if (!roll) return;

  const current = Array.from(roll.children) as HTMLElement[];
  const sameShape = current.length === parts.length && parts.every((p, i) => current[i]!.classList.contains(p.kind === 'digit' ? 'nx-digit' : 'nx-number-sep'));

  if (sameShape) {
    parts.forEach((part, i) => {
      const node = current[i]!;
      if (part.kind === 'digit') node.style.setProperty('--d', String(part.value));
      else node.textContent = part.char;
    });
    return;
  }

  const digits = localeDigits(locale);
  roll.replaceChildren(
    ...parts.map((part, i) => {
      if (part.kind === 'static') {
        const sep = document.createElement('span');
        sep.className = 'nx-number-sep';
        sep.textContent = part.char;
        return sep;
      }
      const column = document.createElement('span');
      column.className = 'nx-digit';
      column.style.setProperty('--nx-p', String(parts.length - 1 - i));
      column.style.setProperty('--d', '0');
      const track = document.createElement('span');
      track.className = 'nx-digit-track';
      for (const digit of digits) {
        const cell = document.createElement('span');
        cell.textContent = digit;
        track.appendChild(cell);
      }
      column.appendChild(track);
      // New columns roll in from zero on the next frame.
      requestAnimationFrame(() => requestAnimationFrame(() => column.style.setProperty('--d', String(part.value))));
      return column;
    }),
  );
}
