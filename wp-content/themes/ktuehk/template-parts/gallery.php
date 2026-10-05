<?php
/**
 * Image gallery with an accessible lightbox (links work without JS).
 *
 * Args: ids (int[]), title (string), id (heading id).
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

$ktuehk_ids = isset( $args['ids'] ) ? array_filter( array_map( 'absint', (array) $args['ids'] ) ) : array();
if ( ! $ktuehk_ids ) {
	return;
}
$ktuehk_group = wp_unique_id( 'gallery-' );
?>
<ul class="gallery-grid" data-lightbox-group="<?php echo esc_attr( $ktuehk_group ); ?>">
	<?php
	foreach ( $ktuehk_ids as $ktuehk_id ) :
		$ktuehk_full = wp_get_attachment_image_url( $ktuehk_id, 'full' );
		if ( ! $ktuehk_full ) {
			continue;
		}
		$ktuehk_caption = wp_get_attachment_caption( $ktuehk_id );
		$ktuehk_alt     = trim( (string) get_post_meta( $ktuehk_id, '_wp_attachment_image_alt', true ) );
		$ktuehk_alt     = '' !== $ktuehk_alt ? $ktuehk_alt : ( $ktuehk_caption ? $ktuehk_caption : get_the_title( $ktuehk_id ) );
		?>
		<li class="gallery-grid__item">
			<a href="<?php echo esc_url( wp_get_attachment_image_url( $ktuehk_id, 'large' ) ); ?>" data-lightbox data-caption="<?php echo esc_attr( $ktuehk_caption ); ?>">
				<?php
				echo wp_get_attachment_image(
					$ktuehk_id,
					'medium_large',
					false,
					array(
						'alt'      => $ktuehk_alt,
						'loading'  => 'lazy',
						'decoding' => 'async',
						'sizes'    => '(min-width: 1024px) 260px, (min-width: 640px) 33vw, 50vw',
					)
				);
				?>
			</a>
		</li>
	<?php endforeach; ?>
</ul>
