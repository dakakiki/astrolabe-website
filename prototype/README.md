# AstroLabe — prototip prezentacionog sajta

Statičan prototip sajta `astrolabe.online` i predlog logotipa. Bez servera i build koraka: otvori `index.html` u browseru (dvoklik). Fontovi se učitavaju sa Bunny Fonts, pa su tačni samo kada je računar na mreži.

## Šta je ovde

| Fajl | Šta je |
|---|---|
| `index.html` | Početna strana (jedna duga strana): hero sa profilom klijenta, problem „sedam mesta“, funkcije, karte, vremenska linija klijenta, privatnost, cene, prijava za betu, FAQ, futer |
| `brand.html` | Predlog logotipa: tri koncepta (A Rete — preporuka, B Ascendant, C Monogram), veličine, ikonica aplikacije, favicon, paleta, fontovi, pravila upotrebe, fajlovi |
| `assets/css/site.css` | Stilovi sajta; tokeni boja su 1:1 iz aplikacije (`resources/css/tokens.css`) |
| `assets/js/site.js` | Tema noć/dan, izbor koncepta logotipa, meni na telefonu, točak karte (crta se kao `ChartWheel` u aplikaciji), forma koja ništa ne šalje |
| `assets/logo/*.svg` | Logotipi: za svaki koncept `night`, `day`, `mono`, `icon`; `favicon.svg` (pojednostavljen A) |
| `tools/logos.mjs` | Generator logotipa iz geometrije — `node tools/logos.mjs` ponovo piše SVG fajlove, inline sprite u obe strane i logo fajlove u temi (`../theme/astrolabe/assets/logo`) |

## Korisni linkovi u prototipu

- `index.html?logo=b` / `?logo=c` — sajt sa drugim konceptom (ili dugmad „Logo A B C“ dole levo; izbor se pamti).
- `index.html?theme=day` — dnevna tema (ili dugme sunce/mesec u zaglavlju).

## Odluke u prototipu

- **Jezik:** engleski, kao aplikacija za betu.
- **Tema:** noć podrazumevano, dan kao u aplikaciji; isti tokeni, isti fontovi (Newsreader, Inter Tight, IBM Plex Mono) preko Bunny Fonts (EU, bez kolačića).
- **Glavni poziv:** „Request a beta invite“ — registracija je samo na poziv; forma u prototipu ništa ne šalje.
- **Cene:** prikazane kao *planirane* (19 / 29 / 49 EUR iz doc 07) uz napomenu da se potvrđuju posle bete; bez liste funkcija po paketu, jer granice paketa nisu odlučene.
- **Tvrdnje na sajtu** su samo ono što aplikacija već radi (Faze 4–8c2), plus „Hosted in the EU“ (potvrđeno 9. 10. 2026).
- **Swiss Ephemeris** je pomenut samo imenom (dozvoljeno ugovorom); izdavač i autori biblioteke se ne pominju (tačka 9 ugovora).
- **Uzorak karte** (Emma Clarke, maj 1991, Bristol) je izmišljen; položaji su zaokružene ilustracije, ne proračun.
- **Nazivi alata** u sekciji „sedam mesta“ su opšti (chat, kalendar, tabela…), bez tuđih robnih marki.

## Odlučeno 9. 10. 2026

- **Logo: koncept A (Rete).** B i C ostaju u `brand.html` kao zapis predloga.
- **Cene ostaju** na sajtu kao planirane.
- **„Hosted in the EU“ ostaje:** server će biti u Nemačkoj ili Finskoj (Hetzner).
- **WordPress tema** se pravi iz ovog prototipa (ACF free, polja i tipovi sadržaja kao ACF Local JSON).

## Otvoreno

- Adresa za kontakt (u prototipu `hello@astrolabe.online`) i naziv radnje u futeru (`[Business name]`) — u temi su to polja u Customizer-u.
- Politika privatnosti samog sajta (forma za betu, analitika); dok je nema, link vodi na Privacy aplikacije.
- Analitika: preporuka Koko Analytics u režimu bez kolačića (vidi README teme).

## Urađeno posle izbora (9. 10. 2026)

- Wordmark i horizontalni lockup u krivama (Newsreader Medium, `tools/wordmark/`), favicon.ico, apple-touch i ikonice 192/512.
- Znak A u aplikaciji (bočna traka, prijava, pravne strane, favicon) — commit 8a224ca, CI zelen.
- WordPress tema: `theme/astrolabe` u ovom repou (ACF Local JSON); lokalni WordPress je vidi preko junction-a, http://dev.lcl.astrolabe.website.

## Sledeći korak

Slike za društvene mreže (Open Graph), politika privatnosti sajta, SMTP za mejlove sa sajta.
