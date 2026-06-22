# Adwise — Figma → Gutenberg Block Theme (blueprint)

Czysty blueprint pod workflow **Figma → custom bloki Gutenberga** (React + server-side render, `@wordpress/scripts` + Webpack 5, SCSS/BEM, PHP). Język: polski (pl_PL).

Dokumentacja workflow jest **self-contained** w `docs/` — klonujesz repo i masz wszystko. Wartości specyficzne (tokeny, prefiksy) odczytuj z plików projektu (`style.css`, `theme.json`, `functions.php`), nie hardcoduj.

**Namespace bloków / textdomain / prefix PHP = `adwise`** (folder theme'u). Po sklonowaniu pod nowy projekt: zmień nazwę folderu, `Theme Name` w `style.css`, stałe `ADWISE_*`, textdomain, namespace bloków, brand w `inc/adwise-login.php`.

---

## Index plików — co gdzie

| Plik | Zawartość | Tryb |
|------|-----------|------|
| **CLAUDE.md** (ten) | Reguły, konwencje bloków, decision-guides, workflow, zasady bezwzględne | Auto-load |
| **project.md** (root) | Stan projektu: tokeny, strony, bloki, decyzje, log. Aktualizuj po każdej zmianie | Auto-load |
| **docs/figma-to-block.md** | Pobieranie z Figmy (kolejność API, tokeny → theme.json), Figma → clamp | Ad-hoc |
| **docs/css-conventions.md** | BEM, breakpointy, clamp, sekcja/inner, box-sizing, hover, iOS, tel/mailto | Ad-hoc |
| **docs/block-template.md** | Bare scaffolding nowego bloku (block.json/index/edit/save/render/scss/view) | Ad-hoc |
| **docs/project-template.md** | Szablon do utworzenia `project.md` na nowym projekcie | Ad-hoc |
| **docs/patterns/navbar-menu.md** | Navbar sticky + live preview WP menu + hamburger + mobile | Ad-hoc |
| **docs/patterns/forms.md** | CF7: preview w edytorze, walidacja, custom submit | Ad-hoc |
| **docs/patterns/backgrounds.md** | Tło sekcji (desktop/mobile, kształtne SVG), `<img>` vs background-image | Ad-hoc |
| **docs/patterns/media-images.md** | Obrazy: `object{id,url,alt}`, responsive srcset, media-remove, SVG upload | Ad-hoc |
| **docs/patterns/buttons-links.md** | Button + LinkControl popover, arrow animation, notched, URL chip | Ad-hoc |
| **docs/patterns/dynamic-blocks.md** | WP_Query + ServerSideRender + REST + load more | Ad-hoc |
| **docs/patterns/slider.md** | Swiper (karuzele, thumbs), gotcha importu CSS | Ad-hoc |
| **docs/patterns/animations.md** | Reveal (vanilla IntersectionObserver default, GSAP opcjonalnie) | Ad-hoc |
| **docs/patterns/editor-gotchas.md** | Sidebar vs inline, content-limit, template parts, SSR pułapki | Ad-hoc |
| **docs/plugin-mode.md** | Bloki jako plugin do istniejącego site'u (bootstrap, tokeny, scope, template) | Ad-hoc |
| **docs/optymalizacja.md** | Performance & a11y: cache, obrazy, fonty, JS, Core Web Vitals, WP_Query | Ad-hoc |
| **docs/migracja-prod.md** | Wdrożenie dev→prod: search-replace, SSL, cache, hardening, checklist | Ad-hoc |
| **docs/recipes/login-page/** | Branded login URL + KV + security hardening (już wpięte — patrz niżej) | Ad-hoc |

**Auto-load przy starcie:** ten CLAUDE.md + `project.md`.
**Ad-hoc:** czytaj `docs/<temat>.md` dopiero gdy pracujesz nad tym tematem (token efficiency — nie ładuj wszystkiego naraz).

### Decision-guide: zadanie → który plik
| Robię… | Czytam |
|--------|--------|
| Nowy blok od zera | docs/block-template.md + docs/figma-to-block.md |
| Navbar / nawigacja / menu | docs/patterns/navbar-menu.md |
| Formularz kontaktowy | docs/patterns/forms.md |
| Tło sekcji (hero, overlay) | docs/patterns/backgrounds.md |
| Zdjęcia, galeria, ikony, logo | docs/patterns/media-images.md |
| Button / CTA / link | docs/patterns/buttons-links.md |
| Grid postów / CPT / archiwum | docs/patterns/dynamic-blocks.md |
| Slider / karuzela | docs/patterns/slider.md |
| Animacje scroll/reveal | docs/patterns/animations.md |
| Coś nie działa w edytorze | docs/patterns/editor-gotchas.md |
| Bloki jako plugin (istniejący site) | docs/plugin-mode.md |
| Performance / Core Web Vitals / a11y | docs/optymalizacja.md |
| Wdrożenie na produkcję | docs/migracja-prod.md |
| Strona logowania | docs/recipes/login-page/ |

---

## Start nowego projektu z tego blueprintu

Blueprint daje gotowy, buildowalny scaffold (zero bloków). Na starcie:
- **Jest `project.md` wypełniony?** → masz kontekst (tokeny, bloki, decyzje). NIE pytaj o to, co tam jest — działaj. Po każdej istotnej zmianie aktualizuj `project.md`.
- **Nowy projekt?** → zadaj pytania kickoff (WSZYSTKIE naraz), zaktualizuj `project.md` (szablon: `docs/project-template.md`), wgraj tokeny po analizie Figmy.

### Pytania kickoff (nowy projekt — w jednej wiadomości)
1. **Nazwa + namespace + prefix PHP** (z brandu/Figmy; namespace = folder theme'u).
2. **Design** — link / file key Figma.
3. **Zakres** — single landing / multi-page? ile stron i szablonów?
4. **CPT / taksonomie?** (bloki dynamiczne → docs/patterns/dynamic-blocks.md)
5. **Custom funkcje** — formularz (CF7?), slider, grid dynamiczny, inne integracje?
6. **Środowisko** — dev (LocalWP?) + domena prod + hosting/cache (LiteSpeed?).
7. **Login** — slug logowania + brand login KV (patrz „Co już jest wpięte").

Plugin do istniejącego site'u zamiast standalone theme? → przeczytaj `docs/plugin-mode.md` (inny bootstrap, emisja tokenów, scope).

---

## Co już jest wpięte (blueprint)

- **Build:** `webpack.config.js` auto-discovery `blocks/*/index.js|view.js` + CopyWebpackPlugin (`block.json` + `*.php` → `build/`). Fallback entry `assets/src/index.js` → build przechodzi przy zero bloków.
- **Rejestracja bloków:** glob `build/blocks/*/block.json` w `functions.php`.
- **SVG upload:** mimes + real-MIME override + minimal sanitizer + preview w media library (cap `edit_posts`).
- **Menu:** `add_theme_support('menus')` + `register_nav_menus` (primary, footer) → Wygląd → Menu.
- **Content-length helper:** limit długości RichText (mnożniki `ADWISE_CL_*` w functions.php).
- **Anchor injection:** id z atrybutu `anchor` wstrzykiwany do bloków SSR.
- **Smooth scroll + scroll-margin** (po dodaniu navbara ustaw `--nav-h`).
- **Login KV:** `inc/adwise-login.php` (split-screen branded, parallax) — require'owany w functions.php. Brand = adwise. Podmiana pod inny brand → `docs/recipes/login-page/brand-swap.md`.
- **Hardening:** XML-RPC off, REST users hidden, author-enum block, hide WP version, X-Pingback off (marker `ADWISE_SECURITY_HARDENING`). **Slug logowania (WPS Hide Login) + Limit Login Attempts = pluginy per-deploy, NIE w repo** → `docs/recipes/login-page/workflow.md`.

```bash
npm install        # raz
npm run start      # dev + watch
npm run build      # produkcja
npm run lint:js    # ESLint (blocks/)
npm run lint:css   # Stylelint (blocks/**/*.scss)
```

---

## Architecture

```
blocks/[block-name]/     # Source bloków (per projekt — blueprint startuje pusty)
  block.json             # Metadata, attributes, supports
  index.js               # registerBlockType
  edit.js                # React component (edytor)
  save.js                # return null (server-rendered)
  render.php             # SSR — HTML output
  style.scss             # Style frontend
  editor.scss            # Style tylko edytor
  view.js                # Interaktywność frontu (opcjonalny)

build/                   # Output webpacka (NIE edytować, NIE commitować)
assets/src/index.js      # Fallback entry (pusty)
assets/css/editor.css    # Style edytora (add_editor_style)
assets/js/               # Plain JS enqueue'owany wprost (content-length-limit.js)
assets/fonts/            # Lokalne woff2 (per projekt)
assets/icons/            # Hardcoded inline SVG per block (NIE user-upload)
templates/               # FSE page templates
parts/                   # FSE template parts (header, footer)
inc/                     # PHP modules (adwise-login.php)
docs/                    # Workflow (patterns, recipes, guides)
```

---

## Block Conventions

### Supports (obowiązkowe w KAŻDYM bloku)
```json
"supports": {
  "html": false,
  "anchor": true,
  "customClassName": true,
  "align": ["wide", "full"],
  "color": false,
  "spacing": false
}
```
`anchor` i `customClassName` — ZAWSZE `true`.

**KRYTYCZNE:** bloki SSR (`save` → `null`) NIE serializują `anchor`/`className` bez jawnej deklaracji w `attributes` — wartości giną po save:
```json
"attributes": {
  "anchor":    { "type": "string" },
  "className": { "type": "string" }
}
```

### Namespace i prefiksy
- **Namespace bloków** = nazwa folderu theme'u (`adwise`).
- **Textdomain** = namespace (`adwise`).
- **Prefix PHP** = `adwise` / stałe `ADWISE_` (z `functions.php`).
- **Prefix CSS** = unikalny 2–5 literowy skrót per blok (z istniejących `.scss`; nie wymyślaj nowego dla istniejącego bloku).

### Inline vs Sidebar — kiedy co
| Typ pola | Gdzie | Komponent |
|----------|-------|-----------|
| Tekst widoczny (tytuł, cena, opis) | **Inline** | `RichText` |
| Obraz widoczny | **Inline** | `MediaUpload` |
| URL buttona | **Inline** | `Popover` + `LinkControl` |
| Toggle/boolean (pokaż/ukryj, wariant) | **Sidebar** | `ToggleControl` |
| Wybór z listy (CPT, menu, wariant) | **Sidebar** | `SelectControl` |
| Liczba (kolumny, ilość, delay) | **Sidebar** | `RangeControl` |

**Zasada:** widoczne na stronie → **inline**. Niewidoczna konfiguracja → **sidebar**. Pełne uzasadnienie → `docs/patterns/editor-gotchas.md`.

### Block Type Decision Guide
| Pytanie | Statyczny | Dynamiczny |
|---------|-----------|------------|
| Skąd dane? | Atrybuty block.json | WP_Query / REST |
| Podgląd? | RichText, MediaUpload | `ServerSideRender` lub `useSelect` |
| render.php? | Wyświetla `$attributes` | Wykonuje `WP_Query` |
| view.js? | Tylko animacje/slider | Load more / AJAX |

**Statyczny** — treść wpisuje redaktor (hero, about, pricing). **Dynamiczny** — treść z bazy (grid postów, CPT, archiwa). Szczegóły → `docs/patterns/dynamic-blocks.md`.

### Warianty zamiast duplikacji
Nowy blok różni się od istniejącego TYLKO kolorystyką/tłem → NIE twórz nowego bloku. Dodaj atrybut `variant` (SelectControl w sidebar) + klasę modifier (`prefix--dark`). Ustaw `color` jawnie na KAŻDYM elemencie tekstowym wariantu — patrz `docs/css-conventions.md`.

---

## Workflow: design → plan → akceptacja → kod

1. `get_screenshot` → analiza wizualna (szczegóły → `docs/figma-to-block.md`)
2. `get_design_context` na 1 elemencie → wartości CSS (mobile context bywa odziedziczony — weryfikuj ze screenshotem)
3. **Pytania do usera — WSZYSTKIE naraz** (lista niżej)
4. **Plan** — tabela clamp (desktop→mobile), zmiany layout @1024px, atrybuty, struktura. NIE implementuj bez planu.
5. **Czekaj na akceptację.**
6. Po akceptacji → checklist z `docs/block-template.md` → implementacja
7. Build → test edytor (desktop + wąski panel) → test frontend (desktop + mobile)

### Nowy blok — pytania (WSZYSTKIE w jednej wiadomości)
- Typ danych? (statyczne / dynamiczne WP_Query / external API)
- Jeśli dynamiczne: skąd? (CPT, taxonomy, endpoint)
- Interaktywność? (brak / load more / slider / accordion / tabs)
- Responsywność: kolumny desktop → tablet → mobile?
- Pola na karcie/elemencie? (image, title, excerpt, link, custom fields)
- Klikalność? Co jest linkiem?
- Content full-width (edge-to-edge) czy opakowany (max-width)?

---

## Zasady BEZWZGLĘDNE (nie trzeba przypominać)
- **Plan przed kodem** — ZAWSZE (plan → akceptacja → kod)
- `editor.scss` ≡ `style.scss` pod względem clamp/breakpointów/box-sizing — ZAWSZE synchronizuj
- `box-sizing: border-box` na elementach z padding + width — ZAWSZE
- `max-width: 100%` na elementach ze stałą width — ZAWSZE
- `anchor` + `className` jawnie w `attributes` — ZAWSZE (bloki SSR)
- Każdy `MediaUpload` ma przycisk "✕" usuwania (docs/patterns/media-images.md)
- Sekcje z overlay → `background-image`, nie `<img>` (docs/patterns/backgrounds.md)
- Clamp na wartościach liczbowych, breakpoint TYLKO na layout
- Buttony/klikalne → jawny `color` + `-webkit-tap-highlight-color: transparent`
- Hover na froncie → `@media (hover: hover)`, nie gołe `:hover`
- Tel/email → `<a href="tel:/mailto:">` z jawnym kolorem
- Kontenery → bez sztywnego `height`
- Walidacja formularzy → `position: absolute`, bez layout shift
- Navbar → top bar static + main `position: sticky` (NIE pure `fixed`, NIE body padding-top)
- Inline SVG z Figmy → `fill="var(--fill-0,...)"` → `fill="currentColor"`
- Obrazy user-upload → `object {id,url,alt}` + responsive srcset (NIE do `assets/`)
- Template parts (`parts/*.html`) → edytuj w pliku, NIE w Site Editor

---

## Token-Optimized Workflow
- **NIE eksploruj projektu agentem** — struktura bloków identyczna (patrz Architecture).
- **NIE czytaj ponownie** plików, które edytowałeś w tej sesji.
- **NIE czytaj ad-hoc `docs/`** dopóki nie pracujesz nad danym tematem.
- Zadawaj wszystkie pytania naraz, nie jedno po drugim.
