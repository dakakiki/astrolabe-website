# AstroLabe — WordPress tema

Tema prezentacionog sajta `astrolabe.online`, napravljena iz odobrenog prototipa (`prototype/` u ovom repou). Logo A (Rete), tokeni boja i fontovi isti kao u aplikaciji, noćna i dnevna tema.

Klasična PHP tema, bez build koraka. Zahtevi: WordPress 6.5+ (provereno na 7.1.3), PHP 8.1+, **Advanced Custom Fields (free) 6.1+** (provereno na 6.8.10).

Repo: https://github.com/dakakiki/astrolabe-website (`theme/astrolabe`). Lokalni WordPress (`C:\wamp64\www\astrolabe.online.website__10.2026`, http://dev.lcl.astrolabe.website) ima `wp-content\themes\astrolabe` kao junction na ovaj folder — zato ACF izmene polja upisuje pravo u repo. Temu **ne briši** iz WordPress admina (obrisala bi fajlove u repou). `Update URI: false` u `style.css` sprečava WordPress da je „ažurira“ temom istog imena sa wordpress.org.

## ACF: sve je u `acf-json/`

| Fajl | Šta je |
|---|---|
| `group_astro_front.json` | **Front page sections** — tabovi sa tekstovima sekcija na početnoj stranici |
| `group_astro_feature.json`, `group_astro_trust.json`, `group_astro_plan.json` | Polja stavki: ikonica i tekst; za paket oznaka, cena, period, godišnja cena, istaknut |
| `post_type_astro_feature.json`, `_trust`, `_plan`, `_faq` | **ACF tipovi sadržaja** Features, Privacy points, Plans, Questions (u meniju *Site content*) |

- Sve se vidi u **ACF → Field Groups** i **ACF → Post Types** i tamo se menja; ACF izmene upisuje nazad u `acf-json/` (`inc/acf.php` postavlja putanje).
- Na drugoj instalaciji (npr. Hetzner): ACF pokaže *Sync available* — klik na Sync i polja/tipovi su u bazi.
- Placeholder-i polja na početnoj su podrazumevani tekstovi iz `inc/defaults.php` (dodaju se pri prikazu forme).

## Postavljanje na novoj instalaciji

1. Folder `astrolabe` u `wp-content/themes/`, aktiviraj temu; instaliraj i aktiviraj ACF.
2. **ACF → Field Groups / Post Types → Sync available → Sync.**
3. Stranica „Home“ kao početna: Settings → Reading → *A static page*.
4. **Site content → Create editable copies of the defaults** — funkcije, tačke privatnosti, paketi i pitanja postaju stavke koje se menjaju i ređaju (polje *Order*).
5. **Appearance → Customize → AstroLabe:** adresa aplikacije (na nju vode „Sign in“, „Start free trial“ → `/register` i pravni linkovi), kontakt mejl, naziv radnje u futeru, napomena u futeru.
6. Po želji: Settings → Privacy (stranica politike privatnosti sajta; dok nije objavljena, linkovi vode na Privacy aplikacije), Settings → General (naziv i podnaslov sajta idu u naslov kartice).

Lokalno je ovo već urađeno (9. 10. 2026): sync, „Home“ (ID 87) kao početna, 24 stavke.

Bez ACF-a sajt se i dalje prikazuje ceo, sa podrazumevanim tekstovima.

## Kako je sadržaj organizovan

| Šta | Gde se menja |
|---|---|
| Tekstovi sekcija (naslovi, uvodi, dugmad, napomene) | Početna stranica → ACF tabovi ispod editora. Prazno polje = podrazumevani tekst. `<em>…</em>` daje plavi kurziv u naslovu. |
| Funkcije, tačke privatnosti, paketi, pitanja | **Site content** → liste (ACF free nema Repeater, pa je svaka stavka zaseban post) |
| Ilustracije: makETa profila, točak karte, vremenska linija, „sedam mesta“, činjenice o kartama | U kodu: `inc/defaults.php` i `assets/js/site.js` (ilustracija proizvoda, menja se uz aplikaciju) |
| Meniji | Appearance → Menus: *Header* i *Footer — legal* (opciono) |
| Obične stranice | Pages, šablon `page.php` sa tipografijom sajta |

## Free trial (umesto prijave za betu, 13. 11. 2026)

- Zatvorene bete nema: sva dugmad („Start your free trial“ u hero-u, ceni, sekciji *Free trial* i na kraju, „Start free trial“ u zaglavlju i futeru) vode na registraciju u aplikaciji — `astrolabe_signup_url()` = adresa aplikacije + `/register`.
- Sekcija `#trial` (`template-parts/front/trial.php`, ACF tab *Free trial*) objašnjava trial: 14 dana bez kartice, do 5 klijenata, do 30 proračuna dnevno, portal i snimak karte uz paket; posle triala praksa samo za čitanje. **Tekst mora ostati u skladu sa pravilima u aplikaciji** (spec 07, „Trial“).
- Forma za prijavu, tip sadržaja *Beta requests* (`inc/beta-requests.php`) i podešavanje „Beta requests go to“ su uklonjeni; sajt više ne prikuplja lične podatke.

## Analitika (bez banera za kolačiće)

Preporuka: **Koko Analytics** u režimu **bez kolačića** — podaci ostaju u bazi sajta. Alternativa: Plausible (plaćen, EU). Ako se ikad doda analitika sa kolačićima, promeni napomenu „No tracking“ u Customizer-u. Politika privatnosti sajta treba da pomene analitiku (forme više nema).

## Logo i fontovi

- `node prototype/tools/logos.mjs` (iz korena repoa) ponovo pravi sve logo fajlove i upisuje ih u `assets/logo/` ove teme: znak (`mark-a-sprite.svg`, `astrolabe-a-*.svg`), wordmark i horizontalni lockup u krivama (`astrolabe-wordmark-*`, `astrolabe-lockup-*`, Newsreader Medium), `favicon.svg`. PNG/ICO (`favicon.ico`, `apple-touch-icon.png`, `astrolabe-icon-192/512.png`) su izvezeni iz istih SVG-ova. Ne menjaj SVG ručno.
- Isti znak i ikonice koristi aplikacija (`BrandMark.vue`, `public/favicon.*`).
- Fontovi (OFL) se učitavaju sa Bunny Fonts (EU, bez kolačića), kao u aplikaciji.

## Fajlovi

```
style.css              zaglavlje teme
functions.php          učitava inc/*
acf-json/              ACF grupe polja i tipovi sadržaja (Local JSON)
inc/defaults.php       podrazumevani tekstovi (iz prototipa, trial od 13. 11. 2026)
inc/template-tags.php  pomoćne funkcije za šablone
inc/setup.php          podrške teme, stilovi i skripte, ikonice, Customizer, meniji
inc/content.php        meni „Site content“ i kopiranje podrazumevanih stavki
inc/acf.php            putanje za acf-json, placeholder-i
front-page.php         početna: template-parts/front/*.php redom
page.php, index.php, 404.php
template-parts/sprites.php   znak i ikonice (inline SVG)
assets/css/site.css    stilovi (tokeni iz aplikacije) + WordPress deo na kraju
assets/css/editor.css  izgled teksta u editoru stranica
assets/js/site.js      tema noć/dan, meni, točak karte
theme.json             paleta i fontovi u editoru (bez proizvoljnih boja)
```
