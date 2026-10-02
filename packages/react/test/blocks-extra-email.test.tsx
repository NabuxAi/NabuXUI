// @vitest-environment happy-dom
/**
 * The email block: the folder rail, the message list (unread counts, stars,
 * keyboard walking) and the reading pane with its reply composer — mounted in
 * every locale the core table ships, plus one it does not know.
 */
import { afterEach, describe, expect, it, vi } from 'vitest';
import { cleanup, fireEvent, render, screen, within } from '@testing-library/react';
import type { Locale } from '@nabuxai/ui-core';
import { Email, type EmailMessage } from '../src/blocks/email';
import { NabuXUIProvider } from '../src/internal/provider';

afterEach(cleanup);

/** Times as plain labels, so no assertion depends on the runner's clock or ICU. */
const messages: EmailMessage[] = [
  {
    id: 'm1',
    from: { name: 'Sara Mohseni', email: 'sara@nabu.dev' },
    subject: 'Quarterly report',
    body: 'Numbers are in.\nSee the appendix.',
    time: '09:05',
    unread: true,
  },
  { id: 'm2', from: { name: 'Payman Ashraf' }, subject: 'Lunch?', body: 'Kabab at noon?', time: '08:30' },
  { id: 'm3', from: { name: 'Acme Corp' }, subject: 'Invoice paid', body: 'Thanks!', time: 'Yesterday', starred: true },
];

describe('Email', () => {
  it('renders the folder rail, the list, and the first inbox message as open', () => {
    render(<Email messages={messages} />);
    expect(screen.getByRole('navigation', { name: 'Folders' })).toBeTruthy(); // i18n.en.emailFolders
    for (const folder of ['Inbox', 'Starred', 'Sent', 'Drafts', 'Archive', 'Trash']) {
      expect(screen.getByText(folder)).toBeTruthy();
    }
    // The one unread inbox message is counted on the Inbox folder itself.
    const inbox = screen.getByText('Inbox').closest('button');
    expect(inbox?.textContent).toContain('1 unread'); // i18n.en.emailFolderUnread

    const list = screen.getByRole('listbox', { name: 'Messages' }); // i18n.en.emailMessages
    expect(within(list).getAllByRole('option')).toHaveLength(3);
    const open = within(list).getAllByRole('option').find((option) => option.getAttribute('aria-selected') === 'true');
    expect(open?.textContent).toContain('Quarterly report');

    // The reading pane carries the open message and its parties.
    expect(screen.getByText('From')).toBeTruthy(); // i18n.en.emailFrom
    expect(screen.getByText('Sara Mohseni · sara@nabu.dev')).toBeTruthy();
    // The first body line doubles as the list snippet, so it shows up twice.
    expect(screen.getAllByText('Numbers are in.')).toHaveLength(2);
    expect(screen.getByText('See the appendix.')).toBeTruthy();
    expect(screen.getByText('Reply to Sara Mohseni')).toBeTruthy(); // i18n.en.emailReplyTo
  });

  it('closes the reader when the open message stays behind, then opens and reads another', () => {
    const onOpenChange = vi.fn();
    const { container } = render(<Email messages={messages} onOpenChange={onOpenChange} />);

    // Leaving the folder closes the open message: the empty reader takes over.
    fireEvent.click(screen.getByText('Trash'));
    expect(onOpenChange).toHaveBeenCalledWith(null);
    expect(screen.getByText('Select a message to read it')).toBeTruthy(); // i18n.en.emailNoMessage

    // Back in the inbox the reader is still empty and m1 is still unread.
    fireEvent.click(screen.getByText('Inbox'));
    const row = screen.getByText('Quarterly report').closest('.nx-email-row');
    expect(row?.getAttribute('data-unread')).toBe('');
    fireEvent.click(screen.getByText('Quarterly report'));
    expect(onOpenChange).toHaveBeenLastCalledWith('m1');
    expect(row?.getAttribute('data-unread')).toBeNull(); // opening marks it read
    expect(screen.getByText('See the appendix.')).toBeTruthy(); // the body arrived
    expect((container.querySelector('textarea.nx-email-input') as HTMLTextAreaElement).disabled).toBe(false);
  });

  it('toggles stars and reports the new state', () => {
    const onStarredChange = vi.fn();
    render(<Email messages={messages} onStarredChange={onStarredChange} />);
    // Curly quotes come straight from the i18n table: Star “{subject}”. The row
    // and the reading pane each carry one; both follow the same state.
    const [star] = screen.getAllByRole('button', { name: 'Star “Quarterly report”' });
    expect(star.getAttribute('aria-pressed')).toBe('false');
    fireEvent.click(star);
    expect(onStarredChange).toHaveBeenCalledWith('m1', true);
    expect(star.getAttribute('aria-pressed')).toBe('true');
    expect(star.getAttribute('aria-label')).toBe('Unstar “Quarterly report”'); // i18n.en.emailUnstar
  });

  it('sends the draft into the open thread and speaks the send', () => {
    const onReply = vi.fn();
    const { container } = render(<Email messages={messages} onReply={onReply} />);
    const area = container.querySelector('textarea.nx-email-input') as HTMLTextAreaElement;
    const send = container.querySelector('button.nx-email-send') as HTMLButtonElement;
    expect(send.disabled).toBe(true); // nothing to send yet

    fireEvent.change(area, { target: { value: 'Thanks, noted.' } });
    expect(send.disabled).toBe(false);
    fireEvent.click(send);

    expect(onReply).toHaveBeenCalledWith('Thanks, noted.', expect.objectContaining({ id: 'm1' }));
    expect(area.value).toBe(''); // the draft clears
    expect(screen.getByRole('status').textContent).toBe('Reply sent to Sara Mohseni'); // i18n.en.emailReplySent
  });

  it('switches folders, keeping the starred set virtual and the empty folder honest', () => {
    const onFolderChange = vi.fn();
    render(<Email messages={messages} onFolderChange={onFolderChange} />);

    fireEvent.click(screen.getByText('Starred'));
    expect(onFolderChange).toHaveBeenCalledWith('starred');
    // On this fresh render only m3 came starred, so the virtual folder lists it alone.
    const options = screen.getAllByRole('option');
    expect(options).toHaveLength(1);
    expect(options[0]!.textContent).toContain('Invoice paid');

    fireEvent.click(screen.getByText('Trash'));
    expect(screen.getByText('Nothing here')).toBeTruthy(); // i18n.en.emailEmpty
  });

  it('lets custom folders replace the built-in rail', () => {
    render(<Email messages={messages} folders={[{ id: 'inbox', label: 'Posteingang' }, { id: 'work' }]} />);
    expect(screen.getByText('Posteingang')).toBeTruthy(); // the explicit label wins
    expect(screen.getByText('work')).toBeTruthy(); // an unknown id falls back to itself
    expect(screen.queryByText('Drafts')).toBeNull(); // the built-in six are gone
  });

  it('speaks Persian through the provider', () => {
    render(
      <NabuXUIProvider locale="fa">
        <Email messages={messages} />
      </NabuXUIProvider>,
    );
    expect(screen.getByRole('navigation', { name: 'پوشه‌ها' })).toBeTruthy(); // i18n.fa.emailFolders
    const inbox = screen.getByText('صندوق ورودی').closest('button'); // i18n.fa.emailInbox
    expect(inbox?.textContent).toContain('۱ خوانده‌نشده'); // i18n.fa.emailFolderUnread, Persian digits
    expect(screen.getByText('فرستنده')).toBeTruthy(); // i18n.fa.emailFrom
    expect(screen.getByText('پاسخ به Sara Mohseni')).toBeTruthy(); // i18n.fa.emailReplyTo
    expect(screen.getByPlaceholderText('پاسخ بنویسید…')).toBeTruthy(); // i18n.fa.emailPlaceholder

    // A folder hop parks the reader on its Persian empty-state words.
    fireEvent.click(screen.getByText('زباله‌دان')); // i18n.fa.emailTrash
    expect(screen.getByText('برای خواندن، یک پیام را برگزینید')).toBeTruthy(); // i18n.fa.emailNoMessage
    expect(screen.getByText('اینجا پیامی نیست')).toBeTruthy(); // i18n.fa.emailEmpty
  });

  it('speaks Arabic through the provider', () => {
    render(
      <NabuXUIProvider locale="ar">
        <Email messages={messages} />
      </NabuXUIProvider>,
    );
    expect(screen.getByRole('navigation', { name: 'المجلدات' })).toBeTruthy(); // i18n.ar.emailFolders
    expect(screen.getByText('الوارد')).toBeTruthy(); // i18n.ar.emailInbox
    expect(screen.getByText('من')).toBeTruthy(); // i18n.ar.emailFrom
    fireEvent.click(screen.getByText('المهملات')); // i18n.ar.emailTrash
    expect(screen.getByText('اختر رسالة لقراءتها')).toBeTruthy(); // i18n.ar.emailNoMessage
  });

  it('falls back to English for a locale the table does not know', () => {
    const unknown = 'xx' as Locale;
    expect(() =>
      render(
        <NabuXUIProvider locale={unknown}>
          <Email messages={messages} />
        </NabuXUIProvider>,
      ),
    ).not.toThrow();
    expect(screen.getByRole('navigation', { name: 'Folders' })).toBeTruthy(); // the English table
    expect(screen.getAllByText('Quarterly report')).toHaveLength(2); // list row and reading pane, intact
  });
});
