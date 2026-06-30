# WP MCP — akcje runtime (oficjalny MCP Adapter)

Domyślny mechanizm, żeby Claude **działał na żywym WP** (stawiał strony z bloków, seedował CPT, tworzył formularz CF7, ustawiał front page, czyścił cache) — przez **oficjalny WordPress MCP Adapter** oparty o **Abilities API** (WP 6.9+). Novamira (proprietary `execute-php`) **porzucona**. Tryb **ad-hoc**.

> Budowanie (pliki bloków, `register_post_type`, theme.json, `npm run build`) NIE wymaga MCP — to filesystem + build. WP MCP jest do **akcji runtime** (działania na bazie/WP za usera).

---

## Co jest czym
- **Abilities API** (WP 6.9+, w core): rejestrujesz „ability" = nazwa + schema in/out + `permission_callback` + `execute_callback`.
- **MCP Adapter** (plugin `WordPress/mcp-adapter`): wystawia abilities jako **toole MCP**. Serwer domyślny: `mcp-adapter-default-server`.
- Claude łączy się jako **zalogowany user** (Application Password) i wywołuje abilities. Bezpieczeństwo per ability przez `permission_callback`.

---

## Instalacja
- **Plugin:** pobierz z `github.com/WordPress/mcp-adapter/releases` → aktywuj. (lub `composer require wordpress/mcp-adapter` we własnym pluginie + autoload).
- **Wymagania:** WordPress **6.9+** (Abilities API w core), PHP 7.4+.
- App Password: WP Admin → Users → Application Passwords.

---

## Połączenie (Claude Code — `.mcp.json` projektu lub `~/.claude.json`)

**Lokalnie (LocalWP) — STDIO via WP-CLI** (zalecane na dev):
```json
{ "mcpServers": { "wordpress": {
  "command": "wp",
  "args": ["--path=/ścieżka/do/wp","mcp-adapter","serve","--server=mcp-adapter-default-server","--user=admin"]
} } }
```

**Remote / prod — HTTP proxy + App Password:**
```json
{ "mcpServers": { "wordpress": {
  "command": "npx",
  "args": ["-y","@automattic/mcp-wordpress-remote@latest"],
  "env": {
    "WP_API_URL": "https://site/wp-json/mcp/mcp-adapter-default-server",
    "WP_API_USERNAME": "admin",
    "WP_API_PASSWORD": "application-password"
  }
} } }
```

---

## Out-of-box = read-only
Domyślne toole: `core/get-site-info`, `core/get-user-info`, `core/get-environment-info` + discovery: `mcp-adapter-discover-abilities`, `mcp-adapter-get-ability-info`, `mcp-adapter-execute-ability`.
**Brak tworzenia stron/CPT/bloków bez własnych abilities.**

---

## Akcje budowania = własne abilities (zamiast execute-php)

Żeby Claude stawiał strony / seedował / tworzył CF7 → zarejestruj **typed abilities** w `inc/abilities.php` (require w functions.php), oznacz `meta.mcp.public => true`, wystaw na serwerze MCP. Przykład „postaw stronę z listy bloków":

```php
add_action( 'abilities_api_init', function () {
	wp_register_ability( '{ns}/create-page', [
		'label'        => 'Utwórz stronę z bloków',
		'input_schema' => [ 'type' => 'object', 'properties' => [
			'title'  => [ 'type' => 'string' ],
			'slug'   => [ 'type' => 'string' ],
			'blocks' => [ 'type' => 'string' ], // markup: <!-- wp:{ns}/hero /--> ...
		], 'required' => [ 'title', 'slug', 'blocks' ] ],
		'output_schema'       => [ 'type' => 'object' ],
		'permission_callback' => fn() => current_user_can( 'publish_pages' ), // realna capability!
		'execute_callback'    => function ( $input ) {
			$existing = get_page_by_path( $input['slug'], OBJECT, 'page' );
			if ( $existing ) {
				return [ 'status' => 'exists', 'id' => $existing->ID, 'url' => get_permalink( $existing->ID ) ];
			}
			$id = wp_insert_post( [
				'post_type'   => 'page', 'post_status' => 'publish',
				'post_title'  => sanitize_text_field( $input['title'] ),
				'post_name'   => sanitize_title( $input['slug'] ),
				'post_content'=> $input['blocks'], // bloki self-closing → defaulty z block.json
			] );
			return [ 'status' => 'created', 'id' => $id, 'url' => get_permalink( $id ) ];
		},
		'meta' => [ 'mcp' => [ 'public' => true ] ],
	] );
} );
```

Analogicznie: `{ns}/seed-cpt`, `{ns}/create-cf7-form`, `{ns}/set-front-page`, `{ns}/flush-rewrites`. **Typed + permission-gated = bezpieczniej niż arbitralny execute-php.**

> **API młode** — dokładną sygnaturę `wp_register_ability` / hook (`abilities_api_init`) + sposób wpięcia abilities na serwer (custom `create_server` vs default-server `meta.mcp.public`) **zweryfikuj w** `developer.wordpress.org/apis/abilities/` i README `WordPress/mcp-adapter` przy wdrożeniu.

---

## Capability matrix
| Akcja | Jak |
|-------|-----|
| Strona z bloków | ability `create-page` (`wp_insert_post`, content = markup), duplicate-check `get_page_by_path` |
| Wpisy CPT (seed) | ability `seed-cpt` (CPT z `show_in_rest:true`; **meta przez REST → CPT MUSI mieć `'custom-fields'` w supports**, inaczej meta cicho ignorowana — patterns/dynamic-blocks.md) |
| **Rejestracja CPT** | KOD `functions.php` (`register_post_type`) — NIE MCP |
| Media | ability upload / lub WP-CLI `wp media import` |
| Rekord formularza CF7 | ability własna (API CF7) **lub** ręcznie w Kontakt → Formularze |
| Front page / options | ability `set-front-page` (`update_option show_on_front`/`page_on_front`) |
| Flush rewrites | ability `flush-rewrites` (`flush_rewrite_rules`) |
| search-replace (migracja) | **WP-CLI**, nie MCP (migracja-prod.md) |

---

## Fallback (gdy MCP Adapter niedostępny)
1. **WP-CLI:** `wp post create`, `wp eval-file insert.php`, `wp media import`.
2. **Ręcznie:** Claude daje snippet PHP → user wkleja (`wp shell` / panel). Zawsze duplicate-check przed `wp_insert_post`.

---

## Bezpieczeństwo
- `permission_callback` z realną capability — NIGDY `__return_true` dla zapisu/usuwania.
- Dedykowany user o minimalnych prawach dla MCP.
- Publiczny endpoint HTTP → preferuj abilities read-only; akcje zapisu lokalnie (STDIO) lub za App Password.
- Loguj użycie (custom error/observability handler adaptera).

---

## Linki
- MCP Adapter: `github.com/WordPress/mcp-adapter`
- Abilities API: `developer.wordpress.org/apis/abilities/`
- HTTP proxy: `@automattic/mcp-wordpress-remote`
- Alt społecznościowy (REST, prostszy, bez pisania abilities): `kungtekno/wp-mcp` (App Passwords, `wp_create_post`/media/plugins) — gdy nie chcesz rejestrować abilities, ale mniej kontroli (brakuje CF7/flush/arbitrary).
