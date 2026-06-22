<?php
/**
 * WP MCP runtime abilities (Abilities API → MCP Adapter).
 *
 * Wystawia akcje runtime jako typed, permission-gated abilities, które
 * oficjalny plugin `mcp-adapter` udostępnia jako toole MCP. Zamiast arbitralnego
 * execute-php. Patrz: docs/wp-mcp.md.
 *
 * Wymaga: WordPress 6.9+ (Abilities API w core) + plugin WordPress/mcp-adapter.
 * UWAGA: Abilities API jest młode — jeśli sygnatura `wp_register_ability` / hook
 * różni się w Twojej wersji WP, zweryfikuj: developer.wordpress.org/apis/abilities/.
 *
 * Namespace ability = 'adwise'. Jeśli zmieniasz nazwę theme — zmień też tu.
 *
 * @package adwise
 */

defined( 'ABSPATH' ) || exit;

add_action( 'abilities_api_init', function () {

	// Brak Abilities API (WP < 6.9 lub plugin nieaktywny) → nie rejestruj (zero fatala).
	if ( ! function_exists( 'wp_register_ability' ) ) {
		return;
	}

	$ns = 'adwise';

	/* --- Postaw stronę z listy bloków --- */
	wp_register_ability( "{$ns}/create-page", [
		'label'        => 'Utwórz stronę z bloków',
		'description'  => 'Tworzy stronę z podanego markupu bloków (post_content). Idempotentne po slug.',
		'input_schema' => [
			'type'       => 'object',
			'properties' => [
				'title'  => [ 'type' => 'string' ],
				'slug'   => [ 'type' => 'string' ],
				'blocks' => [ 'type' => 'string', 'description' => 'Markup bloków, np. <!-- wp:adwise/hero /-->' ],
			],
			'required'   => [ 'title', 'slug', 'blocks' ],
		],
		'output_schema'       => [ 'type' => 'object' ],
		'permission_callback' => function () { return current_user_can( 'publish_pages' ); },
		'execute_callback'    => function ( $input ) {
			$slug = sanitize_title( $input['slug'] );
			$existing = get_page_by_path( $slug, OBJECT, 'page' );
			if ( $existing ) {
				return [ 'status' => 'exists', 'id' => $existing->ID, 'url' => get_permalink( $existing->ID ) ];
			}
			$id = wp_insert_post( [
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'post_title'   => sanitize_text_field( $input['title'] ),
				'post_name'    => $slug,
				'post_content' => $input['blocks'], // markup zaufany (od agenta); bloki same escapują render
			], true );
			if ( is_wp_error( $id ) ) {
				return [ 'status' => 'error', 'message' => $id->get_error_message() ];
			}
			return [ 'status' => 'created', 'id' => $id, 'url' => get_permalink( $id ) ];
		},
		'meta' => [ 'mcp' => [ 'public' => true ] ],
	] );

	/* --- Seed wpisu CPT (lub post/page) --- */
	wp_register_ability( "{$ns}/seed-cpt", [
		'label'        => 'Utwórz wpis CPT',
		'description'  => 'Tworzy wpis danego typu (CPT musi istnieć i mieć show_in_rest dla edycji w edytorze).',
		'input_schema' => [
			'type'       => 'object',
			'properties' => [
				'post_type' => [ 'type' => 'string' ],
				'title'     => [ 'type' => 'string' ],
				'slug'      => [ 'type' => 'string' ],
				'content'   => [ 'type' => 'string' ],
				'status'    => [ 'type' => 'string', 'enum' => [ 'publish', 'draft' ] ],
			],
			'required'   => [ 'post_type', 'title' ],
		],
		'output_schema'       => [ 'type' => 'object' ],
		'permission_callback' => function () { return current_user_can( 'publish_posts' ); },
		'execute_callback'    => function ( $input ) {
			$pt = sanitize_key( $input['post_type'] );
			if ( ! post_type_exists( $pt ) ) {
				return [ 'status' => 'error', 'message' => "Typ '$pt' nie istnieje (zarejestruj w functions.php)." ];
			}
			$id = wp_insert_post( [
				'post_type'    => $pt,
				'post_status'  => sanitize_key( $input['status'] ?? 'publish' ),
				'post_title'   => sanitize_text_field( $input['title'] ),
				'post_name'    => isset( $input['slug'] ) ? sanitize_title( $input['slug'] ) : '',
				'post_content' => $input['content'] ?? '',
			], true );
			if ( is_wp_error( $id ) ) {
				return [ 'status' => 'error', 'message' => $id->get_error_message() ];
			}
			return [ 'status' => 'created', 'id' => $id, 'url' => get_permalink( $id ) ];
		},
		'meta' => [ 'mcp' => [ 'public' => true ] ],
	] );

	/* --- Ustaw stronę startową (front page) --- */
	wp_register_ability( "{$ns}/set-front-page", [
		'label'        => 'Ustaw stronę startową',
		'description'  => 'Ustawia statyczną stronę główną (show_on_front=page, page_on_front=ID).',
		'input_schema' => [
			'type'       => 'object',
			'properties' => [ 'page_id' => [ 'type' => 'integer' ] ],
			'required'   => [ 'page_id' ],
		],
		'output_schema'       => [ 'type' => 'object' ],
		'permission_callback' => function () { return current_user_can( 'manage_options' ); },
		'execute_callback'    => function ( $input ) {
			$pid = absint( $input['page_id'] );
			if ( ! get_post( $pid ) ) {
				return [ 'status' => 'error', 'message' => "Strona $pid nie istnieje." ];
			}
			update_option( 'show_on_front', 'page' );
			update_option( 'page_on_front', $pid );
			return [ 'status' => 'ok', 'page_on_front' => $pid ];
		},
		'meta' => [ 'mcp' => [ 'public' => true ] ],
	] );

	/* --- Flush rewrite rules (po nowych CPT / permalinkach) --- */
	wp_register_ability( "{$ns}/flush-rewrites", [
		'label'               => 'Flush rewrite rules',
		'description'         => 'Przebudowuje reguły permalinków (np. po rejestracji CPT).',
		'input_schema'        => [ 'type' => 'object', 'properties' => [] ],
		'output_schema'       => [ 'type' => 'object' ],
		'permission_callback' => function () { return current_user_can( 'manage_options' ); },
		'execute_callback'    => function () {
			flush_rewrite_rules( false );
			return [ 'status' => 'ok' ];
		},
		'meta' => [ 'mcp' => [ 'public' => true ] ],
	] );

	/* --- Utwórz formularz CF7 --- */
	wp_register_ability( "{$ns}/create-cf7-form", [
		'label'        => 'Utwórz formularz Contact Form 7',
		'description'  => 'Tworzy formularz CF7 z podanego markupu (CF7 musi być aktywny).',
		'input_schema' => [
			'type'       => 'object',
			'properties' => [
				'title' => [ 'type' => 'string' ],
				'form'  => [ 'type' => 'string', 'description' => 'Markup formularza CF7 (pola [text*...] itd.)' ],
			],
			'required'   => [ 'title', 'form' ],
		],
		'output_schema'       => [ 'type' => 'object' ],
		'permission_callback' => function () { return current_user_can( 'manage_options' ); },
		'execute_callback'    => function ( $input ) {
			if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
				return [ 'status' => 'error', 'message' => 'Contact Form 7 nieaktywny.' ];
			}
			$cf7   = WPCF7_ContactForm::get_template();
			$cf7->set_title( sanitize_text_field( $input['title'] ) );
			$props = $cf7->get_properties();
			$props['form'] = $input['form'];
			$cf7->set_properties( $props );
			$id = $cf7->save();
			return [ 'status' => 'created', 'id' => $id, 'shortcode' => '[contact-form-7 id="' . $id . '"]' ];
		},
		'meta' => [ 'mcp' => [ 'public' => true ] ],
	] );

} );
