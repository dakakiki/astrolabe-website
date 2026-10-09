// Outlines a word set in a WOFF (1.0) TrueType font as one SVG path.
// usage: node outline.mjs <font.woff> <positions.json> <out.json>
// positions.json: { text, size, x: [pen x per char, in px at `size`] } measured in a browser,
// so kerning and letter-spacing match what the site renders.
import { readFileSync, writeFileSync } from 'node:fs';
import { inflateSync } from 'node:zlib';

const [fontFile, posFile, outFile] = process.argv.slice(2);
const woff = readFileSync(fontFile);
if (woff.toString('latin1', 0, 4) !== 'wOFF') throw new Error('not a WOFF 1.0 file');

const tables = {};
const numTables = woff.readUInt16BE(12);
for (let i = 0; i < numTables; i++) {
    const at = 44 + i * 20;
    const tag = woff.toString('latin1', at, at + 4);
    const offset = woff.readUInt32BE(at + 4);
    const comp = woff.readUInt32BE(at + 8);
    const orig = woff.readUInt32BE(at + 12);
    const raw = woff.subarray(offset, offset + comp);
    tables[tag] = comp < orig ? inflateSync(raw) : Buffer.from(raw);
}

const head = tables.head;
const unitsPerEm = head.readUInt16BE(18);
const longLoca = head.readInt16BE(50) === 1;
const numGlyphs = tables.maxp.readUInt16BE(4);
const ascender = tables.hhea.readInt16BE(4);
const descender = tables.hhea.readInt16BE(6);

function glyphOffset(i) {
    const loca = tables.loca;
    return longLoca ? [loca.readUInt32BE(i * 4), loca.readUInt32BE(i * 4 + 4)] : [loca.readUInt16BE(i * 2) * 2, loca.readUInt16BE(i * 2 + 2) * 2];
}

function cmapLookup(code) {
    const cmap = tables.cmap;
    const n = cmap.readUInt16BE(2);
    const subs = [];
    for (let i = 0; i < n; i++) {
        subs.push({ pid: cmap.readUInt16BE(4 + i * 8), eid: cmap.readUInt16BE(6 + i * 8), off: cmap.readUInt32BE(8 + i * 8) });
    }
    for (const s of subs) {
        const fmt = cmap.readUInt16BE(s.off);
        if (fmt === 12) {
            const groups = cmap.readUInt32BE(s.off + 12);
            for (let g = 0; g < groups; g++) {
                const at = s.off + 16 + g * 12;
                const start = cmap.readUInt32BE(at);
                const end = cmap.readUInt32BE(at + 4);
                if (code >= start && code <= end) return cmap.readUInt32BE(at + 8) + (code - start);
            }
        }
    }
    for (const s of subs) {
        if (cmap.readUInt16BE(s.off) !== 4) continue;
        const o = s.off;
        const segX2 = cmap.readUInt16BE(o + 6);
        const ends = o + 14;
        const starts = ends + segX2 + 2;
        const deltas = starts + segX2;
        const ranges = deltas + segX2;
        for (let i = 0; i < segX2 / 2; i++) {
            const end = cmap.readUInt16BE(ends + i * 2);
            const start = cmap.readUInt16BE(starts + i * 2);
            if (code < start || code > end) continue;
            const delta = cmap.readInt16BE(deltas + i * 2);
            const ro = cmap.readUInt16BE(ranges + i * 2);
            if (!ro) return (code + delta) & 0xffff;
            const gi = cmap.readUInt16BE(ranges + i * 2 + ro + (code - start) * 2);
            return gi ? (gi + delta) & 0xffff : 0;
        }
    }
    return 0;
}

/** Contours of a glyph: arrays of {x, y, on}. */
function contours(index) {
    const [start, end] = glyphOffset(index);
    if (end <= start) return [];
    const g = tables.glyf.subarray(start, end);
    const n = g.readInt16BE(0);
    if (n >= 0) {
        const ends = [];
        for (let i = 0; i < n; i++) ends.push(g.readUInt16BE(10 + i * 2));
        const count = ends.length ? ends[ends.length - 1] + 1 : 0;
        let p = 10 + n * 2;
        p += 2 + g.readUInt16BE(p);
        const flags = [];
        while (flags.length < count) {
            const f = g[p++];
            flags.push(f);
            if (f & 8) {
                const rep = g[p++];
                for (let r = 0; r < rep; r++) flags.push(f);
            }
        }
        const read = (shortBit, sameBit) => {
            const out = [];
            let v = 0;
            for (const f of flags) {
                if (f & shortBit) {
                    const d = g[p++];
                    v += f & sameBit ? d : -d;
                } else if (!(f & sameBit)) {
                    v += g.readInt16BE(p);
                    p += 2;
                }
                out.push(v);
            }
            return out;
        };
        const xs = read(2, 16);
        const ys = read(4, 32);
        const result = [];
        let from = 0;
        for (const e of ends) {
            const c = [];
            for (let i = from; i <= e; i++) c.push({ x: xs[i], y: ys[i], on: !!(flags[i] & 1) });
            result.push(c);
            from = e + 1;
        }
        return result;
    }
    // Composite glyph
    const result = [];
    let p = 10;
    let more = true;
    while (more) {
        const flags = g.readUInt16BE(p);
        const child = g.readUInt16BE(p + 2);
        p += 4;
        let dx, dy;
        if (flags & 1) {
            dx = g.readInt16BE(p);
            dy = g.readInt16BE(p + 2);
            p += 4;
        } else {
            dx = g.readInt8(p);
            dy = g.readInt8(p + 1);
            p += 2;
        }
        let a = 1, b = 0, c = 0, d = 1;
        const f2 = (o) => g.readInt16BE(o) / 16384;
        if (flags & 8) {
            a = d = f2(p);
            p += 2;
        } else if (flags & 0x40) {
            a = f2(p);
            d = f2(p + 2);
            p += 4;
        } else if (flags & 0x80) {
            a = f2(p);
            b = f2(p + 2);
            c = f2(p + 4);
            d = f2(p + 6);
            p += 8;
        }
        for (const contour of contours(child)) {
            result.push(contour.map((pt) => ({ x: a * pt.x + c * pt.y + dx, y: b * pt.x + d * pt.y + dy, on: pt.on })));
        }
        more = !!(flags & 0x20);
    }
    return result;
}

const r = (v) => Math.round(v * 100) / 100;

/** SVG path of one contour list, scaled and moved (y flipped). */
function toPath(list, ox, oy, s) {
    const X = (x) => r(ox + x * s);
    const Y = (y) => r(oy - y * s);
    let d = '';
    for (const pts of list) {
        if (!pts.length) continue;
        let startIdx = pts.findIndex((q) => q.on);
        let start;
        if (startIdx < 0) {
            start = { x: (pts[0].x + pts[1].x) / 2, y: (pts[0].y + pts[1].y) / 2, on: true };
            startIdx = 0;
        } else {
            start = pts[startIdx];
        }
        d += `M${X(start.x)} ${Y(start.y)}`;
        const order = [];
        for (let i = 1; i <= pts.length; i++) order.push(pts[(startIdx + i) % pts.length]);
        let ctrl = null;
        for (const q of order) {
            if (q.on) {
                d += ctrl ? `Q${X(ctrl.x)} ${Y(ctrl.y)} ${X(q.x)} ${Y(q.y)}` : `L${X(q.x)} ${Y(q.y)}`;
                ctrl = null;
            } else if (ctrl) {
                const mx = (ctrl.x + q.x) / 2;
                const my = (ctrl.y + q.y) / 2;
                d += `Q${X(ctrl.x)} ${Y(ctrl.y)} ${X(mx)} ${Y(my)}`;
                ctrl = q;
            } else {
                ctrl = q;
            }
        }
        if (ctrl) d += `Q${X(ctrl.x)} ${Y(ctrl.y)} ${X(start.x)} ${Y(start.y)}`;
        d += 'Z';
    }
    return d;
}

const pos = JSON.parse(readFileSync(posFile, 'utf8'));
const scale = pos.size / unitsPerEm; // px per font unit at the measured size
let d = '';
let minX = Infinity, maxX = -Infinity, minY = Infinity, maxY = -Infinity;
[...pos.text].forEach((ch, i) => {
    const gi = cmapLookup(ch.codePointAt(0));
    const list = contours(gi);
    const ox = pos.x[i] / scale; // pen position in font units
    for (const c of list) {
        for (const q of c) {
            minX = Math.min(minX, ox + q.x);
            maxX = Math.max(maxX, ox + q.x);
            minY = Math.min(minY, q.y);
            maxY = Math.max(maxY, q.y);
        }
    }
    d += toPath(list, ox, 0, 1);
});
const capL = contours(cmapLookup('L'.codePointAt(0)));
const capHeight = Math.max(...capL.flat().map((q) => q.y));

writeFileSync(
    outFile,
    JSON.stringify({ unitsPerEm, ascender, descender, capHeight, numGlyphs, bbox: { minX, maxX, minY, maxY }, d }, null, 1),
);
console.log({ unitsPerEm, ascender, descender, capHeight, bbox: { minX, maxX, minY, maxY }, length: d.length });
