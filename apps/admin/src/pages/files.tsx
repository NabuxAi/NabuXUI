/**
 * #/files — the media library: the nx-file-manager block over a seeded,
 * stable-id tree (folder labels are bilingual; ids are not, so navigating
 * survives a language switch). The type chip filter narrows what the manager
 * shows, the FileDrop zone takes real drag-and-drops (and toasts the count),
 * "New folder" really appends a folder into the one you are standing in, and
 * the side column measures the store's storage — a gauge against the plan's
 * ceiling and a donut by file type. Sizes/dates are the block's own locale
 * formatting (Persian digits, Jalali calendar for fa). Deterministic: no
 * Math.random, no fetch.
 */
import { useState } from 'react';
import {
  Button,
  ChipFilter,
  Dialog,
  DonutRing,
  Field,
  FileDrop,
  FileManager,
  Gauge,
  Input,
  toast,
  type FileManagerEntry,
} from '@nabuxai/ui-react';
import { AGO } from '../data';
import { useLang, useStrings } from '../lang';

const INTL = { fa: 'fa-IR', en: 'en-US' } as const;
const MB = 1024 * 1024;

/** The filter dimension the page adds on top of the block's own search. */
type Type = 'images' | 'docs' | 'video' | 'audio' | 'other';

type Entry = FileManagerEntry & { type: Type };

type Folder = { id: string; parent: string | null; fa: string; en: string };

/** The folders, with stable ids; labels are rebuilt per render in the page language. */
const FOLDERS: Folder[] = [
  { id: 'f-products', parent: null, fa: 'عکسهای محصول', en: 'Product photos' },
  { id: 'f-360', parent: 'f-products', fa: 'عکسهای ۳۶۰', en: '360 shots' },
  { id: 'f-design', parent: null, fa: 'طراحی', en: 'Design' },
  { id: 'f-docs', parent: null, fa: 'اسناد', en: 'Documents' },
  { id: 'f-media', parent: null, fa: 'رسانه', en: 'Media' },
];

const FILES: Array<Omit<Entry, 'name' | 'parent'> & { name: string; parent: string }> = [
  { id: 'd1', name: 'bag-nabu-hero.jpg', parent: 'f-products', kind: 'image', type: 'images', size: 2.4 * MB, modified: AGO.hours5.at },
  { id: 'd2', name: 'bag-nabu-detail-2.jpg', parent: 'f-products', kind: 'image', type: 'images', size: 1.8 * MB, modified: AGO.day1.at },
  { id: 'd3', name: 'scarf-cashmere-01.jpg', parent: 'f-products', kind: 'image', type: 'images', size: 1.2 * MB, modified: '2026-09-18' },
  { id: 'd4', name: 'watch-classic.jpg', parent: 'f-products', kind: 'image', type: 'images', size: 2.1 * MB, modified: '2026-09-14' },
  { id: 'd5', name: 'poster-nowruz.png', parent: 'f-products', kind: 'image', type: 'images', size: 4.6 * MB, modified: AGO.days2.at },
  { id: 'd6', name: 'bag-nabu-360-1.jpg', parent: 'f-360', kind: 'image', type: 'images', size: 1.6 * MB, modified: AGO.day1.at },
  { id: 'd7', name: 'bag-nabu-360-2.jpg', parent: 'f-360', kind: 'image', type: 'images', size: 1.7 * MB, modified: AGO.day1.at },
  { id: 'd8', name: 'bag-nabu-360-3.jpg', parent: 'f-360', kind: 'image', type: 'images', size: 1.5 * MB, modified: AGO.day1.at },
  { id: 'd9', name: 'logo-nabu.svg', parent: 'f-design', kind: 'image', type: 'images', size: 0.03 * MB, modified: '2026-08-30' },
  { id: 'd10', name: 'brand-guide.pdf', parent: 'f-design', kind: 'file', type: 'docs', size: 3.2 * MB, modified: '2026-09-08' },
  { id: 'd11', name: 'palette-lapis.json', parent: 'f-design', kind: 'file', type: 'docs', size: 0.01 * MB, modified: '2026-09-25' },
  { id: 'd12', name: 'invoice-INV-2026-118.pdf', parent: 'f-docs', kind: 'file', type: 'docs', size: 0.4 * MB, modified: AGO.hours2.at },
  { id: 'd13', name: 'size-guide.pdf', parent: 'f-docs', kind: 'file', type: 'docs', size: 0.9 * MB, modified: '2026-09-21' },
  { id: 'd14', name: 'terms-fa.docx', parent: 'f-docs', kind: 'file', type: 'docs', size: 0.2 * MB, modified: '2026-09-03' },
  { id: 'd15', name: 'teaser-nowruz.mp4', parent: 'f-media', kind: 'video', type: 'video', size: 48 * MB, modified: AGO.day1.at },
  { id: 'd16', name: 'podcast-ep12.mp3', parent: 'f-media', kind: 'audio', type: 'audio', size: 22 * MB, modified: '2026-09-27' },
  { id: 'd17', name: 'store-backup.zip', parent: '', kind: 'file', type: 'other', size: 96 * MB, modified: AGO.days2.at },
];

/** Storage by type, GB — the same numbers the gauge and the donut tell. */
const STORAGE: Array<{ type: Type; gb: number }> = [
  { type: 'images', gb: 3.4 },
  { type: 'docs', gb: 1.1 },
  { type: 'video', gb: 2.2 },
  { type: 'audio', gb: 0.5 },
  { type: 'other', gb: 0.2 },
];
const PLAN_GB = 10;
const USED_GB = STORAGE.reduce((sum, slice) => sum + slice.gb, 0);

export function FilesPage() {
  const lang = useLang();
  const s = useStrings();
  const f = s.pages.files;
  const number = new Intl.NumberFormat(INTL[lang]);

  const [folders, setFolders] = useState<Folder[]>(FOLDERS);
  const [type, setType] = useState('all');
  const [folder, setFolder] = useState<string | null>(null);
  const [creating, setCreating] = useState(false);
  const [name, setName] = useState('');

  const typeLabel: Record<Type, string> = { images: f.typeImages, docs: f.typeDocs, video: f.typeVideo, audio: f.typeAudio, other: f.typeOther };

  // Folder entries: labels in the page language, the child count as `items`;
  // the `type` is only there so Entry stays one shape (folders filter by children).
  const entries: Entry[] = [
    ...folders.map((entry) => ({
      id: entry.id,
      name: lang === 'fa' ? entry.fa : entry.en,
      parent: entry.parent,
      kind: 'folder' as const,
      items: FILES.filter((file) => file.parent === entry.id).length,
      type: 'other' as Type,
      modified: AGO.day1.at,
    })),
    ...FILES.map((file) => ({ ...file, parent: file.parent || null })),
  ];

  // A folder stays visible under a type chip while anything inside it (at any
  // depth) matches — so browsing into a filtered folder still leads somewhere.
  const folderIds = new Set(folders.map((entry) => entry.id));
  const folderMatches = (id: string): boolean => {
    if (type === 'all') return true;
    const direct = FILES.some((file) => file.parent === id && file.type === type);
    const nested = folders.some((child) => child.parent === id && folderMatches(child.id));
    return direct || nested;
  };
  const visible = entries.filter((entry) => (folderIds.has(entry.id) ? folderMatches(entry.id) : type === 'all' || entry.type === type));

  const counts = {
    all: FILES.length + folders.length,
    images: FILES.filter((file) => file.type === 'images').length,
    docs: FILES.filter((file) => file.type === 'docs').length,
    video: FILES.filter((file) => file.type === 'video').length,
    audio: FILES.filter((file) => file.type === 'audio').length,
    other: FILES.filter((file) => file.type === 'other').length,
  };

  const here = folders.find((entry) => entry.id === folder);
  const create = () => {
    const trimmed = name.trim();
    if (!trimmed) return;
    setFolders((prev) => [...prev, { id: `f-${Date.now().toString(36)}`, parent: folder, fa: trimmed, en: trimmed }]);
    setCreating(false);
    setName('');
    toast(f.folderCreated);
  };

  return (
    <div className="adm-dashboard">
      <div className="adm-wide" style={{ display: 'flex', flexWrap: 'wrap', gap: 'var(--nx-space-3)', alignItems: 'center', justifyContent: 'space-between' }}>
        <ChipFilter
          aria-label={f.type}
          value={type}
          onValueChange={setType}
          items={[
            { value: 'all', label: s.common.all, count: counts.all },
            { value: 'images', label: f.typeImages, count: counts.images },
            { value: 'docs', label: f.typeDocs, count: counts.docs },
            { value: 'video', label: f.typeVideo, count: counts.video },
            { value: 'audio', label: f.typeAudio, count: counts.audio },
            { value: 'other', label: f.typeOther, count: counts.other },
          ]}
        />
        <Button variant="secondary" icon="plus" onClick={() => setCreating(true)}>
          {f.secondary}
        </Button>
      </div>

      <div className="adm-wide" style={{ display: 'grid', gap: 'var(--nx-space-3)' }}>
        <FileManager
          items={visible}
          current={folder}
          onCurrentChange={setFolder}
          label={f.root}
          searchPlaceholder={f.search}
          emptyText={f.emptyFolder}
          height="28rem"
          onUpload={(files) => toast(`${number.format(files.length)} ${f.itemsCount} ${f.uploaded}`)}
        />
        <FileDrop
          title={f.upload}
          hint={f.dropHint}
          onFiles={(files) => toast(`${number.format(files.length)} ${f.itemsCount} ${f.uploaded}`)}
        />
      </div>

      <div className="adm-side">
        <Gauge
          value={USED_GB}
          max={PLAN_GB}
          unit="GB"
          decimals={1}
          title={f.storageUsed}
          subtitle={f.size}
          zones={[
            { upTo: PLAN_GB * 0.6, tone: 'success' },
            { upTo: PLAN_GB * 0.85, tone: 'warning' },
            { tone: 'danger' },
          ]}
        />
        <DonutRing
          title={f.type}
          data={STORAGE.map((slice) => ({ label: typeLabel[slice.type], value: slice.gb }))}
          centerValue={USED_GB}
          centerLabel={f.storageUsed}
        />
      </div>

      <Dialog
        open={creating}
        onOpenChange={setCreating}
        size="sm"
        title={f.newFolder}
        description={here ? (lang === 'fa' ? here.fa : here.en) : f.root}
        footer={
          <>
            <Button variant="secondary" onClick={() => setCreating(false)}>
              {s.common.cancel}
            </Button>
            <Button variant="primary" icon="plus" disabled={!name.trim()} onClick={create}>
              {s.common.add}
            </Button>
          </>
        }
      >
        <Field label={f.name} required>
          <Input value={name} onChange={(event) => setName(event.target.value)} onKeyDown={(event) => event.key === 'Enter' && create()} autoComplete="off" />
        </Field>
      </Dialog>
    </div>
  );
}
