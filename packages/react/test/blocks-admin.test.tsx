// @vitest-environment happy-dom
/**
 * The panel blocks (admin-shell, auth, calendar, chat, empty, invoice,
 * kanban, timeline-feed): each one mounts, renders its data through the
 * token/word plumbing, and honours its core interactions.
 */
import { afterEach, describe, expect, it, vi } from 'vitest';
import { cleanup, fireEvent, render, screen, within } from '@testing-library/react';
import { AdminShell, AdminSidebar, AdminTopbar } from '../src/blocks/admin-shell';
import { AuthCard } from '../src/blocks/auth';
import { Calendar, monthGrid, shiftMonth } from '../src/blocks/calendar';
import { Chat } from '../src/blocks/chat';
import { EmptyState } from '../src/blocks/empty';
import { Invoice } from '../src/blocks/invoice';
import { Kanban } from '../src/blocks/kanban';
import { TimelineFeed } from '../src/blocks/timeline-feed';

afterEach(cleanup);

describe('shiftMonth', () => {
  it('steps within the year and across its edges', () => {
    expect(shiftMonth('2026-10', 1)).toBe('2026-11');
    expect(shiftMonth('2026-12', 1)).toBe('2027-01');
    expect(shiftMonth('2026-01', -1)).toBe('2025-12');
  });
});

describe('monthGrid', () => {
  it('covers October 2026 in five Sunday-start weeks, edges included', () => {
    const grid = monthGrid('2026-10', 0);
    expect(grid).toHaveLength(5);
    expect(grid.every((week) => week.length === 7)).toBe(true);
    const flat = grid.flat();
    expect(flat[0]).toBe('2026-09-27'); // the Sunday before Oct 1 (a Thursday)
    expect(flat.at(-1)).toBe('2026-10-31');
    // Every day of the month appears exactly once.
    expect(flat.filter((iso) => iso.startsWith('2026-10'))).toHaveLength(31);
  });
});

describe('AdminSidebar', () => {
  const groups = [
    {
      id: 'general',
      label: 'General',
      items: [
        { id: 'dash', label: 'Dashboard' },
        { id: 'orders', label: 'Orders', badge: 1284 },
      ],
    },
  ];

  it('renders the nav, formats number badges, and marks the active item', () => {
    render(<AdminSidebar groups={groups} defaultValue="orders" navLabel="Admin" />);
    const nav = screen.getByRole('navigation', { name: 'Admin' });
    expect(within(nav).getByText('Dashboard')).toBeTruthy();
    expect(within(nav).getByText('1,284')).toBeTruthy(); // en grouping
    const active = within(nav).getByText('Orders').closest('button');
    const idle = within(nav).getByText('Dashboard').closest('button');
    expect(active?.getAttribute('aria-current')).toBe('page');
    expect(idle?.getAttribute('aria-current')).toBeNull();
  });

  it('reports the picked item', () => {
    const onValueChange = vi.fn();
    render(<AdminSidebar groups={groups} defaultValue="dash" onValueChange={onValueChange} />);
    fireEvent.click(screen.getByText('Orders'));
    expect(onValueChange).toHaveBeenCalledWith('orders');
  });
});

describe('AdminTopbar', () => {
  it('renders the heading pair and the signed-in user', () => {
    render(<AdminTopbar title="Overview" subtitle="Yesterday at a glance" user={{ name: 'Maryam Razi', role: 'Owner' }} />);
    expect(screen.getByText('Overview')).toBeTruthy();
    expect(screen.getByText('Yesterday at a glance')).toBeTruthy();
    expect(screen.getByRole('button', { name: 'Maryam Razi' })).toBeTruthy();
    expect(screen.getByText('Owner')).toBeTruthy();
  });
});

describe('AdminShell', () => {
  it('composes the frame: sidebar, topbar, content, and the mobile drawer', () => {
    render(
      <AdminShell
        sidebar={<AdminSidebar groups={[{ items: [{ id: 'dash', label: 'Dashboard' }] }]} defaultValue="dash" />}
        title="Overview"
        user={{ name: 'Maryam Razi' }}
      >
        <p>KPI cards live here</p>
      </AdminShell>,
    );
    // The sidebar node is rendered twice: in the rail and inside the mobile drawer.
    expect(screen.getAllByRole('navigation')).toHaveLength(2);
    expect(screen.getByText('KPI cards live here')).toBeTruthy();
    expect(screen.getByText('Overview')).toBeTruthy();
    expect(document.querySelector('.nx-admin-drawer')).toBeTruthy();
  });
});

describe('AuthCard', () => {
  it('renders the login pane with the brand panel beside it', () => {
    const { container } = render(<AuthCard brand="Nabu Panel" tagline="Design ops, calm" mode="login" />);
    expect(container.querySelector('form.nx-auth-card')).toBeTruthy();
    expect(screen.getByText('Nabu Panel')).toBeTruthy();
    expect(screen.getByText('Design ops, calm')).toBeTruthy();
    // The active pane is kept in sync with a hidden measure clone, so its words appear twice.
    expect(screen.getAllByText('Welcome back').length).toBeGreaterThanOrEqual(1); // i18n.en.authLoginTitle
  });

  it('switches pane words with the mode', () => {
    render(<AuthCard mode="register" />);
    expect(screen.getAllByText('Get started in minutes').length).toBeGreaterThanOrEqual(1); // i18n.en.authRegisterTitle
    expect(screen.getAllByText('Full name').length).toBeGreaterThanOrEqual(1); // the register-only field
  });
});

describe('Calendar', () => {
  it('renders the month grid, the selected day, and the chip cap with its tally', () => {
    render(
      <Calendar
        month="2026-10"
        value="2026-10-14"
        events={[
          { date: '2026-10-14', label: 'Standup' },
          { date: '2026-10-14', label: 'Design review' },
          { date: '2026-10-14', label: 'Retro' },
          { date: '2026-10-14', label: 'Party' },
        ]}
      />,
    );
    expect(screen.getByRole('grid')).toBeTruthy();
    const cells = screen.getAllByRole('gridcell');
    expect(cells).toHaveLength(35); // monthGrid('2026-10', 0): five Sunday-start weeks
    const selected = cells.find((cell) => cell.getAttribute('aria-selected') === 'true');
    expect(selected?.querySelector('button')?.getAttribute('data-date')).toBe('2026-10-14');
    expect(selected?.querySelectorAll('.nx-calendar-chip')).toHaveLength(3); // maxPerCell default
    expect(selected?.textContent).toContain('+1'); // the rolled-over fourth event
  });

  it('reports the picked day', () => {
    const onValueChange = vi.fn();
    const { container } = render(<Calendar month="2026-10" value="2026-10-14" onValueChange={onValueChange} />);
    fireEvent.click(container.querySelector('button[data-date="2026-10-20"]')!);
    expect(onValueChange).toHaveBeenCalledWith('2026-10-20');
  });
});

describe('Chat', () => {
  const conversations = [
    {
      id: 'c1',
      name: 'Sara Mohseni',
      role: 'Support lead',
      preview: 'The invoice is on its way',
      messages: [
        { id: 'm1', side: 'in' as const, text: 'Salam! The invoice is ready.', time: '2026-10-01T09:05:00Z' },
        { id: 'm2', side: 'out' as const, text: 'Great, thank you.', time: '2026-10-01T09:07:00Z' },
      ],
    },
    { id: 'c2', name: 'Payman Ashraf', preview: 'Seen' },
  ];

  it('renders the conversation list and the active thread', () => {
    render(<Chat conversations={conversations} defaultValue="c1" />);
    const list = screen.getByRole('listbox');
    const options = within(list).getAllByRole('option');
    expect(options).toHaveLength(2);
    expect(options.find((option) => option.getAttribute('aria-selected') === 'true')?.textContent).toContain('Sara Mohseni');
    const log = screen.getByRole('log');
    expect(within(log).getByText('Salam! The invoice is ready.')).toBeTruthy();
    expect(within(log).getByText('Great, thank you.')).toBeTruthy();
  });

  it('sends the draft into the thread', () => {
    const onSend = vi.fn();
    const { container } = render(<Chat conversations={conversations} defaultValue="c1" onSend={onSend} />);
    fireEvent.change(container.querySelector<HTMLTextAreaElement>('.nx-chat-input')!, { target: { value: 'Sending right away' } });
    fireEvent.click(container.querySelector<HTMLButtonElement>('.nx-chat-send')!);
    expect(onSend).toHaveBeenCalledWith('Sending right away', 'c1');
    expect(screen.getByText('Sending right away')).toBeTruthy();
  });
});

describe('EmptyState', () => {
  it('renders the copy and the ways out, and fires the primary action', () => {
    const onClick = vi.fn();
    render(
      <EmptyState
        title="No invoices yet"
        description="Send your first invoice and it will appear here."
        action={{ label: 'New invoice', onClick }}
        secondaryAction={{ label: 'Import' }}
      />,
    );
    expect(screen.getByText('No invoices yet')).toBeTruthy();
    expect(screen.getByText('Send your first invoice and it will appear here.')).toBeTruthy();
    expect(screen.getByRole('button', { name: 'Import' })).toBeTruthy();
    fireEvent.click(screen.getByRole('button', { name: 'New invoice' }));
    expect(onClick).toHaveBeenCalledOnce();
  });
});

describe('Invoice', () => {
  it('renders parties, facts, lines, and the totals ladder', () => {
    render(
      <Invoice
        brand="Studio Nabu"
        number="INV-2026-041"
        issueDate="2026-09-28"
        status="paid"
        from={{ name: 'Studio Nabu Ltd', lines: ['Tehran, Vanak'] }}
        to={{ name: 'Acme Cloth Co.' }}
        lines={[
          { title: 'Design system audit', quantity: 2, unitPrice: 4_800_000 },
          { title: 'Motion pass', amount: 12_000_000 },
        ]}
        extraTotals={[{ label: 'Tax (9%)', percent: 0.09 }]}
        currency="تومان"
      />,
    );
    expect(screen.getByText('INV-2026-041')).toBeTruthy();
    expect(screen.getByText('Studio Nabu Ltd')).toBeTruthy();
    expect(screen.getByText('Tehran, Vanak')).toBeTruthy();
    expect(screen.getByText('Acme Cloth Co.')).toBeTruthy();
    expect(screen.getByText('Design system audit')).toBeTruthy();
    expect(screen.getByText('Motion pass')).toBeTruthy();
    expect(screen.getByText('Tax (9%)')).toBeTruthy();
    expect(screen.getByText('Subtotal')).toBeTruthy(); // i18n.en.invoiceSubtotal
    expect(screen.getByText('Total due')).toBeTruthy(); // i18n.en.invoiceTotal
  });
});

describe('Kanban', () => {
  it('renders the board with its columns and cards', () => {
    render(
      <Kanban
        label="Sprint board"
        defaultColumns={[
          { id: 'todo', title: 'To do', cards: [{ id: 'c1', title: 'Wire the invoice block', meta: 'Due Friday' }] },
          { id: 'doing', title: 'In progress', cards: [] },
          { id: 'done', title: 'Done', cards: [{ id: 'c2', title: 'Ship tokens' }] },
        ]}
      />,
    );
    expect(screen.getByText('To do')).toBeTruthy();
    expect(screen.getByText('In progress')).toBeTruthy();
    expect(screen.getByText('Done')).toBeTruthy();
    expect(screen.getByText('Wire the invoice block')).toBeTruthy();
    expect(screen.getByText('Due Friday')).toBeTruthy();
    expect(screen.getByText('Ship tokens')).toBeTruthy();
  });
});

describe('TimelineFeed', () => {
  it('renders the stream with its actors and targets', () => {
    render(
      <TimelineFeed
        label="Activity"
        entries={[
          { id: 'e1', actor: 'Sara Mohseni', text: 'merged', target: 'pull #214', href: '/pulls/214', time: '2026-10-01T08:00:00Z', tone: 'success' },
          { id: 'e2', actor: 'Payman Ashraf', text: 'commented on', target: 'invoice #31', time: '2026-09-30T18:00:00Z' },
        ]}
      />,
    );
    expect(document.querySelector('ol.nx-timeline-feed-list')?.getAttribute('aria-label')).toBe('Activity');
    expect(screen.getByText('Sara Mohseni')).toBeTruthy();
    expect(screen.getByText('merged')).toBeTruthy();
    expect(screen.getByText('pull #214')).toBeTruthy();
    expect(screen.getByText('Payman Ashraf')).toBeTruthy();
    expect(screen.getByText('invoice #31')).toBeTruthy();
  });
});
