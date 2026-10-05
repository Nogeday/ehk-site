<?php
/**
 * Event card with image (events archive). Use inside the loop.
 *
 * Args: heading (h2|h3), priority (bool).
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

$ktuehk_args     = wp_parse_args(
	isset( $args ) ? $args : array(),
	array(
		'heading'  => 'h3',
		'priority' => false,
	)
);
$ktuehk_tag      = in_array( $ktuehk_args['heading'], array( 'h2', 'h3' ), true ) ? $ktuehk_args['heading'] : 'h3';
$ktuehk_date     = ktuehk_event_date();
$ktuehk_type     = ktuehk_primary_term( 'ehk_etkinlik_turu' );
$ktuehk_upcoming = ktuehk_is_upcoming();
$ktuehk_time     = ktuehk_event_time_range();
$ktuehk_place    = ktuehk_field( 'konum' );
$ktuehk_online   = '1' === ktuehk_field( 'cevrimici' );
$ktuehk_image    = (int) get_post_thumbnail_id();
$ktuehk_poster   = absint( ktuehk_field( 'afis' ) );
?>
<article <?php post_class( 'card card--event ' . ( $ktuehk_upcoming ? 'is-upcoming' : 'is-past' ) ); ?>>
	<?php
	echo ktuehk_media( // phpcs:ignore WordPress.Security.EscapeOutput
		array(
			'image_id' => $ktuehk_image ? $ktuehk_image : $ktuehk_poster,
			'contain'  => ! $ktuehk_image && $ktuehk_poster,
			'icon'     => 'calendar-days',
			'priority' => $ktuehk_args['priority'],
		)
	);
	?>
	<div class="card__body card__body--event">
		<?php if ( $ktuehk_date ) : ?>
			<time class="date-tile" datetime="<?php echo esc_attr( $ktuehk_date['iso'] ); ?>">
				<span class="date-tile__day"><?php echo esc_html( $ktuehk_date['day'] ); ?></span>
				<span class="date-tile__month"><?php echo esc_html( $ktuehk_date['month'] ); ?></span>
				<span class="date-tile__year"><?php echo esc_html( $ktuehk_date['year'] ); ?></span>
			</time>
		<?php endif; ?>
		<div class="card__content">
			<p class="card__badges">
				<?php if ( $ktuehk_upcoming ) : ?>
					<span class="badge badge--green"><?php esc_html_e( 'Yaklaşan', 'ktuehk' ); ?></span>
				<?php endif; ?>
				<?php if ( $ktuehk_type ) : ?>
					<span class="badge badge--blue"><?php echo esc_html( $ktuehk_type->name ); ?></span>
				<?php endif; ?>
			</p>
			<<?php echo esc_html( $ktuehk_tag ); ?> class="card__title">
				<a href="<?php the_permalink(); ?>" class="card__link"><?php the_title(); ?></a>
			</<?php echo esc_html( $ktuehk_tag ); ?>>
			<p class="card__excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
			<ul class="meta">
				<?php if ( $ktuehk_time ) : ?>
					<li class="meta__item"><?php echo ktuehk_icon( 'clock', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $ktuehk_time ); ?></li>
				<?php endif; ?>
				<?php if ( $ktuehk_place || $ktuehk_online ) : ?>
					<li class="meta__item"><?php echo ktuehk_icon( $ktuehk_online ? 'globe' : 'map-pin', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $ktuehk_place ? $ktuehk_place : __( 'Çevrim içi', 'ktuehk' ) ); ?></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
</article>
