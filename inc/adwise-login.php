<?php
/**
 * Plugin Name: AdWise — Custom Login KV
 * Description: Split-screen branded login (purple panel + floating squares parallax + restyled WP form).
 * Version: 1.0.0
 * Author: AdWise
 */

defined( 'ABSPATH' ) || exit;

/* === Brand assets === */

function adw_login_logo_svg() {
	return '<svg xmlns="http://www.w3.org/2000/svg" width="196" height="26" viewBox="0 0 196 26" fill="none" aria-label="AdWise"><g clip-path="url(#adw-l)"><path d="M68.025 3.276H65.078L55.066 22.781H57.08L59.55 17.763H73.55L76.022 22.781H78.036L68.025 3.276zM67.21 4.861 72.77 16.16H60.328L65.897 4.861H67.208z" fill="#fff"/><path d="M95.828 3.247V12.245C94.315 9.799 91.507 8.457 87.867 8.457C82.339 8.457 78.766 11.369 78.766 15.878C78.766 20.387 82.401 23.298 87.812 23.298C91.497 23.298 94.323 21.985 95.828 19.585V22.781H97.446V3.247H95.828zM95.856 15.741V16.121C95.856 19.397 92.611 21.775 88.138 21.775C83.665 21.775 80.557 19.516 80.557 15.875C80.557 12.235 83.332 9.975 87.975 9.975C92.619 9.975 95.856 12.4 95.856 15.738V15.741z" fill="#fff"/><path d="M48.62 1.629H29.164V17.884H48.62V1.629z" fill="#7855DA"/><path d="M182.804 15.459C182.804 11.999 180.156 9.254 176.058 8.462C174.173 8.098 171.921 8.098 170.037 8.462C165.939 9.254 163.291 11.999 163.291 15.459C163.291 18.921 165.939 21.667 170.037 22.455C170.98 22.636 172.012 22.727 173.046 22.727C174.08 22.727 175.115 22.636 176.056 22.455C179.127 21.863 181.382 20.172 182.32 17.905H178.207C177.214 18.745 176.025 19.086 175.448 19.198C173.964 19.485 172.123 19.485 170.639 19.198C169.07 18.895 167.542 18.086 166.924 16.682H182.682C182.757 16.286 182.799 15.878 182.799 15.459H182.804zM167.172 13.752C168.098 12.356 169.923 11.86 170.644 11.72C172.128 11.433 173.969 11.433 175.453 11.72C176.834 11.986 178.114 12.643 178.864 13.752H167.172z" fill="#fff"/><path d="M125.055 20.679L117.849 3.317L117.813 3.235H112.846L112.81 3.317L105.604 20.679V3.235H100.668V22.747H104.743H105.604H109.782L115.328 9.383L120.876 22.747H125.055H125.872H125.913H129.99V3.235H125.055V20.679z" fill="#fff"/><path d="M157.978 14.476C156.863 14.163 155.307 14.008 152.975 13.84C152.76 13.825 146.981 13.494 146.379 13.305C145.927 13.163 145.559 12.935 145.559 12.558C145.559 12.449 145.588 12.356 145.637 12.271C145.671 12.214 145.712 12.162 145.761 12.113C146.064 11.826 146.653 11.699 147.165 11.645C147.209 11.637 147.258 11.635 147.305 11.632C147.605 11.606 152.871 11.609 153.22 11.632C153.761 11.671 154.415 11.764 154.968 12.007C155.485 12.232 156.051 12.597 156.07 13.147H160.4C160.341 9.091 155.26 8.165 151.03 8.165C146.588 8.165 141.479 8.687 141.397 12.77C141.358 14.657 142.325 15.891 144.272 16.436C145.397 16.752 146.969 16.907 149.337 17.077C149.337 17.077 155.268 17.421 155.87 17.607C156.323 17.75 156.69 17.977 156.69 18.355C156.69 18.461 156.662 18.556 156.613 18.642C156.579 18.699 156.538 18.75 156.488 18.797C156.186 19.084 155.596 19.21 155.084 19.265C155.041 19.27 154.991 19.275 154.945 19.278C154.642 19.304 149.06 19.298 148.447 19.231C147.848 19.166 147.186 19.035 146.648 18.773C146.183 18.549 145.686 18.236 145.676 17.757H141.34C141.371 19.627 142.764 22.74 151.217 22.74C155.658 22.74 160.767 22.217 160.85 18.137C160.889 16.25 159.922 15.017 157.975 14.471L157.978 14.476z" fill="#fff"/><path d="M138.135 8.925H133.199V22.747H138.135V8.925z" fill="#fff"/><path d="M138.135 3.235H133.199V6.521H138.135V3.235z" fill="#fff"/><path d="M29.163 17.884H17.813V26H29.163V17.884z" fill="#7855DA"/><path d="M17.809 11.351H8.082V17.884H17.809V11.351z" fill="#7855DA"/><path d="M8.082 17.884H0V21.137H8.082V17.884z" fill="#7855DA"/><path d="M189.312 0C185.809 0 183.58 2.689 183.58 5.779C183.58 8.868 185.809 11.573 189.312 11.573C192.816 11.573 195.029 8.884 195.029 5.779C195.029 2.673 192.785 0 189.312 0zM189.315 10.531C186.535 10.531 184.684 8.292 184.684 5.776C184.684 3.26 186.535 1.039 189.315 1.039C192.094 1.039 193.93 3.258 193.93 5.776C193.93 8.294 192.079 10.531 189.315 10.531z" fill="#fff"/><path d="M190.436 6.549C191.111 6.205 191.488 5.574 191.488 4.806C191.488 3.659 190.625 2.779 189.226 2.779H187.328V8.687H188.569V6.818H189.322L190.281 8.656H191.68L190.438 6.552zM189.303 5.574L188.567 5.59V4.02L189.303 4.036C189.805 4.036 190.167 4.333 190.167 4.806C190.167 5.28 189.805 5.577 189.303 5.577V5.574z" fill="#fff"/></g><defs><clipPath id="adw-l"><rect width="195.028" height="26" fill="#fff"/></clipPath></defs></svg>';
}

/* === Inline CSS in <head> === */

add_action( 'login_head', function() {
	?>
	<style id="adw-login-css">
		:root {
			--adw-purple: #7855DA;
			--adw-purple-hover: #6342c6;
			--adw-navy: #2B2B5C;
			--adw-navy-deep: #1E1E45;
			--adw-bg: #ffffff;
			--adw-text: #2B2B5C;
			--adw-muted: #7b7b9a;
			--adw-border: #e2e0eb;
			--adw-soft: #f7f6fb;
		}

		html { background: var(--adw-bg); }

		body.login {
			margin: 0;
			padding: 0;
			background: var(--adw-bg);
			min-height: 100vh;
			position: relative;
			color: var(--adw-text);
			font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
		}

		/* ── Brand panel (left, fixed) ─────────────────────── */
		.adw-brand {
			position: fixed;
			left: 0;
			top: 0;
			width: 50%;
			height: 100vh;
			background: linear-gradient(135deg, var(--adw-navy) 0%, var(--adw-navy-deep) 100%);
			color: #fff;
			overflow: hidden;
			padding: clamp(32px, 4vw, 64px);
			box-sizing: border-box;
			display: flex;
			flex-direction: column;
			justify-content: space-between;
			z-index: 1;
		}
		.adw-brand__squares {
			position: absolute;
			inset: 0;
			pointer-events: none;
			overflow: hidden;
			z-index: 0;
		}
		.adw-brand__sq {
			position: absolute;
			background: var(--adw-purple);
			border-radius: 2px;
			will-change: transform;
		}
		.adw-brand__logo {
			position: relative;
			z-index: 2;
		}
		.adw-brand__logo a { display: inline-block; line-height: 0; transition: opacity 0.15s; }
		.adw-brand__logo a:hover { opacity: 0.75; }
		.adw-brand__logo svg {
			display: block;
			width: clamp(140px, 14vw, 196px);
			height: auto;
		}
		.adw-brand__tagline {
			position: relative;
			z-index: 2;
			font-size: clamp(28px, 3.2vw, 44px);
			font-weight: 500;
			line-height: 1.2;
			letter-spacing: -0.02em;
			max-width: 540px;
		}
		.adw-brand__tagline strong {
			color: var(--adw-purple);
			font-weight: 600;
		}
		.adw-brand__footer {
			position: relative;
			z-index: 2;
			font-size: 13px;
			opacity: 0.55;
			letter-spacing: 0.04em;
		}
		.adw-brand__footer a {
			color: inherit;
			text-decoration: none;
			border-bottom: 1px solid rgba(255,255,255,0.3);
			transition: border-color 0.15s, color 0.15s;
		}
		.adw-brand__footer a:hover {
			color: var(--adw-purple);
			border-bottom-color: var(--adw-purple);
		}

		/* ── Right column = WP #login ─────────────────────── */
		#login {
			margin: 0 0 0 50%;
			width: 50%;
			max-width: none;
			padding: clamp(48px, 6vw, 96px) clamp(32px, 5vw, 80px);
			box-sizing: border-box;
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			min-height: 100vh;
		}
		#login > * {
			max-width: 420px;
			width: 100%;
			margin-left: auto;
			margin-right: auto;
		}

		/* hide default WP logo block (we have our own) */
		#login h1.wp-login-logo,
		#login h1 a { display: none !important; }

		/* heading injected above form */
		.adw-login-heading {
			margin: 0 0 28px;
			font-size: 32px;
			font-weight: 600;
			line-height: 1.2;
			letter-spacing: -0.02em;
			color: var(--adw-text);
		}
		.adw-login-sub {
			margin: -20px 0 32px;
			color: var(--adw-muted);
			font-size: 15px;
			line-height: 1.5;
		}

		/* Center heading + helper texts, keep field labels left-aligned */
		.adw-login-heading,
		.adw-login-sub,
		.login #nav,
		.login #backtoblog,
		.login .submit { text-align: center; }
		.login form > p > label,
		.login label[for="user_login"],
		.login label[for="user_pass"] { text-align: left; display: block; }
		/* show/hide password eye button — vertically centered inside input */
		.login .wp-pwd { position: relative; }
		.login .wp-pwd input[type="password"],
		.login .wp-pwd input[type="text"] { padding-right: 44px; }
		.login .wp-pwd .button.wp-hide-pw {
			position: absolute;
			top: 50%;
			right: 6px;
			transform: translateY(-50%);
			margin: 0;
			padding: 0;
			width: 32px;
			height: 32px;
			background: transparent;
			border: 0;
			box-shadow: none;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			color: var(--adw-muted);
		}
		.login .wp-pwd .button.wp-hide-pw:hover { color: var(--adw-purple); background: transparent; }
		.login .wp-pwd .button.wp-hide-pw:focus { outline: none; box-shadow: 0 0 0 2px rgba(120,85,218,0.25); border-radius: 6px; }
		.login .wp-pwd .button.wp-hide-pw .text { display: none; }
		.login .wp-pwd .button.wp-hide-pw .dashicons { width: 20px; height: 20px; font-size: 20px; }

		.login .forgetmenot {
			display: flex;
			align-items: center;
			gap: 8px;
			padding-bottom: 24px;
		}
		.login .forgetmenot label { display: inline-flex; align-items: center; gap: 8px; }
		.login .forgetmenot input[type="checkbox"] { margin: 0; }

		/* form */
		#loginform {
			background: transparent;
			box-shadow: none;
			border: 0;
			padding: 0;
			margin: 0;
		}
		.login form .input,
		.login input[type="text"],
		.login input[type="password"],
		.login input[type="email"] {
			background: var(--adw-soft);
			border: 1px solid var(--adw-border);
			border-radius: 10px;
			padding: 12px 14px;
			font-size: 15px;
			line-height: 1.4;
			box-shadow: none;
			margin: 2px 0 16px;
			height: auto;
			transition: border-color 0.15s, box-shadow 0.15s;
		}
		.login input:focus {
			border-color: var(--adw-purple);
			box-shadow: 0 0 0 3px rgba(120,85,218,0.18);
			outline: none;
		}
		.login label {
			color: var(--adw-text);
			font-size: 13px;
			font-weight: 500;
			letter-spacing: 0.02em;
		}
		.login .forgetmenot label {
			font-size: 13px;
			font-weight: 400;
			color: var(--adw-muted);
		}

		/* primary button */
		.wp-core-ui .button-primary,
		.login .button-primary {
			background: var(--adw-purple) !important;
			border: 0 !important;
			box-shadow: none !important;
			border-radius: 10px !important;
			padding: 12px 28px !important;
			height: auto !important;
			line-height: 1.2 !important;
			font-size: 14px !important;
			font-weight: 600 !important;
			letter-spacing: 0.06em !important;
			text-transform: uppercase !important;
			text-shadow: none !important;
			color: #fff !important;
			width: 100%;
			transition: background 0.15s;
		}
		.wp-core-ui .button-primary:hover,
		.login .button-primary:hover {
			background: var(--adw-purple-hover) !important;
		}

		/* misc text */
		.login #nav,
		.login #backtoblog {
			margin: 18px 0 0;
			text-align: left;
			padding: 0;
		}
		.login #nav a,
		.login #backtoblog a {
			color: var(--adw-purple);
			text-decoration: none;
			font-size: 13px;
		}
		.login #nav a:hover,
		.login #backtoblog a:hover {
			color: var(--adw-purple-hover);
			text-decoration: underline;
		}

		/* messages / errors */
		.login .message,
		.login .notice,
		#login_error {
			border-radius: 10px;
			border: 0;
			background: var(--adw-soft);
			border-left: 4px solid var(--adw-purple);
			padding: 12px 14px;
			box-shadow: none;
			margin: 0 0 18px;
			font-size: 14px;
		}
		#login_error {
			background: #fef0f0;
			border-left-color: #d63638;
			color: #5b1a1a;
		}

		/* hide language switcher (we want clean look) */
		.login .language-switcher { display: none; }

		/* ── Mobile ─────────────────────────────────────── */
		@media (max-width: 900px) {
			.adw-brand {
				position: relative;
				width: 100%;
				height: auto;
				min-height: 240px;
				padding: 28px;
			}
			.adw-brand__tagline {
				font-size: clamp(22px, 5vw, 30px);
			}
			#login {
				margin-left: 0;
				width: 100%;
				min-height: auto;
				padding: 40px 24px 64px;
			}
		}

		/* reduced motion */
		@media (prefers-reduced-motion: reduce) {
			.adw-brand__sq { transition: none !important; }
		}
	</style>
	<?php
} );

/* === Brand panel HTML in body === */

add_action( 'login_header', function() {
	?>
	<aside class="adw-brand" aria-hidden="true">
		<div class="adw-brand__squares" id="adw-squares"></div>
		<div class="adw-brand__logo"><a href="https://adwise.pl/" target="_blank" rel="noopener" aria-label="AdWise"><?php echo adw_login_logo_svg(); ?></a></div>
		<div class="adw-brand__tagline">
			Procesowo wdrażamy<br>
			<strong>marketing B2B</strong>
		</div>
		<div class="adw-brand__footer">© <?php echo esc_html( gmdate( 'Y' ) ); ?> <a href="https://adwise.pl/" target="_blank" rel="noopener">AdWise</a></div>
	</aside>
	<?php
} );

/* === Form headings (injected at top of form area) === */

add_action( 'login_form', function() {
	// Inserted INSIDE the form — not ideal. Use login_message instead.
}, 1 );

add_filter( 'login_message', function( $msg ) {
	$heading  = '<h1 class="adw-login-heading">Zaloguj się</h1>';
	$heading .= '<p class="adw-login-sub">Wprowadź swoje dane, aby przejść do panelu.</p>';
	return $heading . $msg;
} );

/* === Logo link/text === */

add_filter( 'login_headerurl', function() {
	return home_url( '/' );
} );
add_filter( 'login_headertext', function() {
	return 'AdWise';
} );

/* === Parallax + ambient JS === */

add_action( 'login_footer', function() {
	?>
	<script id="adw-login-js">
	(function () {
		var wrap = document.getElementById( 'adw-squares' );
		var panel = wrap && wrap.parentElement;
		if ( ! wrap || ! panel ) return;

		var N = 14;
		var squares = [];
		for ( var i = 0; i < N; i++ ) {
			var s = document.createElement( 'div' );
			s.className = 'adw-brand__sq';
			var size = 60 + Math.random() * 220;
			var depth = 8 + Math.random() * 38;
			var rot = ( Math.random() * 24 - 12 );
			s.style.width = s.style.height = size + 'px';
			s.style.left = ( Math.random() * 110 - 5 ) + '%';
			s.style.top = ( Math.random() * 110 - 5 ) + '%';
			s.style.opacity = ( 0.05 + Math.random() * 0.18 ).toFixed( 3 );
			s._depth = depth;
			s._rot = rot;
			s._phase = Math.random() * Math.PI * 2;
			wrap.appendChild( s );
			squares.push( s );
		}

		var tx = 0, ty = 0, cx = 0, cy = 0;
		var reduced = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

		panel.addEventListener( 'mousemove', function ( e ) {
			var r = panel.getBoundingClientRect();
			cx = ( e.clientX - r.left ) / r.width - 0.5;
			cy = ( e.clientY - r.top ) / r.height - 0.5;
		} );
		panel.addEventListener( 'mouseleave', function () { cx = 0; cy = 0; } );

		function tick( now ) {
			tx += ( cx - tx ) * 0.07;
			ty += ( cy - ty ) * 0.07;
			for ( var i = 0; i < squares.length; i++ ) {
				var s = squares[ i ];
				var amb = reduced ? 0 : Math.sin( now / 2400 + s._phase ) * 14;
				var amx = reduced ? 0 : Math.cos( now / 2800 + s._phase ) * 9;
				s.style.transform = 'translate(' + ( tx * s._depth + amx ).toFixed( 2 ) + 'px,' + ( ty * s._depth + amb ).toFixed( 2 ) + 'px) rotate(' + s._rot + 'deg)';
			}
			requestAnimationFrame( tick );
		}
		requestAnimationFrame( tick );
	}());
	</script>
	<?php
} );
