# project.md — Adwise (blueprint baseline)

Stan projektu (auto-load) — single source of truth dla rzeczy NIE odczytywalnych z kodu: tokeny, status bloków, decyzje, log. Trzymaj chudo. Po sklonowaniu pod nowy projekt — nadpisz wartości.

---

## 1. Overview
- **Nazwa:** Adwise (blueprint)
- **Cel:** Czysty startowy block theme pod workflow Figma → Gutenberg.
- **Typ:** theme
- **Namespace bloków:** adwise
- **Prefix PHP:** adwise (stałe `ADWISE_`)
- **Język:** pl_PL

## 2. Środowisko
- **Dev:** LocalWP — `/Users/.../Local_sites/blueprint/app/public/wp-content/themes/adwise`
- **Prod:** —
- **Hosting / cache:** —
- **PHP:** 7.4+

## 3. Design (Figma)
- **Link / file key:** — (wgraj per projekt)
- **Widoki:** desktop 1440 / mobile

## 4. Tokeny (theme.json)
Neutralny skeleton — realne wartości wgraj po analizie Figmy (reguły → docs/figma-to-block.md).

| slug | wartość | rola |
|------|---------|------|
| primary | #1a1a1a | accent / nagłówki (placeholder) |
| base | #ffffff | tło |
| text | #1a1a1a | tekst |
| surface | #f7f7f7 | sekcje alt |

Fonty: system-ui stack (woff2 per projekt → assets/fonts/). Spacing/font-sizes: skeleton xx-small…xx-large.

## 5. Strony / szablony
- `templates/index.html` — header → main → footer (placeholder).

## 6. CPT / taksonomie
brak (dodaj per projekt → docs/patterns/dynamic-blocks.md)

## 7. Bloki (status)
| Blok | Prefix CSS | Typ | Status |
|------|------------|-----|--------|
| — | — | — | brak (blueprint startuje pusty) |

## 8. Custom funkcje / integracje
- Login KV: `inc/adwise-login.php` (wpięty, brand adwise).
- Hardening: aktywny (marker `ADWISE_SECURITY_HARDENING`).
- SVG upload: aktywny. Menu: primary + footer.
- Formularz / slider / dynamic grid: brak (per projekt).

## 9. Decyzje per-projekt
- theme.json neutralny (tokeny po Figmie). Fonty niebundlowane. gsap/swiper poza deps (dodaj gdy blok użyje).
- Slug logowania + pluginy (WPS Hide Login, Limit Login Attempts) = per-deploy, poza repo.

## 10. Rolling log
- 2026-06-22 — utworzono blueprint adwise v1.0: scaffold + docs self-contained + login KV + hardening.
