<?php
/**
 * Article content helpers: heading anchors, table of contents and
 * horizontally scrollable tables for technical articles.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

/**
 * Render the current post content and collect a table of contents.
 *
 * Must be called inside the loop. Runs the regular "the_content" filters,
 * so plugins that hook into content keep working.
 *
 * @param bool $anchors Add ids/anchor links to h2/h3 headings.
 * @return array{html:string,toc:array<int,array{level:int,id:string,text:string}>}
 */
function ktuehk_prepare_content( $anchors = true ) {
	$content = apply_filters( 'the_content', get_the_content() ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals -- core hook.
	$content = str_replace( ']]>', ']]&gt;', $content );
	$toc     = array();

	if ( $anchors ) {
		$used    = array();
		$content = preg_replace_callback(
			'/<h([23])([^>]*)>(.*?)<\/h\1>/is',
			static function ( $match ) use ( &$toc, &$used ) {
				$level = (int) $match[1];
				$attrs = $match[2];
				$inner = $match[3];
				$text  = trim( html_entity_decode( wp_strip_all_tags( $inner ), ENT_QUOTES, 'UTF-8' ) );
				if ( '' === $text ) {
					return $match[0];
				}
				if ( preg_match( '/\sid=(["\'])(.*?)\1/i', $attrs, $id_match ) ) {
					$id = $id_match[2];
				} else {
					$base = sanitize_title( $text );
					$base = '' !== $base ? $base : 'bolum';
					$id   = $base;
					$n    = 2;
					while ( isset( $used[ $id ] ) ) {
						$id = $base . '-' . $n++;
					}
					$attrs .= ' id="' . esc_attr( $id ) . '"';
				}
				$used[ $id ] = true;
				$toc[]       = array(
					'level' => $level,
					'id'    => $id,
					'text'  => $text,
				);
				$anchor = sprintf(
					'<a class="heading-anchor" href="#%1$s" aria-label="%2$s">#</a>',
					esc_attr( $id ),
					/* translators: %s: heading text */
					esc_attr( sprintf( __( '"%s" bölümünün bağlantısı', 'ktuehk' ), $text ) )
				);
				return '<h' . $level . $attrs . '>' . $inner . $anchor . '</h' . $level . '>';
			},
			$content
		);
	}

	// Wide tables scroll inside their own box instead of breaking the layout.
	$content = preg_replace( '/<table\b/i', '<div class="table-scroll" tabindex="0" role="region" aria-label="' . esc_attr__( 'Kaydırılabilir tablo', 'ktuehk' ) . '"><table', $content );
	$content = preg_replace( '/<\/table>/i', '</table></div>', $content );

	return array(
		'html' => (string) $content,
		'toc'  => (int) apply_filters( 'ktuehk_toc_min_headings', 3 ) <= count( $toc ) ? $toc : array(),
	);
}

/**
 * Table of contents markup.
 *
 * @param array $toc Items from ktuehk_prepare_content().
 */
function ktuehk_toc( $toc ) {
	if ( ! $toc ) {
		return;
	}
	echo '<nav class="toc" aria-labelledby="toc-title">';
	echo '<details class="toc__details" open>';
	echo '<summary class="toc__title" id="toc-title">' . ktuehk_icon( 'list-tree', 16 ) . esc_html__( 'Bu yazıda', 'ktuehk' ) . '</summary>'; // phpcs:ignore WordPress.Security.EscapeOutput
	echo '<ol class="toc__list">';
	foreach ( $toc as $item ) {
		printf(
			'<li class="toc__item toc__item--h%1$d"><a href="#%2$s">%3$s</a></li>',
			(int) $item['level'],
			esc_attr( $item['id'] ),
			esc_html( $item['text'] )
		);
	}
	echo '</ol></details></nav>';
}

/**
 * Format a plain-text field (problem, amaç, süreç ...) as paragraphs.
 *
 * @param string $text Stored text (may contain basic HTML).
 * @return string
 */
function ktuehk_format_text( $text ) {
	return wpautop( wp_kses_post( $text ) );
}
