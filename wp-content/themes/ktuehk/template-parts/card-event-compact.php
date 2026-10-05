<?php
/**
 * Compact event card (home, related events): date tile, type, title,
 * time and location. Use inside the loop.
 *
 * Args: heading (h2|h3).
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

$ktuehk_args     = wp_parse_args( isset( $args ) ? $args : array(), array( 'heading' => 'h3' ) );
$ktuehk_tag      = in_array( $ktuehk_args['heading'], array( 'h2', 'h3' ), true ) ? $ktuehk_args['heading'] : 'h3';
$ktuehk_date     = ktuehk_event_date();
$ktuehk_type     = ktuehk_primary_term( 'ehk_etkinlik_turu' );
$ktuehk_upcoming = ktuehk_is_upcoming();
$ktuehk_time     = ktuehk_event_time_range();
$ktuehk_place    = ktuehk_field( 'konum' );
$ktuehk_online   = '1' === ktuehk_field( 'cevrimici' );
?>
<article <?php post_class( 'card card--event-compact ' . ( $ktuehk_upcoming ? 'is-upcoming' : 'is-past' ) ); ?>>
	<div class="card__body">
		<div class="ecard__head">
			<?php if ( $ktuehk_date ) : ?>
				<time class="date-tile" datetime="<?php echo esc_attr( $ktuehk_date['iso'] ); ?>">
					<span class="date-tile__day"><?php echo esc_html( $ktuehk_date['day'] ); ?></span>
					<span class="date-tile__month"><?php echo esc_html( $ktuehk_date['month'] ); ?></span>
					<span class="date-tile__year"><?php echo esc_html( $ktuehk_date['year'] ); ?></span>
				</time>
			<?php endif; ?>
			<div class="ecard__titlebox">
				<?php if ( $ktuehk_type || ! $ktuehk_upcoming ) : ?>
					<p class="card__badges">
						<?php if ( $ktuehk_type ) : ?>
							<span class="badge badge--blue"><?php echo esc_html( $ktuehk_type->name ); ?></span>
						<?php endif; ?>
						<?php if ( ! $ktuehk_upcoming && $ktuehk_date ) : ?>
							<span class="badge badge--gray"><?php esc_html_e( 'Gerçekleşti', 'ktuehk' ); ?></span>
						<?php endif; ?>
					</p>
				<?php endif; ?>
				<<?php echo esc_html( $ktuehk_tag ); ?> class="card__title">
					<a href="<?php the_permalink(); ?>" class="card__link"><?php the_title(); ?></a>
				</<?php echo esc_html( $ktuehk_tag ); ?>>
			</div>
		</div>
		<div class="ecard__foot">
			<ul class="meta meta--stack">
				<?php if ( $ktuehk_time ) : ?>
					<li class="meta__item"><?php echo ktuehk_icon( 'clock', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $ktuehk_time ); ?></li>
				<?php endif; ?>
				<?php if ( $ktuehk_place || $ktuehk_online ) : ?>
					<li class="meta__item"><?php echo ktuehk_icon( $ktuehk_online ? 'globe' : 'map-pin', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $ktuehk_place ? $ktuehk_place : __( 'Çevrim içi', 'ktuehk' ) ); ?></li>
				<?php endif; ?>
			</ul>
			<span class="ecard__arrow" aria-hidden="true"><?php echo ktuehk_icon( 'arrow-right', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?></span>
		</div>
	</div>
</article>
