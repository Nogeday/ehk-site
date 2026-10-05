<?php
/**
 * Article card: image, category, title, short excerpt, date and reading
 * time. Use inside the loop.
 *
 * Args: heading (h2|h3), featured (bool: wide horizontal layout),
 * priority (bool: image above the fold), sizes (image sizes attribute).
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

$ktuehk_args = wp_parse_args(
	isset( $args ) ? $args : array(),
	array(
		'heading'  => 'h3',
		'featured' => false,
		'priority' => false,
		'sizes'    => '(min-width: 1200px) 380px, (min-width: 640px) 50vw, 100vw',
	)
);
$ktuehk_tag  = in_array( $ktuehk_args['heading'], array( 'h2', 'h3' ), true ) ? $ktuehk_args['heading'] : 'h3';
$ktuehk_cat  = ktuehk_primary_term( 'category' );
?>
<article <?php post_class( 'card card--post' . ( $ktuehk_args['featured'] ? ' card--featured' : '' ) ); ?>>
	<?php
	echo ktuehk_media( // phpcs:ignore WordPress.Security.EscapeOutput
		array(
			'icon'     => 'file-text',
			'priority' => $ktuehk_args['priority'],
			'sizes'    => $ktuehk_args['featured'] ? '(min-width: 1024px) 640px, 100vw' : $ktuehk_args['sizes'],
			'size'     => $ktuehk_args['featured'] ? 'large' : 'medium_large',
		)
	);
	?>
	<div class="card__body">
		<?php if ( $ktuehk_cat ) : ?>
			<p class="card__badges"><span class="badge badge--blue"><?php echo esc_html( $ktuehk_cat->name ); ?></span></p>
		<?php endif; ?>
		<<?php echo esc_html( $ktuehk_tag ); ?> class="card__title">
			<a href="<?php the_permalink(); ?>" class="card__link"><?php the_title(); ?></a>
		</<?php echo esc_html( $ktuehk_tag ); ?>>
		<p class="card__excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
		<div class="card__footer">
			<?php ktuehk_post_meta( $ktuehk_args['featured'] ? array( 'author', 'date', 'reading' ) : array( 'date', 'reading_short' ) ); ?>
		</div>
	</div>
</article>
