<?php
/**
 * Empty state message.
 *
 * Args: icon, title, text, link, link_text, admin_link, admin_text.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

$ktuehk_args = wp_parse_args(
	isset( $args ) ? $args : array(),
	array(
		'icon'       => 'search',
		'title'      => __( 'Henüz içerik yok', 'ktuehk' ),
		'text'       => '',
		'link'       => '',
		'link_text'  => '',
		'admin_link' => '',
		'admin_text' => '',
	)
);
?>
<div class="empty">
	<span class="empty__icon"><?php echo ktuehk_icon( $ktuehk_args['icon'], 28 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
	<p class="empty__title"><?php echo esc_html( $ktuehk_args['title'] ); ?></p>
	<?php if ( $ktuehk_args['text'] ) : ?>
		<p class="empty__text"><?php echo esc_html( $ktuehk_args['text'] ); ?></p>
	<?php endif; ?>
	<?php if ( $ktuehk_args['link'] || ( $ktuehk_args['admin_link'] && current_user_can( 'edit_posts' ) ) ) : ?>
		<p class="empty__actions">
			<?php if ( $ktuehk_args['link'] ) : ?>
				<a class="btn btn--outline btn--sm" href="<?php echo esc_url( $ktuehk_args['link'] ); ?>"><?php echo esc_html( $ktuehk_args['link_text'] ); ?></a>
			<?php endif; ?>
			<?php if ( $ktuehk_args['admin_link'] && current_user_can( 'edit_posts' ) ) : ?>
				<a class="btn btn--primary btn--sm" href="<?php echo esc_url( $ktuehk_args['admin_link'] ); ?>"><?php echo esc_html( $ktuehk_args['admin_text'] ); ?></a>
			<?php endif; ?>
		</p>
	<?php endif; ?>
</div>
