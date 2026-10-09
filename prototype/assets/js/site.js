// AstroLabe presentation site — prototype behaviour.
// Theme memory, the logo-concept switch, the mobile menu, the decorative
// wheels and the sample chart (drawn like the app's ChartWheel), and a beta
// form that sends nothing.
(function () {
    'use strict';

    const root = document.documentElement;
    const store = {
        get(key) {
            try {
                return localStorage.getItem(key);
            } catch (e) {
                return null;
            }
        },
        set(key, value) {
            try {
                localStorage.setItem(key, value);
            } catch (e) {}
        },
    };

    /* ---------- Theme ---------- */
    const askedTheme = new URLSearchParams(location.search).get('theme');
    if (askedTheme === 'day' || askedTheme === 'night') {
        if (askedTheme === 'day') root.dataset.theme = 'day';
        else delete root.dataset.theme;
        store.set('astrolabe.site.theme', askedTheme);
    }
    document.querySelectorAll('[data-theme-toggle]').forEach((button) =>
        button.addEventListener('click', () => {
            const day = root.dataset.theme !== 'day';
            if (day) root.dataset.theme = 'day';
            else delete root.dataset.theme;
            store.set('astrolabe.site.theme', day ? 'day' : 'night');
        }),
    );

    /* ---------- Logo concept (prototype only) ---------- */
    const CONCEPTS = ['a', 'b', 'c'];
    function setLogo(key) {
        document.querySelectorAll('use.logo-use').forEach((use) => use.setAttribute('href', `#mark-${key}`));
        document
            .querySelectorAll('[data-logo]')
            .forEach((button) => button.setAttribute('aria-pressed', String(button.dataset.logo === key)));
        store.set('astrolabe.site.logo', key);
    }
    const asked = new URLSearchParams(location.search).get('logo');
    const remembered = store.get('astrolabe.site.logo');
    setLogo(CONCEPTS.includes(asked) ? asked : CONCEPTS.includes(remembered) ? remembered : 'a');
    document.querySelectorAll('[data-logo]').forEach((button) => button.addEventListener('click', () => setLogo(button.dataset.logo)));
    // ?clean hides the switcher (for screenshots, e.g. the theme's screenshot.png).
    if (new URLSearchParams(location.search).has('clean')) document.querySelector('.proto-switch')?.remove();

    /* ---------- Header ---------- */
    const header = document.querySelector('.site-header');
    const onScroll = () => header && header.classList.toggle('is-scrolled', window.scrollY > 8);
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();

    const menu = document.querySelector('[data-menu]');
    const nav = document.getElementById('site-nav');
    if (menu && nav) {
        const close = () => {
            nav.classList.remove('open');
            menu.setAttribute('aria-expanded', 'false');
        };
        menu.addEventListener('click', () => {
            const open = !nav.classList.contains('open');
            nav.classList.toggle('open', open);
            menu.setAttribute('aria-expanded', String(open));
        });
        nav.querySelectorAll('a').forEach((a) => a.addEventListener('click', close));
        document.addEventListener('keydown', (e) => e.key === 'Escape' && close());
    }

    document.querySelectorAll('[data-year]').forEach((el) => (el.textContent = String(new Date().getFullYear())));

    /* ---------- SVG helpers ---------- */
    const NS = 'http://www.w3.org/2000/svg';
    function el(name, attrs, parent, text) {
        const node = document.createElementNS(NS, name);
        for (const [k, v] of Object.entries(attrs || {})) node.setAttribute(k, String(v));
        if (text !== undefined) node.textContent = text;
        if (parent) parent.appendChild(node);
        return node;
    }
    const r2 = (v) => Math.round(v * 100) / 100;

    /* ---------- Decorative wheel (the app's WheelArt) ---------- */
    document.querySelectorAll('[data-wheel-art]').forEach((svg) => {
        const C = 200;
        const point = (deg, r) => [C + r * Math.cos((deg * Math.PI) / 180), C - r * Math.sin((deg * Math.PI) / 180)];
        const g1 = el('g', { fill: 'none', stroke: 'var(--wheel-art)', 'stroke-width': 1 }, svg);
        [196, 170, 122, 116].forEach((r) => el('circle', { cx: C, cy: C, r }, g1));
        const g2 = el('g', { stroke: 'var(--wheel-art)', 'stroke-width': 1 }, svg);
        for (let i = 0; i < 12; i++) {
            const [x1, y1] = point(i * 30, 122);
            const [x2, y2] = point(i * 30, 196);
            el('line', { x1: r2(x1), y1: r2(y1), x2: r2(x2), y2: r2(y2) }, g2);
        }
        for (let i = 0; i < 72; i++) {
            const [x1, y1] = point(i * 5, 170);
            const [x2, y2] = point(i * 5, 178);
            el('line', { x1: r2(x1), y1: r2(y1), x2: r2(x2), y2: r2(y2), opacity: 0.6 }, g2);
        }
        const nodes = [12, 74, 118, 155, 203, 248, 291, 330];
        const g3 = el('g', { stroke: 'var(--wheel-web)', 'stroke-width': 0.8, opacity: 0.85 }, svg);
        nodes.forEach((a, i) =>
            nodes.slice(i + 1).forEach((b) => {
                const [x1, y1] = point(a, 116);
                const [x2, y2] = point(b, 116);
                el('line', { x1: r2(x1), y1: r2(y1), x2: r2(x2), y2: r2(y2) }, g3);
            }),
        );
    });

    /* ---------- Sample chart ----------
       A fictional client born in May 1991 in the afternoon (Placidus, tropical).
       Positions are rounded illustrations, not a calculation. */
    const VS = '︎'; // text form of a symbol, never an emoji
    const SIGNS = [
        ['♈', 'fire'], ['♉', 'earth'], ['♊', 'air'], ['♋', 'water'],
        ['♌', 'fire'], ['♍', 'earth'], ['♎', 'air'], ['♏', 'water'],
        ['♐', 'fire'], ['♑', 'earth'], ['♒', 'air'], ['♓', 'water'],
    ];
    const NATAL = {
        asc: 198,
        mc: 112,
        cusps: [198, 226, 256, 292, 327, 356, 18, 46, 76, 112, 147, 176],
        planets: [
            ['☉', 53.3, true], ['☽', 98.1, true], ['☿', 64.4], ['♀', 77.2], ['♂', 115.6],
            ['♃', 128.3], ['♄', 305.1], ['♅', 282.2], ['♆', 286.4], ['♇', 228.5],
        ],
    };
    const TRANSITS = [['♃', 138.4], ['♄', 10.2], ['♅', 63.1], ['♆', 0.6], ['♇', 303.2]];
    const ASPECTS = [
        [60, 'soft', 4], [90, 'hard', 6], [120, 'soft', 6], [180, 'hard', 7],
    ];

    /** Pushes glyphs apart until neighbours are at least `sep` degrees apart. */
    function spread(lons, sep) {
        const items = lons.map((l, i) => ({ i, d: l })).sort((a, b) => a.d - b.d);
        for (let pass = 0; pass < 80 && items.length > 1; pass++) {
            let moved = false;
            for (let k = 0; k < items.length; k++) {
                const a = items[k];
                const b = items[(k + 1) % items.length];
                const gap = (((b.d - a.d) % 360) + 360) % 360;
                if (gap < sep) {
                    const push = (sep - gap) / 2;
                    a.d -= push;
                    b.d += push;
                    moved = true;
                }
            }
            if (!moved) break;
        }
        const out = [];
        items.forEach((x) => (out[x.i] = x.d));
        return out;
    }

    // compact: drawn small (the hero mock) — larger glyphs, no degree or angle labels, like the app's is-compact.
    function drawWheel(svg, { outer, compact } = {}) {
        const C = outer ? 220 : 200;
        const R = { band: 192, face: 164, tick: 158, marker: 157, glyph: compact ? 136 : 139, deg: 118, house: 87, label: 151, inner: 74 };
        if (compact) svg.classList.add('is-compact');
        const xy = (lon, r) => {
            const t = ((180 + lon - NATAL.asc) * Math.PI) / 180;
            return [r2(C + r * Math.cos(t)), r2(C - r * Math.sin(t))];
        };
        const line = (a, ra, rb, attrs, parent) => {
            const [x1, y1] = xy(a, ra);
            const [x2, y2] = xy(a, rb);
            return el('line', { x1, y1, x2, y2, ...attrs }, parent);
        };

        if (outer) {
            el('circle', { cx: C, cy: C, r: 217, class: 'outer-band' }, svg);
        }
        el('circle', { cx: C, cy: C, r: R.band, class: 'band' }, svg);
        el('circle', { cx: C, cy: C, r: R.face, class: 'face' }, svg);
        el('circle', { cx: C, cy: C, r: R.inner, class: 'ring' }, svg);

        const ticks = el('g', {}, svg);
        for (let d = 0; d < 360; d += 5) line(d, R.face, d % 10 ? R.face - 4 : R.tick, { class: 'tick' }, ticks);
        SIGNS.forEach(([glyph, element], i) => {
            line(i * 30, R.face, R.band, { class: 'sign-edge' }, svg);
            const [x, y] = xy(i * 30 + 15, (R.face + R.band) / 2);
            el('text', { x, y, class: `sign-glyph glyph el-${element}` }, svg, glyph + VS);
        });

        // Houses: cusps, then the two axes on top, then house numbers.
        NATAL.cusps.forEach((cusp, i) => {
            if (i % 3 !== 0) line(cusp, R.inner, R.face, { class: 'cusp' }, svg);
            const next = NATAL.cusps[(i + 1) % 12];
            const mid = cusp + ((((next - cusp) % 360) + 360) % 360) / 2;
            const [x, y] = xy(mid, R.house);
            el('text', { x, y, class: 'house-num' }, svg, String(i + 1));
        });
        [NATAL.asc, NATAL.mc].forEach((angle) => {
            line(angle, R.inner, R.band, { class: 'angle-line' }, svg);
            line(angle + 180, R.inner, R.face, { class: 'angle-line', opacity: 0.6 }, svg);
        });
        if (!compact) {
            [[NATAL.asc, 'ASC', -5], [NATAL.mc, 'MC', -5]].forEach(([angle, label, shift]) => {
                const [x, y] = xy(angle + shift, R.label);
                el('text', { x, y, class: 'angle-label' }, svg, label);
            });
        }

        // Aspects between natal planets (majors; conjunctions are not drawn).
        const aspects = el('g', {}, svg);
        NATAL.planets.forEach(([, a, la], i) =>
            NATAL.planets.slice(i + 1).forEach(([, b, lb]) => {
                let diff = Math.abs(a - b) % 360;
                if (diff > 180) diff = 360 - diff;
                const bonus = la || lb ? 1.5 : 0;
                const hit = ASPECTS.find(([angle, , orb]) => Math.abs(diff - angle) <= orb + bonus);
                if (!hit) return;
                const [x1, y1] = xy(a, R.inner);
                const [x2, y2] = xy(b, R.inner);
                el('line', { x1, y1, x2, y2, class: `asp asp-${hit[1]}` }, aspects);
            }),
        );

        // Planets: a marker at the true degree, the glyph where it has room.
        const shown = spread(
            NATAL.planets.map((p) => p[1]),
            compact ? 11 : 9,
        );
        NATAL.planets.forEach(([glyph, lon], i) => {
            line(lon, R.face, R.marker, { class: 'marker' }, svg);
            const [gx, gy] = xy(shown[i], R.glyph);
            el('text', { x: gx, y: gy, class: 'planet-glyph glyph' }, svg, glyph + VS);
            if (compact) return;
            const [dx, dy] = xy(shown[i], R.deg);
            el('text', { x: dx, y: dy, class: 'planet-deg' }, svg, `${Math.floor(lon % 30)}°`);
        });

        if (outer) {
            const placed = spread(
                outer.map((p) => p[1]),
                10,
            );
            outer.forEach(([glyph, lon], i) => {
                line(lon, R.band, R.band + 6, { class: 'outer-marker' }, svg);
                const [x, y] = xy(placed[i], 206);
                el('text', { x, y, class: 'outer-glyph glyph' }, svg, glyph + VS);
            });
        }
    }

    const hero = document.getElementById('hero-wheel');
    if (hero) drawWheel(hero, { compact: true });
    const spot = document.getElementById('spot-wheel');
    if (spot) drawWheel(spot, { outer: TRANSITS });

    /* ---------- Beta form (prototype: nothing is sent) ---------- */
    const form = document.getElementById('beta-form');
    if (form) {
        const error = form.querySelector('.form-error');
        const done = form.parentElement.querySelector('.form-done');
        form.addEventListener('submit', (event) => {
            event.preventDefault();
            const problems = [];
            if (!form.name.value.trim()) problems.push('your name');
            if (!form.email.validity.valid || !form.email.value.trim()) problems.push('a valid email');
            if (!form.consent.checked) problems.push('your consent to be emailed');
            if (problems.length) {
                error.textContent = `Please add ${problems.join(', ')}.`;
                error.hidden = false;
                return;
            }
            error.hidden = true;
            form.hidden = true;
            done.hidden = false;
            done.focus();
        });
    }
})();
