// @vitest-environment happy-dom
/**
 * The file manager and the todo list: browsing and searching a folder tree
 * with its details drawer, and grouped tasks with their tick, quick-add and
 * keyboard reorder — each in several locales plus an unknown one.
 */
import { afterEach, describe, expect, it, vi } from 'vitest';
import { cleanup, fireEvent, render, screen, within } from '@testing-library/react';
import type { Locale } from '@nabuxai/ui-core';
import { FileManager, type FileManagerEntry } from '../src/blocks/file-manager';
import { Todo } from '../src/blocks/todo';
import { NabuXUIProvider } from '../src/internal/provider';

afterEach(cleanup);

/* ---- FileManager ------------------------------------------------------------------------- */

const tree: FileManagerEntry[] = [
  { id: 'photos', name: 'Photos', parent: null, kind: 'folder', items: 2, modified: '2026-09-28' },
  { id: 'logo', name: 'logo.png', parent: null, size: 2048, modified: '2026-09-28' },
  { id: 'notes', name: 'notes.txt', parent: null, size: 512, modified: '2026-09-30' },
  { id: 'cover', name: 'cover.jpg', parent: 'photos', kind: 'image', size: 1_048_576, modified: '2026-09-28', href: '/files/cover.jpg' },
  { id: 'team', name: 'team.jpg', parent: 'photos', kind: 'image', size: 2_097_152, modified: '2026-09-29' },
];

describe('FileManager', () => {
  it('renders the toolbar and the root listing, folders first', () => {
    const { container } = render(<FileManager items={tree} />);
    expect(screen.getByRole('navigation', { name: 'Breadcrumb' })).toBeTruthy(); // i18n.en.breadcrumb
    expect(container.querySelector('section.nx-file-manager')?.getAttribute('aria-label')).toBe('Files'); // i18n.en.fileManager

    const cards = container.querySelectorAll('ul.nx-fm-items .nx-fm-card');
    expect(cards).toHaveLength(3);
    expect(cards[0]?.textContent).toContain('Photos'); // folders sort ahead of files
    expect(cards[1]?.textContent).toContain('logo.png');
    expect(cards[0]?.querySelector('.nx-fm-meta')?.textContent).toContain('2 items'); // i18n.en.fmItems
    expect(cards[1]?.querySelector('.nx-fm-meta')?.textContent).toContain('2\u2009KB'); // 2048 bytes, thin space
    expect(screen.getByText('3 items')).toBeTruthy(); // the spoken count of the listing
    expect(screen.getByRole('searchbox', { name: 'Search' })?.getAttribute('placeholder')).toBe('Search files…'); // i18n.en.fmSearchPlaceholder
    expect(screen.getByRole('radiogroup', { name: 'View' })).toBeTruthy(); // i18n.en.fmView
    expect(screen.getByRole('button', { name: 'Upload' })).toBeTruthy(); // i18n.en.fmUpload
  });

  it('navigates into folders and opens a file’s details drawer', () => {
    const onCurrentChange = vi.fn();
    const onOpen = vi.fn();
    const { container } = render(<FileManager items={tree} onCurrentChange={onCurrentChange} onOpen={onOpen} />);

    fireEvent.click(screen.getByText('Photos'));
    expect(onCurrentChange).toHaveBeenCalledWith('photos');
    expect(screen.getByRole('status').textContent).toBe('Opened Photos'); // i18n.en.fmEntered
    const current = screen.getByText('Photos').closest('button');
    expect(current?.getAttribute('aria-current')).toBe('page'); // the breadcrumb marks the folder

    fireEvent.click(screen.getByText('cover.jpg'));
    expect(onOpen).toHaveBeenCalledWith(expect.objectContaining({ id: 'cover' }));
    const drawer = container.querySelector('.nx-fm-drawer') as HTMLElement;
    expect(drawer.getAttribute('data-open')).toBe('');
    expect(drawer.getAttribute('aria-label')).toBe('Details'); // i18n.en.fmDetails
    expect(drawer.querySelector('.nx-fm-drawer-kind')?.textContent).toBe('Image'); // i18n.en.fmKindImage
    const rows = new Map(
      Array.from(drawer.querySelectorAll('.nx-fm-drawer-rows > div')).map((row) => [
        row.querySelector('dt')?.textContent ?? '',
        row.querySelector('dd')?.textContent ?? '',
      ]),
    );
    expect(rows.get('Size')).toBe('1\u2009MB'); // i18n.en.fmSize + formatBytes
    expect(rows.has('Modified')).toBe(true); // i18n.en.fmModified
    const open = drawer.querySelector('a.nx-fm-drawer-open');
    expect(open?.getAttribute('href')).toBe('/files/cover.jpg');
    expect(open?.textContent).toContain('Open'); // i18n.en.fmOpen

    // Escape folds the drawer away.
    fireEvent.keyDown(container.querySelector('section.nx-file-manager')!, { key: 'Escape' });
    expect(drawer.getAttribute('data-open')).toBeNull();
  });

  it('searches the current folder and reports the tally', () => {
    const { container } = render(<FileManager items={tree} />);
    const search = screen.getByRole('searchbox', { name: 'Search' }) as HTMLInputElement;
    fireEvent.change(search, { target: { value: 'logo' } });
    const cards = container.querySelectorAll('ul.nx-fm-items .nx-fm-card');
    expect(cards).toHaveLength(1);
    expect(cards[0]?.textContent).toContain('logo.png');
    expect(screen.getByRole('status').textContent).toBe('1 results'); // i18n.en.fmResults

    fireEvent.change(search, { target: { value: 'zzz' } });
    expect(container.querySelector('.nx-fm-empty')?.textContent).toBe('No results found'); // i18n.en.noResults
  });

  it('switches the listing view and reports picked uploads', () => {
    const onViewChange = vi.fn();
    const onUpload = vi.fn();
    const { container } = render(<FileManager items={tree} onViewChange={onViewChange} onUpload={onUpload} />);
    const list = container.querySelector('ul.nx-fm-items')!;
    expect(list.getAttribute('data-view')).toBe('grid');
    fireEvent.click(container.querySelector('.nx-fm-view input[value="list"]')!);
    expect(onViewChange).toHaveBeenCalledWith('list');
    expect(list.getAttribute('data-view')).toBe('list');

    const picker = container.querySelector('input[type="file"]') as HTMLInputElement;
    const file = new File(['hello'], 'hello.txt', { type: 'text/plain' });
    fireEvent.change(picker, { target: { files: [file] } });
    expect(onUpload).toHaveBeenCalledTimes(1);
    expect(onUpload.mock.calls[0]![0]).toEqual([file]);
  });

  it('speaks Persian through the provider', () => {
    const { container } = render(
      <NabuXUIProvider locale="fa">
        <FileManager items={tree} />
      </NabuXUIProvider>,
    );
    expect(container.querySelector('section.nx-file-manager')?.getAttribute('aria-label')).toBe('فایل‌ها'); // i18n.fa.fileManager
    expect(screen.getByPlaceholderText('جست‌وجوی فایل…')).toBeTruthy(); // i18n.fa.fmSearchPlaceholder
    expect(screen.getByText('گرید')).toBeTruthy(); // i18n.fa.fmGridView
    expect(screen.getByText('فهرست')).toBeTruthy(); // i18n.fa.fmListView
    expect(screen.getByRole('button', { name: 'بارگذاری' })).toBeTruthy(); // i18n.fa.fmUpload
    const photos = screen.getByText('Photos').closest('button');
    expect(photos?.querySelector('.nx-fm-meta')?.textContent).toContain('۲ مورد'); // i18n.fa.fmItems, Persian digits
  });

  it('speaks Arabic through the provider', () => {
    const { container } = render(
      <NabuXUIProvider locale="ar">
        <FileManager items={tree} />
      </NabuXUIProvider>,
    );
    expect(container.querySelector('section.nx-file-manager')?.getAttribute('aria-label')).toBe('الملفات'); // i18n.ar.fileManager
    expect(screen.getByPlaceholderText('البحث في الملفات…')).toBeTruthy(); // i18n.ar.fmSearchPlaceholder
  });

  it('falls back to English for a locale the table does not know', () => {
    const unknown = 'xx' as Locale;
    expect(() =>
      render(
        <NabuXUIProvider locale={unknown}>
          <FileManager items={tree} />
        </NabuXUIProvider>,
      ),
    ).not.toThrow();
    expect(screen.getByPlaceholderText('Search files…')).toBeTruthy(); // the English table
    expect(screen.getByText('3 items')).toBeTruthy();
  });
});

/* ---- Todo ---------------------------------------------------------------------------------- */

const groups = [
  { id: 'today', title: 'Today', tasks: [{ id: 't1', title: 'Ship tokens' }, { id: 't2', title: 'Write tests', done: true }] },
  { id: 'soon', title: 'Soon', tasks: [{ id: 't3', title: 'Plan the launch' }] },
];

describe('Todo', () => {
  it('renders the groups, the tasks, and the progress ladder', () => {
    const { container } = render(<Todo defaultGroups={groups} />);
    expect(container.querySelector('section.nx-todo')?.getAttribute('aria-label')).toBe('Tasks'); // i18n.en.todoList
    // The group titles double as quick-add options, so read them off the groups.
    expect(container.querySelector('.nx-todo-group[data-group="today"] .nx-todo-group-title')?.textContent).toBe('Today');
    expect(container.querySelector('.nx-todo-group[data-group="soon"] .nx-todo-group-title')?.textContent).toBe('Soon');
    expect(screen.getByText('Ship tokens')).toBeTruthy();
    expect(screen.getByText('Write tests')).toBeTruthy();

    const bar = screen.getByRole('progressbar');
    expect(bar.getAttribute('aria-valuenow')).toBe('33'); // 1 of 3, rounded
    expect(bar.getAttribute('aria-valuetext')).toBe('1 of 3 done'); // i18n.en.todoProgress
    const done = container.querySelector('.nx-todo-item[data-key="t2"] input.nx-todo-check') as HTMLInputElement;
    expect(done.checked).toBe(true);
  });

  it('ticks a task, reports it, and completes the list', () => {
    const onCheck = vi.fn();
    const { container } = render(<Todo defaultGroups={groups} onCheck={onCheck} />);
    const box = container.querySelector('.nx-todo-item[data-key="t1"] input.nx-todo-check') as HTMLInputElement;
    fireEvent.click(box);
    expect(onCheck).toHaveBeenCalledWith('t1', true, expect.anything());
    expect(screen.getByRole('status').textContent).toBe('Ship tokens marked done'); // i18n.en.todoChecked
    const bar = screen.getByRole('progressbar');
    expect(bar.getAttribute('aria-valuenow')).toBe('67'); // 2 of 3

    const t3 = container.querySelector('.nx-todo-item[data-key="t3"] input.nx-todo-check') as HTMLInputElement;
    fireEvent.click(t3);
    expect(container.querySelector('section.nx-todo')?.getAttribute('data-complete')).toBe(''); // all done
    expect(screen.getByRole('status').textContent).toBe('Plan the launch marked done');

    fireEvent.click(box); // and back again
    expect(onCheck).toHaveBeenLastCalledWith('t1', false, expect.anything());
    expect(screen.getByRole('status').textContent).toBe('Ship tokens reopened'); // i18n.en.todoUnchecked
  });

  it('removes a task and reports what left', () => {
    const onRemove = vi.fn();
    const { container } = render(<Todo defaultGroups={groups} onRemove={onRemove} />);
    fireEvent.click(screen.getByRole('button', { name: 'Remove Ship tokens' })); // i18n.en.todoRemove
    expect(onRemove).toHaveBeenCalledWith('t1', expect.anything());
    expect(screen.queryByText('Ship tokens')).toBeNull();
    expect(screen.getByRole('status').textContent).toBe('Remove Ship tokens');
  });

  it('adds a task through the quick-add composer, into the picked group', () => {
    const onAdd = vi.fn();
    const { container } = render(<Todo defaultGroups={groups} onAdd={onAdd} />);
    const form = container.querySelector('form.nx-todo-add')!;
    const input = form.querySelector('input.nx-todo-add-input') as HTMLInputElement;
    expect(input.getAttribute('placeholder')).toBe('Add a task…'); // i18n.en.todoAddTask
    const select = form.querySelector('select.nx-todo-add-select') as HTMLSelectElement;
    expect(select.getAttribute('aria-label')).toBe('Group'); // i18n.en.todoGroup

    fireEvent.change(input, { target: { value: 'Review the PR' } });
    fireEvent.change(select, { target: { value: 'soon' } });
    fireEvent.submit(form);

    expect(onAdd).toHaveBeenCalledWith('soon', 'Review the PR', expect.anything());
    expect(input.value).toBe(''); // the composer clears
    const soon = container.querySelector('.nx-todo-group[data-group="soon"]')!;
    expect(soon.textContent).toContain('Review the PR'); // it landed in the picked group
    expect(container.querySelector('.nx-todo-group[data-group="today"]')!.textContent).not.toContain('Review the PR');
  });

  it('reorders with Alt+Arrow and refuses to nudge the last task further', () => {
    const onMove = vi.fn();
    const { container } = render(<Todo defaultGroups={groups} onMove={onMove} />);
    const t1 = container.querySelector('.nx-todo-item[data-key="t1"]')!;
    fireEvent.keyDown(t1, { key: 'ArrowDown', altKey: true });
    expect(onMove).toHaveBeenCalledWith({ task: 't1', from: 'today', to: 'today', index: 1 }, expect.anything());
    expect(screen.getByRole('status').textContent).toBe('Ship tokens moved'); // i18n.en.todoMoved
    const order = Array.from(container.querySelectorAll('.nx-todo-group[data-group="today"] .nx-todo-item')).map((item) => item.getAttribute('data-key'));
    expect(order).toEqual(['t2', 't1']); // it slipped under its done sibling

    const t3 = container.querySelector('.nx-todo-item[data-key="t3"]')!; // last task of the last group
    fireEvent.keyDown(t3, { key: 'ArrowDown', altKey: true });
    expect(onMove).toHaveBeenCalledTimes(1); // nowhere to go
  });

  it('shows the empty plate for a group without tasks', () => {
    render(<Todo defaultGroups={[{ id: 'today', title: 'Today', tasks: [] }]} />);
    expect(screen.getByText('No tasks yet')).toBeTruthy(); // i18n.en.todoEmpty
  });

  it('speaks Persian through the provider', () => {
    const { container } = render(
      <NabuXUIProvider locale="fa">
        <Todo defaultGroups={groups} />
      </NabuXUIProvider>,
    );
    expect(container.querySelector('section.nx-todo')?.getAttribute('aria-label')).toBe('کارها'); // i18n.fa.todoList
    expect(screen.getByRole('progressbar').getAttribute('aria-valuetext')).toBe('۱ از ۳ انجام شد'); // i18n.fa.todoProgress
    expect(screen.getByPlaceholderText('کار تازه بنویسید…')).toBeTruthy(); // i18n.fa.todoAddTask
    fireEvent.click(container.querySelector('.nx-todo-item[data-key="t1"] input')!);
    expect(screen.getByRole('status').textContent).toBe('«Ship tokens» انجام شد'); // i18n.fa.todoChecked
  });

  it('falls back to English for a locale the table does not know', () => {
    const unknown = 'xx' as Locale;
    expect(() =>
      render(
        <NabuXUIProvider locale={unknown}>
          <Todo defaultGroups={groups} />
        </NabuXUIProvider>,
      ),
    ).not.toThrow();
    expect(screen.getByRole('progressbar').getAttribute('aria-valuetext')).toBe('1 of 3 done');
    expect(screen.getByRole('button', { name: /Add/ })).toBeTruthy();
  });
});
