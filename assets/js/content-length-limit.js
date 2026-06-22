/**
 * Content Length Limit — globalny helper dla block theme.
 *
 * Używany w edit.js bloków do ograniczania długości tekstu w RichText.
 *
 * Mnożniki globalne (ustawiane w functions.php przez wp_add_inline_script):
 *   window.BLOKEE.contentLength = {
 *     heading: N,  // BLOKEE_CL_HEADING_MULTIPLIER (domyślnie 2)
 *     text:    M,  // BLOKEE_CL_TEXT_MULTIPLIER (domyślnie 3)
 *   };
 *
 * Użycie w edit.js:
 *   // Nagłówek (h1/h2/h3) — ścisły limit:
 *   const h = window.BLOKEE.useLengthLimit({
 *     get: () => heading,
 *     set: ( v ) => setAttributes( { heading: v } ),
 *     ref: 'Nagłówek sekcji',
 *     noticeId: 'hero-heading',
 *     type: 'heading',             // wybiera multiplier = window.BLOKEE.contentLength.heading
 *   });
 *
 *   // Paragraf/opis — luźniejszy limit:
 *   const p = window.BLOKEE.useLengthLimit({
 *     get: () => description,
 *     set: ( v ) => setAttributes( { description: v } ),
 *     ref: 'Lorem ipsum dolor...',
 *     noticeId: 'hero-desc',
 *     type: 'text',                // (lub pomiń — text to default)
 *   });
 *
 *   <RichText value={heading} {...h} placeholder="..." allowedFormats={[]} />
 *
 * Override globalnego mnożnika per pole:
 *   useLengthLimit({ ..., multiplier: 5 })  // własna wartość
 */
( function () {
	if ( ! window.wp?.data || ! window.wp?.notices ) {
		console.warn( 'BLOKEE content-length-limit: wp.data or wp.notices not available' );
		return;
	}

	const { useDispatch } = window.wp.data;
	const { store: noticesStore } = window.wp.notices;

	window.BLOKEE = window.BLOKEE || {};

	/**
	 * Wybiera mnożnik wg typu lub explicit override.
	 */
	const resolveMultiplier = ( type, explicit ) => {
		if ( typeof explicit === 'number' && explicit > 0 ) return explicit;
		const cfg = window.BLOKEE?.contentLength || {};
		if ( type === 'heading' ) return Number( cfg.heading ) || 2;
		return Number( cfg.text ) || 3;  // 'text' lub domyślny
	};

	/**
	 * React hook — zwraca { onChange, onKeyDown, onPaste } dla RichText.
	 *
	 * @param {object}   opts
	 * @param {Function} opts.get        - () => current value
	 * @param {Function} opts.set        - ( val ) => setAttributes / updateItem
	 * @param {string}   opts.ref        - default text (długość × multiplier = max)
	 * @param {string}   opts.noticeId   - unikalny id snackbara
	 * @param {'heading'|'text'} [opts.type='text'] - typ pola (default 'text')
	 * @param {number}   [opts.multiplier] - override globalnego mnożnika
	 */
	window.BLOKEE.useLengthLimit = function ( opts ) {
		const { get, set, ref, noticeId, type = 'text' } = opts;
		const multiplier = resolveMultiplier( type, opts.multiplier );
		const max = ( ref?.length || 0 ) * multiplier;

		const { createWarningNotice } = useDispatch( noticesStore );

		const warn = () => {
			createWarningNotice(
				`Tekst za długi — maks. ${ max } znaków`,
				{ type: 'snackbar', id: noticeId }
			);
		};

		const stripHtml = ( v ) => String( v || '' ).replace( /<[^>]*>/g, '' );

		return {
			onChange: ( val ) => {
				const text = stripHtml( val );
				if ( text.length <= max ) {
					set( val );
				} else {
					set( text.slice( 0, max ) );
					warn();
				}
			},

			onKeyDown: ( e ) => {
				if ( e.ctrlKey || e.metaKey || e.altKey ) return;
				if ( e.key.length !== 1 ) return;
				const sel = window.getSelection();
				if ( sel && ! sel.isCollapsed ) return;
				const currentLen = stripHtml( get() ).length;
				if ( currentLen >= max ) {
					e.preventDefault();
					warn();
				}
			},

			onPaste: ( e ) => {
				const currentLen = stripHtml( get() ).length;
				const pasted = ( e.clipboardData || window.clipboardData ).getData( 'text/plain' );
				const available = max - currentLen;
				if ( available <= 0 ) {
					e.preventDefault();
					warn();
					return;
				}
				if ( pasted.length > available ) {
					e.preventDefault();
					document.execCommand( 'insertText', false, pasted.slice( 0, available ) );
					warn();
				}
			},
		};
	};
} )();
