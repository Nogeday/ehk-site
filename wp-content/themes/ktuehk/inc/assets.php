<?php
/**
 * Styles, scripts, font preloading and conditional KaTeX loading.
 *
 * One stylesheet and one small deferred script per page. Fonts are
 * self-hosted (no third-party requests). KaTeX loads only on articles that
 * actually contain formulas.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

/**
 * Version string for an asset (file modification time, so caches refresh
 * after every deploy).
 *
 * @param string $relative Path relative to the theme root.
 * @return string
 */
function ktuehk_asset_version( $relative ) {
	$path = KTUEHK_DIR . '/' . $relative;
	return file_exists( $path ) ? KTUEHK_VERSION . '.' . filemtime( $path ) : KTUEHK_VERSION;
}

/**
 * Enqueue front-end assets.
 */
function ktuehk_enqueue_assets() {
	wp_enqueue_style( 'ktuehk', KTUEHK_URI . '/assets/css/main.css', array(), ktuehk_asset_version( 'assets/css/main.css' ) );

	wp_enqueue_script(
		'ktuehk',
		KTUEHK_URI . '/assets/js/main.js',
		array(),
		ktuehk_asset_version( 'assets/js/main.js' ),
		array(
			'in_footer' => true,
			'strategy'  => 'defer',
		)
	);
	wp_localize_script(
		'ktuehk',
		'ktuehkL10n',
		array(
			'copy'        => __( 'Kopyala', 'ktuehk' ),
			'copied'      => __( 'Kopyalandı', 'ktuehk' ),
			'linkCopied'  => __( 'Bağlantı kopyalandı', 'ktuehk' ),
			'close'       => __( 'Kapat', 'ktuehk' ),
			'viewer'      => __( 'Görsel görüntüleyici', 'ktuehk' ),
			'previous'    => __( 'Önceki görsel', 'ktuehk' ),
			'next'        => __( 'Sonraki görsel', 'ktuehk' ),
			'openMenu'    => __( 'Menüyü aç', 'ktuehk' ),
			'closeMenu'   => __( 'Menüyü kapat', 'ktuehk' ),
		)
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( ktuehk_needs_math() ) {
		$katex = KTUEHK_URI . '/assets/vendor/katex/';
		wp_enqueue_style( 'katex', $katex . 'katex.min.css', array(), '0.19.0' );
		wp_enqueue_script(
			'katex',
			$katex . 'katex.min.js',
			array(),
			'0.19.0',
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
		wp_enqueue_script(
			'katex-auto-render',
			$katex . 'auto-render.min.js',
			array( 'katex' ),
			'0.19.0',
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
		wp_add_inline_script(
			'katex-auto-render',
			"document.addEventListener('DOMContentLoaded',function(){var c=document.querySelectorAll('.prose');c.forEach(function(el){window.renderMathInElement&&renderMathInElement(el,{delimiters:[{left:'$$',right:'$$',display:true},{left:'\\\\[',right:'\\\\]',display:true},{left:'\\\\(',right:'\\\\)',display:false}],ignoredTags:['script','noscript','style','textarea','pre','code'],throwOnError:false});});});"
		);
	}
}
add_action( 'wp_enqueue_scripts', 'ktuehk_enqueue_assets' );

/**
 * Whether the current single view contains LaTeX formulas
 * ($$…$$, \[…\] or \(…\)). Skipped when another math plugin is active.
 *
 * @return bool
 */
function ktuehk_needs_math() {
	if ( ! is_singular() ) {
		return false;
	}
	$other_plugin = wp_script_is( 'mathjax', 'registered' ) || wp_script_is( 'katex', 'registered' ) || defined( 'MATHJAX_PLUGIN_VERSION' ) || class_exists( 'WP_QuickLaTeX' );
	if ( $other_plugin ) {
		return false;
	}
	$post    = get_queried_object();
	$content = $post instanceof WP_Post ? $post->post_content : '';
	foreach ( array( 'surec', 'sonuclar', 'problem', 'amac' ) as $field ) {
		$content .= ' ' . ktuehk_field( $field, $post );
	}
	$has_math = (bool) preg_match( '/\$\$.+?\$\$|\\\\\[.+?\\\\\]|\\\\\(.+?\\\\\)/s', $content );
	return (bool) apply_filters( 'ktuehk_needs_math', $has_math, $post );
}

/**
 * Preload the main font file and the tiny Turkish subset (Ğ ğ İ Ş ş) so
 * text renders in the final font without a visible swap.
 */
function ktuehk_preload_fonts() {
	$fonts = array(
		'assets/fonts/inter-latin-wght-normal.woff2',
		'assets/fonts/inter-tr-wght-normal.woff2',
	);
	foreach ( $fonts as $font ) {
		printf( '<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n", esc_url( KTUEHK_URI . '/' . $font ) );
	}
}
add_action( 'wp_head', 'ktuehk_preload_fonts', 1 );

/**
 * Replace the "no-js" class as early as possible (used for the mobile menu
 * fallback when JavaScript is disabled).
 */
function ktuehk_js_class() {
	echo "<script>document.documentElement.classList.replace('no-js','js');</script>\n";
}
add_action( 'wp_head', 'ktuehk_js_class', 0 );

/**
 * Theme color for mobile browser UI.
 */
function ktuehk_theme_color() {
	$color = 'blue' === ktuehk_header_style() ? '#0a3069' : '#ffffff';
	echo '<meta name="theme-color" content="' . esc_attr( $color ) . '">' . "\n";
}
add_action( 'wp_head', 'ktuehk_theme_color', 1 );
