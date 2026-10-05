<?php
/**
 * Author avatar: initials by default (consistent look, no third-party
 * request). Return true from the "ktuehk_use_gravatar" filter to use the
 * WordPress / Gravatar avatar for registered users instead.
 *
 * Args: author (from ktuehk_display_author()), size (px).
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

$ktuehk_author = isset( $args['author'] ) ? $args['author'] : array();
$ktuehk_size   = isset( $args['size'] ) ? (int) $args['size'] : 44;
$ktuehk_name   = isset( $ktuehk_author['name'] ) ? (string) $ktuehk_author['name'] : '';

if ( ! empty( $ktuehk_author['user_id'] ) && get_option( 'show_avatars' ) && apply_filters( 'ktuehk_use_gravatar', false ) ) {
	$ktuehk_avatar = get_avatar(
		$ktuehk_author['user_id'],
		$ktuehk_size,
		'',
		'',
		array(
			'class'   => 'avatar-img',
			'loading' => 'lazy',
		)
	);
	if ( $ktuehk_avatar ) {
		echo $ktuehk_avatar; // phpcs:ignore WordPress.Security.EscapeOutput
		return;
	}
}

$ktuehk_words    = preg_split( '/\s+/u', trim( $ktuehk_name ), -1, PREG_SPLIT_NO_EMPTY );
$ktuehk_initials = '';
foreach ( array_slice( (array) $ktuehk_words, 0, 2 ) as $ktuehk_word ) {
	$ktuehk_letter    = mb_substr( $ktuehk_word, 0, 1 );
	$ktuehk_initials .= function_exists( 'mb_strtoupper' ) ? mb_strtoupper( $ktuehk_letter ) : strtoupper( $ktuehk_letter );
}
?>
<span class="avatar-initials" style="--size:<?php echo (int) $ktuehk_size; ?>px" aria-hidden="true"><?php echo esc_html( $ktuehk_initials ? $ktuehk_initials : 'E' ); ?></span>
