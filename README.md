# AstroLabe — presentation site

The public site of [AstroLabe](https://astrolabe.online), practice management for professional astrologers. The app itself is a separate project (`app.astrolabe.online`); this site only links to it.

| Folder | What it is |
|---|---|
| `theme/astrolabe/` | The WordPress theme (classic PHP, no build step, ACF free with Local JSON). See its `README.md`. |
| `prototype/` | The approved static prototype (`index.html`) and the logo proposal (`brand.html`). |
| `prototype/tools/logos.mjs` | Builds every logo file from geometry and copies the chosen ones into the theme. |
| `prototype/tools/wordmark/` | The “AstroLabe” wordmark in outlines (Newsreader Medium, SIL OFL). |

Documentation is in Serbian, the site itself in English.

## Local setup (Windows / WAMP)

- WordPress: `C:\wamp64\www\astrolabe.online.website__10.2026`, http://dev.lcl.astrolabe.website
- `wp-content\themes\astrolabe` is a **junction** to `theme\astrolabe` in this repository, so ACF writes field changes (`acf-json/`) straight into the repo:

  ```powershell
  New-Item -ItemType Junction -Path "C:\wamp64\www\astrolabe.online.website__10.2026\wp-content\themes\astrolabe" -Target "<repo>\theme\astrolabe"
  ```

- Do **not** delete the theme from WordPress admin (Appearance → Themes → Delete): it would delete the files in the repository through the junction.
- Logos: `node prototype/tools/logos.mjs`.

## Production

Hetzner Webhosting (WordPress), separate from the app server. The theme folder is copied to `wp-content/themes/astrolabe`; then ACF → *Sync available* for anything new in `acf-json/`.
