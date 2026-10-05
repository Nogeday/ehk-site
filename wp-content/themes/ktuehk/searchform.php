<?php
/**
 * Search form.
 *
 * Optional $args: 'aria_label', 'post_type' (limit to one content type),
 * 'placeholder', 'size' (default|large).
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

$ktuehk_args        = isset( $args ) && is_array( $args ) ? $args : array();
$ktuehk_id          = wp_unique_id( 'search-' );
$ktuehk_post_type   = isset( $ktuehk_args['post_type'] ) ? $ktuehk_args['post_type'] : '';
$ktuehk_placeholder = isset( $ktuehk_args['placeholder'] ) ? $ktuehk_args['placeholder'] : __( 'Yazı, proje veya etkinlik ara…', 'ktuehk' );
$ktuehk_size        = isset( $ktuehk_args['size'] ) ? $ktuehk_args['size'] : 'default';
?>
<form role="search" method="get" class="search-form search-form--<?php echo esc_attr( $ktuehk_size ); ?>" action="<?php echo esc_url( home_url( '/' ) ); ?>"<?php echo ! empty( $ktuehk_args['aria_label'] ) ? ' aria-label="' . esc_attr( $ktuehk_args['aria_label'] ) . '"' : ''; ?>>
	<label class="screen-reader-text" for="<?php echo esc_attr( $ktuehk_id ); ?>"><?php esc_html_e( 'Aranacak kelime', 'ktuehk' ); ?></label>
	<span class="search-form__icon"><?php echo ktuehk_icon( 'search', 20 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
	<input type="search" id="<?php echo esc_attr( $ktuehk_id ); ?>" class="search-form__input" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php echo esc_attr( $ktuehk_placeholder ); ?>" autocomplete="off" required>
	<?php if ( $ktuehk_post_type ) : ?>
		<input type="hidden" name="post_type" value="<?php echo esc_attr( $ktuehk_post_type ); ?>">
	<?php endif; ?>
	<button type="submit" class="btn btn--primary search-form__submit"><?php esc_html_e( 'Ara', 'ktuehk' ); ?></button>
</form>
