<?php
/**
 * Project card. Use inside the loop.
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
$ktuehk_areas   = get_the_terms( get_the_ID(), 'ehk_alan' );
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
		<?php if ( $ktuehk_program || $ktuehk_year ) : ?>
			<p class="card__eyebrow">
				<?php if ( $ktuehk_program ) : ?>
					<span class="badge badge--blue"><?php echo esc_html( $ktuehk_program->name ); ?></span>
				<?php endif; ?>
				<?php if ( $ktuehk_year ) : ?>
					<span class="card__year"><?php echo esc_html( $ktuehk_year ); ?></span>
				<?php endif; ?>
			</p>
		<?php endif; ?>
		<<?php echo esc_html( $ktuehk_tag ); ?> class="card__title">
			<a href="<?php the_permalink(); ?>" class="card__link"><?php the_title(); ?></a>
		</<?php echo esc_html( $ktuehk_tag ); ?>>
		<p class="card__excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
		<?php if ( $ktuehk_award ) : ?>
			<p class="card__award"><?php echo ktuehk_icon( 'trophy', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $ktuehk_award ); ?></p>
		<?php endif; ?>
		<div class="card__footer">
			<?php if ( $ktuehk_status ) : ?>
				<span class="status status--<?php echo esc_attr( $ktuehk_status['key'] ); ?>"><?php echo esc_html( $ktuehk_status['label'] ); ?></span>
			<?php endif; ?>
			<?php if ( $ktuehk_areas && ! is_wp_error( $ktuehk_areas ) ) : ?>
				<ul class="tag-list" aria-label="<?php esc_attr_e( 'Alanlar', 'ktuehk' ); ?>">
					<?php foreach ( array_slice( $ktuehk_areas, 0, 2 ) as $ktuehk_area ) : ?>
						<li class="tag"><?php echo esc_html( $ktuehk_area->name ); ?></li>
					<?php endforeach; ?>
					<?php if ( count( $ktuehk_areas ) > 2 ) : ?>
						<li class="tag tag--more">+<?php echo esc_html( count( $ktuehk_areas ) - 2 ); ?></li>
					<?php endif; ?>
				</ul>
			<?php endif; ?>
		</div>
	</div>
</article>
