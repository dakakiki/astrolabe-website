# Wordmark „AstroLabe“ u krivama

`wordmark.json` je reč „AstroLabe“ kao jedna SVG putanja: Newsreader Medium (500, SIL OFL), razmak 0.005em, kerning kakav browser crta na sajtu. `tools/logos.mjs` iz nje pravi `astrolabe-wordmark-*.svg` i `astrolabe-lockup-*.svg`.

Ponovna izrada (samo ako se menja font, debljina ili razmak):

1. Preuzmi `newsreader-latin-500-normal.woff` sa Bunny Fonts u ovaj folder (link je u CSS-u `https://fonts.bunny.net/css?family=newsreader:500`).
2. Otvori `measure.html` u Edge-u bez interfejsa i sačuvaj pozicije slova:
   `msedge --headless=new --allow-file-access-from-files --dump-dom file:///…/measure.html` → sadržaj `<pre id="out">` u `positions.json`.
3. `node outline.mjs newsreader-latin-500-normal.woff positions.json out.json`, pa `d`, `capHeight` i `bbox` iz `out.json` prenesi u `wordmark.json`.
4. `node ../logos.mjs`.

Fajl fonta se ne čuva ovde; potreban je samo za ponovnu izradu.
