import { describe, expect, it } from 'vitest';
import {
  argsPreview,
  diffLines,
  diffMarker,
  diffRows,
  diffStats,
  domainOf,
  formatElapsed,
  highlightLine,
  jsonAt,
  jsonContainers,
  jsonOpenToDepth,
  jsonPath,
  jsonRows,
  jsonSearch,
  logLevelOf,
  markParts,
  parseLineRanges,
  streamParts,
  streamTokens,
  toLines,
} from '../src/js/blocks/agent';

const kinds = (before: string, after: string) => diffLines(before, after).map((line) => `${line.kind === 'same' ? ' ' : line.kind === 'add' ? '+' : '-'}${line.text}`);

/** Rebuild both sides from the diff: the old side from same+del, the new from same+add. */
const sides = (before: string, after: string) => {
  const lines = diffLines(before, after);
  return {
    old: lines.filter((line) => line.kind !== 'add').map((line) => line.text),
    next: lines.filter((line) => line.kind !== 'del').map((line) => line.text),
  };
};

describe('toLines', () => {
  it('splits on any newline and ignores a final one', () => {
    expect(toLines('a\r\nb\nc\n')).toEqual(['a', 'b', 'c']);
    expect(toLines('')).toEqual([]);
    expect(toLines(['x', 'y'])).toEqual(['x', 'y']);
  });
});

describe('diffLines', () => {
  it('reports nothing changed for equal texts', () => {
    expect(kinds('a\nb\nc', 'a\nb\nc')).toEqual([' a', ' b', ' c']);
  });

  it('handles empty sides', () => {
    expect(kinds('', 'a\nb')).toEqual(['+a', '+b']);
    expect(kinds('a\nb', '')).toEqual(['-a', '-b']);
    expect(kinds('', '')).toEqual([]);
  });

  it('finds a minimal edit and puts removals before additions in a changed run', () => {
    expect(kinds('a\nb\nc\nd', 'a\nx\nc\nd')).toEqual([' a', '-b', '+x', ' c', ' d']);
    expect(kinds('a\nb\nc', 'a\nc')).toEqual([' a', '-b', ' c']);
    expect(kinds('a\nc', 'a\nb\nc')).toEqual([' a', '+b', ' c']);
  });

  it('is minimal on the classic Myers example (ABCABBA → CBABAC, D = 5)', () => {
    const lines = diffLines('A B C A B B A'.split(' '), 'C B A B A C'.split(' '));
    const { added, removed } = diffStats(lines);
    expect(added + removed).toBe(5);
    expect(lines.filter((line) => line.kind === 'same')).toHaveLength(4);
  });

  it('always reproduces both texts and numbers lines on each side', () => {
    const cases: Array<[string, string]> = [
      ['one\ntwo\nthree\nfour\nfive', 'zero\none\nthree\nfour\nfour and a half\nfive'],
      ['سلام\nدنیا\nخداحافظ', 'سلام\nجهان\nخداحافظ\nبعدی'],
      ['x\nx\nx\ny', 'y\nx\nx\nx'],
    ];
    for (const [before, after] of cases) {
      const { old, next } = sides(before, after);
      expect(old).toEqual(toLines(before));
      expect(next).toEqual(toLines(after));
      const lines = diffLines(before, after);
      expect(lines.filter((line) => line.oldNo !== null).map((line) => line.oldNo)).toEqual(toLines(before).map((_, i) => i + 1));
      expect(lines.filter((line) => line.newNo !== null).map((line) => line.newNo)).toEqual(toLines(after).map((_, i) => i + 1));
    }
  });

  it('agrees with an LCS length on random inputs', () => {
    const lcs = (a: string[], b: string[]) => {
      const t = Array.from({ length: a.length + 1 }, () => new Array<number>(b.length + 1).fill(0));
      for (let i = 1; i <= a.length; i++) for (let j = 1; j <= b.length; j++) t[i]![j] = a[i - 1] === b[j - 1] ? t[i - 1]![j - 1]! + 1 : Math.max(t[i - 1]![j]!, t[i]![j - 1]!);
      return t[a.length]![b.length]!;
    };
    let seed = 7;
    const rand = () => (seed = (seed * 16807) % 2147483647) / 2147483647;
    for (let run = 0; run < 60; run++) {
      const a = Array.from({ length: Math.floor(rand() * 14) }, () => 'abcd'[Math.floor(rand() * 4)]!);
      const b = Array.from({ length: Math.floor(rand() * 14) }, () => 'abcd'[Math.floor(rand() * 4)]!);
      const lines = diffLines(a, b);
      expect(lines.filter((line) => line.kind === 'same')).toHaveLength(lcs(a, b));
    }
  });
});

describe('diffRows', () => {
  const before = Array.from({ length: 20 }, (_, i) => `line ${i + 1}`).join('\n');
  const after = before.replace('line 10', 'line ten');
  const lines = diffLines(before, after);

  it('folds long unchanged runs, keeping context around the change', () => {
    const rows = diffRows(lines, { context: 2 });
    expect(rows.map((row) => (row.type === 'gap' ? `gap${row.count}` : row.type === 'line' ? row.line.kind : '?'))).toEqual([
      'gap7', 'same', 'same', 'del', 'add', 'same', 'same', 'gap8',
    ]);
  });

  it('opens a gap when it is expanded', () => {
    const rows = diffRows(lines, { context: 2, expanded: [0] });
    expect(rows.filter((row) => row.type === 'gap')).toHaveLength(1);
    expect(rows[0]).toMatchObject({ type: 'line', line: { text: 'line 1' } });
  });

  it('pairs removed and added lines side by side in split view', () => {
    const rows = diffRows(diffLines('a\nb\nc\nd', 'a\nB\nC\nX\nd'), { split: true });
    const pairs = rows.map((row) => (row.type === 'pair' ? `${row.left?.text ?? '·'}|${row.right?.text ?? '·'}` : 'gap'));
    expect(pairs).toEqual(['a|a', 'b|B', 'c|C', '·|X', 'd|d']);
  });

  it('does not fold anything when there is no change', () => {
    expect(diffRows(diffLines(before, before)).every((row) => row.type === 'line')).toBe(true);
  });
});

describe('JSON paths and rows', () => {
  const data = { user: { name: 'Sara', 'first name': 'سارا', tags: ['admin', 'ops'] }, count: 3, ok: true, none: null };

  it('writes JS-style paths', () => {
    expect(jsonPath([])).toBe('$');
    expect(jsonPath(['user', 'name'])).toBe('$.user.name');
    expect(jsonPath(['user', 'tags', 1])).toBe('$.user.tags[1]');
    expect(jsonPath(['user', 'first name'])).toBe('$.user["first name"]');
    expect(jsonPath(['2fa'])).toBe('$["2fa"]');
  });

  it('reads the value at a path', () => {
    expect(jsonAt(data, ['user', 'tags', 1])).toBe('ops');
    expect(jsonAt(data, ['missing', 'x'])).toBeUndefined();
  });

  it('flattens only open containers, with closing rows', () => {
    const rows = jsonRows(data, (id) => id === '$');
    expect(rows.map((row) => row.id)).toEqual(['$', '$.user', '$.count', '$.ok', '$.none', '$#close']);
    expect(rows[1]).toMatchObject({ type: 'object', count: 3, open: false, depth: 1, key: 'user' });
    expect(rows[2]).toMatchObject({ type: 'number', literal: '3' });
    expect(rows[4]).toMatchObject({ type: 'null', literal: 'null', last: true });
  });

  it('lists containers for expand-all and to a depth', () => {
    expect(jsonContainers(data)).toEqual(['$', '$.user', '$.user.tags']);
    expect(jsonOpenToDepth(data, 1)).toEqual(['$', '$.user']);
  });

  it('searches keys and values, opening the way to every hit', () => {
    const { hits, open } = jsonSearch(data, 'OPS');
    expect([...hits]).toEqual(['$.user.tags[1]']);
    expect([...open].sort()).toEqual(['$', '$.user', '$.user.tags']);
    expect([...jsonSearch(data, 'first').hits]).toEqual(['$.user["first name"]']);
    expect(jsonSearch(data, '  ').hits.size).toBe(0);
  });

  it('marks matches inside text', () => {
    expect(markParts('Hello hello', 'llo')).toEqual([
      { text: 'He', hit: false },
      { text: 'llo', hit: true },
      { text: ' he', hit: false },
      { text: 'llo', hit: true },
    ]);
    expect(markParts('abc', '')).toEqual([{ text: 'abc', hit: false }]);
  });
});

describe('streaming text', () => {
  it('turns [n] markers into citations', () => {
    expect(streamParts('Paris is the capital [1] of France [2].')).toEqual([
      { type: 'text', text: 'Paris is the capital ' },
      { type: 'cite', n: 1, text: '[1]' },
      { type: 'text', text: ' of France ' },
      { type: 'cite', n: 2, text: '[2]' },
      { type: 'text', text: '.' },
    ]);
  });

  it('holds back a marker that is still arriving', () => {
    expect(streamParts('see [1', true)).toEqual([{ type: 'text', text: 'see ' }]);
    expect(streamParts('see [1', false)).toEqual([{ type: 'text', text: 'see [1' }]);
  });

  it('never splits Persian letters, only words', () => {
    expect(streamTokens('تهران پایتخت ایران است')).toEqual(['تهران ', 'پایتخت ', 'ایران ', 'است']);
  });

  it('derives a source domain', () => {
    expect(domainOf({ url: 'https://www.example.com/a/b?q=1' })).toBe('example.com');
    expect(domainOf({ url: 'x', domain: 'given.org' })).toBe('given.org');
  });
});

describe('small helpers', () => {
  it('parses line ranges', () => {
    expect([...parseLineRanges('1, 3-5,8')]).toEqual([1, 3, 4, 5, 8]);
    expect([...parseLineRanges([2, [6, 4]])]).toEqual([2, 4, 5, 6]);
    expect(parseLineRanges(null).size).toBe(0);
  });

  it('previews tool arguments on one line', () => {
    expect(argsPreview({ query: 'weather in Tehran', limit: 5, filters: { a: 1 }, ids: [1, 2] })).toBe('query: "weather in Tehran", limit: 5, filters: {…}, ids: [2]');
    expect(argsPreview('x'.repeat(100), 10)).toBe('xxxxxxxxx…');
    expect(argsPreview(undefined)).toBe('');
  });

  it('formats elapsed time', () => {
    expect(formatElapsed(12, 'en')).toBe('12s');
    expect(formatElapsed(65, 'en')).toBe('1m 5s');
    expect(formatElapsed(120, 'en')).toBe('2m');
  });

  it('tokenizes a line of code', () => {
    const tokens = highlightLine('const total = sum(1, "two") // add', 'ts');
    expect(tokens.filter((token) => token.type !== 'plain' && token.type !== 'punct').map((token) => `${token.type}:${token.text}`)).toEqual([
      'keyword:const',
      'fn:sum',
      'number:1',
      'string:"two"',
      'comment:// add',
    ]);
    expect(tokens.map((token) => token.text).join('')).toBe('const total = sum(1, "two") // add');
    expect(highlightLine('"name": "x"', 'json').map((token) => token.type)).toContain('attr');
  });

  it('reads diff markers and log levels', () => {
    expect(diffMarker('+ added')).toBe('add');
    expect(diffMarker('- removed')).toBe('del');
    expect(diffMarker('+++ b/file')).toBeNull();
    expect(logLevelOf('ERROR: boom')).toBe('error');
    expect(logLevelOf('[warn] slow')).toBe('warn');
    expect(logLevelOf('✓ built')).toBe('success');
    expect(logLevelOf('plain')).toBe('info');
  });
});
