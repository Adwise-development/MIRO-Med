# Adwise — block theme blueprint

Czysty startowy **WordPress block theme** pod workflow **Figma → custom bloki Gutenberga** (React + server-side render, `@wordpress/scripts` + Webpack 5, SCSS/BEM, PHP).

Blueprint startuje **bez bloków** — buildowalny od razu po instalacji. Bloki dodajesz per projekt wg `docs/block-template.md`. Cała dokumentacja workflow jest w `docs/` (self-contained).

## Wymagania
- WordPress 6.9+, PHP 7.4+
- Node 18+ / npm

## Szybki start
```bash
npm install
npm run build      # produkcja (lub `npm run start` — dev + watch)
```
Aktywuj theme w *Wygląd → Motywy*.

| Skrypt | Co |
|--------|-----|
| `npm run start` | webpack dev + watch |
| `npm run build` | build produkcyjny |
| `npm run lint:js` | ESLint (`blocks/`) |
| `npm run lint:css` | Stylelint (`blocks/**/*.scss`) |
| `npm run format` | Prettier (`blocks/`) |

## Co jest w środku
- **Build:** auto-discovery `blocks/*/index.js|view.js` + kopiowanie `block.json`/`*.php` do `build/`. Fallback entry (`assets/src/index.js`) → build przechodzi przy zero bloków.
- **Rejestracja bloków:** glob `build/blocks/*/block.json` w `functions.php`.
- **SVG upload** z minimalnym sanitizerem (cap `edit_posts`).
- **Menu:** `register_nav_menus` (primary, footer) → *Wygląd → Menu*.
- **Login KV:** `inc/adwise-login.php` — branded split-screen login (parallax). Brand = adwise; podmiana → `docs/recipes/login-page/brand-swap.md`.
- **Security hardening:** XML-RPC off, REST users hidden, author-enum block, hide WP version.
- **Content-length limit, anchor injection, smooth scroll.**
- **Dokumentacja workflow** w `docs/` (patterns, recipes, guides).

## Deploy / produkcja
Slug logowania (**WPS Hide Login**) i brute-force (**Limit Login Attempts Reloaded**) to **pluginy per-deploy — NIE w repo**. Konfiguracja + weryfikacja curl + hardening produkcyjny: `docs/recipes/login-page/workflow.md` i `docs/migracja-prod.md`.

## Nowy projekt z blueprintu
1. Sklonuj, zmień nazwę folderu i `Theme Name` w `style.css`.
2. Podmień namespace/prefix: `adwise` → twój (folder, stałe `ADWISE_*`, textdomain, namespace bloków, brand w `inc/`).
3. Wgraj tokeny do `theme.json` po analizie Figmy (`docs/figma-to-block.md`).
4. Aktualizuj `project.md`. Buduj bloki wg `docs/block-template.md`.

## Struktura
```
adwise/
├── CLAUDE.md project.md          # workflow rules + stan projektu (auto-load)
├── style.css theme.json functions.php
├── package.json webpack.config.js
├── templates/ parts/             # FSE template + parts
├── inc/adwise-login.php          # login KV
├── assets/{src,css,js,fonts,icons}/
├── blocks/                       # puste — bloki per projekt
└── docs/                         # workflow: patterns/, recipes/, guides
```

## Licencja
GPL-2.0-or-later.
