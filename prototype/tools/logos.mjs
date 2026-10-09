// Builds the AstroLabe logo proposals from geometry, so every variant stays in step.
//
//   node tools/logos.mjs
//
// Writes assets/logo/*.svg and refreshes the inline sprite between the
// "logo-sprite" markers in index.html and brand.html. The sprite colours are
// CSS variables (--logo-*), so the marks follow the night/day theme.

import { readFileSync, writeFileSync, mkdirSync, existsSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join(dirname(fileURLToPath(import.meta.url)), '..');
const out = join(root, 'assets', 'logo');
mkdirSync(out, { recursive: true });

const n = (v) => (Math.round(v * 100) / 100).toString();
const rad = (deg) => (deg * Math.PI) / 180;
// Screen angles: 0° = right, clockwise positive (SVG y grows down).
const at = ([cx, cy], r, deg) => [cx + r * Math.cos(rad(deg)), cy + r * Math.sin(rad(deg))];

// limb = outer ring and shackle; brass = brass parts on the dark sky disc;
// sky = the disc; line = horizon arcs; star = the star on the background;
// starSky = the star on the disc; ink = the letter of the monogram.
const PALETTES = {
    night: {
        limb: '#e5b25c', brass: '#e5b25c', sky1: '#5a61e0', sky2: '#151d36',
        line: '#9aa2ff', star: '#ffffff', starSky: '#ffffff', ink: '#e8ecf8',
    },
    day: {
        limb: '#a2701f', brass: '#e5b25c', sky1: '#4950c9', sky2: '#141b2d',
        line: '#5a61e0', star: '#2f3a92', starSky: '#ffffff', ink: '#10182b',
    },
    mono: Object.fromEntries(
        ['limb', 'brass', 'sky1', 'sky2', 'line', 'star', 'starSky', 'ink'].map((k) => [k, 'currentColor']),
    ),
    vars: {
        limb: 'var(--logo-limb)', brass: 'var(--logo-brass)', sky1: 'var(--logo-sky-1)', sky2: 'var(--logo-sky-2)',
        line: 'var(--logo-line)', star: 'var(--logo-star)', starSky: 'var(--logo-star-sky)', ink: 'var(--logo-ink)',
    },
};

/** Four-pointed star with concave sides, like a star pointer on a rete. */
function sparkle([cx, cy], R, w) {
    return (
        `M${n(cx)} ${n(cy - R)}` +
        `Q${n(cx + w)} ${n(cy - w)} ${n(cx + R)} ${n(cy)}` +
        `Q${n(cx + w)} ${n(cy + w)} ${n(cx)} ${n(cy + R)}` +
        `Q${n(cx - w)} ${n(cy + w)} ${n(cx - R)} ${n(cy)}` +
        `Q${n(cx - w)} ${n(cy - w)} ${n(cx)} ${n(cy - R)}Z`
    );
}

/** Where a ray from `from` at `deg` meets a circle (centre, r). */
function rayCircle(from, deg, centre, r) {
    const [dx, dy] = [Math.cos(rad(deg)), Math.sin(rad(deg))];
    const [fx, fy] = [from[0] - centre[0], from[1] - centre[1]];
    const b = fx * dx + fy * dy;
    const t = -b + Math.sqrt(b * b - (fx * fx + fy * fy - r * r));
    return [from[0] + t * dx, from[1] + t * dy];
}

/** Arc through two points that bulges `sag` units towards the top. */
function hump([x1, y1], [x2, y2], sag) {
    const half = Math.hypot(x2 - x1, y2 - y1) / 2;
    const r = (half * half + sag * sag) / (2 * sag);
    return `M${n(x1)} ${n(y1)}A${n(r)} ${n(r)} 0 0 1 ${n(x2)} ${n(y2)}`;
}

/** The shackle ring on top of the limb — what makes a circle read as an astrolabe. */
const shackle = (p, cy = 7.6, r = 3.7, w = 2.2) =>
    `<circle cx="32" cy="${cy}" r="${r}" fill="none" stroke="${p.limb}" stroke-width="${w}"/>`;

// ---------- A · Rete: a night-sky disc, off-centre ecliptic ring, a rule sighting a star ----------
function markA(p, { mono = false, id = 'a' } = {}) {
    const C = [32, 35];
    const E = [32, 31];
    const rule = -60;
    const starAt = rayCircle(C, rule, E, 13);
    const [r1, r2] = [at(C, 22.2, rule), at(C, 22.2, rule + 180)];
    const defs = mono
        ? ''
        : `<defs><radialGradient id="sky-${id}" cx=".38" cy=".3" r=".8">` +
          `<stop offset="0" stop-color="${p.sky1}"/><stop offset="1" stop-color="${p.sky2}"/></radialGradient></defs>`;
    return (
        defs +
        shackle(p) +
        (mono ? '' : `<circle cx="32" cy="35" r="22.2" fill="url(#sky-${id})"/>`) +
        `<circle cx="32" cy="35" r="23.5" fill="none" stroke="${p.limb}" stroke-width="2.6"/>` +
        `<circle cx="${E[0]}" cy="${E[1]}" r="13" fill="none" stroke="${p.brass}" stroke-width="1.7"/>` +
        `<path d="M${n(r1[0])} ${n(r1[1])}L${n(r2[0])} ${n(r2[1])}" stroke="${p.brass}" stroke-width="1.5"/>` +
        `<circle cx="32" cy="35" r="2.3" fill="${p.brass}"/>` +
        `<path d="${sparkle(starAt, 7, 1.45)}" fill="${p.starSky}"/>`
    );
}

// ---------- B · Ascendant: a star rising over the horizon arc of the plate ----------
function markB(p) {
    const C = [32, 35];
    const [l1, r1] = [at(C, 22.2, 158), at(C, 22.2, 22)];
    const [l2, r2] = [at(C, 22.2, 136), at(C, 22.2, 44)];
    return (
        shackle(p) +
        `<circle cx="32" cy="35" r="23.5" fill="none" stroke="${p.limb}" stroke-width="2.6"/>` +
        `<path d="${hump(l1, r1, 6.5)}" fill="none" stroke="${p.line}" stroke-width="2.2"/>` +
        `<path d="${hump(l2, r2, 4.5)}" fill="none" stroke="${p.line}" stroke-width="1.3" opacity=".55"/>` +
        `<path d="${sparkle([32, 25], 9.5, 1.9)}" fill="${p.star}"/>` +
        `<circle cx="44.5" cy="29.5" r="1.6" fill="${p.limb}"/>`
    );
}

// ---------- C · Monogram: a serif A, a horizon for its crossbar, a star for its apex ----------
function markC(p) {
    const legs =
        // thin left stroke, thick right stroke, as in an old-style serif
        `M30.9 13L33.5 13L17.6 56.6L15 56.6Z` +
        `M30.6 13L37 13L50.6 56.6L44.1 56.6Z` +
        // foot serifs
        `M11.4 56.4H21.4V58.6H11.4Z` +
        `M40.4 56.4H54.4V58.6H40.4Z`;
    return (
        `<path d="${legs}" fill="${p.ink}"/>` +
        `<path d="${hump([8, 45.5], [56, 45.5], 7)}" fill="none" stroke="${p.limb}" stroke-width="2.3" stroke-linecap="round"/>` +
        `<path d="${sparkle([33.2, 10.5], 9, 1.8)}" fill="${p.line}"/>`
    );
}

// ---------- Favicon: A, simplified for 16–32 px, always on its night tile ----------
function favicon() {
    const p = PALETTES.night;
    const C = [16, 18.4];
    const E = [16, 16.6];
    const starAt = rayCircle(C, -60, E, 6.4);
    const [r1, r2] = [at(C, 10, -60), at(C, 10, 120)];
    return svg(
        '0 0 32 32',
        `<defs><radialGradient id="sky" cx=".38" cy=".3" r=".8"><stop offset="0" stop-color="${p.sky1}"/>` +
            `<stop offset="1" stop-color="${p.sky2}"/></radialGradient></defs>` +
            `<rect width="32" height="32" rx="7" fill="#0b1022"/>` +
            `<circle cx="16" cy="4.6" r="2.3" fill="none" stroke="${p.limb}" stroke-width="1.6"/>` +
            `<circle cx="${C[0]}" cy="${C[1]}" r="10.4" fill="url(#sky)"/>` +
            `<circle cx="${C[0]}" cy="${C[1]}" r="11" fill="none" stroke="${p.limb}" stroke-width="2"/>` +
            `<circle cx="${E[0]}" cy="${E[1]}" r="6.4" fill="none" stroke="${p.brass}" stroke-width="1.2"/>` +
            `<path d="M${n(r1[0])} ${n(r1[1])}L${n(r2[0])} ${n(r2[1])}" stroke="${p.brass}" stroke-width="1.1"/>` +
            `<path d="${sparkle(starAt, 4.6, 1)}" fill="${p.starSky}"/>`,
    );
}

const MARKS = { a: markA, b: markB, c: markC };

function svg(viewBox, inner) {
    return `<svg xmlns="http://www.w3.org/2000/svg" viewBox="${viewBox}">${inner}</svg>\n`;
}

/** App icon: the mark on a rounded night tile. */
function icon(key, mark) {
    const defs =
        `<defs><linearGradient id="tile-${key}" x1="0" y1="0" x2="0" y2="1">` +
        '<stop offset="0" stop-color="#1a2145"/><stop offset="1" stop-color="#060a15"/></linearGradient></defs>';
    return svg(
        '0 0 64 64',
        defs +
            `<rect width="64" height="64" rx="14" fill="url(#tile-${key})"/>` +
            `<g transform="translate(32 32) scale(.76) translate(-32 -31.5)">${mark(PALETTES.night, { id: `icon-${key}` })}</g>`,
    );
}

for (const [key, mark] of Object.entries(MARKS)) {
    for (const tone of ['night', 'day', 'mono']) {
        const svgText = svg('0 0 64 64', mark(PALETTES[tone], { mono: tone === 'mono', id: `${key}-${tone}` }));
        writeFileSync(join(out, `astrolabe-${key}-${tone}.svg`), svgText);
    }
    writeFileSync(join(out, `astrolabe-${key}-icon.svg`), icon(key, mark));
}
writeFileSync(join(out, 'favicon.svg'), favicon());

const sprite =
    '<svg class="sprite" aria-hidden="true" focusable="false">' +
    Object.entries(MARKS)
        .map(([key, mark]) => `<symbol id="mark-${key}" viewBox="0 0 64 64">${mark(PALETTES.vars, { id: `sprite-${key}` })}</symbol>`)
        .join('') +
    '</svg>';

for (const page of ['index.html', 'brand.html']) {
    const file = join(root, page);
    if (!existsSync(file)) continue;
    const html = readFileSync(file, 'utf8');
    const next = html.replace(
        /(<!-- logo-sprite:start -->)[\s\S]*?(<!-- logo-sprite:end -->)/,
        `$1\n        ${sprite}\n        $2`,
    );
    if (next !== html) writeFileSync(file, next);
}

// The chosen mark (A, 9 Oct 2026) for the WordPress theme: its own sprite and files.
const chosen =
    '<svg class="sprite" aria-hidden="true" focusable="false">' +
    `<symbol id="mark-a" viewBox="0 0 64 64">${markA(PALETTES.vars, { id: 'sprite-a' })}</symbol></svg>\n`;
writeFileSync(join(out, 'mark-a-sprite.svg'), chosen);

// Wordmark and horizontal lockup: "AstroLabe" in outlines (tools/wordmark/wordmark.json),
// cap height 28 on the mark's 64 grid, cap middle on the disc's centre (y 35).
const word = JSON.parse(readFileSync(join(root, 'tools', 'wordmark', 'wordmark.json'), 'utf8'));
const ws = 28 / word.capHeight;
const INK = { night: '#e8ecf8', day: '#10182b', mono: 'currentColor' };
const wordPath = (x, baseline, fill) =>
    `<path transform="translate(${n(x)} ${n(baseline)}) scale(${n(ws * 1000) / 1000})" fill="${fill}" d="${word.d}"/>`;
const wordWidth = (word.bbox.maxX - word.bbox.minX) * ws;
const wordTop = word.bbox.maxY * ws;
const wordBottom = -word.bbox.minY * ws;
for (const tone of ['night', 'day', 'mono']) {
    const x0 = -word.bbox.minX * ws;
    writeFileSync(
        join(out, `astrolabe-wordmark-${tone}.svg`),
        svg(`0 ${n(-wordTop - 1)} ${n(wordWidth)} ${n(wordTop + wordBottom + 2)}`, wordPath(x0, 0, INK[tone])),
    );
    const left = 72 - word.bbox.minX * ws; // 15 units after the limb's right edge (56.8)
    const width = 72 + wordWidth + 2;
    writeFileSync(
        join(out, `astrolabe-lockup-${tone}.svg`),
        svg(`6 2 ${n(width - 6)} 60`, markA(PALETTES[tone], { mono: tone === 'mono', id: `lockup-${tone}` }) + wordPath(left, 49, INK[tone])),
    );
}

// The WordPress theme in this repository (ASTROLABE_THEME overrides the path).
const theme = join(process.env.ASTROLABE_THEME || join(root, '..', 'theme', 'astrolabe'), 'assets', 'logo');
if (existsSync(theme)) {
    const files = ['mark-a-sprite.svg', 'favicon.svg', 'astrolabe-a-night.svg', 'astrolabe-a-day.svg', 'astrolabe-a-mono.svg', 'astrolabe-a-icon.svg'];
    for (const tone of ['night', 'day', 'mono']) files.push(`astrolabe-wordmark-${tone}.svg`, `astrolabe-lockup-${tone}.svg`);
    for (const file of files) {
        writeFileSync(join(theme, file), readFileSync(join(out, file)));
    }
}

console.log('Logos written to', out);
