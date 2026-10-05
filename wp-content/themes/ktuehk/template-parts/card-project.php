<?php
/**
 * Project card: image, program badge, title, summary and a
 * year · area · status row. Use inside the loop.
 *
 * Args: heading (h2|h3), priority (bool).
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

$ktuehk_args    = wp_parse_args(
	isset( $args ) ? $args : array(),
	array(
		'heading'  => 'h3',
		'priority' => false,
	)
);
$ktuehk_tag     = in_array( $ktuehk_args['heading'], array( 'h2', 'h3' ), true ) ? $ktuehk_args['heading'] : 'h3';
$ktuehk_program = ktuehk_primary_term( 'ehk_program' );
$ktuehk_area    = ktuehk_primary_term( 'ehk_alan' );
$ktuehk_year    = ktuehk_field( 'yil' );
$ktuehk_status  = function_exists( 'ktuehk_project_status' ) ? ktuehk_project_status() : null;
$ktuehk_award   = ktuehk_field( 'basari' );
?>
<article <?php post_class( 'card card--project' ); ?>>
	<?php
	echo ktuehk_media( // phpcs:ignore WordPress.Security.EscapeOutput
		array(
			'icon'     => 'cpu',
			'priority' => $ktuehk_args['priority'],
		)
	);
	?>
	<div class="card__body">
		<?php if ( $ktuehk_program ) : ?>
			<p class="card__badges"><span class="badge badge--solid"><?php echo esc_html( $ktuehk_program->name ); ?></span></p>
		<?php endif; ?>
		<<?php echo esc_html( $ktuehk_tag ); ?> class="card__title">
			<a href="<?php the_permalink(); ?>" class="card__link"><?php the_title(); ?></a>
		</<?php echo esc_html( $ktuehk_tag ); ?>>
		<p class="card__excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
		<?php if ( $ktuehk_award ) : ?>
			<p class="card__award"><?php echo ktuehk_icon( 'trophy', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $ktuehk_award ); ?></p>
		<?php endif; ?>
		<?php if ( $ktuehk_year || $ktuehk_area || $ktuehk_status ) : ?>
			<div class="card__footer">
				<ul class="meta">
					<?php if ( $ktuehk_year ) : ?>
						<li class="meta__item"><?php echo ktuehk_icon( 'calendar', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="screen-reader-text"><?php esc_html_e( 'Yıl:', 'ktuehk' ); ?> </span><?php echo esc_html( $ktuehk_year ); ?></li>
					<?php endif; ?>
					<?php if ( $ktuehk_area ) : ?>
						<li class="meta__item"><?php echo ktuehk_icon( 'cpu', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><span class="screen-reader-text"><?php esc_html_e( 'Kategori:', 'ktuehk' ); ?> </span><?php echo esc_html( $ktuehk_area->name ); ?></li>
					<?php endif; ?>
				</ul>
				<?php if ( $ktuehk_status ) : ?>
					<span class="pill pill--<?php echo esc_attr( $ktuehk_status['key'] ); ?>"><?php echo esc_html( $ktuehk_status['label'] ); ?></span>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</article>
