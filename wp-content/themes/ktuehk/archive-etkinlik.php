<?php
/**
 * Events archive: upcoming events as prominent rows, past events as a
 * paginated grid below. Also used for event type archives.
 *
 * @package KTUEHK
 */

defined( 'ABSPATH' ) || exit;

get_header();

global $wp_query;
$ktuehk_type    = ktuehk_event_type();
$ktuehk_archive = (string) get_post_type_archive_link( $ktuehk_type );
$ktuehk_term    = is_tax( 'ehk_etkinlik_turu' ) ? get_queried_object() : null;
$ktuehk_tur     = (string) get_query_var( 'tur' );
$ktuehk_period  = sanitize_key( (string) get_query_var( 'donem' ) );

get_template_part(
	'template-parts/page-header',
	null,
	array(
		'eyebrow' => $ktuehk_term ? __( 'Etkinlik türü', 'ktuehk' ) : __( 'Takvim', 'ktuehk' ),
		'title'   => $ktuehk_term ? $ktuehk_term->name : __( 'Etkinlikler', 'ktuehk' ),
		'desc'    => $ktuehk_term && $ktuehk_term->description ? wp_strip_all_tags( $ktuehk_term->description ) : ktuehk_mod( 'intro_events' ),
	)
);

$ktuehk_types = get_terms(
	array(
		'taxonomy'   => 'ehk_etkinlik_turu',
		'hide_empty' => true,
	)
);
?>
<div class="section section--tight">
	<div class="container">
		<?php if ( $ktuehk_types && ! is_wp_error( $ktuehk_types ) && count( $ktuehk_types ) > 1 ) : ?>
			<nav class="chips-wrap" aria-label="<?php esc_attr_e( 'Etkinlik türleri', 'ktuehk' ); ?>">
				<ul class="chips">
					<li><a class="chip<?php echo $ktuehk_tur ? '' : ' is-active'; ?>" href="<?php echo esc_url( $ktuehk_archive ); ?>"<?php echo $ktuehk_tur ? '' : ' aria-current="page"'; ?>><?php esc_html_e( 'Tümü', 'ktuehk' ); ?></a></li>
					<?php foreach ( $ktuehk_types as $ktuehk_item ) : ?>
						<?php $ktuehk_active = $ktuehk_tur === $ktuehk_item->slug; ?>
						<li>
							<a class="chip<?php echo $ktuehk_active ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_term_link( $ktuehk_item ) ); ?>"<?php echo $ktuehk_active ? ' aria-current="page"' : ''; ?>>
								<?php echo esc_html( $ktuehk_item->name ); ?> <span class="chip__count"><?php echo esc_html( number_format_i18n( $ktuehk_item->count ) ); ?></span>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</nav>
		<?php endif; ?>

		<?php
		// Upcoming events (first page only, unless the main query already lists them).
		if ( 'yaklasan' !== $ktuehk_period && ! is_paged() && function_exists( 'ktuehk_upcoming_events_args' ) ) :
			$ktuehk_upcoming_args = array(
				'posts_per_page' => 20,
				'no_found_rows'  => true,
			);
			if ( $ktuehk_tur ) {
				$ktuehk_upcoming_args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery
					array(
						'taxonomy' => 'ehk_etkinlik_turu',
						'field'    => 'slug',
						'terms'    => $ktuehk_tur,
					),
				);
			}
			$ktuehk_upcoming = new WP_Query( ktuehk_upcoming_events_args( $ktuehk_upcoming_args ) );
			?>
			<section class="events-block events-block--upcoming" aria-labelledby="upcoming-title">
				<h2 class="events-block__title" id="upcoming-title">
					<?php echo ktuehk_icon( 'calendar-clock', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
					<?php esc_html_e( 'Yaklaşan etkinlikler', 'ktuehk' ); ?>
				</h2>
				<?php if ( $ktuehk_upcoming->have_posts() ) : ?>
					<div class="event-rows">
						<?php
						while ( $ktuehk_upcoming->have_posts() ) :
							$ktuehk_upcoming->the_post();
							get_template_part( 'template-parts/event-row' );
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				<?php else : ?>
					<p class="events-block__empty"><?php echo ktuehk_icon( 'info', 18 ); // phpcs:ignore WordPress.Security.EscapeOutput ?><?php esc_html_e( 'Şu anda planlanmış yaklaşan bir etkinlik yok. Yeni etkinlikler duyurulduğunda burada yer alacak.', 'ktuehk' ); ?></p>
				<?php endif; ?>
			</section>
		<?php endif; ?>

		<section class="events-block events-block--past" aria-labelledby="past-title">
			<h2 class="events-block__title" id="past-title">
				<?php echo ktuehk_icon( 'yaklasan' === $ktuehk_period ? 'calendar-clock' : 'history', 22 ); // phpcs:ignore WordPress.Security.EscapeOutput ?>
				<?php 'yaklasan' === $ktuehk_period ? esc_html_e( 'Yaklaşan etkinlikler', 'ktuehk' ) : esc_html_e( 'Geçmiş etkinlikler', 'ktuehk' ); ?>
			</h2>
			<?php if ( have_posts() ) : ?>
				<div class="grid grid--3">
					<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/card', 'event', array( 'heading' => 'h3' ) );
					endwhile;
					?>
				</div>
				<?php ktuehk_pagination(); ?>
			<?php else : ?>
				<?php
				get_template_part(
					'template-parts/empty-state',
					null,
					array(
						'icon'       => 'calendar-days',
						'title'      => __( 'Henüz geçmiş etkinlik yok', 'ktuehk' ),
						'admin_link' => admin_url( 'post-new.php?post_type=' . $ktuehk_type ),
						'admin_text' => __( 'Yeni etkinlik ekle', 'ktuehk' ),
					)
				);
				?>
			<?php endif; ?>
		</section>
	</div>
</div>
<?php
get_footer();
