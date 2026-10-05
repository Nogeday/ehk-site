<?php
/**
 * Upcoming event row (wide layout with date block and actions).
 * Use inside the loop.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

$ktuehk_date   = ktuehk_event_date();
$ktuehk_type   = ktuehk_primary_term( 'ehk_etkinlik_turu' );
$ktuehk_place  = ktuehk_field( 'konum' );
$ktuehk_online = '1' === ktuehk_field( 'cevrimici' );
$ktuehk_signup = ktuehk_field( 'kayit' );
?>
<article <?php post_class( 'event-row' ); ?>>
	<?php if ( $ktuehk_date ) : ?>
		<time class="event-row__date" datetime="<?php echo esc_attr( $ktuehk_date['iso'] ); ?>">
			<span class="event-row__day"><?php echo esc_html( $ktuehk_date['day'] ); ?></span>
			<span class="event-row__month"><?php echo esc_html( $ktuehk_date['month'] . ' ' . $ktuehk_date['year'] ); ?></span>
			<span class="event-row__weekday"><?php echo esc_html( $ktuehk_date['weekday'] ); ?></span>
		</time>
	<?php endif; ?>
	<div class="event-row__body">
		<p class="card__eyebrow">
			<span class="badge badge--green"><?php esc_html_e( 'Yaklaşan', 'ktuehk' ); ?></span>
			<?php if ( $ktuehk_type ) : ?>
				<span class="badge"><?php echo esc_html( $ktuehk_type->name ); ?></span>
			<?php endif; ?>
		</p>
		<h3 class="event-row__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<p class="event-row__excerpt"><?php echo esc_html( wp_strip_all_tags( get_the_excerpt() ) ); ?></p>
		<ul class="meta">
			<?php if ( $ktuehk_date ) : ?>
				<li class="meta__item"><?php echo ktuehk_icon( 'clock', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( ktuehk_event_when() ); ?></li>
			<?php endif; ?>
			<?php if ( $ktuehk_place || $ktuehk_online ) : ?>
				<li class="meta__item"><?php echo ktuehk_icon( $ktuehk_online ? 'globe' : 'map-pin', 16 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php echo esc_html( $ktuehk_place ? $ktuehk_place : __( 'Çevrim içi', 'ktuehk' ) ); ?></li>
			<?php endif; ?>
		</ul>
	</div>
	<div class="event-row__actions">
		<?php if ( $ktuehk_signup ) : ?>
			<a class="btn btn--primary btn--sm" href="<?php echo esc_url( $ktuehk_signup ); ?>" target="_blank" rel="noopener">
				<?php esc_html_e( 'Kayıt ol', 'ktuehk' ); ?>
				<span class="screen-reader-text"><?php echo esc_html( ': ' . get_the_title() ); ?></span>
			</a>
		<?php endif; ?>
		<a class="btn btn--outline btn--sm" href="<?php the_permalink(); ?>">
			<?php esc_html_e( 'Detaylar', 'ktuehk' ); ?>
			<span class="screen-reader-text"><?php echo esc_html( ': ' . get_the_title() ); ?></span>
		</a>
	</div>
</article>
